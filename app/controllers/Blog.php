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
            require_once __DIR__ . '/../core/config.php';
            require_once __DIR__ . '/../core/Model.php';
            require_once __DIR__ . '/../core/Database.php';
            require_once __DIR__ . '/../models/Post.php';
            
            $post = new Post();
            
            // Handle image upload
            $imagePath = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Use absolute path from public directory
                $uploadDir = __DIR__ . '/../../public/uploads/posts/';
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
                'image' => $imagePath,
                'travel_date' => !empty($_POST['travelDate']) ? $_POST['travelDate'] : null,
                'rating' => !empty($_POST['rating']) ? $_POST['rating'] : null,
                'tags' => $_POST['tags'] ?? ''
            ];
            
            if ($post->validate($data)) {
                $result = $post->insert($data);
                ob_end_clean();
                echo json_encode(['success' => true, 'message' => 'Post created successfully']);
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
}