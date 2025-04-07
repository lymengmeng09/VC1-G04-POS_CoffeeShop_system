<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Purchase History</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Arial", sans-serif; /* Clean, neutral font */
            line-height: 1.6;
            color: #3f2a1d; /* Soft coffee brown */
            /* background-color: #faf7f2; Light cream background (commented out as in original) */
        }

        .purchase-history {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff; /* Plain white for simplicity */
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(63, 42, 29, 0.1); /* Subtle shadow */
        }

        /* Header styles */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #d9c8a9; /* Light coffee border */
        }

        h1 {
            font-size: 24px;
            font-weight: 600;
            color: #5c4033; /* Medium roast brown */
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
            background-color: #f2ece2; /* Light hover */
        }

        #refresh-btn svg {
            fill: #5c4033;
        }

        /* Actions bar */
        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 15px;
            flex-wrap: wrap;
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
            color: #8a6a4b; /* Muted coffee */
        }

        #search-input {
            width: 100%;
            padding: 10px 10px 10px 35px;
            border: 1px solid #d9c8a9;
            border-radius: 6px;
            font-size: 14px;
            background-color: #fff;
            color: #3f2a1d;
            transition: border-color 0.2s ease;
        }

        #search-input:focus {
            outline: none;
            border-color: #8a6a4b;
        }

        .filter-container {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filter-container input[type="date"] {
            padding: 9px 12px;
            border: 1px solid #d9c8a9;
            border-radius: 6px;
            font-size: 14px;
            background-color: #fff;
            color: #3f2a1d;
        }

        .filter-container input[type="date"]:focus {
            outline: none;
            border-color: #8a6a4b;
        }

        .export-btn, .filter-btn {
            padding: 10px 16px;
            background-color: #8a6a4b; /* Muted coffee brown */
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .export-btn:hover, .filter-btn:hover {
            background-color: #6b4e31; /* Darker coffee */
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
            table-layout: fixed;
        }

        th, td {
            padding: 12px 16px;
            text-align: center;
            vertical-align: middle;
            font-size: 14px;
            border-bottom: 1px solid #e6dfd4; /* Very light coffee line */
        }

        th {
            background-color: #f2ece2; /* Light coffee background */
            color: #5c4033;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        th:nth-child(2), td:nth-child(2) { /* Coffee Blend */
            text-align: left;
        }

        /* Column widths - Adjusted for 6 columns */
        th:nth-child(1), td:nth-child(1) { width: 10%; }
        th:nth-child(2), td:nth-child(2) { width: 30%; }
        th:nth-child(3), td:nth-child(3) { width: 15%; }
        th:nth-child(4), td:nth-child(4) { width: 15%; }
        th:nth-child(5), td:nth-child(5) { width: 15%; }
        th:nth-child(6), td:nth-child(6) { width: 15%; }

        tbody tr:hover {
            background-color: #faf7f2; /* Subtle hover */
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
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .actions {
                flex-direction: column;
                align-items: stretch;
            }

            .search-container {
                max-width: 100%;
            }

            .filter-container {
                flex-wrap: wrap;
            }

            .export-btn, .filter-btn {
                width: 100%;
                justify-content: center;
            }

            .table-container {
                overflow-x: auto;
            }

            th, td {
                padding: 10px 8px;
                font-size: 13px;
                white-space: normal;
            }
        }
    </style>
</head>
<body>
    <div class="purchase-history">
        <header>
            <h1>Coffee Purchase History</h1>
            <button id="refresh-btn" title="Refresh Data">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6"></path><path d="M3 12a9 9 0 0 1 15-6.7L21 8"></path><path d="M3 22v-6h6"></path><path d="M21 12a9 9 0 0 1-15 6.7L3 16"></path></svg>
            </button>
        </header>

        <div class="actions">
            <div class="search-container">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="search-input" placeholder="Search coffee purchases...">
            </div>
            
            <form method="GET" action="/purchases" class="filter-container">
                <input type="date" name="start_date" value="<?php echo $startDate ?? ''; ?>" required>
                <input type="date" name="end_date" value="<?php echo $endDate ?? ''; ?>" required>
                <button type="submit" class="filter-btn">Filter</button>
                <?php if (isset($startDate) && isset($endDate)): ?>
                    <a href="/purchases" class="filter-btn" style="background-color: #6b4e31;">Clear</a>
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
                        <th style="text-align: left;">Coffee Name</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Date</th>
                        <th>Total Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($purchases)): ?>
                    <tr>
                        <td colspan="6" class="empty-message">No coffee purchase records found</td>
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
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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
        
        const emptyMessage = document.querySelector('.empty-message');
        if (!hasVisibleRows && !emptyMessage) {
            const colspan = 6; // Fixed to 6 columns
            const tbody = document.querySelector('#purchases-table tbody');
            const noResultsRow = document.createElement('tr');
            noResultsRow.className = 'empty-message search-empty';
            noResultsRow.innerHTML = `<td colspan="${colspan}" class="empty-message">No matching coffee records found</td>`;
            tbody.appendChild(noResultsRow);
        } else if (hasVisibleRows) {
            const searchEmpty = document.querySelector('.search-empty');
            if (searchEmpty) searchEmpty.remove();
        }
    });

    // Refresh functionality
    document.getElementById('refresh-btn').addEventListener('click', function() {
        const tableContainer = document.querySelector('.table-container');
        tableContainer.innerHTML = '<div class="loading"><div class="spinner"></div></div>';
        setTimeout(() => {
            window.location.reload();
        }, 500);
    });

    // Export to CSV
    document.getElementById('export-btn').addEventListener('click', function() {
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
        a.download = 'coffee_purchase_history.csv';
        a.click();
        window.URL.revokeObjectURL(url);
        
        setTimeout(() => {
            this.innerHTML = originalText;
            this.disabled = false;
        }, 1000);
    });
    </script>
</body>
</html>