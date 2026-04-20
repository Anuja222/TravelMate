<?php

class Revenue extends Controller {
    public function index() {
        if (!isset($_SESSION['user']['id'])) {
            header('Location: login');
            exit;
        }

        require_once __DIR__ . '/../../core/config.php';
        $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";
        $conn = new PDO($dsn, DBUSER, DBPASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $userId = $_SESSION['user']['id'];

        // filter logic
        $filter = $_GET['filter'] ?? 'month';

        if ($filter === 'week') {
            $currentCondition = "YEARWEEK(tb.created_at, 1) = YEARWEEK(CURDATE(), 1)";
            $previousCondition = "YEARWEEK(tb.created_at, 1) = YEARWEEK(DATE_SUB(CURDATE(), INTERVAL 1 WEEK), 1)";
            $periodLabel = "This Week";
        } elseif ($filter === 'year') {
            $currentCondition = "YEAR(tb.created_at) = YEAR(CURDATE())";
            $previousCondition = "YEAR(tb.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 YEAR))";
            $periodLabel = "This Year";
        } else {
            $filter = 'month'; // Ensure valid value
            $currentCondition = "MONTH(tb.created_at) = MONTH(CURDATE()) AND YEAR(tb.created_at) = YEAR(CURDATE())";
            $previousCondition = "MONTH(tb.created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(tb.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))";
            $periodLabel = "This Month";
        }

        // Current Period
        $stmtCurrent = $conn->prepare("
            SELECT COALESCE(SUM(tb.total_price), 0) AS rev, COUNT(tb.id) AS bookings_count
            FROM transport_bookings tb
            WHERE tb.vehicle_id IN (SELECT id FROM vehicles WHERE user_id = ?)
            AND tb.booking_status IN ('confirmed', 'completed')
            AND $currentCondition
        ");
        $stmtCurrent->execute([$userId]);
        $currentStats = $stmtCurrent->fetch(PDO::FETCH_ASSOC);
        $currentRevenue = $currentStats['rev'];
        $currentBookings = $currentStats['bookings_count'];

        //Previous Period
        $stmtPrev = $conn->prepare("
            SELECT COALESCE(SUM(tb.total_price), 0) AS rev
            FROM transport_bookings tb
            WHERE tb.vehicle_id IN (SELECT id FROM vehicles WHERE user_id = ?)
            AND tb.booking_status IN ('confirmed', 'completed')
            AND $previousCondition
        ");
        $stmtPrev->execute([$userId]);
        $previousRevenue = $stmtPrev->fetchColumn();

        //Asset Wise Breakdown for Current Period
        $stmtProps = $conn->prepare("
            SELECT v.id, v.vehicle_model AS title, v.vehicle_number,
                   COUNT(tb.id) AS property_bookings,
                   COALESCE(SUM(tb.total_price), 0) AS property_revenue
            FROM vehicles v
            LEFT JOIN transport_bookings tb ON v.id = tb.vehicle_id
                 AND tb.booking_status IN ('confirmed', 'completed')
                 AND $currentCondition
            WHERE v.user_id = ?
            GROUP BY v.id, v.vehicle_model, v.vehicle_number
            ORDER BY property_revenue DESC, property_bookings DESC
        ");
        $stmtProps->execute([$userId]);
        $properties = $stmtProps->fetchAll(PDO::FETCH_ASSOC);

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

        $this->view('transpoter/revenue', $data);
    }
}
