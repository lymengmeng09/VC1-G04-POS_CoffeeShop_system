 <div class="full-container">
        <header>
            <h1>Purchase History Report</h1>
            <button id="refresh-btn" title="Refresh Data">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6"></path><path d="M3 12a9 9 0 0 1 15-6.7L21 8"></path><path d="M3 22v-6h6"></path><path d="M21 12a9 9 0 0 1-15 6.7L3 16"></path></svg>
            </button>
        </header>

        <!-- <div class="stats-container">
            <div class="stat-card">
                <h3>Total Revenue</h3>
                <p class="stat-value">$<?php echo number_format($total_revenue, 2); ?></p>
                <p class="stat-description">From all purchases</p>
            </div>
            <div class="stat-card">
                <h3>Total Transactions</h3>
                <p class="stat-value"><?php echo $total_transactions; ?></p>
                <p class="stat-description">Number of purchases</p>
            </div>
            <div class="stat-card">
                <h3>Average Purchase</h3>
                <p class="stat-value">$<?php echo number_format($average_purchase, 2); ?></p>
                <p class="stat-description">Per transaction</p>
            </div>
        </div> -->

        <div class="actions">
            <div class="search-container">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="search-input" placeholder="Search purchases...">
            </div>
            <button id="export-btn" class="export-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="download-icon"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Export CSV
            </button>
        </div>

        <div class="table-container">
            <table id="purchases-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Date</th>
                        <th>Total Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($purchases) === 0): ?>
                    <tr>
                        <td colspan="6" class="empty-message">No purchase records found</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($purchases as $purchase): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($purchase['id']); ?></td>
                            <td><?php echo htmlspecialchars($purchase['product_name']); ?></td>
                            <td class="text-right"><?php echo htmlspecialchars($purchase['quantity']); ?></td>
                            <td class="text-right">$<?php echo number_format($purchase['price'], 2); ?></td>
                            <td><?php echo date('M d, Y', strtotime($purchase['purchase_date'])); ?></td>
                            <td class="text-right">$<?php echo number_format($purchase['total_cost'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

