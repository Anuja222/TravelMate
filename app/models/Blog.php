<?php

if (!defined('DBNAME')) {
    require_once __DIR__ . '/../core/config.php';
}
require_once __DIR__ . '/../core/Database.php';

class BlogModel {
    use Database;
    
    protected $table = 'blogs';

    /**
     * Get all blogs with optional status filter
     */
    public function getAllBlogs($status = null) {
        if ($status) {
            $query = "SELECT b.*, u.email, u.first_name, u.last_name, CONCAT(u.first_name, ' ', u.last_name) as author_name 
                     FROM {$this->table} b 
                     LEFT JOIN users u ON b.traveler_id = u.id 
                     WHERE b.status = :status 
                     ORDER BY b.created_at DESC";
            return $this->query($query, ['status' => $status]);
        } else {
            $query = "SELECT b.*, u.email, u.first_name, u.last_name, CONCAT(u.first_name, ' ', u.last_name) as author_name 
                     FROM {$this->table} b 
                     LEFT JOIN users u ON b.traveler_id = u.id 
                     ORDER BY b.created_at DESC";
            return $this->query($query);
        }
    }

    /**
     * Get pending blogs for moderation
     */
    public function getPendingBlogs() {
        $query = "SELECT b.*, u.email, u.first_name, u.last_name, CONCAT(u.first_name, ' ', u.last_name) as author_name 
                 FROM {$this->table} b 
                 LEFT JOIN users u ON b.traveler_id = u.id 
                 WHERE b.status = 'pending' 
                 ORDER BY b.created_at DESC";
        return $this->query($query);
    }

    /**
     * Get blog by ID
     */
    public function getBlogById($id) {
        $query = "SELECT b.*, u.email, u.first_name, u.last_name, CONCAT(u.first_name, ' ', u.last_name) as author_name 
                 FROM {$this->table} b 
                 LEFT JOIN users u ON b.traveler_id = u.id 
                 WHERE b.id = :id";
        return $this->getRow($query, ['id' => $id]);
    }

    /**
     * Approve blog
     */
    public function approveBlog($id, $adminId) {
        $query = "UPDATE {$this->table} 
                 SET status = 'approved', 
                     approved_by = :admin_id, 
                     approved_at = NOW() 
                 WHERE id = :id";
        
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute([
            'id' => $id,
            'admin_id' => $adminId
        ]);
    }

    /**
     * Reject blog
     */
    public function rejectBlog($id, $feedback = null) {
        $query = "UPDATE {$this->table} 
                 SET status = 'rejected', 
                     admin_feedback = :feedback, 
                     rejected_at = NOW() 
                 WHERE id = :id";
        
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute([
            'id' => $id,
            'feedback' => $feedback
        ]);
    }

    /**
     * Get blog statistics
     */
    public function getBlogStats() {
        $query = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
                 FROM {$this->table}";
        return $this->getRow($query);
    }

    /**
     * Create new blog
     */
    public function createBlog($data) {
        $query = "INSERT INTO {$this->table} 
                 (traveler_id, title, slug, content, excerpt, featured_image, location, status) 
                 VALUES (:traveler_id, :title, :slug, :content, :excerpt, :featured_image, :location, 'pending')";
        
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute([
            'traveler_id' => $data['traveler_id'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'excerpt' => $data['excerpt'],
            'featured_image' => $data['featured_image'],
            'location' => $data['location']
        ]);
    }

    /**
     * Get blogs by user
     */
    public function getBlogsByUser($userId) {
        $query = "SELECT * FROM {$this->table} 
                 WHERE traveler_id = :user_id 
                 ORDER BY created_at DESC";
        return $this->query($query, ['user_id' => $userId]);
    }

    /**
     * Update blog
     */
    public function updateBlog($id, $data) {
        $query = "UPDATE {$this->table} 
                 SET title = :title, 
                     content = :content, 
                     excerpt = :excerpt, 
                     featured_image = :featured_image, 
                     location = :location,
                     status = 'pending'
                 WHERE id = :id";
        
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'] ?? '',
            'content' => $data['content'] ?? '',
            'excerpt' => $data['excerpt'] ?? null,
            'featured_image' => $data['featured_image'] ?? null,
            'location' => $data['location'] ?? null
        ]);
    }

    /**
     * Delete blog
     */
    public function deleteBlog($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Get approved blogs for public display
     */
    public function getApprovedBlogs($limit = null) {
        $query = "SELECT b.*, u.email, u.first_name, u.last_name, 
                 CONCAT(u.first_name, ' ', u.last_name) as author_name 
                 FROM {$this->table} b 
                 LEFT JOIN users u ON b.traveler_id = u.id 
                 WHERE b.status = 'approved' 
                 ORDER BY b.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT " . (int)$limit;
        }
        
        return $this->query($query);
    }

    /**
     * Get blog by slug
     */
    public function getBySlug($slug) {
        $query = "SELECT b.*, u.email, u.first_name, u.last_name,
                 CONCAT(u.first_name, ' ', u.last_name) as author_name 
                 FROM {$this->table} b 
                 LEFT JOIN users u ON b.traveler_id = u.id 
                 WHERE b.slug = :slug";
        return $this->getRow($query, ['slug' => $slug]);
    }

    /**
     * Get all blogs with filters and pagination for admin
     */
    public function getAllBlogsFiltered($page = 1, $perPage = 10, $filters = []) {
        $offset = (int)(($page - 1) * $perPage);
        $perPage = (int)$perPage;
        
        $query = "SELECT b.*, u.email, u.first_name, u.last_name, 
                 CONCAT(u.first_name, ' ', u.last_name) as author_name 
                 FROM {$this->table} b 
                 LEFT JOIN users u ON b.traveler_id = u.id 
                 WHERE 1=1";
        
        $params = [];

        if (!empty($filters['status'])) {
            $query .= " AND b.status = :status";
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $query .= " AND (b.title LIKE :search OR b.content LIKE :search2)";
            $params['search'] = '%' . $filters['search'] . '%';
            $params['search2'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['traveler_id'])) {
            $query .= " AND b.traveler_id = :traveler_id";
            $params['traveler_id'] = $filters['traveler_id'];
        }

        $query .= " ORDER BY b.created_at DESC LIMIT {$perPage} OFFSET {$offset}";

        return $this->query($query, $params);
    }

    /**
     * Get total count of blogs with filters
     */
    public function getTotalBlogsFiltered($filters = []) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} b WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $query .= " AND b.status = :status";
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $query .= " AND (b.title LIKE :search OR b.content LIKE :search2)";
            $params['search'] = '%' . $filters['search'] . '%';
            $params['search2'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['traveler_id'])) {
            $query .= " AND b.traveler_id = :traveler_id";
            $params['traveler_id'] = $filters['traveler_id'];
        }

        $result = $this->getRow($query, $params);
        return $result ? $result->total : 0;
    }

    /**
     * Get traveler's blogs with status counts
     */
    public function getTravelerBlogs($travelerId) {
        $query = "SELECT b.*, 
                 CONCAT(u.first_name, ' ', u.last_name) as author_name
                 FROM {$this->table} b
                 LEFT JOIN users u ON b.traveler_id = u.id
                 WHERE b.traveler_id = :traveler_id
                 ORDER BY b.created_at DESC";
        
        return $this->query($query, ['traveler_id' => $travelerId]);
    }

    /**
     * Get traveler blog stats
     */
    public function getTravelerBlogStats($travelerId) {
        $query = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                    COALESCE(SUM(views_count), 0) as total_views
                 FROM {$this->table}
                 WHERE traveler_id = :traveler_id";
        
        return $this->getRow($query, ['traveler_id' => $travelerId]);
    }

    /**
     * Get featured blogs
     */
    public function getFeaturedBlogs($limit = 5) {
        $query = "SELECT b.*, u.first_name, u.last_name,
                 CONCAT(u.first_name, ' ', u.last_name) as author_name
                 FROM {$this->table} b
                 LEFT JOIN users u ON b.traveler_id = u.id
                 WHERE b.status = 'approved' AND b.is_featured = 1
                 ORDER BY b.created_at DESC
                 LIMIT " . (int)$limit;
        
        return $this->query($query);
    }

    /**
     * Get top blogs by views
     */
    public function getTopBlogs($limit = 10) {
        $query = "SELECT b.*, u.first_name, u.last_name,
                 CONCAT(u.first_name, ' ', u.last_name) as author_name
                 FROM {$this->table} b
                 LEFT JOIN users u ON b.traveler_id = u.id
                 WHERE b.status = 'approved'
                 ORDER BY b.views_count DESC
                 LIMIT " . (int)$limit;
        
        return $this->query($query);
    }

    /**
     * Increment view count
     */
    public function incrementViews($id) {
        $query = "UPDATE {$this->table} SET views_count = views_count + 1 WHERE id = :id";
        
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Get blog images
     */
    public function getImages($blogId) {
        $query = "SELECT * FROM blog_images WHERE blog_id = :blog_id ORDER BY sort_order";
        return $this->query($query, ['blog_id' => $blogId]);
    }

    /**
     * Get blog categories
     */
    public function getCategories($blogId = null) {
        if ($blogId) {
            $query = "SELECT c.* FROM blog_categories c
                     INNER JOIN blog_category_pivot p ON c.id = p.category_id
                     WHERE p.blog_id = :blog_id";
            return $this->query($query, ['blog_id' => $blogId]);
        } else {
            $query = "SELECT * FROM blog_categories ORDER BY name";
            return $this->query($query);
        }
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured($id) {
        $query = "UPDATE {$this->table} SET is_featured = NOT is_featured WHERE id = :id";
        
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Bulk approve blogs
     */
    public function bulkApprove($ids, $adminId) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "UPDATE {$this->table} 
                 SET status = 'approved', 
                     approved_by = ?, 
                     approved_at = NOW() 
                 WHERE id IN ($placeholders)";
        
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        $params = array_merge([$adminId], $ids);
        return $stmt->execute($params);
    }

    /**
     * Bulk reject blogs
     */
    public function bulkReject($ids, $feedback = null) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "UPDATE {$this->table} 
                 SET status = 'rejected', 
                     admin_feedback = ?, 
                     rejected_at = NOW() 
                 WHERE id IN ($placeholders)";
        
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        $params = array_merge([$feedback], $ids);
        return $stmt->execute($params);
    }
}
