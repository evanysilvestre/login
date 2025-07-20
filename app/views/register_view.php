<?php
// your_app_root/app/views/register_view.php

// $message variable is passed from the controller
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Register New Account</h2>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form action="index.php?page=register" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <label for="fullname">Name:</label>
                <input type="text" id="fullname" name="fullname" placeholder="Full Name" required>
            </div>
            <div class="form-group">
                <label for="birthDate">Birth Date:</label>
                <input type="date" id="birthDate" name="birthDate" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <div class="form-group">
                <button type="submit">Register</button>
            </div>
        </form>
        <div class="link-text">
            Already have an account? <a href="index.php?page=login">Login here</a>
        </div>
    </div>
</body>
</html>