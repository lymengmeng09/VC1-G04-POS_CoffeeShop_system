<?php
session_start(); 
if(isset($_SESSION["user"])){ 
    header("location: .php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/views/assets/css/view.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <title>Stock Products</title>
  <style>
    .product-card {
      margin-bottom: 20px;
      padding: 10px;
      border: 1px solid #ddd;
    }
    .out-of-stock {
      background-color: #ffcccc;
      color: #ff0000;
      font-weight: bold;
    }
    .custom-file-input {
      overflow: hidden;
    }
    .user-icons {
    display: flex;
    gap: 15px;
    align-items: center;
}

.notification-icon,
.user-icon {
    position: relative;
    background: #f8f9fa; /* Light background */
    border-radius: 50%;
    padding: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 45px;
    height: 45px;
    transition: all 0.3s ease-in-out;
    cursor: pointer;
    box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1);
}

.notification-icon i,
.user-icon i {
    font-size: 20px;
    color: #333;
    transition: color 0.3s ease-in-out;
}

/* Hover effect */
.notification-icon:hover,
.user-icon:hover {
    background: #007bff; /* Change to primary color */
}

.notification-icon:hover i,
.user-icon:hover i {
    color: white;
}

/* Notification badge */
.notification-icon::after {
    content: '3'; /* Example notification count */
    position: absolute;
    top: 5px;
    right: 5px;
    background: red;
    color: white;
    font-size: 12px;
    font-weight: bold;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
}

  </style>
</head>
<body>
  <div class="container">
    <?php
    if (isset($_SESSION['notification'])) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
        echo $_SESSION['notification'];
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
        unset($_SESSION['notification']); // Clear the notification after displaying it
    }
    ?>

    <div class="header d-flex justify-content-between align-items-center my-4">
      <h1>Stock Products</h1>
      <div class="user-icons">
    <div class="notification-icon">
        <i class="fa fa-bell"></i> <!-- Notification Icon -->
    </div>
    <div class="user-icon avatar">
        <i class="fa fa-user-circle"></i> <!-- User Avatar Icon -->
    </div>
</div>

<!-- Add Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

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
            <div class="product-image">
              <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
            <div class="product-info">
              <h3 class="origin"><?= htmlspecialchars($product['name']) ?></h3>
              <p class="price">Price: $<?= number_format($product['price'], 2) ?></p>
              <p class="quantity">Quantity: <?= $product['quantity'] ?> <?= $product['quantity'] == 0 ? '(Out of Stock)' : '' ?></p>
              <!-- Only show total value for existing products with quantity > 0 -->
              <?php if ($product['quantity'] > 0) : ?>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ✅ CSS for Styling -->
<style>
    .dropdown {
        position: relative; 
        display: inline-block;
        margin-left: 90%;
    }

    .dropbtn {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        min-width: 100px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 8px 12px;
        display: block;
        text-decoration: none;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }
</style>


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
              <div class="mb-3">
                <label for="updateProduct" class="form-label">Select Product</label>
                <select class="form-control" id="updateProduct" name="product_id" required>
                  <option value="">Select a product...</option>
                  <?php foreach ($products as $product) : ?>
                    <option value="<?= $product['id'] ?>" 
                            data-price="<?= $product['price'] ?>">
                      <?= htmlspecialchars($product['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="updatePrice" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="updatePrice" name="price" required>
              </div>
              <div class="mb-3">
                <label for="updateQuantity" class="form-label">New Quantity</label>
                <input type="number" class="form-control" id="updateQuantity" name="quantity" required>
              </div>
              <div class="mb-3">
                <label for="totalPrice" class="form-label">Total Price</label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="text" class="form-control" id="totalPrice" readonly>
                </div>
              </div>
              <button type="submit" class="btn btn-success">Update</button>
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
        if (productName.indexOf(query) !== -1) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    });

    // Update product selection and total price calculation
    document.getElementById('updateProduct').addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      if (selectedOption.value) {
        const price = parseFloat(selectedOption.dataset.price);
        document.getElementById('updatePrice').value = price.toFixed(2);
        calculateTotal();
      }
    });

    // Calculate total when price or quantity changes
    document.getElementById('updatePrice').addEventListener('input', calculateTotal);
    document.getElementById('updateQuantity').addEventListener('input', calculateTotal);

    function calculateTotal() {
      const price = parseFloat(document.getElementById('updatePrice').value) || 0;
      const quantity = parseInt(document.getElementById('updateQuantity').value) || 0;
      const total = price * quantity;
      document.getElementById('totalPrice').value = total.toFixed(2);
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>