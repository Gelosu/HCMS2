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

// Retrieve and sanitize the adid from the query string
$adid = isset($_GET['adid']) ? intval($_GET['adid']) : null;

if ($adid) {
    // Prepare the SQL query
    $sql = "SELECT adid, adname, adsurname, adusername, adpass, adposition FROM admin WHERE adid = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $adid);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if any row was returned
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $response['success'] = true;
            $response['user'] = $user;
        } else {
            $response['error'] = 'User not found.';
        }

        $stmt->close();
    } else {
        $response['error'] = 'Error preparing the SQL statement.';
    }
} else {
    $response['error'] = 'Invalid or missing ID.';
}

// Close the database connection
$conn->close();

// Output the response in JSON format
echo json_encode($response);
?>
