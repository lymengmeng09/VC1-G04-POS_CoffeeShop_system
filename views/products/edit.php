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

        <div class="form-group mb-3">
            <label for="productImage">Product Image</label>
            <!-- Show current image if exists -->
            <?php if (!empty($product['image_url'])): ?>
                <div class="mb-2">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="Current product image" style="max-width: 200px; border-radius: 5px;">
                </div>
            <?php else: ?>
                <div class="mb-2 text-muted">
                    No image uploaded.
                </div>
            <?php endif; ?>
            <input type="file" class="form-control" id="productImage" name="image_url" accept="image/jpeg,image/png,image/gif">
            <small class="form-text text-muted">Leave blank to keep the current image. Accepted formats: JPG, PNG, GIF.</small>
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

        <div class="form-group mb-3">
            <label for="categoryId">Category ID</label>
            <input type="number" class="form-control" id="categoryId" name="category_id" 
                   value="<?= htmlspecialchars($product['category_id']) ?>" placeholder="Enter category ID" required min="1">
        </div>

        <div class="btu-save-cancel d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update Product</button>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='/products'">Cancel</button>
        </div>
    </form>
</div>  