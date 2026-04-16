<?php
namespace App\Models;

class Activity
{
    public $id;
    public $title;
    public $slug;
    public $description;
    public $image;

    public function __construct($data = [])
    {
        foreach ($data as $k => $v) {
            $prop = lcfirst($k);
            if (property_exists($this, $prop)) $this->$prop = $v;
        }
    }

    public function create($conn)
    {
        $sql = "INSERT INTO activities (title, slug, description, image, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $res = $stmt->execute([
            $this->title,
            $this->slug,
            $this->description,
            $this->image
        ]);
        return $res ? $conn->lastInsertId() : false;
    }

    public function update($conn)
    {
        $sql = "UPDATE activities SET title = ?, slug = ?, description = ?, image = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $this->title,
            $this->slug,
            $this->description,
            $this->image,
            $this->id
        ]);
    }

    public static function deleteById($conn, $id)
    {
        $sql = "DELETE FROM activities WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public static function findAll($conn)
    {
        $sql = "SELECT * FROM activities ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function findById($conn, $id)
    {
        $sql = "SELECT * FROM activities WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Places (locations where activity is available)
    public static function createPlace($conn, $activityId, $title, $slug, $description, $image, $location = null, $best_time = null)
    {
        $sql = "INSERT INTO activity_places (activity_id, title, slug, description, image, location, best_time, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $res = $stmt->execute([$activityId, $title, $slug, $description, $image, $location, $best_time]);
        return $res ? $conn->lastInsertId() : false;
    }

    public static function listPlaces($conn, $activityId)
    {
        $sql = "SELECT * FROM activity_places WHERE activity_id = ? ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$activityId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function deletePlaceById($conn, $id)
    {
        $sql = "DELETE FROM activity_places WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public static function findPlaceById($conn, $id)
    {
        $sql = "SELECT * FROM activity_places WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function updatePlace($conn, $id, $title, $slug, $description, $image, $location = null, $best_time = null)
    {
        $sql = "UPDATE activity_places SET title = ?, slug = ?, description = ?, image = ?, location = ?, best_time = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $title,
            $slug,
            $description,
            $image,
            $location,
            $best_time,
            $id
        ]);
    }
}
