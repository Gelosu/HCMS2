<?php
include '../connect.php'; // Include your database connection

header('Content-Type: application/json'); // Set the response to JSON format

$response = array(); // Initialize response array

// Check if connection is valid
if ($conn->connect_error) {
    $response['error'] = "Connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit();
}

// Fetch the list of patients
$sql = "SELECT p_id, p_name FROM patient"; // Retrieve patient ID and name
$result = $conn->query($sql);

$patients = array(); // Array to store patient data

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row; // Store each patient row
    }
    $response['success'] = true;
    $response['data'] = $patients; // Add patient data to response
} else {
    $response['success'] = false;
    $response['message'] = 'No patients found';
}

// Close the connection
$conn->close();

// Output the response in JSON format
echo json_encode($response);
?>
