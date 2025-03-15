// Function to preview the selected image
function previewImage(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader()
  
      reader.onload = (e) => {
        document.getElementById("productImagePreview").src = e.target.result
      }
  
      reader.readAsDataURL(input.files[0])
    }
  }
  
  // Form validation
  document.addEventListener("DOMContentLoaded", () => {
    const editProductForm = document.getElementById("editProductForm")
  
    if (editProductForm) {
      editProductForm.addEventListener("submit", (event) => {
        const productName = document.getElementById("productName").value
        const productSKU = document.getElementById("productSKU").value
        const productQuantity = document.getElementById("productQuantity").value
  
        let isValid = true
        let errorMessage = ""
  
        // Validate product name
        if (productName.trim() === "") {
          isValid = false
          errorMessage += "Product name is required.\n"
        }
  
        // Validate SKU
        if (productSKU.trim() === "") {
          isValid = false
          errorMessage += "SKU is required.\n"
        }
  
        // Validate quantity
        if (productQuantity === "" || isNaN(productQuantity) || Number.parseInt(productQuantity) < 0) {
          isValid = false
          errorMessage += "Quantity must be a valid number greater than or equal to 0.\n"
        }
  
        if (!isValid) {
          event.preventDefault()
          alert("Please fix the following errors:\n" + errorMessage)
        }
      })
    }
  
    // Search functionality
    const searchInput = document.getElementById("searchInput")
    if (searchInput) {
      searchInput.addEventListener("keyup", function () {
        const searchTerm = this.value.toLowerCase()
        const tableRows = document.querySelectorAll("tbody tr")
  
        tableRows.forEach((row) => {
          const productName = row.cells[1].textContent.toLowerCase()
          const productSKU = row.cells[2].textContent.toLowerCase()
  
          if (productName.includes(searchTerm) || productSKU.includes(searchTerm)) {
            row.style.display = ""
          } else {
            row.style.display = "none"
          }
        })
      })
    }
  
    // Category filter
    const categoryFilter = document.getElementById("categoryFilter")
    if (categoryFilter) {
      categoryFilter.addEventListener("change", function () {
        const selectedCategory = this.value.toLowerCase()
        const tableRows = document.querySelectorAll("tbody tr")
  
        tableRows.forEach((row) => {
          const category = row.cells[3].textContent.toLowerCase()
  
          if (selectedCategory === "" || category === selectedCategory) {
            row.style.display = ""
          } else {
            row.style.display = "none"
          }
        })
      })
    }
  
    // Refresh button
    const refreshBtn = document.getElementById("refreshBtn")
    if (refreshBtn) {
      refreshBtn.addEventListener("click", () => {
        location.reload()
      })
    }
  })
  
  