<?php
include '../connect.php';
// Include database connection

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
    $patient_id = $_POST['patientId']; // Updated to match form field name
    $name = $_POST['name'];
    $age = $_POST['age'];
    $bday = $_POST['birthday']; // Updated to match form field name
    $address = $_POST['address'];
    $contact_person = $_POST['contactPerson']; // Updated to match form field name
    $type = $_POST['type'];

    // Prepare and execute update query
    $stmt = $conn->prepare("UPDATE patient SET p_name=?, p_age=?, p_bday=?, p_address=?, p_contper=?, p_type=? WHERE p_id=?");
    $stmt->bind_param("ssssssi", $name, $age, $bday, $address, $contact_person, $type, $patient_id);

    if ($stmt->execute()) {
        // Fetch the latest patient data
        $result = $conn->query("SELECT * FROM patient");
        $patients = array();
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }
        $response['success'] = true;
        $response['message'] = 'Patient record updated successfully';
        $response['patients'] = $patients; // Add patients data to response
    } else {
        $response['error'] = 'Error updating record: ' . $conn->error;
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
