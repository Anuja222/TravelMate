<?php

class Dashboard extends Controller{

    public function index($a = '', $b = '' , $c = ''){
        // Check if user is logged in
        if (!isset($_SESSION['user']['id'])) {
            header('Location: login');
            exit;
        }
        
        // Load dependencies
        require_once __DIR__ . '/../../core/config.php';
        require_once __DIR__ . '/../../core/Model.php';
        require_once __DIR__ . '/../../core/Database.php';
        require_once __DIR__ . '/../../models/Post.php';
        
        // Get user's posts
        $postModel = new Post();
        $userPosts = $postModel->getUserPosts($_SESSION['user']['id']);
        
        // Pass posts to view
        $data = [
            'posts' => $userPosts ? $userPosts : []
        ];
        
        $this->view('Traveller/dashboard', $data);
    }
}