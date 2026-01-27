<?php

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Blog.php';

class AdminDashboardController {
    
    private $blogModel;

    public function __construct() {
        $this->blogModel = new BlogModel();
    }

    /**
     * Display admin dashboard
     */
    public function index() {
        // For now, allow access without login check for development
        // TODO: Uncomment this after admin login is working
        /*
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: login');
            exit;
        }
        */

        // Get dashboard statistics
        $stats = $this->getDashboardStats();

        // Load dashboard view
        require_once __DIR__ . '/../views/admin/dashboard.view.php';
    }

    /**
     * Get all dashboard statistics
     */
    private function getDashboardStats() {
        $stats = new stdClass();

        // Get user count
        $stats->totalUsers = $this->getTotalCount('users');
        $stats->usersLastMonth = $this->getCountLastMonth('users');
        
        // Get accommodation count
        $stats->totalAccommodations = $this->getTotalCount('accommodations');
        $stats->accommodationsLastMonth = $this->getCountLastMonth('accommodations');
        
        // Get vehicle count
        $stats->totalVehicles = $this->getTotalCount('vehicles');
        $stats->vehiclesLastMonth = $this->getCountLastMonth('vehicles');
        
        // Get booking count
        $stats->totalBookings = $this->getTotalCount('bookings');
        $stats->bookingsLastMonth = $this->getCountLastMonth('bookings');
        
        // Get destination count
        $stats->totalDestinations = $this->getTotalCount('destinations');
        
        // Get blog statistics
        $blogStats = $this->blogModel->getBlogStats();
        $stats->pendingBlogs = $blogStats->pending ?? 0;
        
        // Calculate percentage changes
        $stats->userChange = $this->calculatePercentageChange($stats->totalUsers, $stats->usersLastMonth);
        $stats->accommodationChange = $this->calculatePercentageChange($stats->totalAccommodations, $stats->accommodationsLastMonth);
        $stats->vehicleChange = $this->calculatePercentageChange($stats->totalVehicles, $stats->vehiclesLastMonth);
        $stats->bookingChange = $this->calculatePercentageChange($stats->totalBookings, $stats->bookingsLastMonth);

        return $stats;
    }

    /**
     * Get total count for a table
     */
    private function getTotalCount($table) {
        $db = new DatabaseHelper();
        $query = "SELECT COUNT(*) as total FROM {$table}";
        $result = $db->query($query);
        return $result[0]->total ?? 0;
    }

    /**
     * Get count from last month
     */
    private function getCountLastMonth($table) {
        $db = new DatabaseHelper();
        $query = "SELECT COUNT(*) as total FROM {$table} WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        $result = $db->query($query);
        return $result[0]->total ?? 0;
    }

    /**
     * Calculate percentage change
     */
    private function calculatePercentageChange($current, $lastMonth) {
        $previous = $current - $lastMonth;
        if ($previous == 0) {
            return $lastMonth > 0 ? 100 : 0;
        }
        return round((($lastMonth / $previous) * 100), 1);
    }
}

class DatabaseHelper {
    use Database;
}
