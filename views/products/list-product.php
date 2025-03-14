<?php
require_once '../layouts/header.php';
require_once '../layouts/navbar.php'
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop</title>
    <link rel="stylesheet" href="/views/assets/css/add-product.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="/views/assets/js/add-product.js" defer></script>
</head>

<body>
    <div class="container">
        <!-- Left side: Products -->
        <div class="products-section">
            <div class="header">
                <h1>Choose Products</h1>
                <div class="search-container">
                    <input type="text" placeholder="search category ...">
                    <button class="search-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Coffee thumbnails -->
            <div class="coffee-thumbnails">
                <div class="thumbnail">
                    <img src="https://via.placeholder.com/80" alt="Coffee">
                </div>
                <div class="thumbnail">
                    <img src="https://via.placeholder.com/80" alt="Coffee">
                </div>
                <div class="thumbnail">
                    <img src="https://via.placeholder.com/80" alt="Coffee">
                </div>
                <div class="thumbnail">
                    <img src="https://via.placeholder.com/80" alt="Coffee">
                </div>
                <div class="thumbnail">
                    <img src="https://via.placeholder.com/80" alt="Coffee">
                </div>
            </div>

            <h2>Coffee Menu</h2>

            <div class="coffee-grid">
                <!-- Americano Card -->
                <div class="coffee-card" data-id="americano" data-name="Americano" data-price="1.15">
                    <div class="coffee-info">
                        <div class="coffee-image">
                            <img src="https://via.placeholder.com/100" alt="Americano">
                        </div>
                        <div class="coffee-details">
                            <h3>Americano</h3>
                            <p class="price">$ 1,15</p>
                        </div>
                    </div>
                    <button class="add-btn">Order New</button>
                </div>

                <!-- Espresso Card -->
                <div class="coffee-card" data-id="espresso" data-name="Espresso" data-price="1.15">
                    <div class="coffee-info">
                        <div class="coffee-image">
                            <img src="https://via.placeholder.com/100" alt="Espresso">
                        </div>
                        <div class="coffee-details">
                            <h3>Espresso</h3>
                            <p class="price">$ 1,15</p>
                        </div>
                    </div>
                    <button class="add-btn">Order New</button>

                </div>
            </div>
        </div>

        <!-- Right side: Bill -->
        <div class="bill-section">
            <div class="user-info">
                <div class="user-avatar">
                    <img src="https://via.placeholder.com/40" alt="User">
                </div>
                <span class="username">Username</span>
                <div class="notification">
                    <span class="notification-dot"></span>
                </div>
            </div>

            <h2>Bills</h2>

            <div class="cart-items">
                <!-- Cart items will be added dynamically -->
            </div>

            <div class="total-section">
                <h3>Total price</h3>
                <span class="total-price">$0.00</span>
            </div>

            <button class="pay-btn">PAY NOW</button>
        </div>
    </div>

</body>

</html>