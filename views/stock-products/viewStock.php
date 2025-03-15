<?php
 

// Check if user is authenticated
if (!isset($_SESSION["user"])) {
    header("Location: /login.php");
    exit();
}

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/views/assets/css/view.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
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
            background: #f8f9fa;
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
        .notification-icon:hover,
        .user-icon:hover {
            background: #007bff;
        }
        .notification-icon:hover i,
        .user-icon:hover i {
            color: white;
        }
        .notification-icon::after {
            content: '3';
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
        // Display success message
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
            echo htmlspecialchars($_SESSION['success']);
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
            unset($_SESSION['success']);
        }

        // Display error message
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            echo htmlspecialchars($_SESSION['error']);
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
            unset($_SESSION['error']);
        }

        // Display notification
        if (isset($_SESSION['notification'])) {
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
            echo htmlspecialchars($_SESSION['notification']);
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
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($product['image'] ?? '/views/assets/images/default-product.jpg') ?>" 
                                 alt="<?= htmlspecialchars($product['name']) ?>">
                        </div>
                        <div class="product-info">
                            <h3 class="origin"><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="price">Price: $<?= number_format($product['price'], 2) ?></p>
                            <p class="quantity">Quantity: <?= htmlspecialchars($product['quantity']) ?></p>
                            <button class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#updateProductModal"
                                    onclick="setUpdateModal('<?= htmlspecialchars($product['id']) ?>', '<?= htmlspecialchars($product['name']) ?>', '<?= htmlspecialchars($product['price']) ?>', '<?= htmlspecialchars($product['quantity']) ?>')">
                                Edit
                            </button>
                            <form method="POST" action="/delete-product" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                            </form>
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
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                            <div class="mb-3">
                                <label for="addName" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="addName" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="addPrice" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" id="addPrice" name="price" min="0" required>
                            </div>
                            <div class="mb-3">
                                <label for="addQuantity" class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control" id="addQuantity" name="quantity" min="0" required>
                            </div>
                            <div class="mb-3">
                                <label for="addImage" class="form-label">Upload Image</label>
                                <input type="file" class="form-control custom-file-input" id="addImage" name="image" accept="image/*">
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
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                            <input type="hidden" name="product_id" id="updateProductId">
                            <div class="mb-3">
                                <label for="updateName" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="updateName" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="updatePrice" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" id="updatePrice" name="price" min="0" required>
                            </div>
                            <div class="mb-3">
                                <label for="updateQuantity" class="form-label">New Quantity</label>
                                <input type="number" class="form-control" id="updateQuantity" name="quantity" min="0" required>
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

        // Function to set update modal values
        function setUpdateModal(id, name, price, quantity) {
            document.getElementById('updateProductId').value = id;
            document.getElementById('updateName').value = name;
            document.getElementById('updatePrice').value = parseFloat(price).toFixed(2);
            document.getElementById('updateQuantity').value = quantity;
            calculateTotal();
        }

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