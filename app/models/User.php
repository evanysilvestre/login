<?php
// your_app_root/app/models/User.php

class User {
    private $conn;

    public function __construct() {
        // Get database connection from the global function
        $this->conn = getDbConnection();
    }

    /**
     * Creates a new user in the database.
     *
     * @param string $username The user's chosen username.
     * @param string $password The user's plain-text password.
     * @param string $email The user's email address.
     * @param string $fullname The user's full name.
     * @param string $birthdate The user's birth date (YYYY-MM-DD).
     * @return array An associative array with 'success' (boolean) and 'message' (string).
     */
    public function createUser($username, $password, $email, $fullname, $birthdate) {
        // Check if username or email already exists
        $stmt = $this->conn->prepare("SELECT id FROM newUsers WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            return ['success' => false, 'message' => 'Username or Email already exists. Please choose a different one.'];
        }
        $stmt->close();

        // Hash the password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the SQL query to insert the new user
        $stmt = $this->conn->prepare("INSERT INTO newUsers (username, password, email, fullname, birthDate) VALUES (?, ?, ?, ?, ?)");
        // 'sssss' because birthdate is also a string (YYYY-MM-DD)
        $stmt->bind_param("sssss", $username, $hashed_password, $email, $fullname, $birthdate);

        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true, 'message' => 'New account created successfully!'];
        } else {
            $stmt->close();
            return ['success' => false, 'message' => 'Error creating account: ' . $this->conn->error];
        }
    }



    /**
     * Authenticates a user.
     *
     * @param string $username The username provided by the user.
     * @param string $password The plain-text password provided by the user.
     * @return array An associative array with 'success' (boolean), 'message' (string), and 'user' (array|null).
     */
    public function authenticate($username, $password) {
        $stmt = $this->conn->prepare("SELECT id, username, password, email, fullname, birthDate FROM newUsers WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verify the hashed password
            if (password_verify($password, $user['password'])) {
                $stmt->close();
                // Remove password hash before returning user data
                unset($user['password']);
                return ['success' => true, 'message' => 'Login successful!', 'user' => $user];
            } else {
                $stmt->close();
                return ['success' => false, 'message' => 'Invalid username or password.'];
            }
        } else {
            $stmt->close();
            return ['success' => false, 'message' => 'Invalid username or password.'];
        }
    }

    public function __destruct() {
        // Close the database connection when the object is destroyed
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>