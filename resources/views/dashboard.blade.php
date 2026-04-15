@extends('layouts.app')

@section('title', 'Dashboard - Itungin')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="custom-card card-wealth d-flex flex-column justify-content-center">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span style="font-weight: 500; opacity: 0.9;">Total Kekayaan Bersih</span>
                    <span class="badge-trend"><i class="bi bi-graph-up-arrow me-1"></i> +12.5%</span>
                </div>
                <h2 class="wealth-amount">Rp 1.452.000.000</h2>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="d-flex flex-column h-100 justify-content-between gap-3">
                <div class="custom-card py-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-light-green me-3"><i class="bi bi-arrow-up-right"></i></div>
                        <div class="flex-grow-1">
                            <div class="mini-card-title">PENDAPATAN BULANAN</div>
                            <h4 class="mini-card-amount">Rp 85.200.000</h4>
                        </div>
                        <div class="text-green">+4%</div>
                    </div>
                </div>
                <div class="custom-card py-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-light-red me-3"><i class="bi bi-arrow-down-left"></i></div>
                        <div class="flex-grow-1">
                            <div class="mini-card-title">PENGELUARAN BULANAN</div>
                            <h4 class="mini-card-amount">Rp 32.450.000</h4>
                        </div>
                        <div class="text-red">-12%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="custom-card">
                <div class="chart-header">
                    <h5 class="fw-bold m-0">Grafik Finansial</h5>
                    <div class="toggle-btn-group">
                        <button class="toggle-btn active">Bulan</button>
                        <button class="toggle-btn">Tahun</button>
                    </div>
                </div>
                <div style="height: 280px; width: 100%;">
                    <canvas id="financeChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="custom-card" style="background-color: #f8faff;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold m-0">Aktifitas Terbaru</h5>
                    <a href="#" class="text-decoration-none fw-bold" style="font-size: 0.8rem; color: #0d52c6;">View All</a>
                </div>

                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon"><i class="bi bi-bag"></i></div>
                        <div class="activity-details"><h6>Belanja</h6><small>2 hours ago</small></div>
                        <div class="activity-amount text-danger">-Rp 1.250k</div>
                    </div>
                    </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const ctx = document.getElementById('financeChart').getContext('2d');
        const labels = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN'];
        const dataActual = [20, 35, 45, 48, 65, 62, 75];
        const dataTarget = [10, 18, 28, 32, 33, 36, 42];

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Actual', data: dataActual, borderColor: '#0d52c6', borderWidth: 3, tension: 0.4, pointRadius: 0, pointHoverRadius: 6 },
                    { label: 'Target', data: dataTarget, borderColor: '#86efac', borderWidth: 2, borderDash: [5, 5], tension: 0.4, pointRadius: 0, pointHoverRadius: 0 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
                scales: {
                    x: { grid: { display: false, drawBorder: false }, ticks: { font: { size: 10, family: 'Inter' }, color: '#9ca3af' } },
                    y: { display: false, grid: { color: '#f3f4f6', drawBorder: false }, min: 0, max: 90 }
                },
                interaction: { mode: 'nearest', axis: 'x', intersect: false }
            }
        });
    </script>
@endpush