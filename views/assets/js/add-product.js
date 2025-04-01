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