<?php
namespace App\Controllers;

require_once __DIR__ . '/../../models/Preference.php';
use App\Models\Preference as PreferenceModel;

class PreferenceController {
    public function save() {
        global $pdo;
        
        // Start clean output buffering
        if (ob_get_level()) ob_end_clean();
        ob_start();
        
        // Clear any previous output
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ob_clean();
            echo json_encode([
                'success' => false,
                'errors' => ['error' => 'Invalid request method']
            ]);
            ob_end_flush();
            exit;
        }

        try {
            error_log('=== Preference Save Start ===');
            $userId = isset($_POST['userId']) ? $_POST['userId'] : null;
            $environments = isset($_POST['environments']) ? json_decode($_POST['environments']) : null;
            $activities = isset($_POST['activities']) ? json_decode($_POST['activities']) : null;
            
            error_log('User ID: ' . $userId);
            error_log('Environments: ' . print_r($environments, true));
            error_log('Activities: ' . print_r($activities, true));

            if (!$userId || !$environments || !$activities) {
                error_log('Missing required data');
                ob_clean();
                echo json_encode([
                    'success' => false,
                    'errors' => ['error' => 'Missing required data']
                ]);
                ob_end_flush();
                exit;
            }

            if (!isset($pdo) || !$pdo) {
                error_log('Database connection not available');
                ob_clean();
                echo json_encode([
                    'success' => false,
                    'errors' => ['error' => 'Database connection error']
                ]);
                ob_end_flush();
                exit;
            }
            
            error_log('Creating preference model');
            $preference = new PreferenceModel($userId, $environments, $activities);
            
            error_log('Attempting to save preferences');
            if ($preference->savePreferences($pdo)) {
                error_log('Preferences saved successfully');
                ob_clean();
                echo json_encode([
                    'success' => true,
                    'data' => ['redirectUrl' => 'login']
                ], JSON_UNESCAPED_SLASHES);
                ob_end_flush();
            } else {
                error_log('Failed to save preferences');
                ob_clean();
                echo json_encode([
                    'success' => false,
                    'errors' => ['error' => 'Failed to save preferences']
                ], JSON_UNESCAPED_SLASHES);
                ob_end_flush();
            }
        } catch (\Exception $e) {
            error_log('Exception in preference save: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            ob_clean();
            echo json_encode([
                'success' => false,
                'errors' => ['error' => 'Error occurred while saving preferences. Please try again.']
            ], JSON_UNESCAPED_SLASHES);
            ob_end_flush();
        }
        exit;
    }
}