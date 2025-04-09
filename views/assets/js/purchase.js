document.addEventListener('DOMContentLoaded', function () {
    const dateRangeButtons = document.querySelectorAll('.date-range-btn');
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    const searchInput = document.getElementById('searchInput');
    const exportExcelBtn = document.getElementById('export-excel');
    const refreshBtn = document.getElementById('refresh-btn');
    const purchasesBody = document.getElementById('purchases-body');

    let activeRange = 'all';

    dateRangeButtons.forEach(button => {
        if (button.dataset.range === 'all') {
            button.classList.add('active');
        }
        
        button.addEventListener('click', function () {
            dateRangeButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            activeRange = this.dataset.range;
            updateDateInputs(activeRange);
            updateTable();
        });
    });

    startDateInput.addEventListener('change', () => {
        activeRange = 'custom';
        dateRangeButtons.forEach(btn => btn.classList.remove('active'));
        updateTable();
    });
    endDateInput.addEventListener('change', () => {
        activeRange = 'custom';
        dateRangeButtons.forEach(btn => btn.classList.remove('active'));
        updateTable();
    });

    searchInput.addEventListener('input', () => {
        updateTable();
    });

    function updateDateInputs(range) {
        const today = new Date();
        let startDate = new Date();
        let endDate = new Date();

        switch(range) {
            case 'today':
                startDate = endDate = today;
                break;
            case 'this_week':
                const day = today.getDay();
                const diff = today.getDate() - day + (day === 0 ? -6 : 1);
                startDate = new Date(today.setDate(diff));
                endDate = new Date();
                break;
            case 'this_month':
                startDate = new Date(today.getFullYear(), today.getMonth(), 2); // 1st of current month
                endDate = new Date(today.getFullYear(), today.getMonth() + 1, 1);
                break;
            case 'all':
                startDate = new Date('2000-01-01');
                endDate = today;
                break;
            case 'custom':
                return;
        }

        startDateInput.value = formatDate(startDate);
        endDateInput.value = formatDate(endDate);
    }

    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    function updateTable(showLoading = false) {
        if (showLoading) {
            purchasesBody.innerHTML = '<tr><td colspan="5" class="loading"><div class="spinner"></div></td></tr>';
        }

        const params = new URLSearchParams({
            start_date: startDateInput.value,
            end_date: endDateInput.value,
            search: searchInput.value.trim(),
            ajax: '1'
        });

        fetch(`/purchase-history?${params.toString()}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                purchasesBody.innerHTML = '';
                
                if (!data || data.length === 0) {
                    purchasesBody.innerHTML = '<tr><td colspan="5" class="empty-message">No coffee purchase records found</td></tr>';
                    return;
                }

                data.forEach(purchase => {
                    const date = new Date(purchase.purchase_date);
                    const formattedDate = date.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });

                    purchasesBody.innerHTML += `
                        <tr>
                            <td style="text-align: left;">${purchase.product_name}</td>
                            <td>${purchase.quantity}</td>
                            <td>$${parseFloat(purchase.price).toFixed(2)}</td>
                            <td>${formattedDate}</td>
                            <td>$${parseFloat(purchase.total_cost).toFixed(2)}</td>
                        </tr>
                    `;
                });
            })
            .catch(error => {
                console.error('Error:', error);
                purchasesBody.innerHTML = '<tr><td colspan="5">Error loading data: ' + error.message + '</td></tr>';
            });
    }

    refreshBtn.addEventListener('click', () => updateTable(true));

    exportExcelBtn.addEventListener('click', function () {
        const rows = document.querySelectorAll('#purchases-table tbody tr:not(.empty-message)');
        if (rows.length === 0) {
            alert('No data to export');
            return;
        }

        const worksheetData = [
            ['Products Name', 'Quantity', 'Price', 'Date', 'Total Cost']
        ];

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length === 5) {
                const rowData = [
                    cells[0].textContent.trim(), // Coffee Name
                    parseInt(cells[1].textContent.trim(), 10) || 0, // Quantity
                    parseFloat(cells[2].textContent.replace('$', '').trim()) || 0, // Price
                    cells[3].textContent.trim(), // Date
                    parseFloat(cells[4].textContent.replace('$', '').trim()) || 0 // Total Cost
                ];
                worksheetData.push(rowData);
            }
        });

        if (typeof XLSX === 'undefined') {
            alert('Excel export requires the SheetJS library. Please include it in your page.');
            return;
        }

        const workbook = XLSX.utils.book_new();
        const worksheet = XLSX.utils.aoa_to_sheet(worksheetData);
        XLSX.utils.book_append_sheet(workbook, worksheet, "Purchase Report");
        XLSX.writeFile(workbook, `purchase_report_${formatDate(new Date())}.xlsx`);
    });

    updateTable();
});