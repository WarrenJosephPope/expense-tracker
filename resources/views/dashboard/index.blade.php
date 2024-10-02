@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    <div class="flex flex-wrap justify-center gap-6">
        <!-- Line Chart for Expenses -->
        <div class="w-full md:w-1/2 lg:w-1/3">
            <h2 class="text-xl font-semibold mb-4">Expenses per Day (Current Month)</h2>
            <canvas id="lineChart" class="h-64"></canvas>
        </div>

        <!-- Pie Chart for Expenses by Category -->
        <div class="w-full md:w-1/2 lg:w-1/3">
            <h2 class="text-xl font-semibold mb-4">Total Expenses per Category</h2>
            <canvas id="pieChart" class="h-64"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Line Chart for Expenses per Day
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: @json($lineChartLabels), // Use dynamic labels
                datasets: [{
                    label: 'Expenses',
                    data: @json(array_values($lineChartData)), // Use dynamic data
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'YYYY-MM-DD'
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Pie Chart for Expenses by Category
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: @json($pieChartLabels), // Use dynamic category labels
                datasets: [{
                    data: @json($pieChartData), // Use dynamic category totals
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'] // Adjust colors as needed
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection