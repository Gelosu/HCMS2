<?php
include '../connect.php'; // Include your database connection script

header('Content-Type: application/json'); // Ensure the response is in JSON format

$response = []; // Initialize response array

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $patientName = isset($_POST['patientName']) ? htmlspecialchars($_POST['patientName']) : '';
    $purpose = isset($_POST['purpose']) ? htmlspecialchars($_POST['purpose']) : '';
    $appointmentDateTime = isset($_POST['appointmentDateTime']) ? htmlspecialchars($_POST['appointmentDateTime']) : '';
    $healthWorker = isset($_POST['healthWorker']) ? htmlspecialchars($_POST['healthWorker']) : '';

    // Prepare SQL insert query with placeholders
    $sql = "INSERT INTO p_appointment (p_name, p_purpose, datetime, a_healthworker) VALUES (?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters to the SQL statement
        $stmt->bind_param("ssss", $patientName, $purpose, $appointmentDateTime, $healthWorker);

        if ($stmt->execute()) {
            // Fetch updated data
            $fetchSql = "SELECT * FROM p_appointment";
            $result = $conn->query($fetchSql);

            $appointments = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Convert datetime to a more readable format
                    try {
                        $dateTime = new DateTime($row['datetime']);
                        $formattedDateTime = $dateTime->format('F j, Y g:i A'); // Format: September 14, 2024 5:30 AM
                        $row['datetime'] = $formattedDateTime;
                    } catch (Exception $e) {
                        // Handle any date conversion errors
                        $row['datetime'] = 'Invalid Date';
                    }

                    $appointments[] = $row;
                }
            }

            // Set success response
            $response['success'] = true;
            $response['message'] = "Appointment added successfully.";
            $response['data'] = $appointments;
        } else {
            // Error during query execution
            $response['success'] = false;
            $response['message'] = "Error inserting appointment: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // Error preparing the statement
        $response['success'] = false;
        $response['message'] = "Error preparing statement: " . $conn->error;
    }

    $conn->close();
} else {
    // Invalid request method
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

// Return JSON response
echo json_encode($response);
?>
