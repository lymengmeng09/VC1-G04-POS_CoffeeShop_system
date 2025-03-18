
        // Get the canvas element
        const ctx = document.getElementById('salesChart').getContext('2d');

        // Create the chart
        new Chart(ctx, {
            type: 'line', // Line chart with filled area
            data: {
                labels: ['M', 'T', 'W', 'T', 'F'], // Days of the week
                datasets: [{
                    label: 'Sales',
                    data: [30, 70, 40, 80, 60], // Sample data points (you can adjust these)
                    fill: true, // Fills the area under the line
                    backgroundColor: 'rgba(39, 167, 252, 0.2)', // Light blue fill
                    borderColor: 'rgba(54, 162, 235, 1)', // Blue line
                    borderWidth: 2,
                    tension: 0.4, // Smooth curve
                    pointRadius: 5, // Size of the points
                    pointBackgroundColor: 'rgba(101, 67, 33, 0.9)', // Point color
                    pointBorderColor: '#fff', // Point border color
                    pointBorderWidth: 2
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100, // Adjust based on your data
                        ticks: {
                            stepSize: 20 // Adjust the step size for y-axis
                        }
                    },
                    x: {
                        grid: {
                            display: false // Hide vertical grid lines
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Hide the legend
                    }
                }
            }
        });
