<?php

class Report extends Controller {
    use Database;

    public function index($a = "", $b = "", $c = "") {
        // Only admin? (assume admin since it is in Admin folder)
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
        
        // Build base condition
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

        // Stats
        $users = $this->query("SELECT COUNT(*) as count FROM users WHERE $dateCond") ?: [(object)["count"=>0]];
        $prevUsers = $this->query("SELECT COUNT(*) as count FROM users WHERE $prevCond") ?: [(object)["count"=>0]];
        
        $listings = $this->query("SELECT (SELECT COUNT(*) FROM accommodations WHERE $dateCond) + (SELECT COUNT(*) FROM vehicles WHERE $dateCond) as count") ?: [(object)["count"=>0]];
        $prevListings = $this->query("SELECT (SELECT COUNT(*) FROM accommodations WHERE $prevCond) + (SELECT COUNT(*) FROM vehicles WHERE $prevCond) as count") ?: [(object)["count"=>0]];
        
        $bookingsAcc = $this->query("SELECT COUNT(*) as count FROM bookings WHERE $dateCond") ?: [(object)["count"=>0]];
        $bookingsTrans = $this->query("SELECT COUNT(*) as count FROM transport_bookings WHERE status = \"confirmed\" AND $dateCond") ?: [(object)["count"=>0]];
        $bookings = $bookingsAcc[0]->count + $bookingsTrans[0]->count;
        
        $prevBookingsAcc = $this->query("SELECT COUNT(*) as count FROM bookings WHERE $prevCond") ?: [(object)["count"=>0]];
        $prevBookingsTrans = $this->query("SELECT COUNT(*) as count FROM transport_bookings WHERE status = \"confirmed\" AND $prevCond") ?: [(object)["count"=>0]];
        $prevBookings = $prevBookingsAcc[0]->count + $prevBookingsTrans[0]->count;
        
        $revenueAcc = $this->query("SELECT SUM(total_price) as sum FROM bookings WHERE booking_status = \"confirmed\" AND $dateCond") ?: [(object)["sum"=>0]];
        $revenueTrans = $this->query("SELECT SUM(total_price) as sum FROM transport_bookings WHERE status = \"confirmed\" AND $dateCond") ?: [(object)["sum"=>0]];
        $revenue = ($revenueAcc[0]->sum ?: 0) + ($revenueTrans[0]->sum ?: 0);
        
        $prevRevAcc = $this->query("SELECT SUM(total_price) as sum FROM bookings WHERE booking_status = \"confirmed\" AND $prevCond") ?: [(object)["sum"=>0]];
        $prevRevTrans = $this->query("SELECT SUM(total_price) as sum FROM transport_bookings WHERE status = \"confirmed\" AND $prevCond") ?: [(object)["sum"=>0]];
        $prevRevenue = ($prevRevAcc[0]->sum ?: 0) + ($prevRevTrans[0]->sum ?: 0);
        
        // Calc percentage change
        $calcChange = function($current, $prev) {
            if ($prev == 0) return $current > 0 ? "+100%" : "0%";
            $pct = (($current - $prev) / $prev) * 100;
            return ($pct >= 0 ? "+" : "") . number_format($pct, 1) . "%";
        };
        
        // Data for tables
        $recentUsers = $this->query("SELECT first_name, last_name, role, created_at, \"Active\" as status FROM users ORDER BY created_at DESC LIMIT 4") ?: [];
        // Pending approvals (fake content logic from accommodations and vehicles, just taking a few properties)
        $pendingApprovals = $this->query("
            (SELECT name as content, \"Accommodation\" as type, created_at FROM accommodations ORDER BY created_at DESC LIMIT 2)
            UNION
            (SELECT CONCAT(make, \" \", model) as content, \"Vehicle\" as type, created_at FROM vehicles ORDER BY created_at DESC LIMIT 1)
            ORDER BY created_at DESC LIMIT 3
        ") ?: [];

        echo json_encode([
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
                    "value" => "$" . number_format($revenue, 2),
                    "change" => $calcChange($revenue, $prevRevenue)
                ]
            ],
            "lists" => [
                "recentUsers" => $recentUsers,
                "pending" => $pendingApprovals
            ]
        ]);
    }
}
?>
