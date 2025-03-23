<div class="col-md-14 py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Product Management</h2>
        <button class="btn btn-primary text-light">
            <a href="/products/create"><i class="fas fa-plus me-2 text-light"></i> Add Product</a>
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
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            categories: <?= htmlspecialchars(ucfirst($_GET['category_name'] ?? 'all')) ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="">All category</a></li>
                            <li><a class="dropdown-item" href="">Coffee</a></li>
                            <li><a class="dropdown-item" href="">Tea</a></li>
                            <li><a class="dropdown-item" href="">Smoothies</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md shop" style="position: relative;">
                    <a href="javascript:void(0)" id="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="count_cart" id="cart-count">0</span>
                    </a>
                </div>
            </form>
        </div>
        
        <div class="row g-4 coffee-grid">
            <!-- Product Section -->
            <div class="row g-4 coffee-grid">
                <?php foreach ($products as $index => $product): ?>
                    <div class="col-6 col-md-3 product-item" data-category="Coffee">
                        <div class="card border-0 h-100">
                            <div class="text-center p-2">
                                <div class="product-entry">
                                    <div class="dropdown">
                                        <button class="dropbtn">⋮</button>
                                        <div class="dropdown-content">
                                        <a href="/products/edit/<?= htmlspecialchars($product['product_id']) ?>">Edit</a>
                                        <form action="/products/delete/<?= htmlspecialchars($product['product_id']) ?>" method="POST" style="display:inline;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" onclick="return confirm('Are you sure?')" style="background:none;border:none;color:#000;padding:0;">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" class="img-fluid mb-2">
                                    <div class="mt-2">
                                        <h6 class="card-title fw-normal text-center mb-1" style="font-size: 0.9rem;">
                                            <?= htmlspecialchars($product['product_name']) ?>
                                        </h6>
                                        <p class="text-success fw-bold mb-0">
                                            $<?= number_format($product['price'], 2) ?>
                                        </p>
                                        <button class="btn-Order" data-name="<?= htmlspecialchars($product['product_name']) ?>" data-price="<?= number_format($product['price'], 2) ?>" data-img="<?= htmlspecialchars($product['image_url']) ?>">
                                            Order Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>


            <!-- Cart Table -->
            <div id="cart-table" style="display: none;" class="card-order">
                <h3>Bills</h3>
                <table class="table table-bordered">
                    <div id="cart-table-body">
                    </div>
                </table>
                <div class="d-flex justify-content-between total" id="btn">
                    <div class="cart-total">Total: $<span id="cart-total">0.00</span></div>
                    <div class="btn_cart">
                        <button id="clear-all" class="btn btn-secondary">Cancel</button>
                        <button id="PayMent" class="btn btn-primary">Pay New</button>
                    </div>
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