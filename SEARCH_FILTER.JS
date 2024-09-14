
// Function to search MEDICAL SUPPLIES
function searchTable1(inputValue) {
    var searchQuery = inputValue.toLowerCase().trim();
    var table = document.getElementById('medicalSuppliesTable');
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

// Function to search MEDICINE INVENTORY
function searchTable2(inputValue) {
    var searchQuery = inputValue.toLowerCase().trim();
    var table = document.getElementById('medTable'); 
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

function searchTable3(inputValue) {
    var searchQuery = inputValue.toLowerCase().trim();
    var table = document.getElementById('patientTable'); 
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

function searchTable4(inputValue) {
    var searchQuery = inputValue.toLowerCase().trim();
    var table = document.getElementById('appointmentsTable'); 
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


//filter using Dropdown
function filterByCategory(selectedCategory) {
    const table = document.getElementById('patientTable'); 
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) { 
        const categoryCell = rows[i].getElementsByTagName('td')[5]; 

        if (categoryCell) {
            const category = categoryCell.textContent || categoryCell.innerText;

            if (selectedCategory === "" || category.toLowerCase() === selectedCategory.toLowerCase()) {
                rows[i].style.display = ""; 
            } else {
                rows[i].style.display = "none"; 
            }
        }
    }
}