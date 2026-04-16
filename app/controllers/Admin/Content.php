<?php

class Content extends Controller{

    public function index($a = '', $b = '' , $c = ''){
        // Check if user is admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: login');
            exit;
        }
        
        // Get global database connection
        global $pdo;
        
        // Fetch pending posts with user information
        $stmt = $pdo->prepare("
            SELECT p.*, u.first_name, u.last_name, u.email 
            FROM posts p 
            JOIN users u ON p.user_id = u.id 
            WHERE p.status = 'pending' 
            ORDER BY p.created_at DESC
        ");
        $stmt->execute();
        $pendingPosts = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Fetch approved posts currently visible in feed
        $approvedStmt = $pdo->prepare("\n            SELECT p.*, u.first_name, u.last_name, u.email \n            FROM posts p \n            JOIN users u ON p.user_id = u.id \n            WHERE p.status = 'approved' \n            ORDER BY p.created_at DESC\n        ");
        $approvedStmt->execute();
        $approvedPosts = $approvedStmt->fetchAll(PDO::FETCH_OBJ);
        
        // Pass data to view
        $data = [
            'pendingPosts' => $pendingPosts,
            'approvedPosts' => $approvedPosts
        ];
        
        $this->view('admin/content', $data);
    }
    
    public function approve() {
        ob_start();
        
        try {
            header('Content-Type: application/json');
            
            // Check if user is admin
            if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }
            
            if (!isset($_POST['post_id'])) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Post ID required']);
                exit;
            }
            
            global $pdo;
            if (!isset($pdo)) { require_once __DIR__ . '/../../../config/database.php'; }
            
            $postId = intval($_POST['post_id']);
            $stmt = $pdo->prepare("UPDATE posts SET status = 'approved' WHERE id = ?");
            $result = $stmt->execute([$postId]);
            
            ob_end_clean();
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Post approved successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to approve post']);
            }
        } catch (Throwable $e) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        
        exit;
    }
    
    public function reject() {
        ob_start();
        
        try {
            header('Content-Type: application/json');
            
            // Check if user is admin
            if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }
            
            if (!isset($_POST['post_id'])) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Post ID required']);
                exit;
            }
            
            global $pdo;
            if (!isset($pdo)) { require_once __DIR__ . '/../../../config/database.php'; }
            
            $postId = intval($_POST['post_id']);
            $stmt = $pdo->prepare("UPDATE posts SET status = 'rejected' WHERE id = ?");
            $result = $stmt->execute([$postId]);
            
            ob_end_clean();
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Post rejected']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to reject post']);
            }
        } catch (Throwable $e) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        
        exit;
    }

    public function delete() {
        ob_start();

        try {
            header('Content-Type: application/json');

            if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }

            if (!isset($_POST['post_id'])) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Post ID required']);
                exit;
            }

            global $pdo;
            if (!isset($pdo)) { require_once __DIR__ . '/../../../config/database.php'; }

            $postId = intval($_POST['post_id']);

            $imageStmt = $pdo->prepare("SELECT image FROM posts WHERE id = ?");
            $imageStmt->execute([$postId]);
            $post = $imageStmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
            $result = $stmt->execute([$postId]);

            if ($result && $stmt->rowCount() > 0 && !empty($post['image'])) {
                $relativePath = ltrim($post['image'], '/');
                $filePath = __DIR__ . '/../../../public/' . $relativePath;
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }

            ob_end_clean();

            if ($result && $stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Post deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete post']);
            }
        } catch (Throwable $e) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }

        exit;
    }
}
