<?php
include '../connect.php';

header('Content-Type: application/json'); // Ensure the response is in JSON format

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize
    $medName = $_POST['medName'];
    $medDesc = $_POST['medDesc'];
    $stockIn = $_POST['stockIn'];
    $stockOut = $_POST['stockOut'];
    $stockExp = $_POST['stockExp'];
    $stockAvail = $_POST['stockAvail'];

    // Initialize response array
    $response = [];

    // Prepare SQL insert query with placeholders
    $sql = "INSERT INTO inv_meds (meds_name, med_dscrptn, stock_in, stock_out, stock_exp, stock_avail)
            VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters to the SQL statement
        $stmt->bind_param("ssssss", $medName, $medDesc, $stockIn, $stockOut, $stockExp, $stockAvail);

        if ($stmt->execute()) {
            // Fetch updated data
            $fetchSql = "SELECT * FROM inv_meds";
            $result = $conn->query($fetchSql);

            $medicines = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $medicines[] = $row;
                }
            }

            // Set success response
            $response['success'] = true;
            $response['message'] = "Record inserted successfully.";
            $response['data'] = $medicines;
        } else {
            // Error during query execution
            $response['success'] = false;
            $response['message'] = "Error inserting record: " . $stmt->error;
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
