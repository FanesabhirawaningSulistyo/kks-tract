@extends('layouts.master')
@section('title', 'Dashboard Klien - Project Management')
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
    .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: flex-start;
        justify-content: start;
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
    
    /* Feature Timeline Item */
    .feature-timeline-item {
        position: relative;
        padding-left: 30px;
        padding-bottom: 20px;
        border-left: 2px solid #e7e7e7;
    }
    
    .feature-timeline-item:last-child {
        border-left: 2px solid transparent;
    }
    
    .feature-timeline-item::before {
        content: '';
        position: absolute;
        left: -6px;
        top: 0;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #696cff;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #696cff;
    }
    
    .feature-timeline-item.completed::before {
        background: #71dd37;
        box-shadow: 0 0 0 2px #71dd37;
    }
    
    .feature-timeline-item.in-progress::before {
        background: #ffab00;
        box-shadow: 0 0 0 2px #ffab00;
    }
    
    .feature-timeline-item.pending::before {
        background: #8592a3;
        box-shadow: 0 0 0 2px #8592a3;
    }
    
    /* Project Info Card */
    .project-info-item {
        padding: 12px;
        border-radius: 8px;
        background: #f8f9fa;
        margin-bottom: 10px;
        transition: all 0.3s;
    }
    
    .project-info-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }
    
    /* Progress Ring */
    .progress-ring {
        width: 120px;
        height: 120px;
    }
    
    /* Milestone Badge */
    .milestone-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-right: 8px;
        margin-bottom: 8px;
    }
    
    /* Timeline Scroll Container */
    .timeline-scroll-container {
        scrollbar-width: thin;
        scrollbar-color: #696cff #f0f0f0;
    }
    
    .timeline-scroll-container::-webkit-scrollbar {
        width: 8px;
    }
    
    .timeline-scroll-container::-webkit-scrollbar-track {
        background: #f0f0f0;
        border-radius: 10px;
    }
    
    .timeline-scroll-container::-webkit-scrollbar-thumb {
        background: #696cff;
        border-radius: 10px;
    }
    
    .timeline-scroll-container::-webkit-scrollbar-thumb:hover {
        background: #5a5fc7;
    }
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
                        <h5 class="card-title text-primary mb-3">Selamat Datang Kembali, <b class="text-warning">PT Digital Inovasi Indonesia</b>! 🎯</h5>
                         <div class="alert alert-info mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-bell-ring me-2"></i>
                                <div>
                            Anda memiliki <span class="fw-bold text-primary">5 proyek aktif</span> dan 
                            <span class="fw-bold text-success">10 fitur yang telah diselesaikan</span> hari ini.
                        </div>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-primary me-2" onclick="location.href=''">
                            <i class="bx bx-folder-open me-1"></i> Lihat Semua Proyek
                        </button>
                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#taskUpdateModal">
                            <i class="bx bx-bell me-1"></i> Pembaruan Hari Ini (5)
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
    <!-- Total Projects Ordered -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card project-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <span class="fw-semibold d-block mb-1 text-muted">Proyek Dipesan</span>
                        <h3 class="card-title mb-2">12</h3>
                        <small class="text-primary fw-semibold">
                            <i class="bx bx-briefcase"></i> Total Pesanan
                        </small>
                    </div>
                    <div class="stat-icon bg-label-primary">
                        <i class="bx bx-cart text-primary" style="font-size: 1.75rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Completed Tasks -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card project-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <span class="fw-semibold d-block mb-1 text-muted">Fitur Selesai</span>
                        <h3 class="card-title mb-2">87</h3>
                        <small class="text-success fw-semibold">
                            <i class="bx bx-check-circle"></i> Telah Diselesaikan
                        </small>
                    </div>
                    <div class="stat-icon bg-label-success">
                        <i class="bx bx-check-shield text-success" style="font-size: 1.75rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- In Progress Tasks -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card project-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <span class="fw-semibold d-block mb-1 text-muted">Fitur Proses</span>
                        <h3 class="card-title mb-2">24</h3>
                        <small class="text-warning fw-semibold">
                            <i class="bx bx-time"></i> Sedang Dikerjakan
                        </small>
                    </div>
                    <div class="stat-icon bg-label-warning">
                        <i class="bx bx-loader-alt text-warning" style="font-size: 1.75rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- To Do Tasks -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card project-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <span class="fw-semibold d-block mb-1 text-muted">Fitur Menunggu</span>
                        <h3 class="card-title mb-2">15</h3>
                        <small class="text-secondary fw-semibold">
                            <i class="bx bx-list-ul"></i> Belum Dimulai
                        </small>
                    </div>
                    <div class="stat-icon bg-label-secondary">
                        <i class="bx bx-hourglass text-secondary" style="font-size: 1.75rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Feature Status & Timeline -->
<div class="row">
    <!-- Feature Status Pie Chart with Project Filter - LEFT SIDE -->
    <div class="col-lg-6 mb-4">
        <div class="card modern-card equal-height-card">
            <div class="card-header">
                <h5 class="card-title m-0">Status Pengerjaan Fitur</h5>
                <small class="text-muted">Progress fitur per proyek</small>
            </div>
            <div class="card-body">
                <!-- Filter Dropdown -->
                <div class="mb-3">
                    <label class="form-label small mb-1">Pilih Proyek:</label>
                    <select class="form-select form-select-sm custom-select" id="projectFilterClient" onchange="updateFeatureChart()">
                        <option value="all">Semua Proyek (126 Fitur)</option>
                        <option value="project1">E-Commerce Website (40 Fitur)</option>
                        <option value="project2">Mobile App Android (35 Fitur)</option>
                        <option value="project3">Dashboard Analytics (28 Fitur)</option>
                        <option value="project4">CRM System (23 Fitur)</option>
                    </select>
                </div>
                
                <div class="row align-items-center">
                    <!-- Pie Chart - Right Side -->
                    <div class="col-md-6 order-md-2">
                        <div id="featureStatusChart"></div>
                    </div>
                    
                    <!-- Legend - Left Side -->
                    <div class="col-md-6 order-md-1">
                        <div class="feature-status-legend">
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background: #e6ffe6;">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success me-2" style="width: 14px; height: 14px;"></span>
                                    <span class="fw-semibold">Selesai</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold" id="legend-completed">87</div>
                                    <small class="text-muted" id="legend-completed-percent">69%</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background: #fff5e6;">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning me-2" style="width: 14px; height: 14px;"></span>
                                    <span class="fw-semibold">Proses</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold" id="legend-progress">24</div>
                                    <small class="text-muted" id="legend-progress-percent">19%</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: #f8f9fa;">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary me-2" style="width: 14px; height: 14px;"></span>
                                    <span class="fw-semibold">Belum Proses</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold" id="legend-pending">15</div>
                                    <small class="text-muted" id="legend-pending-percent">12%</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Total Features -->
                        <div class="mt-4 p-3 rounded" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="text-white">
                                <small class="d-block mb-1">Total Fitur</small>
                                <h3 class="mb-0 text-white" id="total-features">126</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Feature Development Timeline - RIGHT SIDE -->
    <div class="col-lg-6 mb-4">
        <div class="card modern-card equal-height-card">
            <div class="card-header">
                <h5 class="card-title m-0">Timeline Pengerjaan Fitur</h5>
                <small class="text-muted">Durasi pengerjaan per fitur</small>
            </div>
            <div class="card-body">
                <!-- Filter Dropdown for Timeline -->
                <div class="mb-3">
                    <label class="form-label small mb-1">Pilih Proyek:</label>
                    <select class="form-select form-select-sm custom-select" id="timelineProjectFilter" onchange="updateTimelineChart()">
                        <option value="project1">E-Commerce Website</option>
                        <option value="project2">Mobile App Android</option>
                        <option value="project3">Dashboard Analytics</option>
                        <option value="project4">CRM System</option>
                    </select>
                </div>
                
                <!-- Scrollable Chart Container -->
                <div class="timeline-scroll-container" style="max-height: 400px; overflow-y: auto; overflow-x: hidden;">
                    <div id="featureTimelineChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Project Details & Milestones -->
<div class="row">
    <!-- Project Progress Details -->
    <div class="col-lg-12 mb-4">
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title m-0">Detail Progress Proyek</h5>
                <small class="text-muted">Status terkini proyek yang sedang berjalan</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Proyek</th>
                                <th>Progress</th>
                                <th>Fitur</th>
                                <th>Target Selesai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <i class="bx bx-cart"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">E-Commerce Website</h6>
                                            <small class="text-muted">Web Development</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 85%"></div>
                                    </div>
                                    <small class="text-muted">85%</small>
                                </td>
                                <td>
                                    <span class="badge bg-label-warning">34/40</span>
                                </td>
                                <td>
                                    <small class="text-muted">15 Feb 2026</small>
                                </td>
                                <td>
                                    <span class="badge bg-label-warning">In Progress</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                <i class="bx bx-mobile"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Mobile App Android</h6>
                                            <small class="text-muted">Mobile Development</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 60%"></div>
                                    </div>
                                    <small class="text-muted">60%</small>
                                </td>
                                <td>
                                    <span class="badge bg-label-warning">21/35</span>
                                </td>
                                <td>
                                    <small class="text-muted">28 Feb 2026</small>
                                </td>
                                <td>
                                    <span class="badge bg-label-warning">In Progress</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-success">
                                                <i class="bx bx-line-chart"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Dashboard Analytics</h6>
                                            <small class="text-muted">Data Visualization</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 75%"></div>
                                    </div>
                                    <small class="text-muted">75%</small>
                                </td>
                                <td>
                                    <span class="badge bg-label-warning">21/28</span>
                                </td>
                                <td>
                                    <small class="text-muted">10 Mar 2026</small>
                                </td>
                                <td>
                                    <span class="badge bg-label-warning">In Progress</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-warning">
                                                <i class="bx bx-briefcase"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">CRM System</h6>
                                            <small class="text-muted">Enterprise Software</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 48%"></div>
                                    </div>
                                    <small class="text-muted">48%</small>
                                </td>
                                <td>
                                    <span class="badge bg-label-warning">11/23</span>
                                </td>
                                <td>
                                    <small class="text-muted">20 Mar 2026</small>
                                </td>
                                <td>
                                    <span class="badge bg-label-warning">In Progress</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-secondary">
                                                <i class="bx bx-cube"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">IoT Dashboard</h6>
                                            <small class="text-muted">IoT Integration</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 15%"></div>
                                    </div>
                                    <small class="text-muted">15%</small>
                                </td>
                                <td>
                                    <span class="badge bg-label-warning">3/20</span>
                                </td>
                                <td>
                                    <small class="text-muted">05 Apr 2026</small>
                                </td>
                                <td>
                                    <span class="badge bg-label-warning">In Progress</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal: Task Update Notifications -->
<div class="modal fade" id="taskUpdateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-bell text-warning me-2"></i>
                    Pembaruan Task Hari Ini
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Berikut adalah pembaruan fitur yang terjadi hari ini:</p>
                
                <!-- Task Update 1 - Completed -->
                <div class="task-modal-item" style="border-left: 3px solid #71dd37; background: #f0fdf4; margin-bottom: 15px; padding: 15px; border-radius: 8px;">
                    <div class="d-flex">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle bg-label-success">
                                <i class="bx bx-check"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                <span class="badge bg-success me-2">Selesai</span>
                                Fitur Kelola Produk
                            </h6>
                            <small class="text-muted d-block mb-2">
                                <strong>E-Commerce Website</strong> • Diselesaikan oleh <strong>Ahmad Rizki</strong>
                            </small>
                            <p class="mb-2 small">Fitur kelola produk telah selesai dikembangkan dengan fungsi CRUD lengkap, upload gambar, dan manajemen kategori.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bx bx-time"></i> 2 jam yang lalu
                                </small>
                                <button class="btn btn-sm btn-outline-success">
                                    <i class="bx bx-show"></i> Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Task Update 2 - In Progress -->
                <div class="task-modal-item" style="border-left: 3px solid #ffab00; background: #fff8e1; margin-bottom: 15px; padding: 15px; border-radius: 8px;">
                    <div class="d-flex">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle bg-label-warning">
                                <i class="bx bx-loader-alt"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                <span class="badge bg-warning me-2">Proses</span>
                                Fitur Kelola Kategori
                            </h6>
                            <small class="text-muted d-block mb-2">
                                <strong>E-Commerce Website</strong> • Dikerjakan oleh <strong>Siti Nurhaliza</strong>
                            </small>
                            <p class="mb-2 small">Sedang dalam proses pengembangan sistem kategori produk dengan nested categories dan drag & drop reordering.</p>
                            <div class="progress mb-2" style="height: 6px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 65%"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bx bx-time"></i> Progress: 65% • Estimasi selesai: 1 hari
                                </small>
                                <button class="btn btn-sm btn-outline-warning">
                                    <i class="bx bx-show"></i> Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Task Update 3 - Started -->
                <div class="task-modal-item" style="border-left: 3px solid #03c3ec; background: #e6f7ff; margin-bottom: 15px; padding: 15px; border-radius: 8px;">
                    <div class="d-flex">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle bg-label-info">
                                <i class="bx bx-play"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                <span class="badge bg-info me-2">Dimulai</span>
                                Fitur Shopping Cart
                            </h6>
                            <small class="text-muted d-block mb-2">
                                <strong>E-Commerce Website</strong> • Dikerjakan oleh <strong>Budi Santoso</strong>
                            </small>
                            <p class="mb-2 small">Baru saja memulai pengembangan fitur keranjang belanja dengan session management dan real-time update.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bx bx-time"></i> 5 jam yang lalu • Target: 3 hari
                                </small>
                                <button class="btn btn-sm btn-outline-info">
                                    <i class="bx bx-show"></i> Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Task Update 4 - Completed -->
                <div class="task-modal-item" style="border-left: 3px solid #71dd37; background: #f0fdf4; margin-bottom: 15px; padding: 15px; border-radius: 8px;">
                    <div class="d-flex">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle bg-label-success">
                                <i class="bx bx-check"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                <span class="badge bg-success me-2">Selesai</span>
                                Fitur Push Notification
                            </h6>
                            <small class="text-muted d-block mb-2">
                                <strong>Mobile App Android</strong> • Diselesaikan oleh <strong>Dewi Lestari</strong>
                            </small>
                            <p class="mb-2 small">Implementasi FCM untuk push notification telah selesai dengan support untuk scheduled notifications dan rich media.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bx bx-time"></i> 4 jam yang lalu
                                </small>
                                <button class="btn btn-sm btn-outline-success">
                                    <i class="bx bx-show"></i> Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Task Update 5 - Testing -->
                <div class="task-modal-item" style="border-left: 3px solid #696cff; background: #f0f0ff; margin-bottom: 15px; padding: 15px; border-radius: 8px;">
                    <div class="d-flex">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle bg-label-primary">
                                <i class="bx bx-test-tube"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                <span class="badge bg-primary me-2">Testing</span>
                                Fitur Data Export
                            </h6>
                            <small class="text-muted d-block mb-2">
                                <strong>Dashboard Analytics</strong> • Ditest oleh <strong>Rudi Hartono</strong>
                            </small>
                            <p class="mb-2 small">Fitur export data ke Excel, CSV, dan PDF sedang dalam tahap QA testing untuk memastikan format output sesuai.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bx bx-time"></i> 3 jam yang lalu • QA Progress: 80%
                                </small>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-show"></i> Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary">
                    <i class="bx bx-check-double me-1"></i> Tandai Semua Telah Dibaca
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
    
    // Project Data
    const projectsData = {
        all: {
            name: 'Semua Proyek',
            completed: 87,
            inProgress: 24,
            pending: 15
        },
        project1: {
            name: 'E-Commerce Website',
            completed: 34,
            inProgress: 4,
            pending: 2,
            features: [
                { name: 'Payment Gateway', start: '2 Jan', end: '5 Jan', days: 3, status: 'completed' },
                { name: 'Product Catalog', start: '11 Jan', end: '18 Jan', days: 7, status: 'in-progress' },
                { name: 'Shopping Cart', start: '19 Jan', end: '25 Jan', days: 6, status: 'pending' },
                { name: 'User Reviews', start: '26 Jan', end: '29 Jan', days: 3, status: 'in-progress' },
                { name: 'Wishlist Feature', start: '30 Jan', end: '2 Feb', days: 3, status: 'pending' },
                { name: 'User Authentication', start: '5 Jan', end: '8 Jan', days: 3, status: 'completed' },
                { name: 'Product Search', start: '9 Jan', end: '13 Jan', days: 4, status: 'completed' },
                { name: 'Filter & Sort', start: '14 Jan', end: '17 Jan', days: 3, status: 'completed' },
                { name: 'Checkout Process', start: '20 Jan', end: '26 Jan', days: 6, status: 'in-progress' },
                { name: 'Order Tracking', start: '27 Jan', end: '1 Feb', days: 5, status: 'in-progress' },
                { name: 'Shipping Integration', start: '2 Feb', end: '6 Feb', days: 4, status: 'pending' },
                { name: 'Inventory Management', start: '7 Feb', end: '12 Feb', days: 5, status: 'pending' },
                { name: 'Product Images Upload', start: '13 Feb', end: '15 Feb', days: 2, status: 'pending' },
                { name: 'Category Management', start: '16 Feb', end: '20 Feb', days: 4, status: 'pending' },
                { name: 'Discount System', start: '21 Feb', end: '25 Feb', days: 4, status: 'pending' },
                { name: 'Coupon Management', start: '26 Feb', end: '28 Feb', days: 2, status: 'pending' },
                { name: 'Email Notifications', start: '1 Mar', end: '4 Mar', days: 3, status: 'pending' },
                { name: 'SMS Notifications', start: '5 Mar', end: '7 Mar', days: 2, status: 'pending' },
                { name: 'Customer Dashboard', start: '8 Mar', end: '13 Mar', days: 5, status: 'pending' },
                { name: 'Admin Panel', start: '14 Mar', end: '20 Mar', days: 6, status: 'pending' },
                { name: 'Sales Reports', start: '21 Mar', end: '25 Mar', days: 4, status: 'pending' },
                { name: 'Analytics Dashboard', start: '26 Mar', end: '30 Mar', days: 4, status: 'pending' },
                { name: 'Multi-language', start: '31 Mar', end: '4 Apr', days: 4, status: 'pending' },
                { name: 'Multi-currency', start: '5 Apr', end: '8 Apr', days: 3, status: 'pending' },
                { name: 'SEO Optimization', start: '9 Apr', end: '12 Apr', days: 3, status: 'pending' },
                { name: 'Social Media Share', start: '13 Apr', end: '15 Apr', days: 2, status: 'pending' },
                { name: 'Live Chat Support', start: '16 Apr', end: '20 Apr', days: 4, status: 'pending' },
                { name: 'FAQ Section', start: '21 Apr', end: '23 Apr', days: 2, status: 'pending' },
                { name: 'Blog Integration', start: '24 Apr', end: '28 Apr', days: 4, status: 'pending' },
                { name: 'Newsletter System', start: '29 Apr', end: '2 May', days: 3, status: 'pending' },
                { name: 'Product Comparison', start: '3 May', end: '6 May', days: 3, status: 'pending' },
                { name: 'Recently Viewed', start: '7 May', end: '9 May', days: 2, status: 'pending' },
                { name: 'Related Products', start: '10 May', end: '13 May', days: 3, status: 'pending' },
                { name: 'Quick View Modal', start: '14 May', end: '16 May', days: 2, status: 'pending' },
                { name: 'Size Guide', start: '17 May', end: '19 May', days: 2, status: 'pending' },
                { name: 'Stock Alerts', start: '20 May', end: '23 May', days: 3, status: 'pending' },
                { name: 'Bulk Import Products', start: '24 May', end: '28 May', days: 4, status: 'pending' },
                { name: 'PDF Invoice', start: '29 May', end: '31 May', days: 2, status: 'pending' },
                { name: 'Return/Refund System', start: '1 Jun', end: '5 Jun', days: 4, status: 'pending' },
                { name: 'Security Enhancements', start: '6 Jun', end: '10 Jun', days: 4, status: 'pending' }
            ]
        },
        project2: {
            name: 'Mobile App Android',
            completed: 21,
            inProgress: 8,
            pending: 6,
            features: [
                { name: 'User Authentication', start: '6 Jan', end: '10 Jan', days: 4, status: 'completed' },
                { name: 'Push Notifications', start: '12 Jan', end: '16 Jan', days: 4, status: 'completed' },
                { name: 'Profile Management', start: '17 Jan', end: '22 Jan', days: 5, status: 'in-progress' },
                { name: 'Offline Mode', start: '23 Jan', end: '29 Jan', days: 6, status: 'in-progress' },
                { name: 'Social Sharing', start: '30 Jan', end: '3 Feb', days: 4, status: 'pending' }
            ]
        },
        project3: {
            name: 'Dashboard Analytics',
            completed: 21,
            inProgress: 5,
            pending: 2,
            features: [
                { name: 'Data Visualization', start: '15 Jan', end: '22 Jan', days: 7, status: 'in-progress' },
                { name: 'Real-time Charts', start: '8 Jan', end: '14 Jan', days: 6, status: 'completed' },
                { name: 'Export Reports', start: '23 Jan', end: '27 Jan', days: 4, status: 'in-progress' },
                { name: 'Custom Widgets', start: '28 Jan', end: '3 Feb', days: 6, status: 'pending' },
                { name: 'API Integration', start: '4 Jan', end: '7 Jan', days: 3, status: 'completed' }
            ]
        },
        project4: {
            name: 'CRM System',
            completed: 11,
            inProgress: 7,
            pending: 5,
            features: [
                { name: 'Contact Management', start: '5 Jan', end: '10 Jan', days: 5, status: 'completed' },
                { name: 'Lead Tracking', start: '11 Jan', end: '18 Jan', days: 7, status: 'in-progress' },
                { name: 'Email Templates', start: '19 Jan', end: '24 Jan', days: 5, status: 'in-progress' },
                { name: 'Task Automation', start: '25 Jan', end: '31 Jan', days: 6, status: 'in-progress' },
                { name: 'Sales Pipeline', start: '1 Feb', end: '7 Feb', days: 6, status: 'pending' }
            ]
        }
    };
    
    // 1. Feature Status Pie Chart
    let featureStatusChartInstance;
    const featureStatusChart = document.querySelector('#featureStatusChart');
    
    function renderFeatureChart(data) {
        if (featureStatusChartInstance) {
            featureStatusChartInstance.destroy();
        }
        
        if (featureStatusChart) {
            const total = data.completed + data.inProgress + data.pending;
            const completedPercent = ((data.completed / total) * 100).toFixed(0);
            const progressPercent = ((data.inProgress / total) * 100).toFixed(0);
            const pendingPercent = ((data.pending / total) * 100).toFixed(0);
            
            // Update legend
            document.getElementById('legend-completed').textContent = data.completed;
            document.getElementById('legend-completed-percent').textContent = completedPercent + '%';
            document.getElementById('legend-progress').textContent = data.inProgress;
            document.getElementById('legend-progress-percent').textContent = progressPercent + '%';
            document.getElementById('legend-pending').textContent = data.pending;
            document.getElementById('legend-pending-percent').textContent = pendingPercent + '%';
            document.getElementById('total-features').textContent = total;
            
            const config = {
                chart: {
                    type: 'donut',
                    height: 250,
                    toolbar: { show: false }
                },
                series: [data.completed, data.inProgress, data.pending],
                labels: ['Selesai', 'Proses', 'Belum Proses'],
                colors: [colors.success, colors.warning, colors.secondary],
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
                                    label: 'Total Fitur',
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
                            const total = data.completed + data.inProgress + data.pending;
                            const percentage = ((val / total) * 100).toFixed(1);
                            return val + " fitur (" + percentage + "%)";
                        }
                    }
                }
            };
            featureStatusChartInstance = new ApexCharts(featureStatusChart, config);
            featureStatusChartInstance.render();
        }
    }
    
    // Initial render
    renderFeatureChart(projectsData.project1);
    
    // 2. Feature Timeline Chart - Horizontal Bar showing duration
    let featureTimelineChartInstance;
    const featureTimelineChart = document.querySelector('#featureTimelineChart');
    
    function renderTimelineChart(projectData) {
        if (featureTimelineChartInstance) {
            featureTimelineChartInstance.destroy();
        }
        
        if (featureTimelineChart && projectData.features) {
            const features = projectData.features;
            const categories = features.map(f => f.name);
            const durations = features.map(f => f.days);
            const statusColors = features.map(f => {
                if (f.status === 'completed') return colors.success;
                if (f.status === 'in-progress') return colors.warning;
                return colors.secondary;
            });
            
            // Dynamic height based on number of features (50px per feature)
            const chartHeight = Math.max(300, features.length * 50);
            
            const config = {
                chart: {
                    type: 'bar',
                    height: chartHeight,
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        distributed: true,
                        barHeight: '65%',
                        dataLabels: {
                            position: 'bottom' // Changed from 'top' to 'center'
                        }
                    }
                },
                series: [{
                    name: 'Durasi (Hari)',
                    data: durations
                }],
                colors: statusColors,
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opts) {
                        const feature = features[opts.dataPointIndex];
                        return feature.start + ' - ' + feature.end + ' (' + val + ' hari)';
                    },
                    offsetX: 0,
                    textAnchor: 'start', // Center alignment
                    style: {
                        fontSize: '11px',
                        fontWeight: 600,
                        colors: ['#fff']
                    },
                    dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.3
                    }
                },
                grid: {
                    borderColor: '#f0f0f0',
                    strokeDashArray: 4,
                    padding: {
                        left: 10,
                        right: 30
                    }
                },
                xaxis: {
                    categories: categories,
                    title: {
                        text: 'Durasi Pengerjaan (Hari)',
                        style: {
                            color: '#8592a3',
                            fontSize: '13px',
                            fontWeight: 500
                        }
                    },
                    labels: {
                        style: {
                            colors: '#8592a3',
                            fontSize: '12px'
                        }
                    },
                    axisBorder: {
                        show: true,
                        color: '#e7e7e7'
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#8592a3',
                            fontSize: '12px',
                            fontWeight: 500
                        },
                        maxWidth: 180
                    }
                },
                legend: { show: false },
                tooltip: {
                    custom: function({ series, seriesIndex, dataPointIndex, w }) {
                        const feature = features[dataPointIndex];
                        let statusBadge = '';
                        let statusColor = '';
                        
                        if (feature.status === 'completed') {
                            statusBadge = '<span class="badge bg-success">Selesai</span>';
                            statusColor = '#71dd37';
                        } else if (feature.status === 'in-progress') {
                            statusBadge = '<span class="badge bg-warning">Proses</span>';
                            statusColor = '#ffab00';
                        } else {
                            statusBadge = '<span class="badge bg-secondary">Menunggu</span>';
                            statusColor = '#8592a3';
                        }
                        
                        return '<div class="px-3 py-2" style="border-left: 3px solid ' + statusColor + ';">' +
                            '<div class="mb-2"><strong>' + feature.name + '</strong></div>' +
                            '<div class="mb-1">' + statusBadge + '</div>' +
                            '<div class="text-muted small">' +
                            '<i class="bx bx-calendar"></i> ' + feature.start + ' - ' + feature.end +
                            '</div>' +
                            '<div class="text-muted small">' +
                            '<i class="bx bx-time"></i> Durasi: ' + feature.days + ' hari' +
                            '</div>' +
                            '</div>';
                    }
                }
            };
            featureTimelineChartInstance = new ApexCharts(featureTimelineChart, config);
            featureTimelineChartInstance.render();
        }
    }
    
    // Initial timeline render
    renderTimelineChart(projectsData.project1);
    
    // Function to update charts based on project filter
    window.updateFeatureChart = function() {
        const filterValue = document.getElementById('projectFilterClient').value;
        const selectedProject = projectsData[filterValue];
        
        if (selectedProject) {
            renderFeatureChart(selectedProject);
            // Also update timeline if user changed pie chart filter
            const timelineFilter = document.getElementById('timelineProjectFilter').value;
            if (filterValue === timelineFilter) {
                renderTimelineChart(selectedProject);
            }
        }
    };
    
    // Function to update timeline independently
    window.updateTimelineChart = function() {
        const filterValue = document.getElementById('timelineProjectFilter').value;
        const selectedProject = projectsData[filterValue];
        
        if (selectedProject) {
            renderTimelineChart(selectedProject);
        }
    };
    
})();
</script>
@endpush