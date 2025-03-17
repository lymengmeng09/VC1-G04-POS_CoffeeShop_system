
<h2>Add New Product</h2>
<div class="container mt-3">
    <form id="productForm" action="submit_product.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="productImage">Product Image</label>
            <input type="file" class="form-control-file" id="productImage" name="productImage">
        </div>
        <div class="form-group">
            <label for="productName">Product Name</label>
            <input type="text" class="form-control" id="productName" name="productName" placeholder="Enter product name">
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select class="form-control" id="category" name="category">
                <option value="electronics">Electronics</option>
                <option value="clothing">Clothing</option>
                <option value="home">Home</option>
            </select>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="0.00" step="0.01">
        </div>
        <button type="submit" class="btn btn-primary">Save Product</button>
        <button type="button" class="btn btn-secondary" onclick="resetForm()">Cancel</button>
    </form>
</div>

<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function resetForm() {
            document.getElementById("productForm").reset();
        }
    </script> -->