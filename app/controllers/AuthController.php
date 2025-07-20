<?php
// your_app_root/app/controllers/AuthController.php

class AuthController {
    private $userModel;

    public function __construct() {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new User(); // Instantiate the User Model
    }

    /**
     * Displays the login form.
     */
    public function showLoginForm() {
        $message = $_SESSION['message'] ?? ''; // Get message from session if redirected
        unset($_SESSION['message']); // Clear the message after displaying
        require_once __DIR__ . '/../views/login_view.php';
    }

    /**
     * Handles login form submission.
     */
    public function login() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $_SESSION['message'] = 'Please enter both username and password.';
            header("Location: index.php?page=login");
            exit();
        }

        $result = $this->userModel->authenticate($username, $password);

        if ($result['success']) {
            // Set session variables for successful login
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['username'] = $result['user']['username'];
            $_SESSION['fullname'] = $result['user']['fullname'];
            $_SESSION['message'] = $result['message']; // Optional: success message
            header("Location: index.php?page=dashboard"); // Redirect to a protected page
            exit();
        } else {
            $_SESSION['message'] = $result['message'];
            header("Location: index.php?page=login");
            exit();
        }
    }

    /**
     * Displays the registration form.
     */
    public function showRegisterForm() {
        $message = $_SESSION['message'] ?? ''; // Get message from session if redirected
        unset($_SESSION['message']); // Clear the message after displaying
        require_once __DIR__ . '/../views/register_view.php';
    }

    /**
     * Handles registration form submission.
     */
    public function register() {
        $new_username = $_POST['username'] ?? '';
        $new_fullname = $_POST['fullname'] ?? '';
        $email = $_POST['email'] ?? '';
        $birthdate = $_POST['birthDate'] ?? '';
        $new_password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Basic validation
        if (empty($new_username) || empty($new_password) || empty($confirm_password) || empty($email) || empty($birthdate) || empty($new_fullname)) {
            $_SESSION['message'] = "Please fill in all fields.";
            header("Location: index.php?page=register");
            exit();
        }

        // Password match validation
        if ($new_password !== $confirm_password) {
            $_SESSION['message'] = "Passwords do not match. Please try again.";
            header("Location: index.php?page=register");
            exit();
        }

        // Create user via the model
        $result = $this->userModel->createUser($new_username, $new_password, $email, $new_fullname, $birthdate);

        if ($result['success']) {
            $_SESSION['message'] = $result['message'] . " You can now login.";
            header("Location: index.php?page=login"); // Redirect to login after successful registration
            exit();
        } else {
            $_SESSION['message'] = $result['message'];
            header("Location: index.php?page=register");
            exit();
        }
    }

    /**
     * Handles user logout.
     */
    public function logout() {
        session_unset();   // Unset all session variables
        session_destroy(); // Destroy the session
        $_SESSION['message'] = "You have been logged out.";
        header("Location: index.php?page=login");
        exit();
    }
}
?>