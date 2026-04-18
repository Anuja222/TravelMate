<?php

class Revenue extends Controller {
    public function index() {
        if (!isset($_SESSION['user']['id'])) {
            header('Location: login');
            exit;
        }

        require_once __DIR__ . '/../../core/config.php';
        $dsn = "mysql:host=" . DBHOST . ";port=" . DBPORT . ";dbname=" . DBNAME . ";charset=utf8mb4";
        $conn = new PDO($dsn, DBUSER, DBPASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $userId = $_SESSION['user']['id'];
        
        // filter logic
        $filter = $_GET['filter'] ?? 'month';
        
        if ($filter === 'week') {
            $currentCondition = "YEARWEEK(b.created_at, 1) = YEARWEEK(CURDATE(), 1)";
            $previousCondition = "YEARWEEK(b.created_at, 1) = YEARWEEK(DATE_SUB(CURDATE(), INTERVAL 1 WEEK), 1)";
            $periodLabel = "This Week";
        } elseif ($filter === 'year') {
            $currentCondition = "YEAR(b.created_at) = YEAR(CURDATE())";
            $previousCondition = "YEAR(b.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 YEAR))";
            $periodLabel = "This Year";
        } else {
            $filter = 'month'; // ensure valid value
            $currentCondition = "MONTH(b.created_at) = MONTH(CURDATE()) AND YEAR(b.created_at) = YEAR(CURDATE())";
            $previousCondition = "MONTH(b.created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(b.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))";
            $periodLabel = "This Month";
        }

        // 1. Overall Revenue and Bookings Count for Current Period
        $stmtCurrent = $conn->prepare("
            SELECT COALESCE(SUM(b.total_price), 0) AS rev, COUNT(b.id) AS bookings_count
            FROM bookings b
            WHERE b.accommodation_id IN (SELECT id FROM accommodations WHERE user_id = ?) 
            AND b.booking_status IN ('confirmed', 'completed')
            AND $currentCondition
        ");
        $stmtCurrent->execute([$userId]);
        $currentStats = $stmtCurrent->fetch(PDO::FETCH_ASSOC);
        $currentRevenue = $currentStats['rev'];
        $currentBookings = $currentStats['bookings_count'];

        // 2. Overall Revenue for Previous Period (For Improvement Calculation)
        $stmtPrev = $conn->prepare("
            SELECT COALESCE(SUM(b.total_price), 0) AS rev
            FROM bookings b
            WHERE b.accommodation_id IN (SELECT id FROM accommodations WHERE user_id = ?) 
            AND b.booking_status IN ('confirmed', 'completed')
            AND $previousCondition
        ");
        $stmtPrev->execute([$userId]);
        $previousRevenue = $stmtPrev->fetchColumn();

        // 3. Property Wise Breakdown for Current Period
        $stmtProps = $conn->prepare("
            SELECT a.id, a.title, 
                   COUNT(b.id) AS property_bookings, 
                   COALESCE(SUM(b.total_price), 0) AS property_revenue 
            FROM accommodations a 
            LEFT JOIN bookings b ON a.id = b.accommodation_id 
                 AND b.booking_status IN ('confirmed', 'completed') 
                 AND $currentCondition
            WHERE a.user_id = ?
            GROUP BY a.id, a.title
            ORDER BY property_revenue DESC, property_bookings DESC
        ");
        $stmtProps->execute([$userId]);
        $properties = $stmtProps->fetchAll(PDO::FETCH_ASSOC);

        // helper string formatting for UI
        $calcImprovement = function($current, $previous) {
            if ($previous == 0) {
                return $current > 0 ? 100 : 0;
            }
            return round((($current - $previous) / $previous) * 100, 2);
        };

        $improvement = $calcImprovement($currentRevenue, $previousRevenue);

        $data = [
            'filter' => $filter,
            'period_label' => $periodLabel,
            'total_revenue' => $currentRevenue,
            'total_bookings' => $currentBookings,
            'improvement' => $improvement,
            'properties' => $properties
        ];

        $this->view('accommodation/revenue', $data);
    }
}
