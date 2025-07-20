<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

spl_autoload_register(function ($class_name) {
   
    $paths = [
        __DIR__ . '/../app/controllers/',
        __DIR__ . '/../app/models/'
    ];

    foreach ($paths as $path) {
        $file = $path . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

require_once __DIR__ . '/../config/database.php';

$page = $_GET['page'] ?? 'login'; 


$authController = new AuthController();

switch ($page) {
    case 'login':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {

            $authController->showLoginForm();
        }
        break;

    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } else {
            $authController->showRegisterForm();
        }
        break;

    case 'dashboard':
        echo "<h1>Welcome to your Dashboard!</h1>";
        echo "<p><a href='index.php?page=logout'>Logout</a></p>";
        break;

    case 'logout':
        $authController->logout();
        break;

    default:
        header("Location: index.php?page=login");
        exit();
}
