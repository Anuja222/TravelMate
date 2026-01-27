<?php

namespace App\Models;

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/Database.php';

class Destination {
    use \Database;
    
    protected $table = 'destinations';
    
    // Instance properties for object-based operations
    public $id;
    public $title;
    public $slug;
    public $description;
    public $image;
    public $category;
    public $status;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    // =============================================
    // STATIC METHODS FOR PUBLIC CONTROLLER
    // =============================================

    /**
     * Find all destinations (for public listing)
     */
    public static function findAll($pdo)
    {
        $stmt = $pdo->query("SELECT * FROM destinations WHERE deleted_at IS NULL ORDER BY created_at DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Find destination by ID
     */
    public static function findById($pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM destinations WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Delete destination by ID
     */
    public static function deleteById($pdo, $id)
    {
        $stmt = $pdo->prepare("UPDATE destinations SET deleted_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * List places for a destination
     */
    public static function listPlaces($pdo, $destinationId)
    {
        $stmt = $pdo->prepare("SELECT * FROM destination_places WHERE destination_id = ? ORDER BY created_at DESC");
        $stmt->execute([$destinationId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Create a place
     */
    public static function createPlace($pdo, $destinationId, $title, $slug, $description, $image)
    {
        $stmt = $pdo->prepare("INSERT INTO destination_places (destination_id, name, title, slug, description, image, status, views, created_at) VALUES (?, ?, ?, ?, ?, ?, 'active', 0, NOW())");
        if ($stmt->execute([$destinationId, $title, $title, $slug, $description, $image])) {
            return $pdo->lastInsertId();
        }
        return false;
    }

    /**
     * Delete place by ID
     */
    public static function deletePlaceById($pdo, $id)
    {
        $stmt = $pdo->prepare("DELETE FROM destination_places WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Find place by ID
     */
    public static function findPlaceById($pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM destination_places WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Update place
     */
    public static function updatePlace($pdo, $id, $title, $slug, $description, $image)
    {
        $stmt = $pdo->prepare("UPDATE destination_places SET name = ?, title = ?, slug = ?, description = ?, image = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$title, $title, $slug, $description, $image, $id]);
    }

    /**
     * Instance create method
     */
    public function create($pdo)
    {
        $stmt = $pdo->prepare("INSERT INTO destinations (name, title, slug, description, image, status, created_at) VALUES (?, ?, ?, ?, ?, 'active', NOW())");
        if ($stmt->execute([$this->title, $this->title, $this->slug, $this->description, $this->image])) {
            return $pdo->lastInsertId();
        }
        return false;
    }

    /**
     * Instance update method
     */
    public function update($pdo)
    {
        $stmt = $pdo->prepare("UPDATE destinations SET name = ?, title = ?, slug = ?, description = ?, image = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$this->title, $this->title, $this->slug, $this->description, $this->image, $this->id]);
    }

    // =============================================
    // INSTANCE METHODS FOR ADMIN CONTROLLER
    // =============================================

    /**
     * Get all destination categories with place counts
     */
    public function getAllCategories() {
        $query = "SELECT 
                    d.id,
                    d.name,
                    d.title,
                    d.description,
                    d.image,
                    d.category,
                    d.status,
                    d.created_at,
                    COUNT(dp.id) as place_count,
                    SUM(CASE WHEN dp.status = 'active' THEN 1 ELSE 0 END) as active_count,
                    COALESCE(SUM(dp.views), 0) as total_views
                  FROM {$this->table} d
                  LEFT JOIN destination_places dp ON d.id = dp.destination_id
                  WHERE d.deleted_at IS NULL
                  GROUP BY d.id
                  ORDER BY d.created_at DESC";
        
        $result = $this->query($query);
        return $result ?: [];
    }

    /**
     * Get category by ID
     */
    public function getCategoryById($id) {
        $query = "SELECT d.*, 
                  CONCAT(u.first_name, ' ', u.last_name) as creator_name
                  FROM {$this->table} d
                  LEFT JOIN users u ON d.created_by = u.id
                  WHERE d.id = :id AND d.deleted_at IS NULL";
        return $this->getRow($query, ['id' => $id]);
    }

    /**
     * Get category statistics
     */
    public function getCategoryStats($categoryId) {
        $query = "SELECT 
                    COUNT(*) as total_places,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_places,
                    COALESCE(SUM(views), 0) as total_views
                  FROM destination_places
                  WHERE destination_id = :category_id";
        return $this->getRow($query, ['category_id' => $categoryId]);
    }

    /**
     * Create new destination category
     */
    public function createCategory($data) {
        $slug = $this->generateSlug($data['name']);
        $category = strtolower(str_replace(' ', '_', $data['name']));
        
        $query = "INSERT INTO {$this->table} 
                  (name, title, slug, description, category, image, status, created_by, created_at) 
                  VALUES 
                  (:name, :title, :slug, :description, :category, :image, 'active', :created_by, NOW())";
        
        $params = [
            'name' => $data['name'],
            'title' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? '',
            'category' => $category,
            'image' => $data['image'] ?? null,
            'created_by' => $data['created_by'] ?? 1
        ];

        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        $result = $stmt->execute($params);
        
        return $result ? $conn->lastInsertId() : false;
    }

    /**
     * Update category
     */
    public function updateCategory($id, $data) {
        $hasNewImage = isset($data['image']) && $data['image'] !== null;
        
        if ($hasNewImage) {
            $query = "UPDATE {$this->table} 
                      SET name = :name,
                          title = :name,
                          description = :description,
                          image = :image,
                          updated_at = NOW()
                      WHERE id = :id AND deleted_at IS NULL";
            
            $params = [
                'id' => $id,
                'name' => $data['name'],
                'description' => $data['description'] ?? '',
                'image' => $data['image']
            ];
        } else {
            $query = "UPDATE {$this->table} 
                      SET name = :name,
                          title = :name,
                          description = :description,
                          updated_at = NOW()
                      WHERE id = :id AND deleted_at IS NULL";
            
            $params = [
                'id' => $id,
                'name' => $data['name'],
                'description' => $data['description'] ?? ''
            ];
        }
        
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Soft delete category
     */
    public function deleteCategory($id, $deletedBy = null) {
        $query = "UPDATE {$this->table} 
                  SET deleted_at = NOW(),
                      updated_by = :deleted_by
                  WHERE id = :id AND deleted_at IS NULL";
        
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute(['id' => $id, 'deleted_by' => $deletedBy]);
    }

    /**
     * Get all places for a destination category
     */
    public function getPlacesByCategory($categoryId) {
        $query = "SELECT * FROM destination_places 
                  WHERE destination_id = :destination_id 
                  ORDER BY created_at DESC";
        $result = $this->query($query, ['destination_id' => $categoryId]);
        return $result ?: [];
    }

    /**
     * Get place by ID (instance method)
     */
    public function getPlaceById($placeId) {
        $query = "SELECT dp.*, d.name as category_name
                  FROM destination_places dp
                  LEFT JOIN destinations d ON dp.destination_id = d.id
                  WHERE dp.id = :id";
        return $this->getRow($query, ['id' => $placeId]);
    }

    /**
     * Add new place to category
     */
    public function addPlace($data) {
        $slug = $this->generateSlug($data['name']);
        
        $query = "INSERT INTO destination_places 
                  (destination_id, name, title, slug, description, image, status, views, created_at) 
                  VALUES 
                  (:destination_id, :name, :title, :slug, :description, :image, 'active', 0, NOW())";
        
        $params = [
            'destination_id' => $data['destination_id'],
            'name' => $data['name'],
            'title' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? '',
            'image' => $data['image'] ?? null
        ];

        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        $result = $stmt->execute($params);
        
        return $result ? $conn->lastInsertId() : false;
    }

    /**
     * Update place (instance method)
     */
    public function updatePlaceById($placeId, $data) {
        $hasNewImage = isset($data['image']) && $data['image'] !== null;
        
        if ($hasNewImage) {
            $query = "UPDATE destination_places 
                      SET name = :name,
                          title = :name,
                          description = :description,
                          image = :image,
                          updated_at = NOW()
                      WHERE id = :id";
            
            $params = [
                'id' => $placeId,
                'name' => $data['name'],
                'description' => $data['description'] ?? '',
                'image' => $data['image']
            ];
        } else {
            $query = "UPDATE destination_places 
                      SET name = :name,
                          title = :name,
                          description = :description,
                          updated_at = NOW()
                      WHERE id = :id";
            
            $params = [
                'id' => $placeId,
                'name' => $data['name'],
                'description' => $data['description'] ?? ''
            ];
        }

        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Delete place (instance method)
     */
    public function deletePlaceFromCategory($placeId) {
        $query = "DELETE FROM destination_places WHERE id = :id";
        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute(['id' => $placeId]);
    }

    /**
     * Check if category has places
     */
    public function categoryHasPlaces($categoryId) {
        $query = "SELECT COUNT(*) as count FROM destination_places WHERE destination_id = :id";
        $result = $this->getRow($query, ['id' => $categoryId]);
        return $result && $result->count > 0;
    }

    /**
     * Generate URL slug
     */
    private function generateSlug($text) {
        $slug = strtolower(trim($text));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }

    /**
     * Get categories for dropdown
     */
    public function getCategoriesForDropdown() {
        $query = "SELECT id, name, title FROM {$this->table} 
                  WHERE deleted_at IS NULL 
                  ORDER BY name ASC";
        $result = $this->query($query);
        return $result ?: [];
    }
}

// Also create a non-namespaced alias for AdminDestinationController
class_alias('App\Models\Destination', 'Destination');
