<?php
header('Content-Type: application/json');
include '../connect.php';

// Initialize response array
$response = array();

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if required POST variables are set
    if (isset($_POST['medId'], $_POST['medName'], $_POST['medDesc'], $_POST['stockIn'], $_POST['stockOut'], $_POST['stockExp'], $_POST['stockAvail'])) {
        // Retrieve form data
        $medId = $_POST['medId'];
        $medName = $_POST['medName'];
        $medDesc = $_POST['medDesc'];
        $stockIn = $_POST['stockIn'];
        $stockOut = $_POST['stockOut'];
        $stockExp = $_POST['stockExp'];
        $stockAvail = $_POST['stockAvail'];

        // Prepare update query with placeholders
        $sql = "UPDATE inv_meds SET 
                meds_name = ?, 
                med_dscrptn = ?, 
                stock_in = ?, 
                stock_out = ?, 
                stock_exp = ?, 
                stock_avail = ? 
                WHERE med_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("ssssssi", $medName, $medDesc, $stockIn, $stockOut, $stockExp, $stockAvail, $medId);

            // Execute statement
            if ($stmt->execute()) {
                // Fetch updated data
                $result = $conn->query("SELECT * FROM inv_meds");

                if ($result) {
                    $medicines = $result->fetch_all(MYSQLI_ASSOC);

                    $response['success'] = true;
                    $response['message'] = 'Medicine updated successfully.';
                    $response['medicines'] = $medicines; // Include updated medicines
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Error fetching updated medicines list: ' . $conn->error;
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
