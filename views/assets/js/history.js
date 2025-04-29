document.addEventListener('DOMContentLoaded', function() {
    const dateRangeButtons = document.querySelectorAll('.date-range-btn');
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    const refreshBtn = document.getElementById('refresh-btn');
    const tableBody = document.querySelector('.table tbody');

    // Handle date range button clicks
    dateRangeButtons.forEach(button => {
        button.addEventListener('click', function() {
            console.log('Button clicked:', this.dataset.range); // Debug log
            dateRangeButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const range = this.dataset.range;
            let startDate, endDate;

            const today = new Date();
            const formatDate = (date) => date.toISOString().split('T')[0];

            switch(range) {
                case 'today':
                    startDate = endDate = formatDate(today);
                    break;
                case 'this_week':
                    startDate = new Date(today);
                    startDate.setDate(today.getDate() - today.getDay());
                    startDate = formatDate(startDate);
                    endDate = formatDate(today);
                    break;
                case 'this_month':
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    startDate = formatDate(startDate);
                    endDate = formatDate(today);
                    break;
                case 'all':
                    startDate = '';
                    endDate = '';
                    break;
            }

            startDateInput.value = startDate;
            endDateInput.value = endDate;
            fetchOrders(startDate, endDate);
        });
    });

    // Handle custom date input changes
    startDateInput.addEventListener('change', () => {
        console.log('Start date changed:', startDateInput.value); // Debug log
        updateCustomDate();
    });

    endDateInput.addEventListener('change', () => {
        console.log('End date changed:', endDateInput.value); // Debug log
        updateCustomDate();
    });

    function updateCustomDate() {
        dateRangeButtons.forEach(btn => btn.classList.remove('active'));
        fetchOrders(startDateInput.value, endDateInput.value);
    }

    // Handle refresh button
    refreshBtn.addEventListener('click', () => {
        console.log('Refresh clicked'); // Debug log
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        fetchOrders(startDate, endDate);
    });

    // Fetch orders from server
    function fetchOrders(startDate, endDate) {
        // Validate date range
        if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
            tableBody.innerHTML = '<tr><td colspan="5">Start date cannot be after end date</td></tr>';
            return;
        }

        const url = new URL('/order-history/fetch', window.location.origin);
        if (startDate) url.searchParams.append('start_date', startDate);
        if (endDate) url.searchParams.append('end_date', endDate);

        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data.orders) {
                throw new Error('Invalid response format: missing orders');
            }
            updateTable(data.orders);
        })
        .catch(error => {
            console.error('Error fetching orders:', error);
            tableBody.innerHTML = `<tr><td colspan="5">Error loading orders: ${htmlEscape(error.message)}</td></tr>`;
        });
    }

    // Update table with new data
    function updateTable(orders) {
        tableBody.innerHTML = '';
        
        if (!orders || orders.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5">No orders found</td></tr>';
            return;
        }

        orders.forEach(order => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${htmlEscape(order.order_id)}</td>
                <td>$${Number(order.total_amount).toFixed(2)}</td>
                <td>${htmlEscape(order.item_count)}</td>
                <td>${htmlEscape(order.created_at)}</td>
                <td>
                    <a href="/order-history/details/${htmlEscape(order.order_id)}" 
                       class="btn btn-sm btn-primary">View details</a>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }
    let debounceTimeout;
    function debounceFetchOrders(startDate, endDate) {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            fetchOrders(startDate, endDate);
        }, 300);
    }
    
    startDateInput.addEventListener('change', () => {
        console.log('Start date changed:', startDateInput.value);
        updateCustomDate();
    });
    
    endDateInput.addEventListener('change', () => {
        console.log('End date changed:', endDateInput.value);
        updateCustomDate();
    });
    
    function updateCustomDate() {
        dateRangeButtons.forEach(btn => btn.classList.remove('active'));
        debounceFetchOrders(startDateInput.value, endDateInput.value);
    }
    // HTML escape function for security
    function htmlEscape(str) {
        return str
            .toString()
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }
});