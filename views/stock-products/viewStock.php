<div class="card">
    <?php
        if (isset($_SESSION['notification'])) {
            $notification = $_SESSION['notification'];
            $alertClass = (stripos($notification, 'successfully') !== false) ? 'alert-success' : 'alert-warning';
            echo '<div class="alert ' . $alertClass . ' alert-dismissible fade show" role="alert">';
            echo $notification;
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
            unset($_SESSION['notification']);
        }

        if (isset($_SESSION['test_result'])) {
            $testResult = $_SESSION['test_result'];
            $testClass = $testResult['ok'] ? 'alert-success' : 'alert-danger';
            echo '<div class="alert ' . $testClass . ' alert-dismissible fade show" role="alert">';
            echo 'Test Message Result: ' . ($testResult['ok'] ? 'Success' : 'Failed - ' . $testResult['description']);
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
            unset($_SESSION['test_result']);
        }
    ?>

    <div class="header d-flex justify-content-between align-items-center my-4">
        <h1><?php echo __('Stock Products'); ?></h1>
    </div>

    <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
        <div class="notification-content" id="notificationContent"></div>
    </div>

    <div class="search-section mt-2">
        <div class="search-bar">
            <input type="text" class="form-control search-input" style="background: rgba(190, 190, 190, 0.11);" placeholder="<?php echo __('search_products_placeholder'); ?>">
        </div>
        <div class="action-buttons mt-2">
            <button class="btn btn-primary me-4" data-bs-toggle="modal" data-bs-target="#updateProductModal">
                <i class="bi bi-upload"></i> <?php echo __('Existing'); ?>
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus-circle"></i> <?php echo __('New'); ?>
            </button>
        </div>
    </div>
    <div class="products-section container mt-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle table-bordered">
                <thead class="text-white">
                    <tr>
                        <th class="text-white"><?php echo __('products'); ?></th>
                        <th class="text-white"><?php echo __('price'); ?></th>
                        <th class="text-white"><?php echo __('stock'); ?></th>
                        <th class="text-white"><?php echo __('status'); ?></th>
                        <th class="text-white"><?php echo __('Action'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) : ?>
                    <tr>
                        <td class="d-flex align-items-center">
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="me-2" width="50" height="50">
                            <span class="fw-bold"> <?= htmlspecialchars($product['name']) ?> </span>
                        </td>
                        <td class="fw-semibold text-primary">$<?= number_format($product['price'], 2) ?></td>
                        <td class="fw-semibold text-center"> <?= $product['quantity'] ?> </td>
                        <td>
                            <?php if ($product['quantity'] == 0): ?>
                                <span class="badge bg-danger p-2"><?php echo __('Out of Stock'); ?></span>
                            <?php elseif ($product['quantity'] <= 5): ?>
                                <span class="badge bg-warning text-dark p-2"><?php echo __('Low In Stock'); ?></span>
                            <?php else: ?>
                                <span class="badge bg-success p-2"><?php echo __('In Stock'); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center m-l">
                            <div class="dropdown">
                                <a href="#" class="text-secondary bi-three-dots-vertical" data-bs-toggle="dropdown" aria-expanded="false" style="margin-right:10px;"></a>
                                <ul class="dropdown-menu" style="min-width: 100px;">
                                    <li class="edit"><a href="/edit_product?id=<?= $product['id'] ?>" class="edit-link bi-pencil"> <?php echo __('edit'); ?></a></li>
                                    <li>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal" class="dropdown-item btn-delete bi-trash" onclick="setDeleteModal(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>')">
                                            <?php echo __('delete'); ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel"><?php echo __('Delete Product'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php echo __('are_you_sure_delete'); ?> <strong id="productName"></strong>?
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

<!-- Add New Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel"><?php echo __('Add New Products'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/add-product" enctype="multipart/form-data" id="addProductForm">
                    <div id="add-product-entries">
                        <div class="product-entry mb-3">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label for="addName-0" class="form-label"><?php echo __('product_name'); ?></label>
                                    <input type="text" class="form-control" id="addName-0" name="name[]" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="addPrice-0" class="form-label"><?php echo __('price'); ?></label>
                                    <input type="number" step="0.01" class="form-control" id="addPrice-0" name="price[]" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="addQuantity-0" class="form-label"><?php echo __('Stock Quantity'); ?></label>
                                    <input type="number" class="form-control" id="addQuantity-0" name="quantity[]" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="addImage-0" class="form-label"><?php echo __('upload_image'); ?></label>
                                    <input type="file" class="form-control custom-file-input" id="addImage-0" name="image[]" accept="image/jpeg,image/png,image/gif" required>
                                    <div class="image-preview mt-2" id="preview-addImage-0" style="display: none;">
                                        <img src="" alt="Image Preview" style="max-width: 100px; max-height: 100px;">
                                        <button type="button" class="btn btn-sm btn-danger cancel-upload mt-1" data-input-id="addImage-0">
                                        <?php echo __('cancel'); ?>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <i class="bi bi-trash remove-entry" style="cursor: pointer; font-size: 1.5rem; color: #dc3545; display: none;" title="Remove"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-primary" id="add-more-product"><?php echo __('Add More'); ?></button>
                        <button type="submit" class="btn btn-success"><?php echo __('Completed'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Existing Product Modal -->
<div class="modal fade" id="updateProductModal" tabindex="-1" aria-labelledby="updateProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateProductModalLabel"><?php echo __('Update Existing Product'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/update-stock" id="updateProductForm">
                    <div id="product-entries">
                        <div class="product-entry mb-3">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label for="updateProduct-0" class="form-label"><?php echo __('Select Product'); ?></label>
                                    <select class="form-control update-product" id="updateProduct-0" name="product_id[]" required>
                                        <option value=""><?php echo __('Select a product'); ?>...</option>
                                        <?php foreach ($products as $product) : ?>
                                        <option value="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>">
                                            <?= htmlspecialchars($product['name']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="updatePrice-0" class="form-label"><?php echo __('price'); ?></label>
                                    <input type="number" step="0.01" class="form-control update-price" id="updatePrice-0" name="price[]" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="updateQuantity-0" class="form-label"><?php echo __('quantity'); ?></label>
                                    <input type="number" class="form-control update-quantity" id="updateQuantity-0" name="quantity[]" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="totalPrice-0" class="form-label"><?php echo __('Total Price'); ?></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="text" class="form-control total-price" id="totalPrice-0" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <i class="bi bi-trash remove-entry" style="cursor: pointer; font-size: 1.5rem; color: #dc3545; display: none;" title="Remove"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-primary" id="add-more"><?php echo __('Add More'); ?></button>
                        <button type="submit" class="btn btn-success"><?php echo __('Completed'); ?></button>
                    </div>
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
                <h5 class="modal-title text-white" id="receiptModalLabel">Recent Stock Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (isset($_SESSION['receipt'])): ?>
                    <p style="padding: 10px 20px; font-size: 16px; margin-right: 5%; color: black;">
                        <strong>Action: </strong><?= ucfirst($_SESSION['receipt']['action']) ?>
                    </p>
                    <div id="receipt-content">
                        <div class="header-recept">
                            <img src="/views/assets/images/logo.png" alt="Logo">
                            <h2>Stock Receipt</h2>
                        </div>

                        <!-- First Table: List each item -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Price($)</th>
                                    <th>Total($)</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                date_default_timezone_set('Asia/Phnom_Penh');
                                $totalPrice = 0;
                                foreach ($_SESSION['receipt']['items'] as $item):
                                    $changeQuantity = (float)str_replace('+', '', $item['change_quantity']);
                                    $itemTotal = $changeQuantity * (float)$item['price'];
                                    $totalPrice += $itemTotal;
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['name']) ?></td>
                                        <td><?= htmlspecialchars($item['change_quantity']) ?></td>
                                        <td><?= number_format($item['price'], 2) ?></td>
                                        <td class="total-cell"><?= number_format($itemTotal, 2) ?></td>
                                        <td><?= date('Y-m-d', strtotime($item['timestamp'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Second Table: Subtotal for each product -->
                        <?php
                        $productSubtotals = [];
                        foreach ($_SESSION['receipt']['items'] as $item) {
                            $name = $item['name'];
                            $quantity = (float)str_replace('+', '', $item['change_quantity']);
                            $price = (float)$item['price'];
                            $itemTotal = $quantity * $price;

                            if (!isset($productSubtotals[$name])) {
                                $productSubtotals[$name] = ['quantity' => 0, 'total' => 0];
                            }
                            $productSubtotals[$name]['quantity'] += $quantity;
                            $productSubtotals[$name]['total'] += $itemTotal;
                        }
                        ?>
                        
                        <div class="subtotoal-pro">
                            <h4 style="margin-top: 20px;">Subtotal</h4>
                            <table class="table-receipt">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Subtotal($)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($productSubtotals as $productName => $values): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($productName) ?></td>
                                            <td><?= number_format($values['total'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <!-- Footer total -->
                            <div class="footer-recept" style="margin-top: 8px;">
                                <table class="table">
                                    <tr>
                                        <td colspan="2"><strong>Total</strong></td>
                                        <td><strong>$<?= number_format($totalPrice, 2) ?></strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="seller-infor">
                            <!-- Add Font Awesome -->
                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

                            <div class="contact-info">
                            <div class="contact-item">
                                <div class="icon-circle black-bg">
                                <i class="fas fa-phone"></i>
                                </div>
                                <span class="text">081 369 639</span>
                            </div>

                            <div class="contact-item">
                                <div class="icon-circle border-only">
                                <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <span class="text">#1D, St. 371 (St. Sola)</span>
                            </div>
                            </div>

                        </div>
                         <h3>Thank you! </h3>
                    </div>
                <?php else: ?>
                    <p>No receipt available.</p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="save-pdf" style="padding: 10px 20px; font-size: 16px; margin-right: 5%;" onclick="savePDFAndRedirect()">Save PDF</button>
                <button type="button" class="btn btn-primary" id="ok-button" data-bs-dismiss="modal" style="padding: 10px 20px; font-size: 16px; margin-left: 45%;" onclick="ConceldRedirect()">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
function savePDFAndRedirect() {
    console.log("Saving PDF...");
    setTimeout(() => {
        window.location.href = "/viewStock";
    }, 400);
}

function ConceldRedirect() {
    console.log("Canceling and redirecting...");
    setTimeout(() => {
        window.location.href = "/viewStock";
    }, 200);
}

const hasReceipt = <?php echo json_encode(isset($_SESSION['receipt'])); ?>;
const showReceipt = new URLSearchParams(window.location.search).get('showReceipt') === 'true';
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
function setDeleteModal(productId, productName) {
    document.getElementById('productName').textContent = productName;
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/delete_product/${productId}`;
}
</script>