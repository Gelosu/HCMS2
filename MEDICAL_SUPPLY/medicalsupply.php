


<!-- MEDICAL SUPPLY INVENTORY -->
<section id="medical_supplies-inventory" class="section">
    <h2>Medical & Emergency Supplies Inventory</h2>
    <div class="search-and-add-container">
        <!-- Search bar container -->
        <div class="search-container">
        <input type="text" id="searchInput" onkeyup="searchTable1(this.value);" placeholder="Search for medical supplies...">
        </div>

        <!-- Button container -->
        <div class="add-button-container">
            <button onclick="openAddMedicalSupplyModal()">Add Medical Supply</button>
        </div>
    </div>

<!-- MEDICAL SUPPLY TABLE -->
<table id="medicalSuppliesTable">
    <thead>
        <tr>
            
            <th>Supply Name</th>
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

    
    $sql = "SELECT * FROM inv_medsup";
    $result = $conn->query($sql);

    
    if ($result->num_rows > 0) {
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["prod_name"] . "</td>"; 
            echo "<td>" . $row["stck_in"] . "</td>"; 
            echo "<td>" . $row["stck_out"] . "</td>"; 
            echo "<td>" . $row["stck_expired"] . "</td>"; 
            echo "<td>" . $row["stck_avl"] . "</td>"; 
            echo "<td class='action-icons'>";
            echo "<button onclick=\"openEditMedSupp('" . 
            $row["med_supId"] . "', '" . 
            $row["prod_name"] . "', '" . 
            $row["stck_in"] . "', '" . 
            $row["stck_out"] . "', '" . 
            $row["stck_expired"] . "', '" . 
            $row["stck_avl"] . "')\">";
       echo "<img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'></button>";
       
            echo "<button onclick=\"deleteMedicalSupply('" . $row["med_supId"] . "')\">";
            echo "<img src='delete_icon.png' alt='Delete' class='delete-btn' style='width: 20px; height: 20px;'></button>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No medical supplies found</td></tr>";
    }

    $conn->close();
    ?>
</tbody>

</table>
</section>
<!-- Modal for adding new medical supply -->
<div id="addMedicalSupplyModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddMedicalSupplyModal()">&times;</span>
        <h3>Add New Medical & Emergency Supply</h3>
        <form id="addMedicalSupplyForm" onsubmit="submitMedicalSupplyForm(event)">
            <label for="supplyName">Supply Name:</label>
            <input type="text" id="supplyName" name="supplyName" required><br><br>
            
            <label for="stockIn">Stock In:</label>
            <input type="number" id="stockIn" name="stockIn" required><br><br>
            
            <label for="stockOut">Stock Out:</label>
            <input type="number" id="stockOut" name="stockOut" required><br><br>
            
            <label for="stockExpired">Stock Expired:</label>
            <input type="number" id="stockExpired" name="stockExpired" required><br><br>
            
            <label for="stockAvailable">Stock Available:</label>
            <input type="number" id="stockAvailable" name="stockAvailable" required><br><br>
            
            <input type="submit" value="Submit">
        </form>
    </div>
</div>

<!-- Modal for editing medical supplies -->
<div id="editMedicalSupplyModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h3>Edit Medical/Emergency Supply</h3>
        <form id="editForm" onsubmit="submitEditMedicalSupplyForm(event)">
            <input type="hidden" id="editSuppId" name="supplyId">
            
            <label for="editSupplyName">Supply Name:</label>
            <input type="text" id="editSupplyName" name="supplyName" required><br><br>
            
            <label for="editStockIn">Stock In:</label>
            <input type="number" id="editStockIn2" name="stockIn2" required><br><br>
            
            <label for="editStockOut">Stock Out:</label>
            <input type="number" id="editStockOut2" name="stockOut2" required><br><br>
            
            <label for="editStockExp">Expired:</label>
            <input type="number" id="editStockExp2" name="stockExpired2" required><br><br>
            
            <label for="editStockAvail">Stock Available:</label>
            <input type="number" id="editStockAvail2" name="stockAvailable2" required><br><br>
            
            <input type="submit" value="Submit">
        </form>
    </div>
</div>

