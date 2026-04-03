<?php
$_SERVER['SERVER_NAME'] = 'localhost';
require 'c:/xampp/htdocs/TravelMate/app/core/config.php';
$pdo = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
$post = $pdo->query("SELECT id FROM posts LIMIT 1")->fetchColumn();
$user = $pdo->query("SELECT id FROM users LIMIT 1")->fetchColumn();
echo "Testing with Post ID: $post, User ID: $user\n";

$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['post_id'] = $post;
$_POST['type'] = 'upvote';
session_start();
$_SESSION['user']['id'] = $user;
require 'c:/xampp/htdocs/TravelMate/app/core/Database.php';
require 'c:/xampp/htdocs/TravelMate/app/core/Model.php';
require 'c:/xampp/htdocs/TravelMate/app/core/Controller.php';
require 'c:/xampp/htdocs/TravelMate/app/models/Post.php';
require 'c:/xampp/htdocs/TravelMate/app/controllers/Shared/Blog.php';
(new Blog())->vote();
