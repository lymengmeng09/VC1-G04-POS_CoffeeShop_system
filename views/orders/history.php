<h5><?php echo __('order_history'); ?></h5>
<div class="card mb-2">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Order Date</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Total Amount</th>
                    <th>Payment Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orderDetails)): ?>
                    <tr><td colspan="8">No orders found.</td></tr>
                <?php else: ?>
                    <?php foreach ($orderDetails as $detail): ?>
                        <?php
                        $order = $detail['order'];
                        $items = $detail['items'];
                        $rowspan = count($items) ?: 1;
                        $first_item = true;
                        if (empty($items)):
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                                <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                <td colspan="4">No items</td>
                                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($order['payment_status']); ?></td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($items as $item): ?>
                                <?php if ($first_item): ?>
                                    <tr>
                                        <td rowspan="<?php echo $rowspan; ?>"><?php echo htmlspecialchars($order['order_number']); ?></td>
                                        <td rowspan="<?php echo $rowspan; ?>"><?php echo htmlspecialchars($order['order_date']); ?></td>
                                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                                        <td rowspan="<?php echo $rowspan; ?>">$<?php echo number_format($order['total_amount'], 2); ?></td>
                                        <td rowspan="<?php echo $rowspan; ?>"><?php echo htmlspecialchars($order['payment_status']); ?></td>
                                    </tr>
                                    <?php $first_item = false; ?>
                                <?php else: ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>