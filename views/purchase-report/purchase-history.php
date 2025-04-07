<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History Report</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }
        
        .purchase-history {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        /* Header styles */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        h1 {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        #refresh-btn {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        
        #refresh-btn:hover {
            background-color: #f9f9f9;
            transform: rotate(15deg);
        }
        
        /* Actions bar */
        .actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }
        
        .search-container {
            position: relative;
            flex: 1;
            max-width: 400px;
        }
        
        .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
        }
        
        #search-input {
            width: 100%;
            padding: 10px 10px 10px 35px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s ease;
        }
        
        #search-input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        .filter-container {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .filter-container input[type="date"] {
            padding: 9px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        
        .filter-container input[type="date"]:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        .export-btn, .filter-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
            text-decoration: none;
        }
        
        .export-btn:hover, .filter-btn:hover {
            background-color: #2980b9;
        }
        
        /* Table styles */
        .table-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Fixed table layout for better column control */
        }
        
        th, td {
            padding: 14px 16px;
            text-align: center; /* Center all content */
            border-bottom: 1px solid #ecf0f1;
            vertical-align: middle; /* Vertically center content */
            overflow: hidden;
            text-overflow: ellipsis; /* Add ellipsis for overflow text */
            white-space: nowrap; /* Prevent text wrapping */
        }
        
        th {
            font-weight: 600;
            color: #34495e;
            background-color: #f8f9fa;
            position: sticky;
            top: 0;
        }
        
        /* Column widths */
        th:nth-child(1), td:nth-child(1) { /* ID column */
            width: 8%;
        }
        
        th:nth-child(2), td:nth-child(2) { /* Product Name column */
            width: 25%;
            text-align: left; /* Keep product names left-aligned */
        }
        
        th:nth-child(3), td:nth-child(3) { /* Quantity column */
            width: 10%; /* Reduced width */
        }
        
        th:nth-child(4), td:nth-child(4) { /* Price column */
            width: 15%;
        }
        
        th:nth-child(5), td:nth-child(5) { /* Date column */
            width: 15%;
        }
        
        th:nth-child(6), td:nth-child(6) { /* Total Cost column */
            width: 15%;
        }
        
        th:nth-child(7), td:nth-child(7) { /* Status column (if shown) */
            width: 12%;
        }
        
        tbody tr {
            transition: background-color 0.2s ease;
        }
        
        tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .empty-message {
            text-align: center;
            padding: 40px 0;
            color: #7f8c8d;
            font-style: italic;
        }
        
        /* Status indicators */
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .status-new {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .status-update {
            background-color: #e8f5e9;
            color: #388e3c;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 20px;
        }
        
        .pagination button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .pagination button.active {
            background-color: #3498db;
            color: white;
            border-color: #3498db;
        }
        
        /* Loading indicator */
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
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
            .actions {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .search-container {
                max-width: 100%;
            }
            
            .filter-container {
                flex-wrap: wrap;
                width: 100%;
            }
            
            .filter-container input[type="date"] {
                flex: 1;
                min-width: 120px;
            }
            
            .export-btn {
                width: 100%;
                justify-content: center;
            }
            
            th, td {
                padding: 12px 10px;
            }
            
            .table-container {
                overflow-x: auto;
            }
            
            /* Adjust column widths for mobile */
            table {
                table-layout: auto; /* Allow table to adjust on mobile */
            }
            
            th, td {
                white-space: normal; /* Allow text wrapping on mobile */
            }
        }
    </style>
</head>
<body>
    <div class="purchase-history">
        <header>
            <h1>Purchase History Report</h1>
            <button id="refresh-btn" title="Refresh Data">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6"></path><path d="M3 12a9 9 0 0 1 15-6.7L21 8"></path><path d="M3 22v-6h6"></path><path d="M21 12a9 9 0 0 1-15 6.7L3 16"></path></svg>
            </button>
        </header>

        <div class="actions">
            <div class="search-container">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="search-input" placeholder="Search purchases...">
            </div>
            
            <form method="GET" action="/purchases" class="filter-container">
                <input type="date" name="start_date" value="<?php echo $startDate ?? ''; ?>" required>
                <input type="date" name="end_date" value="<?php echo $endDate ?? ''; ?>" required>
                <button type="submit" class="filter-btn">Filter</button>
                <?php if (isset($startDate) && isset($endDate)): ?>
                    <a href="/purchases" class="filter-btn" style="background-color: #e74c3c;">Clear</a>
                <?php endif; ?>
            </form>
            
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
                        <th style="text-align: left;">Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Date</th>
                        <th>Total Cost</th>
                        <?php if (isset($showStatus) && $showStatus): ?>
                        <th>Status</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($purchases)): ?>
                    <tr>
                        <td colspan="<?php echo isset($showStatus) && $showStatus ? 7 : 6; ?>" class="empty-message">No purchase records found</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($purchases as $purchase): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($purchase['product_id']); ?></td>
                            <td style="text-align: left;"><?php echo htmlspecialchars($purchase['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($purchase['quantity']); ?></td>
                            <td>$<?php echo number_format($purchase['price'], 2); ?></td>
                            <td><?php echo date('M d, Y', strtotime($purchase['purchase_date'])); ?></td>
                            <td>$<?php echo number_format($purchase['total_cost'], 2); ?></td>
                            <?php if (isset($showStatus) && $showStatus): ?>
                            <td>
                                <span class="status-badge status-<?php echo strpos($purchase['status'], 'new') !== false ? 'new' : 'update'; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $purchase['status'])); ?>
                                </span>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination - Uncomment if you implement pagination -->
        <!--
        <div class="pagination">
            <button>&laquo;</button>
            <button class="active">1</button>
            <button>2</button>
            <button>3</button>
            <button>&raquo;</button>
        </div>
        -->
    </div>

    <script>
    // Search functionality
    document.getElementById('search-input').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#purchases-table tbody tr:not(.empty-message)');
        let hasVisibleRows = false;
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const isVisible = text.includes(searchTerm);
            row.style.display = isVisible ? '' : 'none';
            if (isVisible) hasVisibleRows = true;
        });
        
        // Show "no results" message if no matching rows
        const emptyMessage = document.querySelector('.empty-message');
        if (!hasVisibleRows && !emptyMessage) {
            const colspan = document.querySelector('#purchases-table thead th:last-child').cellIndex + 1;
            const tbody = document.querySelector('#purchases-table tbody');
            const noResultsRow = document.createElement('tr');
            noResultsRow.className = 'empty-message search-empty';
            noResultsRow.innerHTML = `<td colspan="${colspan}" class="empty-message">No matching records found</td>`;
            tbody.appendChild(noResultsRow);
        } else if (hasVisibleRows) {
            const searchEmpty = document.querySelector('.search-empty');
            if (searchEmpty) searchEmpty.remove();
        }
    });

    // Refresh functionality
    document.getElementById('refresh-btn').addEventListener('click', function() {
        // Add loading spinner
        const tableContainer = document.querySelector('.table-container');
        tableContainer.innerHTML = '<div class="loading"><div class="spinner"></div></div>';
        
        // Reload after a short delay to show the spinner
        setTimeout(() => {
            window.location.reload();
        }, 500);
    });

    // Export to CSV
    document.getElementById('export-btn').addEventListener('click', function() {
        // Change button text to show loading
        const originalText = this.innerHTML;
        this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="download-icon"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> Exporting...';
        this.disabled = true;
        
        const table = document.getElementById('purchases-table');
        let csv = [];
        const rows = table.querySelectorAll('tr:not(.empty-message):not(.search-empty)');
        
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const cols = row.querySelectorAll('td, th');
                const rowData = Array.from(cols).map(col => `"${col.textContent.trim()}"`).join(',');
                csv.push(rowData);
            }
        });

        const csvContent = csv.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'purchase_history.csv';
        a.click();
        window.URL.revokeObjectURL(url);
        
        // Reset button after a short delay
        setTimeout(() => {
            this.innerHTML = originalText;
            this.disabled = false;
        }, 1000);
    });
    </script>
</body>
</html>

