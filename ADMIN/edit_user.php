<?php
include '../connect.php';

header('Content-Type: application/json'); // Set content type to JSON

$response = array(); // Initialize response array

// Check if the database connection is valid
if ($conn->connect_error) {
    $response['error'] = "Connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit();
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $adid = isset($_POST['adid']) ? $conn->real_escape_string($_POST['adid']) : null;
    $adname = isset($_POST['adname']) ? $conn->real_escape_string($_POST['adname']) : null;
    $adsurname = isset($_POST['adsurname']) ? $conn->real_escape_string($_POST['adsurname']) : null;
    $adusername = isset($_POST['adusername']) ? $conn->real_escape_string($_POST['adusername']) : null;
    $adpass = isset($_POST['adpass']) ? $_POST['adpass'] : null;

    // Validate input
    if ($adid && $adname && $adsurname && $adusername) {
        // Prepare the update query based on whether a new password is provided
        if ($adpass) {
            // If a new password is provided, hash it
            $hashed_password = password_hash($adpass, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE admin SET adname=?, adsurname=?, adusername=?, adpass=? WHERE adid=?");
            $stmt->bind_param("ssssi", $adname, $adsurname, $adusername, $hashed_password, $adid);
        } else {
            // If no new password is provided, don't change the password
            $stmt = $conn->prepare("UPDATE admin SET adname=?, adsurname=?, adusername=? WHERE adid=?");
            $stmt->bind_param("sssi", $adname, $adsurname, $adusername, $adid);
        }

        // Execute the statement and check for success
        if ($stmt->execute()) {
            // Fetch the updated list of users including adpass and adposition
            $result = $conn->query("SELECT adid, adname, adsurname, adusername, adpass, adposition FROM admin");
            $users = array();
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            $response['success'] = true;
            $response['message'] = 'User updated successfully';
            $response['users'] = $users; // Add users data to response
        } else {
            $response['error'] = 'Error updating user: ' . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        $response['error'] = 'Invalid input data';
    }
} else {
    $response['error'] = 'Invalid request method';
}

// Close connection
$conn->close();

// Output the response in JSON format
echo json_encode($response);
?>
