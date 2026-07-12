<?php
session_start();

require_once '../config/database.php';
require_once '../core/Router.php';
require_once __DIR__ . '/../config/mail.php';
require_once __DIR__ . '/../app/Controllers/UserController.php';
$page = $_GET['page'] ?? 'login';
Router::route($page, $pdo);