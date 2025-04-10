document.addEventListener('DOMContentLoaded', function() {
    const scannerPlaceholder = document.getElementById('scanner-placeholder');
    const startScannerBtn = document.getElementById('start-scanner');
    const manualForm = document.getElementById('manual-form');
    const manualInput = document.getElementById('manual-input');
    const readerDiv = document.getElementById('reader');
    const loadingIndicator = document.getElementById('loading-indicator');
    const errorMessage = document.getElementById('error-message');
    const errorBarcode = document.getElementById('error-barcode');
    const scanAgainErrorBtn = document.getElementById('scan-again-error');
    const scanAgainSuccessBtn = document.getElementById('scan-again-success');
    const productDetailsContainer = document.getElementById('product-details-container');
    
    let html5QrCode;

    // Start scanner button click
    startScannerBtn.addEventListener('click', function() {
        readerDiv.style.display = 'block';
        startScanner();
    });

    // Scan again buttons click
    scanAgainErrorBtn.addEventListener('click', resetScanner);
    scanAgainSuccessBtn.addEventListener('click', resetScanner);

    // Manual form submission
    manualForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const barcode = manualInput.value.trim();
        if (barcode) {
            processBarcode(barcode);
            manualInput.value = '';
        }
    });

    // Function to start the scanner
    function startScanner() {
        html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 250, height: 150 } };
        
        html5QrCode.start(
            { facingMode: "environment" }, 
            config, 
            onScanSuccess, 
            onScanFailure
        );
    }

    // Function to stop the scanner
    function stopScanner() {
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop().catch(error => {
                console.error("Error stopping scanner:", error);
            });
        }
    }

    // Function to reset the scanner
    function resetScanner() {
        errorMessage.classList.add('d-none');
        productDetailsContainer.classList.add('d-none');
        scannerPlaceholder.classList.remove('d-none');
        readerDiv.style.display = 'none';
    }

    // Function called on successful scan
    function onScanSuccess(decodedText) {
        // Stop the scanner immediately
        stopScanner();
        
        // Process the barcode automatically
        processBarcode(decodedText);
    }

    // Function called on scan failure
    function onScanFailure(error) {
        // We can ignore this as it's usually just frames without barcodes
    }

    // Function to process the barcode and fetch product details
    function processBarcode(barcode) {
        // Show loading indicator and hide other elements
        scannerPlaceholder.classList.add('d-none');
        loadingIndicator.classList.remove('d-none');
        errorMessage.classList.add('d-none');
        productDetailsContainer.classList.add('d-none');
        
        // Fetch product details from the server
        fetch(`<?= BASE_URL ?>/scanner/processBarcode?barcode=${encodeURIComponent(barcode)}`)
            .then(response => response.json())
            .then(data => {
                loadingIndicator.classList.add('d-none');
                
                if (data.success) {
                    displayProductDetails(data.product);
                } else {
                    errorBarcode.textContent = barcode;
                    errorMessage.querySelector('p').textContent = data.message || 'Product not found for this barcode.';
                    errorMessage.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loadingIndicator.classList.add('d-none');
                errorBarcode.textContent = barcode;
                errorMessage.querySelector('p').textContent = 'Error processing barcode. Please try again.';
                errorMessage.classList.remove('d-none');
            });
    }

    // Function to display product details
    function displayProductDetails(product) {
        console.log('Product data received:', product);
        
        // Populate product details based on your actual database structure
        document.getElementById('product-name').textContent = product.name || '';
        document.getElementById('product-sku').textContent = product.id || '';
        document.getElementById('product-price').textContent = '$' + parseFloat(product.price || 0).toFixed(2);
        document.getElementById('product-quantity').textContent = product.quantity || 0;
        document.getElementById('product-category').textContent = 'Beverages'; // You can add a category field to your database
        document.getElementById('product-location').textContent = 'Shelf A1'; // You can add a location field to your database
        
        // Set product image
        const productImage = document.getElementById('product-image');
        if (product.image) {
            productImage.src = '<?= BASE_URL ?>/' + product.image;
        } else {
            productImage.src = '<?= BASE_URL ?>/assets/images/placeholder.png';
        }
        productImage.alt = product.name || 'Product Image';
        
        // Set product status badges
        const statusContainer = document.getElementById('product-status');
        statusContainer.innerHTML = '';
        
        const quantity = parseInt(product.quantity || 0);
        const reorderLevel = 5; // You can add a reorder_level field to your database
        
        if (quantity > 0) {
            statusContainer.innerHTML += '<span class="badge bg-success">In Stock</span>';
        } else {
            statusContainer.innerHTML += '<span class="badge bg-danger">Out of Stock</span>';
        }
        
        if (quantity <= reorderLevel && quantity > 0) {
            statusContainer.innerHTML += '<span class="badge bg-warning text-dark ms-2">Low Stock</span>';
        }
        
        // Show product details container
        productDetailsContainer.classList.remove('d-none');
    }
});
document.addEventListener('DOMContentLoaded', function() {
    const updateQuantityBtn = document.getElementById('update-quantity');
    const stockSelect = document.getElementById('stock-select');

    // Fetch and populate products (your stock list) on page load
    fetch('<?= BASE_URL ?>/scanner/getStock')
        .then(response => response.json())
        .then(data => {
            const products = data.products;
            products.forEach(product => {
                const option = document.createElement('option');
                option.value = product.barcode; // or product.id if you prefer
                option.textContent = product.name;
                stockSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading stock:', error);
        });

    // Update quantity by 1 when selected product is chosen
    updateQuantityBtn.addEventListener('click', function() {
        const selectedBarcode = stockSelect.value;

        if (!selectedBarcode) {
            alert('Please select a product from the stock list.');
            return;
        }

        // Send the selected barcode to PHP to update the quantity
        fetch(`<?= BASE_URL ?>/scanner/updateStockQuantity`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `barcode=${encodeURIComponent(selectedBarcode)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Quantity updated by 1!');
                // Optionally, you can update the UI to reflect the new quantity
            } else {
                alert('Error updating quantity: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
