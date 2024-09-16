<div id="user" class="section">
    <div class="add-button-container">
        <button onclick="openAddUserModal()">Add User</button>
    </div>
               
    <div class="table-container">
    <table class="styled-table">
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Password</th>
            <th>Position</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include 'connect.php';
        $sql = "SELECT adid, adname, adsurname, adusername, adpass, adposition FROM admin";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["adname"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["adsurname"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["adusername"]) . "</td>";
                echo "<td>******</td>";
                echo "<td>" . htmlspecialchars($row["adposition"]) . "</td>";
                echo "<td>";

                // Edit button
                echo "<a href='#' class='edit-btn' onclick=\"openEditUserModal(
                    '" . htmlspecialchars($row['adid']) . "',
                    '" . htmlspecialchars($row['adname']) . "',
                    '" . htmlspecialchars($row['adsurname']) . "',
                    '" . htmlspecialchars($row['adusername']) . "',
                    '" . htmlspecialchars($row['adpass']) . "',
                    '" . htmlspecialchars($row['adposition']) . "'
                )\"> 
                <img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'></a>";

                // Delete button for non-ADMIN positions
                if ($row["adposition"] != "ADMIN") {
                    echo "<a href='#' class='delete-btn' onclick=\"deleteUser('" . htmlspecialchars($row['adid']) . "')\">
                    <img src='delete_icon.png' alt='Delete' style='width: 20px; height: 20px;'></a>";
                }

                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Error: " . htmlspecialchars(mysqli_error($conn)) . "</td></tr>";
        }

        mysqli_close($conn);
        ?>
    </tbody>
</table>

            </div>
            </div>
    </div>

          <!-- Add User Modal -->
<div id="addUserModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddUserModal()">&times;</span>
        <h3>Add New User</h3>
        <form id="addUserForm" onsubmit="submitForm(event)">
            <label for="addUserAdname">First Name:</label>
            <input type="text" id="addUserAdname" name="adname" required><br><br>

            <label for="addUserAdsurname">Last Name:</label>
            <input type="text" id="addUserAdsurname" name="adsurname" required><br><br>

            <label for="addUserAdusername">Username:</label>
            <input type="text" id="addUserAdusername" name="adusername" required><br><br>

            <label for="addUserAdpass">Password:</label>
            <input type="password" id="addUserAdpass" name="adpass" required><br><br>

            <label for="addUserAdposition">Position:</label>
            <input type="text" id="addUserAdposition" name="adposition" value="HEALTHWORKER" readonly><br><br>

            <input type="submit" value="Add User">
        </form>
    </div>
</div>



<!-- Edit User Modal -->
<div id="editUserModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditUserModal()">&times;</span>
        <h3>Edit User</h3>
        <form id="editUserForm" onsubmit="submitEditForm(event)">
            <input type="hidden" id="editUserId" name="adid">
            <label for="editUserAdname">First Name:</label>
            <input type="text" id="editUserAdname" name="adname" required><br><br>

            <label for="editUserAdsurname">Last Name:</label>
            <input type="text" id="editUserAdsurname" name="adsurname" required><br><br>

            <label for="editUserAdusername">Username:</label>
            <input type="text" id="editUserAdusername" name="adusername" required><br><br>

            <label for="editUserAdpass">Password:</label>
            <input type="password" id="editUserAdpass" name="adpass" required><br><br>

            <label for="editUserAdposition">Position:</label>
            <input type="text" id="editUserAdposition" name="adposition" readonly><br><br>

            <input type="submit" value="Save Changes">
        </form>
    </div>
</div>



        </section>