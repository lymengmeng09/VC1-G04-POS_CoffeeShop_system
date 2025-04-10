<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h2 class="h5 mb-0 text-dark">Scan Barcode</h2>
                </div>
                <div class="card-body">
                    <div id="scanner-container">
                        <!-- Scanner placeholder (shown when not scanning) -->
                        <div id="scanner-placeholder">
                            <div class="text-center p-4 border border-2 border-dashed rounded mb-3 bg-light">
                                <i class="bi bi-upc-scan fs-1 text-secondary mb-2"></i>
                                <p class="text-secondary">Click the button below to scan a barcode</p>
                            </div>

                            <form id="manual-form" class="d-flex gap-2">
                                <input type="text" id="manual-input" class="form-control"
                                    placeholder="Enter barcode number">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>

                        <!-- Scan result (shown after successful scan) -->
                        <div id="scan-result" class="mt-3 d-none">
                            <div class="alert alert-success">
                                <strong>Barcode detected:</strong> <span id="barcode-result"></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button id="scan-again" class="btn btn-outline-secondary">Scan Again</button>
                                <button id="process-barcode" class="btn btn-success">Process Barcode</button>
                            </div>
                        </div>

                        <!-- Loading indicator -->
                        <div id="loading-indicator" class="text-center py-4 d-none">
                            <div class="spinner-border text-primary mb-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p>Processing barcode...</p>
                        </div>

                        <!-- Error message -->
                        <div id="error-message" class="alert alert-danger mt-3 d-none">
                            Product not found for this barcode.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product details section (will be populated after scanning) -->
    <div id="product-details-container" class="row justify-content-center mt-4 d-none">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Product Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img id="product-image" src="/placeholder.svg" alt="Product Image"
                                class="img-fluid rounded mb-3" style="max-height: 200px;">
                        </div>
                        <div class="col-md-8">
                            <h3 id="product-name"></h3>
                            <p class="text-muted">SKU: <span id="product-sku"></span></p>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Price</small>
                                    <p class="fw-bold" id="product-price"></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">In Stock</small>
                                    <p class="fw-bold" id="product-quantity"></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Category</small>
                                    <p id="product-category"></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Location</small>
                                    <p id="product-location"></p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Status</small><br>
                                <div id="product-status"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>