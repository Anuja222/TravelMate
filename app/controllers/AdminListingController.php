<?php

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/Database.php';

/**
 * AdminListingController - Controller for admin listing management
 * Handles destinations, accommodations, and vehicles
 */
class AdminListingController
{
    use Database;

    /**
     * Display the listings page with all types of listings
     */
    public function index()
    {
        // Get filter parameters
        $type = $_GET['type'] ?? 'all';
        $status = $_GET['status'] ?? '';
        $search = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Get listings based on type
        $destinations = $this->getDestinations($search);
        $accommodations = $this->getAccommodations($search, $status);
        $vehicles = $this->getVehicles($search, $status);

        // Get statistics
        $stats = $this->getListingStats();

        // Pass data to view
        require_once __DIR__ . '/../views/admin/ViewListing.view.php';
    }

    /**
     * Get all destinations
     */
    private function getDestinations($search = '')
    {
        $sql = "SELECT d.*, 
                (SELECT COUNT(*) FROM destination_places WHERE destination_id = d.id) as places_count
                FROM destinations d WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (d.title LIKE ? OR d.description LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $sql .= " ORDER BY d.created_at DESC";

        return $this->query($sql, $params);
    }

    /**
     * Get all accommodations
     */
    private function getAccommodations($search = '', $status = '')
    {
        $sql = "SELECT a.*, 
                u.first_name, u.last_name, u.email as owner_email,
                (SELECT COUNT(*) FROM accommodation_images WHERE accommodation_id = a.id) as images_count,
                (SELECT COUNT(*) FROM bookings WHERE room_id = a.id) as bookings_count
                FROM accommodations a
                LEFT JOIN users u ON a.user_id = u.id
                WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (a.title LIKE ? OR a.description LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($status)) {
            $sql .= " AND a.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY a.created_at DESC";

        return $this->query($sql, $params);
    }

    /**
     * Get all vehicles
     */
    private function getVehicles($search = '', $status = '')
    {
        $sql = "SELECT v.*, 
                u.first_name, u.last_name, u.email as owner_email,
                (SELECT COUNT(*) FROM vehicle_images WHERE vehicle_id = v.id) as images_count,
                0 as bookings_count
                FROM vehicles v
                LEFT JOIN users u ON v.user_id = u.id
                WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (v.model LIKE ? OR v.vehicle_type LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($status)) {
            $sql .= " AND v.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY v.created_at DESC";

        return $this->query($sql, $params);
    }

    /**
     * Get listing statistics
     */
    private function getListingStats()
    {
        $stats = [];

        // Destinations count
        $result = $this->getRow("SELECT COUNT(*) as total FROM destinations");
        $stats['destinations'] = $result ? $result->total : 0;

        // Accommodations count
        $result = $this->getRow("SELECT COUNT(*) as total FROM accommodations");
        $stats['accommodations'] = $result ? $result->total : 0;

        // Active accommodations
        $result = $this->getRow("SELECT COUNT(*) as total FROM accommodations WHERE status = 'active'");
        $stats['active_accommodations'] = $result ? $result->total : 0;

        // Vehicles count
        $result = $this->getRow("SELECT COUNT(*) as total FROM vehicles");
        $stats['vehicles'] = $result ? $result->total : 0;

        // Active vehicles
        $result = $this->getRow("SELECT COUNT(*) as total FROM vehicles WHERE status = 'active'");
        $stats['active_vehicles'] = $result ? $result->total : 0;

        // Total listings
        $stats['total'] = $stats['destinations'] + $stats['accommodations'] + $stats['vehicles'];

        return $stats;
    }

    /**
     * Approve a listing (API endpoint)
     */
    public function approve()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $type = $input['type'] ?? '';
        $id = $input['id'] ?? 0;

        if (!$type || !$id) {
            echo json_encode(['success' => false, 'error' => 'Type and ID are required']);
            return;
        }

        $table = $this->getTableByType($type);
        if (!$table) {
            echo json_encode(['success' => false, 'error' => 'Invalid listing type']);
            return;
        }

        $sql = "UPDATE {$table} SET status = 'active' WHERE id = ?";
        $result = $this->query($sql, [$id]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Listing approved successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to approve listing']);
        }
    }

    /**
     * Suspend a listing (API endpoint)
     */
    public function suspend()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $type = $input['type'] ?? '';
        $id = $input['id'] ?? 0;

        if (!$type || !$id) {
            echo json_encode(['success' => false, 'error' => 'Type and ID are required']);
            return;
        }

        $table = $this->getTableByType($type);
        if (!$table) {
            echo json_encode(['success' => false, 'error' => 'Invalid listing type']);
            return;
        }

        $sql = "UPDATE {$table} SET status = 'suspended' WHERE id = ?";
        $result = $this->query($sql, [$id]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Listing suspended successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to suspend listing']);
        }
    }

    /**
     * Delete a listing (API endpoint)
     */
    public function delete()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $type = $input['type'] ?? '';
        $id = $input['id'] ?? 0;

        if (!$type || !$id) {
            echo json_encode(['success' => false, 'error' => 'Type and ID are required']);
            return;
        }

        $table = $this->getTableByType($type);
        if (!$table) {
            echo json_encode(['success' => false, 'error' => 'Invalid listing type']);
            return;
        }

        // Delete related data based on type
        if ($type === 'accommodation') {
            $this->query("DELETE FROM accommodation_images WHERE accommodation_id = ?", [$id]);
        } elseif ($type === 'vehicle') {
            $this->query("DELETE FROM vehicle_images WHERE vehicle_id = ?", [$id]);
        } elseif ($type === 'destination') {
            $this->query("DELETE FROM destination_places WHERE destination_id = ?", [$id]);
        }

        $sql = "DELETE FROM {$table} WHERE id = ?";
        $result = $this->query($sql, [$id]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Listing deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete listing']);
        }
    }

    /**
     * Get table name by listing type
     */
    private function getTableByType($type)
    {
        $tables = [
            'destination' => 'destinations',
            'accommodation' => 'accommodations',
            'vehicle' => 'vehicles'
        ];

        return $tables[$type] ?? null;
    }

    /**
     * Get listing details (API endpoint)
     */
    public function getDetails()
    {
        header('Content-Type: application/json');

        $type = $_GET['type'] ?? '';
        $id = $_GET['id'] ?? 0;

        if (!$type || !$id) {
            echo json_encode(['success' => false, 'error' => 'Type and ID are required']);
            return;
        }

        $data = null;

        switch ($type) {
            case 'destination':
                $data = $this->getRow("SELECT * FROM destinations WHERE id = ?", [$id]);
                break;
            case 'accommodation':
                $data = $this->getRow("SELECT a.*, u.first_name, u.last_name FROM accommodations a 
                                       LEFT JOIN users u ON a.user_id = u.id WHERE a.id = ?", [$id]);
                break;
            case 'vehicle':
                $data = $this->getRow("SELECT v.*, u.first_name, u.last_name FROM vehicles v 
                                       LEFT JOIN users u ON v.user_id = u.id WHERE v.id = ?", [$id]);
                break;
        }

        if ($data) {
            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Listing not found']);
        }
    }
}
