// Get all coffee cards and the "Add New" buttons
const coffeeCards = document.querySelectorAll(".coffee-card");
const cartItemsContainer = document.querySelector(".cart-items");
const totalPriceElement = document.querySelector(".total-price");
const payButton = document.querySelector(".pay-btn");

// Function to update the total price
function updateTotalPrice() {
  const cartItems = cartItemsContainer.querySelectorAll(".cart-item");
  let total = 0;

  cartItems.forEach((item) => {
    const itemPrice = parseFloat(item.dataset.price);
    const itemQuantity = parseInt(item.querySelector(".quantity").textContent);
    total += itemPrice * itemQuantity;
  });

  totalPriceElement.textContent = `$${total.toFixed(2)}`;
}

// Function to add coffee to the cart
function addToCart(coffee) {
  const coffeeId = coffee.dataset.id;
  const coffeeName = coffee.dataset.name;
  const coffeePrice = parseFloat(coffee.dataset.price);

  // Check if the coffee already exists in the cart
  const existingCartItem = document.querySelector(
    `.cart-item[data-id="${coffeeId}"]`
  );
  if (existingCartItem) {
    // If the item already exists, increase the quantity
    const quantityElement = existingCartItem.querySelector(".quantity");
    quantityElement.textContent = parseInt(quantityElement.textContent) + 1;
    updateTotalPrice();
    return;
  }

  // Create a cart item element
  const cartItem = document.createElement("div");
  cartItem.classList.add("cart-item");
  cartItem.dataset.id = coffeeId;
  cartItem.dataset.price = coffeePrice;

  // Add content to the cart item
  cartItem.innerHTML = `
        <span>${coffeeName}</span>
        <span>$${coffeePrice.toFixed(2)}</span>
        <div class="quantity-controls">
            <button class="quantity-btn decrease">-</button>
            <span class="quantity">1</span>
            <button class="quantity-btn increase">+</button>
        </div>
        <button class="remove-btn"><i class="material-icons">delete_forever</i></button>
    `;

  // Add the cart item to the cart
  cartItemsContainer.appendChild(cartItem);

  // Add event listener to the "Remove" button
  const removeButton = cartItem.querySelector(".remove-btn");
  removeButton.addEventListener("click", () => {
    cartItem.remove();
    updateTotalPrice(); // Update total price after removing the item
  });

  // Add event listeners for quantity change
  const decreaseButton = cartItem.querySelector(".decrease");
  const increaseButton = cartItem.querySelector(".increase");
  const quantityElement = cartItem.querySelector(".quantity");

  decreaseButton.addEventListener("click", () => {
    let quantity = parseInt(quantityElement.textContent);
    if (quantity > 1) {
      quantity--;
      quantityElement.textContent = quantity;
      updateTotalPrice();
    }
  });

  increaseButton.addEventListener("click", () => {
    let quantity = parseInt(quantityElement.textContent);
    quantity++;
    quantityElement.textContent = quantity;
    updateTotalPrice();
  });

  // Update total price
  updateTotalPrice();
}

// Add event listeners to each "Add New" button
coffeeCards.forEach((card) => {
  const addButton = card.querySelector(".add-btn");

  addButton.addEventListener("click", () => {
    addToCart(card);
  });
});

// Add event listener to "PAY NOW" button
payButton.addEventListener("click", () => {
  if (cartItemsContainer.children.length > 0) {
    alert("Payment successful!");
    // Clear cart after payment
    cartItemsContainer.innerHTML = "";
    updateTotalPrice(); // Reset the total price
  } else {
    alert("Your cart is empty!");
  }
});



// Sample Product Data
const products = [
    { id: 1, name: "Espresso", price: 3.50, image: "/views/assets/images/coffee.jpg", description: "Strong and bold espresso shot." },
    { id: 4, name: "Latte", price: 4.50, image: "/views/assets/images/coffee.jpg", description: "Smooth and creamy latte." },
    { id: 5, name: "Cappuccino", price: 4.00, image: "/views/assets/images/coffee.jpg", description: "Rich and frothy cappuccino." }
];

// DOM Elements
const coffeeGrid = document.getElementById('coffee-grid');
const modal = document.createElement('div');
modal.className = 'modal';
const modalContent = document.createElement('div');
modalContent.className = 'modal-content';
modal.appendChild(modalContent);
document.body.appendChild(modal);

const cartItems = document.querySelector('.cart-items');
const totalPriceElements = document.querySelector('.total-price');
let totalPrice = 0;

// Function to display product details in modal
function showProductDetails(product) {
    modalContent.innerHTML = `
        <span class="close-btn">&times;</span>
        <img src="${product.image}" alt="${product.name}">
        <h2>${product.name}</h2>
        <p>${product.description}</p>
        <p>Price: $${product.price.toFixed(2)}</p>
        <button class="add-to-cart-btn" data-id="${product.id}">Add to Cart</button>
    `;
    modal.style.display = 'flex';

    // Close modal when clicking on close button
    const closeBtn = modalContent.querySelector('.close-btn');
    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Add to cart functionality
    const addToCartBtn = modalContent.querySelector('.add-to-cart-btn');
    addToCartBtn.addEventListener('click', () => {
        addToCart(product);
        modal.style.display = 'none';
    });
}

// Function to add product to cart
function addToCart(product) {
    const cartItem = document.createElement('div');
    cartItem.className = 'cart-item';
    cartItem.innerHTML = `
        <span>${product.name}</span>
        <span>$${product.price.toFixed(2)}</span>
    `;
    cartItems.appendChild(cartItem);
    totalPrice += product.price;
    totalPriceElements.textContent = `$${totalPrice.toFixed(2)}`;
}

// Render product cards
products.forEach(product => {
    const productCard = document.createElement('div');
    productCard.className = 'product-card';
    productCard.innerHTML = `
        <img src="${product.image}" alt="${product.name}">
        <h3>${product.name}</h3>
        <p>$${product.price.toFixed(2)}</p>
    `;
    productCard.addEventListener('click', () => showProductDetails(product));
    coffeeGrid.appendChild(productCard);
});

// Close modal when clicking outside of it
window.addEventListener('click', (event) => {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});





