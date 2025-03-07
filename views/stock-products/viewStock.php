<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/view.css">
    <title>Stock Products</title>
    
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Stock Products</h1>
            <div class="user-icons">
                <div class="user-icon">G</div>
                <div class="user-icon avatar">JD</div>
            </div>
        </div>

        <div class="search-section">
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Search products...">
                <div class="filter-dropdown">Filter ▼</div>
            </div>
            <div class="action-buttons">
                <button class="btn btn-outline">+ Editing product</button>
                <button class="btn btn-primary">+ New Product</button>
            </div>
        </div>

        <div class="products-section">
            <h2 class="section-title">Products In Stock</h2>
            
            <div class="products-grid">
                <?php
                // Sample product data - in a real application, this would come from a database
                $products = [];
                for ($i = 1; $i <= 6; $i++) {
                    $products[] = [
                        'id' => $i,
                        'origin' => 'Colombia',
                        'price' => 20.99,
                        'quantity' => 1,
                        'image' => '/images/cofe.png' // Using the provided image URL
                    ];
                }

                // Display each product
                foreach ($products as $product) {
                    echo '<div class="product-card">
                        <div class="product-image">
                            <img src="' . $product['image'] . '" alt="Coffee Bag">
                        </div>
                        <div class="product-info">
                            <h3 class="origin">' . $product['origin'] . '</h3>
                            <div class="price-quantity">
                                <p class="price">Price <span>$ ' . number_format($product['price'], 2) . '</span></p>
                                <div class="qty">
                                    <p class="quantity">Quantity</p>
                                    <input type="text" value=" " class="quantity-input">
                                </div>
                            </div>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>