<?php

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/Database.php';

class AdminAccommodationController {
    use Database;
    
    private $adminId;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->adminId = $_SESSION['user_id'] ?? 1;
    }

    /**
     * Display all accommodations in a grid
     */
    public function index() {
        // Get filter parameters
        $type = $_GET['type'] ?? '';
        $city = $_GET['city'] ?? '';
        $search = $_GET['search'] ?? '';
        $sort = $_GET['sort'] ?? 'newest';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        // Build query conditions
        $conditions = ["a.deleted_at IS NULL"];
        $params = [];

        if ($type) {
            $conditions[] = "a.property_type = :type";
            $params['type'] = $type;
        }

        if ($city) {
            $conditions[] = "a.city = :city";
            $params['city'] = $city;
        }

        if ($search) {
            $conditions[] = "(a.title LIKE :search OR a.city LIKE :search2 OR a.address LIKE :search3)";
            $params['search'] = "%{$search}%";
            $params['search2'] = "%{$search}%";
            $params['search3'] = "%{$search}%";
        }

        $whereClause = implode(' AND ', $conditions);

        // Sort options
        $orderBy = match($sort) {
            'oldest' => 'a.created_at ASC',
            'price_low' => 'a.price_per_night ASC',
            'price_high' => 'a.price_per_night DESC',
            'views' => 'a.views_count DESC',
            default => 'a.created_at DESC'
        };

        // Get total count
        $countQuery = "SELECT COUNT(*) as total FROM accommodations a WHERE {$whereClause}";
        $countResult = $this->getRow($countQuery, $params);
        $totalItems = $countResult->total ?? 0;
        $totalPages = ceil($totalItems / $perPage);

        // Get accommodations
        $query = "SELECT 
                    a.*,
                    CONCAT(u.first_name, ' ', u.last_name) as provider_name,
                    u.email as provider_email,
                    (SELECT image_path FROM accommodation_images WHERE accommodation_id = a.id AND is_main = 1 LIMIT 1) as main_image
                  FROM accommodations a
                  LEFT JOIN users u ON a.user_id = u.id
                  WHERE {$whereClause}
                  ORDER BY {$orderBy}
                  LIMIT {$perPage} OFFSET {$offset}";

        $accommodations = $this->query($query, $params) ?: [];

        // Get statistics
        $stats = $this->getStatistics();

        // Get unique cities for filter
        $cities = $this->getUniqueCities();

        // Get property types for filter
        $propertyTypes = [
            'hotel' => 'Hotel',
            'resort' => 'Resort',
            'villa' => 'Villa',
            'apartment' => 'Apartment',
            'guesthouse' => 'Guesthouse',
            'hostel' => 'Hostel',
            'homestay' => 'Homestay',
            'boutique' => 'Boutique'
        ];

        $data = [
            'accommodations' => $accommodations,
            'stats' => $stats,
            'cities' => $cities,
            'propertyTypes' => $propertyTypes,
            'filters' => [
                'type' => $type,
                'city' => $city,
                'search' => $search,
                'sort' => $sort
            ],
            'pagination' => [
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalItems' => $totalItems,
                'perPage' => $perPage
            ],
            'pageTitle' => 'Accommodation Listings'
        ];

        require_once __DIR__ . '/../views/admin/accommodations/index.view.php';
    }

    /**
     * View single accommodation details
     */
    public function view() {
        $id = intval($_GET['id'] ?? 0);

        if (!$id) {
            $_SESSION['error'] = 'Invalid accommodation ID';
            header('Location: ' . ROOT . '/admin/accommodations');
            exit;
        }

        // Get accommodation with provider details
        $query = "SELECT 
                    a.*,
                    u.id as provider_user_id,
                    CONCAT(u.first_name, ' ', u.last_name) as provider_name,
                    u.email as provider_email,
                    u.phone as provider_phone,
                    u.created_at as provider_member_since,
                    (SELECT COUNT(*) FROM accommodations WHERE user_id = u.id AND deleted_at IS NULL) as provider_total_listings
                  FROM accommodations a
                  LEFT JOIN users u ON a.user_id = u.id
                  WHERE a.id = :id AND a.deleted_at IS NULL";

        $accommodation = $this->getRow($query, ['id' => $id]);

        if (!$accommodation) {
            $_SESSION['error'] = 'Accommodation not found';
            header('Location: ' . ROOT . '/admin/accommodations');
            exit;
        }

        // Get all images
        $imagesQuery = "SELECT * FROM accommodation_images WHERE accommodation_id = :id ORDER BY is_main DESC";
        $images = $this->query($imagesQuery, ['id' => $id]) ?: [];

        // Increment view count
        $this->incrementViewCount($id);

        // Create provider object for view
        $provider = (object)[
            'id' => $accommodation->provider_user_id ?? null,
            'name' => $accommodation->provider_name ?? 'Unknown',
            'email' => $accommodation->provider_email ?? 'N/A',
            'phone' => $accommodation->provider_phone ?? null,
            'role' => 'Accommodation Provider'
        ];

        $data = [
            'accommodation' => $accommodation,
            'images' => $images,
            'provider' => $provider,
            'pageTitle' => $accommodation->title . ' - Details'
        ];

        require_once __DIR__ . '/../views/admin/accommodations/view.view.php';
    }

    /**
     * Delete accommodation (soft delete)
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
            echo json_encode(['success' => false, 'message' => 'Invalid accommodation ID']);
            exit;
        }

        // Check if accommodation exists
        $checkQuery = "SELECT id, title FROM accommodations WHERE id = :id AND deleted_at IS NULL";
        $accommodation = $this->getRow($checkQuery, ['id' => $id]);

        if (!$accommodation) {
            echo json_encode(['success' => false, 'message' => 'Accommodation not found']);
            exit;
        }

        // Soft delete
        $query = "UPDATE accommodations 
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
                'message' => 'Accommodation deleted successfully'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete accommodation']);
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
     * Get accommodation statistics
     */
    private function getStatistics() {
        $query = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN property_type = 'hotel' THEN 1 ELSE 0 END) as hotels,
                    SUM(CASE WHEN property_type = 'resort' THEN 1 ELSE 0 END) as resorts,
                    SUM(CASE WHEN property_type = 'villa' THEN 1 ELSE 0 END) as villas,
                    SUM(CASE WHEN property_type = 'apartment' THEN 1 ELSE 0 END) as apartments,
                    SUM(CASE WHEN property_type = 'guesthouse' THEN 1 ELSE 0 END) as guesthouses,
                    SUM(CASE WHEN property_type = 'hostel' THEN 1 ELSE 0 END) as hostels,
                    SUM(CASE WHEN property_type = 'homestay' THEN 1 ELSE 0 END) as homestays,
                    SUM(CASE WHEN property_type = 'boutique' THEN 1 ELSE 0 END) as boutiques,
                    COALESCE(SUM(views_count), 0) as total_views
                  FROM accommodations
                  WHERE deleted_at IS NULL";

        $result = $this->getRow($query);
        
        return [
            'total' => $result->total ?? 0,
            'hotels' => $result->hotels ?? 0,
            'resorts' => $result->resorts ?? 0,
            'villas' => $result->villas ?? 0,
            'apartments' => $result->apartments ?? 0,
            'guesthouses' => $result->guesthouses ?? 0,
            'hostels' => $result->hostels ?? 0,
            'homestays' => $result->homestays ?? 0,
            'boutiques' => $result->boutiques ?? 0,
            'total_views' => $result->total_views ?? 0
        ];
    }

    /**
     * Get unique cities for filter dropdown
     */
    private function getUniqueCities() {
        $query = "SELECT DISTINCT city FROM accommodations 
                  WHERE deleted_at IS NULL AND city IS NOT NULL AND city != ''
                  ORDER BY city";
        $result = $this->query($query);
        return $result ?: [];
    }

    /**
     * Increment view count
     */
    private function incrementViewCount($id) {
        $query = "UPDATE accommodations SET views_count = views_count + 1 WHERE id = :id";
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        $stmt->execute(['id' => $id]);
    }
}
