<?php

class Users extends Controller {

    public function index($a = '', $b = '', $c = '') {
        // Access global database connection (already loaded in index.php)
        global $pdo;
        
        // If PDO doesn't exist, try to load it
        if (!isset($pdo)) {
            require_once __DIR__ . '/../../config/database.php';
        }
        
        // Fetch all users from database
        try {
            $stmt = $pdo->prepare("
                SELECT 
                    id,
                    first_name,
                    last_name,
                    email,
                    phone,
                    date_of_birth,
                    gender,
                    role,
                    profile_image,
                    created_at,
                    bio,
                    country,
                    city
                FROM users 
                ORDER BY created_at DESC
            ");
            
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            // Add default status to each user if not in database
            foreach ($users as $user) {
                $user->status = 'active'; // Default status
                // Map profile_image to profile_picture for view compatibility
                $user->profile_picture = $user->profile_image ?? null;
            }
        } catch (Exception $e) {
            error_log("Error fetching users: " . $e->getMessage());
            $users = [];
        } catch (PDOException $e) {
            error_log("PDO Error fetching users: " . $e->getMessage());
            $users = [];
        }
        
        // Pass data to view
        $data = [
            'users' => $users
        ];
        
        $this->view('admin/Users', $data);
    }
}
