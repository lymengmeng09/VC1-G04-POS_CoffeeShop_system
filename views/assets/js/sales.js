// Sales Report Chart (Placeholder)
const ctx1 = document.getElementById('chart-profile-visit').getContext('2d');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Sales',
            data: [12000, 19000, 30000, 25000, 27000, 22000, 20000, 26000, 25000, 27000, 29000, 32000], // Now 12 values
            backgroundColor: 'rgba(101, 67, 33, 0.9)'
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            datalabels: {
                color: 'white',
                anchor: 'end',
                align: 'top'
            }
        }
    }
});


// Top Selling Products Chart (Placeholder)
const ctx2 = document.getElementById('top-products-chart').getContext('2d');
new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: ['Espresso', 'Latte', 'Cappuccino', 'Americano'],
        datasets: [{
            data: [30, 25, 20, 25],
            backgroundColor: ['#8E5D42', '#844A24', '#8E5D43', '#B98C6A']
        }]
    },
    options: {
        plugins: { datalabels: { color: 'white', font: { weight: 'bold' } } }
    }
});
new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
        labels: ['External', 'Internal'],
        datasets: [{
            data: [25.2, 74.8],
            backgroundColor: ['#d3d3d3', '#844A24']
        }]
    }
});