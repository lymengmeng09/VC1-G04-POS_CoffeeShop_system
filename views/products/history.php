<h5><?php echo __('order_history'); ?></h5>

<div class="card">
    <div class="card-body">
        <?php if (empty($orders)): ?>
            <p class="text-center"><?php echo __('no_orders_found'); ?></p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><?php echo __('order_id'); ?></th>
                            <th><?php echo __('date'); ?></th>
                            <th><?php echo __('items'); ?></th>
                            <th><?php echo __('total'); ?></th>
                            <th><?php echo __('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $currentOrderId = null;
                        foreach ($orders as $order): 
                            if ($currentOrderId !== $order['order_id']):
                                $currentOrderId = $order['order_id'];
                                $orderItems = array_filter($orders, function($item) use ($currentOrderId) {
                                    return $item['order_id'] == $currentOrderId;
                                });
                        ?>
                        <tr>
                            <td>#<?= $order['order_id'] ?></td>
                            <td><?= date('Y-m-d H:i', strtotime($order['order_date'])) ?></td>
                            <td>
                                <?php foreach ($orderItems as $item): ?>
                                    <div>
                                        <?= $item['product_name'] ?> 
                                        (x<?= $item['quantity'] ?> @ $<?= number_format($item['price'], 2) ?>)
                                    </div>
                                <?php endforeach; ?>
                            </td>
                            <td>$<?= number_format($order['total_amount'], 2) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" 
                                    onclick="printReceipt(<?= $order['order_id'] ?>)">
                                    <?php echo __('print_receipt'); ?>
                                </button>
                            </td>
                        </tr>
                        <?php endif; endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function printReceipt(orderId) {
    window.open(`/products/receipt/${orderId}`, '_blank');
}
</script>