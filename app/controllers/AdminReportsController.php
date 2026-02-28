<?php

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class AdminReportsController {
    use Database;

    /**
     * Main reports page — fetches all analytics from database
     */
    public function index() {
        SessionHelper::requireAdmin();

        // Date filters (default: last 90 days to capture more data)
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-90 days'));
        $endDate   = $_GET['end_date']   ?? date('Y-m-d');

        // Sanitize dates
        $startDate = date('Y-m-d', strtotime($startDate)) ?: date('Y-m-d', strtotime('-30 days'));
        $endDate   = date('Y-m-d', strtotime($endDate))   ?: date('Y-m-d');

        // Ensure start <= end
        if (strtotime($startDate) > strtotime($endDate)) {
            $tmp = $startDate;
            $startDate = $endDate;
            $endDate = $tmp;
        }

        // Fetch all report data
        $overview        = $this->getOverviewStats($startDate, $endDate);
        $userStats       = $this->getUserStats($startDate, $endDate);
        $listingStats    = $this->getListingStats($startDate, $endDate);
        $bookingStats    = $this->getBookingStats($startDate, $endDate);
        $revenueStats    = $this->getRevenueStats($startDate, $endDate);
        $popularCities   = $this->getPopularCities();
        $recentBookings  = $this->getRecentBookings();

        $filters = [
            'start_date' => $startDate,
            'end_date'   => $endDate
        ];

        require_once __DIR__ . '/../views/admin/reports/index.view.php';
    }

    // ─── OVERVIEW STATISTICS ─────────────────────────────────────────

    private function getOverviewStats($startDate, $endDate) {
        $stats = new stdClass();

        // Users
        $row = $this->getRow(
            "SELECT 
                COUNT(*) as total_users,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_users,
                SUM(CASE WHEN DATE(created_at) BETWEEN :s AND :e THEN 1 ELSE 0 END) as new_users
             FROM users",
            ['s' => $startDate, 'e' => $endDate]
        );
        $stats->total_users  = $row ? (int)$row->total_users : 0;
        $stats->active_users = $row ? (int)$row->active_users : 0;
        $stats->new_users    = $row ? (int)$row->new_users : 0;

        // Active Listings
        $accRow = $this->getRow(
            "SELECT COUNT(*) as cnt FROM accommodations WHERE deleted_at IS NULL AND status = 'active'"
        );
        $stats->active_accommodations = $accRow ? (int)$accRow->cnt : 0;

        $vehRow = $this->getRow(
            "SELECT COUNT(*) as cnt FROM vehicles WHERE deleted_at IS NULL AND status = 'active'"
        );
        $stats->active_vehicles = $vehRow ? (int)$vehRow->cnt : 0;
        $stats->total_listings  = $stats->active_accommodations + $stats->active_vehicles;

        // Destinations
        $destRow = $this->getRow(
            "SELECT COUNT(*) as cnt FROM destinations WHERE deleted_at IS NULL AND status = 'active'"
        );
        $stats->total_destinations = $destRow ? (int)$destRow->cnt : 0;

        // Bookings in period
        $bRow = $this->getRow(
            "SELECT 
                COUNT(*) as total_bookings,
                SUM(CASE WHEN booking_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
                SUM(CASE WHEN booking_status = 'completed' THEN 1 ELSE 0 END) as completed_bookings,
                SUM(CASE WHEN booking_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_bookings,
                COALESCE(SUM(total_price), 0) as total_revenue,
                COALESCE(AVG(total_price), 0) as avg_booking_value
             FROM bookings
             WHERE DATE(created_at) BETWEEN :s AND :e",
            ['s' => $startDate, 'e' => $endDate]
        );
        $stats->total_bookings     = $bRow ? (int)$bRow->total_bookings : 0;
        $stats->confirmed_bookings = $bRow ? (int)$bRow->confirmed_bookings : 0;
        $stats->completed_bookings = $bRow ? (int)$bRow->completed_bookings : 0;
        $stats->cancelled_bookings = $bRow ? (int)$bRow->cancelled_bookings : 0;
        $stats->total_revenue      = $bRow ? (float)$bRow->total_revenue : 0;
        $stats->avg_booking_value  = $bRow ? round((float)$bRow->avg_booking_value, 2) : 0;

        // Cancellation rate
        $stats->cancellation_rate = $stats->total_bookings > 0
            ? round(($stats->cancelled_bookings / $stats->total_bookings) * 100, 1)
            : 0;

        // Platform commission (10% default)
        $stats->platform_commission = round($stats->total_revenue * 0.10, 2);
        $stats->provider_earnings   = round($stats->total_revenue * 0.90, 2);

        // Reviews
        $revRow = $this->getRow(
            "SELECT COUNT(*) as cnt, COALESCE(AVG(rating), 0) as avg_rating FROM reviews"
        );
        $stats->total_reviews = $revRow ? (int)$revRow->cnt : 0;
        $stats->avg_rating    = $revRow ? round((float)$revRow->avg_rating, 1) : 0;

        return $stats;
    }

    // ─── USER STATISTICS ─────────────────────────────────────────────

    private function getUserStats($startDate, $endDate) {
        $stats = new stdClass();

        // Registration trend (daily)
        $stats->registration_trend = $this->query(
            "SELECT 
                DATE(created_at) as date,
                DATE_FORMAT(created_at, '%b %d') as formatted_date,
                COUNT(*) as count
             FROM users
             WHERE DATE(created_at) BETWEEN :s AND :e
             GROUP BY DATE(created_at)
             ORDER BY date ASC",
            ['s' => $startDate, 'e' => $endDate]
        ) ?: [];

        // Role distribution
        $stats->role_distribution = $this->query(
            "SELECT 
                role,
                COUNT(*) as count,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_count,
                ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM users)), 1) as percentage
             FROM users
             GROUP BY role
             ORDER BY count DESC"
        ) ?: [];

        // Growth comparison vs previous period
        $days = max(1, (strtotime($endDate) - strtotime($startDate)) / 86400);
        $prevStart = date('Y-m-d', strtotime($startDate . " -{$days} days"));
        $prevEnd   = date('Y-m-d', strtotime($startDate . " -1 day"));

        $growthRow = $this->getRow(
            "SELECT 
                SUM(CASE WHEN DATE(created_at) BETWEEN :s AND :e THEN 1 ELSE 0 END) as current_period,
                SUM(CASE WHEN DATE(created_at) BETWEEN :ps AND :pe THEN 1 ELSE 0 END) as previous_period
             FROM users",
            ['s' => $startDate, 'e' => $endDate, 'ps' => $prevStart, 'pe' => $prevEnd]
        );

        $current  = $growthRow ? (int)$growthRow->current_period : 0;
        $previous = $growthRow ? (int)$growthRow->previous_period : 0;

        $stats->current_period_users  = $current;
        $stats->previous_period_users = $previous;
        $stats->growth_percent = $previous > 0
            ? round((($current - $previous) / $previous) * 100, 1)
            : ($current > 0 ? 100 : 0);

        return $stats;
    }

    // ─── LISTING STATISTICS ──────────────────────────────────────────

    private function getListingStats($startDate, $endDate) {
        $stats = new stdClass();

        // Accommodations by property type
        $stats->accommodations_by_type = $this->query(
            "SELECT 
                property_type as type,
                COUNT(*) as total_count,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_count,
                COALESCE(SUM(views_count), 0) as total_views,
                COALESCE(AVG(price_per_night), 0) as avg_price
             FROM accommodations
             WHERE deleted_at IS NULL
             GROUP BY property_type
             ORDER BY total_count DESC"
        ) ?: [];

        // Vehicles by type
        $stats->vehicles_by_type = $this->query(
            "SELECT 
                vehicle_type,
                COUNT(*) as total_count,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_count,
                COALESCE(SUM(views_count), 0) as total_views,
                COALESCE(AVG(price_per_day), 0) as avg_price
             FROM vehicles
             WHERE deleted_at IS NULL
             GROUP BY vehicle_type
             ORDER BY total_count DESC"
        ) ?: [];

        // New listings in period
        $newAccRow = $this->getRow(
            "SELECT COUNT(*) as cnt FROM accommodations
             WHERE DATE(created_at) BETWEEN :s AND :e AND deleted_at IS NULL",
            ['s' => $startDate, 'e' => $endDate]
        );
        $stats->new_accommodations = $newAccRow ? (int)$newAccRow->cnt : 0;

        $newVehRow = $this->getRow(
            "SELECT COUNT(*) as cnt FROM vehicles
             WHERE DATE(created_at) BETWEEN :s AND :e AND deleted_at IS NULL",
            ['s' => $startDate, 'e' => $endDate]
        );
        $stats->new_vehicles = $newVehRow ? (int)$newVehRow->cnt : 0;

        return $stats;
    }

    // ─── BOOKING STATISTICS ──────────────────────────────────────────

    private function getBookingStats($startDate, $endDate) {
        $stats = new stdClass();

        // Bookings by status
        $stats->by_status = $this->query(
            "SELECT 
                booking_status as status,
                COUNT(*) as count,
                COALESCE(SUM(total_price), 0) as revenue,
                ROUND(COUNT(*) * 100.0 / GREATEST((SELECT COUNT(*) FROM bookings WHERE DATE(created_at) BETWEEN :s2 AND :e2), 1), 1) as percentage
             FROM bookings
             WHERE DATE(created_at) BETWEEN :s AND :e
             GROUP BY booking_status
             ORDER BY count DESC",
            ['s' => $startDate, 'e' => $endDate, 's2' => $startDate, 'e2' => $endDate]
        ) ?: [];

        // Daily booking trend
        $stats->daily_trend = $this->query(
            "SELECT 
                DATE(created_at) as date,
                DATE_FORMAT(created_at, '%b %d') as formatted_date,
                COUNT(*) as count,
                COALESCE(SUM(total_price), 0) as revenue
             FROM bookings
             WHERE DATE(created_at) BETWEEN :s AND :e
             GROUP BY DATE(created_at)
             ORDER BY date ASC",
            ['s' => $startDate, 'e' => $endDate]
        ) ?: [];

        // Bookings by payment status
        $stats->by_payment = $this->query(
            "SELECT 
                payment_status,
                COUNT(*) as count,
                COALESCE(SUM(total_price), 0) as revenue
             FROM bookings
             WHERE DATE(created_at) BETWEEN :s AND :e
             GROUP BY payment_status
             ORDER BY count DESC",
            ['s' => $startDate, 'e' => $endDate]
        ) ?: [];

        return $stats;
    }

    // ─── REVENUE STATISTICS ──────────────────────────────────────────

    private function getRevenueStats($startDate, $endDate) {
        $stats = new stdClass();

        // Daily revenue
        $stats->daily_revenue = $this->query(
            "SELECT 
                DATE(created_at) as date,
                DATE_FORMAT(created_at, '%b %d') as formatted_date,
                COALESCE(SUM(total_price), 0) as total_revenue,
                ROUND(COALESCE(SUM(total_price), 0) * 0.10, 2) as commission,
                ROUND(COALESCE(SUM(total_price), 0) * 0.90, 2) as provider_earnings,
                COUNT(*) as booking_count
             FROM bookings
             WHERE DATE(created_at) BETWEEN :s AND :e
               AND booking_status IN ('confirmed', 'completed')
             GROUP BY DATE(created_at)
             ORDER BY date ASC",
            ['s' => $startDate, 'e' => $endDate]
        ) ?: [];

        // Revenue growth vs previous period
        $days = max(1, (strtotime($endDate) - strtotime($startDate)) / 86400);
        $prevStart = date('Y-m-d', strtotime($startDate . " -{$days} days"));
        $prevEnd   = date('Y-m-d', strtotime($startDate . " -1 day"));

        $growthRow = $this->getRow(
            "SELECT 
                COALESCE(SUM(CASE WHEN DATE(created_at) BETWEEN :s AND :e THEN total_price ELSE 0 END), 0) as current_revenue,
                COALESCE(SUM(CASE WHEN DATE(created_at) BETWEEN :ps AND :pe THEN total_price ELSE 0 END), 0) as previous_revenue
             FROM bookings
             WHERE booking_status IN ('confirmed', 'completed')",
            ['s' => $startDate, 'e' => $endDate, 'ps' => $prevStart, 'pe' => $prevEnd]
        );

        $currentRev  = $growthRow ? (float)$growthRow->current_revenue : 0;
        $previousRev = $growthRow ? (float)$growthRow->previous_revenue : 0;

        $stats->current_revenue  = $currentRev;
        $stats->previous_revenue = $previousRev;
        $stats->growth_percent   = $previousRev > 0
            ? round((($currentRev - $previousRev) / $previousRev) * 100, 1)
            : ($currentRev > 0 ? 100 : 0);

        return $stats;
    }

    // ─── POPULAR CITIES ──────────────────────────────────────────────

    private function getPopularCities() {
        return $this->query(
            "SELECT 
                city,
                district,
                COUNT(*) as listing_count,
                COALESCE(SUM(views_count), 0) as total_views,
                COALESCE(AVG(price_per_night), 0) as avg_price
             FROM accommodations
             WHERE deleted_at IS NULL AND city IS NOT NULL AND city != ''
             GROUP BY city, district
             ORDER BY listing_count DESC, total_views DESC
             LIMIT 10"
        ) ?: [];
    }

    // ─── RECENT BOOKINGS ─────────────────────────────────────────────

    private function getRecentBookings() {
        return $this->query(
            "SELECT 
                b.id,
                b.booking_id as booking_code,
                b.room_name,
                b.booking_status,
                b.payment_status,
                b.total_price,
                b.checkin_date,
                b.checkout_date,
                b.nights,
                DATE_FORMAT(b.created_at, '%b %d, %Y %h:%i %p') as formatted_date,
                CONCAT(u.first_name, ' ', u.last_name) as user_name,
                u.email as user_email
             FROM bookings b
             LEFT JOIN users u ON b.user_id = u.id
             ORDER BY b.created_at DESC
             LIMIT 3"
        ) ?: [];
    }
}
