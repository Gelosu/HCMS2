</section>


        <!-- Patient Medication section -->
        <section id="patient-med" class="section" style="display: none;">
            <h2>Patient Medication</h2>
            
            <div class="search-and-add-container">
        <!-- Search bar container -->
        <div class="search-container">
        <input type="text" id="searchInput" onkeyup="searchTable5(this.value);" placeholder="Search for patient medication...">
        </div>

        <!-- Button container -->
        <div class="add-button-container">
            <button onclick="openAddMedicationModal()">Add Patient Medication</button>
        </div>
    </div>
        <!-- Add Patient Medication Modal -->
<div id="addMedicationModal" class="modal2">
<div class="modal-content2">
<span class="close" onclick="closeAddMedicationModal()">&times;</span>
    <h3>Add Patient Medication</h3>
    
    <!-- Form starts here -->
    <form id="addMedicationForm" onsubmit="submitAddMedicationForm(event)">
        <!-- Patient Dropdown -->
        <label for="medicationPatientName">Name of Patient:</label>
        <select id="medicationPatientName" name="medicationPatientName" required>
            <!-- Options will be populated dynamically -->
        </select>
        
        <!-- Medicine Section -->
        <div id="medicineContainer">
            <div class="medicine-entry">
                <label for="medicines">Medicine:</label>
                <select class="medicine-dropdown" name="medicines[]" required>
                    <!-- Options will be populated dynamically -->
                </select>
                <label for="amount">Amount:</label>
                <input type="number" name="amount[]" class="medicine-amount" required>
            </div>
        </div>

        <!-- Add Medicine Button -->
        <button type="button" onclick="addMedicineField()">Add Another Medicine</button>
        
        <!-- Date and Time Input -->
        <label for="medicationDateTime">Date and Time:</label>
        <input type="datetime-local" id="medicationDateTime" name="medicationDateTime" required>
        
        <!-- Assigned Healthworker -->
        <label for="medicationHealthWorker">Assigned Healthworker:</label>
        <input type="text" id="medicationHealthWorker" name="medicationHealthWorker" value="<?php echo htmlspecialchars($healthWorker); ?>" readonly>
        
        <input type="submit" value="Submit">
    </form>
</div>
</div>



<!-- Edit Patient Medication Modal -->
<div id="editMedicationModal" class="modal2">
    <div class="modal-content2">
        <span class="close" onclick="closeEditMedicationModal()">&times;</span>
        <h3>Edit Patient Medication</h3>
        
        <!-- Form starts here -->
        <form id="editMedicationForm" onsubmit="submitEditMedicationForm(event)">
            <!-- Patient Dropdown (disabled or read-only if you don't want to change it) -->
            <label for="editMedicationPatientName">Name of Patient:</label>
            <input type="text" id="editMedicationPatientName" name="editMedicationPatientName" readonly required>
           <!-- Medicine Section in Edit Modal -->
<div id="editMedicineContainer">
    <div class="medicine-entry">
        <label for="editMedicines">Medicine:</label>
        <select class="medicine-dropdown" name="editMedicines[]" required>
            <!-- Options will be populated dynamically -->
        </select>
        <label for="editAmount">Amount:</label>
        <input type="number" name="editAmount[]" class="medicine-amount" required>
    </div>
</div>

<!-- Add Medicine Button -->
<button type="button" onclick="addEditMedicineField()">Add Another Medicine</button>
            
            <!-- Date and Time Input -->
            <label for="editMedicationDateTime">Date and Time:</label>
            <input type="datetime-local" id="editMedicationDateTime" name="editMedicationDateTime" required>
            
            <!-- Assigned Healthworker -->
            <label for="editMedicationHealthWorker">Assigned Healthworker:</label>
            <input type="text" id="editMedicationHealthWorker" name="editMedicationHealthWorker" readonly>
            
            <!-- Hidden input to store the ID of the medication being edited -->
            <input type="hidden" id="editMedicationId" name="editMedicationId">
            
            <!-- Submit and Cancel buttons -->
            <input type="submit" value="Submit">

        </form>
    </div>
</div>



            <!-- Medications Table -->
            <div class="table-container">
            <table id="medicationtable">
            <thead>
        <tr>
            <th>Patient Name</th>
            <th>Medicines with Amount</th>
            <th>Date and Time</th>
            <th>Assigned Healthworker</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php
include 'connect.php';

// SQL query to fetch medications along with patient name
$sql = "SELECT id, p_medpatient AS patient_name, p_medication AS medication, datetime AS date_time, a_healthworker AS healthworker
        FROM p_medication";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["patient_name"]) . "</td>";

        // Decode the JSON data for p_medication
        $medications = json_decode($row["medication"], true);
        $medicationDetails = [];

        if (is_array($medications)) {
            foreach ($medications as $medication) {
                // Use default values if 'name' or 'amount' keys are missing
                $med_name = isset($medication['name']) ? htmlspecialchars($medication['name']) : 'Unknown Medicine';
                $amount = isset($medication['amount']) ? htmlspecialchars($medication['amount']) : '0';

                // Add formatted medication name and amount to the details array
                $medicationDetails[] = "$amount x $med_name";
            }
        }

        // Format the medication details into a single string
        $medicationDisplay = implode("<br>", $medicationDetails);

        echo "<td>" . $medicationDisplay . "</td>";

        // Format the date and time
        $dateTime = new DateTime($row["date_time"]);
        $formattedDateTime = $dateTime->format('F j, Y \a\t g:i a');

        echo "<td>" . htmlspecialchars($formattedDateTime) . "</td>";
        echo "<td>" . htmlspecialchars($row["healthworker"]) . "</td>";
        echo "<td class='action-icons'>";

        // Edit button
        $id = htmlspecialchars($row["id"]);
        $patient_name = htmlspecialchars($row["patient_name"]);
        $medications_json = htmlspecialchars(json_encode(json_decode($row["medication"], true)));
        $date_time = htmlspecialchars($row["date_time"]);
        $healthworker = htmlspecialchars($row["healthworker"]);

        echo "<button onclick=\"openEditMedicationModal('$id', '$patient_name', '$medications_json', '$date_time', '$healthworker')\">";
        echo "<img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'></button>";

        // Delete button
        echo "<button onclick=\"deleteMedication('" . htmlspecialchars($row["id"]) . "')\">";
        echo "<img src='delete_icon.png' alt='Delete' class='delete-btn' style='width: 20px; height: 20px;'></button>";

        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No medications found</td></tr>";
}

$conn->close();
?>



    </tbody>
</table>
</div>
        </section>