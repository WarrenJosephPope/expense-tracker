@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    <!-- Month and Year Selection Form -->
    <form id="filterForm" class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:gap-4">
            <div>
                <label for="month" class="block text-gray-700">Select Month:</label>
                <select id="month" name="month" class="border border-gray-300 rounded-md p-2">
                    @foreach (range(1, 12) as $month)
                    <option value="{{ $month }}" {{ $month == now()->month ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="year" class="block text-gray-700">Select Year:</label>
                <select id="year" name="year" class="border border-gray-300 rounded-md p-2">
                    @foreach (range(now()->year - 5, now()->year) as $year)
                    <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="mt-4 md:mt-0 bg-gray-800 text-white rounded-md p-2">Filter</button>
            </div>
        </div>
    </form>

    <div class="flex flex-wrap justify-center gap-6">
        <!-- Line Chart for Expenses -->
        <div class="w-full md:w-1/2 lg:w-1/3">
            <h2 class="text-xl font-semibold mb-4">Expenses per Day</h2>
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
document.addEventListener('DOMContentLoaded', function () {
    let lineChart;  // Store the line chart instance
    let pieChart;   // Store the pie chart instance

    // Define a color array with at least 20 colors
    const colors = [
        'rgba(255, 99, 132, 0.6)', // Red
        'rgba(54, 162, 235, 0.6)', // Blue
        'rgba(255, 206, 86, 0.6)', // Yellow
        'rgba(75, 192, 192, 0.6)', // Teal
        'rgba(153, 102, 255, 0.6)', // Purple
        'rgba(255, 159, 64, 0.6)', // Orange
        'rgba(255, 205, 86, 0.6)', // Light Yellow
        'rgba(75, 75, 192, 0.6)', // Dark Teal
        'rgba(64, 255, 64, 0.6)', // Green
        'rgba(255, 64, 64, 0.6)', // Light Red
        'rgba(64, 64, 255, 0.6)', // Light Blue
        'rgba(192, 75, 192, 0.6)', // Pink
        'rgba(255, 150, 100, 0.6)', // Light Orange
        'rgba(150, 150, 255, 0.6)', // Soft Blue
        'rgba(100, 255, 100, 0.6)', // Light Green
        'rgba(150, 255, 255, 0.6)', // Aqua
        'rgba(255, 150, 150, 0.6)', // Soft Red
        'rgba(100, 100, 150, 0.6)', // Soft Purple
        'rgba(255, 100, 100, 0.6)', // Bright Red
    ];

    // Function to fetch data and render charts
    function fetchDataAndRenderCharts(month, year) {
        fetch(`/dashboard/data?month=${month}&year=${year}`)
            .then(response => response.json())
            .then(data => {
                // Destroy existing line chart if it exists
                if (lineChart) {
                    lineChart.destroy();
                }
                
                // Line Chart for Expenses per Day
                const lineCtx = document.getElementById('lineChart').getContext('2d');
                lineChart = new Chart(lineCtx, {
                    type: 'line',
                    data: {
                        labels: data.lineChartLabels,  // Use dynamic labels
                        datasets: [{
                            label: 'Expenses',
                            data: data.lineChartData,  // Use dynamic data
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

                // Destroy existing pie chart if it exists
                if (pieChart) {
                    pieChart.destroy();
                }
                
                // Pie Chart for Expenses by Category
                const pieCtx = document.getElementById('pieChart').getContext('2d');
                const pieChartColors = data.pieChartLabels.map((_, index) => colors[index % colors.length]);

                pieChart = new Chart(pieCtx, {
                    type: 'pie',
                    data: {
                        labels: data.pieChartLabels,  // Use dynamic category labels
                        datasets: [{
                            data: data.pieChartData,  // Use dynamic category totals
                            backgroundColor: pieChartColors,  // Use dynamic colors
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
    }

    // Initial render
    fetchDataAndRenderCharts({{ now()->month }}, {{ now()->year }});

    // Handle form submission
    document.getElementById('filterForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;
        fetchDataAndRenderCharts(month, year);
    });
});
</script>
@endsection