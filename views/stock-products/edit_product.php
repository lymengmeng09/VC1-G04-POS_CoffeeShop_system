<h5><?php echo __('edit_stock'); ?></h5>
<div class="card">
    <div class="card-body">

        <form action="/update_product?id=<?php echo $product['id']; ?>" method="post">
            <div class="mb-3">
                <label for="name" class="form-label"><?php echo __('product_name'); ?></label>
                <input type="text" class="form-control" id="name" name="name"
                    value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label"><?php echo __('price'); ?></label>
                <input type="number" step="0.01" class="form-control" id="price" name="price"
                    value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label"><?php echo __('quantity'); ?></label>
                <input type="number" class="form-control" id="quantity" name="quantity"
                    value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
            </div>

            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
            <div class="d-flex">
                <button type="submit" class="btn btn-success m-2"><?php echo __('submit'); ?></button>
                <a href="/viewStock" class="btn btn-outline-secondary m-2"><?php echo __('cancel'); ?></a>
            </div>

        </form>
    </div>
</div>