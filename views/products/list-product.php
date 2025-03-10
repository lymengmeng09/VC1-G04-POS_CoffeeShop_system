<?php
require_once('../layouts/header.php');
require_once('../layouts/navbar.php');

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Target Coffee - Product Management</title>
    <link rel="stylesheet" href="/views/assets/css/add-product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <main class="main-content">
        <header>
            <div class="header-icons">
                <button class="icon-button">
                    <i class="fas fa-bell"></i>
                </button>
                <img src="/views/assets/images/faces/1.jpg" alt="User" class="user-avatar">
            </div>
        </header>

        <div class="content">
            <h2>Products</h2>

            <div class="filters-section">
                <!-- Start Form -->
                <form method="GET" action="your-action-page.php"> <!-- Form to handle search and filters -->
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search products..." name="search" value="">
                    </div>
                    <div class="filters">
                        <!-- Category Filter -->
                        <select name="category" class="filter-select">

                        </select>

                        <!-- Status Filter -->
                        <select name="status" class="filter-select">
                            <option value="All Status">All Status</option>
                            <option value="In Stock">In Stock</option>
                            <option value="Out of Stock">Out of Stock</option>
                        </select>

                        <!-- Add Product Button -->
                        <!-- <button type="submit" class="add-product-btn"> -->
                        <a href="/products/create" class="add-product-btn" >
                            <i class="fas fa-plus"></i>
                            Add Product
                        </a>
                        <!-- </button> -->
                    </div>

                </form>
                <!-- End Form -->
            </div>


            <div class="products-table">
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox"></th>
                            <th>Products Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>

</html>