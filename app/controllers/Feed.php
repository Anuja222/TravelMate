<?php

class Feed extends Controller{

    public function index($a = '', $b = '' , $c = ''){
        // Load config and dependencies
        require_once __DIR__ . '/../core/config.php';
        require_once __DIR__ . '/../core/Model.php';
        require_once __DIR__ . '/../core/Database.php';
        require_once __DIR__ . '/../models/Post.php';
        
        // Get posts from database
        $post = new Post();
        $posts = $post->getAllWithUserInfo();
        
        $data = [
            'posts' => $posts
        ];
        
        $this->view('Traveller/feed', $data);
    }
}