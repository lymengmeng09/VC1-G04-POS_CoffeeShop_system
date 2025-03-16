<?php
 
require "views/layouts/header.php";
require "views/layouts/navbar.php";
?>

 
<body>
  <div class="container"> 
    <?php
    if (isset($_SESSION['notification'])) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
        echo $_SESSION['notification'];
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
        unset($_SESSION['notification']);
    }
    ?>

    <div class="header d-flex justify-content-between align-items-center my-4">
      <h1>Stock Products</h1>
      <div class="user-icons">
        <div class="notification-icon">
          <i class="fa fa-bell"></i>
        </div>
        <div class="user-icon avatar">
          <i class="fa fa-user-circle"></i>
        </div>
      </div>
    </div>

    <div class="search-section mb-4">
      <div class="search-bar">
        <input type="text" class="form-control search-input" placeholder="Search products...">
      </div>
      <div class="action-buttons mt-2">
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateProductModal">+ Existing Product</button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">+ New Product</button>
      </div>
    </div>

    <div class="products-section">
      <h2 class="section-title">Products In Stock</h2>
      <div class="products-grid">
        
        <?php foreach ($products as $product) : ?>
          
          <div class="product-card <?= $product['quantity'] == 0 ? 'out-of-stock' : '' ?>">
          <div class="dropdown">
                <button class="dropbtn">⋮</button>
                <div class="dropdown-content">
                  <a href="/edit_product?id =<?= $product['id'] ?>">Edit</a>
                  <a href="delete_product.php?id=<?= $product['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
              </div>
            <div class="product-image">
              <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
            <div class="product-info">
              <h3 class="origin"><?= htmlspecialchars($product['name']) ?></h3>
              <p class="price">Price: $<?= number_format($product['price'], 2) ?></p>
              <p class="quantity">Quantity: <?= $product['quantity'] ?> <?= $product['quantity'] == 0 ? '(Out of Stock)' : '' ?></p>
             
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <!-- Add New Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form method="POST" action="/add-product" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="addName" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="addName" name="name" required>
              </div>
              <div class="mb-3">
                <label for="addPrice" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="addPrice" name="price" required>
              </div>
              <div class="mb-3">
                <label for="addQuantity" class="form-label">Stock Quantity</label>
                <input type="number" class="form-control" id="addQuantity" name="quantity" required>
              </div>
              <div class="mb-3">
                <label for="addImage" class="form-label">Upload Image</label>
                <input type="file" class="form-control custom-file-input" id="addImage" name="image" accept="image/*" required>
              </div>
              <button type="submit" class="btn btn-primary">Complete</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Update Existing Product Modal -->
    <div class="modal fade" id="updateProductModal" tabindex="-1" aria-labelledby="updateProductModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="updateProductModalLabel">Update Existing Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form method="POST" action="/update-stock">
              <div id="product-entries">
                <!-- Initial product entry -->
                <div class="product-entry mb-3">
                  <div class="mb-3">
                    <label for="updateProduct-0" class="form-label">Select Product</label>
                    <select class="form-control update-product" id="updateProduct-0" name="product_id[]" required>
                      <option value="">Select a product...</option>
                      <?php foreach ($products as $product) : ?>
                        <option value="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>">
                          <?= htmlspecialchars($product['name']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="updatePrice-0" class="form-label">Price</label>
                    <input type="number" step="0.01" class="form-control update-price" id="updatePrice-0" name="price[]" required>
                  </div>
                  <div class="mb-3">
                    <label for="updateQuantity-0" class="form-label">Quantity to Add/Subtract</label>
                    <input type="number" class="form-control update-quantity" id="updateQuantity-0" name="quantity[]" required>
                  </div>
                  <div class="mb-3">
                    <label for="totalPrice-0" class="form-label">Total Price</label>
                    <div class="input-group">
                      <span class="input-group-text">$</span>
                      <input type="text" class="form-control total-price" id="totalPrice-0" readonly>
                    </div>
                  </div>
                  <button type="button" class="btn btn-danger remove-entry" style="display: none;">Remove</button>
                </div>
              </div>
              <button type="button" class="btn btn-secondary mb-3" id="add-more">Add More</button>
              <button type="submit" class="btn btn-success">Update All</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Search functionality
    document.querySelector('.search-input').addEventListener('input', function() {
      const query = this.value.toLowerCase();
      const productCards = document.querySelectorAll('.product-card');
      productCards.forEach(card => {
        const productName = card.querySelector('.origin').innerText.toLowerCase();
        card.style.display = productName.includes(query) ? '' : 'none';
      });
    });

    // Dynamic form handling for Update Existing Product modal
    let entryCount = 0;

    document.getElementById('add-more').addEventListener('click', function() {
      entryCount++;
      const productEntries = document.getElementById('product-entries');
      const newEntry = document.createElement('div');
      newEntry.classList.add('product-entry', 'mb-3');
      newEntry.innerHTML = `
        <div class="mb-3">
          <label for="updateProduct-${entryCount}" class="form-label">Select Product</label>
          <select class="form-control update-product" id="updateProduct-${entryCount}" name="product_id[]" required>
            <option value="">Select a product...</option>
            <?php foreach ($products as $product) : ?>
              <option value="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>">
                <?= htmlspecialchars($product['name']) ?>
              </option>
            <?php endforeach; ?>
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

      // Update visibility of Remove buttons
      updateRemoveButtons();
    });

    // Handle removal of product entries
    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('remove-entry')) {
        e.target.closest('.product-entry').remove();
        updateRemoveButtons();
      }
    });

    // Update visibility of Remove buttons (hide for the last entry if only one remains)
    function updateRemoveButtons() {
      const entries = document.querySelectorAll('.product-entry');
      entries.forEach((entry, index) => {
        const removeBtn = entry.querySelector('.remove-entry');
        removeBtn.style.display = entries.length > 1 ? 'block' : 'none';
      });
    }
    // Update total price when product, price, or quantity changes
    document.addEventListener('change', function(e) {
      if (e.target.classList.contains('update-product')) {
        const entry = e.target.closest('.product-entry');
        const selectedOption = e.target.options[e.target.selectedIndex];
        if (selectedOption.value) {
          const price = parseFloat(selectedOption.dataset.price);
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

    function calculateTotal(entry) {
      const price = parseFloat(entry.querySelector('.update-price').value) || 0;
      const quantity = parseInt(entry.querySelector('.update-quantity').value) || 0;
      const total = price * quantity;
      entry.querySelector('.total-price').value = total.toFixed(2);
    }

    // Initialize Remove buttons visibility on modal load
    document.getElementById('updateProductModal').addEventListener('shown.bs.modal', updateRemoveButtons);
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>