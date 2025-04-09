<div class="card">
    <div class="card-header bg-white">
        <h2 class="h5 mb-0 text-dark">Scan Barcode</h2>
    </div>
    <div class="card-body">
        <div id="scanner-container">

            <div id="scanner-placeholder">
                <div class="text-center p-4 border border-2 border-dashed rounded mb-3 bg-light">
                    <i class="bi bi-upc-scan fs-1 text-secondary mb-2"></i>
                    <p class="text-secondary">Click the button below to scan a barcode</p>
                </div>

                <form id="manual-form" class="d-flex gap-2">
                    <input type="text" id="manual-input" class="form-control" placeholder="Enter barcode number">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            
            <div id="scan-result" class="mt-3 d-none">
                <div class="alert alert-success">
                    <strong>Barcode detected:</strong> <span id="barcode-result"></span>
                </div>
                <button id="process-barcode" class="btn btn-success w-100">Process Barcode</button>
            </div>
        </div>
    </div>
</div>

