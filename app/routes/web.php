<?php

require_once __DIR__ . '/../Controllers/UserController.php';
require_once __DIR__ . '/../../config/database.php';

$controller = new UserController($pdo);

$users = $controller->index();

require_once __DIR__ . '/../Views/users/utilisateur.php';