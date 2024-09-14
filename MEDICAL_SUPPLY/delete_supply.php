<?php
header('Content-Type: application/json');
include '../connect.php';

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the supply ID from POST request
    $medSupId = $_POST['medSupId'];

    // Initialize response array
    $response = [];

    // Prepare SQL delete query with placeholder
    $sql = "DELETE FROM inv_medsup WHERE med_supId = ?";

    // Prepare and bind parameters to the SQL statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $medSupId);

        if ($stmt->execute()) {
            // After deletion, fetch the updated list of supplies
            $fetchSql = "SELECT * FROM inv_medsup";
            $result = $conn->query($fetchSql);

            $supplies = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $supplies[] = $row;
                }
            }

            // Set success response
            $response['success'] = true;
            $response['message'] = "Record deleted successfully";
            $response['supplies'] = $supplies;
        } else {
            // Error during query execution
            $response['success'] = false;
            $response['message'] = "Error deleting record: " . $stmt->error;
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
    $response['message'] = "Invalid request method";
}

// Return JSON response
echo json_encode($response);
?>
