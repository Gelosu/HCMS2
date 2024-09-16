<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="mamamoadmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-DlSg7oCoIEZd3TpUB8tgL5irGlVg8H1Yqf+i6BR6DNOxj8GkoL9Ji/ZgQwsyu8ksk7Qf5owSJ3dZh8tCx3oA8A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
    
    </head>
<body>
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
    <section id="user" class="section">
            <?php include 'ADMIN/userlist.php'; ?>
        </section>


<section id="events" class="section" style="display: none;">
            <h2>Schedule of Events</h2>
            <input type="text" id="searchBar" placeholder="Search events...">
            <button onclick="openAddEventModal()">Add Event</button>
            
            <!-- Add Event Modal -->
            <div id="addEventModal" style="display: none;">
                <h3>Add Event</h3>
                <form id="addEventForm" onsubmit="submitAddEventForm(event)">
                    <label for="eventName">Name of Event:</label>
                    <input type="text" id="eventName" name="eventName" required>
                    <label for="eventDescription">Description of Event:</label>
                    <textarea id="eventDescription" name="eventDescription" required></textarea>
                    <label for="eventDateTime">Date and Time:</label>
                    <input type="datetime-local" id="eventDateTime" name="eventDateTime" required>
                    <button type="submit">Submit</button>
                    <button type="button" onclick="closeAddEventModal()">Cancel</button>
                </form>
            </div>

            <!-- Events Table -->
            <table>
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Description</th>
                        <th>Scheduled Date and Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be added here dynamically -->
                </tbody>
            </table>
        </div>
    </div>
 
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
</html>