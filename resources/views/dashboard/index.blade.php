@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Cards Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted">Data Jabatan</h6>
                    <h4 class="fw-bold">{{ $positionCount ?? 0 }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Karyawan</h6>
                    <h4 class="fw-bold">{{ $userCount ?? 0 }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted">Hadir Hari Ini</h6>
                    <h4 class="fw-bold text-success">{{ $hadirToday ?? 0 }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted">Izin Hari Ini</h6>
                    <h4 class="fw-bold text-warning">{{ $izinToday ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Belum Absen Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted">Belum Absen Hari Ini</h6>
                    <h4 class="fw-bold text-danger">{{ $notYetAbsenUsers->count() ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Pegawai Belum Absen -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Pegawai Belum Absen Hari Ini</h5>
            <div class="row">
                @forelse($notYetAbsenUsers ?? [] as $user)
                <div class="col-md-3 mb-2">
                    <span class="badge bg-danger">{{ $user->name }}</span>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-success text-center">
                        <i class="fas fa-check-circle"></i> Semua pegawai sudah absen hari ini.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Row Charts -->
    <div class="row mb-4">
        <!-- Pie Chart Hari Ini -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">{{ $todayChart['title'] ?? 'Kehadiran Hari Ini' }}</h5>
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="todayChart" width="400" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bar Chart Periode -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">{{ $attendanceChart['title'] ?? 'Kehadiran Periode' }}</h5>
                        <div class="btn-group" role="group">
                            <a href="{{ url('dashboard?period=daily') }}"
                                class="btn btn-sm {{ $period == 'daily' ? 'btn-primary' : 'btn-outline-primary' }}">
                                Harian
                            </a>
                            <a href="{{ url('dashboard?period=weekly') }}"
                                class="btn btn-sm {{ $period == 'weekly' ? 'btn-primary' : 'btn-outline-primary' }}">
                                Mingguan
                            </a>
                            <a href="{{ url('dashboard?period=monthly') }}"
                                class="btn btn-sm {{ $period == 'monthly' ? 'btn-primary' : 'btn-outline-primary' }}">
                                Bulanan
                            </a>
                        </div>
                    </div>
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Chart -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">{{ $monthlyChart['title'] ?? 'Tren Bulanan' }}</h5>
                    <div class="chart-container" style="position: relative; height: 400px;">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6>Total Hadir ({{ $period == 'daily' ? '7 Hari' : ($period == 'weekly' ? '4 Minggu' : '6 Bulan') }})</h6>
                    <h4 id="totalHadir">{{ isset($attendanceChart['hadir']) ? array_sum($attendanceChart['hadir']) : 0 }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h6>Total Izin ({{ $period == 'daily' ? '7 Hari' : ($period == 'weekly' ? '4 Minggu' : '6 Bulan') }})</h6>
                    <h4 id="totalIzin">{{ isset($attendanceChart['izin']) ? array_sum($attendanceChart['izin']) : 0 }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h6>Total Alpha ({{ $period == 'daily' ? '7 Hari' : ($period == 'weekly' ? '4 Minggu' : '6 Bulan') }})</h6>
                    <h4 id="totalAlpha">{{ isset($attendanceChart['alpha']) ? array_sum($attendanceChart['alpha']) : 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>

    window.chartData = @json([
        'today' => $todayChart,
        'attendance' => $attendanceChart,
        'monthly' => $monthlyChart
    ]);
    console.log('DEBUG ChartData:', window.chartData);

document.addEventListener("DOMContentLoaded", function() {
    console.log("Chart script loaded");
    console.log("Chart data:", window.chartData);
    console.log("Chart Data Today:", window.chartData.today);
    console.log("Chart Data Attendance:", window.chartData.attendance);
    console.log("Chart Data Monthly:", window.chartData.monthly);


    // Check if chart data exists
    if (!window.chartData) {
        console.error("Chart data not found!");
        return;
    }

    // Colors
    const colors = {
        hadir: '#28a745',
        izin: '#ffc107', 
        alpha: '#dc3545'
    };

    // 1. PIE CHART - Kehadiran Hari Ini
    if (window.chartData.today && window.chartData.today.labels) {
        const todayCtx = document.getElementById('todayChart').getContext('2d');
        new Chart(todayCtx, {
            type: 'pie',
            data: {
                labels: window.chartData.today.labels || [],
                datasets: [{
                    data: window.chartData.today.data || [],
                    backgroundColor: window.chartData.today.colors || [],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }

    // 2. BAR CHART - Kehadiran Periode
    if (window.chartData.attendance && window.chartData.attendance.labels) {
        const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(attendanceCtx, {
            type: 'bar',
            data: {
                labels: window.chartData.attendance.labels || [],
                datasets: [
                    {
                        label: 'Hadir',
                        data: window.chartData.attendance.hadir || [],
                        backgroundColor: colors.hadir,
                        borderColor: colors.hadir,
                        borderWidth: 1
                    },
                    {
                        label: 'Izin',
                        data: window.chartData.attendance.izin || [],
                        backgroundColor: colors.izin,
                        borderColor: colors.izin,
                        borderWidth: 1
                    },
                    {
                        label: 'Alpha',
                        data: window.chartData.attendance.alpha || [],
                        backgroundColor: colors.alpha,
                        borderColor: colors.alpha,
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    }

    // 3. LINE CHART - Kehadiran Bulanan
    if (window.chartData.monthly && window.chartData.monthly.labels) {
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: window.chartData.monthly.labels || [],
                datasets: [
                    {
                        label: 'Hadir',
                        data: window.chartData.monthly.hadir || [],
                        backgroundColor: 'rgba(40, 167, 69, 0.2)',
                        borderColor: colors.hadir,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Izin',
                        data: window.chartData.monthly.izin || [],
                        backgroundColor: 'rgba(255, 193, 7, 0.2)',
                        borderColor: colors.izin,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Alpha',
                        data: window.chartData.monthly.alpha || [],
                        backgroundColor: 'rgba(220, 53, 69, 0.2)',
                        borderColor: colors.alpha,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection