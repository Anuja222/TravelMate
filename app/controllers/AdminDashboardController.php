<?php

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Blog.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class AdminDashboardController {
    
    private $blogModel;
    private $db;

    public function __construct() {
        $this->blogModel = new BlogModel();
        $this->db = new DatabaseHelper();
    }

    /**
     * Display admin dashboard with full analytics
     */
    public function index() {
        SessionHelper::requireAdmin();

        // W-02: Log dashboard view using system error_log pattern
        $adminId = SessionHelper::getUserId() ?? 0;
        $adminName = SessionHelper::getUserName() ?? 'Admin';
        error_log("[ADMIN DASHBOARD] Admin #{$adminId} ({$adminName}) viewed dashboard at " . date('Y-m-d H:i:s'));

        // Core statistics
        $stats = $this->getDashboardStats();

        // Growth metrics (today, week, month)
        $growth = $this->getGrowthMetrics();

        // User distribution by role (for donut chart)
        $userDistribution = $this->getUserDistribution();

        // Registration chart data (last 12 months)
        $chartData = $this->getRegistrationChartData(12);

        // Recent registrations (last 10) — W-05: pre-format dates
        $recentUsers = $this->getRecentRegistrations(10);

        // Pending approvals across modules
        $pendingApprovals = $this->getPendingApprovals();

        // System health info
        $systemHealth = $this->getSystemHealth();

        // W-04: CSRF token for any future POST actions
        $csrfToken = $_SESSION['csrf_token'] ?? '';

        // W-06: Pass admin name from controller (not SessionHelper in view)
        $adminDisplayName = htmlspecialchars($adminName);

        // W-05: Pre-format today's date for display
        $todayFormatted = date('l, F j, Y');

        // W-01: Pre-format system health bytes in controller (private formatBytes)
        $diskFreeFormatted = self::formatBytes($systemHealth->diskFree ?? 0);
        $diskTotalFormatted = self::formatBytes($systemHealth->diskTotal ?? 0);
        $memoryUsageFormatted = self::formatBytes($systemHealth->memoryUsage ?? 0);

        // Load dashboard view
        require_once __DIR__ . '/../views/admin/dashboard.view.php';
    }

    /**
     * Get core dashboard statistics with growth percentages
     * C-01: All table queries wrapped with existence checks
     */
    private function getDashboardStats() {
        $stats = new stdClass();

        // Users — core table, always exists
        try {
            $stats->totalUsers = $this->getSafeCount('users');
            $stats->usersLastMonth = $this->getSafeCountLastMonth('users');
            $stats->activeUsers = $this->getActiveUserCount();
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error fetching user stats: " . $e->getMessage());
            $stats->totalUsers = 0;
            $stats->usersLastMonth = 0;
            $stats->activeUsers = 0;
        }

        // Accommodations — may not exist in fresh installs
        try {
            if ($this->tableExists('accommodations')) {
                $stats->totalAccommodations = $this->getSafeCount('accommodations');
                $stats->accommodationsLastMonth = $this->getSafeCountLastMonth('accommodations');
            } else {
                $stats->totalAccommodations = 0;
                $stats->accommodationsLastMonth = 0;
            }
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error fetching accommodation stats: " . $e->getMessage());
            $stats->totalAccommodations = 0;
            $stats->accommodationsLastMonth = 0;
        }

        // Vehicles — may not exist in fresh installs
        try {
            if ($this->tableExists('vehicles')) {
                $stats->totalVehicles = $this->getSafeCount('vehicles');
                $stats->vehiclesLastMonth = $this->getSafeCountLastMonth('vehicles');
            } else {
                $stats->totalVehicles = 0;
                $stats->vehiclesLastMonth = 0;
            }
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error fetching vehicle stats: " . $e->getMessage());
            $stats->totalVehicles = 0;
            $stats->vehiclesLastMonth = 0;
        }

        // Bookings — optional table
        try {
            if ($this->tableExists('bookings')) {
                $stats->totalBookings = $this->getSafeCount('bookings');
                $stats->bookingsLastMonth = $this->getSafeCountLastMonth('bookings');
            } else {
                $stats->totalBookings = 0;
                $stats->bookingsLastMonth = 0;
            }
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error fetching booking stats: " . $e->getMessage());
            $stats->totalBookings = 0;
            $stats->bookingsLastMonth = 0;
        }

        // Destinations
        try {
            if ($this->tableExists('destinations')) {
                $stats->totalDestinations = $this->getSafeCount('destinations');
            } else {
                $stats->totalDestinations = 0;
            }
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error fetching destination stats: " . $e->getMessage());
            $stats->totalDestinations = 0;
        }

        // Blogs
        try {
            $blogStats = $this->blogModel->getBlogStats();
            $stats->pendingBlogs = $blogStats->pending ?? 0;
            $stats->totalBlogs = $blogStats->total ?? 0;
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error fetching blog stats: " . $e->getMessage());
            $stats->pendingBlogs = 0;
            $stats->totalBlogs = 0;
        }

        // Percentage changes
        $stats->userChange = $this->calculatePercentageChange($stats->totalUsers, $stats->usersLastMonth);
        $stats->accommodationChange = $this->calculatePercentageChange($stats->totalAccommodations, $stats->accommodationsLastMonth);
        $stats->vehicleChange = $this->calculatePercentageChange($stats->totalVehicles, $stats->vehiclesLastMonth);
        $stats->bookingChange = $this->calculatePercentageChange($stats->totalBookings, $stats->bookingsLastMonth);

        return $stats;
    }

    /**
     * Get growth metrics: new users today, this week, this month
     * C-01: Safe table checks for accommodations/vehicles
     */
    private function getGrowthMetrics() {
        $metrics = new stdClass();
        $metrics->newToday = 0;
        $metrics->newThisWeek = 0;
        $metrics->newThisMonth = 0;
        $metrics->newAccommodationsMonth = 0;
        $metrics->newVehiclesMonth = 0;
        $metrics->newListingsMonth = 0;

        try {
            // New users today
            $result = $this->db->getRow("SELECT COUNT(*) as total FROM users WHERE DATE(created_at) = CURDATE()");
            $metrics->newToday = $result ? (int)$result->total : 0;

            // New users this week
            $result = $this->db->getRow("SELECT COUNT(*) as total FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
            $metrics->newThisWeek = $result ? (int)$result->total : 0;

            // New users this month
            $result = $this->db->getRow("SELECT COUNT(*) as total FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
            $metrics->newThisMonth = $result ? (int)$result->total : 0;
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error fetching user growth: " . $e->getMessage());
        }

        // New accommodations this month (safe check)
        try {
            if ($this->tableExists('accommodations') && $this->columnExists('accommodations', 'created_at')) {
                $result = $this->db->getRow("SELECT COUNT(*) as total FROM accommodations WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
                $metrics->newAccommodationsMonth = $result ? (int)$result->total : 0;
            }
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error fetching accommodation growth: " . $e->getMessage());
        }

        // New vehicles this month (safe check)
        try {
            if ($this->tableExists('vehicles') && $this->columnExists('vehicles', 'created_at')) {
                $result = $this->db->getRow("SELECT COUNT(*) as total FROM vehicles WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
                $metrics->newVehiclesMonth = $result ? (int)$result->total : 0;
            }
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error fetching vehicle growth: " . $e->getMessage());
        }

        $metrics->newListingsMonth = $metrics->newAccommodationsMonth + $metrics->newVehiclesMonth;

        return $metrics;
    }

    /**
     * Get user distribution by role for donut chart
     */
    private function getUserDistribution() {
        try {
            $result = $this->db->query("SELECT role, COUNT(*) as count FROM users GROUP BY role ORDER BY count DESC");
            
            $distribution = [];
            if ($result && is_array($result)) {
                foreach ($result as $row) {
                    $distribution[] = (object)[
                        'role' => $row->role,
                        'count' => (int)$row->count
                    ];
                }
            }
            return $distribution;
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error fetching user distribution: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get monthly registration data for bar chart (last N months)
     */
    private function getRegistrationChartData($months = 12) {
        $chartData = [];
        
        try {
            for ($i = $months - 1; $i >= 0; $i--) {
                $monthStart = date('Y-m-01', strtotime("-{$i} months"));
                $monthEnd = date('Y-m-t', strtotime("-{$i} months"));
                $monthLabel = date('M', strtotime("-{$i} months"));
                $yearLabel = date('Y', strtotime("-{$i} months"));

                $result = $this->db->getRow(
                    "SELECT COUNT(*) as total FROM users WHERE created_at >= :start AND created_at <= :end",
                    ['start' => $monthStart, 'end' => $monthEnd . ' 23:59:59']
                );

                $chartData[] = (object)[
                    'month' => $monthLabel,
                    'year' => $yearLabel,
                    'count' => $result ? (int)$result->total : 0
                ];
            }
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error fetching chart data: " . $e->getMessage());
        }

        return $chartData;
    }

    /**
     * Get recent user registrations
     * W-05: Pre-format dates in controller
     */
    private function getRecentRegistrations($limit = 10) {
        try {
            $result = $this->db->query(
                "SELECT id, first_name, last_name, email, role, status, created_at 
                 FROM users 
                 ORDER BY created_at DESC 
                 LIMIT {$limit}"
            );

            $users = ($result && is_array($result)) ? $result : [];

            // W-05: Pre-format dates for each user
            foreach ($users as $user) {
                $user->formattedDate = date('M d, Y', strtotime($user->created_at));
                $user->formattedTime = date('h:i A', strtotime($user->created_at));
            }

            return $users;
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error fetching recent registrations: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get pending approval counts across all modules
     * C-01: Safe table checks
     */
    private function getPendingApprovals() {
        $pending = new stdClass();
        $pending->blogs = 0;
        $pending->accommodations = 0;
        $pending->vehicles = 0;
        $pending->total = 0;

        // Pending blogs
        try {
            $blogStats = $this->blogModel->getBlogStats();
            $pending->blogs = $blogStats->pending ?? 0;
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error fetching pending blogs: " . $e->getMessage());
        }

        // Pending accommodations (safe check)
        try {
            if ($this->tableExists('accommodations') && $this->columnExists('accommodations', 'status')) {
                $result = $this->db->getRow("SELECT COUNT(*) as total FROM accommodations WHERE status = 'pending'");
                $pending->accommodations = $result ? (int)$result->total : 0;
            }
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error fetching pending accommodations: " . $e->getMessage());
        }

        // Pending vehicles (safe check)
        try {
            if ($this->tableExists('vehicles') && $this->columnExists('vehicles', 'status')) {
                $result = $this->db->getRow("SELECT COUNT(*) as total FROM vehicles WHERE status = 'pending'");
                $pending->vehicles = $result ? (int)$result->total : 0;
            }
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error fetching pending vehicles: " . $e->getMessage());
        }

        // Total pending items
        $pending->total = $pending->blogs + $pending->accommodations + $pending->vehicles;

        return $pending;
    }

    /**
     * Get system health information
     */
    private function getSystemHealth() {
        $health = new stdClass();

        // PHP Version
        $health->phpVersion = phpversion();

        // Server time — use MySQL NOW() for consistency (BUG-03 fix)
        try {
            $timeResult = $this->db->getRow("SELECT NOW() as serverTime, @@session.time_zone as timezone");
            if ($timeResult) {
                $health->serverTime = $timeResult->serverTime;
                $health->timezone = ($timeResult->timezone === 'SYSTEM') 
                    ? date_default_timezone_get() 
                    : $timeResult->timezone;
            } else {
                $health->serverTime = date('Y-m-d H:i:s');
                $health->timezone = date_default_timezone_get();
            }
        } catch (Exception $e) {
            $health->serverTime = date('Y-m-d H:i:s');
            $health->timezone = date_default_timezone_get();
        }

        // Server software
        $health->serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';

        // Database status
        try {
            $result = $this->db->getRow("SELECT VERSION() as version");
            $health->dbVersion = $result ? $result->version : 'Unknown';
            $health->dbStatus = 'Connected';
        } catch (Exception $e) {
            $health->dbVersion = 'Unknown';
            $health->dbStatus = 'Error';
        }

        // Disk usage (where the app lives)
        $appPath = realpath(__DIR__ . '/../../');
        $health->diskFree = @disk_free_space($appPath) ?: 0;
        $health->diskTotal = @disk_total_space($appPath) ?: 0;
        if ($health->diskTotal > 0) {
            $health->diskUsedPercent = round((1 - ($health->diskFree / $health->diskTotal)) * 100, 1);
        } else {
            $health->diskUsedPercent = 0;
        }

        // Memory
        $health->memoryUsage = memory_get_usage(true);
        $health->memoryLimit = ini_get('memory_limit');

        return $health;
    }

    /**
     * Get active user count (non-suspended)
     */
    private function getActiveUserCount() {
        try {
            $result = $this->db->getRow("SELECT COUNT(*) as total FROM users WHERE status = 'active' OR status IS NULL");
            return $result ? (int)$result->total : 0;
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error fetching active user count: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Safely get count from a table (with existence check)
     */
    private function getSafeCount($table) {
        $allowedTables = ['users', 'accommodations', 'vehicles', 'destinations', 'blogs', 'bookings', 'notifications', 'announcements'];
        if (!in_array($table, $allowedTables)) {
            return 0;
        }
        try {
            if (!$this->tableExists($table)) {
                return 0;
            }
            $result = $this->db->getRow("SELECT COUNT(*) as total FROM {$table}");
            return $result ? (int)$result->total : 0;
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error counting {$table}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Safely get count from last month for a table
     */
    private function getSafeCountLastMonth($table) {
        $allowedTables = ['users', 'accommodations', 'vehicles', 'destinations', 'blogs', 'bookings'];
        if (!in_array($table, $allowedTables)) {
            return 0;
        }
        try {
            if (!$this->tableExists($table) || !$this->columnExists($table, 'created_at')) {
                return 0;
            }
            $result = $this->db->getRow("SELECT COUNT(*) as total FROM {$table} WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)");
            return $result ? (int)$result->total : 0;
        } catch (Exception $e) {
            error_log("[DASHBOARD] Error counting {$table} last month: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Check if a database table exists
     * C-01: Core safety method
     */
    private function tableExists($table) {
        try {
            $check = $this->db->query("SHOW TABLES LIKE '{$table}'");
            return ($check && !empty($check));
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Check if a column exists in a table
     * C-01: Column existence safety for created_at, status etc.
     */
    private function columnExists($table, $column) {
        try {
            $check = $this->db->query("SHOW COLUMNS FROM {$table} LIKE '{$column}'");
            return ($check && !empty($check));
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Calculate percentage change between current total and new items last month
     */
    private function calculatePercentageChange($current, $lastMonth) {
        $previous = $current - $lastMonth;
        if ($previous == 0) {
            return $lastMonth > 0 ? 100 : 0;
        }
        return round((($lastMonth / $previous) * 100), 1);
    }

    /**
     * Format bytes to human-readable string
     * W-01: Changed to private static (encapsulation)
     */
    private static function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

class DatabaseHelper {
    use Database;
}
