<?php
include '../connect.php';// Include database connection

header('Content-Type: application/json'); // Set content type to JSON

$response = array(); // Initialize response array

// Check if $conn is valid before proceeding
if ($conn->connect_error) {
    $response['error'] = "Connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit();
}

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $patient_id = $_POST['patientId'];

    // Prepare and execute delete query
    $stmt = $conn->prepare("DELETE FROM patient WHERE p_id=?");
    $stmt->bind_param("i", $patient_id);

    if ($stmt->execute()) {
        // Fetch the latest patient data
        $result = $conn->query("SELECT * FROM patient");
        $patients = array();
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }
        $response['success'] = true;
        $response['message'] = 'Patient record deleted successfully';
        $response['patients'] = $patients; // Add patients data to response
    } else {
        $response['error'] = 'Error deleting record: ' . $conn->error;
    }

    // Close statement
    $stmt->close();
} else {
    $response['error'] = 'Invalid request method';
}

// Close connection
$conn->close();

// Output the response in JSON format
echo json_encode($response);
?>
