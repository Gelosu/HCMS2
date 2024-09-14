<?php
include '../connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data from POST request
    $supplyId = $_POST['supplyId'];
    $supplyName = $_POST['supplyName'];
    $stockIn = $_POST['stockIn2'];
    $stockOut = $_POST['stockOut2'];
    $stockExpired = $_POST['stockExpired2'];
    $stockAvailable = $_POST['stockAvailable2'];

    // Initialize response array
    $response = [];

    // Prepare update query with parameter binding
    $stmt = $conn->prepare("UPDATE inv_medsup SET 
                            prod_name = ?, 
                            stck_in = ?, 
                            stck_out = ?, 
                            stck_expired = ?, 
                            stck_avl = ? 
                            WHERE med_supId = ?");
    $stmt->bind_param("sssssi", $supplyName, $stockIn, $stockOut, $stockExpired, $stockAvailable, $supplyId);

    if ($stmt->execute()) {
        // Fetch all medical supplies after update
        $sql = "SELECT * FROM inv_medsup";
        $result = $conn->query($sql);

        $supplies = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $supplies[] = $row;
            }
        }

        // Set success response
        $response['data'] = $supplies;
        $response['success'] = true;
        $response['message'] = "Update successful.";
    } else {
        // Error during query execution
        $response['error'] = "Update failed: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    $response['error'] = 'Invalid request method';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Close the connection
$conn->close();
?>
