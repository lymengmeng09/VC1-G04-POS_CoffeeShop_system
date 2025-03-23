  <div class="container">
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
    ?>

    <div class="header d-flex justify-content-between align-items-center my-4">
      <h1>Stock Products</h1>
      <div class="user-icons">
        <div class="notification-icon" id="notificationIcon">
          <i class="fa fa-bell"></i>
          <span class="notification-count" id="notificationCount" style="display: none;">0</span>
        </div>
    </div>

    <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
        <div class="notification-content" id="notificationContent"></div>
    </div>

    <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
        <div class="notification-content" id="notificationContent"></div>
    </div>

    <div class="search-section mb-4">
        <div class="search-bar">
            <input type="text" class="form-control search-input" placeholder="Search products...">
        </div>
        <div class="action-buttons mt-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateProductModal">+ Existing Product</button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">+ New Product</button>
        </div>
    </div>

    <div class="products-section">
      <h2 class="section-title"></h2>
      <div class="products-grid">
        <?php 
        // Sort products by quantity in ascending order (lowest to highest)
        usort($products, function($a, $b) {
            return $a['quantity'] - $b['quantity'];
        });
        
        foreach ($products as $product) : ?>
          <div class="product-card <?= $product['quantity'] == 0 ? 'out-of-stock' : '' ?>" 
               data-name="<?= htmlspecialchars(strtolower($product['name'])) ?>" 
               data-price="<?= $product['price'] ?>" 
               data-quantity="<?= $product['quantity'] ?>">
            <div class="dropdown">
              <button class="dropbtn">⋮</button>
              <div class="dropdown-content">
                <a href="/edit_product?id=<?= $product['id'] ?>">Edit</a>
                <a href="/delete_product/<?= $product['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
              </div>
            </div>
            <div class="product-image">
              <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
            <div class="product-info">
              <h3 class="origin"><?= htmlspecialchars($product['name']) ?></h3>
              <p class="price">Price: $<?= number_format($product['price'], 2) ?></p>
              <p class="quantity">Quantity: <?= $product['quantity'] ?> <?= $product['quantity'] == 0 ? '(Out of Stock)' : '' ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  <!-- Add New Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addProductModalLabel">Add New Product(s)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="/add-product" enctype="multipart/form-data" id="addProductForm">
          <div id="add-product-entries">
            <div class="product-entry mb-3">
              <h6>Product 1</h6>
              <div class="row g-3 align-items-end">
                <div class="col-md-3">
                  <label for="addName-0" class="form-label">Product Name</label>
                  <input type="text" class="form-control" id="addName-0" name="name[]" required>
                </div>
                <div class="col-md-2">
                  <label for="addPrice-0" class="form-label">Price</label>
                  <input type="number" step="0.01" class="form-control" id="addPrice-0" name="price[]" required>
                </div>
                <div class="col-md-2">
                  <label for="addQuantity-0" class="form-label">Stock Quantity</label>
                  <input type="number" class="form-control" id="addQuantity-0" name="quantity[]" required>
                </div>
                <div class="col-md-3">
    <label for="addImage-0" class="form-label">Upload Image</label>
    <input type="file" class="form-control custom-file-input" id="addImage-0" name="image[]" accept="image/jpeg,image/png,image/gif" required>
    <div class="image-preview mt-2" id="preview-addImage-0" style="display: none;">
      <img src="" alt="Image Preview" style="max-width: 100px; max-height: 100px;">
      <button type="button" class="btn btn-sm btn-danger cancel-upload mt-1" data-input-id="addImage-0">
     </i>  cancel </button></div>
  </div>
                <div class="col-md-2 text-center">
                  <i class="bi bi-trash remove-entry" style="cursor: pointer; font-size: 1.5rem; color: #dc3545; display: none;" title="Remove"></i>
                </div>
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-secondary mb-3 " id="add-more-product">Add Another Product</button>
          <button type="submit" class="btn btn-primary">Complete</button>
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
        <h5 class="modal-title" id="updateProductModalLabel">Update Existing Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="/update-stock" id="updateProductForm">
          <div id="product-entries">
            <div class="product-entry mb-3">
              <div class="row g-3 align-items-end">
                <div class="col-md-3">
                  <label for="updateProduct-0" class="form-label">Select Product</label>
                  <select class="form-control update-product" id="updateProduct-0" name="product_id[]" required>
                    <option value="">Select a product...</option>
                    <?php foreach ($products as $product) : ?>
                      <option value="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>">
                        <?= htmlspecialchars($product['name']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <label for="updatePrice-0" class="form-label">Price</label>
                  <input type="number" step="0.01" class="form-control update-price" id="updatePrice-0" name="price[]" required>
                </div>
                <div class="col-md-2">
                  <label for="updateQuantity-0" class="form-label">Quantity</label>
                  <input type="number" class="form-control update-quantity" id="updateQuantity-0" name="quantity[]" required>
                </div>
                <div class="col-md-2">
                  <label for="totalPrice-0" class="form-label">Total Price</label>
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
          <button type="button" class="btn btn-secondary mb-3" id="add-more">Add More</button>
          <button type="submit" class="btn btn-success">Update All</button>
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
                <h5 class="modal-title" id="receiptModalLabel">Recent Stock Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (isset($_SESSION['receipt'])): ?>
                    <p><strong>Action: </strong><?= ucfirst($_SESSION['receipt']['action']) ?></p>
                    <div id="receipt-content">
                        <div class="header-recept">
                            <img src="/views/assets/images/logo.png" alt="Logo">
                            <h2>Stock Receipt</h2>
                        </div>
                    </div>
                    <div class="product-image">
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    </div>
                    <div class="product-info">
                        <h3 class="origin"><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="price">Price: $<?= number_format($product['price'], 2) ?></p>
                        <p class="quantity">Quantity: <?= $product['quantity'] ?> <?= $product['quantity'] == 0 ? '(Out of Stock)' : '' ?></p>
                    </div>
                </div>
   
        </div>
    </div>

    <!-- Add New Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product(s)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/add-product" enctype="multipart/form-data" id="addProductForm">
                        <div id="add-product-entries">
                            <div class="product-entry mb-3">
                                <h6>Product 1</h6>
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label for="addName-0" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="addName-0" name="name[]" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="addPrice-0" class="form-label">Price</label>
                                        <input type="number" step="0.01" class="form-control" id="addPrice-0" name="price[]" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="addQuantity-0" class="form-label">Stock Quantity</label>
                                        <input type="number" class="form-control" id="addQuantity-0" name="quantity[]" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="addImage-0" class="form-label">Upload Image</label>
                                        <input type="file" class="form-control custom-file-input" id="addImage-0" name="image[]" accept="image/jpeg,image/png,image/gif" required>
                                        <div class="image-preview mt-2" id="preview-addImage-0" style="display: none;">
                                            <img src="" alt="Image Preview" style="max-width: 100px; max-height: 100px;">
                                            <button type="button" class="btn btn-sm btn-danger cancel-upload mt-1" data-input-id="addImage-0">Cancel</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <i class="bi bi-trash remove-entry" style="cursor: pointer; font-size: 1.5rem; color: #dc3545; display: none;" title="Remove"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary mb-3" id="add-more-product">Add Another Product</button>
                        <button type="submit" class="btn btn-primary">Complete</button>
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
                    <h5 class="modal-title" id="updateProductModalLabel">Update Existing Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/update-stock" id="updateProductForm">
                        <div id="product-entries">
                            <div class="product-entry mb-3">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label for="updateProduct-0" class="form-label">Select Product</label>
                                        <select class="form-control update-product" id="updateProduct-0" name="product_id[]" required>
                                            <option value="">Select a product...</option>
                                            <?php foreach ($products as $product) : ?>
                                                <option value="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>">
                                                    <?= htmlspecialchars($product['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="updatePrice-0" class="form-label">Price</label>
                                        <input type="number" step="0.01" class="form-control update-price" id="updatePrice-0" name="price[]" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="updateQuantity-0" class="form-label">Quantity</label>
                                        <input type="number" class="form-control update-quantity" id="updateQuantity-0" name="quantity[]" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="totalPrice-0" class="form-label">Total Price</label>
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
                        <button type="button" class="btn btn-secondary mb-3" id="add-more">Add More</button>
                        <button type="submit" class="btn btn-success">Update All</button>
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
                    <h5 class="modal-title" id="receiptModalLabel">Recent Stock Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (isset($_SESSION['receipt'])): ?>
                        <p><strong>Action: </strong><?= ucfirst($_SESSION['receipt']['action']) ?></p>
                        <div id="receipt-content">
                            <div class="header-recept">
                                <img src="/views/assets/images/logo.png" alt="Logo">
                                <h2>Stock Receipt</h2>
                            </div>
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
                                    <tr>
                                        <td colspan="2"><strong>Total Price</strong></td>
                                        <td colspan="2"><strong>$<?= number_format($totalPrice, 2) ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p>No receipt available.</p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="save-pdf">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Styles -->
    <style>
    .notification-dropdown {
        position: absolute;
        right: 0;
        top: 60px;
        width: 350px;
        max-height: 400px;
        overflow-y: auto;
        background: white;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        z-index: 1000;
    }
    .notification-item {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }
    .notification-item.unread {
        background-color: #f8f9fa;
        font-weight: 500;
    }
    .notification-actions {
        margin-top: 5px;
    }
    .notification-actions button {
        margin-right: 5px;
    }
    .notification-count {
        position: absolute;
        top: -5px;
        right: -5px;
        background: red;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 12px;
    }
    </style>

    <!-- JavaScript -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const notificationIcon = document.getElementById('notificationIcon');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationContent = document.getElementById('notificationContent');
        const notificationCount = document.getElementById('notificationCount');
        const addProductForm = document.getElementById('addProductForm');
        const updateProductForm = document.getElementById('updateProductForm');
        const hasReceipt = <?php echo json_encode(isset($_SESSION['receipt'])); ?>;
        const showReceipt = new URLSearchParams(window.location.search).get('showReceipt') === 'true';

        // Toggle notification dropdown
        notificationIcon.addEventListener('click', function() {
            notificationDropdown.style.display = 
                notificationDropdown.style.display === 'none' ? 'block' : 'none';
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!notificationIcon.contains(event.target) && !notificationDropdown.contains(event.target)) {
                notificationDropdown.style.display = 'none';
            }
        });

        // Show receipt modal if applicable
        if (hasReceipt && showReceipt) {
            const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
            receiptModal.show();
        }

        // Fetch notifications dynamically
        function fetchNotifications() {
            fetch('/get-notifications')
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    updateNotifications(data.notifications, data.unreadCount);
                })
                .catch(error => console.error('Error fetching notifications:', error));
        }

        // Update notification UI
        function updateNotifications(notifications, unreadCount) {
            notificationContent.innerHTML = '';
            if (!notifications || notifications.length === 0) {
                notificationContent.innerHTML = '<div class="notification-item">No notifications available.</div>';
            } else {
                notifications.forEach(notification => {
                    const item = document.createElement('div');
                    item.className = `notification-item ${notification.is_read ? 'read' : 'unread'}`;
                    item.dataset.id = notification.notification_id;
                    item.innerHTML = `
                        <div class="notification-text">
                            <strong>${notification.notification_type.replace('_', ' ').split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')}:</strong>
                            ${notification.message}
                            <small>${new Date(notification.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</small>
                        </div>
                        <div class="notification-actions">
                            ${!notification.is_read ? `
                                <button class="btn btn-sm btn-primary mark-read" data-id="${notification.notification_id}">Mark as Read</button>
                            ` : ''}
                            <button class="btn btn-sm btn-danger delete-notification" data-id="${notification.notification_id}">Delete</button>
                        </div>
                    `;
                    notificationContent.appendChild(item);
                });

                // Add event listeners for mark as read and delete
                document.querySelectorAll('.mark-read').forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.dataset.id;
                        handleNotificationAction('mark_read', id);
                    });
                });
                document.querySelectorAll('.delete-notification').forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.dataset.id;
                        handleNotificationAction('delete', id);
                    });
                });
            }
            notificationCount.textContent = unreadCount;
            notificationCount.style.display = unreadCount > 0 ? 'inline' : 'none';
        }

        // Handle notification actions (mark as read, delete)
        function handleNotificationAction(action, notificationId) {
            fetch('/handleNotification', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=${action}&notification_id=${notificationId}` // Fixed typo: ¬ification_id to notification_id
            })
            .then(response => {
                if (!response.ok) throw new Error('Action failed');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    fetchNotifications();
                } else {
                    console.error('Failed to handle notification:', data.message);
                }
            })
            .catch(error => console.error('Error handling notification:', error));
        }

        // Handle form submissions with AJAX
        function handleFormSubmission(form, callback) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validate form inputs
                const productIds = form.querySelectorAll('select[name="product_id[]"]');
                const prices = form.querySelectorAll('input[name="price[]"]');
                const quantities = form.querySelectorAll('input[name="quantity[]"]');

                for (let i = 0; i < productIds.length; i++) {
                    if (!productIds[i].value || !prices[i].value || !quantities[i].value) {
                        alert('Please fill all fields for each product.');
                        return;
                    }
                }

                const formData = new FormData(form);
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }

                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Raw response:', response);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Parsed response:', data);
                    if (data.success) {
                        fetchNotifications();
                        callback();
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error submitting form:', error);
                    alert('An error occurred while submitting the form.');
                });
        });
    }

    // Apply to both forms
    handleFormSubmission(addProductForm, () => {
        bootstrap.Modal.getInstance(document.getElementById('addProductModal')).hide();
        fetch('/viewStock?showReceipt=true').then(() => {
            const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
            receiptModal.show();
        });
    });

    handleFormSubmission(updateProductForm, () => {
        bootstrap.Modal.getInstance(document.getElementById('updateProductModal')).hide();
        fetch('/viewStock?showReceipt=true').then(() => {
            const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
            receiptModal.show();
        });
    });

    // Poll for notifications every 5 seconds
    setInterval(fetchNotifications, 5000);

    // Initial fetch
    fetchNotifications();

    // Existing receipt and datetime logic
    updateTotals();
    showCurrentDateTime();

    // Dynamic updates for the update product modal
    document.querySelectorAll('.update-product').forEach((select, index) => {
        select.addEventListener('change', function() {
            const priceInput = document.getElementById(`updatePrice-${index}`);
            const quantityInput = document.getElementById(`updateQuantity-${index}`);
            const totalPriceInput = document.getElementById(`totalPrice-${index}`);
            const selectedOption = this.options[this.selectedIndex];
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            priceInput.value = price.toFixed(2);
            updateTotalPrice(index);
        });
    });

    document.querySelectorAll('.update-quantity').forEach((input, index) => {
        input.addEventListener('input', () => updateTotalPrice(index));
    });

    document.querySelectorAll('.update-price').forEach((input, index) => {
        input.addEventListener('input', () => updateTotalPrice(index));
    });

    function updateTotalPrice(index) {
        const price = parseFloat(document.getElementById(`updatePrice-${index}`).value) || 0;
        const quantity = parseFloat(document.getElementById(`updateQuantity-${index}`).value) || 0;
        const total = price * quantity;
        document.getElementById(`totalPrice-${index}`).value = total.toFixed(2);
    }

    // Add more product entries in update modal
    document.getElementById('add-more').addEventListener('click', function() {
        const entries = document.getElementById('product-entries');
        const index = entries.children.length;
        const entry = document.createElement('div');
        entry.className = 'product-entry mb-3';
        entry.innerHTML = `
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="updateProduct-${index}" class="form-label">Select Product</label>
                    <select class="form-control update-product" id="updateProduct-${index}" name="product_id[]" required>
                        <option value="">Select a product...</option>
                        <?php foreach ($products as $product) : ?>
                            <option value="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>">
                                <?= htmlspecialchars($product['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="updatePrice-${index}" class="form-label">Price</label>
                    <input type="number" step="0.01" class="form-control update-price" id="updatePrice-${index}" name="price[]" required>
                </div>
                <div class="col-md-2">
                    <label for="updateQuantity-${index}" class="form-label">Quantity</label>
                    <input type="number" class="form-control update-quantity" id="updateQuantity-${index}" name="quantity[]" required>
                </div>
                <div class="col-md-2">
                    <label for="totalPrice-${index}" class="form-label">Total Price</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="text" class="form-control total-price" id="totalPrice-${index}" readonly>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <i class="bi bi-trash remove-entry" style="cursor: pointer; font-size: 1.5rem; color: #dc3545;" title="Remove"></i>
                </div>
            </div>
        `;
        entries.appendChild(entry);

        // Add event listeners to new inputs
        const newSelect = entry.querySelector(`#updateProduct-${index}`);
        const newPrice = entry.querySelector(`#updatePrice-${index}`);
        const newQuantity = entry.querySelector(`#updateQuantity-${index}`);
        newSelect.addEventListener('change', () => {
            const price = parseFloat(newSelect.options[newSelect.selectedIndex].getAttribute('data-price')) || 0;
            newPrice.value = price.toFixed(2);
            updateTotalPrice(index);
        });
        newPrice.addEventListener('input', () => updateTotalPrice(index));
        newQuantity.addEventListener('input', () => updateTotalPrice(index));

        // Remove entry
        entry.querySelector('.remove-entry').addEventListener('click', () => {
            entry.remove();
        });
    });

    // Add more product entries in add modal
    document.getElementById('add-more-product').addEventListener('click', function() {
        const entries = document.getElementById('add-product-entries');
        const index = entries.children.length;
        const entry = document.createElement('div');
        entry.className = 'product-entry mb-3';
        entry.innerHTML = `
            <h6>Product ${index + 1}</h6>
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="addName-${index}" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="addName-${index}" name="name[]" required>
                </div>
                <div class="col-md-2">
                    <label for="addPrice-${index}" class="form-label">Price</label>
                    <input type="number" step="0.01" class="form-control" id="addPrice-${index}" name="price[]" required>
                </div>
                <div class="col-md-2">
                    <label for="addQuantity-${index}" class="form-label">Stock Quantity</label>
                    <input type="number" class="form-control" id="addQuantity-${index}" name="quantity[]" required>
                </div>
                <div class="col-md-3">
                    <label for="addImage-${index}" class="form-label">Upload Image</label>
                    <input type="file" class="form-control custom-file-input" id="addImage-${index}" name="image[]" accept="image/jpeg,image/png,image/gif" required>
                    <div class="image-preview mt-2" id="preview-addImage-${index}" style="display: none;">
                        <img src="" alt="Image Preview" style="max-width: 100px; max-height: 100px;">
                        <button type="button" class="btn btn-sm btn-danger cancel-upload mt-1" data-input-id="addImage-${index}">Cancel</button>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <i class="bi bi-trash remove-entry" style="cursor: pointer; font-size: 1.5rem; color: #dc3545;" title="Remove"></i>
                </div>
            </div>
        `;
        entries.appendChild(entry);

        // Image preview for new entry
        const fileInput = entry.querySelector(`#addImage-${index}`);
        fileInput.addEventListener('change', function() {
            const preview = document.getElementById(`preview-addImage-${index}`);
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.querySelector('img').src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Cancel upload for new entry
        entry.querySelector('.cancel-upload').addEventListener('click', function() {
            const inputId = this.getAttribute('data-input-id');
            const input = document.getElementById(inputId);
            const preview = document.getElementById(`preview-${inputId}`);
            input.value = '';
            preview.style.display = 'none';
        });

        // Remove entry
        entry.querySelector('.remove-entry').addEventListener('click', () => {
            entry.remove();
        });
    });

    // Initial image preview setup
    document.querySelectorAll('.custom-file-input').forEach(input => {
        input.addEventListener('change', function() {
            const preview = document.getElementById(`preview-${this.id}`);
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.querySelector('img').src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    });

    document.querySelectorAll('.cancel-upload').forEach(button => {
        button.addEventListener('click', function() {
            const inputId = this.getAttribute('data-input-id');
            const input = document.getElementById(inputId);
            const preview = document.getElementById(`preview-${inputId}`);
            input.value = '';
            preview.style.display = 'none';
        });
    });
});

function updateTotals() {
    let totalSum = 0;
    document.querySelectorAll(".total-cell").forEach(cell => {
        let rowTotal = parseFloat(cell.textContent.replace(',', ''));
        if (!isNaN(rowTotal)) {
            totalSum += rowTotal;
        }
    });
}

function showCurrentDateTime() {
    function updateDateTime() {
        let now = new Date();
        let options = { 
            weekday: 'long', year: 'numeric', month: '2-digit', day: '2-digit',
            hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false,
            timeZone: 'Asia/Phnom_Penh'
        };
        let formattedDateTime = new Intl.DateTimeFormat('en-US', options).format(now);
        let dateTimeElement = document.getElementById("current-datetime");
        if (dateTimeElement) {
            dateTimeElement.textContent = formattedDateTime;
        }
    }
    updateDateTime();
    setInterval(updateDateTime, 1000);
}
</script>

 

  <!-- Inline script to pass PHP data to JavaScript -->
  <script>
    // Pass PHP variables to JavaScript
    const hasReceipt = <?php echo json_encode(isset($_SESSION['receipt'])); ?>;
    const showReceipt = new URLSearchParams(window.location.search).get('showReceipt') === 'true';
  </script>
 
</body>
</html>         