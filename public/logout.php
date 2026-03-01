<?php
require_once '../app/controllers/Traveller/AuthController.php';

use App\Controllers\AuthController;

$authController = new AuthController();
$authController->logoutUser();