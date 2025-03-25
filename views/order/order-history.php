
<!-- Search form -->
<div class="search-container">
    <form method="GET" action="index.php" class="row g-3">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search orders..." name="search" value="<?php echo htmlspecialchars($search_term); ?>">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </div>
        <div class="col-md-4">
            <select class="form-select" name="search_type">
                <option value="order_number" <?php echo ($search_type == 'order_number') ? 'selected' : ''; ?>>Order Number</option>
                <option value="customer_name" <?php echo ($search_type == 'customer_name') ? 'selected' : ''; ?>>Customer Name</option>
                <option value="date" <?php echo ($search_type == 'date') ? 'selected' : ''; ?>>Date (YYYY-MM-DD)</option>
            </select>
        </div>
        <div class="col-md-2">
            <?php if (!empty($search_term)): ?>
                <a href="index.php" class="btn btn-outline-secondary w-100">Clear</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Orders table -->
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Products</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="7" class="text-center">No orders found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo date('M d, Y H:i', strtotime($order['order_date'])); ?></td>
                        <td><?php echo htmlspecialchars($order['products']); ?></td>
                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td>
                            <?php
                            $statusClass = '';
                            switch ($order['status']) {
                                case 'Delivered':
                                    $statusClass = 'status-delivered';
                                    break;
                                case 'Processing':
                                    $statusClass = 'status-processing';
                                    break;
                                case 'Shipped':
                                    $statusClass = 'status-shipped';
                                    break;
                            }
                            ?>
                            <span class="status-badge <?php echo $statusClass; ?>">
                                <?php echo htmlspecialchars($order['status']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="index.php?action=view&id=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="index.php?action=edit&id=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="index.php?action=delete&id=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this order?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
        </li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item">
            <a class="page-link" href="#">Next</a>
        </li>
    </ul>
</nav>