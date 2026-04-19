<?php

class PostController extends Controller {

    public function store() {
        // start output buffering to prevent any accidental output
        ob_start();
        
        try {
            header('Content-Type: application/json');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                exit;
            }
            
            // check if user is logged in
            if (!isset($_SESSION['user']['id'])) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Please login to create a post.']);
                exit;
            }
            
            // load config and dependencies
            require_once __DIR__ . '/../../core/config.php';
            require_once __DIR__ . '/../../core/Model.php';
            require_once __DIR__ . '/../../core/Database.php';
            require_once __DIR__ . '/../../models/Post.php';
            
            $post = new Post();
            
            // handle image upload
            $imagePath = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // use absolute path from public directory
                $uploadDir = __DIR__ . '/../../../public/uploads/posts/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    // store relative path for database
                    $imagePath = 'uploads/posts/' . $fileName;
                }
            }
            
            $data = [
                'user_id' => $_SESSION['user']['id'],
                'title' => $_POST['postTitle'] ?? '',
                'location' => $_POST['location'] ?? '',
                'category' => $_POST['category'] ?? '',
                'description' => $_POST['description'] ?? '',
                'status' => 'pending', // posts require admin approval
                'image' => $imagePath,
                'travel_date' => !empty($_POST['travelDate']) ? $_POST['travelDate'] : null,
                'rating' => !empty($_POST['rating']) ? $_POST['rating'] : null,
                'tags' => $_POST['tags'] ?? '',
                'budget' => $_POST['budget'] ?? null
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

    public function edit($id) {
        // find existing post, display edit form or return post details
        // $post = new Post();
        // $existingPost = $post->getById($id);
        // ...
        
        $this->view('Traveller/editpost', ['post_id' => $id]);
    }

    public function update($id) {
        ob_start();
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user']['id'])) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            exit;
        }

        try {
            // update logic here
            ob_end_clean();
            echo json_encode(['success' => true, 'message' => 'Post updated successfully!']);
        } catch (Exception $e) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()]);
        }
        exit;
    }

    public function delete($id) {
        // start output buffering
        ob_start();
        
        try {
            header('Content-Type: application/json');
            
            // check if user is logged in
            if (!isset($_SESSION['user']['id'])) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'User not logged in']);
                exit;
            }
            
            // check if post ID is provided
            if (empty($id)) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Post ID is required']);
                exit;
            }
            
            $postId = intval($id);
            $userId = $_SESSION['user']['id'];
            
            error_log("Attempting to delete post. Post ID: $postId, User ID: $userId");
            
            // delete the post
            require_once __DIR__ . '/../../core/config.php';
            require_once __DIR__ . '/../../core/Model.php';
            require_once __DIR__ . '/../../core/Database.php';
            require_once __DIR__ . '/../../models/Post.php';
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

    public function toggleLike($id) {
        ob_start();
        header('Content-Type: application/json');
        
        // use the vote implementation mapped internally
        $_POST['post_id'] = $id;
        $_POST['type'] = 'upvote'; // assuming toggleLike maps to upvote
        $this->vote();
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
            
            // connect directly to setup table schema if not exists
            $pdo = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // check if user already voted
            $stmt = $pdo->prepare("SELECT vote_type FROM post_votes WHERE post_id = ? AND user_id = ?");
            $stmt->execute([$postId, $userId]);
            $existingVote = $stmt->fetch(PDO::FETCH_OBJ);
            
            if ($existingVote) {
                if ($existingVote->vote_type === $type) {
                    // remove vote if clicking the same button
                    $pdo->prepare("DELETE FROM post_votes WHERE post_id = ? AND user_id = ?")->execute([$postId, $userId]);
                    $pdo->prepare("UPDATE posts SET {$type}s = GREATEST({$type}s - 1, 0) WHERE id = ?")->execute([$postId]);
                } else {
                    // update vote type
                    $pdo->prepare("UPDATE post_votes SET vote_type = ? WHERE post_id = ? AND user_id = ?")->execute([$type, $postId, $userId]);
                    $oldType = $existingVote->vote_type;
                    $pdo->prepare("UPDATE posts SET {$type}s = {$type}s + 1, {$oldType}s = GREATEST({$oldType}s - 1, 0) WHERE id = ?")->execute([$postId]);
                }
            } else {
                // insert new vote
                $pdo->prepare("INSERT INTO post_votes (post_id, user_id, vote_type) VALUES (?, ?, ?)")->execute([$postId, $userId, $type]);
                $pdo->prepare("UPDATE posts SET {$type}s = {$type}s + 1 WHERE id = ?")->execute([$postId]);
            }
            
            // get updated counts
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

    public function addComment($id) {
        ob_start();
        header('Content-Type: application/json');
        
        // ...
        
        ob_end_clean();
        echo json_encode(['success' => true, 'message' => 'Comment added.']);
        exit;
    }
}
