<?php

class Dashboard extends Controller{

    public function index($a = '', $b = '' , $c = ''){
        // Check if user is logged in
        if (!isset($_SESSION['user']['id'])) {
            header('Location: login');
            exit;
        }
        
        // Load dependencies
        require_once __DIR__ . '/../../core/config.php';
        require_once __DIR__ . '/../../core/Model.php';
        require_once __DIR__ . '/../../core/Database.php';
        require_once __DIR__ . '/../../models/Post.php';
        
        // Get user's posts
        $postModel = new Post();
        $userId = $_SESSION['user']['id'];
        $userPosts = $postModel->getUserPosts($userId);
        
        // Count accommodation and transport bookings
        $accBookingsCount = 0;
        $transBookingsCount = 0;
        $userBio = '';

        try {
            $db = new class { use Database; };
            
            // Get user bio
            $userResult = $db->query("SELECT bio FROM users WHERE id = :id", ['id' => $userId]);
            if ($userResult && count($userResult) > 0) {
                $userBio = $userResult[0]->bio;
            }

            $accResult = $db->query("SELECT COUNT(*) as cnt FROM bookings WHERE user_id = :id", ['id' => $userId]);
            if ($accResult && count($accResult) > 0) {
                $accBookingsCount = $accResult[0]->cnt;
            }
            
            $transResult = $db->query("SELECT COUNT(*) as cnt FROM transport_bookings WHERE user_id = :id", ['id' => $userId]);
            if ($transResult && count($transResult) > 0) {
                $transBookingsCount = $transResult[0]->cnt;
            }
        } catch (Exception $e) {
            error_log("Error getting booking counts: " . $e->getMessage());
        }
        
        // Pass posts to view
        $data = [
            'posts' => $userPosts ? $userPosts : [],
            'accBookingsCount' => $accBookingsCount,
            'transBookingsCount' => $transBookingsCount,
            'userBio' => $userBio
        ];
        
        $this->view('Traveller/dashboard', $data);
    }
}