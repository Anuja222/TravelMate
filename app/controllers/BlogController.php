<?php

require_once __DIR__ . '/../models/Blog.php';

class BlogController {
    
    private $blogModel;

    public function __construct() {
        $this->blogModel = new BlogModel();
    }

    /**
     * Show create blog form
     */
    public function create() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // Load create blog view
        require_once __DIR__ . '/../views/traveller/createBlog.view.php';
    }

    /**
     * Store new blog
     */
    public function store() {
        header('Content-Type: application/json');

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        if (empty($data['title']) || empty($data['content'])) {
            echo json_encode(['success' => false, 'message' => 'Title and content are required']);
            exit;
        }

        // Prepare blog data
        $blogData = [
            'traveler_id' => $_SESSION['user_id'],
            'title' => $data['title'],
            'content' => $data['content'],
            'excerpt' => $data['excerpt'] ?? null,
            'featured_image' => $data['featured_image'] ?? null,
            'location' => $data['location'] ?? null,
            'slug' => $this->generateSlug($data['title'])
        ];

        $result = $this->blogModel->createBlog($blogData);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Blog created successfully and pending approval']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create blog']);
        }
    }

    /**
     * Show user's blogs
     */
    public function myBlogs() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $blogs = $this->blogModel->getBlogsByUser($_SESSION['user_id']);
        require_once __DIR__ . '/../views/traveller/myBlogs.view.php';
    }

    /**
     * Show edit blog form
     */
    public function edit() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $blogId = $_GET['id'] ?? null;
        
        if (!$blogId) {
            header('Location: /traveller/myblogs');
            exit;
        }

        $blog = $this->blogModel->getBlogById($blogId);
        
        // Check if blog exists and belongs to user
        if (!$blog || $blog->traveler_id != $_SESSION['user_id']) {
            header('Location: /traveller/myblogs');
            exit;
        }

        require_once __DIR__ . '/../views/traveller/editBlog.view.php';
    }

    /**
     * Update blog
     */
    public function update() {
        header('Content-Type: application/json');

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $blogId = $data['id'] ?? null;

        if (!$blogId) {
            echo json_encode(['success' => false, 'message' => 'Blog ID is required']);
            exit;
        }

        // Verify ownership
        $blog = $this->blogModel->getBlogById($blogId);
        if (!$blog || $blog->traveler_id != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $result = $this->blogModel->updateBlog($blogId, $data);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Blog updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update blog']);
        }
    }

    /**
     * Delete blog
     */
    public function delete() {
        header('Content-Type: application/json');

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $blogId = $data['blog_id'] ?? null;

        if (!$blogId) {
            echo json_encode(['success' => false, 'message' => 'Blog ID is required']);
            exit;
        }

        // Verify ownership
        $blog = $this->blogModel->getBlogById($blogId);
        if (!$blog || $blog->traveler_id != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $result = $this->blogModel->deleteBlog($blogId);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Blog deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete blog']);
        }
    }

    /**
     * View single blog
     */
    public function view() {
        $blogId = $_GET['id'] ?? null;
        
        if (!$blogId) {
            header('Location: /blogs');
            exit;
        }

        $blog = $this->blogModel->getBlogById($blogId);
        
        if (!$blog) {
            header('Location: /blogs');
            exit;
        }

        require_once __DIR__ . '/../views/traveller/viewBlog.view.php';
    }

    /**
     * Generate URL-friendly slug from title
     */
    private function generateSlug($title) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        return $slug . '-' . time();
    }
}
