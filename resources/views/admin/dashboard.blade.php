@extends('cms_login.index_admin')
<!-- Memuat jQuery dari CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Memuat jQuery UI dari CDN -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- Memuat CSS untuk jQuery UI (dibutuhkan untuk styling datepicker) -->

@section('content')
<div class="container-fluid">

    <div class="box">
        <div class="box-body">

            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class='bx bxs-user' ></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Device Access</span>
                            <span class="info-box-number">{{$totalAccessDevice}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class='bx bxs-pie-chart-alt-2'></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Precentage Highest Access</span>
                            <span class="info-box-number">{{round($highestAccess,2) . ' %'}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class='bx bx-globe'></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Most Access Region</span>
                            <span class="info-box-number">{{isset($accessByCity['city']) ? $accessByCity['city'] : 0}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-danger">
                        <span class="info-box-icon"><i class='bx bxs-grid-alt'></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Activity</span>
                            <span class="info-box-number">{{$countActivity}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2 mt-2" style="height: 300px;">
                            <canvas id="accessBarChart"></canvas>
                        </div>
                        <div class="col-md-6 mt-2">
                            <canvas id="accessPercentageChart"></canvas>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div id="w0" class="gridview table-responsive" style="overflow-y: auto;max-height:525px;">
                        <table class="table text-nowrap table-striped table-bordered mb-0">
                            <thead>
                                <tr>
                                    <td></td>
                                    <td style="max-width: 5%;">IP Address</td>
                                    <td>District</td>
                                    <td>Province</td>
                                    <td>Country</td>
                                    <td style="width: 45px;">Access Precentage</td>
                                    <td style="width: 15px;">Total Activity</td>
                                </tr>
                                <form action="{{route('admin.dashboard.search')}}" id="searchForm" method="get">
                                    @csrf
                                    <tr id="w0-filters" class="filters">
                                        <td></td>
                                        <td><input type="text" class="form-control" name="search[ip_address]" onkeypress="handleKeyPress(event)" value="{{(isset($searchData['ip_address'])) ? $searchData['ip_address'] : ''}}"></td>
                                        <td><input type="text" class="form-control" name="search[district]" onkeypress="handleKeyPress(event)" value="{{(isset($searchData['district'])) ? $searchData['district'] : ''}}"></td>
                                        <td><input type="text" class="form-control" name="search[province]" onkeypress="handleKeyPress(event)" value="{{(isset($searchData['province'])) ? $searchData['province'] : ''}}"></td>
                                        <td><input type="text" class="form-control" name="search[country]" onkeypress="handleKeyPress(event)" value="{{(isset($searchData['country'])) ? $searchData['country'] : ''}}"></td>
                                    </tr>
                                </form>
                            </thead>
                            <tbody>
                                @forelse ($accessPercentageByIP as $access)
                                    <tr>
                                        <td>{{$loop->index += 1}}</td>
                                        <td>{{$access['ip_address']}}</td>
                                        <td>{{strpos($access['city'], ',') !== false ? explode(',', $access['city'])[0] : $access['city']}}</td>
                                        <td>{{strpos($access['city'], ',') !== false ? explode(',', $access['city'])[1] : $access['city']}}</td>
                                        <td>{{$access['country']}}</td>
                                        <td>{{round($access['access_percentage'], 2) . ' %'}}</td>
                                        <td>{{$access['total_access']}}</td>
                                    </tr>
                                @empty
                                    <p class="ml-2 mt-3 text-danger">Data access not found!</p>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
                
            </div>

        </div>
    </div>
</div>

@endsection

<script>
        document.addEventListener('DOMContentLoaded', function () {

            var accessPercentageData = @json($accessPercentageByIP);

            var labels = [];
            var data = [];
            var total_access = [];

            accessPercentageData.forEach(function(item) {
                labels.push(item.ip_address);
                data.push(item.access_percentage.toFixed(2));
                total_access.push(item.total_access.toFixed(2));
            });

            var ctx = document.getElementById('accessPercentageChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Access Percentage',
                        data: data,
                        backgroundColor: [
                            'rgba(126, 232, 138, 0.7)',
                            'rgba(251, 91, 90, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(250, 193, 62, 0.7)',
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(90, 251, 236, 0.7)',
                            'rgba(228, 90, 250, 0.7)',
                            'rgba(250, 90, 182, 0.7)',
                            'rgba(182, 250, 90, 0.7)',
                        ],
                        borderColor: [
                            'rgba(126, 232, 138, 1)',
                            'rgba(251, 91, 90, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(250, 193, 62, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(90, 251, 236, 1)',
                            'rgba(228, 90, 250, 1)',
                            'rgba(250, 90, 182, 1)',
                            'rgba(182, 250, 90, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Access Percentage by IP'
                        },
                        legend: {
                            position: 'bottom'
                        }
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                                    return previousValue + currentValue;
                                });
                                var currentValue = dataset.data[tooltipItem.index];
                                var percentage = Math.floor(((currentValue/total) * 100)+0.5);
                                return labels[tooltipItem.index] + ': ' + currentValue + '%';
                            }
                        }
                    }
                }
            });

            var ctxBar = document.getElementById('accessBarChart').getContext('2d');
            var myBarChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Activity Access', // Mengubah label
                        data: total_access,
                        backgroundColor: [
                            'rgba(126, 232, 138, 0.7)',
                            'rgba(251, 91, 90, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)',
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(90, 251, 236, 0.7)',
                            'rgba(228, 90, 250, 0.7)',
                            'rgba(250, 90, 182, 0.7)',
                            'rgba(182, 250, 90, 0.7)',
                        ],
                        borderColor: [
                            'rgba(126, 232, 138, 1)',
                            'rgba(251, 91, 90, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(90, 251, 236, 1)',
                            'rgba(228, 90, 250, 1)',
                            'rgba(250, 90, 182, 1)',
                            'rgba(182, 250, 90, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Access Total by IP'
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    return value.toFixed(2);
                                }
                            }
                        },
                        x: {
                            barPercentage: 0.5,
                            categoryPercentage: 0.5
                        }
                    }
                }
            });
        });
</script>

<script>
    function handleKeyPress(event) {
        // Periksa apakah tombol yang ditekan adalah tombol "Enter" (kode 13)
        if (event.keyCode === 13) {
            // Hentikan perilaku bawaan dari tombol "Enter" (yang akan mengirimkan formulir)
            event.preventDefault();
            // Submit formulir secara manual
            document.getElementById('searchForm').submit();
        }
    }
</script>