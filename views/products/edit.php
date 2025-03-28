<h3>Edit Product</h3>
<div class="container mt-3">
    <div class="card card2">
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

            <form action="/products/update/<?= htmlspecialchars($product['product_id']) ?>" method="post"
                enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($product['product_id']) ?>">
                <!-- CSRF Token -->
                <input type="hidden" name="_token"
                    value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? bin2hex(random_bytes(32))) ?>">

                    <label for="productImage">Product Image</label>
            <div class="image-upload-container">
                <input type="file" class="file-input" id="productImage" name="image_url" accept="image/*" required>
                <div class="image-preview-box" id="uploadBox" onclick="triggerFileInput()">
                    <div class="upload-placeholder" id="uploadPlaceholder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        <p>Upload Image</p>
                    </div>
                    <img id="imagePreview" src="#" alt="Product Image Preview" style="display: none; max-width: 100%; max-height: 100%;">
                </div>
            </div>

                <div class="form-group mb-3">
                    <label for="productName">Product Name</label>
                    <input type="text" class="form-control" id="productName" name="product_name"
                        value="<?= htmlspecialchars($product['product_name']) ?>" placeholder="Enter product name"
                        required>
                </div>

                <div class="form-group mb-3">
                    <label for="category">Category</label>
                    <select class="form-control" id="category" name="category" required>
                        <option value="coffee" <?= $product['category'] === 'coffee' ? 'selected' : '' ?>>Coffee
                        </option>
                        <option value="espresso" <?= $product['category'] === 'espresso' ? 'selected' : '' ?>>Espresso
                        </option>
                        <option value="matcha" <?= $product['category'] === 'matcha' ? 'selected' : '' ?>>Matcha
                        </option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" id="price" name="price"
                        value="<?= htmlspecialchars($product['price']) ?>" placeholder="Enter price" required
                        step="0.01" min="0">
                </div>

                <div class="form-group mb-3">
                    <label for="categoryId">Category ID</label>
                    <input type="number" class="form-control" id="categoryId" name="category_id"
                        value="<?= htmlspecialchars($product['category_id']) ?>" placeholder="Enter category ID"
                        required min="1">
                </div>

                <div class="btu-save-cancel d-flex gap-2">
                    <button type="submit" class="btn btn-primary m-2">Update Product</button>
                    <button type="button" class="btn btn-outline-secondary m-2"
                        onclick="window.location.href='/products'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Wait for the DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
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