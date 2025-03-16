document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
  
    // Search functionality with researchProduct
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        const query = this.value;
        researchProduct(query);
      });
    } else {
      console.error('Search input not found');
    }
  
    // Dynamic form handling for Update Existing Product modal
    let entryCount = 0;
    const addMoreButton = document.getElementById('add-more');
    if (addMoreButton) {
      addMoreButton.addEventListener('click', function() {
        console.log('Add More (Update) clicked');
        entryCount++;
        const productEntries = document.getElementById('product-entries');
        const newEntry = document.createElement('div');
        newEntry.classList.add('product-entry', 'mb-3');
        newEntry.innerHTML = `
          <div class="mb-3">
            <label for="updateProduct-${entryCount}" class="form-label">Select Product</label>
            <select class="form-control update-product" id="updateProduct-${entryCount}" name="product_id[]" required>
              <option value="">Select a product...</option>
              ${getProductOptions()}
            </select>
          </div>
          <div class="mb-3">
            <label for="updatePrice-${entryCount}" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control update-price" id="updatePrice-${entryCount}" name="price[]" required>
          </div>
          <div class="mb-3">
            <label for="updateQuantity-${entryCount}" class="form-label">Quantity to Add/Subtract</label>
            <input type="number" class="form-control update-quantity" id="updateQuantity-${entryCount}" name="quantity[]" required>
          </div>
          <div class="mb-3">
            <label for="totalPrice-${entryCount}" class="form-label">Total Price</label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input type="text" class="form-control total-price" id="totalPrice-${entryCount}" readonly>
            </div>
          </div>
          <button type="button" class="btn btn-danger remove-entry">Remove</button>
        `;
        productEntries.appendChild(newEntry);
        updateRemoveButtons();
      });
    } else {
      console.error('add-more button not found');
    }
  
    // Dynamic form handling for Add New Product modal
    let addEntryCount = 0;
    const addMoreProductButton = document.getElementById('add-more-product');
    if (addMoreProductButton) {
      addMoreProductButton.addEventListener('click', function() {
        console.log('Add More Product clicked');
        addEntryCount++;
        const addProductEntries = document.getElementById('add-product-entries');
        const newEntry = document.createElement('div');
        newEntry.classList.add('product-entry', 'mb-3');
        newEntry.innerHTML = `
          <h6>Product ${addEntryCount + 1}</h6>
          <div class="mb-3">
            <label for="addName-${addEntryCount}" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="addName-${addEntryCount}" name="name[]" required>
          </div>
          <div class="mb-3">
            <label for="addPrice-${addEntryCount}" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="addPrice-${addEntryCount}" name="price[]" required>
          </div>
          <div class="mb-3">
            <label for="addQuantity-${addEntryCount}" class="form-label">Stock Quantity</label>
            <input type="number" class="form-control" id="addQuantity-${addEntryCount}" name="quantity[]" required>
          </div>
          <div class="mb-3">
            <label for="addImage-${addEntryCount}" class="form-label">Upload Image</label>
            <input type="file" class="form-control custom-file-input" id="addImage-${addEntryCount}" name="image[]" accept="image/jpeg,image/png,image/gif" required>
          </div>
          <button type="button" class="btn btn-danger remove-entry">Remove</button>
        `;
        addProductEntries.appendChild(newEntry);
        updateAddRemoveButtons();
      });
    } else {
      console.error('add-more-product button not found');
    }
  
    // Handle remove buttons
    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('remove-entry')) {
        console.log('Remove button clicked');
        e.target.closest('.product-entry').remove();
        updateRemoveButtons();
        updateAddRemoveButtons();
      }
    });
  
    // Utility functions
    function updateRemoveButtons() {
      const entries = document.querySelectorAll('#product-entries .product-entry');
      entries.forEach((entry, index) => {
        const removeBtn = entry.querySelector('.remove-entry');
        removeBtn.style.display = entries.length > 1 ? 'block' : 'none';
      });
    }
  
    function updateAddRemoveButtons() {
      const addEntries = document.querySelectorAll('#add-product-entries .product-entry');
      addEntries.forEach((entry, index) => {
        const removeBtn = entry.querySelector('.remove-entry');
        removeBtn.style.display = addEntries.length > 1 ? 'block' : 'none';
      });
    }
  
    function calculateTotal(entry) {
      const price = parseFloat(entry.querySelector('.update-price').value) || 0;
      const quantity = parseInt(entry.querySelector('.update-quantity').value) || 0;
      const total = price * quantity;
      entry.querySelector('.total-price').value = total.toFixed(2);
    }
  
    // Event listeners for price/quantity updates
    document.addEventListener('change', function(e) {
      if (e.target.classList.contains('update-product')) {
        const entry = e.target.closest('.product-entry');
        const selectedOption = e.target.options[e.target.selectedIndex];
        if (selectedOption.value) {
          const price = parseFloat(selectedOption.dataset.price) || 0;
          const priceInput = entry.querySelector('.update-price');
          priceInput.value = price.toFixed(2);
          calculateTotal(entry);
        }
      }
    });
  
    document.addEventListener('input', function(e) {
      if (e.target.classList.contains('update-price') || e.target.classList.contains('update-quantity')) {
        const entry = e.target.closest('.product-entry');
        calculateTotal(entry);
      }
    });
  
    // Modal event listeners
    document.getElementById('updateProductModal').addEventListener('shown.bs.modal', updateRemoveButtons);
    document.getElementById('addProductModal').addEventListener('shown.bs.modal', updateAddRemoveButtons);
  
    // Receipt modal logic (using variables from inline script)
    if (showReceipt && hasReceipt && document.getElementById('receiptModal')) {
      const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
      receiptModal.show();
      window.history.replaceState({}, document.title, window.location.pathname);
    }
  
    // Save as PDF
    document.getElementById('save-pdf').addEventListener('click', function() {
      const element = document.getElementById('receipt-content');
      if (typeof html2pdf !== 'undefined') {
        html2pdf().from(element).save('stock_receipt_' + new Date().toISOString().slice(0, 10) + '.pdf');
      } else {
        console.error('html2pdf library not loaded');
      }
    });
  
    // Clear and hide receipt
    document.getElementById('clear-hide').addEventListener('click', function() {
      fetch('/clearReceipt', {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(response => response.text())
      .then(() => {
        const receiptModal = bootstrap.Modal.getInstance(document.getElementById('receiptModal'));
        receiptModal.hide();
      })
      .catch(error => console.error('Error clearing receipt:', error));
    });
  
    // Debug form submissions
    const addProductForm = document.getElementById('addProductForm');
    if (addProductForm) {
      addProductForm.addEventListener('submit', function(e) {
        console.log('Add Product Form submitted');
        console.log(new FormData(this));
      });
    }
  
    const updateProductForm = document.getElementById('updateProductForm');
    if (updateProductForm) {
      updateProductForm.addEventListener('submit', function(e) {
        console.log('Update Product Form submitted');
        console.log(new FormData(this));
      });
    }
  
    // Check for notification alert and log its presence
    const alert = document.querySelector('.alert');
    if (alert) {
      console.log('Notification alert displayed:', alert.textContent.trim());
    }
  
    // Function to dynamically generate product options (placeholder, ideally fetched via AJAX)
    function getProductOptions() {
      return document.querySelector('#updateProduct-0').innerHTML;
    }
  });
  
  // Research product function
  function researchProduct(query) {
    const productCards = document.querySelectorAll('.product-card');
    let visibleCount = 0;
  
    query = query.trim().toLowerCase();
  
    productCards.forEach(card => {
      const name = card.dataset.name;
      const price = parseFloat(card.dataset.price);
      const quantity = parseInt(card.dataset.quantity);
  
      const matchesName = name.includes(query);
      const matchesPrice = price.toString().includes(query);
      const matchesQuantity = quantity.toString().includes(query);
  
      if (matchesName || matchesPrice || matchesQuantity) {
        card.style.display = '';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });
  
    console.log(`Found ${visibleCount} products matching "${query}"`);
  }