<section id="events" class="section" style="display: none;">

<div class="search-and-add-container">
    <!-- Search bar container -->
    <div class="search-container">
        <input type="text" id="searchInput" onkeyup="searchTable7(this.value);" placeholder="Search for events...">
    </div>
    <div class="filter-container">
        
    <select id="categoryDropdown" onchange="filterByCategory2(this.value)">
    <option value="">All</option>
    <option value="January">January</option>
    <option value="February">February</option>
    <option value="March">March</option>
    <option value="April">April</option>
    <option value="May">May</option>
    <option value="June">June</option>
    <option value="July">July</option>
    <option value="August">August</option>
    <option value="September">September</option>
    <option value="October">October</option>
    <option value="November">November</option>
    <option value="December">December</option>
</select>

<select id="yearDropdown" onchange="filterByYear(this.value)">
    <option value="">Select Year</option>
    <!-- Options will be added here dynamically -->
</select>

    </div>
<div class="add-button-container2">
        <button onclick="openAddEventModal()">Add Event</button>
    </div>
</div>

 <!-- Events Table -->
 <div class="table-container">
        <table id="eventsTable" class="styled-table">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Description</th>
                    <th>Scheduled Date and Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'connect.php';
                $sql = "SELECT * FROM events";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $datetime = new DateTime($row["datetime"]);
                        $formattedDate = $datetime->format('F j, Y');
                        $formattedTime = $datetime->format('g:i A');
                        $formattedDateTime = $formattedDate . '  at  ' . $formattedTime;
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["event_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["event_description"]) . "</td>";
                        echo "<td>" . $formattedDateTime . "</td>"; 
                        echo "<td>";

                        // Edit button
                        echo "<a href='#' class='edit-btn' onclick=\"openEditEventModal(
                            '" . htmlspecialchars($row['id']) . "',
                            '" . htmlspecialchars($row['event_name']) . "',
                            '" . htmlspecialchars($row['event_description']) . "',
                            '" . htmlspecialchars($row['datetime']) . "'
                        )\">
                        <img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'></a>";

                        // Delete button
                        echo "<a href='#' class='delete-btn' onclick=\"deleteEvent('" . htmlspecialchars($row['id']) . "')\">
                        <img src='delete_icon.png' alt='Delete' style='width: 20px; height: 20px;'></a>";

                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Error: " . htmlspecialchars(mysqli_error($conn)) . "</td></tr>";
                }

                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
    </div>
            
           <!-- Add Event Modal -->
<div id="addEventModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeAddEventModal()">&times;</span>
        <h3>Add Event</h3>
        <form id="addEventForm" onsubmit="submitAddEventForm(event)">
            <!-- Event Name -->
            <label for="eventName">Event Name:</label>
            <input type="text" id="eventName" name="eventName" required><br><br>

            <!-- Event Description -->
            <label for="eventDescription">Event Description:</label>
            <input type="text" id="eventDescription" name="eventDescription" required><br><br>

            <!-- Date and Time -->
            <label for="eventDateTime">Date and Time:</label>
            <input type="datetime-local" id="eventDateTime" name="eventDateTime" required><br><br>

            <!-- Submit Button -->
            <input type="submit" value="Add Event">
        </form>
    </div>
</div>


<!-- Edit Event Modal -->
<div id="editEventModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeEditEventModal()">&times;</span>
        <h3>Edit Event</h3>
        <form id="editEventForm" onsubmit="submitEditEventForm(event)">
            <!-- Hidden Field for Event ID -->
            <input type="hidden" id="editEventId" name="eventId">

            <!-- Event Name -->
            <label for="editEventName">Event Name:</label>
            <input type="text" id="editEventName" name="eventName" required><br><br>

            <!-- Event Description -->
            <label for="editEventDescription">Event Description:</label>
            <input type="text" id="editEventDescription" name="eventDescription" required><br><br>

            <!-- Date and Time -->
            <label for="editEventDateTime">Date and Time:</label>
            <input type="datetime-local" id="editEventDateTime" name="eventDateTime" required><br><br>

            <!-- Submit Button -->
            <input type="submit" value="Save Changes">
        </form>
    </div>
</div>


        
 