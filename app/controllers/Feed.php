<?php

class Feed extends Controller{

    public function index($a = '', $b = '' , $c = ''){
        // Load config and dependencies
        require_once __DIR__ . '/../core/config.php';
        require_once __DIR__ . '/../core/Model.php';
        require_once __DIR__ . '/../core/Database.php';
        require_once __DIR__ . '/../models/Post.php';
        
        global $pdo;
        
        // Get posts from database
        $post = new Post();
        $posts = $post->getAllWithUserInfo();
        
        // Get trending destinations (top 3 destinations from database)
        $stmt = $pdo->prepare("SELECT id, title, slug, image FROM destinations ORDER BY created_at DESC LIMIT 3");
        $stmt->execute();
        $destinations = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Get post counts for each destination location
        $destinationPostCounts = [];
        foreach ($destinations as $destination) {
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM posts WHERE location LIKE ?");
            $stmt->execute(['%' . $destination->title . '%']);
            $destinationPostCounts[$destination->id] = $stmt->fetch(PDO::FETCH_OBJ)->count ?? 0;
        }
        
        // Get suggested travelers (random 3 travelers excluding current user)
        $currentUserId = $_SESSION['user_id'] ?? 0;
        $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, created_at FROM users WHERE id != ? AND role = 'traveller' ORDER BY RAND() LIMIT 3");
        $stmt->execute([$currentUserId]);
        $suggestedTravelers = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Get follower counts for suggested travelers (placeholder for now - set to 0)
        $travelerFollowerCounts = [];
        foreach ($suggestedTravelers as $traveler) {
            $travelerFollowerCounts[$traveler->id] = 0; // Placeholder - can be updated when followers feature is implemented
        }
        
        $data = [
            'posts' => $posts,
            'destinations' => $destinations,
            'destinationPostCounts' => $destinationPostCounts,
            'suggestedTravelers' => $suggestedTravelers,
            'travelerFollowerCounts' => $travelerFollowerCounts
        ];
        
        $this->view('Traveller/feed', $data);
    }
}