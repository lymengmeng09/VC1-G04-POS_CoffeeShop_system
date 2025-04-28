<h5><?php echo __('order_details'); ?> - Order #<?= htmlspecialchars($order['order_id']) ?></h5>
<div class="card">
    <div class="card-body">
        <p><strong><?php echo __('total_amount'); ?>:</strong> $<?= number_format($order['total_amount'], 2) ?></p>
        <p><strong><?php echo __('date'); ?>:</strong> <?= htmlspecialchars($order['created_at']) ?></p>
        <h6><?php echo __('items'); ?>:</h6>
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo __('product_name'); ?></th>
                    <th><?php echo __('quantity'); ?></th>
                    <th><?php echo __('price'); ?></th>
                    <th><?php echo __('total'); ?></th>
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
        <a href="/order-history" class="btn btn-secondary" style="background-color:rgb(141, 140, 140); color: white; outline: none;">
            <?php echo __('back'); ?>
        </a>

    </div>
</div>