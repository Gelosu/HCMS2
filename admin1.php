<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="mamamoadmin.css">
    <link rel="stylesheet" href="style.css">
    
    </head>
<div>
    <header>
        <h1>BRGY STA. MARIA HEALTH CENTER</h1>
    </header>

    <div id="sidebar">
        <div id="logo">
            <img src="mary.jpg" alt="Logo">
        </div>
        <ul>
            <li><a href="#" id="userLink" onclick="setActiveSection('user')">Users</a></li>
            <li><a href="#" id="events" onclick="setActiveSection('events')">Schedule of Events</a></li>
        </ul>
        <button id="logoutBtn" onclick="logout()">Logout</button>
    </div>

    <div id="main-content">
            <?php include 'ADMIN/userlist.php'; ?>
            <?php include 'ADMIN/eventlist.php'; ?>

</div>

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


//EVENTS MANAGEMENET

function searchTable7(inputValue) {
    var searchQuery = inputValue.toLowerCase().trim();
    var table = document.getElementById("eventsTable"); 
    var rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var cell = row.getElementsByTagName('td')[0]; 

        if (cell) {
            var cellValue = cell.textContent || cell.innerText; 
           
            if (cellValue.toLowerCase().indexOf(searchQuery) > -1) {
                row.style.display = ''; 
            } else {
                row.style.display = 'none'; 
            }
        }
    }
}



let selectedMonth = "";
let selectedYear = "";

// Fetch available years when the document is loaded
document.addEventListener('DOMContentLoaded', function() {
    fetchYears();
});

// Fetch unique years from the database
function fetchYears() {
    fetch('ADMIN/fetch_years.php') // Adjust the path if necessary
        .then(response => response.json())
        .then(years => {
            const yearDropdown = document.getElementById('yearDropdown');
            yearDropdown.innerHTML = '<option value="">Select Year</option>'; // Clear any previous options
            years.forEach(year => {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearDropdown.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching years:', error));
}

// Triggered when a year is selected
function filterByYear(year) {
    selectedYear = year;  // Store the selected year
    filterEvents();  // Apply combined filters (year and month)
}

// Triggered when a month is selected
function filterByCategory2(month) {
    selectedMonth = month;  // Store the selected month
    filterEvents();  // Apply combined filters (year and month)
}

// Function to filter events based on the selected year and month
function filterEvents() {
    const table = document.getElementById('eventsTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) { 
        const dateCell = rows[i].getElementsByTagName('td')[2]; // Adjust index if needed

        if (dateCell) {
            const dateText = dateCell.textContent || dateCell.innerText;
            console.log("Date Cell Text:", dateText); // Debugging line

            // Extract year and month from the formatted date string
            const yearFromCell = extractYearFromDate(dateText);
            const monthFromCell = extractMonthFromDate(dateText);

            // Check if the row matches the selected year and month
            const matchesYear = (selectedYear === "" || yearFromCell === selectedYear);
            const matchesMonth = (selectedMonth === "" || monthFromCell.toLowerCase() === selectedMonth.toLowerCase());

            // Show the row if both year and month match (or if they are not selected)
            if (matchesYear && matchesMonth) {
                rows[i].style.display = ""; 
            } else {
                rows[i].style.display = "none"; 
            }
        }
    }
}

// Extract the year from the formatted date
function extractYearFromDate(dateText) {
    const match = dateText.match(/\d{4}/); // Matches a four-digit year
    return match ? match[0] : ''; // Return the matched year or empty string if no match
}

// Extract the month from the formatted date (assumes month is the first word)
function extractMonthFromDate(dateText) {
    const match = dateText.match(/\b\w+\b/); // Matches the first word (month)
    return match ? match[0] : ''; // Return the matched month or empty string if no match
}




function openAddEventModal() {
    document.getElementById('addEventModal').style.display = 'block';
}

function closeAddEventModal() {
    document.getElementById('addEventModal').style.display = 'none';
}

// ADD EVENT
function submitAddEventForm(event) {
    event.preventDefault();

    var formData = new FormData(document.getElementById('addEventForm'));

    fetch('ADMIN/add_event.php', { // Adjust the path as necessary
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Parse JSON response
    .then(result => {
        if (result.success) {
            console.log("Event data:", result.events);
            console.log("Years data:", result.years);
            closeAddEventModal(); // Close the modal on success
            updateEventTable(result.events); // Update the table with new data
            updateYearDropdown(result.years); // Update the year dropdown
        } else {
            alert('Error: ' + result.error);
        }
    })
    .catch(error => console.error('Error:', error));
}
// Function to format date and time
function formatDateTime(datetime) {
    const date = new Date(datetime);
    // Format date to "Month Day, Year Time AM/PM"
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true
    };
    return date.toLocaleString('en-US', options);
}

// UPDATE EVENT TABLE
function updateEventTable(events) {
    var tableBody = document.querySelector('#events table tbody');
    tableBody.innerHTML = ''; // Clear existing rows

    events.forEach(event => {
        const formattedDateTime = formatDateTime(event.datetime);
        var row = document.createElement('tr');
        row.innerHTML = `
            <td>${event.event_name}</td>
            <td>${event.event_description}</td>
            <td>${formattedDateTime}</td>
            <td>
                <a href='#' class='edit-btn' onclick="openEditEventModal(
                    '${event.id}',
                    '${event.event_name}',
                    '${event.event_description}',
                    '${event.datetime}'
                )">
                    <img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'>
                </a>
                <a href='#' class='delete-btn' onclick="deleteEvent('${event.id}')">
                    <img src='delete_icon.png' alt='Delete' style='width: 20px; height: 20px;'>
                </a>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

// UPDATE YEAR DROPDOWN
function updateYearDropdown(years) {
    const yearDropdown = document.getElementById('yearDropdown');
    yearDropdown.innerHTML = '<option value="">Select Year</option>'; // Clear existing options

    years.forEach(year => {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearDropdown.appendChild(option);
    });
}



//EDIT TABLE
function openEditEventModal(eventId, eventName, eventDescription, eventDateTime) {
    // Set the form values
    document.getElementById('editEventId').value = eventId;
    document.getElementById('editEventName').value = eventName;
    document.getElementById('editEventDescription').value = eventDescription;
    document.getElementById('editEventDateTime').value = eventDateTime;

    // Show the modal
    document.getElementById('editEventModal').style.display = 'block';
}

function closeEditEventModal() {
    document.getElementById('editEventModal').style.display = 'none';
}

function submitEditEventForm(event) {
    event.preventDefault();

    var formData = new FormData(document.getElementById('editEventForm'));

    fetch('ADMIN/edit_event.php', { // Adjust the path as necessary
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            closeEditEventModal(); // Close the modal on success
            updateEventTable(result.events); // Update the table with new data
        } else {
            alert('Error: ' + result.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteEvent(eventId) {
    console.log("Event ID to delete:", eventId);

    // Check if eventId is valid
    if (!eventId) {
        console.error('Invalid Event ID:', eventId);
        alert('Error: Event ID is missing.');
        return;
    }

    if (confirm('Are you sure you want to delete this event?')) {
        fetch('ADMIN/delete_event.php', { // Adjust the path if necessary
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: eventId })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Event deleted successfully.');
                updateEventTable(result.events); // Use the updated list of events
            } else {
                console.error('Error deleting event:', result.error);
                alert('Error deleting event: ' + result.error + '. Event ID: ' + result.received_id);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error processing the request.');
        });
    }
}









//USER MANAGEMENT
function openAddUserModal() {
    document.getElementById('addUserModal').style.display = 'block';
}

function closeAddUserModal() {
    document.getElementById('addUserModal').style.display = 'none';
}


function openEditUserModal(id, adname, adsurname, adusername, adpass, adposition) {
  
    document.getElementById('editUserId').value = id;
    document.getElementById('editUserAdname').value = adname;
    document.getElementById('editUserAdsurname').value = adsurname;
    document.getElementById('editUserAdusername').value = adusername;
    document.getElementById('editUserAdpass').value = adpass;
    document.getElementById('editUserAdposition').value = adposition;

    // Show the modal
    document.getElementById('editUserModal').style.display = 'block';
}

    function closeEditUserModal() {
            document.getElementById('editUserModal').style.display = 'none';
        }

     

//ADD USER
    function submitForm(event) {
    event.preventDefault();

    var formData = new FormData(document.getElementById('addUserForm'));

    fetch('ADMIN/add_user.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            
            closeAddUserModal();
            updateUserTable(result.users); 
        } else {
            alert('Error: ' + result.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

//UPDATE USER
        function submitEditForm(event) {
            event.preventDefault();

            var formData = new FormData(document.getElementById('editUserForm'));

            fetch('ADMIN/edit_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {

                    closeEditUserModal();
                    updateUserTable(result.users);
                } else {
                    alert(result.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }

    
    //RELOAD TABLE
    function updateUserTable(users) {
    var tableBody = document.querySelector('table tbody');
    tableBody.innerHTML = ''; // Clear existing rows

    users.forEach(user => {
        var row = document.createElement('tr');
        row.innerHTML = `
            <td>${user.adname}</td>
            <td>${user.adsurname}</td>
            <td>${user.adusername}</td>
            <td>${user.adpass}</td>
            <td>${user.adposition}</td>
            <td>
                <a href='#' class='edit-btn' onclick="openEditUserModal(
                    '${user.adid}',
                    '${user.adname}',
                    '${user.adsurname}',
                    '${user.adusername}',
                    '${user.adpass}',
                    '${user.adposition}'
                )">
                    <img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'>
                </a>
                <a href='#' class='delete-btn' onclick="deleteUser('${user.adid}')">
                    <img src='delete_icon.png' alt='Delete' style='width: 20px; height: 20px;'>
                </a>
            </td>
        `;
        tableBody.appendChild(row);
    });
}


//DELETE USER
function deleteUser(adid) {
    if (confirm('Are you sure you want to delete this user?')) {
        fetch('ADMIN/delete_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: adid })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('User deleted successfully.');
                updateUserTable(result.users);  // Use the updated list of users
            } else {
                alert('Error deleting user: ' + result.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error processing the request.');
        });
    }
}




        function logout() {
            window.location.href = '/HCMS';
        }
    </script>
</body>
<footer>
    <p>&copy; 2024 BRGY STA. MARIA HEALTH CENTER. All rights reserved.</p>
</footer>
</html>