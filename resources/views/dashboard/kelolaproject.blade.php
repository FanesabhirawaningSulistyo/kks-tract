@extends('layouts.master')

@section('title', 'Kelola Project')

@push('styles')
<style>
    /* Modern Minimalist Variables */
    :root {
        --primary-blue: #4F46E5;
        --primary-light: #EEF2FF;
        --success-green: #10B981;
        --success-light: #D1FAE5;
        --warning-orange: #F59E0B;
        --warning-light: #FEF3C7;
        --danger-red: #EF4444;
        --danger-light: #FEE2E2;
        --purple: #8B5CF6;
        --purple-light: #EDE9FE;
        --info-blue: #3B82F6;
        --info-light: #DBEAFE;
        --gray-50: #F9FAFB;
        --gray-100: #F3F4F6;
        --gray-200: #E5E7EB;
        --gray-300: #D1D5DB;
        --gray-400: #9CA3AF;
        --gray-500: #6B7280;
        --gray-600: #4B5563;
        --gray-700: #374151;
        --gray-800: #1F2937;
        --gray-900: #111827;
    }

    body {
        background: var(--gray-50);
    }

    /* Page Header */
    .page-header {
        background: white;
        border-radius: 16px;
        padding: 28px 32px;
        margin-bottom: 24px;
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .page-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .page-title-section {
        flex: 1;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-title i {
        font-size: 32px;
        color: var(--primary-blue);
    }

    .page-subtitle {
        font-size: 14px;
        color: var(--gray-600);
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    .btn-action {
        padding: 12px 20px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--purple) 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
    }

    .btn-outline-secondary {
        background: white;
        color: var(--gray-700);
        border: 2px solid var(--gray-300);
    }

    .btn-outline-secondary:hover {
        background: var(--gray-50);
        border-color: var(--gray-400);
    }

    /* Project Header Card */
    .project-header-card {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--gray-200);
        margin-bottom: 24px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .project-header-top {
        padding: 24px 32px;
        border-bottom: 1px solid var(--gray-200);
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .project-header-content {
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .project-icon {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--purple) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 28px;
        flex-shrink: 0;
    }

    .project-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 8px;
    }

    .project-desc {
        font-size: 14px;
        color: var(--gray-600);
        margin-bottom: 12px;
        line-height: 1.5;
    }

    .employee-name {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: var(--gray-700);
        font-weight: 500;
    }

    .employee-name i {
        color: var(--primary-blue);
    }

    /* Statistics Bar */
    .project-stats-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0;
        background: white;
    }

    .stat-item {
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        border-right: 1px solid var(--gray-200);
        transition: all 0.3s ease;
    }

    .stat-item:last-child {
        border-right: none;
    }

    .stat-item:hover {
        background: var(--gray-50);
    }

    .stat-icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .stat-icon-circle.total {
        background: var(--primary-light);
        color: var(--primary-blue);
    }

    .stat-icon-circle.done {
        background: var(--success-light);
        color: var(--success-green);
    }

    .stat-icon-circle.progress {
        background: var(--info-light);
        color: var(--info-blue);
    }

    .stat-icon-circle.todo {
        background: var(--danger-light);
        color: var(--danger-red);
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        font-size: 12px;
        color: var(--gray-600);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-percentage {
        font-size: 11px;
        color: var(--gray-500);
        font-weight: 500;
    }

    /* Statistics Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-blue);
    }

    .stat-card.total::before { background: linear-gradient(180deg, var(--primary-blue), var(--purple)); }
    .stat-card.active::before { background: linear-gradient(180deg, var(--success-green), #059669); }
    .stat-card.progress::before { background: linear-gradient(180deg, var(--warning-orange), #D97706); }
    .stat-card.done::before { background: linear-gradient(180deg, var(--info-blue), #2563EB); }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .stat-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-card.total .stat-icon {
        background: var(--primary-light);
        color: var(--primary-blue);
    }

    .stat-card.active .stat-icon {
        background: var(--success-light);
        color: var(--success-green);
    }

    .stat-card.progress .stat-icon {
        background: var(--warning-light);
        color: var(--warning-orange);
    }

    .stat-card.done .stat-icon {
        background: var(--info-light);
        color: var(--info-blue);
    }

    .stat-label-card {
        font-size: 13px;
        color: var(--gray-600);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 8px;
    }

    .stat-value-card {
        font-size: 32px;
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1;
    }

    /* Filter & Search */
    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 24px;
        border: 1px solid var(--gray-200);
    }

    .filter-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr auto;
        gap: 12px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-700);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .filter-input,
    .filter-select {
        padding: 10px 14px;
        border: 1px solid var(--gray-300);
        border-radius: 8px;
        font-size: 14px;
        color: var(--gray-800);
        background: white;
        transition: all 0.2s;
    }

    .filter-input:focus,
    .filter-select:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .filter-input::placeholder {
        color: var(--gray-400);
    }

    .btn-filter {
        padding: 10px 16px;
        border-radius: 8px;
        border: 1px solid var(--gray-300);
        background: white;
        color: var(--gray-700);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-filter:hover {
        background: var(--gray-50);
        border-color: var(--gray-400);
    }

    .btn-filter.reset {
        color: var(--danger-red);
        border-color: var(--danger-red);
    }

    .btn-filter.reset:hover {
        background: var(--danger-light);
    }

    /* Table Container */
    .table-container {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--gray-200);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        background: var(--gray-50);
        padding: 20px 24px;
        border-bottom: 1px solid var(--gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--gray-900);
    }

    .table-info {
        font-size: 13px;
        color: var(--gray-600);
        font-weight: 500;
    }

    /* Project Table */
    .project-table-wrapper {
        overflow-x: auto;
    }

    .project-table {
        width: 100%;
        border-collapse: collapse;
    }

    .project-table thead {
        background: var(--gray-50);
        border-bottom: 2px solid var(--gray-200);
    }

    .project-table th {
        padding: 16px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: var(--gray-700);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    .project-table td {
        padding: 20px;
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
    }

    .project-table tbody tr {
        transition: background-color 0.2s;
    }

    .project-table tbody tr:hover {
        background-color: var(--gray-50);
    }

    /* Project Info Cell */
    .project-info {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        min-width: 280px;
    }

    .project-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
        background: var(--primary-light);
        color: var(--primary-blue);
    }

    .project-details {
        flex: 1;
        min-width: 0;
    }

    .project-name {
        font-size: 15px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 4px;
        line-height: 1.4;
    }

    .project-company {
        font-size: 13px;
        color: var(--gray-600);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .project-company i {
        font-size: 14px;
    }

    /* Category Badge */
    .category-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .category-badge i {
        font-size: 14px;
    }

    .category-web {
        background: var(--primary-light);
        color: var(--primary-blue);
    }

    .category-mobile {
        background: var(--success-light);
        color: var(--success-green);
    }

    .category-desktop {
        background: var(--info-light);
        color: var(--info-blue);
    }

    .category-ecommerce {
        background: var(--warning-light);
        color: var(--warning-orange);
    }

    .category-other {
        background: var(--purple-light);
        color: var(--purple);
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    .status-badge::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .status-aktif {
        background: var(--success-light);
        color: var(--success-green);
    }

    .status-pengerjaan {
        background: var(--warning-light);
        color: var(--warning-orange);
    }

    .status-selesai {
        background: var(--info-light);
        color: var(--info-blue);
    }

    .status-ditunda {
        background: var(--danger-light);
        color: var(--danger-red);
    }

    /* Date Info */
    .date-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 180px;
    }

    .date-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
    }

    .date-row i {
        font-size: 16px;
        color: var(--gray-500);
        flex-shrink: 0;
    }

    .date-label {
        font-size: 11px;
        color: var(--gray-500);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        min-width: 50px;
    }

    .date-value {
        font-size: 13px;
        color: var(--gray-900);
        font-weight: 600;
    }

    .date-separator {
        width: 100%;
        height: 1px;
        background: var(--gray-200);
    }

    /* Progress Bar in Table */
    .progress-cell {
        min-width: 180px;
    }

    .progress-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .progress-percentage {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-blue);
        min-width: 45px;
    }

    .progress-bar-mini {
        flex: 1;
        height: 8px;
        background: var(--gray-100);
        border-radius: 4px;
        overflow: hidden;
        position: relative;
    }

    .progress-bar-fill-mini {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-blue) 0%, var(--purple) 100%);
        border-radius: 4px;
        transition: width 0.6s ease;
        position: relative;
    }

    .progress-bar-fill-mini::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 50%;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.3) 0%, transparent 100%);
    }

    /* File Report */
    .file-report {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        font-size: 12px;
        color: var(--gray-700);
        font-weight: 600;
        transition: all 0.2s;
        cursor: pointer;
        white-space: nowrap;
    }

    .file-report:hover {
        background: var(--primary-light);
        border-color: var(--primary-blue);
        color: var(--primary-blue);
    }

    .file-report i {
        font-size: 18px;
    }

    .file-report.no-file {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .file-report.no-file:hover {
        background: var(--gray-50);
        border-color: var(--gray-200);
        color: var(--gray-700);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid var(--gray-300);
        background: white;
        color: var(--gray-700);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 16px;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .action-btn.show {
        border-color: var(--info-blue);
        color: var(--info-blue);
    }

    .action-btn.show:hover {
        background: var(--info-blue);
        color: white;
    }

    .action-btn.edit {
        border-color: var(--primary-blue);
        color: var(--primary-blue);
    }

    .action-btn.edit:hover {
        background: var(--primary-blue);
        color: white;
    }

    .action-btn.delete {
        border-color: var(--danger-red);
        color: var(--danger-red);
    }

    .action-btn.delete:hover {
        background: var(--danger-red);
        color: white;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 20px 24px;
        border-top: 1px solid var(--gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pagination-info {
        font-size: 13px;
        color: var(--gray-600);
        font-weight: 500;
    }

    .pagination {
        display: flex;
        gap: 6px;
    }

    .page-btn {
        padding: 8px 14px;
        border-radius: 6px;
        border: 1px solid var(--gray-300);
        background: white;
        color: var(--gray-700);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .page-btn:hover {
        background: var(--gray-50);
        border-color: var(--gray-400);
    }

    .page-btn.active {
        background: var(--primary-blue);
        color: white;
        border-color: var(--primary-blue);
    }

    .page-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Empty State */
    .empty-state {
        padding: 60px 24px;
        text-align: center;
    }

    .empty-state i {
        font-size: 64px;
        color: var(--gray-300);
        margin-bottom: 16px;
    }

    .empty-state h5 {
        font-size: 18px;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 14px;
        color: var(--gray-500);
        margin-bottom: 20px;
    }

    /* Responsive */
    @media (max-width: 1400px) {
        .stats-grid,
        .project-stats-bar {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 992px) {
        .filter-row {
            grid-template-columns: 1fr 1fr;
        }
        .filter-group:last-child {
            grid-column: 1 / -1;
        }
        
        .project-stats-bar {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .stat-item {
            border-right: 1px solid var(--gray-200);
        }
        
        .stat-item:nth-child(2n) {
            border-right: none;
        }
    }

    @media (max-width: 768px) {
        .page-header-content {
            flex-direction: column;
            align-items: flex-start;
        }
        .header-actions {
            width: 100%;
            flex-direction: column;
        }
        .btn-action {
            width: 100%;
            justify-content: center;
        }
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .filter-row {
            grid-template-columns: 1fr;
        }
        .table-header {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }
        .pagination-wrapper {
            flex-direction: column;
            gap: 16px;
        }
        
        .project-header-content {
            flex-direction: column;
            text-align: center;
        }
        
        .project-icon {
            margin: 0 auto;
        }
        
        .employee-name {
            justify-content: center;
        }
        
        .project-stats-bar {
            grid-template-columns: 1fr;
        }
        
        .stat-item {
            border-right: none;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .stat-item:last-child {
            border-bottom: none;
        }
    }
</style>
@endpush

@section('content')
<!-- Page Header -->


<!-- Project Header Card -->
<div class="project-header-card">
    <div class="project-header-top">
        <div class="project-header-content">
            <div class="d-flex align-items-start gap-3">
                <div class="project-icon">
                    <i class="bx bx-folder-open"></i>
                </div>
                <div>
                    <h4 class="project-title" id="selectedProjectTitle">Kelola Project</h4>
                    <p class="project-desc" id="selectedProjectDesc">Manajemen dan monitoring seluruh project perusahaan</p>
                    
                </div>
            </div>
            
       <div class="header-actions" style="margin-left: auto;">
    <button class="btn-action btn-outline-secondary" onclick="exportData()">
        <i class="bx bx-download"></i>
        Export
    </button>
    <button class="btn-action btn-primary" onclick="addNewProject()">
        <i class="bx bx-plus"></i>
        Tambah Project
    </button>
</div>
        </div>
    </div>
    
    <!-- Statistics Bar -->
    <div class="project-stats-bar">
        <div class="stat-item">
            <div class="stat-icon-circle total">
                <i class="bx bx-task"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Project</div>
                <div class="stat-value" id="totalTasks">3</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-circle progress">
                <i class="bx bx-trending-up"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Project Aktif</div>
                <div class="stat-value" id="earlyTasksCount">1</div>
                <div class="stat-percentage">Project Tahap Maintenance</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-circle progress">
                <i class="bx bx-check-double"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Dalam Pengerjaan</div>
                <div class="stat-value" id="ontimeTasksCount">1</div>
                <div class="stat-percentage">Project Dalam Pengerjaan</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-circle done">
                <i class="bx bx-check-double"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Project Selesai</div>
                <div class="stat-value" id="lateTasksCount">1</div>
                <div class="stat-percentage">Project Telah selesai Dikerjakan</div>
            </div>
        </div>
    </div>
</div>


<!-- Filter & Search Section -->
<div class="filter-section">
    <div class="filter-row">
        <div class="filter-group">
            <label class="filter-label">Pencarian</label>
            <input type="text" class="filter-input" id="searchInput" placeholder="Cari nama project atau perusahaan...">
        </div>
        
        <div class="filter-group">
            <label class="filter-label">Kategori</label>
            <select class="filter-select" id="categoryFilter">
                <option value="">Semua Kategori</option>
                <option value="web">Web Development</option>
                <option value="mobile">Mobile App</option>
                <option value="desktop">Desktop App</option>
                <option value="ecommerce">E-Commerce</option>
                <option value="other">Lainnya</option>
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">Status</label>
            <select class="filter-select" id="statusFilter">
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="pengerjaan">Pengerjaan</option>
                <option value="selesai">Selesai</option>
                <option value="ditunda">Ditunda</option>
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">Urutkan</label>
            <select class="filter-select" id="sortFilter">
                <option value="newest">Terbaru</option>
                <option value="oldest">Terlama</option>
                <option value="name">Nama A-Z</option>
                <option value="progress">Progress</option>
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">&nbsp;</label>
            <button class="btn-filter reset" onclick="resetFilters()">
                <i class="bx bx-refresh"></i>
                Reset
            </button>
        </div>
    </div>
</div>

<!-- Table Container -->
<div class="table-container">
    <div class="table-header">
        <h3 class="table-title">Daftar Project</h3>
        <span class="table-info">Menampilkan <strong id="showingCount">12</strong> dari <strong id="totalCount">12</strong> project</span>
    </div>
    <div class="project-table-wrapper">
        <table class="project-table" id="projectTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Informasi Project</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Timeline</th>
                    <th>Progress</th>
                    <th>File Laporan</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody id="projectTableBody">
                <!-- Data will be rendered here -->
            </tbody>
        </table>
        <div id="emptyState" class="empty-state" style="display: none;">
            <i class="bx bx-folder-open"></i>
            <h5>Tidak ada project ditemukan</h5>
            <p>Coba ubah filter pencarian atau tambahkan project baru</p>
            <button class="btn-action btn-primary" onclick="addNewProject()">
                <i class="bx bx-plus"></i>
                Tambah Project
            </button>
        </div>
    </div>
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Menampilkan <strong>1-12</strong> dari <strong>12</strong> project
        </div>
        <div class="pagination">
            <button class="page-btn" disabled>
                <i class="bx bx-chevron-left"></i>
            </button>
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <button class="page-btn">
                <i class="bx bx-chevron-right"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
'use strict';

// Sample Data
let projects = [
    {
        id: 1,
        nama: 'Kelola Project',
        perusahaan: 'PT Digital Indonesia',
        kategori: 'ecommerce',
        status: 'aktif',
        tanggalMulai: '2026-01-01',
        targetSelesai: '2026-03-31',
        progress: 67,
        fileReport: 'report-ecommerce-jan.pdf',
        projectManager: 'Ahmad Rizki',
        description: 'Manajemen dan monitoring seluruh project perusahaan',
        totalTasks: 15,
        earlyTasks: 3,
        ontimeTasks: 5,
        lateTasks: 2
    },
    {
        id: 2,
        nama: 'Mobile Banking App',
        perusahaan: 'Bank Sejahtera',
        kategori: 'mobile',
        status: 'pengerjaan',
        tanggalMulai: '2026-01-15',
        targetSelesai: '2026-04-15',
        progress: 45,
        fileReport: 'report-banking.pdf',
        projectManager: 'Siti Nurhaliza',
        description: 'Pengembangan aplikasi mobile banking dengan fitur keamanan terbaru',
        totalTasks: 20,
        earlyTasks: 5,
        ontimeTasks: 8,
        lateTasks: 2
    },
    {
        id: 3,
        nama: 'Company Website',
        perusahaan: 'CV Maju Jaya',
        kategori: 'web',
        status: 'selesai',
        tanggalMulai: '2025-11-01',
        targetSelesai: '2026-01-10',
        progress: 100,
        fileReport: 'report-website-final.pdf',
        projectManager: 'Budi Santoso',
        description: 'Pembuatan website perusahaan dengan CMS custom',
        totalTasks: 10,
        earlyTasks: 2,
        ontimeTasks: 7,
        lateTasks: 1
    }
];

// Sample tasks for selected project
let selectedProjectId = 1;

// Format tanggal
function formatDate(dateString) {
    const d = new Date(dateString);
    return d.toLocaleDateString('id-ID', { 
        day: 'numeric', 
        month: 'short',
        year: 'numeric'
    });
}

// Get category details
function getCategoryDetails(category) {
    const categories = {
        'web': { name: 'Web Development', icon: 'bx-globe', class: 'category-web' },
        'mobile': { name: 'Mobile App', icon: 'bx-mobile', class: 'category-mobile' },
        'desktop': { name: 'Desktop App', icon: 'bx-desktop', class: 'category-desktop' },
        'ecommerce': { name: 'E-Commerce', icon: 'bx-shopping-bag', class: 'category-ecommerce' },
        'other': { name: 'Lainnya', icon: 'bx-category', class: 'category-other' }
    };
    return categories[category] || categories['other'];
}

// Get status details
function getStatusDetails(status) {
    const statuses = {
        'aktif': { name: 'Aktif', class: 'status-aktif' },
        'pengerjaan': { name: 'Pengerjaan', class: 'status-pengerjaan' },
        'selesai': { name: 'Selesai', class: 'status-selesai' },
        'ditunda': { name: 'Ditunda', class: 'status-ditunda' }
    };
    return statuses[status] || statuses['aktif'];
}

// Update project header
function updateProjectHeader(project) {
    document.getElementById('selectedProjectTitle').textContent = project.nama;
    document.getElementById('selectedProjectDesc').textContent = project.description || project.nama;
    document.getElementById('selectedProjectManager').textContent = project.projectManager;
    
    document.getElementById('totalTasks').textContent = project.totalTasks || 0;
    document.getElementById('earlyTasksCount').textContent = project.earlyTasks || 0;
    document.getElementById('ontimeTasksCount').textContent = project.ontimeTasks || 0;
    document.getElementById('lateTasksCount').textContent = project.lateTasks || 0;
    
    // Update project icon based on category
    const category = getCategoryDetails(project.kategori);
    const projectIcon = document.querySelector('.project-icon i');
    projectIcon.className = `bx ${category.icon}`;
}

// Render project row
function renderProjectRow(project, index) {
    const category = getCategoryDetails(project.kategori);
    const status = getStatusDetails(project.status);
    return `
        <tr onclick="selectProject(${project.id})" style="cursor: pointer;">
            <td style="text-align: center; font-weight: 700; color: var(--gray-500);">${index + 1}</td>
            
            <td>
                <div class="project-info">
                    <div class="project-icon-box">
                        <i class="bx ${category.icon}"></i>
                    </div>
                    <div class="project-details">
                        <div class="project-name">${project.nama}</div>
                        <div class="project-company">
                            <i class="bx bx-buildings"></i>
                            ${project.perusahaan}
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <span class="category-badge ${category.class}">
                    <i class="bx ${category.icon}"></i>
                    ${category.name}
                </span>
            </td>
            <td>
                <span class="status-badge ${status.class}">
                    ${status.name}
                </span>
            </td>
            <td>
                <div class="date-info">
                    <div class="date-row">
                        <i class="bx bx-calendar-check"></i>
                        <span class="date-label">Mulai:</span>
                        <span class="date-value">${formatDate(project.tanggalMulai)}</span>
                    </div>
                    <div class="date-separator"></div>
                    <div class="date-row">
                        <i class="bx bx-calendar-x"></i>
                        <span class="date-label">Target:</span>
                        <span class="date-value">${formatDate(project.targetSelesai)}</span>
                    </div>
                </div>
            </td>
            <td>
                <div class="progress-cell">
                    <div class="progress-info">
                        <span class="progress-percentage">${project.progress}%</span>
                        <div class="progress-bar-mini">
                            <div class="progress-bar-fill-mini" style="width: ${project.progress}%"></div>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <div class="file-report ${project.fileReport ? '' : 'no-file'}" 
                     ${project.fileReport ? 'onclick="downloadReport(\'' + project.fileReport + '\')"' : ''}>
                    <i class="bx ${project.fileReport ? 'bx-download' : 'bx-file'}"></i>
                    <span>${project.fileReport ? 'Download' : 'Belum ada'}</span>
                </div>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn show" onclick="showProject(${project.id}); event.stopPropagation();" title="Lihat Detail">
                        <i class="bx bx-show"></i>
                    </button>
                    <button class="action-btn edit" onclick="editProject(${project.id}); event.stopPropagation();" title="Edit">
                        <i class="bx bx-edit"></i>
                    </button>
                    <button class="action-btn delete" onclick="deleteProject(${project.id}); event.stopPropagation();" title="Hapus">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `;
}

// Render all projects
function renderProjects(filteredProjects = projects) {
    const tbody = document.getElementById('projectTableBody');
    const emptyState = document.getElementById('emptyState');
    
    if (filteredProjects.length === 0) {
        tbody.innerHTML = '';
        emptyState.style.display = 'block';
    } else {
        emptyState.style.display = 'none';
        tbody.innerHTML = filteredProjects.map((project, index) => renderProjectRow(project, index)).join('');
    }
    updateStatistics(filteredProjects);
    updateTableInfo(filteredProjects.length);
    
    // Set initial selected project
    const initialProject = filteredProjects.find(p => p.id === selectedProjectId) || filteredProjects[0];
    if (initialProject) {
        updateProjectHeader(initialProject);
    }
}

// Update statistics
function updateStatistics(filteredProjects = projects) {
    const total = filteredProjects.length;
    const active = filteredProjects.filter(p => p.status === 'aktif').length;
    const progress = filteredProjects.filter(p => p.status === 'pengerjaan').length;
    const done = filteredProjects.filter(p => p.status === 'selesai').length;
    
    document.getElementById('totalProjects').textContent = total;
    document.getElementById('activeProjects').textContent = active;
    document.getElementById('progressProjects').textContent = progress;
    document.getElementById('doneProjects').textContent = done;
}

// Update table info
function updateTableInfo(count) {
    document.getElementById('showingCount').textContent = count;
    document.getElementById('totalCount').textContent = projects.length;
}

// Filter projects
function filterProjects() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const sortFilter = document.getElementById('sortFilter').value;
    
    let filtered = projects.filter(project => {
        const matchSearch = project.nama.toLowerCase().includes(searchTerm) || 
                          project.perusahaan.toLowerCase().includes(searchTerm);
        const matchCategory = !categoryFilter || project.kategori === categoryFilter;
        const matchStatus = !statusFilter || project.status === statusFilter;
        
        return matchSearch && matchCategory && matchStatus;
    });

    // Sort
    switch(sortFilter) {
        case 'newest':
            filtered.sort((a, b) => new Date(b.tanggalMulai) - new Date(a.tanggalMulai));
            break;
        case 'oldest':
            filtered.sort((a, b) => new Date(a.tanggalMulai) - new Date(b.tanggalMulai));
            break;
        case 'name':
            filtered.sort((a, b) => a.nama.localeCompare(b.nama));
            break;
        case 'progress':
            filtered.sort((a, b) => b.progress - a.progress);
            break;
    }

    renderProjects(filtered);
}

// Select project from table
function selectProject(id) {
    const project = projects.find(p => p.id === id);
    if (project) {
        selectedProjectId = id;
        updateProjectHeader(project);
        
        // Add visual feedback
        const rows = document.querySelectorAll('#projectTableBody tr');
        rows.forEach(row => {
            row.style.background = 'white';
        });
        
        const selectedRow = document.querySelector(`tr[onclick="selectProject(${id})"]`);
        if (selectedRow) {
            selectedRow.style.background = 'var(--primary-light)';
            selectedRow.style.boxShadow = 'inset 4px 0 0 var(--primary-blue)';
        }
    }
}

// Reset filters
function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('sortFilter').value = 'newest';
    renderProjects();
}

// Action functions
function addNewProject() {
    alert('Fitur tambah project akan dibuka di modal/halaman baru');
}

function showProject(id) {
    const project = projects.find(p => p.id === id);
    alert(`Menampilkan detail project:\n\nNama: ${project.nama}\nPerusahaan: ${project.perusahaan}\nProgress: ${project.progress}%`);
}

function editProject(id) {
    const project = projects.find(p => p.id === id);
    alert(`Edit project: ${project.nama}`);
}

function deleteProject(id) {
    const project = projects.find(p => p.id === id);
    if (confirm(`Apakah Anda yakin ingin menghapus project "${project.nama}"?`)) {
        projects = projects.filter(p => p.id !== id);
        renderProjects();
        showNotification('Project berhasil dihapus', 'success');
    }
}

function downloadReport(filename) {
    alert(`Mengunduh file: ${filename}`);
}

function exportData() {
    alert('Export data ke Excel/PDF');
}

// Show notification
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        border-radius: 8px;
    `;
    
    notification.innerHTML = `
        <strong>${type === 'success' ? 'Sukses!' : 'Error!'}</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Event listeners
document.getElementById('searchInput').addEventListener('input', filterProjects);
document.getElementById('categoryFilter').addEventListener('change', filterProjects);
document.getElementById('statusFilter').addEventListener('change', filterProjects);
document.getElementById('sortFilter').addEventListener('change', filterProjects);

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    renderProjects();
});
</script>
@endpush