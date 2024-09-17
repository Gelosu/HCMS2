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

// Function to add a new medicine input field dynamically in the Add Medication Modal
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
    newAmountInput.className = 'medicine-amount';
    newAmountInput.required = true;
    newMedicineEntry.appendChild(newAmountInput);

    // Create delete button
    const deleteButton = document.createElement('button');
    deleteButton.type = 'button';
    deleteButton.textContent = 'Delete';
    deleteButton.className = 'delete-btn';
    deleteButton.onclick = function() {
        container.removeChild(newMedicineEntry); // Remove this medicine entry
    };
    newMedicineEntry.appendChild(deleteButton);

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

                // Escape function parameters for safe usage in HTML attributes
                const escapeHtml = (str) => {
                    return str
                        .replace(/&/g, "&amp;")
                        .replace(/</g, "&lt;")
                        .replace(/>/g, "&gt;")
                        .replace(/"/g, "&quot;")
                        .replace(/'/g, "&#039;");
                };

                // Create and append row
                row.innerHTML = `
                    <td>${escapeHtml(medication.patient_name)}</td>
                    <td>${medicinesList}</td>
                    <td>${formatDateTime(medication.date_time)}</td>
                    <td>${escapeHtml(medication.healthworker)}</td>
                    <td>
                        <button onclick="openEditMedicationModal('${escapeHtml(medication.id)}', '${escapeHtml(medication.patient_name)}', '${escapeHtml(JSON.stringify(medication.p_medication))}', '${escapeHtml(medication.date_time)}', '${escapeHtml(medication.healthworker)}')">
                            <img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'>
                        </button>
                        <button onclick="deleteMedication('${escapeHtml(medication.id)}')">
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
    document.getElementById('editMedicationDateTime').value = dateTime;
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
// Function to add a new medicine input field dynamically in the Edit Medication Modal
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
    newAmountInput.className = 'medicine-amount';
    newAmountInput.required = true;
    newAmountInput.value = medAmount; // Set the default amount value
    newMedicineEntry.appendChild(newAmountInput);

    // Create delete button
    const deleteButton = document.createElement('button');
    deleteButton.type = 'button';
    deleteButton.textContent = 'Delete';
    deleteButton.className = 'delete-btn';
    deleteButton.onclick = function() {
        removeMedicineField(deleteButton); // Remove this medicine entry
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

// Function to remove a medicine field
function removeMedicineField(button) {
    const editContainer = document.getElementById('editMedicineContainer');
    editContainer.removeChild(button.parentNode); // Remove the medicine entry
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


function deleteMedication(medicationId) {
    if (confirm('Are you sure you want to delete this medication?')) {
        fetch('P_MEDICATION/delete_pmedication.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ medId: medicationId })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                console.log("resultsss: ", result.data)
                updateMedicationTable(result.data); // Refresh table with the updated data
            } else {
                alert('Error deleting medication: ' + result.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}



