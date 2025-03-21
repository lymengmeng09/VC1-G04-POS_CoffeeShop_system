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
                            <option value="<?php echo htmlspecialchars($category); ?>" <?php echo (isset($_GET['categories']) && $_GET['categories'] == $category) ? 'selected' : ''; ?>>
                                <option value=""><?= htmlspecialchars($user['category_name']) ?></option>
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
            <!-- Product Section -->
            <div class="row g-4 coffee-grid">
                <?php foreach ($products as $index => $product): ?>
                    <div class="col-6 col-md-3 product-item" data-category="Coffee">
                        <div class="card border-0 h-100">
                            <div class="text-center p-2">
                                <div class="product-entry">
                                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" class="img-fluid mb-2">
                                    <div class="mt-2">
                                        <h6 class="card-title fw-normal text-center mb-1" style="font-size: 0.9rem;">
                                            <?= htmlspecialchars($product['product_name']) ?>
                                        </h6>
                                        <p class="text-success fw-bold mb-0">
                                            $<?= number_format($product['price'], 2) ?>
                                        </p>
                                        <button class="btn-Order" data-name="<?= htmlspecialchars($product['product_name']) ?>" data-price="<?= number_format($product['price'], 2) ?>" data-img="<?= htmlspecialchars($product['image_url']) ?>">
                                            Order New
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

                <div class="d-flex justify-content-between" id="btn">
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

 