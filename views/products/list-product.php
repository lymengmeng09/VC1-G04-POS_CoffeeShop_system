<div class="col-md-14 py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Product Management</h2>
        <button class="btn btn-primary text-light">
            <a href="/products/create"><i class="fas fa-plus me-2 text-light"></i> Add New Product</a>
        </button>
    </div>

    <!-- Search and Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search products..." name="search" id="searchInput">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-2 category">
                    <select class="form-select" name="category" id="categoryFilter">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $category) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md shop" style="position: relative;">
                    <a href="javascript:void(0)" id="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="count_cart" id="cart-count">0</span>
                    </a>
                </div>
            </form>
        </div>
        <hr>
        <div class="row g-4 coffee-grid">
            <div class="row g-4 coffee-grid">
                <!-- Product Section -->
                <div class="col-6 col-md-2-4 product-item" data-category="Coffee">
                    <div class="card border-0 h-100">
                        <div class="text-center p-2 position-relative">
                            <!-- Dropdown Menu -->
                            <div class="dropdown" style="position: absolute; top: 10px; right: 10px;">
                                <button class="dropbtn" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">⋮</button>
                                <div class="dropdown-content" style="display: none; position: absolute; right: 0; background-color: #f9f9f9; min-width: 100px; box-shadow: 0px 8px 16px rgba(0,0,0,0.2); z-index: 1;">
                                    <a href="/products/edit?name=Matcha" style="color: #000; padding: 8px 16px; text-decoration: none; display: block;">Edit</a>
                                    <a href="/products/delete?name=Matcha" style="color: #000; padding: 8px 16px; text-decoration: none; display: block;" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                </div>
                            </div>
                            <img src="/views/assets/images/coffee.jpg" alt="Matcha" class="img-fluid mb-2">
                            <div class="mt-2">
                                <h6 class="card-title fw-normal text-center mb-1" style="font-size: 0.9rem;">
                                    Matcha
                                </h6>
                                <p class="text-success fw-bold mb-0">$7.99</p>
                                <button class="btn-Order" data-name="Matcha" data-price="7.99" data-img="/views/assets/images/coffee.jpg">
                                    Order New
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-2-4 product-item" data-category="Coffee">
                    <div class="card border-0 h-100">
                        <div class="text-center p-2 position-relative">
                            <!-- Dropdown Menu -->
                            <div class="dropdown" style="position: absolute; top: 10px; right: 10px;">
                                <button class="dropbtn" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">⋮</button>
                                <div class="dropdown-content" style="display: none; position: absolute; right: 0; background-color: #f9f9f9; min-width: 100px; box-shadow: 0px 8px 16px rgba(0,0,0,0.2); z-index: 1;">
                                    <a href="/products/edit?name=Coffee" style="color: #000; padding: 8px 16px; text-decoration: none; display: block;">Edit</a>
                                    <a href="/products/delete?name=Coffee" style="color: #000; padding: 8px 16px; text-decoration: none; display: block;" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                </div>
                            </div>
                            <img src="/views/assets/images/coffee.jpg" alt="Coffee" class="img-fluid mb-2">
                            <div class="mt-2">
                                <h6 class="card-title fw-normal text-center mb-1" style="font-size: 0.9rem;">
                                    Coffee
                                </h6>
                                <p class="text-success fw-bold mb-0">$7.99</p>
                                <button class="btn-Order" data-name="Coffee" data-price="7.99" data-img="/views/assets/images/coffee.jpg">
                                    Order New
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-2-4 product-item" data-category="Coffee">
                    <div class="card border-0 h-100">
                        <div class="text-center p-2 position-relative">
                            <!-- Dropdown Menu -->
                            <div class="dropdown" style="position: absolute; top: 10px; right: 10px;">
                                <button class="dropbtn" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">⋮</button>
                                <div class="dropdown-content" style="display: none; position: absolute; right: 0; background-color: #f9f9f9; min-width: 100px; box-shadow: 0px 8px 16px rgba(0,0,0,0.2); z-index: 1;">
                                    <a href="/products/edit?name=Matcha Latte" style="color: #000; padding: 8px 16px; text-decoration: none; display: block;">Edit</a>
                                    <a href="/products/delete?name=Matcha Latte" style="color: #000; padding: 8px 16px; text-decoration: none; display: block;" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                </div>
                            </div>
                            <img src="/views/assets/images/coffee.jpg" alt="Matcha Latte" class="img-fluid mb-2">
                            <div class="mt-2">
                                <h6 class="card-title fw-normal text-center mb-1" style="font-size: 0.9rem;">
                                    Matcha Latte
                                </h6>
                                <p class="text-success fw-bold mb-0">$7.99</p>
                                <button class="btn-Order" data-name="Matcha Latte" data-price="7.99" data-img="/views/assets/images/coffee.jpg">
                                    Order New
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-2-4 product-item" data-category="Coffee">
                    <div class="card border-0 h-100">
                        <div class="text-center p-2 position-relative">
                            <!-- Dropdown Menu -->
                            <div class="dropdown" style="position: absolute; top: 10px; right: 10px;">
                                <button class="dropbtn" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">⋮</button>
                                <div class="dropdown-content" style="display: none; position: absolute; right: 0; background-color: #f9f9f9; min-width: 100px; box-shadow: 0px 8px 16px rgba(0,0,0,0.2); z-index: 1;">
                                    <a href="/products/edit?name=Latte" style="color: #000; padding: 8px 16px; text-decoration: none; display: block;">Edit</a>
                                    <a href="/products/delete?name=Latte" style="color: #000; padding: 8px 16px; text-decoration: none; display: block;" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                </div>
                            </div>
                            <img src="/views/assets/images/coffee.jpg" alt="Latte" class="img-fluid mb-2">
                            <div class="mt-2">
                                <h6 class="card-title fw-normal text-center mb-1" style="font-size: 0.9rem;">
                                    Latte
                                </h6>
                                <p class="text-success fw-bold mb-0">$7.99</p>
                                <button class="btn-Order" data-name="Latte" data-price="7.99" data-img="/views/assets/images/coffee.jpg">
                                    Order New
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add more product items here -->
            </div>
            <tbody id="cart-table-body">
                <!-- Cart items will be dynamically inserted here -->
            </tbody>

            <!-- Cart Table -->
            <div id="cart-table" style="display: none;" class="card-order">
                <h3>Bills</h3>
                <table class="table table-bordered">
                    <div id="cart-table-body">
                    </div>
                </table>
                <button id="clear-all" class="btn btn-danger">Clear</button>

                <div class="d-flex justify-content-between">
                    <div class="cart-total">Total: $<span id="cart-total">0.00</span></div>
                    <button id="PayMent" class="btn btn-primary">Pay New</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inline JavaScript for Dropdown Functionality -->
<script>
    document.querySelectorAll('.dropbtn').forEach(button => {
        button.addEventListener('click', function() {
            const dropdownContent = this.nextElementSibling;
            dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
        });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.matches('.dropbtn')) {
            document.querySelectorAll('.dropdown-content').forEach(dropdown => {
                dropdown.style.display = 'none';
            });
        }
    });
</script>