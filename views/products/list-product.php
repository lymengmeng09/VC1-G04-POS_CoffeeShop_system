<h5>Product Management</h5>
<!-- Modified Search and Filter Section -->
<div class="card mb-2">
    <div class="card-body">
        <form id="filterForm" method="GET" class="nav">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search products..." name="search" id="searchInput">
                    <button class="btn" style="border:1px solid #D9D9D9;" type="submit">
                        <i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-8 d-flex align-items-center justify-content-end">
                <div class="btn-group me-2">
                    <button id="btnGroupDrop1" type="button" class="btn btn-outline-primary dropdown-toggle"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Category: <?= htmlspecialchars(ucfirst($_GET['category'] ?? 'All')) ?>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" id="categoryList">
                        <li><a class="dropdown-item" href="?category=all&category_id=<?= $_GET['category_id'] ?? 'all' ?>">All</a></li>
                        <li><a class="dropdown-item"
                                href="?category=ColdDrinks&category_id=<?= $_GET['category_id'] ?? 'all' ?>">Cold Drinks</a>
                        </li>
                        <li><a class="dropdown-item"
                                href="?category=Frappe&category_id=<?= $_GET['category_id'] ?? 'all' ?>">Frappe</a></li>
                        <li><a class="dropdown-item"
                                href="?category=HotDrinks&category_id=<?= $_GET['category_id'] ?? 'all' ?>">Hot Drinks</a>
                        </li>
                        <li><a class="dropdown-item"
                                href="?category=Smoothies&category_id=<?= $_GET['category_id'] ?? 'all' ?>">Smoothies</a></li>
                    </ul>
                </div>
                <button class="btn btn-primary text-light ms-2">
                    <a href="/products/create" class="text-light text-decoration-none"><i class="fas fa-plus me-2"></i> Create Menu</a>
                </button>
            </div>
        </form>
        <!-- Main Content Area -->
        <div class="container-fluid">
            <div class="row">
                <!-- Product Grid - Changes columns based on cart visibility -->
                <div id="product-grid" class="col-lg-<?= isset($_GET['cart']) ? '9' : '12' ?>">
                    <div class="row">
                        <?php foreach ($products as $product): ?>
                            <div class="col-6 mb-4 col-md-<?= isset($_GET['cart']) ? '4' : '3' ?> product-item" data-category="<?= strtolower(str_replace(' ', '', $product['category'])) ?>">
                                <div class="card h-100 pt-2" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 4px;">
                                    <div class="text-center">
                                        <!-- Dropdown for Edit/Delete -->
                                        <div class="dropstart text-end">
                                            <a href="#" class="text-secondary" data-bs-toggle="dropdown" aria-expanded="false" style="margin-right:10px;">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <!-- Edit Link -->
                                                <li class="edit"><a href="/products/edit/<?= htmlspecialchars($product['product_id']) ?>" class="edit-link bi-pencil"> Edit</a></li>
                                                <!-- Delete Button with Confirmation -->
                                                <li><button type="button" class="dropdown-item btn-delete bi-trash"
                                                        data-id="<?= htmlspecialchars($product['product_id']) ?>"
                                                        data-name="<?= htmlspecialchars($product['product_name']) ?>"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                        Delete
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- Product Image -->
                                        <div class="image-container">
                                            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" class="img-fluid mb-2 product-image">
                                        </div>
                                        <div class="mt-2">
                                            <h6 class="card-title text-center mb-1" style="font-size: 1.1em; font-weight:350; color: rgba(101, 67, 33, 0.9);">
                                                <strong><?= htmlspecialchars($product['product_name']) ?></strong>
                                            </h6>
                                            <p class="text-success fw-bold mb-0">
                                                $<?= number_format($product['price'], 2) ?>
                                            </p>
                                            <button class="btn-Order"
                                                data-id="<?= htmlspecialchars($product['product_id']) ?>"
                                                data-name="<?= htmlspecialchars($product['product_name']) ?>"
                                                data-price="<?= number_format($product['price'], 2) ?>"
                                                data-img="<?= htmlspecialchars($product['image_url']) ?>"
                                                data-category="<?= htmlspecialchars($product['category']) ?>">
                                                Order
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-lg-3" id="cart-section" style="display: <?= isset($_GET['cart']) ? 'block' : 'none' ?>;">
                    <!-- Cart Table (right side, initially hidden) -->
                    <div id="cart-table" style="display: none; padding: 0;">
                        <div class="card card-order sticky-top" style="top: 20px; width: 440px;">
                            <div class="card-body">
                                <h3>Bills</h3>
                                <table class="table">
                                    <tbody id="cart-table-body">
                                        <!-- Cart items will be added here dynamically -->
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-between total" id="btn">
                                    <div class="cart-total">Total: $<span id="cart-total">0.00</span></div>
                                    <div class="btn_cart">
                                        <button id="clear-all" class="btn btn-secondary">Cancel</button>
                                        <button id="PayMent" class="btn btn-primary">Pay Now</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <strong id="modalProductName"></strong>?
                    </div>
                    <div class="modal-footer">
                        <form id="deleteForm" method="POST">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger">Delete</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receipt Modal -->
        <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="receiptModalLabel">Order Receipt</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="receipt-content">
                        <!-- Receipt details will be inserted here dynamically -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="confirm-receipt">Confirm</button>
                    </div>
                </div>
            </div>
        </div>