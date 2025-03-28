<div class="col-md-14 py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Product Management</h2>
        <button class="btn btn-primary text-light">
            <a href="/products/create"><i class="fas fa-plus me-2 text-light"></i> Create Menu</a>
        </button>
    </div>

    <!-- Modified Search and Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search products..." name="search"
                            id="searchInput">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div class="col-md-2 category">
                    <div class="btn-group me-2">
                        <button id="btnGroupDrop1" type="button" class="btn btn-outline-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Category: <?= htmlspecialchars(ucfirst($_GET['category'] ?? 'All')) ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" id="categoryList">
                            <li><a class="dropdown-item" href="?category=all&category_id=<?= $_GET['category_id'] ?? 'all' ?>">All</a></li>
                            <li><a class="dropdown-item"
                                    href="?category=ColdDrinks&category_id=<?= $_GET['category_id'] ?? 'all' ?>">Cold Drinks</a>
                            </li>
                            <li><a class="dropdown-item"
                                    href="?category=Frappe&category_id=<?= $_GET['category_id'] ?? 'all' ?>">Frappe</a></li>
                            <li><a class="dropdown-item"
                                    href="?category=HotDrinks&category_id=<?= $_GET['category_id'] ?? 'all' ?>">Hot Drinks</a>
                            </li>
                            <li><a class="dropdown-item"
                                    href="?category=Smoothies&category_id=<?= $_GET['category_id'] ?? 'all' ?>">Smoothies</a></li>
                        </ul>
                    </div>
                </div>

            </form>
        </div>
    </div>


    <div class="row g-4 coffee-grid">
      <!-- Product Section -->
<div class="row g-4 coffee-grid">
    <?php foreach ($products as $index => $product): ?>
    <div class="col-6 col-md-3 product-item" data-category="Coffee">
        <div class="card border-0 h-100">
            <div class="text-center p-2">
                <div class="product-entry">
                    <!-- Dropdown for Edit/Delete -->
                    <div class="dropdown">
                        <button class="dropbtn">⋮</button>
                        <div class="dropdown-content">
                            <!-- Edit Link -->
                            <a href="/products/edit/<?= htmlspecialchars($product['product_id']) ?>">Edit</a>
                            <!-- Delete Button with Confirmation -->
                            <button type="button" class="btn-delete" 
                                data-id="<?= htmlspecialchars($product['product_id']) ?>" 
                                data-name="<?= htmlspecialchars($product['product_name']) ?>" 
                                data-bs-toggle="modal" data-bs-target="#deleteModal">
                                Delete
                            </button>
                        </div>
                    </div>
                    <!-- Product Image -->
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                        alt="<?= htmlspecialchars($product['product_name']) ?>" 
                        class="img-fluid mb-2" 
                        onerror="this.src='/path/to/placeholder-image.jpg';">
                    <div class="mt-2">
                        <!-- Product Name and Price -->
                        <h6 class="card-title fw-normal text-center mb-1" style="font-size: 0.9rem;">
                            <?= htmlspecialchars($product['product_name']) ?>
                        </h6>
                        <p class="text-success fw-bold mb-0">
                            $<?= number_format($product['price'], 2) ?>
                        </p>
                        <!-- Order Button -->
                        <button class="btn-Order" data-name="<?= htmlspecialchars($product['product_name']) ?>" 
                            data-price="<?= number_format($product['price'], 2) ?>" 
                            data-img="<?= htmlspecialchars($product['image_url']) ?>">
                            Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

 
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <strong id="modalProductName"></strong>?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger" style = " margin-top:9%;">Delete</button>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Handle Delete Confirmation Modal
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.dataset.id;
            const productName = this.dataset.name;

            // Set modal product name and update form action
            document.getElementById('modalProductName').textContent = productName;
            document.getElementById('deleteForm').action = `/products/delete/${productId}`;
        });
    });

    // Handle Order Button
    document.querySelectorAll('.btn-Order').forEach(button => {
        button.addEventListener('click', function () {
            const productName = this.dataset.name;
            const productPrice = this.dataset.price;
            const productImg = this.dataset.img;

            alert(`Order placed for: ${productName}, Price: $${productPrice}`);
            // Add additional logic for order handling (e.g., add to cart or open order modal)
        });
    });
</script>


        <!-- Cart Table -->
        <div id="cart-table" style="display: none;" class="card-order">
            <h3>Bills</h3>
            <table class="table table-bordered">
                <div id="cart-table-body">
                </div>
            </table>
            <div class="d-flex justify-content-between total" id="btn">
                <div class="cart-total">Total: $<span id="cart-total">0.00</span></div>
                <div class="btn_cart">
                    <button id="clear-all" class="btn btn-secondary">Cancel</button>
                    <button id="PayMent" class="btn btn-primary">Pay Now</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Order Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="receipt-content">
                <!-- Receipt details will be inserted here dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="confirm-receipt">Confirm</button>
            </div>
        </div>
    </div>
</div>


<script>
document.querySelectorAll('.dropbtn').forEach(button => {
    button.addEventListener('click', function() {
        const dropdownContent = this.nextElementSibling;
        dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
    });
});
</script>
<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Order Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="receipt-content">
                <!-- Receipt details will be inserted here dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="confirm-receipt">Confirm</button>
            </div>
        </div>
    </div>
</div>



<script>// Adding a product to the cart
document.querySelectorAll('.btn-Order').forEach(button => {
    button.addEventListener('click', function() {
        const productName = this.getAttribute('data-name');
        const productPrice = parseFloat(this.getAttribute('data-price'));
        const productImg = this.getAttribute('data-img');

        // Create a new row in the cart table
        const cartTableBody = document.getElementById('cart-table-body');
        const row = document.createElement('tr');

        row.innerHTML = `
            <td><img src="${productImg}" alt="${productName}" style="width: 50px;"></td>
            <td>${productName}</td>
            <td>$${productPrice.toFixed(2)}</td>
            <td><button class="btn btn-danger remove-item">Remove</button></td>
        `;
        cartTableBody.appendChild(row);

        // Update the cart total
        updateCartTotal(productPrice);
        
        // Show the cart table
        document.getElementById('cart-table').style.display = 'block';
    });
});

// Helper function to update the cart total
function updateCartTotal(amount) {
    const cartTotal = document.getElementById('cart-total');
    const currentTotal = parseFloat(cartTotal.textContent.replace('$', '')) || 0;
    const newTotal = currentTotal + amount;
    cartTotal.textContent = `$${newTotal.toFixed(2)}`;
}

// Removing an item from the cart
document.getElementById('cart-table-body').addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('remove-item')) {
        // Find the row that contains the "Remove" button
        const row = e.target.closest('tr');

        // Get the price of the product being removed
        const productPrice = parseFloat(row.children[2].textContent.replace('$', ''));

        // Remove the row
        row.remove();

        // Update the cart total
        updateCartTotal(-productPrice);

        // If the cart is empty, hide the cart table
        if (document.getElementById('cart-table-body').children.length === 0) {
            document.getElementById('cart-table').style.display = 'none';
        }
    }
});

// Cancel button event listener
document.getElementById('clear-all').addEventListener('click', function() {
    // Clear cart items in the table
    const cartTableBody = document.getElementById('cart-table-body');
    cartTableBody.innerHTML = ''; // This removes all rows

    // Reset the total
    const cartTotal = document.getElementById('cart-total');
    cartTotal.textContent = '0.00';

    // Optionally hide the cart table after clearing
    const cartTable = document.getElementById('cart-table');
    cartTable.style.display = 'none'; // Hide the table
});

// Search category
document.addEventListener("DOMContentLoaded", function () {
    const categoryButton = document.getElementById("btnGroupDrop1");
    const categoryLinks = document.querySelectorAll(".dropdown-item");

    categoryLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const selectedCategory = this.textContent;
            categoryButton.textContent = "Category: " + selectedCategory;
            window.location.href = this.href; // Redirect with selected category
        });
    });
});

</script>
