<?php
header('Content-Type: application/json');
include '../connect.php'; // Ensure correct path to your database connection

// Initialize response array
$response = array();

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if medId is provided
    if (isset($_POST['medId'])) {
        $medId = intval($_POST['medId']); // Convert to integer for security

        // Prepare SQL delete query with placeholder
        $sql = "DELETE FROM p_medication WHERE id = ?";

        // Prepare and execute the statement
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $medId); // Bind the ID as an integer

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $response['success'] = true;
                    $response['message'] = 'Medication deleted successfully.';

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
                    $response['data'] = $medications; // Include medications in the response
                } else {
                    $response['success'] = false;
                    $response['message'] = 'No records found to delete.';
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'Error deleting record: ' . $stmt->error;
            }

            $stmt->close();
        } else {
            $response['success'] = false;
            $response['message'] = 'Error preparing the statement: ' . $conn->error;
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'No medication ID provided.';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method.';
}

$conn->close();

// Return the response in JSON format
echo json_encode($response);
?>
