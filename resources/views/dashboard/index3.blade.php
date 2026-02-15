@extends('layouts.master')
@section('title', 'Dashboard - Employee')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
<style>
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .project-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .project-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .notification-item {
        border-left: 3px solid transparent;
        transition: all 0.3s;
        padding: 12px;
        border-radius: 6px;
    }
    .notification-item:hover {
        background-color: #f8f9fa;
        border-left-color: #696cff;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
    
    /* Modern Card Design */
    .modern-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s;
    }
    .modern-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    }
    
    /* Custom Dropdown */
    .custom-select {
        border-radius: 8px;
        border: 1px solid #d9dee3;
        padding: 8px 12px;
        font-size: 0.875rem;
    }
    
    /* Chart Container */
    .chart-container {
        padding: 20px;
        background: linear-gradient(135deg, #f5f7fa 0%, #ffffff 100%);
        border-radius: 12px;
    }
    
    /* Equal Height Cards */
    .equal-height-card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .equal-height-card .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    /* Achievement Section */
    .achievement-card {
        background: linear-gradient(135deg, #fff5e6 0%, #fff 100%);
        border: 2px solid #ffab00;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
    }
    
    .motivation-card {
        background: linear-gradient(135deg, #e6f7ff 0%, #fff 100%);
        border: 2px solid #03c3ec;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
    }
    
    .achievement-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }
    
    /* Recent Activity Cards */
    .activity-card {
        border-left: 4px solid #696cff;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 10px;
        transition: all 0.3s;
    }
    
    .activity-card:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }
    
    .activity-time {
        font-size: 12px;
        color: #8592a3;
    }
    
    /* Top Performer List */
    .top-performer-list {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .performer-item {
        padding: 12px;
        border-radius: 8px;
        background: #fff;
        border: 1px solid #e7e7e7;
        margin-bottom: 8px;
        transition: all 0.3s;
    }
    
    .performer-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .performer-rank {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        margin-right: 12px;
    }
    
    .rank-1 { background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: #fff; }
    .rank-2 { background: linear-gradient(135deg, #C0C0C0 0%, #808080 100%); color: #fff; }
    .rank-3 { background: linear-gradient(135deg, #CD7F32 0%, #8B4513 100%); color: #fff; }
    .rank-4 { background: #f8f9fa; color: #8592a3; border: 1px solid #d9dee3; }
    .rank-5 { background: #f8f9fa; color: #8592a3; border: 1px solid #d9dee3; }
    
    .current-user-highlight {
        background: linear-gradient(135deg, #e6f7ff 0%, #fff 100%);
        border: 2px solid #03c3ec;
    }
</style>
@endpush

@section('content')
<!-- Welcome Card with User Info -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card modern-card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="card-title text-primary mb-0">Selamat Datang Kembali,<b class="text-warning"> Developer</b> ! 🎯</h5>
                        </div>
                        
                      
                        
                        <!-- Notifications -->
                        <div class="alert alert-info mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-bell-ring me-2"></i>
                                <div>
                                    <strong>Informasi Baru!</strong> Anda mendapatkan 
                                    <span class="fw-bold text-primary">2 proyek baru</span> dan 
                                    <span class="fw-bold text-warning" style="cursor: pointer;">8 task baru</span> minggu ini.
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-sm btn-primary" onclick="location.href='{{ route('master-data-tugas.index') }}'">
                                <i class="bx bx-task me-1"></i> Lihat Semua Task
                            </button>
                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#deadlineModal">
                                <i class="bx bx-calendar-exclamation me-1"></i> Deadline Mendekat (5)
                            </button>
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#projectListModal">
                                <i class="bx bx-briefcase me-1"></i> Proyek Saya (3)
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                       
                        <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="120" alt="Dashboard" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards for Employee -->
<div class="row">
    <!-- My Projects -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card project-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <span class="fw-semibold d-block mb-1 text-muted">Proyek Saya</span>
                        <h3 class="card-title mb-2">3</h3>
                        <small class="text-primary fw-semibold">
                            <i class="bx bx-briefcase"></i> Sedang Dikerjakan
                        </small>
                    </div>
                    <div class="stat-icon bg-label-primary">
                        <i class="bx bx-folder-open text-primary" style="font-size: 1.75rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Task Selesai -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card project-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <span class="fw-semibold d-block mb-1 text-muted">Task Selesai</span>
                        <h3 class="card-title mb-2">28</h3>
                        <small class="text-success fw-semibold">
                            <i class="bx bx-check-circle"></i> +5 minggu ini
                        </small>
                    </div>
                    <div class="stat-icon bg-label-success">
                        <i class="bx bx-check-shield text-success" style="font-size: 1.75rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Task In Progress -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card project-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <span class="fw-semibold d-block mb-1 text-muted">Task Progress</span>
                        <h3 class="card-title mb-2">8</h3>
                        <small class="text-warning fw-semibold">
                            <i class="bx bx-time"></i> Sedang Dikerjakan
                        </small>
                    </div>
                    <div class="stat-icon bg-label-warning">
                        <i class="bx bx-loader-circle text-warning" style="font-size: 1.75rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Task To Do -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card project-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <span class="fw-semibold d-block mb-1 text-muted">Task To Do</span>
                        <h3 class="card-title mb-2">6</h3>
                        <small class="text-info fw-semibold">
                            <i class="bx bx-list-check"></i> Belum Dimulai
                        </small>
                    </div>
                    <div class="stat-icon bg-label-info">
                        <i class="bx bx-list-ul text-info" style="font-size: 1.75rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Upcoming Deadlines & Recent Activity -->
<div class="row">
    <!-- Upcoming Deadlines -->
    <div class="col-lg-6 mb-4">
        <div class="card modern-card equal-height-card">
            <div class="card-header d-flex align-items-center justify-content-between pb-0">
                <div>
                    <h5 class="card-title m-0">Deadline Mendekat</h5>
                    <small class="text-muted">5 task dengan deadline terdekat</small>
                </div>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#deadlineModal">
                    Lihat Semua
                </button>
            </div>
            <div class="card-body">
                <div class="deadline-list">
                    <!-- Deadline Items... (same as before) -->
                    <!-- Deadline 1 - Overdue -->
                    <div class="mb-3 p-3 rounded" style="border-left: 4px solid #ff3e1d; background: #fff5f5;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">API Integration Testing</h6>
                                <small class="text-muted">Proyek: E-Commerce Redesign</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-danger">Melewati 2 hari</div>
                                <small class="text-muted">Deadline: 20 Jan 2024</small>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-danger" style="width: 100%"></div>
                        </div>
                    </div>
                    
                    <!-- Deadline 2 - Today -->
                    <div class="mb-3 p-3 rounded" style="border-left: 4px solid #ffab00; background: #fff5e6;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">User Dashboard Design</h6>
                                <small class="text-muted">Proyek: Mobile App Development</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-warning">Hari Ini</div>
                                <small class="text-muted">Deadline: {{ date('d M Y') }}</small>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: 90%"></div>
                        </div>
                    </div>
                    
                    <!-- More deadlines... -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="col-lg-6 mb-4">
        <div class="card modern-card equal-height-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title m-0">Aktivitas Terbaru</h5>
                    <small class="text-muted">Update terbaru dari proyek Anda</small>
                </div>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#activityModal">
                    Lihat Semua
                </button>
            </div>
            <div class="card-body">
                <!-- Activity Items... (same as before) -->
                <!-- Activity 1 -->
                <div class="activity-card">
                    <div class="d-flex align-items-start">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle bg-label-success">
                                <i class="bx bx-check"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Task Selesai: API Integration</h6>
                            <p class="mb-1">Anda menyelesaikan task API Integration pada proyek E-Commerce Redesign</p>
                            <div class="activity-time">
                                <i class="bx bx-time"></i> 2 jam yang lalu
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- More activities... -->
            </div>
        </div>
    </div>
</div>

<!-- My Projects Progress -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card modern-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title m-0">Proyek Saya</h5>
                    <small class="text-muted">Progress 3 proyek yang sedang dikerjakan</small>
                </div>
                <span class="badge bg-primary">Total: 3 Proyek</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Project 1 -->
                    <div class="col-md-4 mb-3">
                        <div class="project-progress">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">E-Commerce Redesign</h6>
                                <span class="badge bg-primary">Web Dev</span>
                            </div>
                            <small class="text-muted d-block mb-2">Redesign website e-commerce</small>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">Progress</small>
                                <small class="fw-bold">75%</small>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width: 75%"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted d-block">Task</small>
                                    <div class="fw-bold">18/24</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Deadline</small>
                                    <div class="fw-bold text-warning">15 Feb 2024</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Project 2 -->
                    <div class="col-md-4 mb-3">
                        <div class="project-progress">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Mobile App Dev</h6>
                                <span class="badge bg-success">Full Stack</span>
                            </div>
                            <small class="text-muted d-block mb-2">Aplikasi mobile cross-platform</small>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">Progress</small>
                                <small class="fw-bold">45%</small>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: 45%"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted d-block">Task</small>
                                    <div class="fw-bold">9/20</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Deadline</small>
                                    <div class="fw-bold text-info">30 Mar 2024</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Project 3 -->
                    <div class="col-md-4 mb-3">
                        <div class="project-progress">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">ERP System</h6>
                                <span class="badge bg-info">Backend</span>
                            </div>
                            <small class="text-muted d-block mb-2">Integrasi sistem ERP</small>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">Progress</small>
                                <small class="fw-bold">30%</small>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-info" style="width: 30%"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted d-block">Task</small>
                                    <div class="fw-bold">6/20</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Deadline</small>
                                    <div class="fw-bold text-success">15 Apr 2024</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Performance & Top Performers -->
<div class="row">
    <!-- Task Performance Chart -->
    <div class="col-lg-12 mb-4">
        <div class="card modern-card equal-height-card">
            <div class="card-header d-flex align-items-center justify-content-between pb-0">
                <div>
                    <h5 class="card-title m-0">Performa Task Saya</h5>
                    <small class="text-muted">Penyelesaian task berdasarkan timeline</small>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" id="performanceFilterBtn">
                        Minggu Ini
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="javascript:void(0);" onclick="updatePerformanceChart('week')">Minggu Ini</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="updatePerformanceChart('month')">Bulan Ini</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="updatePerformanceChart('year')">Tahun Ini</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <div id="taskPerformanceChart"></div>
                </div>
                <div class="row text-center mt-3">
                    <div class="col-4">
                        <div class="border-end">
                            <div class="fw-bold text-success">15</div>
                            <small class="text-muted">Tepat Waktu</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border-end">
                            <div class="fw-bold text-info">8</div>
                            <small class="text-muted">Sebelum Deadline</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-danger">2</div>
                        <small class="text-muted">Melewati Deadline</small>
                    </div>
                </div>
                
                <!-- Achievement/Motivation Section - Data Dummy -->
                <div class="mt-4">
                    <!-- Data Dummy: User saat ini adalah Top 3 Performer -->
                    @php
                        // Data dummy untuk contoh
                        $currentUserRank = 3; // User saat ini ranking 3
                        $isTopPerformer = $currentUserRank <= 5; // Jika ranking <= 5, termasuk top performer
                        $userTasksOnTime = 28; // Jumlah task tepat waktu user
                    @endphp
                    
                    @if($isTopPerformer)
                    <!-- Jika user adalah top 5 performer -->
                    <div class="achievement-card">
                        <div class="achievement-icon">🏆</div>
                        <h5 class="text-warning">Selamat {{ Auth::user()->nama }}!</h5>
                        <p class="mb-2">Anda termasuk dalam <strong>Top 5 Pegawai</strong> dengan pengerjaan task tepat waktu!</p>
                        <small class="text-muted">Peringkat Anda: <strong>#{{ $currentUserRank }}</strong> dari 29 pegawai</small>
                        <div class="mt-2">
                            <span class="badge bg-warning">{{ $userTasksOnTime }} Task Tepat Waktu</span>
                        </div>
                        <p class="mt-2 mb-0 small text-muted">Pertahankan performa Anda untuk tetap berada di posisi terbaik!</p>
                    </div>
                    @else
                    <!-- Jika user bukan top 5 performer -->
                    <div class="motivation-card">
                        <div class="achievement-icon">💪</div>
                        <h5 class="text-info">Tingkatkan Performa, {{ Auth::user()->nama }}!</h5>
                        <p class="mb-2">Terus tingkatkan produktivitas Anda untuk masuk dalam daftar Top 5 Pegawai.</p>
                        <small class="text-muted">Anda telah menyelesaikan {{ $userTasksOnTime }} task tepat waktu</small>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-info">
                                <i class="bx bx-target-lock me-1"></i> Tetapkan Target
                            </button>
                            <button class="btn btn-sm btn-outline-success ms-1">
                                <i class="bx bx-trending-up me-1"></i> Lihat Tips
                            </button>
                        </div>
                        <p class="mt-2 mb-0 small text-muted">Fokus pada deadline dan komunikasi dengan tim untuk meningkatkan performa.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
</div>


<!-- Modals -->
<!-- Deadline Modal -->
<div class="modal fade" id="deadlineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-calendar-exclamation text-warning me-2"></i>
                    Semua Deadline Task
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Deadline list will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Project List Modal -->
<div class="modal fade" id="projectListModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-briefcase text-primary me-2"></i>
                    Semua Proyek Saya
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Project list will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Activity Modal -->
<div class="modal fade" id="activityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-history text-info me-2"></i>
                    Semua Aktivitas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Activity list will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
'use strict';
(function () {
    // Modern Color Palette
    const colors = {
        primary: '#696cff',
        success: '#71dd37',
        warning: '#ffab00',
        danger: '#ff3e1d',
        info: '#03c3ec',
        secondary: '#8592a3',
        purple: '#8a6fdf',
        pink: '#ff5b9d'
    };
    
    // Task Performance Chart
    let taskPerformanceChartInstance;
    const taskPerformanceChart = document.querySelector('#taskPerformanceChart');
    
    function renderPerformanceChart(timeRange) {
        if (taskPerformanceChartInstance) {
            taskPerformanceChartInstance.destroy();
        }
        
        let seriesData, categories;
        
        switch(timeRange) {
            case 'month':
                seriesData = [
                    { name: 'Tepat Waktu', data: [5, 7, 6, 8, 7, 9, 8, 10, 9, 11, 10, 12, 11, 13, 12, 14, 13, 15, 14, 16, 15, 17, 16, 18, 17, 19, 18, 20, 19, 21] },
                    { name: 'Sebelum Deadline', data: [2, 3, 2, 4, 3, 5, 4, 6, 5, 7, 6, 8, 7, 9, 8, 7, 6, 8, 7, 9, 8, 10, 9, 11, 10, 9, 8, 10, 9, 11] },
                    { name: 'Melewati Deadline', data: [1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 2, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0] }
                ];
                categories = Array.from({length: 30}, (_, i) => i + 1 + ' Jan');
                document.getElementById('performanceFilterBtn').textContent = 'Bulan Ini';
                break;
                
            case 'year':
                seriesData = [
                    { name: 'Tepat Waktu', data: [15, 18, 20, 22, 25, 28, 30, 32, 35, 38, 40, 42] },
                    { name: 'Sebelum Deadline', data: [8, 10, 12, 14, 16, 18, 20, 22, 24, 26, 28, 30] },
                    { name: 'Melewati Deadline', data: [2, 1, 2, 1, 3, 2, 1, 2, 1, 3, 2, 1] }
                ];
                categories = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
                document.getElementById('performanceFilterBtn').textContent = 'Tahun Ini';
                break;
                
            default: // week
                seriesData = [
                    { name: 'Tepat Waktu', data: [3, 4, 5, 6, 7, 8, 9] },
                    { name: 'Sebelum Deadline', data: [1, 2, 2, 3, 3, 4, 4] },
                    { name: 'Melewati Deadline', data: [0, 0, 1, 0, 0, 1, 0] }
                ];
                categories = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                document.getElementById('performanceFilterBtn').textContent = 'Minggu Ini';
        }
        
        if (taskPerformanceChart) {
            const config = {
                chart: {
                    type: 'line',
                    height: 280,
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                series: seriesData,
                colors: [colors.success, colors.info, colors.danger],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                markers: {
                    size: 5,
                    strokeWidth: 2,
                    strokeColors: '#fff',
                    hover: { size: 7 }
                },
                dataLabels: { enabled: false },
                grid: {
                    borderColor: '#f0f0f0',
                    strokeDashArray: 4,
                    padding: {
                        top: 0,
                        bottom: 0,
                        left: 10
                    }
                },
                xaxis: {
                    categories: categories,
                    labels: {
                        style: {
                            colors: '#8592a3',
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Jumlah Task',
                        style: {
                            color: '#8592a3',
                            fontSize: '12px',
                            fontWeight: 500
                        }
                    },
                    labels: {
                        style: {
                            colors: '#8592a3',
                            fontSize: '12px'
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    fontSize: '12px',
                    fontWeight: 500,
                    markers: {
                        width: 10,
                        height: 10,
                        radius: 12
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function (val) {
                            return val + " task"
                        }
                    }
                }
            };
            taskPerformanceChartInstance = new ApexCharts(taskPerformanceChart, config);
            taskPerformanceChartInstance.render();
        }
    }
    
    // Initial render
    renderPerformanceChart('week');
    
    // Global function for dropdown
    window.updatePerformanceChart = function(timeRange) {
        renderPerformanceChart(timeRange);
    };
    
    // Update notification badge with dynamic data
    function updateNotificationBadge() {
        const newProjects = Math.floor(Math.random() * 3) + 1;
        const newTasks = Math.floor(Math.random() * 5) + 3;
        
        const notificationText = `Informasi Baru! Anda mendapatkan 
            <span class="fw-bold text-primary">${newProjects} proyek baru</span> dan 
            <span class="fw-bold text-warning" style="cursor: pointer;">${newTasks} task baru</span> minggu ini.`;
        
        document.querySelector('.alert-info').innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bx bx-bell-ring me-2"></i>
                <div>${notificationText}</div>
            </div>
        `;
    }
    
    // Update deadline counters
    function updateDeadlineCounters() {
        const today = new Date();
        const deadlines = [
            new Date('2024-01-20'), // Overdue
            new Date(today.toISOString().split('T')[0]), // Today
            new Date(today.getTime() + 24 * 60 * 60 * 1000), // Tomorrow
            new Date(today.getTime() + 3 * 24 * 60 * 60 * 1000), // 3 days
            new Date(today.getTime() + 5 * 24 * 60 * 60 * 1000) // 5 days
        ];
        
        let overdue = 0;
        let todayCount = 0;
        
        deadlines.forEach(date => {
            const diffTime = date - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays < 0) overdue++;
            else if (diffDays === 0) todayCount++;
        });
        
        // Update button text
        const deadlineBtn = document.querySelector('[data-bs-target="#deadlineModal"]');
        if (deadlineBtn) {
            deadlineBtn.innerHTML = `<i class="bx bx-calendar-exclamation me-1"></i> Deadline Mendekat (${overdue + todayCount})`;
        }
    }
    
    // Simulate rank updates (for demo purposes)
    function updateRankDisplay() {
        const achievementSection = document.querySelector('.achievement-card, .motivation-card');
        if (achievementSection) {
            // Randomly change the rank for demo (between 1-10)
            const randomRank = Math.floor(Math.random() * 10) + 1;
            const isTopPerformer = randomRank <= 5;
            
            if (isTopPerformer) {
                achievementSection.innerHTML = `
                    <div class="achievement-icon">🏆</div>
                    <h5 class="text-warning">Selamat {{ Auth::user()->nama }}!</h5>
                    <p class="mb-2">Anda termasuk dalam <strong>Top 5 Pegawai</strong> dengan pengerjaan task tepat waktu!</p>
                    <small class="text-muted">Peringkat Anda: <strong>#${randomRank}</strong> dari 29 pegawai</small>
                    <div class="mt-2">
                        <span class="badge bg-warning">28 Task Tepat Waktu</span>
                    </div>
                    <p class="mt-2 mb-0 small text-muted">Pertahankan performa Anda untuk tetap berada di posisi terbaik!</p>
                `;
            } else {
                achievementSection.innerHTML = `
                    <div class="achievement-icon">💪</div>
                    <h5 class="text-info">Tingkatkan Performa, {{ Auth::user()->nama }}!</h5>
                    <p class="mb-2">Terus tingkatkan produktivitas Anda untuk masuk dalam daftar Top 5 Pegawai.</p>
                    <small class="text-muted">Peringkat Anda saat ini: #${randomRank}. Anda telah menyelesaikan 28 task tepat waktu</small>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-outline-info">
                            <i class="bx bx-target-lock me-1"></i> Tetapkan Target
                        </button>
                        <button class="btn btn-sm btn-outline-success ms-1">
                            <i class="bx bx-trending-up me-1"></i> Lihat Tips
                        </button>
                    </div>
                    <p class="mt-2 mb-0 small text-muted">Fokus pada deadline dan komunikasi dengan tim untuk meningkatkan performa.</p>
                `;
            }
        }
    }
    
    // Initialize
    updateNotificationBadge();
    updateDeadlineCounters();
    
    // For demo: update rank every 60 seconds
    setInterval(updateRankDisplay, 60000);
    
    // Simulate real-time updates every 30 seconds
    setInterval(() => {
        updateNotificationBadge();
        updateDeadlineCounters();
    }, 30000);
    
})();
</script>
@endpush