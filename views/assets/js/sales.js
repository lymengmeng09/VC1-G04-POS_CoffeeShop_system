const ctx = document.getElementById('clickChart').getContext('2d');
        const clickChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Clicks (Thousands)',
                    data: [10, 15, 20, 25, 30, 35, 40, 45, 50, 60, 70, 82.7],
                    backgroundColor: function(context) {
                        const chart = context.chart;
                        const {ctx, chartArea} = chart;
                        if (!chartArea) return;
                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                        gradient.addColorStop(0, 'rgba(255, 99, 132, 0.8)');
                        gradient.addColorStop(0.5, 'rgba(255, 165, 0, 0.8)');
                        gradient.addColorStop(1, 'rgba(255, 215, 0, 0.8)');
                        return gradient;
                    },
                    borderColor: 'rgba(255, 215, 0, 1)', // Gold border
                    borderWidth: 2,
                    borderRadius: 5,
                    barThickness: 20
                }]
            },
            options: {
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuad'
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#ffffff',
                            callback: function(value) { return value + 'K'; },
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)',
                            borderColor: 'rgba(255, 255, 255, 0.2)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#ffffff',
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: 'rgba(255, 215, 0, 1)',
                        borderWidth: 1
                    }
                },
                maintainAspectRatio: false
            }
        });