  // Register the datalabels plugin
  Chart.register(ChartDataLabels);

  // Ensure DOM is fully loaded before initializing the chart
  document.addEventListener('DOMContentLoaded', function () {
      const ctx = document.getElementById('top-products-chart').getContext('2d');
      if (!ctx) {
          console.error('Canvas element with ID "top-products-chart" not found!');
          return;
      }

      // Custom plugin for gradient fill
      const gradientPlugin = {
          id: 'gradientPlugin',
          beforeDatasetsDraw(chart) {
              const { ctx, chartArea: { top, bottom, left, right } } = chart;
              const dataset = chart.data.datasets[0];
              const meta = chart.getDatasetMeta(0);

              meta.data.forEach((element, index) => {
                  const gradient = ctx.createLinearGradient(left, top, right, bottom);
                  if (index === 0) {
                      gradient.addColorStop(0, 'rgba(34, 139, 34, 0.9)'); // Dark Green gradient start
                      gradient.addColorStop(1, 'rgba(34, 139, 34, 0.5)'); // Dark Green gradient end
                  } else {
                      gradient.addColorStop(0, 'rgba(101, 67, 33, 0.9)');  // Dark Brown gradient start
                      gradient.addColorStop(1, 'rgba(101, 67, 33, 0.5)'); // Dark Brown gradient end
                  }
                  element.options.backgroundColor = gradient;
              });
          }
      };

      // Register the custom gradient plugin
      Chart.register(gradientPlugin);

      const topProductsChart = new Chart(ctx, {
          type: 'doughnut',
          data: {
              labels: ['Latte', 'Espresso'],
              datasets: [{
                  data: [30, 70],
                  backgroundColor: [
                      'rgba(34, 139, 34, 0.9)',  // Dark Green
                      'rgba(101, 67, 33, 0.9)'   // Dark Brown
                  ],
                  borderColor: [
                      'rgba(34, 139, 34, 1)',    // Dark Green border
                      'rgba(101, 67, 33, 1)'     // Dark Brown border
                  ],
                  borderWidth: 1,
                  hoverOffset: 10
              }]
          },
          options: {
              responsive: true,
              maintainAspectRatio: false,
              cutout: '70%',
              rotation: 0,
              animation: {
                  animateScale: true,
                  animateRotate: true,
                  duration: 1000
              },
              plugins: {
                  legend: {
                      position: 'bottom',
                      labels: {
                          font: {
                              size: 12,
                              weight: 'normal'
                          },
                          color: '#333',
                          padding: 15,
                          boxWidth: 20,
                          boxHeight: 20
                      }
                  },
                  title: {
                      display: true,
                      text: 'Top Selling Products Distribution',
                      font: {
                          size: 14,
                          weight: 'bold',
                          family: "'Arial', sans-serif"
                      },
                      color: '#333',
                      padding: {
                          top: 10,
                          bottom: 20
                      }
                  },
                  tooltip: {
                      backgroundColor: 'rgba(0, 0, 0, 0.8)',
                      titleFont: {
                          size: 14,
                          weight: 'bold'
                      },
                      bodyFont: {
                          size: 12
                      },
                      padding: 10,
                      callbacks: {
                          label: function(tooltipItem) {
                              const label = tooltipItem.label || '';
                              const value = tooltipItem.raw || 0;
                              return `${label}: ${value}% of Total Sales`;
                          }
                      }
                  },
                  datalabels: {
                      color: '#fff',
                      font: {
                          size: 12,
                          weight: 'bold',
                          family: "'Arial', sans-serif"
                      },
                      formatter: (value) => {
                          return value + '%';
                      },
                      textShadow: '0 0 3px rgba(0, 0, 0, 0.5)',
                      anchor: 'center',
                      align: 'center'
                  }
              },
              elements: {
                  arc: {
                      borderWidth: 1,
                      borderColor: '#fff'
                  }
              }
          }
      });
  });






// Register the datalabels plugin
Chart.register(ChartDataLabels);

// Ensure DOM is fully loaded before initializing the charts
document.addEventListener('DOMContentLoaded', function () {
    const ctxTopProducts = document.getElementById('top-products-chart').getContext('2d');
    if (!ctxTopProducts) {
        console.error('Canvas element with ID "top-products-chart" not found!');
        return;
    }

    const ctxSalesReport = document.getElementById('chart-profile-visit').getContext('2d');
    if (!ctxSalesReport) {
        console.error('Canvas element with ID "chart-profile-visit" not found!');
        return;
    }

    // Define sales data for Top Selling Products (update these values as needed)
    const totalOrders = 183000; // From "Total Order" card
    const productSales = [
        { name: 'Latte', sales: 54900 },    // Example: 30% of 183,000
        { name: 'Espresso', sales: 128100 } // Example: 70% of 183,000
    ];

    // Calculate percentages for Top Selling Products
    const percentages = productSales.map(product => 
        Math.round((product.sales / totalOrders) * 100)
    );

    // Custom plugin for gradient fill for Top Selling Products
    const gradientPluginTopProducts = {
        id: 'gradientPluginTopProducts',
        beforeDatasetsDraw(chart) {
            const { ctx, chartArea: { top, bottom, left, right } } = chart;
            const dataset = chart.data.datasets[0];
            const meta = chart.getDatasetMeta(0);

            meta.data.forEach((element, index) => {
                const gradient = ctx.createLinearGradient(left, top, right, bottom);
                if (index === 0) {
                    gradient.addColorStop(0, 'rgba(34, 139, 34, 0.9)'); // Dark Green gradient start
                    gradient.addColorStop(1, 'rgba(34, 139, 34, 0.5)'); // Dark Green gradient end
                } else {
                    gradient.addColorStop(0, 'rgba(101, 67, 33, 0.9)');  // Dark Brown gradient start
                    gradient.addColorStop(1, 'rgba(101, 67, 33, 0.5)'); // Dark Brown gradient end
                }
                element.options.backgroundColor = gradient;
            });
        }
    };

    // Register the custom gradient plugin for Top Selling Products
    Chart.register(gradientPluginTopProducts);

    // Top Selling Products Chart
    const topProductsChart = new Chart(ctxTopProducts, {
        type: 'doughnut',
        data: {
            labels: productSales.map(product => product.name), // ['Latte', 'Espresso']
            datasets: [{
                data: percentages, // Calculated percentages [30, 70]
                backgroundColor: [
                    'rgba(34, 139, 34, 0.9)',  // Dark Green
                    'rgba(101, 67, 33, 0.9)'   // Dark Brown
                ],
                borderColor: [
                    'rgba(34, 139, 34, 1)',    // Dark Green border
                    'rgba(101, 67, 33, 1)'     // Dark Brown border
                ],
                borderWidth: 1,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            rotation: 0,
            animation: {
                animateScale: true,
                animateRotate: true,
                duration: 1000
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 12,
                            weight: 'normal'
                        },
                        color: '#333',
                        padding: 15,
                        boxWidth: 20,
                        boxHeight: 20
                    }
                },
                title: {
                    display: true,
                    text: 'Top Selling Products Distribution',
                    font: {
                        size: 14,
                        weight: 'bold',
                        family: "'Arial', sans-serif"
                    },
                    color: '#333',
                    padding: {
                        top: 10,
                        bottom: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 12
                    },
                    padding: 10,
                    callbacks: {
                        label: function(tooltipItem) {
                            const label = tooltipItem.label || '';
                            const value = tooltipItem.raw || 0;
                            const sales = productSales[tooltipItem.dataIndex].sales;
                            return `${label}: ${value}% (${sales} Cups)`;
                        }
                    }
                },
                datalabels: {
                    color: '#fff',
                    font: {
                        size: 12,
                        weight: 'bold',
                        family: "'Arial', sans-serif"
                    },
                    formatter: (value) => {
                        return value + '%';
                    },
                    textShadow: '0 0 3px rgba(0, 0, 0, 0.5)',
                    anchor: 'center',
                    align: 'center'
                }
            },
            elements: {
                arc: {
                    borderWidth: 1,
                    borderColor: '#fff'
                }
            }
        }
    });

    // Sales Report Chart (Bar Chart with Dark Brown)
    const salesReportChart = new Chart(ctxSalesReport, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [{
                label: 'Sales (L.E)',
                data: [20000, 25000, 30000, 28000, 32000],
                backgroundColor: 'rgba(101, 67, 33, 0.9)', // Dark Brown
                borderColor: 'rgba(101, 67, 33, 1)', // Dark Brown border
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Sales Amount (L.E)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Months'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 12,
                            weight: 'normal'
                        },
                        color: '#333'
                    }
                },
                title: {
                    display: true,
                    text: 'Monthly Sales Report',
                    font: {
                        size: 14,
                        weight: 'bold',
                        family: "'Arial', sans-serif"
                    },
                    color: '#333',
                    padding: {
                        top: 10,
                        bottom: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 12
                    },
                    padding: 10,
                    callbacks: {
                        label: function(tooltipItem) {
                            return `${tooltipItem.label}: ${tooltipItem.raw} L.E`;
                        }
                    }
                }
            }
        }
    });
});