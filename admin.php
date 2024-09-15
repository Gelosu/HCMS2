<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['adusername'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch user info from session
$adfirstname = $_SESSION['adfirstname'];
$adsurname = $_SESSION['adsurname'];
$healthWorker = $_SESSION['adfirstname'] . ' ' . $_SESSION['adsurname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STA. MARIA HCMS</title>
    <link rel="stylesheet" href="mamamoadmin.css">
    <link rel="stylesheet" href="style.css">
   
</head>
<body>

<header>
    <h1>BRGY STA. MARIA HEALTH CENTER</h1>
</header>

<!-- SIDE BAR -->
<div id="sidebar">
    <div id="logo">
        <img src="mary.jpg" alt="Logo">
    </div>
    <p>HEALTH WORKER: <?php echo htmlspecialchars($adfirstname . ' ' . $adsurname); ?></p>
    <ul>
    <li><a href="#" onclick="setActiveSection('dashboard')">Dashboard</a></li>
        <li><a href="#" onclick="setActiveSection('medical_supplies-inventory')">Medical & Emergency Supplies Inventory</a></li>
        <li><a href="#" onclick="setActiveSection('medicine-inventory')">Medicine Inventory</a></li>
        <li><a href="#" onclick="setActiveSection('patient-list')">Patient List</a></li>
        <li><a href="#" onclick="setActiveSection('patient-appointment')">Patient Appointment</a></li>
        <li><a href="#" onclick="setActiveSection('patient-med')">Patient - Medication</a></li>
        
       
    </ul>
    <button id="logoutBtn">Logout</button>
</div>


<div id="content">
<?php
        
        include 'PATIENTLIST/patientlist.php';
        include 'MEDICAL_SUPPLY/medicalsupply.php';
        include 'MEDICINES/medicinelist.php';
        include 'APPOINTMENT/appointmentlist.php';
?>

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
<div id="addMedicationModal" class="modal">
<div class="modal-content">
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
        
        <!-- Submit and Cancel buttons -->
        <button type="submit">Submit</button>
        <button type="button" onclick="closeAddMedicationModal()">Cancel</button>
    </form>
</div>
</div>



<!-- Edit Patient Medication Modal -->
<div id="editMedicationModal" class="modal" style="display: none;">
    <div class="modal-content">
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
            <button type="submit">Save Changes</button>
            <button type="button" onclick="closeEditMedicationModal()">Cancel</button>
        </form>
    </div>
</div>



            <!-- Medications Table -->
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
        </section>


</div>
<script>const healthWorkerName = "<?php echo htmlspecialchars($healthWorker); ?>";</script>
<script src="functionforMEDSUPP.js"></script>
<script src="functionforMEDICINE.js"></script>
<script src="functionforPATIENTLIST.js"></script>
<script src="functionforP_APPOINTMENT.js"> </script>
<script src="SEARCH_FILTER.js"> </script>
<script> 


// Open Add Medication Modal
// Open Add Medication Modal
function openAddMedicationModal() {
    // Fetch and populate patient names
    fetch('P_MEDICATION/fetch_patients.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populatePatientDropdown(data.data, 'medicationPatientName');
            } else {
                console.error('Failed to fetch patient data:', data.message);
            }
        })
        .catch(error => console.error('Error fetching patient data:', error));

    // Fetch and populate medicines for the dropdowns
    fetch('P_MEDICATION/fetch_medicines.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Ensure all existing dropdowns are populated
                populateMedicineDropdown(data.data);
            } else {
                console.error('Failed to fetch medicine data:', data.message);
            }
        })
        .catch(error => console.error('Error fetching medicine data:', error));

    // Show the modal
    document.getElementById('addMedicationModal').style.display = 'block';
}

// Populate all medicine dropdowns
function populateMedicineDropdown(medicines) {
    const dropdowns = document.querySelectorAll('.medicine-dropdown'); // Select all current dropdowns
    dropdowns.forEach(dropdown => {
        dropdown.innerHTML = ''; // Clear existing options

        const defaultOption = document.createElement('option');
        defaultOption.text = 'Select a medicine';
        defaultOption.value = '';
        dropdown.appendChild(defaultOption);

        medicines.forEach(medicine => {
            const option = document.createElement('option');
            option.value = medicine.med_id;  // assuming medicine ID is 'id'
            option.text = `${medicine.meds_name} (Available: ${medicine.stock_avail})`; // Display medicine name and available stock
            dropdown.appendChild(option);
        });
    });
}


// Close Add Medication Modal
function closeAddMedicationModal() {
    document.getElementById('addMedicationModal').style.display = 'none';
}

// Populate patient dropdown
function populatePatientDropdown(patients, dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    dropdown.innerHTML = ''; // Clear existing options

    const defaultOption = document.createElement('option');
    defaultOption.text = 'Select a patient';
    defaultOption.value = '';
    dropdown.add(defaultOption);

    patients.forEach(patient => {
        const option = document.createElement('option');
        option.value = patient.p_id; // assuming patient ID is 'p_id'
        option.text = patient.p_name; // assuming patient name is 'p_name'
        dropdown.add(option);
    });
}

// Cache medicine options globally to avoid fetching multiple times
let medicineOptions = [];

// Function to fetch and cache medicine options
function fetchMedicineOptions() {
    return fetch('P_MEDICATION/fetch_medicines.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                medicineOptions = data.data;
                return medicineOptions;
            } else {
                throw new Error('Failed to fetch medicine options.');
            }
        });
}

// Function to add a new medicine input field dynamically
function addMedicineField() {
    const container = document.getElementById('medicineContainer');
    
    const newMedicineEntry = document.createElement('div');
    newMedicineEntry.className = 'medicine-entry';

    // Create new medicine dropdown
    const medicineLabel = document.createElement('label');
    medicineLabel.textContent = "Medicine:";
    newMedicineEntry.appendChild(medicineLabel);

    const newMedicineDropdown = document.createElement('select');
    newMedicineDropdown.className = 'medicine-dropdown';
    newMedicineDropdown.name = 'medicines[]';
    newMedicineDropdown.required = true;
    newMedicineEntry.appendChild(newMedicineDropdown);

    // Create new amount input
    const amountLabel = document.createElement('label');
    amountLabel.textContent = "Amount:";
    newMedicineEntry.appendChild(amountLabel);

    const newAmountInput = document.createElement('input');
    newAmountInput.type = 'number';
    newAmountInput.name = 'amount[]';
    newAmountInput.required = true;
    newMedicineEntry.appendChild(newAmountInput);

    // Append the new entry to the medicine container
    container.appendChild(newMedicineEntry);

    // Populate the newly added medicine dropdown
    if (medicineOptions.length > 0) {
        populateMedicineDropdownForEntry(newMedicineDropdown);
    } else {
        // Fetch medicine options if not already cached
        fetchMedicineOptions().then(() => {
            populateMedicineDropdownForEntry(newMedicineDropdown);
        });
    }
}

// Function to populate a specific medicine dropdown (used for both add and edit modals)
function populateMedicineDropdownForEntry(dropdown, selectedMedicine = '') {
    dropdown.innerHTML = ''; // Clear existing options

    const defaultOption = document.createElement('option');
    defaultOption.text = 'Select a medicine';
    defaultOption.value = '';
    dropdown.add(defaultOption);

    // Loop through available medicine options and add them to the dropdown
    medicineOptions.forEach(medicine => {
        const option = document.createElement('option');
        option.value = medicine.med_id;  // assuming 'med_id' is the unique ID for each medicine
        option.text = `${medicine.meds_name} (Available: ${medicine.stock_avail})`; // Display name and available stock
        dropdown.add(option);

        // Pre-select the correct medicine if it matches the 'selectedMedicine' value
        if (medicine.med_id === selectedMedicine || medicine.meds_name === selectedMedicine) {
            dropdown.value = medicine.med_id;
        }
    });
}


// Submit Add Medication Form
function submitAddMedicationForm(event) {
    event.preventDefault();

    const form = document.getElementById('addMedicationForm');
    const formData = new FormData(form);

    // Ensure the FormData object contains the correct data
    console.log('FormData contents:');
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }

    fetch('P_MEDICATION/add_pmedication.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())  // Parse the response as JSON
    .then(result => {
        if (result.success) {
            console.log("Results: ", result);
            closeAddMedicationModal();
            updateMedicationTable(result.data); // Refresh table with the updated data
        } else {
            console.log('Error adding medication: ' + result.error);
        }
    })
    .catch(error => console.error('Fetch Error:', error));
}


// Update Medication Table
function updateMedicationTable(medications) {
    const tableBody = document.querySelector('#medicationtable tbody');
    
    if (tableBody) {
        tableBody.innerHTML = ''; // Clear existing rows

        // Check if medications is an array
        if (Array.isArray(medications)) {
            medications.forEach(medication => {
                const row = document.createElement('tr');

                // Ensure p_medication is an array
                let medicinesList = '';
                if (Array.isArray(medication.p_medication)) {
                    medication.p_medication.forEach(med => {
                        // Check if each med object has name and amount
                        if (med.name && med.amount) {
                            medicinesList += `${med.amount}x ${med.name}<br>`;
                        } else {
                            console.warn('Medication object missing name or amount:', med);
                        }
                    });
                } else {
                    console.warn('Expected p_medication to be an array, but got:', medication.p_medication);
                }

                // Create and append row
                row.innerHTML = `
                    <td>${medication.patient_name}</td>
                    <td>${medicinesList}</td>
                    <td>${formatDateTime(medication.date_time)}</td>
                    <td>${medication.healthworker}</td>
                    <td>
                        <button onclick="openEditMedicationModal('${medication.id}', '${medication.patient_name}', '${JSON.stringify(medication.p_medication)}', '${medication.date_time}', '${medication.healthworker}')">
                            <img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'>
                        </button>
                        <button onclick="deleteMedication('${medication.id}')">
                            <img src='delete_icon.png' alt='Delete' class='delete-btn' style='width: 20px; height: 20px;'>
                        </button>
                    </td>
                `;

                tableBody.appendChild(row);
            });
        } else {
            console.error('Expected an array of medications, but got:', medications);
        }
    } else {
        console.error('Table body not found. Ensure the table ID and selector are correct.');
    }
}

// Helper function to format date and time
function formatDateTime(dateTime) {
    const date = new Date(dateTime);
    // Customize the format as needed
    return date.toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}




// Function to open the Edit Medication Modal and populate the fields
// Function to open the Edit Medication Modal and populate the fields
function openEditMedicationModal(id, patientName, medicationsJson, dateTime, healthworker) {
    console.log("ID:", id);
    console.log("Patient Name:", patientName);
    console.log("Medications JSON:", medicationsJson);
    console.log("Date Time:", dateTime);
    console.log("Health Worker:", healthworker);

    // Set form values
    document.getElementById('editMedicationId').value = id;
    document.getElementById('editMedicationPatientName').value = patientName;
    document.getElementById('editMedicationDateTime').value = formatDateTimeForInput(dateTime);
    document.getElementById('editMedicationHealthWorker').value = healthworker;

    // Parse the JSON string into an object
    let medications;
    try {
        medications = JSON.parse(medicationsJson);
    } catch (error) {
        console.error("Error parsing JSON:", error);
        medications = [];
    }

    if (!Array.isArray(medications)) {
        console.error("Expected medications to be an array but got:", medications);
        medications = [];
    }

    // Populate medicines
    const editMedicineContainer = document.getElementById('editMedicineContainer');
    editMedicineContainer.innerHTML = ''; // Clear existing entries

    // Add each medication dynamically with delete option and pre-select the correct value
    medications.forEach(med => {
        addEditMedicineField(med.name, med.amount); // Pass med.name to pre-select the correct medicine
    });

    // Show the modal
    document.getElementById('editMedicationModal').style.display = 'block';
}


// Format Date and Time for input
function formatDateTimeForInput(datetime) {
    const date = new Date(datetime);
    return date.toISOString().slice(0, 16); // Format for datetime-local input
}

// Function to add a new medicine input field dynamically in the Edit Modal
function addEditMedicineField(medName = '', medAmount = '') {
    const editContainer = document.getElementById('editMedicineContainer');
    
    const newMedicineEntry = document.createElement('div');
    newMedicineEntry.className = 'medicine-entry';

    // Create new medicine dropdown
    const medicineLabel = document.createElement('label');
    medicineLabel.textContent = "Medicine:";
    newMedicineEntry.appendChild(medicineLabel);

    const newMedicineDropdown = document.createElement('select');
    newMedicineDropdown.className = 'medicine-dropdown';
    newMedicineDropdown.name = 'editMedicines[]';
    newMedicineDropdown.required = true;
    newMedicineEntry.appendChild(newMedicineDropdown);

    // Create new amount input
    const amountLabel = document.createElement('label');
    amountLabel.textContent = "Amount:";
    newMedicineEntry.appendChild(amountLabel);

    const newAmountInput = document.createElement('input');
    newAmountInput.type = 'number';
    newAmountInput.name = 'editAmount[]';
    newAmountInput.required = true;
    newAmountInput.value = medAmount; // Set the default amount value
    newMedicineEntry.appendChild(newAmountInput);

    // Create delete button
    const deleteButton = document.createElement('button');
    deleteButton.type = 'button';
    deleteButton.textContent = 'Delete';
    deleteButton.className = 'delete-btn';
    deleteButton.onclick = function() {
        editContainer.removeChild(newMedicineEntry); // Remove this medicine entry
    };
    newMedicineEntry.appendChild(deleteButton);

    // Append the new entry to the medicine container
    editContainer.appendChild(newMedicineEntry);

    // Populate the dropdown and pre-select the correct medicine
    if (medicineOptions.length > 0) {
        populateMedicineDropdownForEntry(newMedicineDropdown, medName); // Pass the medName to pre-select
    } else {
        // Fetch medicine options if not already cached
        fetchMedicineOptions().then(() => {
            populateMedicineDropdownForEntry(newMedicineDropdown, medName); // Pass the medName to pre-select
        });
    }
}



// Close Edit Medication Modal
function closeEditMedicationModal() {
    document.getElementById('editMedicationModal').style.display = 'none';
}



// Submit Edit Medication Form
function submitEditMedicationForm(event) {
    event.preventDefault();
    const form = document.getElementById('editMedicationForm');
    const formData = new FormData(form);

    fetch('P_MEDICATION/edit_pmedication.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())  // Get response as text
    .then(text => {
        try {
            const result = JSON.parse(text);  // Parse the text as JSON

            if (result.success) {
                console.log("Edit result: ", result);
                closeEditMedicationModal();
                updateMedicationTable(result.data);
            } else {
                alert('Error editing medication: ' + result.error);
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
        }
    })
    .catch(error => console.error('Error:', error));
}


// Delete Medication
function deleteMedication(medicationId) {
    if (confirm('Are you sure you want to delete this medication?')) {
        fetch('delete_medication.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ id: medicationId })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                updateMedicationTable(result.data); // Refresh table with the updated data
            } else {
                alert('Error deleting medication: ' + result.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}




































    // Function to set and activate the desired section based on navigation clicks
    function setActiveSection(sectionId) {
    window.location.hash = sectionId;  
    toggleSection(sectionId); 
    }
    // Function to toggle visibility of sections
    function toggleSection(sectionId) {
        var sections = document.querySelectorAll('.section');
        sections.forEach(function(section) {
            if (section.id === sectionId) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });
    }
//logout
    document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("logoutBtn").addEventListener("click", function() {
        window.location.href = "logout.php";
    });
});
    // When the page loads, show the appropriate section based on the URL hash
    window.onload = function() {
        var hash = window.location.hash.substring(1); 
        if (hash) {
            toggleSection(hash);
        } else {
             
        }
    };

</script>
<footer>
    <p>&copy; 2024 BRGY STA. MARIA HEALTH CENTER. All rights reserved.</p>
</footer>

</body>
</html>