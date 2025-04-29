document.addEventListener("DOMContentLoaded", () => {
    // Element References
    const elements = {
        cartTable: document.getElementById("cart-table"),
        cartTableBody: document.getElementById("cart-table-body"),
        cartTotalElement: document.getElementById("cart-total"),
        cartCountElement: document.getElementById("cart-count"),
        cartSection: document.getElementById("cart-section"),
        productGrid: document.getElementById("product-grid"),
        clearAllBtn: document.getElementById("clear-all"),
        payNowBtn: document.getElementById("PayMent"),
        okButton: document.getElementById("ok-button"),
        receiptModal: document.getElementById("receiptModal")
            ? new bootstrap.Modal(document.getElementById("receiptModal"))
            : null,
        receiptContent: document.getElementById("receipt-content"),
        confirmReceiptBtn: document.getElementById("confirm-receipt"),
        cartIcon: document.getElementById("cart-icon"),
    };

    // State Management
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let cartVisible = JSON.parse(localStorage.getItem("cartVisible")) || false;

    // Placeholder for showError and showSuccess (replace with your actual implementation)
    function showError(message) {
        console.error(message);
        alert(`Error: ${message}`); // Replace with your UI notification system (e.g., Bootstrap toast)
    }

    function showSuccess(message) {
        console.log(message);
        alert(`Success: ${message}`); // Replace with your UI notification system
    }

    // Initialization
    updateCartCount(); // Update cart count on all pages
    updateCartDisplay(); // Update cart table on order page
    toggleCart(cartVisible && cart.length > 0); // Toggle cart section on order page

    // Event Listeners
    document.querySelectorAll(".btn-Order").forEach((button) => {
        button.addEventListener("click", () => {
            addToCart(
                button.dataset.id,
                button.dataset.name,
                button.dataset.price,
                button.dataset.img,
                button.dataset.category
            );
        });
    });

    if (elements.cartTableBody) {
        elements.cartTableBody.addEventListener("input", (e) => {
            if (e.target.classList.contains("quantity-input")) {
                const productId = e.target.dataset.id;
                const product = cart.find((item) => item.id === productId);
                if (product) {
                    const newQuantity = parseInt(e.target.value);
                    product.quantity = isNaN(newQuantity) || newQuantity < 1 ? 1 : newQuantity;
                    saveCart();
                }
            }
        });

        elements.cartTableBody.addEventListener("click", (e) => {
            if (e.target.classList.contains("remove-item")) {
                const productId = e.target.dataset.id;
                cart = cart.filter((item) => item.id !== productId);
                saveCart();
            }
        });
    }

    if (elements.clearAllBtn) {
        elements.clearAllBtn.addEventListener("click", clearCart);
    }

    if (elements.payNowBtn) {
        elements.payNowBtn.addEventListener("click", () => {
            if (cart.length === 0) {
                showError("Your cart is empty!");
                return;
            }
            generateReceipt();
        });
    }

    if (elements.okButton) {
        elements.okButton.addEventListener("click", saveOrderToServer);
    }

    if (elements.confirmReceiptBtn) {
        elements.confirmReceiptBtn.addEventListener("click", saveAsPDF);
    } else {
        console.warn("Confirm Receipt button not found in DOM!");
    }

    if (elements.cartIcon) {
        elements.cartIcon.addEventListener("click", () => {
            window.location.href = "/views/products/list-product.php";
        });
    }

    // Cart Management
    function addToCart(id, name, price, img, category) {
        const parsedPrice = parseFloat(price);
        if (isNaN(parsedPrice)) {
            showError("Invalid product price!");
            return;
        }

        const existingItem = cart.find((item) => item.id === id);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id,
                name,
                price: parsedPrice,
                quantity: 1,
                img,
                category,
            });
        }
        cartVisible = true;
        saveCart();
        toggleCart(true);
    }

    function saveCart() {
        localStorage.setItem("cart", JSON.stringify(cart));
        localStorage.setItem("cartVisible", JSON.stringify(cartVisible));
        updateCartCount();
        updateCartDisplay();
    }

    function clearCart() {
        cart = [];
        cartVisible = false;
        localStorage.setItem("cart", JSON.stringify(cart));
        localStorage.setItem("cartVisible", JSON.stringify(cartVisible));
        updateCartCount();
        updateCartDisplay();
        toggleCart(false);
    }

    // UI Updates
    function updateCartCount() {
        const totalItems = cart.reduce((total, item) => total + (item.quantity || 0), 0);
        if (elements.cartCountElement) {
            elements.cartCountElement.textContent = totalItems;
            elements.cartCountElement.style.display = totalItems > 0 ? "inline-block" : "none";
        }
    }

    function updateCartDisplay() {
        if (!elements.cartTableBody) return;
        const totalItems = cart.reduce((total, item) => total + (item.quantity || 0), 0);
        elements.cartTableBody.innerHTML = "";
        let total = 0;

        cart.forEach((product) => {
            const itemTotal = product.price * product.quantity;
            total += itemTotal;
            const row = document.createElement("tr");
            row.innerHTML = `
                <td><img src="${product.img}" alt="${product.name}" style="width: 50px;"></td>
                <td>${product.name}</td>
                <td>$${product.price.toFixed(2)}</td>
                <td><input type="number" class="quantity-input" value="${product.quantity}" data-id="${product.id}" min="1"></td>
                <td><button class="remove-item bi-trash" data-id="${product.id}"></button></td>
            `;
            elements.cartTableBody.appendChild(row);
        });

        elements.cartTotalElement.textContent = total.toFixed(2);
        elements.cartTable.style.display = totalItems > 0 && cartVisible ? "block" : "none";
        if (elements.cartCountElement) {
            elements.cartCountElement.textContent = totalItems;
            elements.cartCountElement.style.display = totalItems > 0 ? "inline-block" : "none";
        }

        if (totalItems === 0 && cartVisible) {
            cartVisible = false;
            localStorage.setItem("cartVisible", JSON.stringify(cartVisible));
            toggleCart(false);
        }
    }

    function toggleCart(show) {
        if (!elements.cartSection || !elements.productGrid) return;
        cartVisible = show;
        localStorage.setItem("cartVisible", JSON.stringify(cartVisible));

        if (show && cart.length > 0) {
            elements.cartSection.style.display = "block";
            elements.cartTable.style.display = "block";
            elements.productGrid.classList.remove("col-lg-12");
            elements.productGrid.classList.add("col-lg-7");
            document.querySelectorAll("#product-grid .col-md-3").forEach((el) => {
                el.classList.remove("col-md-3");
                el.classList.add("col-md-4");
            });
        } else {
            elements.cartSection.style.display = "none";
            elements.cartTable.style.display = "none";
            elements.productGrid.classList.remove("col-lg-7");
            elements.productGrid.classList.add("col-lg-12");
            document.querySelectorAll("#product-grid .col-md-4").forEach((el) => {
                el.classList.remove("col-md-4");
                el.classList.add("col-md-3");
            });
        }
    }

    // Handle payment option selection
    document.querySelectorAll('.payment-option').forEach(option => {
        option.addEventListener('click', function () {
            const paymentMethod = this.dataset.payment;

            if (paymentMethod === 'aba') {
                const qrCodeModal = new bootstrap.Modal(document.getElementById('qrCodeModal'));
                qrCodeModal.show();
                bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
            } else {
                generateReceipt();
                bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
            }
        });
    });

    document.getElementById('confirm-aba-payment')?.addEventListener('click', function () {
        const qrModal = bootstrap.Modal.getInstance(document.getElementById('qrCodeModal'));
        qrModal.hide();

        setTimeout(() => {
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            generateReceipt();
        }, 150);
    });

    // Receipt Generation
    function generateReceipt() {
        if (cart.length === 0) {
            showError("Your cart is empty!");
            return;
        }

        let totalPrice = 0;
        const now = new Date();
        const receiptItems = cart.map((item) => {
            const itemTotal = item.quantity * item.price;
            totalPrice += itemTotal;
            return `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>$${item.price.toFixed(2)}</td>
                    <td>$${itemTotal.toFixed(2)}</td>
                    <td>${now.toISOString().slice(0, 10)}</td>
                </tr>
            `;
        }).join("");

        const productSubtotals = {};
        cart.forEach((item) => {
            const itemTotal = item.quantity * item.price;
            if (productSubtotals[item.name]) {
                productSubtotals[item.name] += itemTotal;
            } else {
                productSubtotals[item.name] = itemTotal;
            }
        });

        const subtotalRows = Object.keys(productSubtotals)
            .map((productName) => {
                return `
                    <tr>
                        <td>${productName}</td>
                        <td>$${productSubtotals[productName].toFixed(2)}</td>
                    </tr>
                `;
            })
            .join("");

        elements.receiptContent.innerHTML = `
            <p class="action-text"><strong>Action:</strong> Ordered</p>
            <div class="header-recept">
                <img src="/views/assets/images/logo.png" alt="Logo">
                <h2>Invoice</h2>
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
                </tbody>
            </table>
            <div style="margin-top: 20px;">
                <h3 style="margin-left: 40%">Subtotal</h3>
                <table class="table" style="text-align: center; width: 60%; margin-left: 36%">
                    <thead style="background-color: #f5eee5;">
                        <tr>
                            <th style="color: #6c4b3c;">NAME</th>
                            <th style="color: #6c4b3c;">SUBTOTAL($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${subtotalRows}
                    </tbody>
                </table>
                <div style="text-align: center; margin-top: 10px;">
                    <div style="display: inline-block; border: 1px solid #ccc; padding: 6px 8px; border-radius: 10px; margin-left: 66%">
                        <strong style="font-size: 18px;">Total</strong> 
                        <strong style="font-size: 18px;">$${totalPrice.toFixed(2)}</strong>
                    </div>
                </div>
            </div>
        `;
        elements.receiptModal.show();
    }
    function saveAsPDF() {
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const now = new Date();
    
            // === CONFIGURATION ===
            const logo = {
                url: "/views/assets/images/logo.png",
                width: 40,
                height: 23,
                x: (doc.internal.pageSize.getWidth() - 30) / 2,
                y: 10,
            };
    
            const headerY = logo.y + logo.height + 10;
    
            // === ADD LOGO ===
            doc.addImage(logo.url, "PNG", logo.x, logo.y, logo.width, logo.height);
    
            // === HEADER INFO ===
            doc.setFont("helvetica", "bold");
            doc.setFontSize(18);
            doc.setTextColor(40, 40, 40);
            doc.text("Target Coffee", 105, headerY, null, null, "center");
    
            doc.setFont("helvetica", "normal");
            doc.setFontSize(12);
            doc.setTextColor(60, 60, 60);
            doc.text(`Date: ${now.toLocaleDateString()}`, 20, headerY + 10);
            doc.text(`Time: ${now.toLocaleTimeString()}`, 20, headerY + 18);
            doc.text(`Phone: 081 369 639`, 130, headerY + 10);
            doc.text(`Address: #1D, St. 371 (St. Sola)`, 130, headerY + 18);
    
            // === BUILD TABLE DATA FROM CART ===
            let totalPrice = 0;
            const tableData = cart.map(item => {
                const itemTotal = item.quantity * item.price;
                totalPrice += itemTotal;
                return [
                    item.name,
                    item.quantity,
                    `$${item.price.toFixed(2)}`,
                    `$${itemTotal.toFixed(2)}`,
                    now.toISOString().slice(0, 10),
                ];
            });
    
            // === MAIN TABLE ===
            const tableStartY = headerY + 45;
            doc.autoTable({
                startY: tableStartY,
                head: [["Name", "Quantity", "Price($)", "Total($)", "Timestamp"]],
                body: tableData,
                theme: "grid",
                headStyles: {
                    fillColor: [108, 75, 60],
                    textColor: 255,
                    fontStyle: "bold"
                },
                alternateRowStyles: { fillColor: [240, 240, 240] },
                margin: { left: 20, right: 20 },
                styles: { fontSize: 10, cellPadding: 4 },
            });
    
            // === PRODUCT SUBTOTALS ===
            const productSubtotals = {};
            cart.forEach(item => {
                const itemTotal = item.quantity * item.price;
                productSubtotals[item.name] = (productSubtotals[item.name] || 0) + itemTotal;
            });
    
            const subtotalData = Object.entries(productSubtotals).map(([name, subtotal]) => {
                return [name, `$${subtotal.toFixed(2)}`];
            });
    
            // === SUBTOTAL TABLE ===
            let nextY = doc.lastAutoTable.finalY + 10;
            doc.setFont("helvetica", "bold");
            doc.setFontSize(14);
            doc.text("Subtotal", 125, nextY, null, null, "center");
    
            doc.autoTable({
                startY: nextY + 5,
                head: [["Name", "Subtotal($)"]],
                body: subtotalData,
                theme: "grid",
                headStyles: {
                    fillColor: [245, 238, 229],
                    textColor: [108, 75, 60],
                    fontStyle: "bold"
                },
                alternateRowStyles: { fillColor: [240, 240, 240] },
                margin: { left: 70 },
                styles: { fontSize: 10, cellPadding: 4 },
            });
    
            // === TOTAL PRICE ===
            nextY = doc.lastAutoTable.finalY + 10;
            doc.setFontSize(14);
            doc.setFont("helvetica", "bold");
            doc.setTextColor(0, 0, 0);
            doc.text("Total", 150, nextY);
            doc.text(`$${totalPrice.toFixed(2)}`, 170, nextY);
    
            // === SAVE PDF ===
            doc.save(`receipt_${now.getTime()}.pdf`);
    
        } catch (error) {
            console.error("Error generating PDF:", error);
            showError("Failed to generate PDF: " + error.message);
        } finally {
            if (elements.receiptModal) {
                elements.receiptModal.hide();
            }
        }
    }
    
    // Save Order to Server
    function saveOrderToServer() {
        const orderData = {
            items: cart.map((item) => ({
                product_id: item.id,
                name: item.name,
                price: item.price,
                quantity: item.quantity,
                category_id: item.category,
            })),
            total: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0),
        };

        elements.okButton.disabled = true;
        elements.okButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

        fetch("/products/save-order", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(orderData),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    clearCart();
                    elements.receiptModal.hide();
                    showSuccess("Order saved successfully!");
                } else {
                    showError("Error saving order: " + data.message);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showError("Error saving order: " + error.message);
            })
            .finally(() => {
                elements.okButton.disabled = false;
                elements.okButton.innerHTML = "OK";
            });
    }
});