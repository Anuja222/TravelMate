<?php
require_once '../app/controllers/AuthController.php';

use App\Controllers\AuthController;

$authController = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['action']) && $_GET['action'] === 'register') {
        $authController->registerUser();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'login') {
        $authController->loginUser();
    }
}