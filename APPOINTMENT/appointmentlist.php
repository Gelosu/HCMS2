    <!-- Patient Appointment section -->
    <section id="patient-appointment" class="section">
    <h2>Patient Appointment</h2>
    <div class="search-and-add-container">
    <!-- Search bar container -->
    <div class="search-container">
        <input type="text" id="searchInput" onkeyup="searchTable4(this.value);" placeholder="Search for appointments...">
    </div>

    <!-- Button container -->
    <div class="add-button-container">
        <button onclick="openAddAppointmentModal()">Add Appointment</button>
    </div>
    </div>

<!-- Add Appointment Modal -->
<div id="addAppointmentModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddAppointmentModal()">&times;</span>
        <h3>Add Appointment</h3>
        <form id="addAppointmentForm" onsubmit="submitAddAppointmentForm(event)">
            <label for="patientName">Name of Registered Patient:</label>
            <select id="patientName" name="patientName" required>
                <!-- Options will be populated dynamically -->
            </select>
            
            <label for="purpose">Purpose:</label>
            <select id="purpose" name="purpose" required>
                <option value="Consultation">Consultation</option>
                <option value="Follow-Up">Follow-Up</option>
                <option value="Emergency">Emergency</option>
                <!-- Add more options as needed -->
            </select>
            
            <label for="appointmentDateTime">Date and Time:</label>
            <input type="datetime-local" id="appointmentDateTime" name="appointmentDateTime" required>
            
            <label for="healthWorker">Assigned Healthworker:</label>
            <input type="text" id="healthWorker" name="healthWorker" readonly>
            
            <input type="submit" value="Submit">
            
        </form>
    </div>
</div>


<!--  Edit Appointment Modal -->
<div id="editAppointmentModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditAppointmentModal()">&times;</span>
        <h3>Edit Appointment</h3>
        <form id="editAppointmentForm" onsubmit="submitEditAppointmentForm(event)">
            
            <!-- Display patient name as non-editable -->
            <label for="editPatientName">Name of Patient:</label>
            <input type="text" id="editPatientName" name="editPatientName" readonly>
            
            <label for="editPurpose">Purpose:</label>
            <select id="editPurpose" name="editPurpose" required>
                <option value="Consultation">Consultation</option>
                <option value="Follow-Up">Follow-Up</option>
                <option value="Emergency">Emergency</option>
                <!-- Add more options as needed -->
            </select>
            
            <label for="editAppointmentDateTime">Date and Time:</label>
            <input type="datetime-local" id="editAppointmentDateTime" name="editAppointmentDateTime" required>
            
            <!-- Health worker field set to readonly -->
            <label for="editHealthWorker">Assigned Healthworker:</label>
            <input type="text" id="editHealthWorker" name="editHealthWorker" readonly>
            
            <!-- Hidden field for appointment ID -->
            <input type="hidden" id="editAppointmentId" name="editAppointmentId">
            
            <input type="submit" value="Submit">
            <input type="button" onclick="closeEditAppointmentModal()">Cancel</input>
        </form>
    </div>
</div>





    <!-- Appointments Table -->
    <div class="table-container">
<table id="appointmentsTable">
    <thead>
        <tr>
            <th>Name of Patient</th>
            <th>Purpose</th>
            <th>Date</th>
            <th>Assigned Healthworker</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php
    include 'connect.php'; 

    $sql = "SELECT * FROM p_appointment";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Format date and time
            $datetime = new DateTime($row["datetime"]);
            $formattedDate = $datetime->format('F j, Y');
            $formattedTime = $datetime->format('g:i A');
            $formattedDateTime = $formattedDate . ' ' . $formattedTime;

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["p_name"]) . "</td>"; 
            echo "<td>" . htmlspecialchars($row["p_purpose"]) . "</td>"; 
            echo "<td>" . $formattedDateTime . "</td>"; 
            echo "<td>" . htmlspecialchars($row["a_healthworker"]) . "</td>"; 
            echo "<td class='action-icons'>";
            echo "<a href='#' class='edit-btn' onclick=\"openEditAppointmentModal('" . 
            $row["id"] . "', '" . 
            htmlspecialchars($row["p_name"]) . "', '" . 
            htmlspecialchars($row["p_purpose"]) . "', '" . 
            htmlspecialchars($row["datetime"]) . "', '" . 
            htmlspecialchars($row["a_healthworker"]) . "')\">";
            echo "<img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'></a>";
            
            echo "<a href='#' class='delete-btn' onclick=\"deleteAppointment('" . $row["id"] . "')\">";
            echo "<img src='delete_icon.png' alt='Delete' class='delete-btn' style='width: 20px; height: 20px;'></a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No appointments found</td></tr>";
    }

    $conn->close();
    ?>
    </tbody>
</table>
</div>
