<div class="row justify-content-center mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Product Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="/<?= htmlspecialchars($product['image_url']) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>" 
                             class="img-fluid rounded mb-3" style="max-height: 200px;">
                    </div>
                    <div class="col-md-8">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="text-muted">SKU: <?= htmlspecialchars($product['sku']) ?></p>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted">Price</small>
                                <p class="fw-bold">$<?= number_format($product['price'], 2) ?></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">In Stock</small>
                                <p class="fw-bold"><?= $product['quantity'] ?></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Category</small>
                                <p><?= htmlspecialchars($product['category']) ?></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Location</small>
                                <p><?= htmlspecialchars($product['location']) ?></p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">Status</small><br>
                            <?php if ($product['quantity'] > 0): ?>
                                <span class="badge bg-success">In Stock</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Out of Stock</span>
                            <?php endif; ?>
                            
                            <?php if ($product['quantity'] <= $product['reorder_level'] && $product['quantity'] > 0): ?>
                                <span class="badge bg-warning text-dark ms-2">Low Stock</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
