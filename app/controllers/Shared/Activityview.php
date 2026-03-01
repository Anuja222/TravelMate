<?php

class Activityview extends Controller {

    public function index($a = '', $b = '', $c = '') {
        // Load config to get database connection
        require_once __DIR__ . '/../../core/config.php';
        global $pdo;
        
        // Get activity ID from URL parameter
        $activityId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($activityId <= 0) {
            // Redirect to home if no valid ID
            header('Location: homet');
            exit;
        }
        
        // Fetch activity details from database
        $stmt = $pdo->prepare("SELECT * FROM activities WHERE id = ?");
        $stmt->execute([$activityId]);
        $activity = $stmt->fetch(PDO::FETCH_OBJ);
        
        if (!$activity) {
            // Activity not found, redirect to home
            header('Location: homet');
            exit;
        }
        
        // Fetch places/locations related to this activity
        try {
            $stmt = $pdo->prepare("SELECT * FROM activity_places WHERE activity_id = ? ORDER BY created_at DESC");
            $stmt->execute([$activityId]);
            $places = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            // If places table doesn't exist yet, just set empty array
            $places = [];
        }
        
        // Get posts related to this activity
        try {
            $stmt = $pdo->prepare("SELECT p.*, u.first_name, u.last_name, u.email 
                                   FROM posts p 
                                   LEFT JOIN users u ON p.user_id = u.id 
                                   WHERE p.location LIKE ? OR p.title LIKE ? OR p.description LIKE ?
                                   ORDER BY p.created_at DESC 
                                   LIMIT 6");
            $searchTerm = '%' . $activity->title . '%';
            $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
            $relatedPosts = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            $relatedPosts = [];
        }
        
        // Pass data to view
        $data = [
            'activity' => $activity,
            'places' => $places,
            'relatedPosts' => $relatedPosts
        ];
        
        $this->view('Traveller/activityview', $data);
    }
}
