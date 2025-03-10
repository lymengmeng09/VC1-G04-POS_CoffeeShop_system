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
  <!-- Optional: Bootstrap CSS if needed for button styling -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Stock Products</title>
  <style>
    /* Optional custom styles */
    .product-card {
      margin-bottom: 20px;
      padding: 10px;
      border: 1px solid #ddd;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header d-flex justify-content-between align-items-center my-4">
      <h1>Stock Products</h1>
      <div class="user-icons">
        <div class="user-icon">G</div>
        <div class="user-icon avatar">JD</div>
      </div>
    </div>

    <!-- Search Section -->
    <div class="search-section mb-4">
      <div class="search-bar">
        <input type="text" class="form-control search-input" placeholder="Search products...">
        <div class="filter-dropdown">Filter ▼</div>
      </div>
      <div class="action-buttons mt-2">
        <a href="/add-product"><button class="btn btn-outline">+ Editing product</button></a>
        <a href="/add-product"><button class="btn btn-primary">+ New Product</button></a>
      </div>
    </div>

    <!-- Products Section -->
    <div class="products-section">
      <h2 class="section-title">Products In Stock</h2>
      <div class="products-grid">
        <?php foreach ($products as $product) : ?>
          <div class="product-card">
            <div class="product-image">
              <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
            <div class="product-info">
              <h3 class="origin"><?= htmlspecialchars($product['name']) ?></h3>
              <p class="price">Price: $<?= number_format($product['price'], 2) ?></p>
              <p class="quantity">Quantity: <?= $product['quantity'] ?></p>
              <a href="/edit-product/<?= $product['id'] ?>" class="btn btn-warning">Edit</a>
              <form method="POST" action="/delete-product/<?= $product['id'] ?>" class="d-inline">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- JavaScript for live search -->
  <script>
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
  </script>
  <!-- Optional: Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
