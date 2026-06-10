        </div> <!-- container -->
    </div> <!-- main-content -->
</div> <!-- wrapper -->
<script>
const ctx = document.getElementById('ticketChart');

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['New', 'Open', 'Closed'],
        datasets: [{
            data: [
                <?= $new['t'] ?>,
                <?= $open['t'] ?>,
                <?= $closed['t'] ?>
            ],
            backgroundColor: [
                '#3b82f6', // New (blue)
                '#f59e0b', // Open (yellow)
                '#22c55e'  // Closed (green)
            ],
            borderWidth: 0,
            hoverOffset: 8
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
</body>
</html>