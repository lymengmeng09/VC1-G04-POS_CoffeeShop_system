document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const categorySelect = document.getElementById("categoryFilter");
  const productItems = document.querySelectorAll(".product-item");

  // Function to filter products based on search input and category selection
  function filterProducts() {
    const searchTerm = searchInput.value.toLowerCase(); // Get the search term in lowercase
    const selectedCategory = categorySelect.value.toLowerCase(); // Get selected category

    productItems.forEach(function (product) {
      const productName = product
        .querySelector(".card-title")
        .textContent.toLowerCase(); // Get the product name
      const productCategory = product
        .getAttribute("data-category")
        .toLowerCase(); // Get the product category

      // Check if the product matches the search term and category filter
      const matchesSearch = productName.includes(searchTerm);
      const matchesCategory =
        selectedCategory === "" || productCategory.includes(selectedCategory);

      // Show or hide the product based on the matches
      if (matchesSearch && matchesCategory) {
        product.style.display = ""; // Show product
      } else {
        product.style.display = "none"; // Hide product
      }
    });
  }

  // Event listeners for input changes
  searchInput.addEventListener("input", filterProducts);
  categorySelect.addEventListener("change", filterProducts);
});
