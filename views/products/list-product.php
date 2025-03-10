<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management System</title>
    <link rel="stylesheet" href="/views/assets/css/add-product.css">
</head>

<body>
    <div class="container">
        <h1>Products</h1>

        <div class="controls">
            <div class="search-container">
                <input type="text" id="search" placeholder="Search products...">
                <button class="search-btn"><i class="search-icon">🔍</i></button>
            </div>

            <div class="filters">
                <div class="dropdown">
                    <button class="dropdown-btn">All Category <span class="arrow">▼</span></button>
                    <div class="dropdown-content">
                        <a href="?category=all">All Category</a>
                        <a href="?category=coffee">Coffee</a>
                        <a href="?category=cold-drinks">Cold Drinks</a>
                    </div>
                </div>

                <div class="dropdown">
                    <button class="dropdown-btn">All Status <span class="arrow">▼</span></button>
                    <div class="dropdown-content">
                        <a href="?status=all">All Status</a>
                        <a href="?status=in-stock">In Stock</a>
                        <a href="?status=out-of-stock">Out of Stock</a>
                    </div>
                </div>
            </div>

            <a href="add_product.php" class="add-btn">+ Add Product</a>
        </div>

        <div class="table-container">
            <table class="products-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Products Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
            </table>
        </div>
    </div>
</body>

</html>