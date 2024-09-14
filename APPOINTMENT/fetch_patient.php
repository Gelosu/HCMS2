<?php
include '../connect.php'; // Include your database connection script

header('Content-Type: application/json'); // Ensure the response is JSON

$response = array(); // Initialize response array

// Check if the connection was successful
if ($conn->connect_error) {
    $response['error'] = "Connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit();
}

// Prepare and execute the select query using prepared statements
$stmt = $conn->prepare("SELECT p_name FROM patient"); // Adjust table and column names as needed
if (!$stmt) {
    $response['error'] = "Prepare failed: " . $conn->error;
    echo json_encode($response);
    exit();
}

$stmt->execute();
$result = $stmt->get_result();

$patients = [];
while ($row = $result->fetch_assoc()) {
    $patients[] = $row['p_name']; // Adjust column name as needed
}

$response['success'] = true;
$response['data'] = $patients;

// Close statement
$stmt->close();

// Close connection
$conn->close();

// Output the response in JSON format
echo json_encode($response);
?>
