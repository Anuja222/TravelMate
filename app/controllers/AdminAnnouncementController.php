<?php

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

/**
 * AdminAnnouncementController - Controller for admin announcement management
 */
class AdminAnnouncementController
{
    use Database;

    /**
     * Display the announcements page
     */
    public function index()
    {
        SessionHelper::requireAdmin();

        // Get announcements data
        $announcements = $this->getAllAnnouncements();
        $stats = $this->getAnnouncementStats();

        // Pass data to view
        require_once __DIR__ . '/../views/admin/announcement.view.php';
    }

    /**
     * Get all announcements
     */
    private function getAllAnnouncements()
    {
        $sql = "SELECT * FROM announcements ORDER BY created_at DESC";
        return $this->query($sql);
    }

    /**
     * Get announcement statistics
     */
    private function getAnnouncementStats()
    {
        $stats = [];

        // Total announcements
        $result = $this->getRow("SELECT COUNT(*) as total FROM announcements");
        $stats['total'] = $result ? $result->total : 0;

        // Active announcements
        $result = $this->getRow("SELECT COUNT(*) as total FROM announcements WHERE status = 'active'");
        $stats['active'] = $result ? $result->total : 0;

        // Total views
        $result = $this->getRow("SELECT SUM(views) as total FROM announcements");
        $stats['total_views'] = $result && $result->total ? $result->total : 0;

        return $stats;
    }

    /**
     * Create a new announcement (API endpoint)
     */
    public function create()
    {
        header('Content-Type: application/json');

        if (!SessionHelper::requireAdminApi()) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $title = substr(trim($input['title'] ?? ''), 0, 200);
        $content = substr(trim($input['content'] ?? ''), 0, 5000);
        $audience = $input['audience'] ?? 'all';
        $expiresAt = $input['expires_at'] ?? null;

        // Validate audience
        if (!in_array($audience, ['all', 'traveller', 'accommodation', 'transport'])) {
            $audience = 'all';
        }

        if (empty($title) || empty($content)) {
            echo json_encode(['success' => false, 'error' => 'Title and content are required']);
            return;
        }

        $sql = "INSERT INTO announcements (title, content, audience, expires_at) VALUES (?, ?, ?, ?)";
        $result = $this->query($sql, [$title, $content, $audience, $expiresAt]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Announcement created successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to create announcement']);
        }
    }

    /**
     * Update an announcement (API endpoint)
     */
    public function update()
    {
        header('Content-Type: application/json');

        if (!SessionHelper::requireAdminApi()) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $id = (int)($input['id'] ?? 0);
        $title = substr(trim($input['title'] ?? ''), 0, 200);
        $content = substr(trim($input['content'] ?? ''), 0, 5000);
        $audience = $input['audience'] ?? 'all';
        $status = $input['status'] ?? 'active';

        // Validate status
        if (!in_array($status, ['active', 'inactive', 'expired'])) {
            $status = 'active';
        }

        if (!$id || empty($title) || empty($content)) {
            echo json_encode(['success' => false, 'error' => 'ID, title and content are required']);
            return;
        }

        $sql = "UPDATE announcements SET title = ?, content = ?, audience = ?, status = ? WHERE id = ?";
        $result = $this->query($sql, [$title, $content, $audience, $status, $id]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Announcement updated successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update announcement']);
        }
    }

    /**
     * Delete an announcement (API endpoint)
     */
    public function delete()
    {
        header('Content-Type: application/json');

        if (!SessionHelper::requireAdminApi()) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $id = (int)($input['id'] ?? 0);

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'Announcement ID is required']);
            return;
        }

        $sql = "DELETE FROM announcements WHERE id = ?";
        $result = $this->query($sql, [$id]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Announcement deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete announcement']);
        }
    }

    /**
     * Get a single announcement (API endpoint)
     */
    public function get()
    {
        header('Content-Type: application/json');

        if (!SessionHelper::requireAdminApi()) return;

        $id = (int)($_GET['id'] ?? 0);

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'Announcement ID is required']);
            return;
        }

        $sql = "SELECT * FROM announcements WHERE id = ?";
        $result = $this->getRow($sql, [$id]);

        if ($result) {
            // Increment view count
            $this->query("UPDATE announcements SET views = views + 1 WHERE id = ?", [$id]);
            echo json_encode(['success' => true, 'data' => $result]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Announcement not found']);
        }
    }

    /**
     * Toggle announcement status (API endpoint)
     */
    public function toggleStatus()
    {
        header('Content-Type: application/json');

        if (!SessionHelper::requireAdminApi()) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $id = (int)($input['id'] ?? 0);

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'Announcement ID is required']);
            return;
        }

        // Get current status
        $current = $this->getRow("SELECT status FROM announcements WHERE id = ?", [$id]);
        if (!$current) {
            echo json_encode(['success' => false, 'error' => 'Announcement not found']);
            return;
        }

        $newStatus = $current->status === 'active' ? 'inactive' : 'active';
        $sql = "UPDATE announcements SET status = ? WHERE id = ?";
        $result = $this->query($sql, [$newStatus, $id]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Announcement status updated', 'newStatus' => $newStatus]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update announcement status']);
        }
    }
}
