<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <div class="card p-4 shadow">
        <h2 class="text-center mb-4">Edit Product</h2>
        <form method="POST" action="/edit-product/<?= $product['id'] ?>">
            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" class="form-control" name="name" value="<?= $product['name'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Price:</label>
                <input type="number" class="form-control" name="price" value="<?= $product['price'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Quantity:</label>
                <input type="number" class="form-control" name="quantity" value="<?= $product['quantity'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Image URL:</label>
                <input type="text" class="form-control" name="image" value="<?= $product['image'] ?>">
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Update</button>

                <!-- Delete Button -->
                <form method="POST" action="/delete-product/<?= $product['id'] ?>">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                </form>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS (optional if needed) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
