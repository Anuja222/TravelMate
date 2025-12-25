<?php
require_once '../app/controllers/AuthController.php';

use App\Controllers\AuthController;

$authController = new AuthController();
$authController->logoutUser();