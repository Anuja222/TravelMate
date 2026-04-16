<?php

class Report extends Controller {
    use Database;

    public function index($a = "", $b = "", $c = "") {
        // only admin? (assume admin since it is in Admin folder)
        if (!isset($_SESSION["user"]["id"])) {
            header("Location: login");
            exit;
        }

        $this->view("admin/report");
    }

    public function get_stats() {
        if (!isset($_SESSION["user"]["id"])) {
            echo json_encode(["error" => "Unauthorized"]);
            exit;
        }

        $period = $_GET["period"] ?? "week";
        
        // build base condition
        $dateCond = "1=1";
        switch ($period) {
            case "today":
                $dateCond = "DATE(created_at) = CURDATE()";
                break;
            case "week":
                $dateCond = "created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
                break;
            case "month":
                $dateCond = "created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
                break;
            case "quarter":
                $dateCond = "created_at >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
                break;
            case "year":
                $dateCond = "created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
                break;
        }
        
        $prevCond = "1=1";
        switch ($period) {
            case "today":
                $prevCond = "DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
                break;
            case "week":
                $prevCond = "created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) AND created_at < DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
                break;
            case "month":
                $prevCond = "created_at >= DATE_SUB(CURDATE(), INTERVAL 2 MONTH) AND created_at < DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
                break;
            case "quarter":
                $prevCond = "created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND created_at < DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
                break;
            case "year":
                $prevCond = "created_at >= DATE_SUB(CURDATE(), INTERVAL 2 YEAR) AND created_at < DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
                break;
        }

        // stats
        $users = $this->query("SELECT COUNT(*) as count FROM users WHERE $dateCond") ?: [(object)["count"=>0]];
        $prevUsers = $this->query("SELECT COUNT(*) as count FROM users WHERE $prevCond") ?: [(object)["count"=>0]];
        
        $listings = $this->query("SELECT (SELECT COUNT(*) FROM accommodations WHERE $dateCond) + (SELECT COUNT(*) FROM vehicles WHERE $dateCond) as count") ?: [(object)["count"=>0]];
        $prevListings = $this->query("SELECT (SELECT COUNT(*) FROM accommodations WHERE $prevCond) + (SELECT COUNT(*) FROM vehicles WHERE $prevCond) as count") ?: [(object)["count"=>0]];
        
        $bookingsAcc = $this->query("SELECT COUNT(*) as count FROM bookings WHERE $dateCond") ?: [(object)["count"=>0]];
        $bookingsTrans = $this->query("SELECT COUNT(*) as count FROM transport_bookings WHERE booking_status = \"confirmed\" AND $dateCond") ?: [(object)["count"=>0]];
        $bookings = $bookingsAcc[0]->count + $bookingsTrans[0]->count;
        
        $prevBookingsAcc = $this->query("SELECT COUNT(*) as count FROM bookings WHERE $prevCond") ?: [(object)["count"=>0]];
        $prevBookingsTrans = $this->query("SELECT COUNT(*) as count FROM transport_bookings WHERE booking_status = \"confirmed\" AND $prevCond") ?: [(object)["count"=>0]];
        $prevBookings = $prevBookingsAcc[0]->count + $prevBookingsTrans[0]->count;
        
        $revenueAcc = $this->query("SELECT SUM(total_price) as sum FROM bookings WHERE booking_status = \"confirmed\" AND $dateCond") ?: [(object)["sum"=>0]];
        $revenueTrans = $this->query("SELECT SUM(total_price) as sum FROM transport_bookings WHERE booking_status = \"confirmed\" AND $dateCond") ?: [(object)["sum"=>0]];
        $revenue = ($revenueAcc[0]->sum ?: 0) + ($revenueTrans[0]->sum ?: 0);
        
        $prevRevAcc = $this->query("SELECT SUM(total_price) as sum FROM bookings WHERE booking_status = \"confirmed\" AND $prevCond") ?: [(object)["sum"=>0]];
        $prevRevTrans = $this->query("SELECT SUM(total_price) as sum FROM transport_bookings WHERE booking_status = \"confirmed\" AND $prevCond") ?: [(object)["sum"=>0]];
        $prevRevenue = ($prevRevAcc[0]->sum ?: 0) + ($prevRevTrans[0]->sum ?: 0);
        
        // calc percentage change
        $calcChange = function($current, $prev) {
            if ($prev == 0) return $current > 0 ? "+100%" : "0%";
            $pct = (($current - $prev) / $prev) * 100;
            return ($pct >= 0 ? "+" : "") . number_format($pct, 1) . "%";
        };
        
        // data for tables
        $recentUsers = $this->query("SELECT first_name, last_name, role, created_at, \"Active\" as status FROM users ORDER BY created_at DESC LIMIT 4") ?: [];
        // pending approvals (fake content logic from accommodations and vehicles, just taking a few properties)
        $pendingApprovals = $this->query("
            (SELECT title as content, \"Accommodation\" as type, created_at FROM accommodations ORDER BY created_at DESC LIMIT 2)
            UNION
            (SELECT vehicle_model as content, \"Vehicle\" as type, created_at FROM vehicles ORDER BY created_at DESC LIMIT 1)
            ORDER BY created_at DESC LIMIT 3
        ") ?: [];

        // chart: User Distribution
        $rawUserDist = $this->query("SELECT role as label, COUNT(*) as count FROM users GROUP BY role") ?: [];
        $userDistLabels = [];
        $userDistData = [];
        foreach ($rawUserDist as $row) {
            $userDistLabels[] = ucfirst($row->label ?: 'Traveler');
            $userDistData[] = (int)$row->count;
        }

        // chart: Booking Trends
        $chartPeriod = $_GET["chart_period"] ?? "weekly";
        $bookingTrendsMap = [];
        
        if ($chartPeriod === 'yearly') {
            // group by month for the last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $d = date('Y-m', strtotime("-$i months"));
                $bookingTrendsMap[$d] = 0;
            }
            
            $rawBookingsAcc = $this->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as date, COUNT(*) as count FROM bookings WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY DATE_FORMAT(created_at, '%Y-%m')") ?: [];
            foreach ($rawBookingsAcc as $row) {
                if (isset($bookingTrendsMap[$row->date])) {
                    $bookingTrendsMap[$row->date] += (int)$row->count;
                }
            }

            $rawBookingsTrans = $this->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as date, COUNT(*) as count FROM transport_bookings WHERE booking_status = \"confirmed\" AND created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY DATE_FORMAT(created_at, '%Y-%m')") ?: [];
            foreach ($rawBookingsTrans as $row) {
                if (isset($bookingTrendsMap[$row->date])) {
                    $bookingTrendsMap[$row->date] += (int)$row->count;
                }
            }
            
            $bookingTrendLabels = array_map(function($date) { return date('M Y', strtotime($date . '-01')); }, array_keys($bookingTrendsMap));
        
        } elseif ($chartPeriod === 'monthly') {
            // group by day for the last 30 days
            for ($i = 29; $i >= 0; $i--) {
                $d = date('Y-m-d', strtotime("-$i days"));
                $bookingTrendsMap[$d] = 0;
            }
            
            $rawBookingsAcc = $this->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM bookings WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY DATE(created_at)") ?: [];
            foreach ($rawBookingsAcc as $row) {
                if (isset($bookingTrendsMap[$row->date])) {
                    $bookingTrendsMap[$row->date] += (int)$row->count;
                }
            }

            $rawBookingsTrans = $this->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM transport_bookings WHERE booking_status = \"confirmed\" AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY DATE(created_at)") ?: [];
            foreach ($rawBookingsTrans as $row) {
                if (isset($bookingTrendsMap[$row->date])) {
                    $bookingTrendsMap[$row->date] += (int)$row->count;
                }
            }
            
            $bookingTrendLabels = array_map(function($date) { return date('M d', strtotime($date)); }, array_keys($bookingTrendsMap));
            
        } else {
            // default weekly
            for ($i = 6; $i >= 0; $i--) {
                $d = date('Y-m-d', strtotime("-$i days"));
                $bookingTrendsMap[$d] = 0;
            }

            $rawBookingsAcc = $this->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM bookings WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(created_at)") ?: [];
            foreach ($rawBookingsAcc as $row) {
                if (isset($bookingTrendsMap[$row->date])) {
                    $bookingTrendsMap[$row->date] += (int)$row->count;
                }
            }

            $rawBookingsTrans = $this->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM transport_bookings WHERE booking_status = \"confirmed\" AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(created_at)") ?: [];
            foreach ($rawBookingsTrans as $row) {
                if (isset($bookingTrendsMap[$row->date])) {
                    $bookingTrendsMap[$row->date] += (int)$row->count;
                }
            }

            $bookingTrendLabels = array_map(function($date) { return date('D', strtotime($date)); }, array_keys($bookingTrendsMap));
        }

        $bookingTrendData = array_values($bookingTrendsMap);

        echo json_encode([
            "charts" => [
                "userDistribution" => [
                    "labels" => $userDistLabels,
                    "data" => $userDistData
                ],
                "bookingTrends" => [
                    "labels" => $bookingTrendLabels,
                    "data" => $bookingTrendData
                ]
            ],
            "stats" => [
                "users" => [
                    "value" => number_format($users[0]->count),
                    "change" => $calcChange($users[0]->count, $prevUsers[0]->count)
                ],
                "listings" => [
                    "value" => number_format($listings[0]->count),
                    "change" => $calcChange($listings[0]->count, $prevListings[0]->count)
                ],
                "bookings" => [
                    "value" => number_format($bookings),
                    "change" => $calcChange($bookings, $prevBookings)
                ],
                "revenue" => [
                    "value" => "Rs. " . number_format($revenue, 2),
                    "change" => $calcChange($revenue, $prevRevenue)
                ],
                "travellers" => [
                    "value" => number_format($t_curr = $this->query("SELECT COUNT(*) as count FROM users WHERE role IN ('traveler', 'traveller') AND $dateCond")[0]->count ?? 0),
                    "change" => $calcChange($t_curr, $this->query("SELECT COUNT(*) as count FROM users WHERE role IN ('traveler', 'traveller') AND $prevCond")[0]->count ?? 0)
                ],
                "accom_providers" => [
                    "value" => number_format($ap_curr = $this->query("SELECT COUNT(*) as count FROM users WHERE role = 'accommodation_provider' AND $dateCond")[0]->count ?? 0),
                    "change" => $calcChange($ap_curr, $this->query("SELECT COUNT(*) as count FROM users WHERE role = 'accommodation_provider' AND $prevCond")[0]->count ?? 0)
                ],
                "transport_providers" => [
                    "value" => number_format($tp_curr = $this->query("SELECT COUNT(*) as count FROM users WHERE role = 'transport_provider' AND $dateCond")[0]->count ?? 0),
                    "change" => $calcChange($tp_curr, $this->query("SELECT COUNT(*) as count FROM users WHERE role = 'transport_provider' AND $prevCond")[0]->count ?? 0)
                ],
                "accom_ads" => [
                    "value" => number_format($aa_curr = $this->query("SELECT COUNT(*) as count FROM accommodations WHERE $dateCond")[0]->count ?? 0),
                    "change" => $calcChange($aa_curr, $this->query("SELECT COUNT(*) as count FROM accommodations WHERE $prevCond")[0]->count ?? 0)
                ],
                "vehicle_ads" => [
                    "value" => number_format($va_curr = $this->query("SELECT COUNT(*) as count FROM vehicles WHERE $dateCond")[0]->count ?? 0),
                    "change" => $calcChange($va_curr, $this->query("SELECT COUNT(*) as count FROM vehicles WHERE $prevCond")[0]->count ?? 0)
                ],
                "acc_bookings" => [
                    "value" => number_format($ab_curr = $this->query("SELECT COUNT(*) as count FROM bookings WHERE $dateCond")[0]->count ?? 0),
                    "change" => $calcChange($ab_curr, $this->query("SELECT COUNT(*) as count FROM bookings WHERE $prevCond")[0]->count ?? 0)
                ],
                "transport_bookings" => [
                    "value" => number_format($tb_curr = $this->query("SELECT COUNT(*) as count FROM transport_bookings WHERE $dateCond")[0]->count ?? 0),
                    "change" => $calcChange($tb_curr, $this->query("SELECT COUNT(*) as count FROM transport_bookings WHERE $prevCond")[0]->count ?? 0)
                ],
                "total_destinations" => [
                    "value" => number_format($td_curr = $this->query("SELECT COUNT(*) as count FROM destinations WHERE $dateCond")[0]->count ?? 0),
                    "change" => $calcChange($td_curr, $this->query("SELECT COUNT(*) as count FROM destinations WHERE $prevCond")[0]->count ?? 0)
                ],
                "total_activities" => [
                    "value" => number_format($ta_curr = $this->query("SELECT COUNT(*) as count FROM activities WHERE $dateCond")[0]->count ?? 0),
                    "change" => $calcChange($ta_curr, $this->query("SELECT COUNT(*) as count FROM activities WHERE $prevCond")[0]->count ?? 0)
                ],
                "blogs_vlogs" => [
                    "value" => number_format($bv_curr = $this->query("SELECT COUNT(*) as count FROM posts WHERE $dateCond")[0]->count ?? 0),
                    "change" => $calcChange($bv_curr, $this->query("SELECT COUNT(*) as count FROM posts WHERE $prevCond")[0]->count ?? 0)
                ],
                "pending_approvals" => [
                    "value" => count($pendingApprovals),
                    "change" => "0%"
                ],
            ],
            "lists" => [
                "recentUsers" => $recentUsers,
                "pending" => $pendingApprovals
            ]
        ]);
    }
}
?>
