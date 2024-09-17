<?php
// Check if form data is sent
if (isset($_POST['eventId']) && isset($_POST['eventName']) && isset($_POST['eventDescription']) && isset($_POST['eventDateTime'])) {
    // Include your database connection file
    include '../connect.php'; // Adjust the path if necessary

    // Get form data
    $eventId = $_POST['eventId'];
    $eventName = $_POST['eventName'];
    $eventDescription = $_POST['eventDescription'];
    $eventDateTime = $_POST['eventDateTime'];

    // Sanitize input to prevent SQL injection
    $eventId = $conn->real_escape_string($eventId);
    $eventName = $conn->real_escape_string($eventName);
    $eventDescription = $conn->real_escape_string($eventDescription);
    $eventDateTime = $conn->real_escape_string($eventDateTime);

    // SQL query to update the event
    $sql = "UPDATE events 
            SET event_name = '$eventName', event_description = '$eventDescription', datetime = '$eventDateTime' 
            WHERE id = '$eventId'";

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

        // Send success response with event data
        echo json_encode(array('success' => true, 'events' => $events));
    } else {
        // Respond with error message
        echo json_encode(array('success' => false, 'error' => 'Database Error: ' . $conn->error));
    }

    $conn->close();
} else {
    // If form data is not set, return an error message
    echo json_encode(array('success' => false, 'error' => 'Required parameters are not set'));
}
?>
