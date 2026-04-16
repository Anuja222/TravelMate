<?php
namespace App\Controllers;

require_once __DIR__ . '/../../models/Activity.php';
require_once __DIR__ . '/../../../config/database.php';

use App\Models\Activity;

class ActivityController
{
    private $uploadDir;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->uploadDir = __DIR__ . '/../../../public/uploads/activities';
        if (!is_dir($this->uploadDir)) mkdir($this->uploadDir, 0755, true);
    }

    private function sendResponse($success, $errors = [], $data = null)
    {
        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'errors' => $errors, 'data' => $data]);
        exit;
    }

    private function saveFile($tmpPath, $originalName)
    {
        if (!file_exists($tmpPath)) return null;
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png'];
        if (!in_array($ext, $allowed)) return null;
        $fileName = uniqid('activity_', true) . '.' . $ext;
        $dest = $this->uploadDir . '/' . $fileName;
        if (move_uploaded_file($tmpPath, $dest)) {
            return '/uploads/activities/' . $fileName;
        }
        return null;
    }

    public function create()
    {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->sendResponse(false, ['error'=>'Invalid method']);

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        if (empty($title)) $this->sendResponse(false, ['error'=>'Title required']);

        if (empty($slug)) {
            $slug = strtolower(preg_replace('/[^a-z0-9]+/','-', $title));
        } else {
            $slug = strtolower(preg_replace('/[^a-z0-9]+/','-', $slug));
        }

        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->saveFile($_FILES['image']['tmp_name'], $_FILES['image']['name']);
        }

        $activity = new Activity([
            'title' => $title,
            'slug' => $slug,
            'description' => $description,
            'image' => $imagePath
        ]);

        try {
            $id = $activity->create($pdo);
            if ($id) {
                $this->sendResponse(true, [], ['id' => $id]);
            } else {
                $this->sendResponse(false, ['error'=>'Failed to save']);
            }
        } catch (\Exception $e) {
            $this->sendResponse(false, ['error' => $e->getMessage()]);
        }
    }

    public function list()
    {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') $this->sendResponse(false, ['error'=>'Invalid method']);
        $rows = Activity::findAll($pdo);
        $this->sendResponse(true, [], $rows);
    }

    public function get()
    {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') $this->sendResponse(false, ['error'=>'Invalid method']);
        $id = $_GET['id'] ?? null;
        if (!$id) $this->sendResponse(false, ['error'=>'Missing id']);
        $row = Activity::findById($pdo, $id);
        if (!$row) $this->sendResponse(false, ['error'=>'Not found']);
        $places = Activity::listPlaces($pdo, $id);
        $row['places'] = $places;
        $this->sendResponse(true, [], $row);
    }

    public function update()
    {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->sendResponse(false, ['error'=>'Invalid method']);
        $id = $_POST['id'] ?? null;
        if (!$id) $this->sendResponse(false, ['error'=>'Missing id']);
        $existing = Activity::findById($pdo, $id);
        if (!$existing) $this->sendResponse(false, ['error'=>'Not found']);

        $title = trim($_POST['title'] ?? $existing['title']);
        $description = trim($_POST['description'] ?? $existing['description']);
        $slug = trim($_POST['slug'] ?? $existing['slug']);
        if (empty($slug)) $slug = strtolower(preg_replace('/[^a-z0-9]+/','-', $title));

        $imagePath = $existing['image'] ?? null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $saved = $this->saveFile($_FILES['image']['tmp_name'], $_FILES['image']['name']);
            if ($saved) $imagePath = $saved;
        }

        $activity = new Activity([
            'id' => $id,
            'title' => $title,
            'slug' => $slug,
            'description' => $description,
            'image' => $imagePath
        ]);

        $ok = $activity->update($pdo);
        $this->sendResponse((bool)$ok, $ok ? [] : ['error'=>'Update failed'], $ok ? ['id' => $id] : null);
    }

    public function delete()
    {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->sendResponse(false, ['error'=>'Invalid method']);
        $id = $_POST['id'] ?? null;
        if (!$id) $this->sendResponse(false, ['error'=>'Missing id']);
        $ok = Activity::deleteById($pdo, $id);
        $this->sendResponse((bool)$ok, $ok ? [] : ['error'=>'Delete failed']);
    }

    // places (locations related to activity)
    public function placeCreate()
    {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->sendResponse(false, ['error'=>'Invalid method']);
        $activityId = $_POST['activity_id'] ?? null;
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        $location = trim($_POST['location'] ?? '');
        $best_time = trim($_POST['best_time'] ?? '');

        if (!$activityId || !$title) $this->sendResponse(false, ['error'=>'Missing data']);

        $slug = trim($_POST['slug'] ?? '');
        if (empty($slug)) $slug = strtolower(preg_replace('/[^a-z0-9]+/','-', $title));

        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->saveFile($_FILES['image']['tmp_name'], $_FILES['image']['name']);
        }

        $id = Activity::createPlace($pdo, $activityId, $title, $slug, $description, $imagePath, $location, $best_time);
        if ($id) $this->sendResponse(true, [], ['id' => $id]);
        $this->sendResponse(false, ['error'=>'Failed to create place']);
    }

    public function placeDelete()
    {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->sendResponse(false, ['error'=>'Invalid method']);
        $id = $_POST['id'] ?? null;
        if (!$id) $this->sendResponse(false, ['error'=>'Missing id']);
        $ok = Activity::deletePlaceById($pdo, $id);
        $this->sendResponse((bool)$ok, $ok ? [] : ['error'=>'Delete failed']);
    }

    public function placeUpdate()
    {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->sendResponse(false, ['error'=>'Invalid method']);

        $id = $_POST['id'] ?? null;
        if (!$id) $this->sendResponse(false, ['error'=>'Missing id']);

        $existing = Activity::findPlaceById($pdo, $id);
        if (!$existing) $this->sendResponse(false, ['error'=>'Place not found']);

        $title = trim($_POST['title'] ?? $existing['title']);
        if (empty($title)) $this->sendResponse(false, ['error'=>'Title required']);

        $slug = trim($_POST['slug'] ?? $existing['slug']);
        if (empty($slug)) $slug = strtolower(preg_replace('/[^a-z0-9]+/','-', $title));

        $description = trim($_POST['description'] ?? $existing['description']);
        
        $location = trim($_POST['location'] ?? ($existing['location'] ?? ''));
        $best_time = trim($_POST['best_time'] ?? ($existing['best_time'] ?? ''));

        $imagePath = $existing['image'] ?? null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $saved = $this->saveFile($_FILES['image']['tmp_name'], $_FILES['image']['name']);
            if ($saved) $imagePath = $saved;
        }

        $ok = Activity::updatePlace($pdo, $id, $title, $slug, $description, $imagePath, $location, $best_time);
        $this->sendResponse((bool)$ok, $ok ? [] : ['error'=>'Update failed'], $ok ? ['id' => $id] : null);
    }
}
