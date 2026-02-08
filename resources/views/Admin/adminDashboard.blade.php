@extends('layouts.adminLayout')

@section('title', 'Admin Dashboard')

@section('css')
    <style>
        :root {
            --primary-blue: #4A90E2;
            --primary-light: #f5f7fa;
            --text-primary: #2d3436;
            --text-secondary: #636e72;
            --border-color: #e1e8ed;
            --hover-bg: #f1f3f5;
            --card-bg: #ffffff;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }

        body {
            background: var(--primary-light);
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, #357abd 100%);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            color: white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .dashboard-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 0.35rem 0;
        }

        .dashboard-header p {
            font-size: 0.85rem;
            opacity: 0.9;
            margin: 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 1.25rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-blue), #357abd);
        }

        .stat-card.installations::before {
            background: linear-gradient(90deg, #27ae60, #229954);
        }

        .stat-card.partners::before {
            background: linear-gradient(90deg, #f39c12, #e67e22);
        }

        .stat-card.stock::before {
            background: linear-gradient(90deg, #8e44ad, #7d3c98);
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 0.75rem;
            background: var(--primary-light);
        }

        .stat-card.installations .stat-icon {
            background: #d4edda;
            color: var(--success-color);
        }

        .stat-card.partners .stat-icon {
            background: #fff3cd;
            color: var(--warning-color);
        }

        .stat-card.stock .stat-icon {
            background: #e7d4f5;
            color: #8e44ad;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 0.35rem 0;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-secondary);
            font-weight: 500;
            margin: 0;
        }

        .stat-info {
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border-color);
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .stat-info i {
            margin-right: 0.35rem;
        }

        .chart-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .chart-card {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 1.25rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        .chart-card h3 {
            margin: 0 0 0.75rem 0;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.95rem;
        }

        .activity-list {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 1.25rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        .activity-list h3 {
            margin: 0 0 1rem 0;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.95rem;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            border-bottom: 1px solid var(--border-color);
            transition: background 0.2s;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item:hover {
            background: var(--primary-light);
        }

        .activity-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .activity-icon.success {
            background: #d4edda;
            color: var(--success-color);
        }

        .activity-icon.warning {
            background: #fff3cd;
            color: var(--warning-color);
        }

        .activity-text {
            flex: 1;
        }

        .activity-text p {
            margin: 0;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.85rem;
        }

        .activity-text small {
            color: var(--text-secondary);
            font-size: 0.75rem;
        }

        .empty-state {
            padding: 1.5rem;
            text-align: center;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 2.5rem;
            opacity: 0.5;
            margin-bottom: 0.75rem;
        }

        @media (max-width: 768px) {
            .dashboard-header h1 {
                font-size: 1.2rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .stat-number {
                font-size: 1.75rem;
            }

            .chart-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div style="padding: 1rem 0;">
        <div style="max-width: 1400px; margin: 0 auto; padding: 0 1rem;">
            <!-- Header -->
            <div class="dashboard-header">
                <h1><i class="fas fa-sun me-2"></i>Solar Energy Dashboard</h1>
                <p>Welcome back! Here's your business overview</p>
            </div>

            <!-- Statistics Grid -->
            <div class="stats-grid">
                <!-- Installations Card -->
                <div class="stat-card installations">
                    <div class="stat-icon">
                        <i class="fas fa-solar-panel"></i>
                    </div>
                    <p class="stat-number">{{ $totalInstallations ?? 0 }}</p>
                    <p class="stat-label">Total Installations</p>
                    <div class="stat-info">
                        <i class="fas fa-arrow-up"></i>
                        <span>{{ $installationsThisMonth ?? 0 }} this month</span>
                    </div>
                </div>

                <!-- Channel Partners Card -->
                <div class="stat-card partners">
                    <div class="stat-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <p class="stat-number">{{ $totalPartners ?? 0 }}</p>
                    <p class="stat-label">Channel Partners</p>
                    <div class="stat-info">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ $activePartners ?? 0 }} active</span>
                    </div>
                </div>

                <!-- Stock Card -->
                <div class="stat-card stock">
                    <div class="stat-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <p class="stat-number">{{ $totalStock ?? 0 }}</p>
                    <p class="stat-label">Current Stock</p>
                    <div class="stat-info">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>{{ $lowStock ?? 0 }} low stock</span>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="chart-section">
                <!-- Installation Trend -->
                <div class="chart-card">
                    <h3><i class="fas fa-chart-line me-2"></i>Installation Trend</h3>
                    <canvas id="installationChart" height="100"></canvas>
                </div>

                <!-- Partner Distribution -->
                {{-- <div class="chart-card">
                    <h3><i class="fas fa-chart-pie me-2"></i>Partner Distribution</h3>
                    <canvas id="partnerChart" height="100"></canvas>
                </div> --}}
            </div>

            <!-- Recent Activity -->
            <div class="activity-list">
                <h3><i class="fas fa-clock me-2"></i>Recent Activity</h3>
                @if(isset($recentActivity) && count($recentActivity) > 0)
                    @foreach($recentActivity as $activity)
                        <div class="activity-item">
                            <div class="activity-icon {{ $activity['type'] ?? 'success' }}">
                                <i class="{{ $activity['icon'] ?? 'fas fa-check' }}"></i>
                            </div>
                            <div class="activity-text">
                                <p>{{ $activity['description'] ?? 'No description' }}</p>
                                <small>{{ $activity['time'] ?? 'Recently' }}</small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No recent activity</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Installation Chart
        const ctx1 = document.getElementById('installationChart');
        if (ctx1) {
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Installations',
                        data: [12, 19, 15, 25, 22, 30],
                        borderColor: '#27ae60',
                        backgroundColor: 'rgba(39, 174, 96, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointBackgroundColor: '#27ae60'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { font: { size: 11 } }
                        },
                        x: {
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });
        }

        // Partner Chart
        const ctx2 = document.getElementById('partnerChart');
        if (ctx2) {
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Vendors', 'Partners', 'Installers'],
                    datasets: [{
                        data: [30, 45, 25],
                        backgroundColor: ['#f39c12', '#27ae60', '#8e44ad'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { font: { size: 11 } }
                        }
                    }
                }
            });
        }
    </script>
@endsection