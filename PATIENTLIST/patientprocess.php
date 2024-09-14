<?php
include '../connect.php';
// Include database connection

header('Content-Type: application/json'); // Set content type to JSON

$response = array(); // Initialize response array

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $p_name = $_POST['p_name'];
    $p_age = $_POST['p_age'];
    $p_bday = $_POST['p_bday'];
    $p_address = $_POST['p_address'];
    $p_contper = $_POST['p_contper'];
    $p_type = $_POST['p_type'];

    // Prepare and execute insert query
    $sql = "INSERT INTO patient (p_name, p_age, p_bday, p_address, p_contper, p_type) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $p_name, $p_age, $p_bday, $p_address, $p_contper, $p_type);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Patient record added successfully';

        // Retrieve the latest patient records
        $result = $conn->query("SELECT * FROM patient ORDER BY p_id DESC");
        if ($result->num_rows > 0) {
            $patients = array();
            while ($row = $result->fetch_assoc()) {
                $patients[] = $row;
            }
            $response['patients'] = $patients;
        } else {
            $response['patients'] = array();
        }
    } else {
        if ($conn->errno == 1062) {
            $response['error'] = 'The data already exists!';
        } else {
            $response['error'] = 'An error occurred while processing your request. Please try again later.';
        }
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();

// Output the response in JSON format
echo json_encode($response);
?>
