<?php
$user = $_SESSION['user'];
?>

<!-- Include Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Include Chart.js Data Labels Plugin -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>

<div class="page-heading">
    <h3>Target Coffee Dashboard</h3>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-8 d-flex">
                                    <h4 class="font-semibold text-danger">Expenses</h4>
                                    <div class="col-md-4">
                                        <i class="material-icons text-danger down fs-1">trending_down</i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="font-semibold text-danger">$<?php echo number_format($expenses, 2); ?></h1>
                            <h6 class="mb-0">For This Month</h6>  
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-8 d-flex">
                                    <h4 class="font-semibold text-success">Income</h4>
                                    <div class="col-md-4">
                                        <i class="material-icons text-success down1 fs-1">trending_up</i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="font-semibold text-success">$<?php echo number_format($income, 2); ?></h1>
                            <h6 class="mb-0">For This Month</h6>  
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-8 d-flex">
                                    <h4 class="text-primary font-semibold">Profits</h4>
                                    <div class="col-md-4">
                                        <i class="material-icons text-primary down2 fs-1">paid</i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="font-semibold text-primary">$<?php echo number_format($profits, 2); ?></h1>
                            <h6 class="mb-0">For This Month</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Sales Report</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="sales-report-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Top Selling Products</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="top-products-chart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Top Selling Products Details</h4>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($topProducts)): ?>
                                <?php foreach ($topProducts as $index => $product): ?>
                                    <div class="mb-3">
                                        <h5 class="mb-0 ms-3"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                                        <p class="ms-3 mb-0">Sold: <?php echo htmlspecialchars($product['total_quantity']); ?> units</p>
                                        <p class="ms-3 mb-0">Revenue: $<?php echo number_format($product['total_revenue'], 2); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="ms-3">No sales data available.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-body py-4 px-5">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl">
                            <img src="/views/assets/images/faces/1.jpg" alt="Face 1"
                                onerror="this.src='https://via.placeholder.com/150';">
                        </div>
                        <div class="ms-3 name">
                            <h4 class="font-bold"><?php echo htmlspecialchars($user['name']); ?></h4>
                            <h6 class="text-muted mb-0">@targetcoffee</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Recent Team Members</h4>
                </div>
                <div class="card-content pb-4">
                    <!-- Static team members, replace with dynamic data if needed -->
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="/views/assets/images/faces/2.jpg" alt="Face 2"
                                onerror="this.src='https://via.placeholder.com/150';">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">Lymeng Phorng</h5>
                            <h6 class="text-muted mb-0">@mengmeng</h6>
                        </div>
                    </div>
                    <!-- Add more team members as needed -->
                </div>
            </div>
        </div>
    </section>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>

<!-- JavaScript for Charts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Register the datalabels plugin
    Chart.register(ChartDataLabels);

    // Top Selling Products Chart
    const topProductsData = <?php echo json_encode($topProducts); ?>;
    console.log('Top Products Data:', topProductsData); // Debug

    // Check if there's data to display
    if (!topProductsData || topProductsData.length === 0) {
        const chartContainer = document.getElementById('top-products-chart').parentElement;
        chartContainer.innerHTML = '<p class="text-center">No sales data available.</p>';
        return;
    }

    const topProductsLabels = topProductsData.map(product => product.product_name);
    const topProductsQuantities = topProductsData.map(product => parseFloat(product.total_quantity));
    const topProductsRevenue = topProductsData.map(product => parseFloat(product.total_revenue));

    const topProductsChart = new Chart(document.getElementById('top-products-chart'), {
        type: 'pie',
        data: {
            labels: topProductsLabels,
            datasets: [{
                data: topProductsQuantities,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Top Selling Products by Quantity'
                },
                datalabels: {
                    color: '#fff',
                    formatter: (value, context) => {
                        const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return `${percentage}%`;
                    },
                    font: {
                        weight: 'bold'
                    }
                }
            }
        }
    });

    // Sales Report Chart (unchanged for now)
    const monthlySalesData = <?php echo json_encode($monthlySales); ?>;
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    const salesLabels = monthlySalesData.map(sale => monthNames[sale.month - 1]);
    const salesValues = monthlySalesData.map(sale => parseFloat(sale.total_sales));

    const salesReportChart = new Chart(document.getElementById('sales-report-chart'), {
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
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Monthly Sales Report'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenue ($)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                }
            }
        }
    });
});
</script>