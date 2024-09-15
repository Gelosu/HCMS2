<?php
include '../connect.php'; // Include your database connection file

header('Content-Type: application/json'); // Ensure the response is in JSON format

// Fetch medicines from the inventory
$sql = "SELECT med_id, meds_name, stock_avail FROM inv_meds WHERE stock_avail > 0"; // Only fetch available medicines
$result = $conn->query($sql);

$medicines = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $medicines[] = $row; // Store each medicine row in the array
    }
}

// Close the connection
$conn->close();

// Return the data as JSON
echo json_encode([
    'success' => true,
    'data' => $medicines
]);
?>
