<?php
include '../connect.php';  // Database connection

header('Content-Type: application/json');  // Set response to JSON format

$response = array();  // Initialize the response array

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Print POST data for debugging
    error_log(print_r($_POST, true));

    // Get the POST data
    $medicationId = $_POST['editMedicationId'] ?? '';  // The medication ID
    $medicationPatientName = $_POST['editMedicationPatientName'] ?? '';  // The patient's ID
    $medicines = $_POST['editMedicines'] ?? [];  // Array of medicine names (not IDs)
    $amounts = $_POST['editAmount'] ?? [];  // Array of corresponding amounts
    $medicationDateTime = $_POST['editMedicationDateTime'] ?? '';
    $medicationHealthWorker = $_POST['editMedicationHealthWorker'] ?? '';

    // Validate the medication ID
    if (empty($medicationId)) {
        $response['success'] = false;
        $response['error'] = 'Medication ID is missing.';
        echo json_encode($response);
        exit();
    }

    // Initialize an array to store the medicines and their amounts
    $medicationDetails = [];

    // Combine medicines and their corresponding amounts
    foreach ($medicines as $index => $medicine) {
        $amount = isset($amounts[$index]) ? $amounts[$index] : 0;
        $medicationDetails[] = [
            'name' => $medicine,  // Use the medicine name directly from POST data
            'amount' => $amount
        ];  // Store as an associative array with 'name' and 'amount'
    }

    // Convert the medication details array to a JSON string for storage
    $medicationJson = json_encode($medicationDetails);

    // Prepare the SQL query to update the existing medication record
    $sql = "UPDATE p_medication 
            SET p_medpatient = ?, p_medication = ?, datetime = ?, a_healthworker = ? 
            WHERE id = ?";

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssss", $medicationPatientName, $medicationJson, $medicationDateTime, $medicationHealthWorker, $medicationId);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Patient medication updated successfully.';

            // Fetch updated medication data to return
            $fetchSql = "
                SELECT 
                    id, 
                    p_medpatient AS patient_name, 
                    p_medication, 
                    datetime AS date_time, 
                    a_healthworker AS healthworker
                FROM p_medication
            ";
            $result = $conn->query($fetchSql);
            $medications = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Decode JSON data for p_medication
                    $row['p_medication'] = json_decode($row['p_medication'], true);
                    $medications[] = $row;
                }
            }
            $response['data'] = $medications;  // Include medications in the response
        } else {
            $response['success'] = false;
            $response['error'] = 'Error inserting medication: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $response['success'] = false;
        $response['error'] = 'Error preparing the statement: ' . $conn->error;
    }
} else {
    $response['success'] = false;
    $response['error'] = 'Invalid request method.';
}

$conn->close();

// Return the response in JSON format
echo json_encode($response);
?>
