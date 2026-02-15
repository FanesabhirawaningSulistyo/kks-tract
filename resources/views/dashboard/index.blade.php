@extends('layouts.master')
@section('title', 'Dashboard - Project Management')
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
    
    /* Modal Styles */
    .task-modal-item {
        padding: 15px;
        border-left: 3px solid #696cff;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 12px;
        transition: all 0.3s;
    }
    .task-modal-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
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
    
    /* Top Performer Styles */
    .performer-item {
        padding: 16px;
        border-radius: 10px;
        background: #fff;
        border: 1px solid #e7e7e7;
        margin-bottom: 12px;
        transition: all 0.3s;
    }
    
    .performer-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateX(5px);
    }
    
    .performer-rank {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
    }
    
    .performer-rank.rank-1 { background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: #fff; }
    .performer-rank.rank-2 { background: linear-gradient(135deg, #C0C0C0 0%, #808080 100%); color: #fff; }
    .performer-rank.rank-3 { background: linear-gradient(135deg, #CD7F32 0%, #8B4513 100%); color: #fff; }
    .performer-rank.rank-4 { background: #fff4e6; color: #ff9f43; border: 2px solid #ff9f43; }
    .performer-rank.rank-5 { background: #f0f0f0; color: #666; border: 2px solid #999; }
</style>
@endpush
@section('content')
<!-- Welcome Card -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card modern-card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                       <div class="d-flex align-items-center mb-3">
                            <h5 class="card-title text-primary mb-0">Selamat Datang Kembali, <b class="text-warning">{{ Auth::user()->nama }}</b>! 🎯</h5>
                            
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
                        <button class="btn btn-sm btn-primary me-2" onclick="location.href=''">
                            <i class="bx bx-folder-open me-1"></i> Lihat Semua Proyek
                        </button>
                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#reviewTaskModal">
                            <i class="bx bx-bell me-1"></i> Lihat Task Review
                        </button>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="Dashboard" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <!-- Total Projects -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card project-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <span class="fw-semibold d-block mb-1 text-muted">Total Proyek</span>
                        <h3 class="card-title mb-2">24</h3>
                        <small class="text-primary fw-semibold">
                            <i class="bx bx-briefcase"></i> 1 Perusahaan
                        </small>
                    </div>
                    <div class="stat-icon bg-label-primary">
                        <i class="bx bx-folder-open text-primary" style="font-size: 1.75rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Active Projects -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card project-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <span class="fw-semibold d-block mb-1 text-muted">Proyek Aktif</span>
                        <h3 class="card-title mb-2">5</h3>
                        <small class="text-success fw-semibold">
                            <i class="bx bx-timer"></i> Tahap Pemeliharaan
                        </small>
                    </div>
                    <div class="stat-icon bg-label-success">
                        <i class="bx bx-rocket text-success" style="font-size: 1.75rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Completed Projects -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card project-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <span class="fw-semibold d-block mb-1 text-muted">Proyek Selesai</span>
                        <h3 class="card-title mb-2">18</h3>
                        <small class="text-info fw-semibold">
                            <i class="bx bx-check-circle"></i> Telah Selesai
                        </small>
                    </div>
                    <div class="stat-icon bg-label-info">
                        <i class="bx bx-check-shield text-info" style="font-size: 1.75rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Ongoing Projects -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card project-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <span class="fw-semibold d-block mb-1 text-muted">Proyek Dikerjakan</span>
                        <h3 class="card-title mb-2">12</h3>
                        <small class="text-warning fw-semibold">
                            <i class="bx bx-time"></i> Sedang Berjalan
                        </small>
                    </div>
                    <div class="stat-icon bg-label-warning">
                        <i class="bx bx-task text-warning" style="font-size: 1.75rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Status & Project Acquisition -->
<div class="row">
    <!-- Task Status Pie Chart with Filter - LEFT SIDE -->
    <div class="col-lg-6 mb-4">
        <div class="card modern-card equal-height-card">
            <div class="card-header">
                <h5 class="card-title m-0">Status Task</h5>
                <small class="text-muted">Distribusi semua task</small>
            </div>
            <div class="card-body">
                <!-- Filter Dropdown -->
                <div class="mb-3">
                    <label class="form-label small mb-1">Filter Proyek:</label>
                    <select class="form-select form-select-sm custom-select" id="projectFilter" onchange="updateTaskChart()">
                        <option value="all">Semua Proyek Aktif</option>
                        <option value="project1">E-Commerce Redesign</option>
                        <option value="project2">Mobile App Development</option>
                        <option value="project3">ERP System</option>
                        <option value="project4">Cloud Migration</option>
                    </select>
                </div>
                
                <div class="row align-items-center">
                    <!-- Pie Chart - Right Side -->
                    <div class="col-md-6 order-md-2">
                        <div id="taskStatusChart"></div>
                    </div>
                    
                    <!-- Legend - Left Side -->
                    <div class="col-md-6 order-md-1">
                        <div class="task-status-legend">
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background: #f8f9fa;">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary me-2" style="width: 14px; height: 14px;"></span>
                                    <span class="fw-semibold">To Do</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">45</div>
                                    <small class="text-muted">28%</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background: #fff5e6;">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning me-2" style="width: 14px; height: 14px;"></span>
                                    <span class="fw-semibold">In Progress</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">32</div>
                                    <small class="text-muted">20%</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background: #e6f7ff;">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-info me-2" style="width: 14px; height: 14px;"></span>
                                    <span class="fw-semibold">Review</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">18</div>
                                    <small class="text-muted">11%</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: #e6ffe6;">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success me-2" style="width: 14px; height: 14px;"></span>
                                    <span class="fw-semibold">Done</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">67</div>
                                    <small class="text-muted">41%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Project Acquisition Line Chart - RIGHT SIDE -->
    <div class="col-lg-6 mb-4">
        <div class="card modern-card equal-height-card">
            <div class="card-header d-flex align-items-center justify-content-between pb-0">
                <div>
                    <h5 class="card-title m-0">Perolehan Proyek 12 Bulan Terakhir</h5>
                    <small class="text-muted">Proyek Didapat, Aktif, dan Selesai</small>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        12 Bulan
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="javascript:void(0);">6 Bulan</a>
                        <a class="dropdown-item" href="javascript:void(0);">12 Bulan</a>
                        <a class="dropdown-item" href="javascript:void(0);">Tahun Ini</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <div id="projectAcquisitionChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Employee Activity & Top Performers -->
<div class="row">
    <!-- Employee Activity - Task Completion by Deadline -->
    <div class="col-lg-6 mb-4">
        <div class="card modern-card equal-height-card">
            <div class="card-header d-flex align-items-center justify-content-between pb-0">
                <div>
                    <h5 class="card-title m-0">Aktivitas Penyelesaian Task Karyawan</h5>
                    <small class="text-muted">Tracking deadline performance</small>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        7 Hari Terakhir
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="javascript:void(0);">7 Hari Terakhir</a>
                        <a class="dropdown-item" href="javascript:void(0);">30 Hari Terakhir</a>
                        <a class="dropdown-item" href="javascript:void(0);">3 Bulan Terakhir</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <div id="employeeDeadlineChart"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Performers -->
    <div class="col-lg-6 mb-4">
        <div class="card modern-card equal-height-card">
            <div class="card-header">
                <h5 class="card-title m-0">Performa Karyawan Terbaik</h5>
                <small class="text-muted">Top 5 karyawan tepat waktu/sebelum deadline</small>
            </div>
            <div class="card-body">
                <div class="performers-list">
                    <!-- Rank 1 -->
                    <div class="performer-item">
                        <div class="d-flex align-items-center">
                            <div class="performer-rank rank-1 me-3">
                                🏆
                            </div>
                            <div class="avatar flex-shrink-0 me-3">
                                <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Avatar" class="rounded-circle" width="40" />
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Ahmad Rizki</h6>
                                <small class="text-muted">Full Stack Developer</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-info">28 Task</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rank 2 -->
                    <div class="performer-item">
                        <div class="d-flex align-items-center">
                            <div class="performer-rank rank-2 me-3">
                                #2
                            </div>
                            <div class="avatar flex-shrink-0 me-3">
                                <img src="{{ asset('assets/img/avatars/5.png') }}" alt="Avatar" class="rounded-circle" width="40" />
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Siti Nurhaliza</h6>
                                <small class="text-muted">UI/UX Designer</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-primary">24 Task</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rank 3 -->
                    <div class="performer-item">
                        <div class="d-flex align-items-center">
                            <div class="performer-rank rank-3 me-3">
                                #3
                            </div>
                            <div class="avatar flex-shrink-0 me-3">
                                <img src="{{ asset('assets/img/avatars/5.png') }}" alt="Avatar" class="rounded-circle" width="40" />
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Budi Santoso</h6>
                                <small class="text-muted">Backend Developer</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">22 Task</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rank 4 -->
                    <div class="performer-item">
                        <div class="d-flex align-items-center">
                            <div class="performer-rank rank-4 me-3">
                                #4
                            </div>
                            <div class="avatar flex-shrink-0 me-3">
                                <img src="{{ asset('assets/img/avatars/5.png') }}" alt="Avatar" class="rounded-circle" width="40" />
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Dewi Lestari</h6>
                                <small class="text-muted">QA Engineer</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-warning">18 Task</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rank 5 -->
                    <div class="performer-item">
                        <div class="d-flex align-items-center">
                            <div class="performer-rank rank-5 me-3">
                                #5
                            </div>
                            <div class="avatar flex-shrink-0 me-3">
                                <img src="{{ asset('assets/img/avatars/5.png') }}" alt="Avatar" class="rounded-circle" width="40" />
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Rudi Hartono</h6>
                                <small class="text-muted">Project Manager</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-info">16 Task</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="col-12">
                <div class="card modern-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title m-0">Daftar Karyawan</h5>
                    <small class="text-muted">Tim berdasarkan role dan spesialisasi</small>
                </div>
                <div class="text-end">
                    <h1 class="mb-0 text-primary">29</h1>
                    <small class="text-muted">Total Karyawan</small>
                </div>
            </div>
            <div class="card-body">
                <!-- Web Developer Row -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="role-item">
                            <div class="d-flex align-items-center">
                                <div class="role-icon bg-label-primary me-3">
                                    <i class="bx bx-code-alt text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Web Developer</h6>
                                    <small class="text-muted">Frontend & Backend</small>
                                </div>
                                <div class="text-end">
                                    <h5 class="mb-0 text-primary">8</h5>
                                    <small class="text-muted">Orang</small>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-primary" style="width: 32%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="role-item">
                            <div class="d-flex align-items-center">
                                <div class="role-icon bg-label-success me-3">
                                    <i class="bx bx-palette text-success"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">UI/UX Designer</h6>
                                    <small class="text-muted">Design System</small>
                                </div>
                                <div class="text-end">
                                    <h5 class="mb-0 text-success">5</h5>
                                    <small class="text-muted">Orang</small>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 20%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="role-item">
                            <div class="d-flex align-items-center">
                                <div class="role-icon bg-label-warning me-3">
                                    <i class="bx bx-line-chart text-warning"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Digital Marketing</h6>
                                    <small class="text-muted">SEO & Ads</small>
                                </div>
                                <div class="text-end">
                                    <h5 class="mb-0 text-warning">3</h5>
                                    <small class="text-muted">Orang</small>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-warning" style="width: 12%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Second Row -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="role-item">
                            <div class="d-flex align-items-center">
                                <div class="role-icon bg-label-info me-3">
                                    <i class="bx bx-camera text-info"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Content Creator</h6>
                                    <small class="text-muted">Video & Artikel</small>
                                </div>
                                <div class="text-end">
                                    <h5 class="mb-0 text-info">6</h5>
                                    <small class="text-muted">Orang</small>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-info" style="width: 24%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="role-item">
                            <div class="d-flex align-items-center">
                                <div class="role-icon bg-label-danger me-3">
                                    <i class="bx bxl-instagram text-danger"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Social Media</h6>
                                    <small class="text-muted">Community Manager</small>
                                </div>
                                <div class="text-end">
                                    <h5 class="mb-0 text-danger">4</h5>
                                    <small class="text-muted">Orang</small>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-danger" style="width: 16%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="role-item">
                            <div class="d-flex align-items-center">
                                <div class="role-icon bg-label-secondary me-3">
                                    <i class="bx bx-bug text-secondary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">QA Engineer</h6>
                                    <small class="text-muted">Testing & Quality</small>
                                </div>
                                <div class="text-end">
                                    <h5 class="mb-0 text-secondary">3</h5>
                                    <small class="text-muted">Orang</small>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-secondary" style="width: 12%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Third Row - Additional Roles -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="role-item">
                            <div class="d-flex align-items-center">
                                <div class="role-icon bg-label-primary me-3">
                                    <i class="bx bx-server text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">DevOps</h6>
                                    <small class="text-muted">Infrastructure</small>
                                </div>
                                <div class="text-end">
                                    <h5 class="mb-0 text-primary">2</h5>
                                    <small class="text-muted">Orang</small>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-primary" style="width: 8%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="role-item">
                            <div class="d-flex align-items-center">
                                <div class="role-icon bg-label-success me-3">
                                    <i class="bx bx-data text-success"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Data Analyst</h6>
                                    <small class="text-muted">Analytics & BI</small>
                                </div>
                                <div class="text-end">
                                    <h5 class="mb-0 text-success">3</h5>
                                    <small class="text-muted">Orang</small>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 12%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="role-item">
                            <div class="d-flex align-items-center">
                                <div class="role-icon bg-label-warning me-3">
                                    <i class="bx bx-briefcase-alt text-warning"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Project Manager</h6>
                                    <small class="text-muted">Coordination</small>
                                </div>
                                <div class="text-end">
                                    <h5 class="mb-0 text-warning">3</h5>
                                    <small class="text-muted">Orang</small>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-warning" style="width: 12%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Summary Stats -->
                <div class="row mt-4 pt-3 border-top">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Review Task List -->
<div class="modal fade" id="reviewTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-bell text-warning me-2"></i>
                    Task yang Perlu Ditinjau
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Berikut adalah 10 task yang dipindahkan ke status review dan memerlukan persetujuan Anda:</p>
                
                <!-- Task 1 -->
                <div class="task-modal-item">
                    <div class="d-flex">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle bg-label-success">
                                <i class="bx bx-check"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Task Selesai</h6>
                            <small class="text-muted d-block mb-1">
                                <strong>Ahmad Rizki</strong> menyelesaikan task <strong>"Design Database"</strong> dari proyek <strong>E-Commerce Redesign</strong>
                            </small>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">
                                    <i class="bx bx-time"></i> 5 menit yang lalu
                                </small>
                                <div>
                                    <button class="btn btn-sm btn-success me-1">
                                        <i class="bx bx-check"></i> Approve
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bx bx-x"></i> Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Task 2 -->
                <div class="task-modal-item" style="border-left-color: #ffab00;">
                    <div class="d-flex">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle bg-label-warning">
                                <i class="bx bx-right-arrow-alt"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Task Dipindahkan</h6>
                            <small class="text-muted d-block mb-1">
                                <strong>Siti Nurhaliza</strong> memindahkan <strong>"API Integration"</strong> dari To Do ke In Progress di proyek <strong>Mobile App Development</strong>
                            </small>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">
                                    <i class="bx bx-time"></i> 1 jam yang lalu
                                </small>
                                <div>
                                    <button class="btn btn-sm btn-success me-1">
                                        <i class="bx bx-check"></i> Approve
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bx bx-x"></i> Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
               
                
                <!-- Continuing with remaining tasks... -->
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary">
                    <i class="bx bx-check-double me-1"></i> Approve Semua
                </button>
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
    
    // 1. Project Acquisition Line Chart (12 Months)
    const projectAcquisitionChart = document.querySelector('#projectAcquisitionChart');
    if (projectAcquisitionChart) {
        const config = {
            chart: {
                type: 'line',
                height: 300,
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            series: [{
                name: 'Proyek Didapat',
                data: [3, 5, 4, 6, 7, 5, 8, 6, 9, 7, 10, 8]
            }, {
                name: 'Proyek Aktif',
                data: [2, 3, 3, 4, 5, 4, 5, 4, 6, 5, 7, 5]
            }, {
                name: 'Proyek Selesai',
                data: [1, 2, 1, 2, 2, 1, 3, 2, 3, 2, 3, 3]
            }],
            colors: [colors.primary, colors.warning, colors.success],
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
                strokeDashArray: 4
            },
            xaxis: {
                categories: ['Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des', 'Jan'],
                labels: {
                    style: {
                        colors: '#8592a3',
                        fontSize: '13px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Jumlah Proyek',
                    style: {
                        color: '#8592a3',
                        fontSize: '13px',
                        fontWeight: 500
                    }
                },
                labels: {
                    style: {
                        colors: '#8592a3',
                        fontSize: '13px'
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                fontSize: '13px',
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
                        return val + " proyek"
                    }
                }
            }
        };
        new ApexCharts(projectAcquisitionChart, config).render();
    }
    
    // 2. Task Status Pie Chart (Modern with Percentage)
    let taskStatusChartInstance;
    const taskStatusChart = document.querySelector('#taskStatusChart');
    
    function renderTaskChart(data) {
        if (taskStatusChartInstance) {
            taskStatusChartInstance.destroy();
        }
        
        if (taskStatusChart) {
            const config = {
                chart: {
                    type: 'donut',
                    height: 250,
                    toolbar: { show: false }
                },
                series: data.series,
                labels: ['To Do', 'In Progress', 'Review', 'Done'],
                colors: [colors.secondary, colors.warning, colors.info, colors.success],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '14px',
                                    fontWeight: 600,
                                    offsetY: -5
                                },
                                value: {
                                    show: true,
                                    fontSize: '24px',
                                    fontWeight: 700,
                                    offsetY: 5,
                                    formatter: function (val) {
                                        return val
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Total Tasks',
                                    fontSize: '13px',
                                    fontWeight: 500,
                                    color: '#8592a3',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                }
                            }
                        }
                    }
                },
                legend: { show: false },
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex]
                    },
                    style: {
                        fontSize: '12px',
                        fontWeight: 600,
                        colors: ['#fff']
                    },
                    dropShadow: {
                        enabled: false
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            const total = data.series.reduce((a, b) => a + b, 0);
                            const percentage = ((val / total) * 100).toFixed(1);
                            return val + " tasks (" + percentage + "%)";
                        }
                    }
                }
            };
            taskStatusChartInstance = new ApexCharts(taskStatusChart, config);
            taskStatusChartInstance.render();
        }
    }
    
    // Initial render with all projects data
    const allProjectsData = { series: [45, 32, 18, 67] };
    renderTaskChart(allProjectsData);
    
    // Function to update chart based on project filter
    window.updateTaskChart = function() {
        const filterValue = document.getElementById('projectFilter').value;
        let data;
        
        switch(filterValue) {
            case 'project1':
                data = { series: [12, 8, 5, 15] };
                break;
            case 'project2':
                data = { series: [15, 10, 6, 20] };
                break;
            case 'project3':
                data = { series: [8, 7, 4, 12] };
                break;
            case 'project4':
                data = { series: [10, 7, 3, 20] };
                break;
            default:
                data = allProjectsData;
        }
        
        renderTaskChart(data);
    };
    
    // 3. Employee Deadline Performance Chart
    const employeeDeadlineChart = document.querySelector('#employeeDeadlineChart');
    if (employeeDeadlineChart) {
        const config = {
            chart: {
                type: 'line',
                height: 280,
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            series: [{
                name: 'Tepat Waktu',
                data: [8, 12, 10, 14, 12, 15, 13]
            }, {
                name: 'Sebelum Deadline',
                data: [5, 7, 8, 6, 9, 8, 10]
            }, {
                name: 'Melewati Deadline',
                data: [2, 1, 2, 2, 1, 2, 1]
            }],
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
                categories: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                labels: {
                    style: {
                        colors: '#8592a3',
                        fontSize: '13px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Jumlah Task',
                    style: {
                        color: '#8592a3',
                        fontSize: '13px',
                        fontWeight: 500
                    }
                },
                labels: {
                    style: {
                        colors: '#8592a3',
                        fontSize: '13px'
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                fontSize: '13px',
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
        new ApexCharts(employeeDeadlineChart, config).render();
    }
})();
</script>
@endpush