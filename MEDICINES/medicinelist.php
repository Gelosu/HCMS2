
<!-- MEDICINE INVETORY DITO --> 

<section id="medicine-inventory" class="section">
        <h2>Medicine Inventory</h2>
        
        <div class="search-and-add-container">
        <!-- Search bar container -->
        <div class="search-container">
        <input type="text" id="searchInput" onkeyup="searchTable2(this.value);" placeholder="Search for medical supplies...">
        </div>

        <!-- Button container -->
        <div class="add-button-container">
            <button onclick="openAddMedicineModal()">Add Medicine</button>
        </div>
    </div>

        <table id="medTable">
            <thead>
                <tr>
                    <th>Medicine Name</th>
                    <th>Description</th>
                    <th>Stock In</th>
                    <th>Stock Out</th>
                    <th>Expired</th>
                    <th>Stock Available</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
include 'connect.php';

$sql = "SELECT * FROM inv_meds";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["meds_name"] . "</td>";
        echo "<td>" . $row["med_dscrptn"] . "</td>";
        echo "<td>" . $row["stock_in"] . "</td>";
        echo "<td>" . $row["stock_out"] . "</td>";
        echo "<td>" . $row["stock_exp"] . "</td>";
        echo "<td>" . $row["stock_avail"] . "</td>";
        echo "<td class='action-icons'>";
        echo "<button onclick=\"openEditMed('" . 
            $row["med_id"] . "', '" . 
            $row["meds_name"] . "', '" . 
            $row["med_dscrptn"] . "', '" . 
            $row["stock_in"] . "', '" . 
            $row["stock_out"] . "', '" . 
            $row["stock_exp"] . "', '" . 
            $row["stock_avail"] . "')\">";
        echo "<img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'></button>";

        echo "<button onclick=\"deleteMedicine('" . $row["med_id"] . "')\">";
        echo "<img src='delete_icon.png' alt='Delete' class='delete-btn' style='width: 20px; height: 20px;'></button>";
        
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No medicines found</td></tr>";
}

$conn->close();
?>

            </tbody>
        </table>
    </section>



       


<!-- Modal for adding new medicine -->
<div id="addMedicineModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddMedicineModal()">&times;</span>
        <h3>Add New Medicine</h3>
        <form id="addmedicine" onsubmit="submitMedicineForm(event)">
            <label for="medName">Medicine Name:</label>
            <input type="text" id="medName" name="medName" required><br><br>
            
            <label for="medDesc">Description:</label>
            <input type="text" id="medDesc" name="medDesc"><br><br>
            
            <label for="stockIn">Stock In:</label>
            <input type="number" id="stockIn" name="stockIn" required><br><br>
            
            <label for="stockOut">Stock Out:</label>
            <input type="number" id="stockOut" name="stockOut" required><br><br>
            
            <label for="stockExp">Expired:</label>
            <input type="number" id="stockExp" name="stockExp" required><br><br>
            
            <label for="stockAvail">Stock Available:</label>
            <input type="number" id="stockAvail" name="stockAvail" required><br><br>
            
            <input type="submit" value="Submit">
        </form>
    </div>
</div>



<!-- Modal for editing medicine -->
<div id="editMedicineModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditMedModal()">&times;</span>
        <h3>Edit Medicine</h3>
        <form id="editForm2" onsubmit="submitEditMedicineForm(event)">
            <input type="hidden" id="editMedId" name="medId">
            
            <label for="editMedName">Medicine Name:</label>
            <input type="text" id="editMedName" name="medName" required><br><br>
            
            <label for="editMedDesc">Description:</label>
            <input type="text" id="editMedDesc" name="medDesc" required><br><br>
            
            <label for="editStockIn">Stock In:</label>
            <input type="number" id="editStockIn" name="stockIn" required><br><br>
            
            <label for="editStockOut">Stock Out:</label>
            <input type="number" id="editStockOut" name="stockOut" required><br><br>
            
            <label for="editStockExp">Expired:</label>
            <input type="number" id="editStockExp" name="stockExp" required><br><br>
            
            <label for="editStockAvail">Stock Available:</label>
            <input type="number" id="editStockAvail" name="stockAvail" required><br><br>
            
            <input type="submit" value="Update">
        </form>
    </div>
</div>

