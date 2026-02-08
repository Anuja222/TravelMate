<?php

class Users extends Controller {

    public function index($a = '', $b = '', $c = '') {
        // Access global database connection (already loaded in index.php)
        global $pdo;
        
        error_log("Users Controller: Starting index method");
        error_log("PDO object: " . (isset($pdo) ? 'exists' : 'NOT SET'));
        
        // Fetch all users from database
        try {
            $stmt = $pdo->prepare("
                SELECT 
                    id,
                    first_name,
                    last_name,
                    email,
                    phone,
                    role,
                    profile_image,
                    created_at
                FROM users 
                ORDER BY created_at DESC
            ");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            error_log("Users Controller: Fetched " . count($users) . " users");
            
            // Add default status to each user if not in database
            foreach ($users as $user) {
                $user->status = 'active'; // Default status
                // Map profile_image to profile_picture for view compatibility
                $user->profile_picture = $user->profile_image ?? null;
            }
        } catch (Exception $e) {
            error_log("Error fetching users: " . $e->getMessage());
            $users = [];
        }
        
        // Pass data to view
        $data = [
            'users' => $users
        ];
        
        error_log("Users Controller: Passing " . count($data['users']) . " users to view");
        
        $this->view('admin/Users', $data);
    }
}
