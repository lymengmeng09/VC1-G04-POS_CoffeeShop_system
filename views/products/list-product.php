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
            <i class="material-icons">shopping_cart</i>
        </div>

        <!-- Coffee thumbnails -->
        <div class="coffee-thumbnails">
            <div class="thumbnail" data-product-id="1">
                <img src="/views/assets/images/coffee.jpg" alt="Coffee">
            </div>
            <div class="thumbnail" data-product-id="4">
                <img src="/views/assets/images/coffee.jpg" alt="Coffee">
            </div>
            <div class="thumbnail" data-product-id="5">
                <img src="/views/assets/images/coffee.jpg" alt="Coffee">
            </div>
        </div>

        <!-- <h2>Coffee Menu</h2> -->

        <div class="coffee-grid" id="coffee-grid">
            <!-- Dynamic Product Cards will be added here -->
        </div>
    </div>

    <!-- Right side: Bill -->
    <div class="bill-section" id="bill-section">
        <div class="user-info">
            <div class="user-avatar">
                <img src="/views/assets/images/cofe.png" alt="User">
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
