document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const categoryDropdown = document.getElementById("categoryDropdown");
  const productItems = document.querySelectorAll(".product-item");
  const categoryFilterBtn = document.getElementById("categoryFilterBtn");

  let selectedCategory = 'all'; // Default to 'all' category

  // Function to filter products based on search input and category selection
  function filterProducts() {
    const searchTerm = searchInput.value.toLowerCase(); // Get the search term in lowercase

    productItems.forEach(function (product) {
      const productName = product.querySelector(".card-title").textContent.toLowerCase(); 
      const productCategory = product.getAttribute("data-category").toLowerCase(); 

      // Check if the product matches the search term and category filter
      const matchesSearch = productName.includes(searchTerm);
      const matchesCategory = selectedCategory === 'all' || productCategory.includes(selectedCategory);

      // Show or hide the product based on the matches
      if (matchesSearch && matchesCategory) {
        product.style.display = ""; 
      } else {
        product.style.display = "none"; 
      }
    });
  }

  // Event listener for search input
  searchInput.addEventListener("input", filterProducts);

  // Event listeners for category selection
  categoryDropdown.addEventListener("click", function (e) {
    if (e.target && e.target.matches("a.dropdown-item")) {
      selectedCategory = e.target.getAttribute("data-category"); // Get selected category
      categoryFilterBtn.textContent = "categories: " + (selectedCategory === 'all' ? 'All' : selectedCategory.charAt(0).toUpperCase() + selectedCategory.slice(1)); // Update button text
      filterProducts(); // Apply filtering
    }
  });
});


// Function to add product to the shopping cart
function addToCart(productName, productPrice, productImg) {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  const product = {
    id: Date.now(),
    name: productName,
    price: parseFloat(productPrice),
    quantity: 1,
    img: productImg
  };
  
  cart.push(product);
  localStorage.setItem('cart', JSON.stringify(cart));
  updateCartDisplay();
}

// Event listener for "Order New" buttons
document.querySelectorAll('.btn-Order').forEach(button => {
  button.addEventListener('click', function() {
    const productName = this.getAttribute('data-name');
    const productPrice = this.getAttribute('data-price');
    const productImg = this.getAttribute('data-img');
    
    // Add product to the cart
    addToCart(productName, productPrice, productImg);
  });
});

// Update cart display
function updateCartDisplay() {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  const cartTableBody = document.getElementById('cart-table-body');
  const cartTotalElement = document.getElementById('cart-total');
  const cartCountElement = document.getElementById('cart-count');
  const cartTable = document.getElementById('cart-table');

  // Update cart count and display
  const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
  cartCountElement.textContent = totalItems;
  
  if (totalItems === 0) {
    cartTable.style.display = 'none';
  } else {
    cartTable.style.display = 'block';
    displayCartItems(cart);
  }
}

// Function to display cart items
function displayCartItems(cart) {
  const cartTableBody = document.getElementById('cart-table-body');
  const cartTotalElement = document.getElementById('cart-total');
  
  cartTableBody.innerHTML = '';
  let total = 0;
  
  cart.forEach(product => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td><img src="${product.img}" alt="${product.name}" class="cart-item-image" style="width: 50px;"></td>
      <td>${product.name}</td>
      <td>$${product.price}</td>
      <td><input type="number" class="quantity-input" value="${product.quantity}" data-id="${product.id}" min="1"></td>
      <td><button class="remove-item" data-id="${product.id}">Remove</button></td>
    `;
    cartTableBody.appendChild(row);
    total += product.price * product.quantity;
  });
  
  cartTotalElement.textContent = total.toFixed(2);
}

// Cart quantity change
document.getElementById('cart-table-body').addEventListener('input', function (e) {
  if (e.target.classList.contains('quantity-input')) {
    const productId = parseInt(e.target.getAttribute('data-id'));
    const newQuantity = parseInt(e.target.value);
    
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const product = cart.find(item => item.id === productId);
    
    if (product) {
      product.quantity = newQuantity;
      localStorage.setItem('cart', JSON.stringify(cart));
      updateCartDisplay();
    }
  }
});

// Remove product from cart
document.getElementById('cart-table-body').addEventListener('click', function (e) {
  if (e.target && e.target.classList.contains('remove-item')) {
    const productId = parseInt(e.target.getAttribute('data-id'));
    
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const updatedCart = cart.filter(item => item.id !== productId);
    
    localStorage.setItem('cart', JSON.stringify(updatedCart));
    updateCartDisplay();
  }
});

// Cart Management Functions
function addToCart(productName, productPrice, productImg) {
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  
  // Check if product already exists in cart
  const existingProduct = cart.find(item => item.name === productName);
  
  if (existingProduct) {
      // If product exists, increment its quantity
      existingProduct.quantity += 1;
  } else {
      // If product doesn't exist, add new product
      const product = {
          id: Date.now(),
          name: productName,
          price: parseFloat(productPrice),
          quantity: 1,
          img: productImg
      };
      cart.push(product);
  }
  
  localStorage.setItem('cart', JSON.stringify(cart));
  updateCartDisplay();
}

// Event Listeners for Cart Actions
document.addEventListener('DOMContentLoaded', function() {
  // Clear all products from cart
  const clearButton = document.getElementById('clear-all');
  if (clearButton) {
      clearButton.addEventListener('click', function() {
          localStorage.removeItem('cart');
          updateCartDisplay();
      });
  }

  // Payment button action
  const paymentButton = document.getElementById('PayMent');
  if (paymentButton) {
      paymentButton.addEventListener('click', function() {
          const cart = JSON.parse(localStorage.getItem('cart')) || [];
          
          if (cart.length === 0) {
              alert("Your cart is empty!");
          } else {
              localStorage.removeItem('cart');
              updateCartDisplay();
          }
      });
  }
});