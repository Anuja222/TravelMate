<?php

require_once __DIR__ . '/../models/Blog.php';
require_once __DIR__ . '/../models/BlogNotification.php';

/**
 * AdminBlogController - Handles all admin blog management operations
 */
class AdminBlogController {
    
    private $blogModel;
    private $notificationModel;

    public function __construct() {
        $this->blogModel = new BlogModel();
        $this->notificationModel = new BlogNotificationModel();
    }

    /**
     * Display blog moderation queue (pending blogs)
     */
    public function moderationQueue() {
        // Get pending blogs
        $blogs = $this->blogModel->getPendingBlogs();
        
        // Get stats
        $stats = $this->blogModel->getBlogStats();

        // Load view
        require_once __DIR__ . '/../views/admin/content.view.php';
    }

    /**
     * Display all blogs with filters
     */
    public function allBlogs() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        
        $filters = [
            'status' => $_GET['status'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];

        $blogs = $this->blogModel->getAllBlogsFiltered($page, $perPage, $filters);
        $totalBlogs = $this->blogModel->getTotalBlogsFiltered($filters);
        $totalPages = ceil($totalBlogs / $perPage);
        $stats = $this->blogModel->getBlogStats();

        $data = [
            'blogs' => $blogs,
            'stats' => $stats,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalBlogs' => $totalBlogs,
            'filters' => $filters
        ];

        require_once __DIR__ . '/../views/admin/blogs_list.view.php';
    }

    /**
     * View blog detail for moderation
     */
    public function viewBlogDetail() {
        $blogId = $_GET['id'] ?? null;
        
        if (!$blogId) {
            header('Location: /TravelMate/public/content');
            exit;
        }

        $blog = $this->blogModel->getBlogById($blogId);
        
        if (!$blog) {
            $_SESSION['error'] = 'Blog not found';
            header('Location: /TravelMate/public/content');
            exit;
        }

        require_once __DIR__ . '/../views/admin/blog_detail.view.php';
    }

    /**
     * Approve blog (API endpoint)
     */
    public function approve() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $blogId = $data['blog_id'] ?? null;

        if (!$blogId) {
            echo json_encode(['success' => false, 'message' => 'Blog ID is required']);
            exit;
        }

        // Get blog info before approving
        $blog = $this->blogModel->getBlogById($blogId);
        
        if (!$blog) {
            echo json_encode(['success' => false, 'message' => 'Blog not found']);
            exit;
        }

        // Admin ID - use session or default to 1 for development
        $adminId = $_SESSION['user_id'] ?? 1;

        $result = $this->blogModel->approveBlog($blogId, $adminId);

        if ($result) {
            // Create notification for traveler
            $this->notificationModel->createNotification(
                $blogId,
                $blog->traveler_id,
                'approved',
                "Great news! Your blog \"{$blog->title}\" has been approved and is now live!"
            );
            
            echo json_encode(['success' => true, 'message' => 'Blog approved successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to approve blog']);
        }
        exit;
    }

    /**
     * Reject blog (API endpoint)
     */
    public function reject() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $blogId = $data['blog_id'] ?? null;
        $feedback = $data['feedback'] ?? '';

        if (!$blogId) {
            echo json_encode(['success' => false, 'message' => 'Blog ID is required']);
            exit;
        }

        // Get blog info before rejecting
        $blog = $this->blogModel->getBlogById($blogId);
        
        if (!$blog) {
            echo json_encode(['success' => false, 'message' => 'Blog not found']);
            exit;
        }

        $result = $this->blogModel->rejectBlog($blogId, $feedback);

        if ($result) {
            // Create notification for traveler
            $message = "Your blog \"{$blog->title}\" was not approved.";
            if ($feedback) {
                $message .= " Feedback: {$feedback}";
            }
            
            $this->notificationModel->createNotification(
                $blogId,
                $blog->traveler_id,
                'rejected',
                $message
            );
            
            echo json_encode(['success' => true, 'message' => 'Blog rejected successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to reject blog']);
        }
        exit;
    }

    /**
     * Delete blog (API endpoint)
     */
    public function deleteBlog() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $blogId = $data['blog_id'] ?? null;

        if (!$blogId) {
            echo json_encode(['success' => false, 'message' => 'Blog ID is required']);
            exit;
        }

        $result = $this->blogModel->deleteBlog($blogId);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Blog deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete blog']);
        }
        exit;
    }

    /**
     * Toggle featured status (API endpoint)
     */
    public function toggleFeatured() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $blogId = $data['blog_id'] ?? null;

        if (!$blogId) {
            echo json_encode(['success' => false, 'message' => 'Blog ID is required']);
            exit;
        }

        $result = $this->blogModel->toggleFeatured($blogId);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Featured status updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update featured status']);
        }
        exit;
    }

    /**
     * Bulk approve blogs (API endpoint)
     */
    public function bulkApprove() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $blogIds = $data['blog_ids'] ?? [];

        if (empty($blogIds)) {
            echo json_encode(['success' => false, 'message' => 'No blogs selected']);
            exit;
        }

        $adminId = $_SESSION['user_id'] ?? 1;
        $result = $this->blogModel->bulkApprove($blogIds, $adminId);

        if ($result) {
            // Create notifications for all travelers
            foreach ($blogIds as $blogId) {
                $blog = $this->blogModel->getBlogById($blogId);
                if ($blog) {
                    $this->notificationModel->createNotification(
                        $blogId,
                        $blog->traveler_id,
                        'approved',
                        "Great news! Your blog \"{$blog->title}\" has been approved and is now live!"
                    );
                }
            }
            
            echo json_encode(['success' => true, 'message' => count($blogIds) . ' blogs approved successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to approve blogs']);
        }
        exit;
    }

    /**
     * Bulk reject blogs (API endpoint)
     */
    public function bulkReject() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $blogIds = $data['blog_ids'] ?? [];
        $feedback = $data['feedback'] ?? '';

        if (empty($blogIds)) {
            echo json_encode(['success' => false, 'message' => 'No blogs selected']);
            exit;
        }

        $result = $this->blogModel->bulkReject($blogIds, $feedback);

        if ($result) {
            // Create notifications for all travelers
            foreach ($blogIds as $blogId) {
                $blog = $this->blogModel->getBlogById($blogId);
                if ($blog) {
                    $message = "Your blog \"{$blog->title}\" was not approved.";
                    if ($feedback) {
                        $message .= " Feedback: {$feedback}";
                    }
                    
                    $this->notificationModel->createNotification(
                        $blogId,
                        $blog->traveler_id,
                        'rejected',
                        $message
                    );
                }
            }
            
            echo json_encode(['success' => true, 'message' => count($blogIds) . ' blogs rejected successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to reject blogs']);
        }
        exit;
    }

    /**
     * Get blog stats (API endpoint)
     */
    public function getStats() {
        header('Content-Type: application/json');
        
        $stats = $this->blogModel->getBlogStats();
        echo json_encode(['success' => true, 'stats' => $stats]);
        exit;
    }
}
