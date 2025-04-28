<h5 class="mb-4 text-uppercase text-primary">
    <?php echo __('Order Details'); ?> - <?php echo __('Order ID'); ?> <?= htmlspecialchars($order['order_id']) ?>
</h5>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="mb-4">
            <p class="mb-1"><strong><?php echo __('Total Amount'); ?>:</strong> <span class="text-success">$<?= number_format($order['total_amount'], 2) ?></span></p>
            <p class="mb-1"><strong><?php echo __('Date'); ?>:</strong> <?= htmlspecialchars($order['created_at']) ?></p>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th><?php echo __('Product Name'); ?></th>
                        <th><?php echo __('Quantity'); ?></th>
                        <th><?php echo __('Price'); ?></th>
                        <th><?php echo __('Total'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-end mt-4">
            <a href="/order-history" class="btn btn-outline-primary">
                <?php echo __('Back'); ?>
            </a>
        </div>
    </div>
</div>
