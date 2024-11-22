document.addEventListener("DOMContentLoaded", function () {
    // Earnings Chart
    const earningsChartCtx = document.getElementById('earningsChart').getContext('2d');
    const earningsChart = new Chart(earningsChartCtx, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Earnings in $',
                data: [120, 150, 180, 220, 100, 90, 50],
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        },
        options: {
            responsive: true,
        }
    });

    // Orders Chart
    const ordersChartCtx = document.getElementById('ordersChart').getContext('2d');
    const ordersChart = new Chart(ordersChartCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Orders',
                data: [10, 12, 15, 9, 14, 8, 7],
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                fill: true
            }]
        },
        options: {
            responsive: true,
        }
    });
});
