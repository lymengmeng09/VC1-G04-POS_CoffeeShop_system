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

  // Utility function to get product options for Update modal
  function getProductOptions() {
    const initialSelect = document.querySelector('#updateProduct-0');
    return initialSelect ? initialSelect.innerHTML : '<option value="">No products available</option>';
  }

  // Function to calculate total price for Update modal
  function calculateTotal(entry) {
    const price = parseFloat(entry.querySelector('.update-price').value) || 0;
    const quantity = parseInt(entry.querySelector('.update-quantity').value) || 0;
    const total = price * quantity;
    entry.querySelector('.total-price').value = total.toFixed(2);
  }

  // Function to add event listeners to dynamic entries (Update modal)
  function addDynamicEventListeners(entry) {
    const select = entry.querySelector('.update-product');
    const priceInput = entry.querySelector('.update-price');
    const quantityInput = entry.querySelector('.update-quantity');

    if (select) {
      select.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
          const price = parseFloat(selectedOption.dataset.price) || 0;
          priceInput.value = price.toFixed(2);
          calculateTotal(entry);
        }
      });
    }

    if (priceInput) {
      priceInput.addEventListener('input', function() {
        calculateTotal(entry);
      });
    }

    if (quantityInput) {
      quantityInput.addEventListener('input', function() {
        calculateTotal(entry);
      });
    }
  }

  // Function to update remove icons visibility
  function updateRemoveIcons(containerId) {
    const entries = document.querySelectorAll(`#${containerId} .product-entry`);
    entries.forEach((entry) => {
      const removeIcon = entry.querySelector('.remove-entry');
      removeIcon.style.display = entries.length > 1 ? 'inline-block' : 'none';
    });
  }

  // Function to handle image preview and cancel
  function setupImagePreview(input) {
    const inputId = input.id;
    const previewContainer = document.getElementById(`preview-${inputId}`);
    const previewImg = previewContainer.querySelector('img');
    const cancelBtn = previewContainer.querySelector('.cancel-upload');

    input.addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImg.src = e.target.result;
          previewContainer.style.display = 'block'; // Show preview
          input.style.display = 'none'; // Hide file input
        };
        reader.readAsDataURL(file);
      } else {
        previewContainer.style.display = 'none'; // Hide preview
        input.style.display = 'block'; // Show file input
      }
    });

    cancelBtn.addEventListener('click', function() {
      input.value = ''; // Reset file input
      previewImg.src = ''; // Clear preview image
      previewContainer.style.display = 'none'; // Hide preview
      input.style.display = 'block'; // Show file input again
    });
  }

  // Handle "Add More" for Add New Product modal
  let addEntryCount = 0;
  const addMoreProductButton = document.getElementById('add-more-product');
  if (addMoreProductButton) {
    addMoreProductButton.addEventListener('click', function() {
      console.log('Add More Product clicked');
      addEntryCount++;
      const addProductEntries = document.getElementById('add-product-entries');
      if (!addProductEntries) {
        console.error('add-product-entries container not found');
        return;
      }


      const newEntry = document.createElement('div');
      newEntry.classList.add('product-entry', 'mb-3');
      newEntry.style.display = 'block'; // Ensure new entry is visible
      newEntry.innerHTML = `
        <h6>Product ${addEntryCount + 1}</h6>
        <div class="row g-3 align-items-end">
          <div class="col-md-3">
            <label for="addName-${addEntryCount}" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="addName-${addEntryCount}" name="name[]" required>
          </div>
          <div class="col-md-2">
            <label for="addPrice-${addEntryCount}" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="addPrice-${addEntryCount}" name="price[]" required>
          </div>
          <div class="col-md-2">
            <label for="addQuantity-${addEntryCount}" class="form-label">Stock Quantity</label>
            <input type="number" class="form-control" id="addQuantity-${addEntryCount}" name="quantity[]" required>
          </div>
          <div class="col-md-3">
            <label for="addImage-${addEntryCount}" class="form-label">Upload Image</label>
            <input type="file" class="form-control custom-file-input" id="addImage-${addEntryCount}" name="image[]" accept="image/jpeg,image/png,image/gif" required>
            <div class="image-preview mt-2" id="preview-addImage-${addEntryCount}" style="display: none;">
              <img src="" alt="Image Preview" style="max-width: 100px; max-height: 100px;">
              <button type="button" class="btn btn-sm btn-danger cancel-upload mt-1" data-input-id="addImage-${addEntryCount}">Cancel</button>
            </div>
          </div>
          <div class="col-md-2 text-center">
            <i class="bi bi-trash remove-entry" style="cursor: pointer; font-size: 1.5rem; color: #dc3545;" title="Remove"></i>
          </div>
        </div>
      `;
      addProductEntries.appendChild(newEntry);
      updateRemoveIcons('add-product-entries');

      // Setup image preview for the new input
      const newFileInput = newEntry.querySelector(`#addImage-${addEntryCount}`);
      setupImagePreview(newFileInput);
    });
  } else {
    console.error('add-more-product button not found');
  }

  // Handle "Add More" for Update Existing Product modal
  let updateEntryCount = 0;
  const addMoreButton = document.getElementById('add-more');
  if (addMoreButton) {
    addMoreButton.addEventListener('click', function() {
  console.log('Add More (Update) clicked');
  updateEntryCount++;
  const productEntries = document.getElementById('product-entries');
  if (!productEntries) {
    console.error('product-entries container not found');
    return;
  }

  // Get all currently selected product IDs
  const selectedProductIds = Array.from(document.querySelectorAll('#product-entries .update-product'))
    .map(select => select.value)
    .filter(value => value !== '');

  // Get the original product options and filter out selected ones
  const initialSelect = document.querySelector('#updateProduct-0');
  let productOptions = initialSelect ? initialSelect.innerHTML : '<option value="">No products available</option>';
  if (selectedProductIds.length > 0) {
    const parser = new DOMParser();
    const doc = parser.parseFromString(`<select>${productOptions}</select>`, 'text/html');
    const options = doc.querySelectorAll('option');
    productOptions = '<option value="">Select a product...</option>';
    options.forEach(option => {
      if (option.value && !selectedProductIds.includes(option.value)) {
        productOptions += option.outerHTML;
      }
    });
  }


  const newEntry = document.createElement('div');
  newEntry.classList.add('product-entry', 'mb-3');
  newEntry.style.display = 'block'; // Ensure new entry is visible
  newEntry.innerHTML = `
    <div class="row g-3 align-items-end">
      <div class="col-md-3">
        <label for="updateProduct-${updateEntryCount}" class="form-label">Select Product</label>
        <select class="form-control update-product" id="updateProduct-${updateEntryCount}" name="product_id[]" required>
          ${productOptions}
        </select>
      </div>
      <div class="col-md-2">
        <label for="updatePrice-${updateEntryCount}" class="form-label">Price</label>
        <input type="number" step="0.01" class="form-control update-price" id="updatePrice-${updateEntryCount}" name="price[]" required>
      </div>
      <div class="col-md-2">
        <label for="updateQuantity-${updateEntryCount}" class="form-label">Quantity</label>
        <input type="number" class="form-control update-quantity" id="updateQuantity-${updateEntryCount}" name="quantity[]" required>
      </div>
      <div class="col-md-2">
        <label for="totalPrice-${updateEntryCount}" class="form-label">Total Price</label>
        <div class="input-group">
          <span class="input-group-text">$</span>
          <input type="text" class="form-control total-price" id="totalPrice-${updateEntryCount}" readonly>
        </div>
      </div>
      <div class="col-md-2 text-center">
        <i class="bi bi-trash remove-entry" style="cursor: pointer; font-size: 1.5rem; color: #dc3545;" title="Remove"></i>
      </div>
    </div>
  `;
  productEntries.appendChild(newEntry);
  updateRemoveIcons('product-entries');
  addDynamicEventListeners(newEntry);

  updateAllDropdowns(); // 🔥 Ensure dropdown updates dynamically
});
}

  // Handle remove icon clicks (event delegation for both modals)
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-entry')) {
      console.log('Remove icon clicked');
      const entry = e.target.closest('.product-entry');
      if (entry) {
        const containerId = entry.closest('#add-product-entries') ? 'add-product-entries' : 'product-entries';
        entry.remove();
        updateRemoveIcons(containerId);
      }
    }
  });

  // Initialize event listeners for existing Update modal entries
  const existingUpdateEntries = document.querySelectorAll('#product-entries .product-entry');
  existingUpdateEntries.forEach(addDynamicEventListeners);

  // Initialize image preview for the initial Add New Product input
  const initialFileInput = document.querySelector('#addImage-0');
  if (initialFileInput) {
    setupImagePreview(initialFileInput);
  }

  // Modal event listeners
  document.getElementById('addProductModal')?.addEventListener('shown.bs.modal', () => updateRemoveIcons('add-product-entries'));
  document.getElementById('updateProductModal')?.addEventListener('shown.bs.modal', () => updateRemoveIcons('product-entries'));

  // Receipt modal logic
  if (typeof showReceipt !== 'undefined' && typeof hasReceipt !== 'undefined' && showReceipt && hasReceipt && document.getElementById('receiptModal')) {
    const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
    receiptModal.show();
    window.history.replaceState({}, document.title, window.location.pathname);
  } const savePdfButton = document.getElementById('save-pdf');

  if (savePdfButton) {
    savePdfButton.addEventListener('click', function() {
      const receiptElement = document.getElementById('receipt-content');
      
      if (receiptElement) {
        html2pdf().set({
          margin: 10,
          filename: 'stock_receipt_' + new Date().toISOString().slice(0, 10) + '.pdf',
          image: { type: 'jpeg', quality: 0.98 },
          html2canvas: { scale: 2, logging: false },
          jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        }).from(receiptElement).save().then(() => {
          // Ensure Bootstrap Modal is closed after saving
          const receiptModal = document.getElementById('receiptModal');
          if (receiptModal) {
            const modalInstance = bootstrap.Modal.getInstance(receiptModal);
            if (modalInstance) modalInstance.hide();
          }
        });
      } else {
        console.error('Error: #receipt-content not found.');
      }
    });
  } else {
    console.error('Error: Save PDF button not found.');
  }
// Clear and hide receipt
const clearHideButton = document.getElementById('clear-hide');
if (clearHideButton) {
  clearHideButton.addEventListener('click', function() {
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
}

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

// Check for notification alert
const alert = document.querySelector('.alert');

if (alert) {
console.log('Notification alert displayed:', alert.textContent.trim());

// Hide alert after 2 seconds
setTimeout(() => {
  alert.style.display = 'none';
}, 2000); // 2000ms = 2 seconds
}

// Initial update of remove icons for both modals
updateRemoveIcons('add-product-entries');
updateRemoveIcons('product-entries');
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

// Check for notification alert
const alert = document.querySelector('.alert');

if (alert) {
  console.log('Notification alert displayed:', alert.textContent.trim());

  // Hide alert after 2 seconds
  setTimeout(() => {
    alert.style.display = 'none';
  }, 2000); // 2000ms = 2 seconds
}

  // Initial update of remove icons for both modals
  updateRemoveIcons('add-product-entries');
  updateRemoveIcons('product-entries');


// Research product function
function researchProduct(query) {
  const productCards = document.querySelectorAll('.product-card');
  let visibleCount = 0;

  query = query.trim().toLowerCase();

  productCards.forEach(card => {
    const name = card.dataset.name || '';
    const price = parseFloat(card.dataset.price) || 0;
    const quantity = parseInt(card.dataset.quantity) || 0;

    const matchesName = name.toLowerCase().includes(query);
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
