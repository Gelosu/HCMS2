<?php
include '../connect.php'; // Ensure the connection script is included

header('Content-Type: application/json'); // Ensure response is in JSON format

// Initialize response array
$response = array();

// Check if the request is GET (assuming you're using GET to retrieve data)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Check if required GET variables are set
    if (isset($_GET['editAppointmentId'], $_GET['editPatientName'], $_GET['editPurpose'], $_GET['editAppointmentDateTime'], $_GET['editHealthWorker'])) {
        // Retrieve data from URL parameters
        $appointmentId = $_GET['editAppointmentId'];
        $patientName = $_GET['editPatientName'];
        $purpose = $_GET['editPurpose'];
        $appointmentDateTime = $_GET['editAppointmentDateTime']; // This is 'YYYY-MM-DDTHH:MM'
        $healthWorker = $_GET['editHealthWorker'];

        // Convert 'YYYY-MM-DDTHH:MM' to 'YYYY-MM-DD HH:MM:SS'
        $appointmentDateTime = str_replace('T', ' ', $appointmentDateTime) . ':00'; // Ensure no extra ':00'

        // Prepare update query with placeholders
        $sql = "UPDATE p_appointment SET 
                p_name = ?, 
                p_purpose = ?, 
                datetime = ?, 
                a_healthworker = ? 
                WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("ssssi", $patientName, $purpose, $appointmentDateTime, $healthWorker, $appointmentId);

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
                    $response['message'] = 'Appointment updated successfully.';
                    $response['appointments'] = $appointments; // Include updated appointments
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Error fetching updated appointments list: ' . $conn->error;
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'Error updating record: ' . $stmt->error;
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
