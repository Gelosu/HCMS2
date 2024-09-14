<?php
include '../connect.php'; // Ensure your database connection is secure

// Get the raw POST data and decode it from JSON
$data = json_decode(file_get_contents('php://input'), true);

// Get the ID from the decoded data
$id = $data['id'] ?? null;

$response = [];

if ($id) {
    $id = intval($id); // Sanitize ID to integer

    // Prepare the SQL statement to prevent SQL injection
    $sql = "DELETE FROM admin WHERE adid = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $id);

        // Execute the statement
        if ($stmt->execute()) {
            // Check if any rows were affected
            if ($stmt->affected_rows > 0) {
                // Fetch the updated user list
                $userSql = "SELECT adid, adname, adsurname, adusername, adpass, adposition FROM admin";
                $userResult = $conn->query($userSql);

                if ($userResult) {
                    $users = [];
                    while ($row = $userResult->fetch_assoc()) {
                        $users[] = $row;
                    }
                    $response['success'] = true;
                    $response['users'] = $users; // Include updated user list in response
                } else {
                    $response['error'] = 'Error fetching updated user list: ' . $conn->error;
                }
            } else {
                $response['error'] = 'No user found with the provided ID';
            }
        } else {
            $response['error'] = 'Error executing statement: ' . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        $response['error'] = 'Error preparing statement';
    }
} else {
    $response['error'] = 'No ID provided';
}

// Close the database connection
$conn->close();

// Return the response as JSON
echo json_encode($response);
?>
