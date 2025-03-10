// Initialize the chart
let topProductsChart;
let currentChartType = 'bar'; // Default chart type

function drawChart(data, chartType = currentChartType) {
    const ctx = document.getElementById('topProductsChart').getContext('2d');
    if (topProductsChart) {
        topProductsChart.destroy(); // Destroy the old chart to prevent overlap
    }
    topProductsChart = new Chart(ctx, {
        type: chartType,
        data: {
            labels: data.map(item => item.product_name),
            datasets: [{
                label: 'Total Sold',
                data: data.map(item => item.total_sold),
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(201, 203, 207, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(75, 192, 192, 0.6)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(201, 203, 207, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)'
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
                    text: 'Top Selling Products'
                }
            },
            scales: chartType === 'bar' ? {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Units Sold'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Product Name'
                    }
                }
            } : {}
        }
    });
}

// Function to update chart type
function updateChartType() {
    const chartTypeSelect = document.getElementById('chartType');
    currentChartType = chartTypeSelect.value;
    drawChart(currentData, currentChartType);
}

// Initial chart draw
let currentData = INITIAL_DATA; // Placeholder for initial data
drawChart(currentData);

// Function to fetch updated data and redraw chart
function updateTopProducts() {
    const chartLoading = document.getElementById('chartLoading');
    chartLoading.style.display = 'block'; // Show loading message
    fetch('?action=dashboard', {
        method: 'POST',
        headers: {
            'Accept': 'application/json'
        },
        body: new FormData(document.querySelector('form'))
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        chartLoading.style.display = 'none'; // Hide loading message
        if (data && data.length > 0) {
            currentData = data;
            drawChart(currentData, currentChartType);
        } else {
            document.querySelector('.chart-container').innerHTML = '<p class="text-muted">No sales data available.</p>';
        }
    })
    .catch(error => {
        chartLoading.style.display = 'none'; // Hide loading message
        console.error('Error updating chart:', error);
        document.querySelector('.chart-container').innerHTML = '<p class="text-muted">Failed to update chart. Please try again later.</p>';
    });
}

// Update every 60 seconds
setInterval(updateTopProducts, 60000);


