<div class="card ">
    <div class="card-body">

        
    <h2>Edit Product</h2>
<div class="container mt-3">
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

    <form action="/products/update/<?= htmlspecialchars($product['product_id']) ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= htmlspecialchars($product['product_id']) ?>">
        <!-- CSRF Token (if your framework doesn't handle this automatically) -->
        <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? bin2hex(random_bytes(32))) ?>">

        <div class="form-group">
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
        </div>

        <div class="form-group mb-3">
            <label for="productName">Product Name</label>
            <input type="text" class="form-control" id="productName" name="product_name" 
                   value="<?= htmlspecialchars($product['product_name']) ?>" placeholder="Enter product name" required>
        </div>

        <div class="form-group mb-3">
            <label for="category">Category</label>
            <select class="form-control" id="category" name="category" required>
                <option value="coffee" <?= $product['category'] === 'coffee' ? 'selected' : '' ?>>Coffee</option>
                <option value="espresso" <?= $product['category'] === 'espresso' ? 'selected' : '' ?>>Espresso</option>
                <option value="matcha" <?= $product['category'] === 'matcha' ? 'selected' : '' ?>>Matcha</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="price">Price</label>
            <input type="number" class="form-control" id="price" name="price" 
                   value="<?= htmlspecialchars($product['price']) ?>" placeholder="Enter price" required step="0.01" min="0">
        </div>
        <div class="btu-save-cancel d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='/products'">Cancel</button>
        </div>
    </form>
    
</div>
    </div>

</div>