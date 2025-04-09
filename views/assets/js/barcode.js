document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const startScannerBtn = document.getElementById('start-scanner');
    const cancelScanBtn = document.getElementById('cancel-scan');
    const simulateScanBtn = document.getElementById('simulate-scan');
    const manualForm = document.getElementById('manual-form');
    const manualInput = document.getElementById('manual-input');
    const cameraContainer = document.getElementById('camera-container');
    const scannerPlaceholder = document.getElementById('scanner-placeholder');
    const scanResult = document.getElementById('scan-result');
    const barcodeResult = document.getElementById('barcode-result');
    const processBarcode = document.getElementById('process-barcode');
    
    // Store detected barcode
    let detectedBarcode = '';
    
    // Start scanner button click handler
    startScannerBtn.addEventListener('click', function() {
        startScanner();
    });
    
    // Cancel scan button click handler
    cancelScanBtn.addEventListener('click', function() {
        stopScanner();
    });
    
    // Simulate scan button click handler (for testing)
    simulateScanBtn.addEventListener('click', function() {
        const testBarcodes = [
            '8849300162009',
            '9780201379624',
            '5901234123457',
            '4006381333931',
            '3800065711087'
        ];
        
        const randomBarcode = testBarcodes[Math.floor(Math.random() * testBarcodes.length)];
        processScannedBarcode(randomBarcode);
    });
    
    // Manual form submit handler
    manualForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const barcode = manualInput.value.trim();
        if (barcode) {
            processScannedBarcode(barcode);
        }
    });
    
    // Process barcode button click handler
    processBarcode.addEventListener('click', function() {
        if (detectedBarcode) {
            fetchProductDetails(detectedBarcode);
        }
    });

    function fetchProductDetails(barcode) {
        fetch(`ScannerController.php?action=processBarcode&barcode=${encodeURIComponent(barcode)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayProductDetails(data.product);
                } else {
                    displayError(data.message);
                }
            })
            .catch(error => {
                console.error('Error fetching product:', error);
                displayError('Error fetching product details');
            });
    }

    function displayProductDetails(product) {
        const resultContainer = document.getElementById('scan-result');
        resultContainer.innerHTML = `
            <div class="alert alert-success">
                <h4>Product Found</h4>
                <p><strong>Name:</strong> ${product.name}</p>
                <p><strong>Price:</strong> $${product.price}</p>
                <p><strong>Stock:</strong> ${product.quantity}</p>
                ${product.image ? `<img src="${product.image}" class="img-fluid mt-2" style="max-width: 200px;" alt="${product.name}">` : ''}
            </div>
        `;
        resultContainer.classList.remove('d-none');
    }

    function displayError(message) {
        const resultContainer = document.getElementById('scan-result');
        resultContainer.innerHTML = `
            <div class="alert alert-warning">
                ${message}
            </div>
        `;
        resultContainer.classList.remove('d-none');
    }

    function startScanner() {
        cameraContainer.classList.remove('d-none');
        scannerPlaceholder.classList.add('d-none');
        scanResult.classList.add('d-none');
        
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#interactive'),
                constraints: {
                    width: 640,
                    height: 480,
                    facingMode: "environment"
                },
            },
            locator: {
                patchSize: "medium",
                halfSample: true
            },
            numOfWorkers: navigator.hardwareConcurrency || 4,
            decoder: {
                readers: [
                    "code_128_reader",
                    "ean_reader",
                    "ean_8_reader",
                    "code_39_reader",
                    "code_39_vin_reader",
                    "codabar_reader",
                    "upc_reader",
                    "upc_e_reader",
                    "i2of5_reader"
                ]
            },
            locate: true
        }, function(err) {
            if (err) {
                console.error("Error initializing Quagga:", err);
                alert("Error starting the barcode scanner. Please make sure you've granted camera permissions.");
                stopScanner();
                return;
            }
            Quagga.start();
        });
        
        Quagga.onDetected(function(result) {
            if (result && result.codeResult && result.codeResult.code) {
                const code = result.codeResult.code;
                const beep = new Audio("data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YU");
                beep.play();
                processScannedBarcode(code);
                stopScanner();
            }
        });
    }
    
    function stopScanner() {
        Quagga.stop();
        cameraContainer.classList.add('d-none');
        scannerPlaceholder.classList.remove('d-none');
    }
    
    function processScannedBarcode(barcode) {
        detectedBarcode = barcode;
        barcodeResult.textContent = barcode;
        scanResult.classList.remove('d-none');
        cameraContainer.classList.add('d-none');
        manualInput.value = '';
        fetchProductDetails(barcode);
    }
    
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        startScannerBtn.disabled = true;
        startScannerBtn.textContent = "Camera not supported";
        document.querySelector('#scanner-placeholder p').textContent = 
            "Your browser doesn't support camera access. Please enter the barcode manually.";
    }
});