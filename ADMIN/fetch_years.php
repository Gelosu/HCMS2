<?php
// Include your database connection file
include '../connect.php'; // Adjust the path if necessary

// SQL query to get distinct years
$sql = "SELECT DISTINCT YEAR(datetime) AS year FROM events ORDER BY year DESC";
$result = $conn->query($sql);

$years = array();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $years[] = $row['year'];
    }
}

// Send response with unique years
echo json_encode($years);

$conn->close();
?>
