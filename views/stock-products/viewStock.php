<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $names = $_POST['name'];
    $prices = $_POST['price'];
    $quantities = $_POST['quantity'];
    $images = $_FILES['image'];

    for ($i = 0; $i < count($names); $i++) {
        $imagePath = $images['name'][$i] ? htmlspecialchars($images['name'][$i]) : 'No image';
        $message = "🆕 <b>New Product Added</b>\n" .
                   "Name: {$names[$i]}\n" .
                   "Price: \${$prices[$i]}\n" .
                   "Quantity: {$quantities[$i]}\n" .
                   "Image: {$imagePath}\n" .
                   "Timestamp: " . date('Y-m-d H:i:s');

        // Send to human Telegram chat
        $humanResult = sendTelegramMessage($message, '6461561884');
        
        // Send to chatbot group/channel
        $chatbotResult = sendTelegramMessage($message, '-1009876543210'); // Replace with your actual chatbot chat ID
        
        // Enhanced notification with detailed error info
        if ($chatbotResult && json_decode($chatbotResult, true)['ok']) {
            $_SESSION['notification'] = "Product '{$names[$i]}' added and notified via chatbot successfully!";
        } else {
            $errorResponse = $chatbotResult ? json_decode($chatbotResult, true) : ['description' => 'cURL execution failed'];
            $_SESSION['notification'] = "Product '{$names[$i]}' added but chatbot notification failed: " . $errorResponse['description'];
        }
    }
}

// Handle Update Stock
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productIds = $_POST['product_id'];
    $prices = $_POST['price'];
    $quantities = $_POST['quantity'];

    foreach ($productIds as $i => $id) {
        $productName = isset($products[$i]['name']) ? $products[$i]['name'] : "Product ID $id";
        $message = "🔄 <b>Product Updated</b>\n" .
                   "Name: {$productName}\n" .
                   "New Price: \${$prices[$i]}\n" .
                   "New Quantity: {$quantities[$i]}\n" .
                   "Product ID: {$id}\n" .
                   "Timestamp: " . date('Y-m-d H:i:s');

        // Send to human Telegram chat
        $humanResult = sendTelegramMessage($message, '6461561884');
        
        // Send to chatbot group/channel
        $chatbotResult = sendTelegramMessage($message, '-1009876543210'); // Replace with your actual chatbot chat ID
        
        // Enhanced notification with detailed error info
        if ($chatbotResult && json_decode($chatbotResult, true)['ok']) {
            $_SESSION['notification'] = "Product '{$productName}' updated and notified via chatbot successfully!";
        } else {
            $errorResponse = $chatbotResult ? json_decode($chatbotResult, true) : ['description' => 'cURL execution failed'];
            $_SESSION['notification'] = "Product '{$productName}' updated but chatbot notification failed: " . $errorResponse['description'];
        }
    }
}

// Enhanced Telegram message sending function with detailed debugging
function sendTelegramMessage($message, $chatId = '6461561884') {
    $botToken = '7898878636:AAFtwwPFcVSIi256SkNUaKitGDS5eaOhq1o'; // Replace with your actual bot token
    $url = "https://api.telegram.org/bot{$botToken}/sendMessage";

    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
    $verbose = fopen('php://temp', 'w+');
    curl_setopt($ch, CURLOPT_STDERR, $verbose);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($result === false) {
        $error = curl_error($ch);
        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);
        error_log("cURL Error for chat $chatId: $error (HTTP Code: $httpCode)\nVerbose: $verboseLog");
    } else {
        $response = json_decode($result, true);
        if (!$response['ok']) {
            rewind($verbose);
            $verboseLog = stream_get_contents($verbose);
            error_log("Telegram API Error for chat $chatId: " . $response['description'] . " (HTTP Code: $httpCode)\nVerbose: $verboseLog");
        } else {
            error_log("Telegram Message Sent Successfully to chat $chatId (HTTP Code: $httpCode)");
        }
    }

    fclose($verbose);
    curl_close($ch);
    return $result;
}
?>

<!-- Updated HTML to show test result -->
<div class="card">
  <div class="container">
    <?php
    // Display regular notification
    if (isset($_SESSION['notification'])) {
      $notification = $_SESSION['notification'];
      $alertClass = (stripos($notification, 'successfully') !== false) ? 'alert-success' : 'alert-warning';
      echo '<div class="alert ' . $alertClass . ' alert-dismissible fade show" role="alert">';
      echo $notification;
      echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
      echo '</div>';
      unset($_SESSION['notification']);
    }

    // Display test result
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

    <!-- Rest of your HTML remains unchanged -->
    <div class="header d-flex justify-content-between align-items-center my-4">
      <h1>Stock Products</h1>
    </div>

    <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
      <div class="notification-content" id="notificationContent"></div>
    </div>

    <div class="search-section mt-2">
      <div class="search-bar">
        <input type="text" class="form-control search-input" style='background:rgba(190, 190, 190, 0.11);' placeholder="Search products...">
      </div>
      <div class="action-buttons mt-2">
        <button class="btn btn-primary me-4" data-bs-toggle="modal" data-bs-target="#updateProductModal">
          <i class="bi bi-upload"></i> Existing
        </button>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
          <i class="bi bi-plus-circle"></i> New
        </button>
      </div>
    </div>
    <div class="products-section">
      <div class="products-grid">
        <?php
        usort($products, function ($a, $b) {
          return $a['quantity'] - $b['quantity'];
        });

        foreach ($products as $product) : ?>
          <div class="product-card <?= $product['quantity'] == 0 ? 'out-of-stock' : '' ?>"
            data-name="<?= htmlspecialchars(strtolower($product['name'])) ?>"
            data-price="<?= $product['price'] ?>"
            data-quantity="<?= $product['quantity'] ?>">
            <div class="dropdown">
              <a href="#" class="text-secondary bi-three-dots-vertical" data-bs-toggle="dropdown" aria-expanded="false" style="margin-right:10px;"></a>
              <ul class="dropdown-menu">
                <li class="edit"><a href="/edit_product?id=<?= $product['id'] ?>" class="edit-link bi-pencil"> Edit</a></li>
                <li><a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal" class="dropdown-item btn-delete bi-trash" onclick="setDeleteModal(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>')"> Delete</a></li>
              </ul>
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Delete Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete <strong id="productName"></strong>?
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

    <script>
      function setDeleteModal(productId, productName) {
        document.getElementById('productName').textContent = productName;
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `/delete_product/${productId}`;
      }
    </script>

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
                          <i class="bi bi-x"></i> Cancel
                        </button>
                      </div>
                    </div>
                    <div class="col-md-2 text-center">
                      <i class="bi bi-trash remove-entry" style="cursor: pointer; font-size: 1.5rem; color: #dc3545; display: none;" title="Remove"></i>
                    </div>
                  </div>
                </div>
              </div>
              <button type="button" class="btn btn-outline-primary mb-3" id="add-more-product">Add More</button>
              <button type="submit" class="btn btn-success" id="add-more-complet">Completed</button>
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
              <button type="button" class="btn btn-outline-primary mb-3" id="add-more">Add More</button>
              <button type="submit" class="btn btn-success" id="add-more-complet">Completed</button>
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
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
      const hasReceipt = <?php echo json_encode(isset($_SESSION['receipt'])); ?>;
      const showReceipt = new URLSearchParams(window.location.search).get('showReceipt') === 'true';
    </script>
  </div>
</div>