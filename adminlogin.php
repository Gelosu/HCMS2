<?php
require_once('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare a statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT ad_email, ad_pass FROM admin1 WHERE ad_email = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    // Bind the result variables
    $stmt->bind_result($db_username, $db_password);
    
    // Fetch the result
    $stmt->fetch();

    // Check if user exists and password is correct
    if ($stmt->num_rows > 0) {
        if (password_verify($password, $db_password)) {
            // Password is correct, redirect to admin.php
            header("Location: ADMIN/admin1.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this email.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="adminlog.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form action="admin1.php" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
</body>
</html>
