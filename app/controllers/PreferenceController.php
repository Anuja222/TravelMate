<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/Preference.php';
use App\Models\Preference as PreferenceModel;

class PreferenceController {
    public function save() {
        global $pdo;
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'errors' => ['error' => 'Invalid request method']
            ]);
            exit;
        }

        $userId = $_POST['userId'];
        $environments = json_decode($_POST['environments']);
        $activities = json_decode($_POST['activities']);

        if (!$userId || !$environments || !$activities) {
            echo json_encode([
                'success' => false,
                'errors' => ['error' => 'Missing required data']
            ]);
            exit;
        }

        $preference = new PreferenceModel($userId, $environments, $activities);
        
        if ($preference->savePreferences($pdo)) {
            echo json_encode([
                'success' => true,
                'data' => ['redirectUrl' => 'login']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'errors' => ['error' => 'Failed to save preferences']
            ]);
        }
        exit;
    }
}