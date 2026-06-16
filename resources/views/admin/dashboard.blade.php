@extends('layouts.main')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    <div class="container-fluid">
    <div class="row">

        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cloud-upload-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Recent Uploads</span>
                    <span class="info-box-number">
                        {{ $recentUploads }}
                    </span>
                </div>
                </div>
            </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="info-box">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-file-archive"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Files Uploaded</span>
                    <span class="info-box-number">{{ $totalFiles }}</span>
                </div>
                </div>
            </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="info-box">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Registered Employees</span>
                    <span class="info-box-number">{{ $registeredEmployees }}</span>
                </div>
                </div>
            </div>
        </div>

        {{-- BAR CHART START --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h5 class="card-title">Monthly File Uploads</h5>
                        <div class="card-tools">
                            <select id="yearFilter" class="form-control form-control-sm" style="width: 100px;">
                                @forelse ($years as $year)
                                    <option value="{{ $year }}" {{ $loop->first ? 'selected' : '' }}>{{ $year }}</option>
                                @empty
                                    <option>No Data</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="position-relative">
                            <canvas id="uploadsChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- BAR CHART END --}}

    </div>
@endsection

{{-- @push('scripts') --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get data from the controller, safely encoded as JSON
        const uploadsByYear = @json($uploadsByMonth);
        const yearFilter = document.getElementById('yearFilter');
        const ctx = document.getElementById('uploadsChart').getContext('2d');
        let fileUploadChart;

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                        grid: { display: false }, 
                        border: { display: false },
                        beginAtZero: true
                    },
                y: {
                    grid: { display: true }, 
                    border: { display: false }, 
                    beginAtZero: true,
                    ticks: {
                        // This ensures the Y-axis only shows whole numbers
                        callback: function(value) {if (value % 1 === 0) {return value;}}
                    }
                }
            },
            plugins: {
                legend: {
                    display: false // We don't need a legend for a single dataset
                }
            },
            tooltips: {
                callbacks: {
                   label: function(tooltipItem) {
                          return "Uploads: " + tooltipItem.yLabel;
                   }
                }
            }
        };

        const chartConfig = {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Uploads',
                    backgroundColor: 'rgba(25, 135, 84, 1)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    data: [] // Initial data is empty, will be populated by the update function
                }]
            },
            options: chartOptions
        };

        // Function to update chart data based on selected year
        function updateChart() {
            const selectedYear = yearFilter.value;
            // Get data for the selected year, or an empty array of 12 zeros if no data exists
            const newData = uploadsByYear[selectedYear] || Array(12).fill(0);

            if (fileUploadChart) {
                fileUploadChart.data.datasets[0].data = newData;
                fileUploadChart.update();
            }
        }

        // Initialize the chart
        fileUploadChart = new Chart(ctx, chartConfig);

        // Add an event listener to the dropdown
        yearFilter.addEventListener('change', updateChart);

        // Call the function once to load the initial data for the default selected year
        updateChart();
    });
</script>
{{-- @endpush --}}