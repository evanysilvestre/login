<?php

class AuthController {
    private $userModel;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new User(); 
    }

    public function showLoginForm() {
        $message = $_SESSION['message'] ?? ''; 
        unset($_SESSION['message']); 
        require_once __DIR__ . '/../views/login_view.php';
    }


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
           
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['username'] = $result['user']['username'];
            $_SESSION['fullname'] = $result['user']['fullname'];
            $_SESSION['message'] = $result['message'];
            header("Location: index.php?page=dashboard"); 
            exit();
        } else {
            $_SESSION['message'] = $result['message'];
            header("Location: index.php?page=login");
            exit();
        }
    }

    public function showRegisterForm() {
        $message = $_SESSION['message'] ?? ''; 
        unset($_SESSION['message']);
        require_once __DIR__ . '/../views/register_view.php';
    }

    public function register() {
        $new_username = $_POST['username'] ?? '';
        $new_fullname = $_POST['fullname'] ?? '';
        $email = $_POST['email'] ?? '';
        $birthdate = $_POST['birthDate'] ?? '';
        $new_password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($new_username) || empty($new_password) || empty($confirm_password) || empty($email) || empty($birthdate) || empty($new_fullname)) {
            $_SESSION['message'] = "Please fill in all fields.";
            header("Location: index.php?page=register");
            exit();
        }

        if ($new_password !== $confirm_password) {
            $_SESSION['message'] = "Passwords do not match. Please try again.";
            header("Location: index.php?page=register");
            exit();
        }

        $result = $this->userModel->createUser($new_username, $new_password, $email, $new_fullname, $birthdate);

        if ($result['success']) {
            $_SESSION['message'] = $result['message'] . " You can now login.";
            header("Location: index.php?page=login"); 
            exit();
        } else {
            $_SESSION['message'] = $result['message'];
            header("Location: index.php?page=register");
            exit();
        }
    }
 
    public function logout() {
        session_unset();  
        session_destroy(); 
        $_SESSION['message'] = "You have been logged out.";
        header("Location: index.php?page=login");
        exit();
    }
}
?>