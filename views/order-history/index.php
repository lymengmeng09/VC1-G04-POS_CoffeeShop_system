<h3><?php echo __('Order History'); ?></h3>
<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo __('order id'); ?></th>
                    <th><?php echo __('Total amount'); ?></th>
                    <th><?php echo __('item count'); ?></th>
                    <th><?php echo __('date'); ?></th>
                    <th><?php echo __('actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']) ?></td>
                        <td>$<?= number_format($order['total_amount'], 2) ?></td>
                        <td><?= htmlspecialchars($order['item_count']) ?></td>
                        <td><?= htmlspecialchars($order['created_at']) ?></td>
                        <td>
                            <a href="/order-history/details/<?= htmlspecialchars($order['order_id']) ?>" class="btn btn-sm btn-primary"><?php echo __('View details'); ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>