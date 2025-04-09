<h5><?php echo __('order_history'); ?></h5>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <?php if (empty($orders)): ?>
                <p><?php echo __('no_orders_found'); ?></p>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-white" >Product</th>
                                        <th class="text-white">Category</th>
                                        <th class="text-white">Quantity</th>
                                        <th class="text-white">Price</th>
                                        <th class="text-white">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($order['items']) && is_array($order['items']) && !empty($order['items'])): ?>
                                        <?php foreach ($order['items'] as $item): ?>
                                            <tr>
                                                <td><?php echo isset($item['product_name']) ? htmlspecialchars($item['product_name']) : 'N/A'; ?></td>
                                                <td><?php echo isset($item['category_name']) ? htmlspecialchars($item['category_name']) : 'N/A'; ?></td>
                                                <td><?php echo isset($item['quantity']) ? $item['quantity'] : '0'; ?></td>
                                                <td>$<?php echo isset($item['price']) ? number_format($item['price'], 2) : '0.00'; ?></td>
                                                <td>$<?php echo isset($item['subtotal']) ? number_format($item['subtotal'], 2) : '0.00'; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6">No items in this order.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="text-end">
                                <strong>Total Amount: $<?php echo isset($order['total_amount']) ? number_format($order['total_amount'], 2) : '0.00'; ?></strong>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .card-header {
        background-color: #f8f9fa;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .table th, .table td {
        vertical-align: middle;
    }
</style>