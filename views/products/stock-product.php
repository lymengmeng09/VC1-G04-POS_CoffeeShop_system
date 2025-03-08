<?php
$conn = new mysqli("localhost", "root", "", "pos-s");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stock Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Products In Stock</h2>
        <button class="btn btn-success mb-3" onclick="showUpdateForm()">+ Existing Product</button>

        <!-- Update Form (Hidden by Default) -->
        <div id="updateForm" style="display: none;" class="card p-3 mb-3">
            <h4>Update Product Stock</h4>
            <form action="edit-product.php" method="POST">
                <div class="mb-3">
                    <label for="product" class="form-label">Select Product:</label>
                    <select name="id" id="product" class="form-control" required>
                        <option value="">-- Select Product --</option>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id']; ?>">
                                <?php echo $row['name']; ?> (Current Qty: <?php echo $row['qty']; ?>)
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="qty" class="form-label">New Quantity:</label>
                    <input type="number" name="qty" id="qty" class="form-control" min="1" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Stock</button>
            </form>
        </div>

        <!-- Product List -->
        <div class="row">
            <?php
            $result = $conn->query($sql); // Re-run query
            while ($row = $result->fetch_assoc()) { ?>
                <div class="col-md-3">
                    <div class="card mb-4">
                        <img src="<?php echo $row['image']; ?>" class="card-img-top" alt="Product Image">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo $row['name']; ?></h5>
                            <p class="card-text">Price: $<?php echo number_format($row['price'], 2); ?></p>
                            <p class="card-text">Quantity: <?php echo $row['qty']; ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        function showUpdateForm() {
            document.getElementById('updateForm').style.display = 'block';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
