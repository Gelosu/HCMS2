var addMedicalSupplyModal = document.getElementById("addMedicalSupplyModal"); //ADD MEDICAL SUPPLY 
    var editMedicalSupplyModal =document.getElementById("editMedicalSupplyModal") //EDIT MS

//MEDICAL SUPPLY
    // FUNCTION FOR ADDING MEDICAL SUPPLY 
    function submitMedicalSupplyForm(event) {
        event.preventDefault(); 
    
        var formData = new FormData(document.getElementById('addMedicalSupplyForm'));
    
        fetch('MEDICAL_SUPPLY/add_medical_supply.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) 
        .then(data => {
            console.log('Success:', data);
            
            if (data.error) {
                alert('Error: ' + data.error);
            } else {
                console.log("data: ", data)
                updateMedicalSupplyTable(data.data); 
                closeAddMedicalSupplyModal(); 
            }
        })
        .catch(error => console.error('Error submitting form:', error));
    }
    
    function updateMedicalSupplyTable(data) {
        var tableBody = document.querySelector('#medicalSuppliesTable tbody');
        tableBody.innerHTML = ''; 
    
        if (Array.isArray(data) && data.length > 0) {
            data.forEach(supply => {
                var row = document.createElement('tr');
                row.innerHTML = `
                    <td>${supply.prod_name}</td>
                    <td>${supply.stck_in}</td>
                    <td>${supply.stck_out}</td>
                    <td>${supply.stck_expired}</td>
                    <td>${supply.stck_avl}</td>
                    <td class='action-icons'>
                        <a href='#' class='edit-btn' onclick='openEditMedSupp(${supply.med_supId}, "${escapeHtml(supply.prod_name)}", ${supply.stck_in}, ${supply.stck_out}, ${supply.stck_expired}, ${supply.stck_avl})'>
                            <img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'>
                        </a>
                        <a href='#' class='delete-btn' onclick='deleteMedicalSupply(${supply.med_supId})'>
                            <img src='delete_icon.png' alt='Delete' style='width: 20px; height: 20px;'>
                        </a>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="6">No medical supplies found</td></tr>';
        }
    }
    
    // Function to escape special characters for HTML
    function escapeHtml(text) {
        if (text == null) return ''; // Handle null or undefined values
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
        // Function to close the add medical supply modal
        function closeAddMedicalSupplyModal() {
            if (addMedicalSupplyModal) {
                addMedicalSupplyModal.style.display = 'none';
            }
        }
    
        // Function to open the add medical supply modal
        function openAddMedicalSupplyModal() {
            if (addMedicalSupplyModal) {
                addMedicalSupplyModal.style.display = 'block';
            }
        }
    
    
        // Function to edit MS
        function openEditMedSupp(supplyId, supplyName, stockIn2, stockOut2, stockExpired2, stockAvailable2) {
        document.getElementById('editSuppId').value = supplyId;
        document.getElementById('editSupplyName').value = supplyName;
        document.getElementById('editStockIn2').value = stockIn2;
        document.getElementById('editStockOut2').value = stockOut2;
        document.getElementById('editStockExp2').value = stockExpired2;
        document.getElementById('editStockAvail2').value = stockAvailable2;
    
        document.getElementById('editMedicalSupplyModal').style.display = 'block';
    }
    
    function closeEditModal() {
        var modal = document.getElementById("editMedicalSupplyModal");
        if (modal) {
            modal.style.display = 'none';
        }
    }
    
    function submitEditMedicalSupplyForm(event) {
        event.preventDefault(); 
    
        var formData = new FormData(document.getElementById('editForm'));
    
        fetch('MEDICAL_SUPPLY/update_supply.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) 
        .then(data => {
            console.log('Success:', data.data);
            
            if (data.error) {
                alert('Error: ' + data.error);
            } else {
                
                updateMedicalSupplyTable(data.data); 
                closeEditModal(); 
            }
        })
        .catch(error => console.error('Error submitting form:', error));
    }
    
    
    document.getElementById('editForm').addEventListener('submit', submitEditMedicalSupplyForm);
    
    
    
    // Function to handle delete MS
    function deleteMedicalSupply(medSupId) {
        if (confirm('Are you sure you want to delete this supply?')) {
            fetch('MEDICAL_SUPPLY/delete_supply.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    medSupId: medSupId
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok.');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    updateMedicalSupplyTable(data.supplies);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
    
    