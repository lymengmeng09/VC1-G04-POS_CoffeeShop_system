<style>
    /* Reset and base styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: "Arial", sans-serif;
        line-height: 1.6;
        color: #3f2a1d;
        background-color: #f8f9fa;
    }

    .purchase-history {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(63, 42, 29, 0.1);
    }

    /* Header styles */
    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #d9c8a9;
    }

    h1 {
        font-size: 24px;
        font-weight: 600;
        color: #5c4033;
    }

    #refresh-btn {
        background-color: #fff;
        border: 1px solid #d9c8a9;
        border-radius: 4px;
        padding: 8px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    #refresh-btn:hover {
        background-color: #f2ece2;
    }

    /* Filter panel */
    .filter-panel {
        /* background-color: #f2ece2; */
        border-radius: 6px;
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }


    .filter-group {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        font-weight: 600;
        margin-bottom: 5px;
        color: #5c4033;
    }

    .date-range-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .date-range-btn {
        padding: 8px 12px;
        background-color: #fff;
        border: 1px solid #d9c8a9;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .date-range-btn.active {
        background-color: #8a6a4b;
        color: #fff;
        border-color: #8a6a4b;
    }

    .date-range-btn:hover:not(.active) {
        background-color: #f8f5f0;
    }

    .date-inputs {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .date-inputs input[type="date"] {
        padding: 8px;
        border: 1px solid #d9c8a9;
        border-radius: 4px;
        flex: 1;
    }

    .search-container {
        position: relative;
        flex: 1;
    }

    .search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #8a6a4b;
    }

    #product-search {
        width: 450px;
        padding: 10px 10px 10px 35px;
        border: 1px solid #d9c8a9;
        border-radius: 4px;
        font-size: 14px;
    }

    .filter-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn {
        padding: 10px 16px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s ease;
        border: none;
    }

    #export-csv {
        background-color: #8a6a4b;
        color: #fff;
    }

    #export-csv:hover {
        background-color: #6b4e31;
    }

    .btn-secondary {
        background-color: #f2ece2;
        color: #5c4033;
        border: 1px solid #d9c8a9;
    }

    .btn-secondary:hover {
        background-color: #e6dfd4;
    }

    /* Table styles */
    .table-container {
        background-color: #fff;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(63, 42, 29, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 12px 16px;
        text-align: left;
        font-size: 14px;
        border-bottom: 1px solid #e6dfd4;
    }

    th {
        background-color: #f2ece2;
        color: #5c4033;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    tbody tr:hover {
        background-color: #faf7f2;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    /* Empty message */
    .empty-message {
        text-align: center;
        padding: 30px 0;
        color: #8a6a4b;
        font-size: 14px;
    }

    /* Loading indicator */
    .loading {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 200px;
    }

    .spinner {
        border: 4px solid #f2ece2;
        border-top: 4px solid #8a6a4b;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .filter-row {
            flex-direction: column;
        }

        .filter-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }

        .table-container {
            overflow-x: auto;
        }
    }
</style>
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
                    <input type="search" class="form-control" id="product-search" placeholder="Search product name" aria-label="Search">
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
        <label>Date Range</label>
        <div class="date-range-buttons">
            <div class="date-filter">
                <button type="button" class="date-range-btn" data-range="today"><?php echo __('Today'); ?></button>
                <button type="button" class="date-range-btn" data-range="this_week"><?php echo __('This Week'); ?></button>
                <button type="button" class="date-range-btn" data-range="this_month"><?php echo __('This Month'); ?></button>
                <button type="button" class="date-range-btn active" data-range="all"><?php echo __('all'); ?></button>
            </div>
            <div class="date-inputs">
                <input type="date" id="start-date" name="start_date" value="<?php echo $startDate; ?>">
                <span>to</span>
                <input type="date" id="end-date" name="end_date" value="<?php echo $endDate; ?>">
            </div>
        </div>
    </div>

    <div class="table-container">
        <table id="purchases-table">
            <thead>
                <tr>
                    <th style="text-align: left;">Products Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Date</th>
                    <th>Total Cost</th>
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