<?php
// Check if form data is sent
if (isset($_POST['eventName']) && isset($_POST['eventDescription']) && isset($_POST['eventDateTime'])) {
    // Include your database connection file
    include '../connect.php'; // Adjust the path if necessary

    // Get form data
    $eventName = $_POST['eventName'];
    $eventDescription = $_POST['eventDescription'];
    $eventDateTime = $_POST['eventDateTime'];

    // Sanitize input to prevent SQL injection
    $eventName = $conn->real_escape_string($eventName);
    $eventDescription = $conn->real_escape_string($eventDescription);
    $eventDateTime = $conn->real_escape_string($eventDateTime);

    // SQL query to insert a new event
    $sql = "INSERT INTO events (event_name, event_description, datetime) 
            VALUES ('$eventName', '$eventDescription', '$eventDateTime')";

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

        // Fetch distinct years
        $sql = "SELECT DISTINCT YEAR(datetime) AS year FROM events ORDER BY year DESC";
        $result = $conn->query($sql);

        $years = array();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $years[] = $row['year'];
            }
        }

        // Send success response with event data and years
        echo json_encode(array(
            'success' => true,
            'events' => $events,
            'years' => $years
        ));
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
