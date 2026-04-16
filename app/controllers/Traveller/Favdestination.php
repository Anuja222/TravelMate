<?php

class Favdestination extends Controller{

    public function index($a = '', $b = '' , $c = ''){
        // get global database connection (already loaded in index.php)
        global $pdo;
        
        // fetch all destinations from database
        $stmt = $pdo->prepare("SELECT * FROM destinations ORDER BY created_at DESC");
        $stmt->execute();
        $destinations = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // pass destinations to view
        $data = [
            'destinations' => $destinations
        ];
        
        $this->view('Traveller/favdestination', $data);
    }
}