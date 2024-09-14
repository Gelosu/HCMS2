<?php
header('Content-Type: application/json');
include '../connect.php'; // Include your database connection script

$response = array();

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if required POST variable is set
    if (isset($_POST['appointmentId'])) {
        // Retrieve appointment ID from POST data
        $appointmentId = $_POST['appointmentId'];

        // Prepare SQL delete query with placeholders
        $sql = "DELETE FROM p_appointment WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("i", $appointmentId);

            // Execute statement
            if ($stmt->execute()) {
                // Fetch updated data
                $fetchSql = "SELECT * FROM p_appointment";
                $result = $conn->query($fetchSql);

                $appointments = [];
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $appointments[] = $row;
                    }

                    // Set success response
                    $response['success'] = true;
                    $response['message'] = 'Appointment deleted successfully.';
                    $response['appointments'] = $appointments; // Include updated appointments
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Error fetching updated appointments list: ' . $conn->error;
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'Error deleting record: ' . $stmt->error;
            }

            $stmt->close();
        } else {
            $response['success'] = false;
            $response['message'] = 'Error preparing statement: ' . $conn->error;
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Missing required fields.';
    }

    $conn->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method.';
}

// Output JSON response
echo json_encode($response);
?>
