<?php
// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to database
require_once('connect.php');  // Ensure this file sets up the $conn variable for database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and get form inputs
    $username = trim($_POST['username']);  // Trim leading/trailing spaces
    $password = $_POST['password'];

    // Prepare a statement to select hashed password and user details from the database
    $stmt_admin = $conn->prepare("SELECT adusername, adpass, adname, adsurname FROM admin WHERE adusername = ?");
    $stmt_admin->bind_param("s", $username);
    $stmt_admin->execute();
    $stmt_admin->store_result(); // Store the result set

    // Check if user exists
    if ($stmt_admin->num_rows > 0) {
        // Bind the result columns (username, hashed password, first name, surname)
        $stmt_admin->bind_result($adusername, $adpass, $adfirstname, $adsurname);
        $stmt_admin->fetch(); // Fetch the result

        // Verify password using password_verify
        if (password_verify($password, $adpass)) {
            // Password is correct, start session and store user info
            session_start();
            $_SESSION['adusername'] = $adusername;
            $_SESSION['adfirstname'] = $adfirstname;
            $_SESSION['adsurname'] = $adsurname;

            // Redirect to admin.php
            header("Location: admin.php");
            exit();
        } else {
            // Password is incorrect
            echo "Invalid password.";
        }
    } else {
        // User does not exist
        echo "User not found.";
    }

    // Close statements and connection
    $stmt_admin->close();
    $conn->close();
}
?>
