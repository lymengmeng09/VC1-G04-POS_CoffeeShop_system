<h5><?php echo __('edit_product'); ?></h5>
<div class="card">
    <div class="card-body">
        <!-- Display success or error messages -->
        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
        <?php endif; ?>

        <form action="/products/update/<?= htmlspecialchars($product['product_id']) ?>" method="POST"
            enctype="multipart/form-data">
            <!-- Hidden fields -->
            <input type="hidden" name="id" value="<?= htmlspecialchars($product['product_id']) ?>">
            <!-- CSRF Token -->
            <input type="hidden" name="_token"
                value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? bin2hex(random_bytes(32))) ?>">

            <!-- Product Image -->
            <div class="form-group mb-3">
                <label for="productImage"><?php echo __('Product image'); ?></label>
                <div class="image-upload-container">
                    <input type="file" class="file-input" id="productImage" name="image_url" accept="image/*">
                    <div class="image-preview-box" id="uploadBox" onclick="triggerFileInput()">
                        <div class="upload-placeholder" id="uploadPlaceholder">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="#999" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            <p><?php echo __('upload_image'); ?></p>
                        </div>
                        <!-- Display existing image if available -->
                        <?php if (!empty($product['image_url'])): ?>

                        <img id="imagePreview" src="#" alt="Product Image Preview"
                            style="display: none; max-width: 100%; max-height: 100%;">
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Product Name -->
            <div class="form-group mb-3">
                <label for="productName"><?php echo __('product_name'); ?></label>
                <input type="text" class="form-control" id="productName" name="product_name"
                    value="<?= htmlspecialchars($product['product_name']) ?>" placeholder="Enter product name" required>
            </div>

            <!-- Category -->
            <div class="form-group mb-3">
                <label for="category"><?php echo __('category'); ?></label>
                <select class="form-control" id="category" name="category" required onchange="updateCategoryId(this)">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['category_name']) ?>" 
                                data-id="<?= htmlspecialchars($category['category_id']) ?>"
                                <?= $product['category_id'] === $category['category_name'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <!-- Hidden field for category_id -->
                <input type="hidden" name="category_id" id="categoryId" value="<?= htmlspecialchars($product['category_id']) ?>">
            </div>

            <!-- Price -->
            <div class="form-group mb-3">
                <label for="price"><?php echo __('price'); ?></label>
                <input type="number" class="form-control" id="price" name="price"
                    value="<?= htmlspecialchars($product['price']) ?>" placeholder="Enter price" required step="0.01"
                    min="0">
            </div>

            <!-- Buttons -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><?php echo __('save'); ?></button>
                <button type="button" class="btn btn-outline-secondary"
                    onclick="window.location.href='/products'"><?php echo __('cancel'); ?></button>
            </div>
        </form>
    </div>
</div>
<script>
    // Function to update the hidden category ID field when category changes
    function updateCategoryId(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        document.getElementById('categoryId').value = selectedOption.getAttribute('data-id');
    }
    
    // Wait for the DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize category ID
        const categorySelect = document.getElementById('category');
        updateCategoryId(categorySelect);
        
        // Get the file input element
        const fileInput = document.getElementById('productImage');
        const imagePreview = document.getElementById('imagePreview');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        
        // Add event listener for file selection
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Set the image source to the loaded data URL
                    imagePreview.src = e.target.result;
                    // Make the image visible
                    imagePreview.style.display = 'block';
                    // Hide the upload placeholder
                    uploadPlaceholder.style.display = 'none';
                };
                
                // Read the selected file as a data URL
                reader.readAsDataURL(this.files[0]);
            } else {
                // If no file is selected, hide the preview and show the placeholder
                imagePreview.style.display = 'none';
                uploadPlaceholder.style.display = 'flex';
                imagePreview.src = '#';
            }
        });
    });
    
    // Function to trigger the file input when clicking on the upload box
    function triggerFileInput() {
        document.getElementById('productImage').click();
    }
</script>