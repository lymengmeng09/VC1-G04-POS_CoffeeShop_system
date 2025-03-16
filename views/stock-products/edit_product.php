<div class="container mt-5">
    <h1 class="mb-4">Edit Product</h1>
    <form action="/update_product?id=<?php echo $product['id']; ?>" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
        </div>
        
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="/viewStock" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>