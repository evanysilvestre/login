<?php
// your_app_root/config/database.php

// Database connection details
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Replace with your DB username
define('DB_PASSWORD', '');             // Replace with your DB password
define('DB_NAME', 'meusProjetos');         // Replace with your database name

// Function to get a new database connection
function getDbConnection() {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        // In a real application, you'd log this error and show a user-friendly message
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>