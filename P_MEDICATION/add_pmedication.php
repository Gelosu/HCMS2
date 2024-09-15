<?php
include '../connect.php';  // Database connection

header('Content-Type: application/json');  // Set response to JSON format

$response = array();  // Initialize the response array

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the POST data
    $medicationPatientName = $_POST['medicationPatientName'] ?? '';  // The patient's name
    $medicines = isset($_POST['medicines']) ? $_POST['medicines'] : [];  // Array of medicine IDs
    $amounts = isset($_POST['amount']) ? $_POST['amount'] : [];  // Array of corresponding amounts
    $medicationDateTime = $_POST['medicationDateTime'] ?? '';
    $medicationHealthWorker = $_POST['medicationHealthWorker'] ?? '';

    // Initialize an array to store the medicines and their amounts
    $medicationDetails = [];

    // Fetch medication names from invmeds table
    $medicationNames = [];
    foreach ($medicines as $med_id) {
        $medSql = "SELECT meds_name FROM inv_meds WHERE med_id = ?";
        if ($medStmt = $conn->prepare($medSql)) {
            $medStmt->bind_param("s", $med_id);
            $medStmt->execute();
            $medStmt->bind_result($meds_name);
            $medStmt->fetch();
            $medStmt->close();
            $medicationNames[$med_id] = $meds_name;
        } else {
            $response['success'] = false;
            $response['error'] = 'Error preparing medication name query: ' . $conn->error;
            echo json_encode($response);
            exit();
        }
    }

    // Combine medicines and their corresponding amounts with names
    foreach ($medicines as $index => $medicine) {
        $amount = isset($amounts[$index]) ? $amounts[$index] : 0;
        $medicationDetails[] = [
            'name' => $medicationNames[$medicine] ?? 'Unknown Medicine',
            'amount' => $amount
        ];  // Store as an associative array with 'name' and 'amount'
    }

    // Convert the medication details array to a JSON string for storage
    $medicationJson = json_encode($medicationDetails);

    // Fetch the patient ID from the patient name
    $patientId = null;
    $patientSql = "SELECT p_name FROM patient WHERE p_id = ?";
    if ($patientStmt = $conn->prepare($patientSql)) {
        $patientStmt->bind_param("s", $medicationPatientName);
        $patientStmt->execute();
        $patientStmt->bind_result($p_id);
        if ($patientStmt->fetch()) {
            $patientId = $p_id;
        }
        $patientStmt->close();
    } else {
        $response['success'] = false;
        $response['error'] = 'Error preparing patient ID query: ' . $conn->error;
        echo json_encode($response);
        exit();
    }

    if ($patientId === null) {
        $response['success'] = false;
        $response['error'] = 'Patient not found.';
        echo json_encode($response);
        exit();
    }

    // Prepare the SQL query to insert a new medication record
    $sql = "INSERT INTO p_medication (p_medpatient, p_medication, datetime, a_healthworker) 
            VALUES (?, ?, ?, ?)";

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $patientId, $medicationJson, $medicationDateTime, $medicationHealthWorker);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Patient medication added successfully.';

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
