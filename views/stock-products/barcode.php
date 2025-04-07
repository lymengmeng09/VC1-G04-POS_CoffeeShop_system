<div class="container py-4">
        <h1 class="mb-4">Inventory Management</h1>
        
        <div class="row g-4">
            <!-- Barcode Scanner Section -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0">Scan Barcode</h2>
                    </div>
                    <div class="card-body">
                        <div id="scanner-container">
                            <div id="camera-container" class="d-none">
                                <div class="position-relative mb-3">
                                    <video id="video" class="w-100 rounded bg-dark" autoplay playsinline></video>
                                    <div class="scanner-overlay">
                                        <div class="scanner-box"></div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mb-3">
                                    <button id="cancel-scan" class="btn btn-outline-secondary flex-grow-1">Cancel</button>
                                    <button id="simulate-scan" class="btn btn-primary flex-grow-1">Simulate Scan</button>
                                </div>
                            </div>
                            
                            <div id="scanner-placeholder">
                                <div class="text-center p-4 border border-2 border-dashed rounded mb-3 bg-light">
                                    <i class="bi bi-upc-scan fs-1 text-secondary mb-2"></i>
                                    <p class="text-secondary">Click the button below to scan a barcode</p>
                                </div>
                                <button id="start-scanner" class="btn btn-primary w-100 mb-3">
                                    <i class="bi bi-camera me-2"></i> Start Scanner
                                </button>
                                
                                <div class="separator my-3">
                                    <span>Or enter manually</span>
                                </div>
                                
                                <form id="manual-form" class="d-flex gap-2">
                                    <input type="text" id="manual-input" class="form-control" placeholder="Enter barcode number">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product Details Section -->
            <div class="col-md-6">
                <div id="product-container" class="d-none">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h2 class="h5 mb-0">Product Details</h2>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <div>
                                    <img id="product-image" src="/placeholder.svg" alt="Product" class="rounded border" width="100" height="100">
                                </div>
                                <div>
                                    <h3 id="product-name" class="h5"></h3>
                                    <p id="product-sku" class="small text-secondary"></p>
                                    <p id="product-price" class="fw-medium"></p>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <span class="small fw-medium">Current Stock:</span>
                                        <span id="product-stock" class="badge bg-primary"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Inventory Update Form -->
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0">Update Stock Quantity</h2>
                        </div>
                        <div class="card-body">
                            <form id="update-form">
                                <div class="mb-3">
                                    <label for="stock-quantity" class="form-label">New Quantity</label>
                                    <div class="input-group">
                                        <button type="button" id="decrement" class="btn btn-outline-secondary">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" id="stock-quantity" class="form-control text-center" min="0">
                                        <button type="button" id="increment" class="btn btn-outline-secondary">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="d-flex justify-content-between border rounded p-2 small">
                                            <span class="text-secondary">Current:</span>
                                            <span id="current-stock" class="fw-medium"></span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex justify-content-between border rounded p-2 small">
                                            <span class="text-secondary">New:</span>
                                            <span id="new-stock" class="fw-medium"></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between border rounded p-2 small">
                                            <span class="text-secondary">Change:</span>
                                            <span id="stock-change" class="fw-medium"></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100">Update Inventory</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Success Alert -->
        <div id="success-alert" class="alert alert-success mt-4 d-none">
            <i class="bi bi-check-circle me-2"></i>
            <span id="success-message"></span>
        </div>
    </div>