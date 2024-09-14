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
            <input type="text" id="medicationSearchBar" placeholder="Search medications...">
            <button onclick="openAddMedicationModal()">Add Patient Medication</button>

            <!-- Add Patient Medication Modal -->
            <div id="addMedicationModal" style="display: none;">
                <h3>Add Patient Medication</h3>
                <form id="addMedicationForm" onsubmit="submitAddMedicationForm(event)">
                    <label for="medicationPatientName">Name of Patient:</label>
                    <select id="medicationPatientName" name="medicationPatientName" required>
                        <!-- Options will be populated dynamically -->
                    </select>
                    <label for="medicines">Medicine:</label>
                    <select id="medicines" name="medicines" multiple required>
                        <!-- Options will be populated dynamically -->
                    </select>
                    <label for="amount">Amount:</label>
                    <input type="number" id="amount" name="amount" required>
                    <label for="medicationDateTime">Date and Time:</label>
                    <input type="datetime-local" id="medicationDateTime" name="medicationDateTime" required>
                    <label for="medicationHealthWorker">Assigned Healthworker:</label>
                    <input type="text" id="medicationHealthWorker" name="medicationHealthWorker" required>
                    <button type="submit">Submit</button>
                    <button type="button" onclick="closeAddMedicationModal()">Cancel</button>
                </form>
            </div>

            <!-- Medications Table -->
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Medicines with Amount</th>
                        <th>Date Time</th>
                        <th>Assigned Healthworker</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be added here dynamically -->
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