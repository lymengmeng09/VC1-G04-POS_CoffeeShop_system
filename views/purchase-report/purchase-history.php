
<div class="purchase-history">
    <header>
        <h1><?php echo __('Purchase Reports'); ?></h1>
        <button id="refresh-btn" title="Refresh Data">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 2v6h-6"></path>
                <path d="M3 12a9 9 0 0 1 15-6.7L21 8"></path>
                <path d="M3 22v-6h6"></path>
                <path d="M21 12a9 9 0 0 1-15 6.7L3 16"></path>
            </svg>
        </button>
    </header>

    <div class="filter-panel">
        <div class="filter-row">
            <div class="filter-group">
                <div class="search-container">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="search" class="form-control" id="product-search" placeholder="<?php echo __('search_products_placeholder'); ?>" aria-label="Search">
                </div>
            </div>
        </div>
        <div class="filter-actions">
            <button type="button" id="export-excel" class="btn btn-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 5px;">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                <?php echo __('Export to Excel'); ?>
            </button>
        </div>
    </div>
    <div class="filter-group">
        <label><?php echo __('Date Range'); ?></label>
        <div class="date-range-buttons">
            <div class="date-filter">
                <button type="button" class="date-range-btn" data-range="today"><?php echo __('Today'); ?></button>
                <button type="button" class="date-range-btn" data-range="this_week"><?php echo __('This Week'); ?></button>
                <button type="button" class="date-range-btn" data-range="this_month"><?php echo __('This Month'); ?></button>
                <button type="button" class="date-range-btn active" data-range="all"><?php echo __('all'); ?></button>
            </div>
            <div class="date-inputs">
                <input type="date" id="start-date" name="start_date" value="<?php echo $startDate; ?>">
                <span><?php echo __('to'); ?></span>
                <input type="date" id="end-date" name="end_date" value="<?php echo $endDate; ?>">
            </div>
        </div>
    </div>

    <div class="table-container">
        <table id="purchases-table">
            <thead>
                <tr>
                    <th style="text-align: left;"><?php echo __('product_name'); ?></th>
                    <th><?php echo __('quantity'); ?></th>
                    <th><?php echo __('price'); ?></th>
                    <th><?php echo __('date'); ?></th>
                    <th><?php echo __('Total Cost'); ?></th>
                </tr>
            </thead>
            <tbody id="purchases-body">
                <?php if (empty($purchases)): ?>
                    <tr>
                        <td colspan="5" class="empty-message">No coffee purchase records found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($purchases as $purchase): ?>
                        <tr>
                            <td style="text-align: left;"><?php echo htmlspecialchars($purchase['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($purchase['quantity']); ?></td>
                            <td>$<?php echo number_format($purchase['price'], 2); ?></td>
                            <td><?php echo date('M d, Y', strtotime($purchase['purchase_date'])); ?></td>
                            <td>$<?php echo number_format($purchase['total_cost'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>