<?php

class Profile extends Controller {

    public function index($username = null) {
        global $pdo;
        
        // Get the target user (username parameter is actually email)
        if ($username) {
            // Find by email
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            
            if (!$user) {
                // User not found, redirect to 404
                $this->view('404');
                return;
            }
        } else {
            // No username provided, show current user's profile
            if (!isset($_SESSION['user_id'])) {
                header('Location: login');
                exit;
            }
            
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            
            if (!$user) {
                header('Location: login');
                exit;
            }
        }

        // Get user statistics
        $stats = $this->getUserStats($user->id);
        
        // Get user posts
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user->id]);
        $posts = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Get user preferences
        $preferences = $this->getUserPreferences($user->id);
        
        // Pass data to view
        $data = [
            'user' => $user,
            'stats' => $stats,
            'posts' => $posts ?: [],
            'preferences' => $preferences
        ];
        
        $this->view('traveller/profile', $data);
    }
    
    private function getUserStats($userId) {
        try {
            // Using the database connection from config
            global $pdo;
            
            if (!isset($pdo)) {
                return [
                    'posts_count' => 0,
                    'followers_count' => 0,
                    'following_count' => 0,
                    'destinations_count' => 0
                ];
            }
            
            // Get posts count
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM posts WHERE user_id = ?");
            $stmt->execute([$userId]);
            $postsCount = $stmt->fetch(PDO::FETCH_OBJ)->count ?? 0;
            
            // Get followers count (placeholder - implement when followers table exists)
            $followersCount = 0;
            
            // Get following count (placeholder - implement when followers table exists)
            $followingCount = 0;
            
            // Get destinations count (placeholder - based on unique locations in posts)
            $stmt = $pdo->prepare("SELECT COUNT(DISTINCT location) as count FROM posts WHERE user_id = ? AND location IS NOT NULL AND location != ''");
            $stmt->execute([$userId]);
            $destinationsCount = $stmt->fetch(PDO::FETCH_OBJ)->count ?? 0;
            
            return [
                'posts_count' => $postsCount,
                'followers_count' => $followersCount,
                'following_count' => $followingCount,
                'destinations_count' => $destinationsCount
            ];
        } catch (Exception $e) {
            error_log('Error getting user stats: ' . $e->getMessage());
            return [
                'posts_count' => 0,
                'followers_count' => 0,
                'following_count' => 0,
                'destinations_count' => 0
            ];
        }
    }
    
    private function getUserPreferences($userId) {
        try {
            global $pdo;
            
            if (!isset($pdo)) {
                return [
                    'environments' => [],
                    'activities' => []
                ];
            }
            
            // Get user environments
            $stmt = $pdo->prepare("SELECT environment_name FROM user_environments WHERE user_id = ?");
            $stmt->execute([$userId]);
            $environments = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Get user activities
            $stmt = $pdo->prepare("SELECT activity_name FROM user_activities WHERE user_id = ?");
            $stmt->execute([$userId]);
            $activities = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            return [
                'environments' => $environments ?: [],
                'activities' => $activities ?: []
            ];
        } catch (Exception $e) {
            error_log('Error getting user preferences: ' . $e->getMessage());
            return [
                'environments' => [],
                'activities' => []
            ];
        }
    }
}
