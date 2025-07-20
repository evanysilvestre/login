<?php

class User {
    private $conn;

    public function __construct() {
       
        $this->conn = getDbConnection();
    }

    /**
     * @param string $username The user's chosen username.
     * @param string $password The user's plain-text password.
     * @param string $email The user's email address.
     * @param string $fullname The user's full name.
     * @param string $birthdate The user's birth date (YYYY-MM-DD).
     * @return array An associative array with 'success' (boolean) and 'message' (string).
     */

    public function createUser($username, $password, $email, $fullname, $birthdate) {
        
        $stmt = $this->conn->prepare("SELECT id FROM newUsers WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            return ['success' => false, 'message' => 'Username or Email already exists. Please choose a different one.'];
        }
        $stmt->close();

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO newUsers (username, password, email, fullname, birthDate) VALUES (?, ?, ?, ?, ?)");
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
            if (password_verify($password, $user['password'])) {
                $stmt->close();
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
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>