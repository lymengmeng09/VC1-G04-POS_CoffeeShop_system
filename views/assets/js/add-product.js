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
      cartTableBody.addEventListener('input', function(e) {
          if (e.target.classList.contains('quantity-input')) {
              const productId = e.target.dataset.id;
              const product = cart.find(item => item.id === productId);
              
              if (product) {
                  product.quantity = parseInt(e.target.value);
                  saveCart();
              }
          }
      });
      
      // Remove item from cart
      cartTableBody.addEventListener('click', function(e) {
          if (e.target.classList.contains('remove-item')) {
              const productId = e.target.dataset.id;
              cart = cart.filter(item => item.id !== productId);
              saveCart();
          }
      });
      
      // Clear cart
      clearAllBtn.addEventListener('click', function() {
          cart = [];
          saveCart();
      });
      
      // Payment
      payNowBtn.addEventListener('click', function() {
          if (cart.length === 0) {
              alert("Your cart is empty!");
              return;
          }
          generateReceipt();
      });
      
      // Confirm receipt
      confirmReceiptBtn.addEventListener('click', function() {
          saveAsPDF();
          cart = [];
          saveCart();
          receiptModal.hide();
          toggleCart(false);
      });
      
      // Delete product modal
      document.querySelectorAll('.btn-delete').forEach(button => {
          button.addEventListener('click', function() {
              document.getElementById('modalProductName').textContent = this.dataset.name;
              document.getElementById('deleteForm').action = `/products/delete/${this.dataset.id}`;
          });
      });
      
      // Dropdown toggles
      document.querySelectorAll('.dropbtn').forEach(button => {
          button.addEventListener('click', function() {
              const dropdownContent = this.nextElementSibling;
              dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
          });
      });
      
      // Category selection
      document.querySelectorAll('.dropdown-item').forEach(item => {
          item.addEventListener('click', function() {
              document.getElementById('btnGroupDrop1').textContent = `Category: ${this.textContent}`;
              filterProducts();
          });
      });
  }
  
  function filterProducts() {
      const searchTerm = searchInput.value.toLowerCase();
      const selectedCategory = document.querySelector(".dropdown-item.active")?.dataset.category || 'all';
      
      productItems.forEach(function(product) {
          const productName = product.querySelector(".card-title").textContent.toLowerCase();
          const productCategory = product.dataset.category.toLowerCase();
          
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
              price: parseFloat(price),
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
    // Update count
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
    if (cartCountElement) cartCountElement.textContent = totalItems;
    
    // Update table
    cartTableBody.innerHTML = '';
    let total = 0;
    
    cart.forEach(product => {
        const row = document.createElement('tr');
        const itemTotal = product.price * product.quantity;
        total += itemTotal;
        
        row.innerHTML = `
            <td><img src="${product.img}" alt="${product.name}" style="width: 50px;"></td>
            <td>${product.name}</td>
            <td>$${product.price.toFixed(2)}</td>
            <td><input type="number" class="quantity-input" value="${product.quantity}" data-id="${product.id}" min="1"></td>
            <td><button class="remove-item bi-trash" data-id="${product.id}"></button></td>
        `;
        cartTableBody.appendChild(row);
    });
    
    cartTotalElement.textContent = total.toFixed(2);
    cartTable.style.display = totalItems > 0 ? 'block' : 'none';
    
    // Automatically hide cart section if empty
    if (totalItems === 0) {
        toggleCart(false);
    }
}
  
  function toggleCart(show) {
    const cartSection = document.getElementById('cart-section');
    const productGrid = document.getElementById('product-grid');
    
    if(show) {
        cartSection.style.display = 'block';
        productGrid.classList.remove('col-lg-12');
        productGrid.classList.add('col-lg-7');
        
        // Change product items to 3 per row
        document.querySelectorAll('#product-grid .col-md-3').forEach(el => {
            el.classList.remove('col-md-3');
            el.classList.add('col-md-4');
        });
    } else {
        cartSection.style.display = 'none';
        productGrid.classList.remove('col-lg-7');
        productGrid.classList.add('col-lg-12');
        
        // Change product items to 4 per row
        document.querySelectorAll('#product-grid .col-md-4').forEach(el => {
            el.classList.remove('col-md-4');
            el.classList.add('col-md-3');
        });
    }
}

// Initialize event listeners
document.querySelectorAll('.btn-Order').forEach(btn => {
    btn.addEventListener('click', () => toggleCart(true));
});

document.getElementById('clear-all').addEventListener('click', () => toggleCart(false));
  
  function generateReceipt() {
      let totalPrice = 0;
      const now = new Date();
      
      const receiptItems = cart.map(item => {
          const itemTotal = item.quantity * item.price;
          totalPrice += itemTotal;
          return `
              <tr>
                  <td>${item.name}</td>
                  <td>${item.quantity}</td>
                  <td>$${item.price.toFixed(2)}</td>
                  <td>$${itemTotal.toFixed(2)}</td>
                  <td>${new Date().toISOString().slice(0, 10)}</td>
              </tr>
          `;
      }).join('');
      
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
      
      receiptModal.show();
  }
  function saveAsPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const now = new Date();

    // Add receipt content to PDF
    doc.setFont("helvetica", "bold");
    doc.setFontSize(18);
    doc.setTextColor(40, 40, 40);
    doc.text("Target Coffee", 105, 20, null, null, "center");
    
    // Add Date and Time
    doc.setFontSize(12);
    doc.setFont("helvetica", "normal");
    doc.setTextColor(60, 60, 60);
    doc.text(`Date: ${now.toLocaleDateString()}`, 20, 30);
    doc.text(`Time: ${now.toLocaleTimeString()}`, 20, 38);

    // Prepare Data for the Table
    let totalPrice = 0;
    const tableData = cart.map(item => {
        const itemTotal = item.quantity * item.price;
        totalPrice += itemTotal;
        return [item.name, item.quantity, `$${item.price.toFixed(2)}`, `$${itemTotal.toFixed(2)}`];
    });

    // Add table with styles
    doc.autoTable({
        startY: 45,
        head: [['Item', 'Quantity', 'Price', 'Total']],
        body: tableData,
        theme: 'grid',
        headStyles: { fillColor: [100, 100, 255], textColor: 255, fontStyle: 'bold' },
        alternateRowStyles: { fillColor: [240, 240, 240] },
        margin: { left: 20, right: 20 },
        styles: { fontSize: 12, cellPadding: 5 },
    });

    // Add Total Price at the bottom
    let finalY = doc.lastAutoTable.finalY + 10;
    doc.setFontSize(14);
    doc.setFont("helvetica", "bold");
    doc.setTextColor(0, 0, 0);
    doc.text("TOTAL:", 130, finalY);
    doc.text(`$${totalPrice.toFixed(2)}`, 170, finalY);

    doc.save(`receipt_${now.getTime()}.pdf`);
    
    // Send data to server
    sendOrderData();
}
  
function sendOrderData() {
  const orderData = {
      items: cart.map(item => ({
          product_id: item.id,
          name: item.name,
          price: item.price,
          quantity: item.quantity,
          category: item.category,
          change_quantity: `+${item.quantity}`,
          timestamp: new Date().toISOString()
      })),
      total: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0)
  };
  
  fetch('/products/generate-receipt', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify(orderData)
  })
  .then(response => {
      if (!response.ok) {
          throw new Error('Network response was not ok');
      }
      return response.json();
  })
  .then(data => {
      if (!data.success) {
          console.error('Error generating receipt on backend:', data.message);
      }
  })
  .catch(error => {
      console.error('Fetch error:', error);
  });
}
});

///Hide the bills document.addEventListener("DOMContentLoaded", () => {
    // === Element References ===
    const elements = {
        cartItemsContainer: document.getElementById("cart-items"),
        cartTotalElement: document.getElementById("cart-total"),
        cartTable: document.getElementById("cart-table"),
        cartSection: document.getElementById("cart-section"),
        productGrid: document.getElementById("product-grid"),
        cartCountElement: document.getElementById("cart-count"),
        billsNotification: document.getElementById("bills-notification"),
        billsTotal: document.getElementById("bills-total"),
        clearAllBtn: document.getElementById("clear-all"),
        payNowBtn: document.getElementById("PayMent"),
        receiptModal: document.getElementById("receiptModal")
            ? new bootstrap.Modal(document.getElementById("receiptModal"))
            : null,
    };

    // === State Management ===
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let cartVisible = JSON.parse(localStorage.getItem("cartVisible")) || false;

    // === Initialization ===
    toggleCart(cartVisible);
    updateCartDisplay();
    updateCartCount();
    updateBillsNotification();

    // === Event Listeners ===
    function setupEventListeners() {
        // Order buttons
        document.querySelectorAll(".btn-Order").forEach((button) => {
            button.addEventListener("click", () => {
                addToCart(
                    button.dataset.id,
                    button.dataset.name,
                    button.dataset.price,
                    button.dataset.img,
                    button.dataset.category
                );
                toggleCart(true);
            });
        });

        // Cart quantity changes
        if (elements.cartItemsContainer) {
            elements.cartItemsContainer.addEventListener("input", (e) => {
                if (e.target.classList.contains("quantity-input")) {
                    const productId = e.target.dataset.id;
                    const product = cart.find((item) => item.id === productId);

                    if (product) {
                        product.quantity = parseInt(e.target.value);
                        if (product.quantity <= 0) {
                            cart = cart.filter((item) => item.id !== productId);
                        }
                        saveCart();
                    }
                }
            });

            // Remove item from cart
            elements.cartItemsContainer.addEventListener("click", (e) => {
                if (e.target.classList.contains("remove-item")) {
                    const productId = e.target.dataset.id;
                    cart = cart.filter((item) => item.id !== productId);
                    saveCart();
                }
            });
        }

        // Clear cart
        if (elements.clearAllBtn) {
            elements.clearAllBtn.addEventListener("click", () => {
                cart = [];
                saveCart();
                toggleCart(false);
            });
        }

        // Payment
        if (elements.payNowBtn) {
            elements.payNowBtn.addEventListener("click", () => {
                if (cart.length === 0) {
                    alert("Your cart is empty!");
                    return;
                }
                const receiptContent = document.getElementById("receipt-content");
                receiptContent.innerHTML = "<p>Your order has been placed. Thank you!</p>";
                elements.receiptModal.show();
            });
        }

        // OK button in receipt modal
        const okButton = document.getElementById("ok-button");
        if (okButton) {
            okButton.addEventListener("click", () => {
                cart = [];
                saveCart();
                toggleCart(false);
                elements.receiptModal.hide();
            });
        }

        // Confirm receipt
        const confirmButton = document.getElementById("confirm-receipt");
        if (confirmButton) {
            confirmButton.addEventListener("click", () => {
                saveAsPDF();
                cart = [];
                saveCart();
                toggleCart(false);
                elements.receiptModal.hide();
            });
        }

        // Cart icon click to redirect to order page
        const cartIcon = document.getElementById("cart-icon");
        if (cartIcon) {
            cartIcon.addEventListener("click", () => {
                window.location.href = "/path/to/order-page"; // Adjust the URL
            });
        }
    }

    // === Cart Management ===
    function addToCart(id, name, price, img, category) {
        const existingItem = cart.find((item) => item.id === id);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id,
                name,
                price: parseFloat(price),
                quantity: 1,
                img,
                category,
            });
        }
        saveCart();
    }

    function saveCart() {
        localStorage.setItem("cart", JSON.stringify(cart));
        updateCartDisplay();
        updateCartCount();
        updateBillsNotification();
    }

    // === UI Updates ===
    function updateCartDisplay() {
        if (!elements.cartItemsContainer) return; // Skip if not on order page

        const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        elements.cartItemsContainer.innerHTML = "";
        let total = 0;

        cart.forEach((product) => {
            const itemTotal = product.price * product.quantity;
            total += itemTotal;

            const card = document.createElement("div");
            card.classList.add("col-12", "mb-3");
            card.innerHTML = `
                <div class="card h-100 pt-2" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 4px;">
                    <div class="text-center">
                        <div class="image-container">
                            <img src="${product.img}" alt="${product.name}" class="img-fluid mb-2 product-image">
                        </div>
                        <div class="mt-2">
                            <h6 class="card-title text-center mb-1" style="font-size: 1.1em; font-weight:350; color: rgba(101, 67, 33, 0.9);">
                                <strong>${product.name}</strong>
                            </h6>
                            <p class="text-success fw-bold mb-0">
                                $${product.price.toFixed(2)} x 
                                <input type="number" class="quantity-input" value="${product.quantity}" data-id="${product.id}" min="1" style="width: 60px;">
                                = $${itemTotal.toFixed(2)}
                            </p>
                            <button class="btn btn-danger btn-sm remove-item mt-2" data-id="${product.id}">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            `;
            elements.cartItemsContainer.appendChild(card);
        });

        if (elements.cartTotalElement) {
            elements.cartTotalElement.textContent = total.toFixed(2);
        }
        if (elements.cartTable) {
            elements.cartTable.style.display = totalItems > 0 && cartVisible ? "block" : "none";
        }

        if (totalItems === 0) {
            toggleCart(false);
        }
    }

    function toggleCart(show) {
        if (!elements.cartSection || !elements.productGrid) return; // Skip if not on order page

        cartVisible = show;
        localStorage.setItem("cartVisible", JSON.stringify(cartVisible));

        if (show && cart.length > 0) {
            elements.cartSection.style.display = "block";
            elements.productGrid.classList.remove("col-lg-12");
            elements.productGrid.classList.add("col-lg-8");
            elements.cartSection.classList.remove("col-lg-3");
            elements.cartSection.classList.add("col-lg-4");

            document.querySelectorAll("#product-grid .col-md-3").forEach((el) => {
                el.classList.remove("col-md-3");
                el.classList.add("col-md-4");
            });
        } else {
            elements.cartSection.style.display = "none";
            elements.productGrid.classList.remove("col-lg-8");
            elements.productGrid.classList.add("col-lg-12");

            document.querySelectorAll("#product-grid .col-md-4").forEach((el) => {
                el.classList.remove("col-md-4");
                el.classList.add("col-md-3");
            });
        }
        updateCartDisplay();
    }

    function updateCartCount() {
        if (!elements.cartCountElement) return; // Skip if not on a page with cart count

        const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        elements.cartCountElement.textContent = totalItems;
        elements.cartCountElement.style.display = totalItems > 0 ? "inline-block" : "none";
    }

    function updateBillsNotification() {
        if (!elements.billsNotification || !elements.billsTotal) return; // Skip if not on a page with notification

        const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        const totalPrice = cart.reduce((total, item) => total + item.price * item.quantity, 0);

        if (totalItems > 0) {
            elements.billsTotal.textContent = `Bills: $${totalPrice.toFixed(2)}`;
            elements.billsNotification.style.display = "block";
        } else {
            elements.billsNotification.style.display = "none";
        }
    }

    // === Event Listener for Storage Changes ===
    window.addEventListener("storage", (event) => {
        if (event.key === "cart") {
            cart = JSON.parse(localStorage.getItem("cart")) || [];
            updateCartCount();
            updateCartDisplay();
            updateBillsNotification();
        }
    });

    // === Start the App ===
    setupEventListeners();
 