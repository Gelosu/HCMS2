<?php
// Read the input from the request body
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Check if event ID is sent
if (isset($data['id'])) {
    // Include your database connection file
    include '../connect.php'; // Adjust the path if necessary

    // Get event ID
    $eventId = $data['id'];

    // Log received ID for debugging
    error_log("Received Event ID: " . $eventId);

    // Sanitize input to prevent SQL injection
    $eventId = $conn->real_escape_string($eventId);

    // SQL query to delete the event
    $sql = "DELETE FROM events WHERE id = '$eventId'";

    if ($conn->query($sql) === TRUE) {
        // Fetch the updated event list
        $sql = "SELECT id, event_name, event_description, datetime FROM events";
        $result = $conn->query($sql);

        $events = array();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $events[] = $row;
            }
        }

        // Send success response with updated event data
        echo json_encode(array('success' => true, 'events' => $events));
    } else {
        // Respond with error message
        echo json_encode(array('success' => false, 'error' => 'Database Error: ' . $conn->error));
    }

    $conn->close();
} else {
    // If ID is not set, return an error message
    error_log("Error deleting event: Event ID not set.");
    echo json_encode(array('success' => false, 'error' => 'Event ID not set'));
}
?>
