<?php
require_once('../layouts/header.php');
require_once('../layouts/navbar.php');

?>
<div class="container">
    <form action=" " method="POST">
        <h1>Add New Product</h1>
        <div class="form-group">
            <label for="" class="form-label">Product Name:</label>
            <input type="text" value="" name="name" class="form-control">
        </div>
        <div class="form-group">
        <select name="category" class="filter-select">
            <option value="coffee">Coffee</option>
            <option value="cold drink">Cold Drink</option>
        </select>
            <div class="group-card">
                <label for="" class="form-label">Price: </label>
                <input type="text" value="" name="phone" class="form-control">
            </div>
        </div>
        <select name="stock-status" class="filter-select">
            <option value="IN Status">In Status</option>
            <option value="Out of Stock">Out of Stock</option>
        </select>
        <button type="submit" class="btn btn-success mt-3">cancel</button>
        <button type="submit" class="btn btn-success mt-3">Save Product</button>
    </form>
</div>