 document.addEventListener("DOMContentLoaded", function() {
    // Initialize elements
    const searchInput = document.getElementById("searchInput");
    const productItems = document.querySelectorAll(".product-item");
    const cartTableBody = document.getElementById("cart-table-body");
    const cartTotalElement = document.getElementById("cart-total");
    const cartCountElement = document.getElementById("cart-count");
    const cartTable = document.getElementById("cart-table");
    const clearAllBtn = document.getElementById("clear-all");
    const payNowBtn = document.getElementById("PayMent");
    const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
    const receiptContent = document.getElementById('receipt-content');
    const confirmReceiptBtn = document.getElementById('confirm-receipt');
    const deleteModal = document.getElementById('deleteModal') ? new bootstrap.Modal(document.getElementById('deleteModal')) : null;

    // Cart state
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Initialize
    updateCartDisplay();
    setupEventListeners();

    function setupEventListeners() {
        // Product filtering
        if (searchInput) {
            searchInput.addEventListener("input", filterProducts);
        }

        // Order buttons
        document.querySelectorAll('.btn-Order').forEach(button => {
            button.addEventListener('click', function() {
                addToCart(
                    this.dataset.id,
                    this.dataset.name,
                    this.dataset.price,
                    this.dataset.img,
                    this.dataset.category
                );
            });
        });

        // Cart quantity changes
        if (cartTableBody) {
            cartTableBody.addEventListener('input', function(e) {
                if (e.target.classList.contains('quantity-input')) {
                    const productId = e.target.dataset.id;
                    const product = cart.find(item => item.id === productId);
                    if (product) {
                        product.quantity = parseInt(e.target.value) || 1; // Default to 1 if invalid
                        saveCart();
                    }
                }
            });

            cartTableBody.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    const productId = e.target.dataset.id;
                    cart = cart.filter(item => item.id !== productId);
                    saveCart();
                }
            });
        }

        // Clear cart
        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', function() {
                cart = [];
                saveCart();
            });
        }

        // Payment
        if (payNowBtn) {
            payNowBtn.addEventListener('click', function() {
                if (cart.length === 0) {
                    alert("Your cart is empty!");
                    return;
                }
                generateReceipt();
            });
        }

        // Confirm receipt
        if (confirmReceiptBtn) {
            confirmReceiptBtn.addEventListener('click', function() {
                saveAsPDF();
                cart = [];
                saveCart();
                receiptModal.hide();
                toggleCart(false);
            });
        }

        // Delete product modal
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const modalProductName = document.getElementById('modalProductName');
                const deleteForm = document.getElementById('deleteForm');
                if (modalProductName && deleteForm) {
                    modalProductName.textContent = this.dataset.name;
                    deleteForm.action = `/products/delete/${this.dataset.id}`;
                }
            });
        });

        // Dropdown toggles
        document.querySelectorAll('.dropbtn').forEach(button => {
            button.addEventListener('click', function() {
                const dropdownContent = this.nextElementSibling;
                if (dropdownContent) {
                    dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
                }
            });
        });

        // Category selection
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
                const btnGroupDrop1 = document.getElementById('btnGroupDrop1');
                if (btnGroupDrop1) {
                    btnGroupDrop1.textContent = `Category: ${this.textContent}`;
                    filterProducts();
                }
            });
        });
    }

    function filterProducts() {
        const searchTerm = (searchInput?.value || '').toLowerCase();
        const selectedCategory = document.querySelector(".dropdown-item.active")?.dataset.category || 'all';

        productItems.forEach(product => {
            const productName = product.querySelector(".card-title")?.textContent.toLowerCase() || '';
            const productCategory = product.dataset.category?.toLowerCase() || '';
            const matchesSearch = productName.includes(searchTerm);
            const matchesCategory = selectedCategory === 'all' || productCategory.includes(selectedCategory);
            product.style.display = matchesSearch && matchesCategory ? "" : "none";
        });
    }

    function addToCart(id, name, price, img, category) {
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id: id,
                name: name,
                price: parseFloat(price) || 0,
                quantity: 1,
                img: img,
                category: category
            });
        }
        saveCart();
        toggleCart(true);
    }

    function saveCart() {
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartDisplay();
    }

    function updateCartDisplay() {
        const totalItems = cart.reduce((total, item) => total + (item.quantity || 0), 0);
        if (cartCountElement) cartCountElement.textContent = totalItems;

        if (cartTableBody) {
            cartTableBody.innerHTML = '';
            let total = 0;

            cart.forEach(product => {
                const itemTotal = (product.price || 0) * (product.quantity || 0);
                total += itemTotal;
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><img src="${product.img || ''}" alt="${product.name || ''}" style="width: 50px;"></td>
                    <td>${product.name || 'N/A'}</td>
                    <td>$${product.price.toFixed(2)}</td>
                    <td><input type="number" class="quantity-input" value="${product.quantity || 1}" data-id="${product.id}" min="1"></td>
                    <td><button class="remove-item bi-trash" data-id="${product.id}"></button></td>
                `;
                cartTableBody.appendChild(row);
            });

            if (cartTotalElement) cartTotalElement.textContent = total.toFixed(2);
            if (cartTable) cartTable.style.display = totalItems > 0 ? 'block' : 'none';

            if (totalItems === 0) toggleCart(false);
        }
    }

    function toggleCart(show) {
        const cartSection = document.getElementById('cart-section');
        const productGrid = document.getElementById('product-grid');

        if (cartSection && productGrid) {
            if (show) {
                cartSection.style.display = 'block';
                productGrid.classList.remove('col-lg-12');
                productGrid.classList.add('col-lg-7');
                document.querySelectorAll('#product-grid .col-md-3').forEach(el => {
                    el.classList.remove('col-md-3');
                    el.classList.add('col-md-4');
                });
            } else {
                cartSection.style.display = 'none';
                productGrid.classList.remove('col-lg-7');
                productGrid.classList.add('col-lg-12');
                document.querySelectorAll('#product-grid .col-md-4').forEach(el => {
                    el.classList.remove('col-md-4');
                    el.classList.add('col-md-3');
                });
            }
        }
    }

    function generateReceipt() {
        let totalPrice = 0;
        const now = new Date();
        const receiptItems = cart.map(item => {
            const itemTotal = (item.quantity || 0) * (item.price || 0);
            totalPrice += itemTotal;
            return {
                product_id: item.id,
                name: item.name || 'N/A',
                quantity: item.quantity || 1,
                price: item.price || 0,
                subtotal: itemTotal,
                timestamp: now.toISOString().slice(0, 10)
            };
        });

        // Send data to backend
        sendOrderData(receiptItems, totalPrice);

        // Display receipt
        if (receiptContent) {
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
                        ${receiptItems.map(item => `
                            <tr>
                                <td>${item.name}</td>
                                <td>${item.quantity}</td>
                                <td>$${item.price.toFixed(2)}</td>
                                <td>$${item.subtotal.toFixed(2)}</td>
                                <td>${item.timestamp}</td>
                            </tr>
                        `).join('') || '<tr><td colspan="5">No items in cart</td></tr>'}
                        <tr class="total-row">
                            <td colspan="3"><strong>TOTAL PRICE</strong></td>
                            <td colspan="2"><strong>$${totalPrice.toFixed(2)}</strong></td>
                        </tr>
                    </tbody>
                </table>
            `;
            receiptModal.show();
        }
    }

    function sendOrderData(items, total) {
        const orderData = {
            customer_id: 1, // Replace with dynamic customer ID if available (e.g., from a global variable or API)
            items: items,
            total: total,
            order_date: new Date().toISOString(),
            payment_status: 'pending'
        };

        fetch('/products/storeReceipt', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify(orderData)
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                console.log('Order stored successfully with ID:', data.order_id);
            } else {
                console.error('Failed to store order:', data.message);
                alert('Failed to place order: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Error sending order: ' + error.message);
        });
    }

    function saveAsPDF() {
        // Check if jsPDF is loaded (you need to include the jsPDF library in your HTML)
        if (typeof window.jspdf === 'undefined') {
            console.error('jsPDF library is not loaded. Include <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>');
            return;
        }

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const now = new Date();

        doc.setFont("helvetica", "bold");
        doc.setFontSize(18);
        doc.text("Order Receipt", 105, 20, null, null, "center");

        doc.setFontSize(12);
        doc.setFont("helvetica", "normal");
        doc.text(`Date: ${now.toLocaleDateString()}`, 20, 30);

        const tableData = cart.map(item => {
            const itemTotal = (item.quantity || 0) * (item.price || 0);
            return [item.name || 'N/A', item.quantity || 1, `$${item.price.toFixed(2)}`, `$${itemTotal.toFixed(2)}`];
        });

        doc.autoTable({
            startY: 40,
            head: [['Name', 'Quantity', 'Price', 'Total']],
            body: tableData,
            theme: 'grid'
        });

        const totalPrice = cart.reduce((sum, item) => sum + (item.quantity || 0) * (item.price || 0), 0);
        const finalY = doc.lastAutoTable.finalY + 10;
        doc.text(`Total: $${totalPrice.toFixed(2)}`, 20, finalY);

        doc.save(`receipt_${now.getTime()}.pdf`);
    }
});