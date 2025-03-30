// Function to apply search filter
function searchTable() {
    let searchInput = document.getElementById("searchInput").value.toLowerCase();
    let table = document.getElementById("user");
    let rows = table.getElementsByTagName("tr");
    let noResultsMessage = document.getElementById("noResultsMessage");
    let found = false;

    for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
        // Skip the "No records found" row if it exists
        if (rows[i].cells && rows[i].cells.length === 1 && rows[i].cells[0].id === "noResultsMessage") {
            continue;
        }
        
        let cells = rows[i].getElementsByTagName("td");
        if (cells.length > 0) {
            // Adjust indexes based on your table structure
            // Your table has: checkbox, name, email, role, action
            let name = cells[1].textContent.toLowerCase();
            let email = cells[2].textContent.toLowerCase();
            let role = cells[3].textContent.toLowerCase();

            if (name.includes(searchInput) || email.includes(searchInput) || role.includes(searchInput)) {
                rows[i].style.display = "";
                found = true;
            } else {
                rows[i].style.display = "none";
            }
        }
    }

    // Show 'No records found' message if no matching results
    if (!found) {
        // Find the no results message row or create it if it doesn't exist
        let noResultsRow = document.getElementById("noResultsMessage");
        if (!noResultsRow) {
            noResultsRow = document.createElement("tr");
            let cell = document.createElement("td");
            cell.id = "noResultsMessage";
            cell.colSpan = 5; // Span all columns
            cell.textContent = "No records found";
            cell.style.textAlign = "center";
            noResultsRow.appendChild(cell);
            table.getElementsByTagName("tbody")[0].appendChild(noResultsRow);
        }
        noResultsRow.style.display = "table-row";
    } else {
        let noResultsRow = document.getElementById("noResultsMessage");
        if (noResultsRow) {
            noResultsRow.style.display = "none";
        }
    }
}

// Function to handle saving a new user
function saveUser() {
    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const role = document.getElementById("role").value;

    // Add logic here to save the new user (e.g., send data to the server)
    console.log("New User:", { name, email, role });

    // After saving, close the modal
    $('#addUserModal').modal('hide');
    
    // Optional: Refresh the table or add the new user to the table
    // This would depend on your backend implementation
}

// Add event listener to the Save button in the modal
document.addEventListener('DOMContentLoaded', function() {
    const saveButton = document.querySelector('.modal-footer .btn-primary');
    if (saveButton) {
        saveButton.addEventListener('click', saveUser);
    }
});
// Add this to your existing JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Get all dropdown items
    const dropdownItems = document.querySelectorAll('.dropdown-menu .dropdown-item');
    
    // Add click event listeners to each dropdown item
    dropdownItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent the default link behavior
            
            // Extract the role from the href attribute
            const url = new URL(this.href);
            const role = url.searchParams.get('role');
            
            // Update the dropdown button text
            const dropdownButton = document.querySelector('.btn-group #btnGroupDrop1');
            dropdownButton.textContent = 'Role: ' + (role === 'all' ? 'All' : role);
            
            // Apply the filter
            filterByRole(role.toLowerCase());
        });
    });
});


// Function to filter by role (client-side)
function filterByRole(role) {
    let table = document.getElementById("user");
    let rows = table.getElementsByTagName("tr");
    let noResultsMessage = document.getElementById("noResultsMessage");
    let found = false;

    for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
        // Skip the "No records found" row
        if (rows[i].cells && rows[i].cells.length === 1 && rows[i].cells[0].id === "noResultsMessage") {
            continue;
        }
        
        let cells = rows[i].getElementsByTagName("td");
        if (cells.length > 0) {
            let userRole = cells[3].textContent.toLowerCase();
            
            if (role === 'all' || userRole === role.toLowerCase()) {
                rows[i].style.display = "";
                found = true;
            } else {
                rows[i].style.display = "none";
            }
        }
    }

    // Show 'No records found' message if no matching results
    if (!found) {
        // Find or create the no results message
        let noResultsRow = document.getElementById("noResultsMessage");
        if (!noResultsRow) {
            noResultsRow = document.createElement("tr");
            let cell = document.createElement("td");
            cell.id = "noResultsMessage";
            cell.colSpan = 5; // Span all columns
            cell.textContent = "No records found";
            cell.style.textAlign = "center";
            noResultsRow.appendChild(cell);
            table.getElementsByTagName("tbody")[0].appendChild(noResultsRow);
        }
        noResultsRow.style.display = "table-row";
    } else {
        let noResultsRow = document.getElementById("noResultsMessage");
        if (noResultsRow) {
            noResultsRow.style.display = "none";
        }
    }
}// Add this to your existing JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Get all dropdown items
    const dropdownItems = document.querySelectorAll('.dropdown-menu .dropdown-item');
    
    // Add click event listeners to each dropdown item
    dropdownItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent the default link behavior
            
            // Extract the role from the href attribute
            const url = new URL(this.href);
            const role = url.searchParams.get('role');
            
            // Update the dropdown button text
            const dropdownButton = document.querySelector('.btn-group #btnGroupDrop1');
            dropdownButton.textContent = 'Role: ' + (role === 'all' ? 'All' : role);
            
            // Apply the filter
            filterByRole(role.toLowerCase());
        });
    });
});

// Function to filter by role (client-side)
function filterByRole(role) {
    let table = document.getElementById("user");
    let rows = table.getElementsByTagName("tr");
    let noResultsMessage = document.getElementById("noResultsMessage");
    let found = false;

    for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
        // Skip the "No records found" row
        if (rows[i].cells && rows[i].cells.length === 1 && rows[i].cells[0].id === "noResultsMessage") {
            continue;
        }
        
        let cells = rows[i].getElementsByTagName("td");
        if (cells.length > 0) {
            let userRole = cells[3].textContent.toLowerCase();
            
            if (role === 'all' || userRole === role.toLowerCase()) {
                rows[i].style.display = "";
                found = true;
            } else {
                rows[i].style.display = "none";
            }
        }
    }


    // Show 'No records found' message if no matching results
    if (!found) {
        // Find or create the no results message
        let noResultsRow = document.getElementById("noResultsMessage");
        if (!noResultsRow) {
            noResultsRow = document.createElement("tr");
            let cell = document.createElement("td");
            cell.id = "noResultsMessage";
            cell.colSpan = 5; // Span all columns
            cell.textContent = "No records found";
            cell.style.textAlign = "center";
            noResultsRow.appendChild(cell);
            table.getElementsByTagName("tbody")[0].appendChild(noResultsRow);
        }
        noResultsRow.style.display = "table-row";
    } else {
        let noResultsRow = document.getElementById("noResultsMessage");
        if (noResultsRow) {
            noResultsRow.style.display = "none";
        }
    }
}
