<?php

class PostController extends Controller {

    public function store() {
        ob_start();
        try {
            header('Content-Type: application/json');
            
            if (!isset($_SESSION['user']['id'])) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Please login to create a post.']);
                exit;
            }
            
            // Handle post creation logic here
            $postData = [
                'user_id' => $_SESSION['user']['id'],
                'title' => $_POST['postTitle'] ?? '',
                'location' => $_POST['location'] ?? '',
                'category' => $_POST['category'] ?? '',
                'description' => $_POST['description'] ?? '',
                'status' => 'pending', 
                'image' => '' // Handle image upload properly here
            ];
            
            $post = new Post();
            if ($post->validate($postData)) {
                $post->insert($postData);
                ob_end_clean();
                echo json_encode(['success' => true, 'message' => 'Post created successfully!']);
            } else {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Validation failed.', 'errors' => $post->errors]);
            }
        } catch (Exception $e) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
        exit;
    }

    public function edit($id) {
        // Find existing post, display edit form or return post details
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
            // Update logic here
            ob_end_clean();
            echo json_encode(['success' => true, 'message' => 'Post updated successfully!']);
        } catch (Exception $e) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()]);
        }
        exit;
    }

    public function delete($id) {
        ob_start();
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user']['id'])) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            exit;
        }

        try {
            $post = new Post();
            $result = $post->deletePost($id, $_SESSION['user']['id']);
            
            ob_end_clean();
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Post deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete post.']);
            }
        } catch (Exception $e) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
        exit;
    }

    public function toggleLike($id) {
        ob_start();
        header('Content-Type: application/json');
        
        // Similar to the vote() method in Blog
        // ...
        
        ob_end_clean();
        echo json_encode(['success' => true, 'message' => 'Like toggled.']);
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
