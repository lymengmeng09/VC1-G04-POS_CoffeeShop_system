<h5><?php echo __('product_management'); ?></h5>
<!-- Modified Search and Filter Section -->
<div class="card mb-2">
    <div class="card-body">
        <form id="filterForm" method="GET" class="nav">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="<?php echo __('search_products_placeholder'); ?>" name="search" id="searchInput">
                    <button class="btn" style="border:1px solid #D9D9D9;" type="submit">
                        <i class="bi bi-search"></i></button>
                </div>
            </div>
            <div class="col-md-8 d-flex align-items-center justify-content-end">
                <!-- Modify the category dropdown section -->
                <div class="btn-group cofe-category me-2">
                    <button id="drop2" type="button" class="btn btn-outline-primary dropdown-toggle"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        if ($selected_category === 'all') {
                            echo __('category');
                        } else {
                            // Find the selected category name
                            $selected_name = __('category');
                            foreach ($categories as $cat) {
                                if ($cat['category_id'] == $selected_category) {
                                    $selected_name = $cat['category_name'];
                                    break;
                                }
                            }
                            echo htmlspecialchars($selected_name);
                        }
                        ?>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="drop2" id="categoryList">
                        <li>
                            <a class="dropdown-item cofe <?= ($selected_category === 'all') ? 'active' : '' ?>"
                                href="?category=all"><?php echo __('all'); ?></a>
                        </li>
                        <?php foreach ($categories as $category): ?>
                            <li>
                                <a class="dropdown-item cofe <?= ($selected_category == $category['category_id']) ? 'active' : '' ?>"
                                    href="?category=<?= $category['category_id'] ?>">
                                    <?= htmlspecialchars($category['category_name']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <button class="btn btn-primary text-light ms-2">
                    <a href="/products/create" class="text-light text-decoration-none"><i class="fas fa-plus me-2"></i> <?php echo __('create_menu'); ?></a>
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
                            <div class="col-6 mb-4 col-md-<?= isset($_GET['cart']) ? '4' : '3' ?> product-item"
                                data-category="<?= strtolower(str_replace(' ', '', $product['category_name'])) ?>">
                                <div class="card h-100 pt-2" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 4px;">
                                    <div class="text-center">
                                        <!-- Dropdown for Edit/Delete -->
                                        <div class="dropstart text-end">
                                            <a href="#" class="text-secondary" data-bs-toggle="dropdown" aria-expanded="false" style="margin-right:10px;">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </a>
                                            <ul class="dropdown-menu" style="min-width: 120px; padding: 0.5rem 0;">
                                                <!-- Edit Link -->
                                                <li class="edit">
                                                    <a href="/products/edit/<?= htmlspecialchars($product['product_id']) ?>" class="edit-link">
                                                        <i class="bi bi-pencil"></i>
                                                        <span><?php echo __('edit'); ?></span>
                                                    </a>
                                                </li>
                                                <!-- Delete Button with Confirmation -->
                                                <li>
                                                    <button type="button" class="dropdown-item btn-delete"
                                                        data-id="<?= htmlspecialchars($product['product_id']) ?>"
                                                        data-name="<?= htmlspecialchars($product['product_name']) ?>"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                        <i class="bi bi-trash"></i>
                                                        <span><?php echo __('delete'); ?></span>
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
                                            <p style="color: gray; font-size:small"><?= htmlspecialchars($product['category_name']) ?></p>
                                            <button class="btn-Order"
                                                data-id="<?= htmlspecialchars($product['product_id']) ?>"
                                                data-name="<?= htmlspecialchars($product['product_name']) ?>"
                                                data-price="<?= number_format($product['price'], 2) ?>"
                                                data-img="<?= htmlspecialchars($product['image_url']) ?>"
                                                data-category="<?= htmlspecialchars($product['category_id']) ?>">
                                                <?php echo __('order'); ?>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-lg-3" id="cart-section" style="display: <?= isset($_GET['cart']) ? 'block' : 'none' ?>;">
                    <div id="cart-sticky-wrapper">
                        <!-- Cart Table (right side, fixed position) -->
                        <div id="cart-table" style="display: none; padding: 0;">
                            <div class="card card-order" style="width: 450px;">
                                <div class="card-body">
                                    <h3><?php echo __('bills'); ?></h3>
                                    <table class="table">
                                        <tbody id="cart-table-body">
                                            <!-- Cart items will be added here dynamically -->
                                        </tbody>
                                    </table>
                                    <div class="total-container mb-3">
                                        <div class="d-flex justify-content-between align-items-center" id="btn">
                                            <div class="cart-label"><?php echo __('total'); ?>: $ <span id="cart-total">0.00</span></div>
                                            <div class="pay">
                                                <input type="radio" name="payment" value="aba" id="payment-aba"> ABA
                                                <input type="radio" name="payment" value="cash" class="ms-3" id="payment-cash"> Cash
                                            </div>
                                        </div>
                                        <div class="btn_cart d-flex justify-content-between mt-2">
                                            <button id="clear-all" class="btn btn-outline-secondary btn-sm"><?php echo __('cancel'); ?></button>
                                            <button id="PayMent" class="btn btn-primary btn-sm"><?php echo __('pay_now'); ?></button>
                                            <button id="check-btn" class="btn btn-success btn-sm">check</button>
                                        </div>
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
                        <h5 class="modal-title" id="deleteModalLabel"><?php echo __('delete_confirmation'); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php echo __('are_you_sure_delete'); ?> <strong id="modalProductName"></strong>?
                    </div>
                    <div class="modal-footer">
                        <form id="deleteForm" method="POST">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger"><?php echo __('delete'); ?></button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?php echo __('cancel'); ?></button>
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
                        <h5 class="modal-title" id="receiptModalLabel"><?php echo __('order_receipt'); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="receipt-content">
                        <!-- Receipt details will be inserted here dynamically -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="confirm-receipt">Confirm</button>
                        <button type="button" class="btn btn-primary" id="ok-button" data-bs-dismiss="modal" style="padding: 10px 20px; font-size: 16px; margin-left: 45%;">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- QR Code Modal -->
<!-- QR Code Modal -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrCodeModalLabel"><?php echo __('scan_qr_code'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="qrimg d-flex justify-content-center">
                    <img id="qrCodeImage" src="views/assets/images/us.JPG" alt="USD QR Code" class="img-fluid" style="width: 400px;">
                </div>
                
                <div class="mb-4 d-flex justify-content-center">
                    <div class="form-check form-check-inline">
                        <input class="currency-radio" type="radio" name="currency" id="currency-usd" value="usd" checked> US
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="currency-radio" type="radio" name="currency" id="currency-khr" value="khr"> KHR
                    </div>
                </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?php echo __('close'); ?></button>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('check-btn').addEventListener('click', function () {
    // Check if the "aba" radio button is selected
    const abaRadio = document.getElementById('payment-aba');
    if (abaRadio.checked) {
        // Reset to USD QR code when opening the modal
        const qrCodeImage = document.getElementById('qrCodeImage');
        qrCodeImage.src = 'views/assets/images/us.JPG';
        qrCodeImage.alt = 'USD QR Code';
        document.getElementById('currency-usd').checked = true;

        // Show the QR code modal
        const qrCodeModal = new bootstrap.Modal(document.getElementById('qrCodeModal'));
        qrCodeModal.show();
    } else {
        // Alert if ABA is not selected
        alert('Please select ABA payment method to view the QR code.');
    }
});

// Handle currency selection
document.querySelectorAll('.currency-radio').forEach(radio => {
    radio.addEventListener('change', function () {
        const currency = this.value;
        const qrCodeImage = document.getElementById('qrCodeImage');

        // Update QR code image based on currency
        if (currency === 'usd') {
            qrCodeImage.src = 'views/assets/images/us.JPG';
            qrCodeImage.alt = 'USD QR Code';
        } else if (currency === 'khr') {
            qrCodeImage.src = 'views/assets/images/kh.JPG';
            qrCodeImage.alt = 'KHR QR Code';
        }
    });
});
</script>