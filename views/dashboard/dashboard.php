<?php
$user = $_SESSION['user'];
$filter = $filter ?? 'today';
$startDate = $startDate ?? date('Y-m-d');
$endDate = $endDate ?? date('Y-m-d');
?>
<div class="page-heading">
    <h3><?php echo __('Target Coffee Dashboard'); ?></h3>
    <div class="filter-group">
        <label><?php echo __('Date Range'); ?></label>
        <div class="date-range-buttons">
            <div class="date-filter">
                <button type="button" class="date-range-btn btn btn-outline-primary <?php echo $filter === 'today' ? 'active' : ''; ?>" data-range="today"><?php echo __('Today'); ?></button>
                <button type="button" class="date-range-btn btn btn-outline-primary <?php echo $filter === 'this_week' ? 'active' : ''; ?>" data-range="this_week"><?php echo __('This Week'); ?></button>
                <button type="button" class="date-range-btn btn btn-outline-primary <?php echo $filter === 'this_month' ? 'active' : ''; ?>" data-range="this_month"><?php echo __('This Month'); ?></button>
            </div>
            <div class="date-inputs d-flex align-items-center gap-2">
                <input type="date" id="start-date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>" class="form-control w-auto">
                <span><?php echo __('to'); ?></span>
                <input type="date" id="end-date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>" class="form-control w-auto">
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
            <div class="row">
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body px-3 py-4-5 Expenses">
                            <div class="row">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="me-3">
                                        <h4 class="font-semibold text-danger"><?php echo __('Expenses'); ?></h4>
                                    </div>
                                    <div>
                                        <i class="bi bi-graph-down text-danger fs-1"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="font-semibold text-danger">$<?php echo number_format($expenses, 2); ?></h1>
                            <h6 class="mb-0">
                                For <?php
                                    if ($filter === 'today') echo 'Today';
                                    elseif ($filter === 'this_week') echo 'This Week';
                                    elseif ($filter === 'this_month') echo 'This Month';
                                    else echo htmlspecialchars("$startDate to $endDate");
                                ?>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body px-3 py-4-5 Income">
                            <div class="row">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="me-3">
                                        <h4 class="font-semibold text-success"><?php echo __('Incomes'); ?></h4>
                                    </div>
                                    <div>
                                        <i class="bi bi-graph-up text-success fs-1"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="font-semibold text-success">$<?php echo number_format($income, 2); ?></h1>
                            <h6 class="mb-0">
                                For <?php
                                    if ($filter === 'today') echo 'Today';
                                    elseif ($filter === 'this_week') echo 'This Week';
                                    elseif ($filter === 'this_month') echo 'This Month';
                                    else echo htmlspecialchars("$startDate to $endDate");
                                ?>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body px-3 py-4-5  Profits">
                            <div class="row">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="me-3">
                                        <h4 class="text-primary font-semibold"><?php echo __('Profits'); ?></h4>
                                    </div>
                                    <div>
                                        <i class="bi bi-cash-coin text-primary fs-1"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="font-semibold text-primary">$<?php echo number_format($profits, 2); ?></h1>
                            <h6 class="mb-0">
                                For <?php
                                    if ($filter === 'today') echo 'Today';
                                    elseif ($filter === 'this_week') echo 'This Week';
                                    elseif ($filter === 'this_month') echo 'This Month';
                                    else echo htmlspecialchars("$startDate to $endDate");
                                ?>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h4><?php echo __('Sales Report'); ?></h4>
                        </div>
                        <div class="card-body">
                            <canvas id="sales-report-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h4><?php echo __('Top Selling Products'); ?></h4>
                        </div>
                        <div class="card-body">
                            <canvas id="top-products-chart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h4><?php echo __('Top Selling Products Details'); ?></h4>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($topProducts)): ?>
                                <?php foreach ($topProducts as $index => $product): ?>
                                    <div class="mb-3">
                                        <h5 class="mb-0 ms-3"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                                        <p class="ms-3 mb-0"><?php echo __('Sold'); ?>: <?php echo htmlspecialchars($product['total_quantity']); ?> <?php echo __('units'); ?></p>
                                        <p class="ms-3 mb-0"><?php echo __('Revenue'); ?>: $<?php echo number_format($product['total_revenue'], 2); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="ms-3">No sales data available.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Register Chart.js datalabels plugin
    Chart.register(ChartDataLabels);

    // Handle filter buttons
    const filterButtons = document.querySelectorAll('.date-range-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const range = this.getAttribute('data-range');
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const url = new URL(window.location);
            url.searchParams.set('filter', range);
            url.searchParams.delete('start_date');
            url.searchParams.delete('end_date');
            window.location.href = url.toString();
        });
    });

    // Handle custom date range inputs
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');

    function updateCustomDateRange() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        if (startDate && endDate) {
            if (new Date(startDate) > new Date(endDate)) {
                alert('Start date cannot be after end date.');
                endDateInput.value = startDate; // Reset end date to match start date
                return;
            }
            filterButtons.forEach(btn => btn.classList.remove('active')); // Deactivate all buttons
            const url = new URL(window.location);
            url.searchParams.set('filter', 'custom');
            url.searchParams.set('start_date', startDate);
            url.searchParams.set('end_date', endDate);
            window.location.href = url.toString();
        }
    }

    startDateInput.addEventListener('change', updateCustomDateRange);
    endDateInput.addEventListener('change', updateCustomDateRange);

    // Top Selling Products Chart
    const topProductsData = <?php echo json_encode($topProducts); ?>;
    const topProductsCanvas = document.getElementById('top-products-chart');
    if (!topProductsData || topProductsData.length === 0) {
        topProductsCanvas.parentElement.innerHTML = '<p class="text-center">No sales data available.</p>';
    } else {
        const topProductsLabels = topProductsData.map(product => product.product_name);
        const topProductsQuantities = topProductsData.map(product => parseFloat(product.total_quantity));

        new Chart(topProductsCanvas, {
            type: 'pie',
            data: {
                labels: topProductsLabels,
                datasets: [{
                    data: topProductsQuantities,
                    backgroundColor: [
                        '#724816',
                        '#925F49',
                        '#A8783E',
                        '#9BA36A',
                        '#F0DAAE'
                    ],
                    borderColor: [
                        '#724816',
                        '#925F49',
                        '#A8783E',
                        '#9BA36A',
                        '#F0DAAE'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Top Selling Products by Quantity' },
                    datalabels: {
                        color: '#fff',
                        formatter: (value, context) => {
                            const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${percentage}%`;
                        },
                        font: { weight: 'bold' }
                    }
                }
            }
        });
    }

    // Sales Report Chart
    const monthlySalesData = <?php echo json_encode($monthlySales); ?>;
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    const salesLabels = monthNames;
    const salesValues = new Array(12).fill(0);
    monthlySalesData.forEach(sale => {
        salesValues[sale.month - 1] = parseFloat(sale.total_sales);
    });

    new Chart(document.getElementById('sales-report-chart'), {
        type: 'line',
        data: {
            labels: salesLabels,
            datasets: [{
                label: 'Sales ($)',
                data: salesValues,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Monthly Sales Report' }
            },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Revenue ($)' } },
                x: { title: { display: true, text: 'Month' } }
            }
        }
    });
});
</script>