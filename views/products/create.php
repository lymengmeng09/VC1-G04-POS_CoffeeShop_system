<h2>Add New Product</h2>
<div class="container mt-3">
    <form action="/products/store" id="productForm" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="productImage">Product Image URL</label>
            <input type="file" class="form-control" id="productImage" name="image_url" placeholder="Enter image URL" required>
        </div>
        <div class="form-group">
            <label for="productName">Product Name</label>
            <input type="text" class="form-control" id="productName" name="product_name" placeholder="Enter product name" required>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select class="form-control" id="category" name="category" required>
                <option value="clothing">coffee</option>
                <option value="home">expresso</option>
                <option value="home">martcha</option>
            </select>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" required step="0.01">
        </div>
        <div class="form-group">
            <label for="categoryId">Category ID</label>
            <input type="number" class="form-control" id="categoryId" name="category_id" placeholder="Enter category ID" required>
        </div>
         <div class="btu-save-cancel">
             <button type="submit" class="btn btn-primary">Save Product</button>
             <button type="button" class="btn btn-secondary" onclick="resetForm()"><a href="/products">Cancel</a></button>
         </div>
    </form>
</div>

<!-- Optional JavaScript to Reset Form -->
<script>
    function resetForm() {
        document.getElementById("productForm").reset();
    }
</script>
