<?php

class Destinationview extends Controller {

    public function index($a = '', $b = '', $c = '') {
        // Load config to get database connection
        require_once __DIR__ . '/../../core/config.php';
        global $pdo;
        
        // Get destination ID from URL parameter
        $destinationId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($destinationId <= 0) {
            // Redirect to home if no valid ID
            header('Location: homet');
            exit;
        }
        
        // Fetch destination details from database
        $stmt = $pdo->prepare("SELECT * FROM destinations WHERE id = ?");
        $stmt->execute([$destinationId]);
        $destination = $stmt->fetch(PDO::FETCH_OBJ);
        
        if (!$destination) {
            // Destination not found, redirect to home
            header('Location: homet');
            exit;
        }
        
        // Fetch places related to this destination (if you have a places table)
        try {
            $stmt = $pdo->prepare("SELECT * FROM destination_places WHERE destination_id = ? ORDER BY created_at DESC");
            $stmt->execute([$destinationId]);
            $places = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            // If places table doesn't exist yet, just set empty array
            $places = [];
        }
        
        // Get posts related to this destination
        $stmt = $pdo->prepare("SELECT p.*, u.first_name, u.last_name, u.email 
                               FROM posts p 
                               LEFT JOIN users u ON p.user_id = u.id 
                               WHERE p.location LIKE ? 
                               ORDER BY p.created_at DESC 
                               LIMIT 6");
        $stmt->execute(['%' . $destination->title . '%']);
        $relatedPosts = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Pass data to view
        $data = [
            'destination' => $destination,
            'places' => $places,
            'relatedPosts' => $relatedPosts
        ];
        
        $this->view('Traveller/destinationview', $data);
    }
}
