<?php

class Blog extends Controller{

    public function index($a = '', $b = '' , $c = ''){
        $this->view('Traveller/blog');
    }
    
    public function store() {
        // Start output buffering to prevent any accidental output
        ob_start();
        
        try {
            header('Content-Type: application/json');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                exit;
            }
            
            // Check if user is logged in
            if (!isset($_SESSION['user']['id'])) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Please login to create a post.']);
                exit;
            }
            
            // Load config and dependencies
            require_once __DIR__ . '/../../core/config.php';
            require_once __DIR__ . '/../../core/Model.php';
            require_once __DIR__ . '/../../core/Database.php';
            require_once __DIR__ . '/../../models/Post.php';
            
            $post = new Post();
            
            // Handle image upload
            $imagePath = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Use absolute path from public directory
                $uploadDir = __DIR__ . '/../../../public/uploads/posts/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    // Store relative path for database
                    $imagePath = 'uploads/posts/' . $fileName;
                }
            }
            
            $data = [
                'user_id' => $_SESSION['user']['id'],
                'title' => $_POST['postTitle'] ?? '',
                'location' => $_POST['location'] ?? '',
                'category' => $_POST['category'] ?? '',
                'description' => $_POST['description'] ?? '',
                'status' => 'pending', // Posts require admin approval
                'image' => $imagePath,
                'travel_date' => !empty($_POST['travelDate']) ? $_POST['travelDate'] : null,
                'rating' => !empty($_POST['rating']) ? $_POST['rating'] : null,
                'tags' => $_POST['tags'] ?? ''
            ];
            
            if ($post->validate($data)) {
                $result = $post->insert($data);
                ob_end_clean();
                echo json_encode(['success' => true, 'message' => 'Post submitted for approval']);
                exit;
            } else {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Validation failed', 'errors' => $post->errors]);
                exit;
            }
        } catch (Exception $e) {
            ob_end_clean();
            error_log('Blog store error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            exit;
        }
    }
    
    public function delete() {
        // Start output buffering
        ob_start();
        
        try {
            header('Content-Type: application/json');
            
            // Check if user is logged in
            if (!isset($_SESSION['user']['id'])) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'User not logged in']);
                exit;
            }
            
            // Check if post ID is provided
            if (!isset($_POST['post_id']) || empty($_POST['post_id'])) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Post ID is required']);
                exit;
            }
            
            $postId = intval($_POST['post_id']);
            $userId = $_SESSION['user']['id'];
            
            error_log("Attempting to delete post. Post ID: $postId, User ID: $userId");
            
            // Delete the post (dependencies already loaded by routing)
            $postModel = new Post();
            $result = $postModel->deletePost($postId, $userId);
            
            ob_end_clean();
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Post deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete post or post not found']);
            }
        } catch (Exception $e) {
            error_log("Delete post exception: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
        
        exit;
    }
    
    public function vote() {
        ob_start();
        try {
            header('Content-Type: application/json');
            
            if (!isset($_SESSION['user']['id'])) {
                ob_end_clean();
                error_log("VOTE ERROR: User not logged in. Session info: " . print_r($_SESSION, true));
                echo json_encode(['success' => false, 'message' => 'User not logged in']);
                exit;
            }
            
            if (!isset($_POST['post_id']) || !isset($_POST['type'])) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Post ID and type are required']);
                exit;
            }
            
            $postId = intval($_POST['post_id']);
            $userId = $_SESSION['user']['id'];
            $type = $_POST['type']; // 'upvote' or 'downvote'
            
            if (!in_array($type, ['upvote', 'downvote'])) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Invalid vote type']);
                exit;
            }

            require_once __DIR__ . '/../../core/config.php';
            
            // Connect directly to setup table schema if not exists
            $pdo = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Create post_votes table if not exists (quick migration)
            $pdo->exec("CREATE TABLE IF NOT EXISTS post_votes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                post_id INT NOT NULL,
                user_id INT NOT NULL,
                vote_type ENUM('upvote', 'downvote') NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_user_post (post_id, user_id)
            )");
            
            // Add columns to posts table if missing
            try { $pdo->exec("ALTER TABLE posts ADD COLUMN upvotes INT DEFAULT 0"); } catch (PDOException $e) {}
            try { $pdo->exec("ALTER TABLE posts ADD COLUMN downvotes INT DEFAULT 0"); } catch (PDOException $e) {}
            
            // Check if user already voted
            $stmt = $pdo->prepare("SELECT vote_type FROM post_votes WHERE post_id = ? AND user_id = ?");
            $stmt->execute([$postId, $userId]);
            $existingVote = $stmt->fetch(PDO::FETCH_OBJ);
            
            if ($existingVote) {
                if ($existingVote->vote_type === $type) {
                    // Remove vote if clicking the same button
                    $pdo->prepare("DELETE FROM post_votes WHERE post_id = ? AND user_id = ?")->execute([$postId, $userId]);
                    $pdo->prepare("UPDATE posts SET {$type}s = GREATEST({$type}s - 1, 0) WHERE id = ?")->execute([$postId]);
                } else {
                    // Update vote type
                    $pdo->prepare("UPDATE post_votes SET vote_type = ? WHERE post_id = ? AND user_id = ?")->execute([$type, $postId, $userId]);
                    $oldType = $existingVote->vote_type;
                    $pdo->prepare("UPDATE posts SET {$type}s = {$type}s + 1, {$oldType}s = GREATEST({$oldType}s - 1, 0) WHERE id = ?")->execute([$postId]);
                }
            } else {
                // Insert new vote
                $pdo->prepare("INSERT INTO post_votes (post_id, user_id, vote_type) VALUES (?, ?, ?)")->execute([$postId, $userId, $type]);
                $pdo->prepare("UPDATE posts SET {$type}s = {$type}s + 1 WHERE id = ?")->execute([$postId]);
            }
            
            // Get updated counts
            $stmt = $pdo->prepare("SELECT upvotes, downvotes FROM posts WHERE id = ?");
            $stmt->execute([$postId]);
            $updatedPost = $stmt->fetch(PDO::FETCH_OBJ);
            
            ob_end_clean();
            echo json_encode([
                'success' => true,
                'upvotes' => $updatedPost->upvotes ?? 0,
                'downvotes' => $updatedPost->downvotes ?? 0
            ]);
            
        } catch (Exception $e) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        
        exit;
    }
}