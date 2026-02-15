@extends('layouts.master')
@section('title', 'Task Saya - E-Commerce Redesign')
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

    /* Project Header */
    .project-header-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
        overflow: hidden;
        border: 1px solid var(--gray-200);
    }

    .project-header-top {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--purple) 100%);
        padding: 24px 28px;
        position: relative;
        overflow: hidden;
    }

    .project-header-top::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .project-header-content {
        position: relative;
        z-index: 1;
    }

    .project-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
        font-size: 24px;
    }

    .project-title {
        color: white;
        font-weight: 700;
        font-size: 22px;
        margin-bottom: 6px;
        line-height: 1.2;
    }

    .project-desc {
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 0;
        font-size: 14px;
        line-height: 1.5;
    }

    .employee-name {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        margin-top: 12px;
    }

    .employee-name i {
        font-size: 18px;
    }

    /* Statistics Bar */
    .project-stats-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border-top: 1px solid var(--gray-200);
        background: var(--gray-50);
    }

    .stat-item {
        padding: 20px 28px;
        border-right: 1px solid var(--gray-200);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: background 0.2s;
    }

    .stat-item:last-child {
        border-right: none;
    }

    .stat-item:hover {
        background: white;
    }

    .stat-icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .stat-icon-circle.total { 
        background: linear-gradient(135deg, var(--primary-blue), var(--purple)); 
        color: white;
    }

    .stat-icon-circle.done { 
        background: linear-gradient(135deg, var(--success-green), #059669); 
        color: white;
    }

    .stat-icon-circle.progress { 
        background: linear-gradient(135deg, var(--warning-orange), #D97706); 
        color: white;
    }

    .stat-icon-circle.todo { 
        background: linear-gradient(135deg, var(--gray-400), var(--gray-500)); 
        color: white;
    }

    .stat-info {
        flex: 1;
        min-width: 0;
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
        font-size: 28px;
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-percentage {
        font-size: 12px;
        color: var(--gray-500);
        font-weight: 500;
    }

    /* Progress Bar Project Section */
    .progress-project-section {
        background: white;
        border-radius: 12px;
        padding: 24px 28px;
        margin-bottom: 24px;
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .progress-project-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .progress-project-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 4px;
    }

    .progress-project-subtitle {
        font-size: 13px;
        color: var(--gray-600);
        margin: 0;
    }

    .progress-percentage-display {
        font-size: 36px;
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--purple) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .progress-bar-container {
        background: var(--gray-100);
        border-radius: 12px;
        height: 28px;
        overflow: hidden;
        margin-bottom: 20px;
        position: relative;
        border: 1px solid var(--gray-200);
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-blue) 0%, var(--purple) 100%);
        border-radius: 12px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 14px;
        color: white;
        font-weight: 700;
        font-size: 13px;
        box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.3);
        position: relative;
    }

    .progress-bar-fill::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 50%;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.2) 0%, transparent 100%);
        border-radius: 12px 12px 0 0;
    }

    .progress-detail-info {
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
    }

    .progress-info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: var(--gray-700);
    }

    .progress-info-item i {
        font-size: 20px;
        color: var(--primary-blue);
    }

    .progress-info-item strong {
        color: var(--gray-900);
        font-weight: 700;
    }

    /* Kanban Board */
    .kanban-board {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .kanban-column {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--gray-200);
        display: flex;
        flex-direction: column;
        min-height: 600px;
        max-height: 800px;
    }

    .kanban-column-header {
        padding: 20px;
        border-bottom: 1px solid var(--gray-200);
        background: var(--gray-50);
        border-radius: 12px 12px 0 0;
    }

    .kanban-column-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .kanban-column-title i {
        font-size: 20px;
    }

    .kanban-column.todo .kanban-column-title {
        color: var(--gray-700);
    }

    .kanban-column.progress .kanban-column-title {
        color: #D97706;
    }

    .kanban-column.done .kanban-column-title {
        color: var(--success-green);
    }

    .kanban-column-count {
        font-size: 13px;
        color: var(--gray-600);
        font-weight: 600;
        background: white;
        padding: 4px 12px;
        border-radius: 6px;
        display: inline-block;
    }

    .kanban-cards-container {
        padding: 16px;
        flex: 1;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    /* Scrollbar */
    .kanban-cards-container::-webkit-scrollbar {
        width: 8px;
    }

    .kanban-cards-container::-webkit-scrollbar-track {
        background: var(--gray-100);
        border-radius: 4px;
    }

    .kanban-cards-container::-webkit-scrollbar-thumb {
        background: var(--gray-400);
        border-radius: 4px;
    }

    .kanban-cards-container::-webkit-scrollbar-thumb:hover {
        background: var(--gray-500);
    }

    /* Drag Over State */
    .kanban-cards-container.drag-over {
        background: var(--primary-light);
        border: 2px dashed var(--primary-blue);
        border-radius: 8px;
    }

    /* Task Card */
    .task-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: 10px;
        padding: 16px;
        cursor: grab;
        transition: all 0.2s;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .task-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-blue);
    }

    .task-card:active {
        cursor: grabbing;
    }

    .task-card.dragging {
        opacity: 0.5;
        transform: rotate(2deg);
    }

    .task-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .task-card-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1.4;
        flex: 1;
        margin-right: 8px;
    }

    .task-level-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        flex-shrink: 0;
    }

    .task-level-badge.level-1 { 
        background: linear-gradient(135deg, #10B981, #059669); 
        color: white;
    }

    .task-level-badge.level-2 { 
        background: linear-gradient(135deg, var(--warning-orange), #D97706); 
        color: white;
    }

    .task-level-badge.level-3 { 
        background: linear-gradient(135deg, var(--danger-red), #DC2626); 
        color: white;
    }

    .task-card-desc {
        font-size: 13px;
        color: var(--gray-600);
        line-height: 1.5;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .task-card-meta {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding-top: 12px;
        border-top: 1px solid var(--gray-100);
    }

    .task-deadline {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--gray-700);
        font-weight: 600;
    }

    .task-deadline i {
        font-size: 14px;
        color: var(--gray-500);
    }

    .task-timeline-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .task-timeline-badge.early {
        background: var(--primary-light);
        color: var(--primary-blue);
    }

    .task-timeline-badge.ontime {
        background: var(--success-light);
        color: var(--success-green);
    }

    .task-timeline-badge.late {
        background: var(--danger-light);
        color: var(--danger-red);
    }

    .task-timeline-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .task-card-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }

    .task-action-btn {
        flex: 1;
        padding: 8px;
        border-radius: 6px;
        border: 1px solid var(--gray-300);
        background: white;
        color: var(--gray-700);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .task-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .task-action-btn.detail {
        border-color: var(--primary-blue);
        color: var(--primary-blue);
    }

    .task-action-btn.detail:hover {
        background: var(--primary-blue);
        color: white;
    }

    .task-action-btn.report {
        border-color: var(--success-green);
        color: var(--success-green);
    }

    .task-action-btn.report:hover {
        background: var(--success-green);
        color: white;
    }

    /* Empty State */
    .kanban-empty-state {
        padding: 40px 20px;
        text-align: center;
        color: var(--gray-400);
    }

    .kanban-empty-state i {
        font-size: 48px;
        margin-bottom: 12px;
        opacity: 0.5;
    }

    .kanban-empty-state p {
        font-size: 13px;
        font-weight: 500;
        margin: 0;
    }

    /* Modal Styles */
    .task-detail-modal .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    .task-detail-header {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--purple) 100%);
        color: white;
        padding: 24px 28px;
    }

    .modal-title {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .task-detail-body {
        padding: 28px;
    }

    .detail-field {
        margin-bottom: 24px;
    }

    .detail-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 8px;
    }

    .detail-value {
        font-size: 15px;
        color: var(--gray-900);
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .project-stats-bar {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .stat-item:nth-child(2) {
            border-right: none;
        }
        
        .stat-item:nth-child(3) {
            border-top: 1px solid var(--gray-200);
        }
    }

    @media (max-width: 992px) {
        .kanban-board {
            grid-template-columns: 1fr;
        }

        .kanban-column {
            min-height: 400px;
            max-height: 600px;
        }
    }

    @media (max-width: 768px) {
        .project-stats-bar {
            grid-template-columns: 1fr;
        }
        
        .stat-item {
            border-right: none !important;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .stat-item:last-child {
            border-bottom: none;
        }
        
        .project-header-top {
            padding: 20px;
        }

        .progress-project-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .progress-detail-info {
            flex-direction: column;
            gap: 12px;
        }
    }
</style>
@endpush

@section('content')
<!-- Project Header with Stats -->
<div class="project-header-card">
    <!-- Project Info Section -->
    <div class="project-header-top">
        <div class="project-header-content">
            <div class="d-flex align-items-start gap-3">
                <div class="project-icon">
                    <i class="bx bx-shopping-bag"></i>
                </div>
                <div>
                    <h4 class="project-title">E-Commerce Redesign Project</h4>
                    <p class="project-desc">Redesign aplikasi e-commerce dengan UI/UX modern dan peningkatan performa</p>
                    <div class="employee-name">
                        <i class="bx bx-user"></i>
                        <span>Ahmad Rizki</span>
                    </div>
                </div>
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
                <div class="stat-label">Total Task Saya</div>
                <div class="stat-value" id="totalTasks">3</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-circle done">
                <i class="bx bx-trending-up"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Sebelum Deadline</div>
                <div class="stat-value" id="earlyTasksCount">1</div>
                <div class="stat-percentage">Task selesai lebih cepat</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-circle progress">
                <i class="bx bx-check-double"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Tepat Waktu</div>
                <div class="stat-value" id="ontimeTasksCount">0</div>
                <div class="stat-percentage">Task selesai on time</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-circle todo">
                <i class="bx bx-error-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Terlambat</div>
                <div class="stat-value" id="lateTasksCount">2</div>
                <div class="stat-percentage">Task melewati deadline</div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Bar Project -->
<div class="progress-project-section">
    <div class="progress-project-header">
        <div>
            <h5 class="progress-project-title">Progress Penyelesaian Project</h5>
            <p class="progress-project-subtitle">Persentase task yang telah diselesaikan</p>
        </div>
        <div class="progress-percentage-display" id="progressPercentageDisplay">67%</div>
    </div>
    
    <div class="progress-bar-container">
        <div class="progress-bar-fill" id="progressBarFill" style="width: 67%;">
            <span id="progressBarText">67%</span>
        </div>
    </div>

    <div class="progress-detail-info">
        <div class="progress-info-item">
            <i class="bx bx-check-circle"></i>
            <span><strong id="doneTasksCount">3</strong> dari <strong id="totalTasksCount">15</strong> task, merupakan milik anda</span>
        </div>
        <div class="progress-info-item">
            <i class="bx bx-loader-circle"></i>
            <span><strong id="remainingTasksCount">1</strong> task masih dalam proses</span>
        </div>
    </div>
</div>

<!-- Kanban Board -->
<div class="kanban-board">
    <!-- TO DO Column -->
    <div class="kanban-column todo" data-status="todo">
        <div class="kanban-column-header">
            <div class="kanban-column-title">
                <i class="bx bx-time-five"></i>
                To Do
            </div>
            <span class="kanban-column-count"><span id="todoCount">0</span> Task</span>
        </div>
        <div class="kanban-cards-container" id="todoContainer" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
            <!-- Empty state will show if no tasks -->
        </div>
    </div>

    <!-- IN PROGRESS Column -->
    <div class="kanban-column progress" data-status="progress">
        <div class="kanban-column-header">
            <div class="kanban-column-title">
                <i class="bx bx-loader-circle"></i>
                In Progress
            </div>
            <span class="kanban-column-count"><span id="progressCount">1</span> Task</span>
        </div>
        <div class="kanban-cards-container" id="progressContainer" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
            <!-- Tasks will be rendered here -->
        </div>
    </div>

    <!-- DONE Column -->
    <div class="kanban-column done" data-status="done">
        <div class="kanban-column-header">
            <div class="kanban-column-title">
                <i class="bx bx-check-circle"></i>
                Done
            </div>
            <span class="kanban-column-count"><span id="doneCount">2</span> Task</span>
        </div>
        <div class="kanban-cards-container" id="doneContainer" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
            <!-- Tasks will be rendered here -->
        </div>
    </div>
</div>

<!-- Modal: Task Detail -->
<div class="modal fade task-detail-modal" id="taskDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="task-detail-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="modal-title" id="detailTaskName">Task Name</h5>
                        <small id="detailTaskId">ID: </small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="task-detail-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="detail-field">
                            <div class="detail-label">Deskripsi</div>
                            <div class="detail-value" id="detailTaskDesc"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-field">
                                    <div class="detail-label">Status</div>
                                    <div id="detailTaskStatus"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-field">
                                    <div class="detail-label">Level</div>
                                    <div id="detailTaskLevel"></div>
                                </div>
                            </div>
                        </div>
                        <div class="detail-field">
                            <div class="detail-label">Timeline Status</div>
                            <div id="detailTimelineStatus"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-field">
                            <div class="detail-label">Tanggal Mulai</div>
                            <div class="detail-value fw-bold" id="detailStartDate"></div>
                        </div>
                        <div class="detail-field">
                            <div class="detail-label">Deadline</div>
                            <div class="detail-value fw-bold" id="detailEndDate"></div>
                        </div>
                        <div class="detail-field">
                            <div class="detail-label">Durasi</div>
                            <div class="detail-value" id="detailDuration"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
'use strict';

// Data tasks untuk karyawan Ahmad Rizki (ID: 1)
let tasks = [
    { 
        id: 1, 
        nama: 'Database Design', 
        desc: 'Merancang struktur database baru untuk e-commerce dengan performa tinggi',
        assignee: 1, 
        status: 'done', 
        level: '3',
        startDate: '2026-01-05', 
        endDate: '2026-01-15',
        progress: 100
    },
    { 
        id: 6, 
        nama: 'User Authentication', 
        desc: 'Sistem autentikasi dan otorisasi pengguna',
        assignee: 1, 
        status: 'done', 
        level: '2',
        startDate: '2026-01-15', 
        endDate: '2026-01-25',
        progress: 100
    },
    { 
        id: 2, 
        nama: 'API Development', 
        desc: 'Membuat RESTful API untuk integrasi dengan third-party services',
        assignee: 1, 
        status: 'progress', 
        level: '3',
        startDate: '2026-01-10', 
        endDate: '2026-02-20',
        progress: 60
    }
];

let draggedTask = null;

// Format tanggal
function formatFullDate(dateString) {
    const d = new Date(dateString);
    return d.toLocaleDateString('id-ID', { 
        day: 'numeric', 
        month: 'long',
        year: 'numeric'
    });
}

// Hitung hari antara dua tanggal
function daysBetween(d1, d2) {
    return Math.round(Math.abs((new Date(d1) - new Date(d2)) / (24*60*60*1000)));
}

// Hitung status timeline
function calculateTimelineStatus(task) {
    const today = new Date('2026-02-14'); // Tanggal referensi
    const end = new Date(task.endDate);
    
    if (task.status === 'done') {
        const daysDiff = Math.ceil((end - today) / (1000 * 60 * 60 * 24));
        if (daysDiff > 0) {
            return 'early'; // Selesai sebelum deadline
        } else if (daysDiff === 0) {
            return 'ontime'; // Selesai tepat waktu
        } else {
            return 'late'; // Terlambat
        }
    }
    
    if (end < today) return 'late';
    if (end > today) return 'early';
    return 'ontime';
}

// Render task card
function renderTaskCard(task) {
    const timelineStatus = calculateTimelineStatus(task);
    const timelineStatusText = {
        'late': 'Terlambat',
        'ontime': 'Tepat Waktu',
        'early': 'Sebelum Deadline'
    };

    // Button untuk task yang Done
    const doneActions = task.status === 'done' ? `
        <button class="task-action-btn report" onclick="sendReport(${task.id})">
            <i class="bx bx-send"></i>
            Kirim Report
        </button>
    ` : '';

    return `
        <div class="task-card" draggable="true" data-task-id="${task.id}" 
             ondragstart="dragStart(event)" ondragend="dragEnd(event)">
            <div class="task-card-header">
                <div class="task-card-title">${task.nama}</div>
                <span class="task-level-badge level-${task.level}">Lvl ${task.level}</span>
            </div>
            <div class="task-card-desc">${task.desc}</div>
            <div class="task-card-meta">
                <div class="task-deadline">
                    <i class="bx bx-calendar"></i>
                    <span>${formatFullDate(task.endDate)}</span>
                </div>
                <span class="task-timeline-badge ${timelineStatus}">
                    ${timelineStatusText[timelineStatus]}
                </span>
            </div>
            <div class="task-card-actions">
                <button class="task-action-btn detail" onclick="showTaskDetail(${task.id})">
                    <i class="bx bx-info-circle"></i>
                    Detail
                </button>
                ${doneActions}
            </div>
        </div>
    `;
}

// Render empty state
function renderEmptyState() {
    return `
        <div class="kanban-empty-state">
            <i class="bx bx-package"></i>
            <p>Belum ada task</p>
        </div>
    `;
}

// Render semua tasks ke kolom masing-masing
function renderAllTasks() {
    const todoContainer = document.getElementById('todoContainer');
    const progressContainer = document.getElementById('progressContainer');
    const doneContainer = document.getElementById('doneContainer');

    // Clear containers
    todoContainer.innerHTML = '';
    progressContainer.innerHTML = '';
    doneContainer.innerHTML = '';

    // Filter dan render tasks
    const todoTasks = tasks.filter(t => t.status === 'todo');
    const progressTasks = tasks.filter(t => t.status === 'progress' || t.status === 'review');
    const doneTasks = tasks.filter(t => t.status === 'done');

    // Render to DO
    if (todoTasks.length === 0) {
        todoContainer.innerHTML = renderEmptyState();
    } else {
        todoTasks.forEach(task => {
            todoContainer.innerHTML += renderTaskCard(task);
        });
    }

    // Render In Progress
    if (progressTasks.length === 0) {
        progressContainer.innerHTML = renderEmptyState();
    } else {
        progressTasks.forEach(task => {
            progressContainer.innerHTML += renderTaskCard(task);
        });
    }

    // Render Done
    if (doneTasks.length === 0) {
        doneContainer.innerHTML = renderEmptyState();
    } else {
        doneTasks.forEach(task => {
            doneContainer.innerHTML += renderTaskCard(task);
        });
    }

    // Update counts
    document.getElementById('todoCount').textContent = todoTasks.length;
    document.getElementById('progressCount').textContent = progressTasks.length;
    document.getElementById('doneCount').textContent = doneTasks.length;

    updateStatistics();
}

// Update statistics
function updateStatistics() {
    const total = tasks.length;
    const done = tasks.filter(t => t.status === 'done').length;
    const progress = tasks.filter(t => t.status === 'progress' || t.status === 'review').length;
    const todo = tasks.filter(t => t.status === 'todo').length;

    const donePercentage = total > 0 ? Math.round((done / total) * 100) : 0;

    // Update statistics bar - hanya total task
    document.getElementById('totalTasks').textContent = total;

    // Update timeline stats di statistics bar
    let earlyCount = 0;
    let ontimeCount = 0;
    let lateCount = 0;

    tasks.forEach(task => {
        const status = calculateTimelineStatus(task);
        if (status === 'early') earlyCount++;
        else if (status === 'ontime') ontimeCount++;
        else if (status === 'late') lateCount++;
    });

    document.getElementById('earlyTasksCount').textContent = earlyCount;
    document.getElementById('ontimeTasksCount').textContent = ontimeCount;
    document.getElementById('lateTasksCount').textContent = lateCount;

    // Update progress bar project
    document.getElementById('progressPercentageDisplay').textContent = `${donePercentage}%`;
    const progressBar = document.getElementById('progressBarFill');
    progressBar.style.width = `${donePercentage}%`;
    document.getElementById('progressBarText').textContent = `${donePercentage}%`;

    // Update progress detail info
    document.getElementById('doneTasksCount').textContent = done;
    document.getElementById('totalTasksCount').textContent = total;
    document.getElementById('remainingTasksCount').textContent = progress + todo;
}

// Drag and Drop Functions
function dragStart(e) {
    draggedTask = e.target;
    e.target.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', e.target.innerHTML);
}

function dragEnd(e) {
    e.target.classList.remove('dragging');
}

function allowDrop(e) {
    e.preventDefault();
    e.currentTarget.classList.add('drag-over');
}

function dragLeave(e) {
    e.currentTarget.classList.remove('drag-over');
}

function drop(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');
    
    if (!draggedTask) return;

    const taskId = parseInt(draggedTask.dataset.taskId);
    const newStatus = e.currentTarget.parentElement.dataset.status;
    
    // Update task status
    const task = tasks.find(t => t.id === taskId);
    if (task) {
        task.status = newStatus;
        
        // Auto update progress based on status
        if (newStatus === 'done') {
            task.progress = 100;
        } else if (newStatus === 'progress') {
            task.progress = task.progress || 50;
        } else if (newStatus === 'todo') {
            task.progress = 0;
        }

        renderAllTasks();
        
        showNotification(`Task "${task.nama}" dipindahkan ke ${getStatusLabel(newStatus)}`, 'success');
    }

    draggedTask = null;
}

// Get status label
function getStatusLabel(status) {
    const labels = {
        'todo': 'To Do',
        'progress': 'In Progress',
        'review': 'Review',
        'done': 'Done'
    };
    return labels[status] || status;
}

// Show task detail
function showTaskDetail(taskId) {
    const task = tasks.find(t => t.id === taskId);
    if (!task) return;

    const timelineStatus = calculateTimelineStatus(task);
    const duration = daysBetween(task.startDate, task.endDate) + 1;

    document.getElementById('detailTaskName').textContent = task.nama;
    document.getElementById('detailTaskId').textContent = `ID: ${task.id}`;
    document.getElementById('detailTaskDesc').textContent = task.desc;
    document.getElementById('detailStartDate').textContent = formatFullDate(task.startDate);
    document.getElementById('detailEndDate').textContent = formatFullDate(task.endDate);
    document.getElementById('detailDuration').textContent = `${duration} hari`;

    const statusLabels = {
        'todo': 'To Do',
        'progress': 'In Progress',
        'review': 'Review',
        'done': 'Done'
    };

    const statusClass = {
        'todo': 'todo',
        'progress': 'progress',
        'review': 'progress',
        'done': 'done'
    };

    document.getElementById('detailTaskStatus').innerHTML = `
        <span class="task-timeline-badge ${statusClass[task.status]}">
            ${statusLabels[task.status]}
        </span>
    `;

    document.getElementById('detailTaskLevel').innerHTML = `
        <span class="task-level-badge level-${task.level}">Level ${task.level}</span>
    `;

    const timelineStatusLabels = {
        'late': 'Terlambat',
        'ontime': 'Tepat Waktu',
        'early': 'Sebelum Deadline'
    };

    document.getElementById('detailTimelineStatus').innerHTML = `
        <span class="task-timeline-badge ${timelineStatus}">
            ${timelineStatusLabels[timelineStatus]}
        </span>
    `;

    const modal = new bootstrap.Modal(document.getElementById('taskDetailModal'));
    modal.show();
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

// Send report function
function sendReport(taskId) {
    const task = tasks.find(t => t.id === taskId);
    if (!task) return;

    // Show confirmation
    if (confirm(`Kirim report penyelesaian untuk task "${task.nama}"?\n\nPastikan Anda telah melampirkan bukti penyelesaian task.`)) {
        // Simulate sending report
        showNotification(`Report task "${task.nama}" berhasil dikirim!`, 'success');
        
        // Optional: Bisa tambahkan logic untuk upload file atau redirect ke halaman report
        console.log('Sending report for task:', task);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    renderAllTasks();
});
</script>
@endpush