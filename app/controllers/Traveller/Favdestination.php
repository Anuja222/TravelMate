<?php

class Favdestination extends Controller{

    public function index($a = '', $b = '' , $c = ''){
        // Get global database connection (already loaded in index.php)
        global $pdo;
        
        // Fetch all destinations from database
        $stmt = $pdo->prepare("SELECT * FROM destinations ORDER BY created_at DESC");
        $stmt->execute();
        $destinations = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Pass destinations to view
        $data = [
            'destinations' => $destinations
        ];
        
        $this->view('Traveller/favdestination', $data);
    }
}