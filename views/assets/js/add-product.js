document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const categoryDropdown = document.getElementById("categoryDropdown");
  const productItems = document.querySelectorAll(".product-item");
  const categoryFilterBtn = document.getElementById("categoryFilterBtn");

  let selectedCategory = "all"; // Default to 'all' category

  // Function to filter products based on search input and category selection
  function filterProducts() {
    const searchTerm = searchInput.value.toLowerCase(); // Get the search term in lowercase

    productItems.forEach(function (product) {
      const productName = product
        .querySelector(".card-title")
        .textContent.toLowerCase();
      const productCategory = product
        .getAttribute("data-category")
        .toLowerCase();

      // Check if the product matches the search term and category filter
      const matchesSearch = productName.includes(searchTerm);
      const matchesCategory =
        selectedCategory === "all" ||
        productCategory.includes(selectedCategory);

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
      categoryFilterBtn.textContent =
        "categories: " +
        (selectedCategory === "all"
          ? "All"
          : selectedCategory.charAt(0).toUpperCase() +
            selectedCategory.slice(1)); // Update button text
      filterProducts(); // Apply filtering
    }
  });
});

// Function to add product to the shopping cart
function addToCart(productName, productPrice, productImg) {
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  const product = {
    id: Date.now(),
    name: productName,
    price: parseFloat(productPrice),
    quantity: 1,
    img: productImg,
  };

  cart.push(product);
  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartDisplay();
}

// Event listener for "Order New" buttons
document.querySelectorAll(".btn-Order").forEach((button) => {
  button.addEventListener("click", function () {
    const productName = this.getAttribute("data-name");
    const productPrice = this.getAttribute("data-price");
    const productImg = this.getAttribute("data-img");

    // Add product to the cart
    addToCart(productName, productPrice, productImg);
  });
});

// Update cart display
function updateCartDisplay() {
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  const cartTableBody = document.getElementById("cart-table-body");
  const cartTotalElement = document.getElementById("cart-total");
  const cartCountElement = document.getElementById("cart-count");
  const cartTable = document.getElementById("cart-table");

  // Update cart count and display
  const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
  cartCountElement.textContent = totalItems;

  if (totalItems === 0) {
    cartTable.style.display = "none";
  } else {
    cartTable.style.display = "block";
    displayCartItems(cart);
  }
}


// Function to display cart items
function displayCartItems(cart) {
  const cartTableBody = document.getElementById("cart-table-body");
  const cartTotalElement = document.getElementById("cart-total");

  cartTableBody.innerHTML = "";
  let total = 0;

  cart.forEach((product) => {
    const row = document.createElement("tr");
    row.innerHTML = `
        <td><img src="${product.img}" alt="${product.name}" class="cart-item-image" style="width: 50px;"></td>
        <td>${product.name}</td>
        <td>$${product.price}</td>
        <td><input type="number" class="quantity-input" value="${product.quantity}" data-id="${product.id}"></td>
        <td><i class="bi bi-trash remove-item" data-id="${product.id}"></i></td>
      `;
    cartTableBody.appendChild(row);
    total += product.price * product.quantity;
  });

  cartTotalElement.textContent = total.toFixed(2);
}

// Cart quantity change
document
  .getElementById("cart-table-body")
  .addEventListener("input", function (e) {
    if (e.target.classList.contains("quantity-input")) {
      const productId = parseInt(e.target.getAttribute("data-id"));
      const newQuantity = parseInt(e.target.value);

      const cart = JSON.parse(localStorage.getItem("cart")) || [];
      const product = cart.find((item) => item.id === productId);

      if (product) {
        product.quantity = newQuantity;
        localStorage.setItem("cart", JSON.stringify(cart));
        displayCartItems(cart); // Call the function to update the cart display
      }
    }
  });

// Remove cart item
document
  .getElementById("cart-table-body")
  .addEventListener("click", function (e) {
    if (e.target.classList.contains("remove-item")) {
      const productId = parseInt(e.target.getAttribute("data-id"));
      
      // Get the current cart
      const cart = JSON.parse(localStorage.getItem("cart")) || [];
      
      // Remove the item with the matching productId
      const updatedCart = cart.filter((item) => item.id !== productId);
      
      // Save the updated cart to localStorage
      localStorage.setItem("cart", JSON.stringify(updatedCart));
      
      // Update the cart display
      displayCartItems(updatedCart);
    }
  });

// Remove product from cart
document
.getElementById("cart-table-body")
.addEventListener("click", function (e) {
  if (e.target && e.target.classList.contains("remove-item")) {
    const productId = parseInt(e.target.getAttribute("data-id"));

    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const updatedCart = cart.filter((item) => item.id !== productId);

    localStorage.setItem("cart", JSON.stringify(updatedCart));
    updateCartDisplay();
  }
});

// Cart Management Functions
function addToCart(productName, productPrice, productImg) {
let cart = JSON.parse(localStorage.getItem("cart")) || [];

// Check if product already exists in cart
const existingProduct = cart.find((item) => item.name === productName);

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
    img: productImg,
  };
  cart.push(product);
}

localStorage.setItem("cart", JSON.stringify(cart));
updateCartDisplay();
}





// rechipe
//  recept
// Cart management
let cart = [];
const cartCountElement = document.getElementById("cart-count");
const payNowButton = document.getElementById("PayMent");
let receiptModal;
const receiptContent = document.getElementById("receipt-content");

// Initialize the modal
try {
  receiptModal = new bootstrap.Modal(document.getElementById("receiptModal"));
  console.log("Modal initialized successfully");
} catch (error) {
  console.error("Error initializing modal:", error);
}

// Load cart from localStorage
if (localStorage.getItem("cart")) {
  cart = JSON.parse(localStorage.getItem("cart"));
  console.log("Cart loaded from localStorage:", cart);
  updateCartCount();
} else {
  console.log("No cart data in localStorage");
}

// Update cart count display
function updateCartCount() {
  let itemCount = 0;
  cart.forEach((item) => {
    itemCount += item.quantity;
  });
  cartCountElement.textContent = itemCount;
  console.log("Cart count updated:", itemCount);
}

// Add product to cart
document.querySelectorAll(".btn-Order").forEach((button) => {
  button.addEventListener("click", function () {
    const name = this.getAttribute("data-name");
    const price = parseFloat(this.getAttribute("data-price"));
    const img = this.getAttribute("data-img");

    const existingItem = cart.find((item) => item.name === name);
    if (existingItem) {
      existingItem.quantity += 1;
    } else {
      cart.push({ name, price, img, quantity: 1 });
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    console.log("Product added to cart:", {
      name,
      price,
      img,
      quantity: existingItem ? existingItem.quantity : 1,
    });
    updateCartCount();
  });
});

// Pay Now button
payNowButton.addEventListener("click", function () {
  // Generate receipt HTML directly from cart data
  let totalPrice = 0;
  const receiptItems = cart
    .map((item) => {
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
    })
    .join("");

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
                    ${
                      receiptItems ||
                      '<tr><td colspan="5">No items in cart</td></tr>'
                    }
                    <tr class="total-row">
                        <td colspan="3"><strong>TOTAL PRICE</strong></td>
                        <td colspan="2"><strong>$${totalPrice.toFixed(
                          2
                        )}</strong></td>
                    </tr>
                </tbody>
            </table>
        `;
  console.log("Receipt content generated:", receiptContent.innerHTML);

  // Show the receipt modal
  if (receiptModal) {
    receiptModal.show();
    console.log("Receipt modal shown");
  } else {
    console.error("Receipt modal not initialized");
  }


  // Prepare order data for backend
  const orderData = {
    items: cart.map((item) => ({
      name: item.name,
      price: item.price,
      change_quantity: `+${item.quantity}`,
      timestamp: new Date().toISOString(),
    })),
    total: totalPrice,
  };
  console.log("Order data prepared:", orderData);

  // Send order data to backend
  fetch("/products/generate-receipt", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-Requested-With": "XMLHttpRequest",
    },
    body: JSON.stringify(orderData),
  })
    .then((response) => {
      console.log("Fetch response received:", response);
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      console.log("Fetch data:", data);
      if (!data.success) {
        console.error("Error generating receipt on backend:", data.message);
      }
    })
    .catch((error) => {
      console.error("Fetch error:", error);
    });
});
//unctionality
document.querySelectorAll(".dropbtn").forEach((button) => {
  button.addEventListener("click", function () {
    const dropdownContent = this.nextElementSibling;
    dropdownContent.style.display =
      dropdownContent.style.display === "block" ? "none" : "block";
    console.log("Dropdown toggled:", dropdownContent.style.display);
  });
});
// Fix the cancel button functionality
document.getElementById("clear-all").addEventListener("click", function () {
  // Clear the cart array
  cart = [];

  // Remove from localStorage
  localStorage.removeItem("cart");

  // Update the cart count display
  updateCartCount();

  // Hide the cart table
  document.getElementById("cart-table").style.display = "none";

  console.log("Cart cleared and hidden");
});

// Modify the confirm receipt button to save as HTML
document
  .getElementById("confirm-receipt")
  .addEventListener("click", function () {
    // Check if cart has items
    if (cart.length === 0) {
      console.error("Cart is empty!");
      alert("Error: Cannot save an empty receipt.");
      return;
    }

    // Save as HTML file
    saveAsHTML();

    // Clear cart and close modal after saving
    cart = [];
    localStorage.removeItem("cart");
    updateCartCount();
    receiptModal.hide();
  });

// Function to save receipt as HTML
function saveAsHTML() {
  // Get current date and time
  const now = new Date();
  const dateStr = now.toLocaleDateString();
  const timeStr = now.toLocaleTimeString();

  // Calculate total
  let totalPrice = 0;
  cart.forEach((item) => {
    totalPrice += item.quantity * item.price;
  });

  // Create HTML content with styling
  let htmlContent = `

 
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .receipt {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #f8f8f8;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f8f8;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }
        .timestamp {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>


    <div class="receipt">
    <div class="header">
        <h1>Order Receipt</h1>
    </div>
    
    <div class="timestamp">
        <p><strong>Date:</strong> ${dateStr}</p>
        <p><strong>Time:</strong> ${timeStr}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
`;

  // Add each item to the HTML table
  cart.forEach((item) => {
    const itemTotal = item.quantity * item.price;
    htmlContent += `
            <tr>
                <td>${item.name}</td>
                <td>${item.quantity}</td>
                <td>$${item.price.toFixed(2)}</td>
                <td>$${itemTotal.toFixed(2)}</td>
            </tr>
`;
  });

  // Add total row and close the HTML
  htmlContent += `
            <tr class="total-row">
                <td colspan="3"><strong>TOTAL</strong></td>
                <td><strong>$${totalPrice.toFixed(2)}</strong></td>
            </tr>
        </tbody>
    </table>
    
 
</div>

`;

  // Create a Blob with the HTML content
  const blob = new Blob([htmlContent], { type: "text/html;charset=utf-8;" });

  // Create a download link and trigger it
  const link = document.createElement("a");
  const url = URL.createObjectURL(blob);
  link.setAttribute("href", url);
  link.setAttribute("download", `receipt_${now.getTime()}.html`);
  link.style.visibility = "hidden";
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}
