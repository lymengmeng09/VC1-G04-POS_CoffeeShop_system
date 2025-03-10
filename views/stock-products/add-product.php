<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Product</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <div class="card p-4 shadow">
        <h2 class="text-center mb-4">Add Product</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Price:</label>
                <input type="number" class="form-control" name="price" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Quantity:</label>
                <input type="number" class="form-control" name="quantity" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Image URL:</label>
                <input type="text" class="form-control" name="image">
            </div>

            <button type="submit" class="btn btn-success w-100">Add Product</button>
        </form>
    </div>

    <!-- Bootstrap JS (optional if needed) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
