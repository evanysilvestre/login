<?php
// your_app_root/public/index.php (Front Controller)

// --- 1. Error Reporting (for development) ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- 2. Autoloading (simple version) ---
// This function automatically includes class files when they are needed.
spl_autoload_register(function ($class_name) {
    // Define paths for controllers and models
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

// --- 3. Include Database Configuration ---
require_once __DIR__ . '/../config/database.php';

// --- 4. Basic Routing ---
// Determine which page/action the user is requesting
$page = $_GET['page'] ?? 'login'; // Default to login page

// Instantiate the AuthController
$authController = new AuthController();

switch ($page) {
    case 'login':
        // If it's a POST request, attempt to log in
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            // Otherwise, display the login form
            $authController->showLoginForm();
        }
        break;

    case 'register':
        // If it's a POST request, attempt to register
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } else {
            // Otherwise, display the registration form
            $authController->showRegisterForm();
        }
        break;

    case 'dashboard':
        // Example: A simple dashboard page after successful login
        // In a real app, you'd check if the user is authenticated here
        echo "<h1>Welcome to your Dashboard!</h1>";
        echo "<p><a href='index.php?page=logout'>Logout</a></p>";
        break;

    case 'logout':
        // Handle logout
        $authController->logout();
        break;

    default:
        // Handle unknown pages, redirect to login or a 404 page
        header("Location: index.php?page=login");
        exit();
}
