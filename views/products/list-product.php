<div class="col-md-14 py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Product Management</h2>
        <button class="btn btn-primary text-light">
            <a href="/products/create"><i class="fas fa-plus me-2 text-light"></i> Create Menu</a>
        </button>
    </div>

    <!-- Search and Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search products..." name="search" id="searchInput">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-2 category">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            categories: <span id="selected-category"><?= htmlspecialchars(ucfirst($_GET['category_name'] ?? 'all')) ?></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" data-category="all">All categories</a></li>
                            <li><a class="dropdown-item" data-category="coffee">Coffee</a></li>
                            <li><a class="dropdown-item" data-category="martcha">Martcha</a></li>
                            <li><a class="dropdown-item" data-category="expresso">Expresso</a></li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>

        <div class="row g-4 coffee-grid">
            <!-- Product Section -->
            <div class="row g-4 coffee-grid">
                <?php foreach ($products as $index => $product): ?>
                    <div class="col-6 col-md-3 product-item" data-category="Coffee">
                        <div class="card border-0 h-100">
                            <div class="text-center p-2">
                                <div class="product-entry">
                                    <div class="dropdown">
                                        <button class="dropbtn">⋮</button>
                                        <div class="dropdown-content">
                                            <a href="/products/edit/<?= htmlspecialchars($product['product_id']) ?>">Edit</a>
                                            <form action="/products/delete/<?= htmlspecialchars($product['product_id']) ?>" method="POST" style="display:inline;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" onclick="return confirm('Are you sure?')" style="background:none;border:none;color:#000;padding:0;">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="image-container">
                                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" class="img-fluid mb-2 product-image">
                                    </div>
                                    <div class="mt-2">
                                    <h6 class="card-title text-center mb-1" style="font-size: 1.2em; font-weight:350;">
    <strong><?= htmlspecialchars($product['product_name']) ?></strong>
</h6>

                                        <p class="text-success fw-bold mb-0">
                                            $<?= number_format($product['price'], 2) ?>
                                        </p>
                                        <button class="btn-Order" data-name="<?= htmlspecialchars($product['product_name']) ?>" data-price="<?= number_format($product['price'], 2) ?>" data-img="<?= htmlspecialchars($product['image_url']) ?>">
                                            Order
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


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

 
 
<script>
  // Adding a product to the cart
document.querySelectorAll('.btn-Order').forEach(button => {
    button.addEventListener('click', function() {
        const productName = this.getAttribute('data-name');
        const productPrice = this.getAttribute('data-price');
        const productImg = this.getAttribute('data-img');

        // Create a new row in the cart table
        const cartTableBody = document.getElementById('cart-table-body');
        const row = document.createElement('tr');

        row.innerHTML = `
            <td><img src="${productImg}" alt="${productName}" style="width: 50px;"></td>
            <td>${productName}</td>
            <td>$${productPrice}</td>
            <td><button class="btn btn-danger remove-item">Remove</button></td>
        `;
        cartTableBody.appendChild(row);

        // Update the cart total
        const cartTotal = document.getElementById('cart-total');
        const currentTotal = parseFloat(cartTotal.textContent.replace('$', ''));
        const newTotal = currentTotal + parseFloat(productPrice);
        cartTotal.textContent = `$${newTotal.toFixed(2)}`;

        // Show the cart table
        document.getElementById('cart-table').style.display = 'block';
    });
});

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
        const cartTotal = document.getElementById('cart-total');
        const currentTotal = parseFloat(cartTotal.textContent.replace('$', ''));
        const newTotal = currentTotal - productPrice;
        cartTotal.textContent = `$${newTotal.toFixed(2)}`;


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
    cartTableBody.innerHTML = '';  // This removes all rows

    // Reset the total
    const cartTotal = document.getElementById('cart-total');
    cartTotal.textContent = '0.00';

    // Optionally hide the cart table after clearing
    const cartTable = document.getElementById('cart-table');
    cartTable.style.display = 'none';  // Hide the table
});


 

</script>
