<div class="col-md-14 py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Product Management</h2>
        <button class="btn btn-primary text-light">
            <a href="/products/create"><i class="fas fa-plus me-2 text-light"></i> Add Product</a>
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
                            categories: <?= htmlspecialchars(ucfirst($_GET['category_name'] ?? 'all')) ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="">All category</a></li>
                            <li><a class="dropdown-item" href="">Coffee</a></li>
                            <li><a class="dropdown-item" href="">Tea</a></li>
                            <li><a class="dropdown-item" href="">Smoothies</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md shop" style="position: relative;">
                    <a href="javascript:void(0)" id="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="count_cart" id="cart-count">0</span>
                    </a>
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
                                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" class="img-fluid mb-2">
                                    <div class="mt-2">
                                        <h6 class="card-title fw-normal text-center mb-1" style="font-size: 0.9rem;">
                                            <?= htmlspecialchars($product['product_name']) ?>
                                        </h6>
                                        <p class="text-success fw-bold mb-0">
                                            $<?= number_format($product['price'], 2) ?>
                                        </p>
                                        <button class="btn-Order" data-name="<?= htmlspecialchars($product['product_name']) ?>" data-price="<?= number_format($product['price'], 2) ?>" data-img="<?= htmlspecialchars($product['image_url']) ?>">
                                            Order New
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

<!-- Include html2pdf.js for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

 

<!-- Inline JavaScript -->
<script>
    // Cart management
    let cart = [];
    const cartCountElement = document.getElementById('cart-count');
    const payNowButton = document.getElementById('PayMent');
    let receiptModal;
    const receiptContent = document.getElementById('receipt-content');

    // Initialize the modal
    try {
        receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
        console.log('Modal initialized successfully');
    } catch (error) {
        console.error('Error initializing modal:', error);
    }

    // Load cart from localStorage
    if (localStorage.getItem('cart')) {
        cart = JSON.parse(localStorage.getItem('cart'));
        console.log('Cart loaded from localStorage:', cart);
        updateCartCount();
    } else {
        console.log('No cart data in localStorage');
    }

    // Update cart count display
    function updateCartCount() {
        let itemCount = 0;
        cart.forEach(item => {
            itemCount += item.quantity;
        });
        cartCountElement.textContent = itemCount;
        console.log('Cart count updated:', itemCount);
    }

    // Add product to cart
    document.querySelectorAll('.btn-Order').forEach(button => {
        button.addEventListener('click', function() {
            const name = this.getAttribute('data-name');
            const price = parseFloat(this.getAttribute('data-price'));
            const img = this.getAttribute('data-img');

            const existingItem = cart.find(item => item.name === name);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({ name, price, img, quantity: 1 });
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            console.log('Product added to cart:', { name, price, img, quantity: existingItem ? existingItem.quantity : 1 });
            updateCartCount();
        });
    });


    // Pay Now button
    payNowButton.addEventListener('click', function() {
        // Generate receipt HTML directly from cart data
        let totalPrice = 0;
        const receiptItems = cart.map(item => {
            const itemTotal = item.quantity * item.price; // Calculate total for each item
            totalPrice += itemTotal; // Add to overall total
            return `
                <tr>
                    <td>${item.name}</td>
                    <td>+${item.quantity}</td>
                    <td>$${item.price.toFixed(2)}</td>
                    <td>$${itemTotal.toFixed(2)}</td>
                    <td>${new Date().toISOString().slice(0, 10)}</td>
                </tr>
            `;
        }).join('');

        // Show receipt even if cart is empty
        receiptContent.innerHTML = `
            <p class="action-text"><strong>Action:</strong> Ordered</p>
            <div class="header-recept">
                <img src="/views/assets/images/logo.png" alt="Logo">
                <h2>Order Receipt</h2>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Price($)</th>
                        <th>Total($)</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    ${receiptItems || '<tr><td colspan="5">No items in cart</td></tr>'}
                    <tr class="total-row">
                        <td colspan="3"><strong>TOTAL PRICE</strong></td>
                        <td colspan="2"><strong>$${totalPrice.toFixed(2)}</strong></td>
                    </tr>
                </tbody>
            </table>
        `;
        console.log('Receipt content generated:', receiptContent.innerHTML);

        // Show the receipt modal
        if (receiptModal) {
            receiptModal.show();
            console.log('Receipt modal shown');
        } else {
            console.error('Receipt modal not initialized');
        }

        // Prepare order data for backend
        const orderData = {
            items: cart.map(item => ({
                name: item.name,
                price: item.price,
                change_quantity: `+${item.quantity}`,
                timestamp: new Date().toISOString()
            })),
            total: totalPrice
        };
        console.log('Order data prepared:', orderData);
 
        // Send order data to backend
        fetch('/products/generate-receipt', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(orderData)
        })
        .then(response => {
            console.log('Fetch response received:', response);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Fetch data:', data);
            if (!data.success) {
                console.error('Error generating receipt on backend:', data.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
    });
 //unctionality
    document.querySelectorAll('.dropbtn').forEach(button => {
        button.addEventListener('click', function() {
            const dropdownContent = this.nextElementSibling;
            dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
            console.log('Dropdown toggled:', dropdownContent.style.display);
        });
    });

    document.getElementById('confirm-receipt').addEventListener('click', function () {
    const element = document.getElementById('receipt-content'); // Target element to save as PDF

    // Check if content is available inside the receipt element
    if (!element || !element.innerHTML.trim()) {
        console.error('The receipt content is empty!');
        return;
    }

    // Log the content of the element for debugging purposes
    console.log('Receipt content:', element.innerHTML);

    const options = {
        margin: 1,
        filename: 'order_receipt.pdf',
        image: { type: 'png', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    };

    // Generate and save the PDF
    html2pdf().set(options).from(element).save().then(() => {
        console.log('Receipt saved as PDF.');

        // Optionally prevent modal from closing immediately after saving the PDF
        // Allow the user to manually close the modal if needed

        // Clear the cart
        cart = [];
        localStorage.removeItem('cart'); // Remove cart from localStorage
        updateCartCount(); // Update cart count in the UI
        console.log('Cart cleared.');

        // Optionally send data to the backend
        fetch('/products/confirm-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                action: 'confirm',
                timestamp: new Date().toISOString(),
                items: cart // Send cart data (should be empty after clearing)
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to confirm order on the server.');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                console.log('Order confirmed successfully on the backend.');
            } else {
                console.error('Backend order confirmation failed:', data.message);
            }
        })
        .catch(error => {
            console.error('Error during backend confirmation:', error);
        });

        // Optionally refresh the page after saving the PDF
        setTimeout(() => {
            location.reload(); // Refresh the page to show updated UI
        }, 500);
    }).catch((error) => {
        console.error('Error saving PDF:', error);
    });
});


</script>
