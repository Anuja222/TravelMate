<?php

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/Database.php';

class AdminTransportController {
    use Database;
    
    private $adminId;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->adminId = $_SESSION['user_id'] ?? 1;
    }

    /**
     * Display all vehicles in a grid
     */
    public function index() {
        // Get filter parameters
        $type = $_GET['type'] ?? '';
        $district = $_GET['district'] ?? '';
        $status = $_GET['status'] ?? '';
        $search = $_GET['search'] ?? '';
        $sort = $_GET['sort'] ?? 'newest';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        // Build query conditions
        $conditions = ["v.deleted_at IS NULL"];
        $params = [];

        if ($type) {
            $conditions[] = "v.vehicle_type = :type";
            $params['type'] = $type;
        }

        if ($district) {
            $conditions[] = "v.working_district = :district";
            $params['district'] = $district;
        }

        if ($status) {
            $conditions[] = "v.status = :status";
            $params['status'] = $status;
        }

        if ($search) {
            $conditions[] = "(v.vehicle_model LIKE :search OR v.vehicle_number LIKE :search2 OR v.working_district LIKE :search3 OR v.driver_name LIKE :search4)";
            $params['search'] = "%{$search}%";
            $params['search2'] = "%{$search}%";
            $params['search3'] = "%{$search}%";
            $params['search4'] = "%{$search}%";
        }

        $whereClause = implode(' AND ', $conditions);

        // Sort options
        $orderBy = match($sort) {
            'oldest' => 'v.created_at ASC',
            'price_low' => 'v.price_per_day ASC',
            'price_high' => 'v.price_per_day DESC',
            'views' => 'v.views_count DESC',
            default => 'v.created_at DESC'
        };

        // Get total count
        $countQuery = "SELECT COUNT(*) as total FROM vehicles v WHERE {$whereClause}";
        $countResult = $this->getRow($countQuery, $params);
        $totalItems = $countResult->total ?? 0;
        $totalPages = ceil($totalItems / $perPage);

        // Get vehicles with transporter info
        $query = "SELECT 
                    v.*,
                    CONCAT(u.first_name, ' ', u.last_name) as transporter_name,
                    u.email as transporter_email,
                    u.phone as transporter_phone,
                    (SELECT image_path FROM vehicle_images WHERE vehicle_id = v.id AND is_main = 1 LIMIT 1) as main_image
                  FROM vehicles v
                  LEFT JOIN users u ON v.user_id = u.id
                  WHERE {$whereClause}
                  ORDER BY {$orderBy}
                  LIMIT {$perPage} OFFSET {$offset}";

        $vehicles = $this->query($query, $params) ?: [];

        // Get statistics
        $stats = $this->getStatistics();

        // Get unique districts for filter
        $districts = $this->getUniqueDistricts();

        // Get vehicle types for filter
        $vehicleTypes = [
            'car' => 'Car',
            'van' => 'Van',
            'suv' => 'SUV',
            'bus' => 'Bus',
            'minibus' => 'Mini Bus',
            'tuk_tuk' => 'Tuk Tuk',
            'bike' => 'Bike',
            'scooter' => 'Scooter'
        ];

        // Status options
        $statusOptions = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'maintenance' => 'Maintenance',
            'booked' => 'Booked'
        ];

        $data = [
            'vehicles' => $vehicles,
            'stats' => $stats,
            'districts' => $districts,
            'vehicleTypes' => $vehicleTypes,
            'statusOptions' => $statusOptions,
            'filters' => [
                'type' => $type,
                'district' => $district,
                'status' => $status,
                'search' => $search,
                'sort' => $sort
            ],
            'pagination' => [
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalItems' => $totalItems,
                'perPage' => $perPage
            ],
            'pageTitle' => 'Transport / Vehicle Listings'
        ];

        require_once __DIR__ . '/../views/admin/transport/index.view.php';
    }

    /**
     * View single vehicle details
     */
    public function view() {
        $id = intval($_GET['id'] ?? 0);

        if (!$id) {
            $_SESSION['error'] = 'Invalid vehicle ID';
            header('Location: ' . ROOT . '/admin/transport');
            exit;
        }

        // Get vehicle with transporter details
        $query = "SELECT 
                    v.*,
                    u.id as transporter_user_id,
                    CONCAT(u.first_name, ' ', u.last_name) as transporter_name,
                    u.email as transporter_email,
                    u.phone as transporter_phone,
                    u.created_at as transporter_member_since,
                    (SELECT COUNT(*) FROM vehicles WHERE user_id = u.id AND deleted_at IS NULL) as transporter_total_vehicles
                  FROM vehicles v
                  LEFT JOIN users u ON v.user_id = u.id
                  WHERE v.id = :id AND v.deleted_at IS NULL";

        $vehicle = $this->getRow($query, ['id' => $id]);

        if (!$vehicle) {
            $_SESSION['error'] = 'Vehicle not found';
            header('Location: ' . ROOT . '/admin/transport');
            exit;
        }

        // Get all images
        $imagesQuery = "SELECT * FROM vehicle_images WHERE vehicle_id = :id ORDER BY is_main DESC";
        $images = $this->query($imagesQuery, ['id' => $id]) ?: [];

        // Increment view count
        $this->incrementViewCount($id);

        // Create transporter object for view
        $transporter = (object)[
            'id' => $vehicle->transporter_user_id ?? null,
            'name' => $vehicle->transporter_name ?? 'Unknown',
            'email' => $vehicle->transporter_email ?? 'N/A',
            'phone' => $vehicle->transporter_phone ?? null,
            'member_since' => $vehicle->transporter_member_since ?? null,
            'total_vehicles' => $vehicle->transporter_total_vehicles ?? 0,
            'role' => 'Transporter'
        ];

        $data = [
            'vehicle' => $vehicle,
            'images' => $images,
            'transporter' => $transporter,
            'pageTitle' => ($vehicle->vehicle_model ?? 'Vehicle') . ' - Details'
        ];

        require_once __DIR__ . '/../views/admin/transport/view.view.php';
    }

    /**
     * Delete vehicle (soft delete)
     */
    public function delete() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id = intval($data['id'] ?? 0);

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Invalid vehicle ID']);
            exit;
        }

        // Check if vehicle exists
        $checkQuery = "SELECT id, vehicle_model, vehicle_number FROM vehicles WHERE id = :id AND deleted_at IS NULL";
        $vehicle = $this->getRow($checkQuery, ['id' => $id]);

        if (!$vehicle) {
            echo json_encode(['success' => false, 'message' => 'Vehicle not found']);
            exit;
        }

        // Soft delete
        $query = "UPDATE vehicles 
                  SET deleted_at = NOW(), 
                      deleted_by = :admin_id,
                      updated_at = NOW()
                  WHERE id = :id";

        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        $result = $stmt->execute(['id' => $id, 'admin_id' => $this->adminId]);

        if ($result) {
            echo json_encode([
                'success' => true, 
                'message' => 'Vehicle deleted successfully'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete vehicle']);
        }
    }

    /**
     * Get statistics API
     */
    public function getStats() {
        header('Content-Type: application/json');
        $stats = $this->getStatistics();
        echo json_encode(['success' => true, 'stats' => $stats]);
    }

    /**
     * Get vehicle statistics
     */
    private function getStatistics() {
        $query = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN vehicle_type IN ('car', 'suv') THEN 1 ELSE 0 END) as cars_suvs,
                    SUM(CASE WHEN vehicle_type IN ('van', 'bus', 'minibus') THEN 1 ELSE 0 END) as vans_buses,
                    SUM(CASE WHEN vehicle_type IN ('tuk_tuk', 'bike', 'scooter') THEN 1 ELSE 0 END) as two_three_wheelers,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as available,
                    COALESCE(SUM(views_count), 0) as total_views
                  FROM vehicles
                  WHERE deleted_at IS NULL";

        $result = $this->getRow($query);
        
        return [
            'total' => $result->total ?? 0,
            'cars_suvs' => $result->cars_suvs ?? 0,
            'vans_buses' => $result->vans_buses ?? 0,
            'two_three_wheelers' => $result->two_three_wheelers ?? 0,
            'available' => $result->available ?? 0,
            'total_views' => $result->total_views ?? 0
        ];
    }

    /**
     * Get unique districts for filter dropdown
     */
    private function getUniqueDistricts() {
        $query = "SELECT DISTINCT working_district FROM vehicles 
                  WHERE deleted_at IS NULL AND working_district IS NOT NULL AND working_district != ''
                  ORDER BY working_district";
        $result = $this->query($query);
        return $result ?: [];
    }

    /**
     * Increment view count
     */
    private function incrementViewCount($id) {
        $query = "UPDATE vehicles SET views_count = COALESCE(views_count, 0) + 1 WHERE id = :id";
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        $stmt->execute(['id' => $id]);
    }
}
