<div class="col-md-14 py-4">
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
        </form>
    </div>
    <div class="row g-3 coffee-grid">
        <div class="col-6 col-md-2-4 product-item" data-category="Coffee">
            <div class="card border-0 h-100">
                <div class="text-center p-2">
                    <img src="/views/assets/images/coffee.jpg" alt="Costa Coffee" class="img-fluid mb-2">
                    <div class="mt-2">
                        <h6 class="card-title fw-normal text-center mb-1" style="font-size: 0.9rem;">
                            Costa Coffee
                        </h6>
                        <p class="text-success fw-bold mb-0">$7.99</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2-4 product-item" data-category="Coffee">
            <div class="card border-0 h-100">
                <div class="text-center p-2">
                    <img src="/views/assets/images/coffee.jpg" alt="Costa Coffee" class="img-fluid mb-2">
                    <div class="mt-2">
                        <h6 class="card-title fw-normal text-center mb-1" style="font-size: 0.9rem;">
                            Costa Coffee
                        </h6>
                        <p class="text-success fw-bold mb-0">$7.99</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2-4 product-item" data-category="Coffee">
            <div class="card border-0 h-100">
                <div class="text-center p-2">
                    <img src="/views/assets/images/coffee.jpg" alt="Costa Coffee" class="img-fluid mb-2">
                    <div class="mt-2">
                        <h6 class="card-title fw-normal text-center mb-1" style="font-size: 0.9rem;">
                            Chocolate Coffee
                        </h6>
                        <p class="text-success fw-bold mb-0">$7.99</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2-4 product-item" data-category="Coffee">
            <div class="card border-0 h-100">
                <div class="text-center p-2">
                    <img src="/views/assets/images/coffee.jpg" alt="Costa Coffee" class="img-fluid mb-2">
                    <div class="mt-2">
                        <h6 class="card-title fw-normal text-center mb-1" style="font-size: 0.9rem;">
                            Latte Coffee
                        </h6>
                        <p class="text-success fw-bold mb-0">$7.99</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2-4 product-item" data-category="Chocolate">
            <div class="card border-0 h-100">
                <div class="text-center p-2">
                    <img src="/views/assets/images/coffee.jpg" alt="Chocolate Coffee" class="img-fluid mb-2">
                    <div class="mt-2">
                        <h6 class="card-title fw-normal text-center mb-1" style="font-size: 0.9rem;">
                            Hot Coffee
                        </h6>
                        <p class="text-success fw-bold mb-0">$9.99</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2-4 product-item" data-category="Chocolate">
            <div class="card border-0 h-100">
                <div class="text-center p-2">
                    <img src="/views/assets/images/coffee.jpg" alt="Chocolate Coffee" class="img-fluid mb-2">
                    <div class="mt-2">
                        <h6 class="card-title fw-normal text-center mb-1" style="font-size: 0.9rem;">
                        Mocha/Hot Chocolate
                        </h6>
                        <p class="text-success fw-bold mb-0">$9.99</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2-4 product-item" data-category="Chocolate">
            <div class="card border-0 h-100">
                <div class="text-center p-2">
                    <img src="/views/assets/images/coffee.jpg" alt="Chocolate Coffee" class="img-fluid mb-2">
                    <div class="mt-2">
                        <h6 class="card-title fw-normal text-center mb-1" style="font-size: 0.9rem;">
                        Caramel Latte Coffee
                        </h6>
                        <p class="text-success fw-bold mb-0">$9.99</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2-4 product-item" data-category="Chocolate">
            <div class="card border-0 h-100">
                <div class="text-center p-2">
                    <img src="/views/assets/images/coffee.jpg" alt="Chocolate Coffee" class="img-fluid mb-2">
                    <div class="mt-2">
                        <h6 class="card-title fw-normal text-center mb-1" style="font-size: 0.9rem;">
                            Matchar
                        </h6>
                        <p class="text-success fw-bold mb-0">$9.99</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
