<?php

class Post {
    
    use Model;
    
    protected $table = 'posts';
    protected $allowedColumns = [
        'user_id',
        'title',
        'location',
        'category',
        'description',
        'status',
        'image',
        'travel_date',
        'rating',
        'tags'
    ];
    
    public function validate($data) {
        $this->errors = [];
        
        if (empty($data['title'])) {
            $this->errors['title'] = "Title is required";
        }
        
        if (empty($data['location'])) {
            $this->errors['location'] = "Location is required";
        }
        
        if (empty($data['category'])) {
            $this->errors['category'] = "Category is required";
        }
        
        if (empty($data['description'])) {
            $this->errors['description'] = "Description is required";
        }
        
        if (empty($this->errors)) {
            return true;
        }
        
        return false;
    }
    
    public function getAllWithUserInfo($currentUserId = 0, $category = null) {
        $query = "SELECT 
                    p.*,
                    u.first_name,
                    u.last_name,
                    u.email,
                    u.profile_image,
                    COALESCE(p.upvotes, 0) as upvotes,
                    COALESCE(p.downvotes, 0) as downvotes,
                    (SELECT vote_type FROM post_votes WHERE post_id = p.id AND user_id = :current_user_id LIMIT 1) as user_vote
                FROM {$this->table} p
                LEFT JOIN users u ON p.user_id = u.id
                WHERE p.status = 'approved'";
                
        $params = ['current_user_id' => $currentUserId];
        
        if (!empty($category)) {
            $query .= " AND p.category = :category";
            $params['category'] = $category;
        }
        
        $query .= " ORDER BY p.created_at DESC";
        
        return $this->query($query, $params);
    }
    
    public function getUserPosts($userId) {
        $query = "SELECT 
                    p.*,
                    u.first_name,
                    u.last_name,
                    u.email
                FROM {$this->table} p
                LEFT JOIN users u ON p.user_id = u.id
                WHERE p.user_id = :user_id
                ORDER BY p.created_at DESC";
        
        return $this->query($query, ['user_id' => $userId]);
    }
    
    public function insert($data) {
        // remove keys that are not allowed columns
        foreach ($data as $key => $value) {
            if (!in_array($key, $this->allowedColumns)) {
                unset($data[$key]);
            }
        }
        
        $keys = array_keys($data);
        $query = "INSERT INTO {$this->table} (" . implode(", ", $keys) . ") 
                  VALUES (:" . implode(", :", $keys) . ")";
        
        $this->query($query, $data);
        return true;
    }
    
    public function deletePost($postId, $userId) {
        try {
            error_log("=== DELETE POST START ===");
            error_log("Post ID: $postId (type: " . gettype($postId) . ")");
            error_log("User ID: $userId (type: " . gettype($userId) . ")");
            error_log("Table: {$this->table}");
            
            // first verify the post belongs to the user
            $query = "SELECT * FROM {$this->table} WHERE id = :post_id AND user_id = :user_id";
            error_log("SELECT Query: $query");
            error_log("SELECT Parameters: " . json_encode(['post_id' => $postId, 'user_id' => $userId]));
            
            $result = $this->query($query, ['post_id' => $postId, 'user_id' => $userId]);
            
            error_log("Query result type: " . gettype($result));
            error_log("Query result: " . json_encode($result));
            
            if (!$result || !is_array($result) || count($result) === 0) {
                error_log("Post not found or doesn't belong to user. Post ID: $postId, User ID: $userId");
                error_log("=== DELETE POST FAILED (NOT FOUND) ===");
                return false; // post doesn't exist or doesn't belong to user
            }
            
            // get post data to delete image file
            $post = $result[0];
            error_log("Found post: " . json_encode($post));
            
            // delete the post from database using direct connection
            $conn = $this->connect();
            $deleteQuery = "DELETE FROM {$this->table} WHERE id = :post_id AND user_id = :user_id";
            error_log("DELETE Query: $deleteQuery");
            
            $stmt = $conn->prepare($deleteQuery);
            $deleteSuccess = $stmt->execute(['post_id' => $postId, 'user_id' => $userId]);
            $rowCount = $stmt->rowCount();
            
            error_log("DELETE executed: " . ($deleteSuccess ? 'true' : 'false'));
            error_log("Rows affected: $rowCount");
            
            if (!$deleteSuccess || $rowCount === 0) {
                error_log("Failed to execute DELETE query for post ID: $postId");
                error_log("=== DELETE POST FAILED (DELETE FAILED) ===");
                return false;
            }
            
            // delete the image file if it exists
            if (!empty($post->image)) {
                $imagePath = '../public/' . $post->image;
                error_log("Attempting to delete image: $imagePath");
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                    error_log("Deleted image file: $imagePath");
                } else {
                    error_log("Image file not found: $imagePath");
                }
            }
            
            error_log("=== DELETE POST SUCCESS ===");
            return true;
        } catch (Exception $e) {
            error_log("Error deleting post: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            error_log("=== DELETE POST EXCEPTION ===");
            return false;
        }
    }
}
