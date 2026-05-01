@extends('layouts.master')
@section('title', 'Dashboard - Admin System')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
<style>
/* ===== PROGRESS PROJECT ===== */
.chart-container {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
}
.chart-container::before {
    content: '';
    position: absolute;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(105,108,255,0.12) 0%, rgba(105,108,255,0) 70%);
    pointer-events: none;
    z-index: 0;
}
#progressProjectChart { position: relative; z-index: 1; }
.progress-info .fw-bold { font-size: 15px; letter-spacing: 0.2px; }
.tooltip-note {
    background: linear-gradient(135deg, #fff8e1 0%, #fff3cd 100%);
    border: 1px solid #ffe082;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 11px;
    color: #7a6500;
    line-height: 1.5;
    text-align: center;
    box-shadow: 0 2px 6px rgba(255,200,0,0.1);
}
.col-lg-8.d-flex.flex-column { display: flex !important; flex-direction: column !important; }
.card.row-2-card.flex-grow-1 { flex: 1 1 auto; }
/* Top 5 table */
.top5-inline-table thead th {
    font-size: 11px; font-weight: 700; color: #566a7f;
    background: transparent; border-bottom: 2px solid #e9ecef; border-top: none;
    padding: 10px 12px; text-transform: uppercase; letter-spacing: 0.4px; white-space: nowrap;
}
.top5-inline-table tbody td {
    font-size: 13px; color: #2c3e50; border-color: #f0f0f0;
    vertical-align: middle; padding: 11px 12px;
}
.top5-inline-table tbody tr:last-child td { border-bottom: none; }
.skor-badge {
    display: inline-block; background: #e6f9f0; color: #00a65a;
    font-weight: 700; font-size: 12px; border-radius: 6px;
    padding: 3px 10px; min-width: 48px; text-align: center; border: 1px solid #b2f0d6;
}
/* ===== STAT CARDS ===== */
.stat-card { border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
.stat-icon-box {
    width: 46px; height: 46px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
/* ===== CHART CARDS ===== */
.chart-card { border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); height: 100%; }
.chart-card .card-header { background: transparent; border-bottom: 1px solid #e9ecef; padding: 14px 18px; }
.chart-card .card-header h6 { margin-bottom: 0; }
/* ===== LEGEND ===== */
.legend-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
/* ===== TABS AKTIVITAS ===== */
.act-tabs { display: flex; gap: 2px; background: #f0f1f5; border-radius: 8px; padding: 3px; }
.act-tab {
    border: none; background: transparent; border-radius: 6px;
    padding: 3px 12px; font-size: 12px; color: #566a7f;
    cursor: pointer; font-weight: 500; transition: all .2s;
}
.act-tab.active { background: #696cff; color: #fff; font-weight: 600; }
/* ===== SIMPLE LIST ===== */
.simple-list { list-style: none; padding: 0; margin: 0; }
.simple-list li {
    display: flex; justify-content: space-between; align-items: center;
    padding: 8px 0; border-bottom: 1px solid #f0f0f0; font-size: 13px;
}
.simple-list li:last-child { border-bottom: none; }
.simple-list .list-label { display: flex; align-items: center; gap: 8px; color: #2c3e50; }
.simple-list .bullet { width: 8px; height: 8px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
.simple-list .list-value { font-weight: 600; color: #2c3e50; }
.simple-list .total-item { padding-top: 12px; margin-top: 6px; border-top: 1px solid #e9ecef; font-weight: 700; }
.simple-list .total-item .list-label { font-weight: 700; }
.bullet-0  { background: #696cff; }
.bullet-1  { background: #00E396; }
.bullet-2  { background: #00D4FF; }
.bullet-3  { background: #FEB019; }
.bullet-4  { background: #FF66B2; }
.bullet-5  { background: #775DD0; }
.bullet-6  { background: #FF4560; }
.bullet-7  { background: #008FFB; }
.bullet-8  { background: #00E396; }
.bullet-9  { background: #FEB019; }
/* ===== SELECT DROPDOWN ===== */
.header-select {
    border: 1px solid #d9dee3; border-radius: 6px;
    padding: 4px 10px; font-size: 12px; color: #2c3e50;
    background: #fff; cursor: pointer; outline: none;
}
/* ===== STATUS PROGRESS BAR ===== */
.status-container { padding: 16px; }
.status-item { margin-bottom: 16px; }
.status-item:last-of-type { margin-bottom: 0; }
.status-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
.status-label { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; color: #2c3e50; }
.status-values { display: flex; gap: 8px; font-size: 12px; }
.status-number { font-weight: 700; color: #2c3e50; }
.status-percent { color: #8592a3; }
.progress-bar-container { width: 100%; height: 8px; background-color: #e9ecef; border-radius: 10px; overflow: hidden; }
.progress-bar-fill { height: 100%; border-radius: 10px; transition: width 0.4s ease; }
.status-total {
    display: flex; justify-content: space-between; align-items: center;
    padding-top: 16px; margin-top: 8px; border-top: 1px solid #e9ecef;
    font-weight: 700; font-size: 13px;
}
.status-total-label { color: #2c3e50; }
.status-total-number { font-weight: 700; color: #2c3e50; }
.status-total-percent { color: #8592a3; font-weight: normal; }
/* ===== ROW-2 CARDS ===== */
.row-2-card { border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
.row-2-card .card-header { background: transparent; border-bottom: 1px solid #e9ecef; padding: 14px 18px; }
/* ===== STAT LIST ===== */
.stat-list { border-top: 1px solid #e9ecef; padding-top: 8px; }
.stat-item { display: flex; align-items: center; justify-content: space-between; padding: 5px 0; font-size: 12px; }
.stat-label { display: flex; align-items: center; gap: 8px; color: #566a7f; }
.bullet { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }
.bullet-primary   { background: #696cff; }
.bullet-success   { background: #00E396; }
.bullet-warning   { background: #FEB019; }
.bullet-secondary { background: #8592a3; }
.bullet-purple    { background: #775DD0; }
.text-dark-custom { color: #2c3e50 !important; }

/* ===== HEALTH CHART CUSTOM ===== */
.health-chart-wrap {
    position: relative; height: 185px; display: flex; gap: 0;
}
.health-y-axis {
    width: 36px; flex-shrink: 0; position: relative;
}
.health-y-axis span {
    position: absolute; right: 4px; font-size: 9px; color: #8592a3; line-height: 1;
}
.health-chart-inner { flex: 1; position: relative; overflow: hidden; }
.health-x-labels {
    display: flex; padding-left: 36px; margin-top: 3px;
    margin-bottom: 6px; font-size: 9px; color: #8592a3;
    justify-content: space-between;
}
.health-legend-row {
    display: flex; gap: 16px; margin-bottom: 8px; font-size: 10px;
    color: #566a7f; align-items: center; flex-wrap: wrap;
}
.health-legend-line {
    width: 24px; height: 3px; border-radius: 2px; display: inline-block;
}
.health-status-box {
    border-radius: 0 0 12px 12px;
    border-top: 1px solid #e9ecef;
    padding: 12px 16px;
    transition: background .4s ease;
}
.health-icon-wrap {
    width: 38px; height: 38px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    transition: background .4s ease;
}
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="mb-4">
    <h4 class="fw-bold mb-1">Selamat datang, <span class="text-primary">Admin!</span></h4>
    <small class="text-muted">Ringkasan aktivitas proyek</small>
</div>

{{-- ════════════════════════════════════════════════════════
     ROW 1 — STAT CARDS
════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- Total User --}}
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="stat-icon-box me-3" style="background:#e7e8ff;">
                        <i class="bx bx-group fs-4 text-primary"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Total User</div>
                        <h3 class="fw-bold mb-0 lh-1">{{ number_format($totalUser) }}</h3>
                    </div>
                </div>
                <div class="stat-list mt-2">
                    <div class="stat-item">
                        <div class="stat-label"><span class="bullet bullet-primary"></span> Admin</div>
                        <span class="fw-bold">{{ $totalAdmin }}</span>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><span class="bullet bullet-success"></span> Project Manager</div>
                        <span class="fw-bold">{{ $totalPM }}</span>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><span class="bullet bullet-warning"></span> Karyawan</div>
                        <span class="fw-bold">{{ $totalKaryawan }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Klien --}}
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="stat-icon-box me-3" style="background:#e0f7e9;">
                        <i class="bx bx-building-house fs-4 text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Total Klien</div>
                        <h3 class="fw-bold mb-0 lh-1">{{ number_format($totalKlien) }}</h3>
                    </div>
                </div>
                <div class="stat-list mt-2">
                    <div class="stat-item">
                        <div class="stat-label"><span class="bullet bullet-success"></span> Klien Aktif</div>
                        <span class="fw-bold">{{ $totalKlienAktif }}</span>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><span class="bullet bullet-secondary"></span> Klien Non-Aktif</div>
                        <span class="fw-bold">{{ $totalKlienNonAktif }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Project --}}
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="stat-icon-box me-3" style="background:#ede7f6;">
                        <i class="bx bx-folder fs-4" style="color:#7c60c8;"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Total Project</div>
                        <h3 class="fw-bold mb-0 lh-1">{{ number_format($totalProjek) }}</h3>
                    </div>
                </div>
                <div class="stat-list mt-2">
                    <div class="stat-item">
                        <div class="stat-label"><span class="bullet bullet-secondary"></span> Pending</div>
                        <span class="fw-bold">{{ $totalProjekPending }}</span>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><span class="bullet bullet-primary"></span> Aktif</div>
                        <span class="fw-bold">{{ $totalProjekAktif }}</span>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><span class="bullet bullet-warning"></span> Dikerjakan</div>
                        <span class="fw-bold">{{ $totalProjekDikerjakan }}</span>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><span class="bullet bullet-success"></span> Selesai</div>
                        <span class="fw-bold">{{ $totalProjekSelesai }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Task --}}
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="stat-icon-box me-3" style="background:#fff3e0;">
                        <i class="bx bx-task fs-4 text-warning"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Total Task</div>
                        <h3 class="fw-bold mb-0 lh-1">{{ number_format($totalTask) }}</h3>
                    </div>
                </div>
                <div class="stat-list mt-2">
                    <div class="stat-item">
                        <div class="stat-label"><span class="bullet bullet-primary"></span> To Do</div>
                        <span class="fw-bold">{{ $totalTaskTodo }}</span>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><span class="bullet bullet-warning"></span> In Progress</div>
                        <span class="fw-bold">{{ $totalTaskInProgress }}</span>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><span class="bullet bullet-success"></span> Done</div>
                        <span class="fw-bold">{{ $totalTaskDone }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>{{-- /ROW 1 --}}

{{-- ════════════════════════════════════════════════════════
     ROW 2 — PROGRESS PROJECT + STATUS TASK + APPROVAL TASK
════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- KIRI: Progress Project (donut chart) --}}
    <div class="col-lg-4 d-flex">
        <div class="card row-2-card w-100">
            <div class="card-header">
                <h6 class="fw-bold mb-0 text-dark-custom">Progress Project</h6>
                <div class="text-muted" style="font-size:11px; margin-top:2px;">Persentase task selesai (Done &amp; Approved)</div>
                <select class="header-select mt-2 w-100" id="projectFilterSelect">
                    <option value="all">Semua Project</option>
                    @foreach($allProjeks as $p)
                        <option value="{{ $p->id_projek }}">{{ $p->nama_projek }}</option>
                    @endforeach
                </select>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center py-4 px-3">
                <div class="chart-container mb-3">
                    <div id="progressProjectChart"></div>
                </div>
                <div class="text-center w-100">
                    <p class="mb-1 fw-bold text-dark-custom fs-6" id="projectNameDisplay">Semua Project</p>
                    <p class="mb-0 small text-muted" id="totalTaskDisplay">
                        Total {{ $progressData['all']['total'] }} task
                    </p>
                    <p class="mb-3 small text-muted" id="completedTaskDisplay">
                        Selesai (Done &amp; Approved) {{ $progressData['all']['selesai'] }} task
                    </p>
                    <div class="tooltip-note">
                        Persentase dihitung dari total task yang berstatus Done &amp; Approved.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KANAN: Status Task & Approval Task --}}
    <div class="col-lg-8 d-flex flex-column" style="gap:16px;">
        
        {{-- Baris 1: Ringkasan Status Task + Final Status Task (2 kolom) --}}
        <div class="row g-3">
            {{-- Ringkasan Status Task --}}
            <div class="col-md-6">
                <div class="card row-2-card h-100">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0 text-dark-custom">Ringkasan Status Task</h6>
                        <div class="text-muted" id="statusTaskLabel" style="font-size:11px; margin-top:2px; word-break:break-word;">Semua Project</div>
                    </div>
                    <div class="status-container">
                        <div class="status-item">
                            <div class="status-header">
                                <div class="status-label">To Do</div>
                                <div class="status-values">
                                    <span class="status-number" id="todoCount">{{ $statusTaskData['all']['todo'] }}</span>
                                    <span class="status-percent" id="todoPercent">({{ $statusTaskData['all']['todo_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" id="todoBar"
                                     style="width:{{ $statusTaskData['all']['todo_pct'] }}%; background:#696cff;"></div>
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="status-header">
                                <div class="status-label">In Progress</div>
                                <div class="status-values">
                                    <span class="status-number" id="progressCount">{{ $statusTaskData['all']['inprogress'] }}</span>
                                    <span class="status-percent" id="progressPercent">({{ $statusTaskData['all']['inprogress_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" id="progressBar"
                                     style="width:{{ $statusTaskData['all']['inprogress_pct'] }}%; background:#FEB019;"></div>
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="status-header">
                                <div class="status-label">Done</div>
                                <div class="status-values">
                                    <span class="status-number" id="doneCount">{{ $statusTaskData['all']['done'] }}</span>
                                    <span class="status-percent" id="donePercent">({{ $statusTaskData['all']['done_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" id="doneBar"
                                     style="width:{{ $statusTaskData['all']['done_pct'] }}%; background:#00E396;"></div>
                            </div>
                        </div>
                        <div class="status-total">
                            <span class="status-total-label">Total</span>
                            <div>
                                <span class="status-total-number" id="totalStatusCount">{{ $statusTaskData['all']['total'] }}</span>
                                <span class="status-total-percent ms-1">(100%)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Final Status Task --}}
            <div class="col-md-6">
                <div class="card row-2-card h-100">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0 text-dark-custom">Final Status Task</h6>
                        <div class="text-muted" id="finalStatusLabel" style="font-size:11px; margin-top:2px; word-break:break-word;">Semua Project</div>
                    </div>
                    <div class="status-container">
                        <div class="status-item">
                            <div class="status-header">
                                <div class="status-label">Review</div>
                                <div class="status-values">
                                    <span class="status-number" id="previewCount">{{ $statusTaskData['all']['preview'] }}</span>
                                    <span class="status-percent" id="previewPercent">({{ $statusTaskData['all']['preview_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" id="previewBar"
                                     style="width:{{ $statusTaskData['all']['preview_pct'] }}%; background:#775DD0;"></div>
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="status-header">
                                <div class="status-label">Revisi</div>
                                <div class="status-values">
                                    <span class="status-number" id="revisiCount">{{ $statusTaskData['all']['revisi'] }}</span>
                                    <span class="status-percent" id="revisiPercent">({{ $statusTaskData['all']['revisi_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" id="revisiBar"
                                     style="width:{{ $statusTaskData['all']['revisi_pct'] }}%; background:#FEB019;"></div>
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="status-header">
                                <div class="status-label">Approved</div>
                                <div class="status-values">
                                    <span class="status-number" id="approvedCount">{{ $statusTaskData['all']['approved'] }}</span>
                                    <span class="status-percent" id="approvedPercent">({{ $statusTaskData['all']['approved_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" id="approvedBar"
                                     style="width:{{ $statusTaskData['all']['approved_pct'] }}%; background:#00E396;"></div>
                            </div>
                        </div>
                        <div class="status-total">
                            <span class="status-total-label">Total</span>
                            <div>
                                <span class="status-total-number" id="totalFinalCount">{{ $statusTaskData['all']['total_final'] }}</span>
                                <span class="status-total-percent ms-1">(100%)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>{{-- /Baris 1 --}}

        {{-- Baris 2: Approval Task (full width - col-12) --}}
        <div class="row g-3">
            <div class="col-12">
                <div class="card row-2-card">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0 text-dark-custom">Approval Task</h6>
                        <div class="text-muted" id="approvalTaskLabel" style="font-size:11px; margin-top:2px; word-break:break-word;">Semua Project</div>
                    </div>
                    <div class="status-container">
                        <div class="row g-3">
                            {{-- Menunggu Review --}}
                            <div class="col-md-4">
                                <div class="status-item">
                                    <div class="status-header">
                                        <div class="status-label">
                                            <i class="bx bx-time-five bx-sm" style="color:#775DD0;"></i>
                                            Menunggu Review
                                        </div>
                                        <div class="status-values">
                                            <span class="status-number" id="approvalReviewCount">{{ $statusTaskData['all']['preview'] }}</span>
                                            <span class="status-percent" id="approvalReviewPercent">({{ $statusTaskData['all']['preview_pct'] }}%)</span>
                                        </div>
                                    </div>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar-fill" id="approvalReviewBar"
                                             style="width:{{ $statusTaskData['all']['preview_pct'] }}%; background:#775DD0;"></div>
                                    </div>
                                    <div class="small text-muted mt-1">
                                        <i class="bx bx-info-circle"></i> Task yang sudah selesai dan menunggu persetujuan
                                    </div>
                                </div>
                            </div>

                            {{-- Revisi --}}
                            <div class="col-md-4">
                                <div class="status-item">
                                    <div class="status-header">
                                        <div class="status-label">
                                            <i class="bx bx-edit-alt bx-sm" style="color:#FEB019;"></i>
                                            Revisi
                                        </div>
                                        <div class="status-values">
                                            <span class="status-number" id="approvalRevisiCount">{{ $statusTaskData['all']['revisi'] }}</span>
                                            <span class="status-percent" id="approvalRevisiPercent">({{ $statusTaskData['all']['revisi_pct'] }}%)</span>
                                        </div>
                                    </div>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar-fill" id="approvalRevisiBar"
                                             style="width:{{ $statusTaskData['all']['revisi_pct'] }}%; background:#FEB019;"></div>
                                    </div>
                                    <div class="small text-muted mt-1">
                                        <i class="bx bx-info-circle"></i> Task yang perlu direvisi oleh karyawan
                                    </div>
                                </div>
                            </div>

                            {{-- Approved --}}
                            <div class="col-md-4">
                                <div class="status-item">
                                    <div class="status-header">
                                        <div class="status-label">
                                            <i class="bx bx-check-circle bx-sm" style="color:#00E396;"></i>
                                            Approved
                                        </div>
                                        <div class="status-values">
                                            <span class="status-number" id="approvalApprovedCount">{{ $statusTaskData['all']['approved'] }}</span>
                                            <span class="status-percent" id="approvalApprovedPercent">({{ $statusTaskData['all']['approved_pct'] }}%)</span>
                                        </div>
                                    </div>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar-fill" id="approvalApprovedBar"
                                             style="width:{{ $statusTaskData['all']['approved_pct'] }}%; background:#00E396;"></div>
                                    </div>
                                    <div class="small text-muted mt-1">
                                        <i class="bx bx-info-circle"></i> Task yang telah disetujui dan selesai
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="status-total mt-3">
                            <span class="status-total-label">Total Approval</span>
                            <div>
                                <span class="status-total-number" id="approvalTotalCount">{{ $statusTaskData['all']['total_final'] }}</span>
                                <span class="status-total-percent ms-1">(100%)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>{{-- /Baris 2 --}}

    </div>{{-- /col-lg-8 --}}
</div>{{-- /ROW 2 --}}


{{-- ════════════════════════════════════════════════════════
     ROW 3 — KESEHATAN PROJECT (Health Chart) + TOP 5 KARYAWAN
════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- Kesehatan Project (Health Chart) --}}
    <div class="col-lg-6">
        <div class="card row-2-card h-100">
            <div class="card-header d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold mb-0 text-dark-custom">
                        Kesehatan Project
                        <span class="fw-normal text-muted" style="font-size:12px;">(Berdasarkan Deadline)</span>
                        <i class="bx bx-info-circle text-muted ms-1"
                           data-bs-toggle="tooltip"
                           title="Persentase task yang selesai tepat waktu atau sebelum deadline dari semua task yang memiliki tenggat waktu."></i>
                    </h6>
                    <small class="text-muted">Persentase task yang selesai tepat waktu atau sebelum deadline.</small>
                </div>
                <div class="act-tabs" id="healthTabBtns">
                    <button class="act-tab active" onclick="switchHealthTab(this,'week')">Minggu</button>
                    <button class="act-tab" onclick="switchHealthTab(this,'month')">Bulan</button>
                    <button class="act-tab" onclick="switchHealthTab(this,'year')">Tahun</button>
                </div>
            </div>

            <div class="card-body pt-3 pb-0 px-3">
                {{-- Legend --}}
                <div class="health-legend-row">
                    <span style="display:flex;align-items:center;gap:5px;">
                        <span class="health-legend-line" style="background:#2dbb7c;"></span>
                        Performance (%)
                    </span>
                    <span style="margin-left:auto;font-size:10px;color:#8592a3;">
                        🟢 ≥80% Baik &nbsp; 🟡 50–79% Warning &nbsp; 🔴 &lt;50% Buruk
                    </span>
                </div>

                {{-- Chart --}}
                <div class="health-chart-wrap">
                    <div class="health-y-axis">
                        <span style="top:0;">100%</span>
                        <span style="top:25%;">75%</span>
                        <span style="top:50%;">50%</span>
                        <span style="top:75%;">25%</span>
                        <span style="bottom:0;">0%</span>
                    </div>
                    <div class="health-chart-inner">
                        <svg id="healthChartSvg" width="100%" height="185"
                             preserveAspectRatio="none" viewBox="0 0 600 185">
                            <defs>
                                <linearGradient id="gradGreen" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#2dbb7c" stop-opacity="0.45"/>
                                    <stop offset="100%" stop-color="#2dbb7c" stop-opacity="0.03"/>
                                </linearGradient>
                                <linearGradient id="gradYellow" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#f5a623" stop-opacity="0.45"/>
                                    <stop offset="100%" stop-color="#f5a623" stop-opacity="0.03"/>
                                </linearGradient>
                                <linearGradient id="gradRed" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#e74c3c" stop-opacity="0.45"/>
                                    <stop offset="100%" stop-color="#e74c3c" stop-opacity="0.03"/>
                                </linearGradient>
                                <clipPath id="chartClip">
                                    <rect x="0" y="0" width="600" height="185"/>
                                </clipPath>
                            </defs>
                            <line x1="0" y1="1"   x2="600" y2="1"   stroke="#e9ecef" stroke-width="0.5"/>
                            <line x1="0" y1="47"  x2="600" y2="47"  stroke="#e9ecef" stroke-width="0.5" stroke-dasharray="4 4"/>
                            <line x1="0" y1="93"  x2="600" y2="93"  stroke="#f5a623" stroke-width="1" stroke-dasharray="6 3" opacity="0.45"/>
                            <line x1="0" y1="139" x2="600" y2="139" stroke="#e9ecef" stroke-width="0.5" stroke-dasharray="4 4"/>
                            <line x1="0" y1="184" x2="600" y2="184" stroke="#e9ecef" stroke-width="0.5"/>
                            <g id="chartContent" clip-path="url(#chartClip)"></g>
                        </svg>
                    </div>
                </div>

                <div class="health-x-labels" id="xLabels"></div>
            </div>

            <div class="health-status-box" id="healthStatusBox" style="background:#f8f9fa;">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="health-icon-wrap" id="healthIconWrap" style="background:#e9ecef;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="#8592a3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             id="healthIconSvg">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="small text-muted">Status Kesehatan Project</div>
                        <div class="fw-bold small" id="healthStatusLabel" style="color:#566a7f;">Memuat data...</div>
                    </div>
                    <div class="ms-auto text-end small" id="healthStatusDesc"
                         style="max-width:280px;line-height:1.5;color:#566a7f;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top 5 Karyawan Terbaik --}}
    <div class="col-lg-6">
        <div class="card row-2-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark-custom">Top 5 Karyawan Terbaik</h6>
                <a href="{{ route('performa-karyawan.index') }}" class="small text-primary text-decoration-none fw-semibold">
                    Lihat semua &rarr;
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table top5-inline-table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3" style="width:36px;">#</th>
                            <th>Karyawan</th>
                            <th class="text-center">Tepat Waktu</th>
                            <th class="text-center">Sebelum Deadline</th>
                            <th class="text-center">Terlambat</th>
                            <th class="text-center">Total Task</th>
                            <th class="text-center pe-3">Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($top5Karyawan as $i => $k)
                        <tr>
                            <td class="ps-3 fw-bold text-muted">{{ $i + 1 }}</td>
                            <td>
                                <div class="fw-semibold text-dark-custom">{{ $k['nama'] }}</div>
                                @if($k['jabatan'])
                                    <div class="small text-muted">{{ $k['jabatan'] }}</div>
                                @endif
                            </td>
                            <td class="text-center">{{ $k['tepat_waktu'] }}</td>
                            <td class="text-center">{{ $k['sebelum_deadline'] }}</td>
                            <td class="text-center text-danger fw-bold">{{ $k['terlambat'] }}</td>
                            <td class="text-center">{{ $k['total_task'] }}</td>
                            <td class="text-center pe-3">
                                <span class="skor-badge">{{ number_format($k['poin']) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada data karyawan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>{{-- /ROW 3 --}}

{{-- ════════════════════════════════════════════════════════
     ROW 4 — TREN PROJECT + AKTIVITAS TASK
════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- Tren Perolehan Project --}}
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header">
                <h6 class="fw-bold mb-0 text-dark-custom">
                    Tren Perolehan Project
                    <span class="fw-normal text-muted">(12 Bulan Terakhir)</span>
                </h6>
                <small class="text-muted">Jumlah project baru per bulan berdasarkan tanggal dibuat</small>
            </div>
            <div class="card-body pt-1 pb-2">
                <div id="trendProjectChart"></div>
            </div>
        </div>
    </div>

    {{-- Aktivitas Penyelesaian Task --}}
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark-custom">Aktivitas Penyelesaian Task</h6>
                <div class="act-tabs">
                    <button class="act-tab active" onclick="switchActTab(this,'week')">Minggu</button>
                    <button class="act-tab" onclick="switchActTab(this,'month')">Bulan</button>
                    <button class="act-tab" onclick="switchActTab(this,'year')">Tahun</button>
                </div>
            </div>
            <div class="card-body pt-2 pb-2">
                <div class="d-flex gap-3 mb-3" style="font-size:12px;">
                    <span><span class="legend-dot me-1" style="background:#00E396;"></span><span class="text-dark-custom">Tepat Waktu</span></span>
                    <span><span class="legend-dot me-1" style="background:#FF4560;"></span><span class="text-dark-custom">Terlambat</span></span>
                    <span><span class="legend-dot me-1" style="background:#775DD0;"></span><span class="text-dark-custom">Sebelum Deadline</span></span>
                </div>
                <div id="taskActivityChart"></div>
            </div>
        </div>
    </div>

</div>{{-- /ROW 4 --}}

{{-- ════════════════════════════════════════════════════════
     ROW 5 — KATEGORI PROJECT + DISTRIBUSI ROLE KARYAWAN
════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- Kategori Project --}}
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header">
                <h6 class="fw-bold mb-0 text-dark-custom">Kategori Project</h6>
            </div>
            <div class="card-body">
                @if($kategoriProject->isEmpty())
                    <p class="text-muted text-center py-3 mb-0">Belum ada data kategori.</p>
                @else
                <ul class="simple-list">
                    @foreach($kategoriProject as $i => $kat)
                    <li>
                        <div class="list-label">
                            <span class="bullet bullet-{{ $i % 10 }}"></span>
                            {{ $kat->nama_kategori }}
                        </div>
                        <span class="list-value">{{ $kat->projek_count }}</span>
                    </li>
                    @endforeach
                    <li class="total-item">
                        <div class="list-label">Total</div>
                        <span class="list-value">{{ $totalKategoriProjek }} Project</span>
                    </li>
                </ul>
                @endif
            </div>
        </div>
    </div>

    {{-- Distribusi Role Karyawan --}}
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header">
                <h6 class="fw-bold mb-0 text-dark-custom">Distribusi Role Karyawan</h6>
            </div>
            <div class="card-body">
                @if($distribusiRole->isEmpty())
                    <p class="text-muted text-center py-3 mb-0">Belum ada data role.</p>
                @else
                <ul class="simple-list">
                    @foreach($distribusiRole as $i => $role)
                    <li>
                        <div class="list-label">
                            <span class="bullet bullet-{{ $i % 10 }}"></span>
                            {{ $role->nama_job_role }}
                        </div>
                        <span class="list-value">{{ $role->jumlah }}</span>
                    </li>
                    @endforeach
                    <li class="total-item">
                        <div class="list-label">Total</div>
                        <span class="list-value">{{ $totalKaryawanAktif }} Karyawan</span>
                    </li>
                </ul>
                @endif
            </div>
        </div>
    </div>

</div>{{-- /ROW 5 --}}

@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
'use strict';

/* ════════════════════════════════════════════════════════
   DATA DARI PHP
════════════════════════════════════════════════════════ */
const PHP_PROGRESS    = @json($progressData);
const PHP_STATUS      = @json($statusTaskData);
const PHP_TREND       = @json($trendProject);
const PHP_ACTIVITY    = {
    week:  @json($activityWeek),
    month: @json($activityMonth),
    year:  @json($activityYear),
};
const PHP_HEALTH = @json($healthDataPerProject ?? [
    'all' => ['week' => [], 'month' => [], 'year' => []]
]);

/* ════════════════════════════════════════════════════════
   WARNA
════════════════════════════════════════════════════════ */
const C = {
    primary:   '#696cff',
    success:   '#00E396',
    warning:   '#FEB019',
    danger:    '#FF4560',
    purple:    '#775DD0',
    secondary: '#8592a3',
    blue:      '#0055cc',
};

/* ════════════════════════════════════════════════════════
   INSTANCE CHART
════════════════════════════════════════════════════════ */
let progressChart  = null;
let trendChart     = null;
let activityChart  = null;

let currentHealthData = PHP_HEALTH['all'] ?? { week: [], month: [], year: [] };
let currentHealthPeriod = 'week';

/* ════════════════════════════════════════════════════════
   1. PROGRESS PROJECT — DONUT CHART
════════════════════════════════════════════════════════ */
function renderProgressChart(key) {
    const data = PHP_PROGRESS[key] ?? PHP_PROGRESS['all'];
    const pct  = parseFloat(data.persen) || 0;

    if (progressChart) { progressChart.destroy(); progressChart = null; }

    const el = document.querySelector('#progressProjectChart');
    if (!el || typeof ApexCharts === 'undefined') return;

    progressChart = new ApexCharts(el, {
        chart: {
            type: 'donut', height: 240, width: 240,
            toolbar: { show: false },
            animations: { enabled: true, speed: 600 }
        },
        series: [pct, 100 - pct],
        labels: ['Selesai', 'Sisa'],
        colors: [C.primary, '#ededff'],
        plotOptions: {
            pie: {
                donut: {
                    size: '78%',
                    background: 'transparent',
                    labels: {
                        show: true,
                        name: {
                            show: true, fontSize: '12px', fontWeight: 600,
                            color: C.primary, offsetY: -6, formatter: () => 'Selesai'
                        },
                        value: {
                            show: true, fontSize: '28px', fontWeight: 800,
                            color: C.primary, offsetY: 8, formatter: () => pct + '%'
                        },
                        total: {
                            show: true, showAlways: true, label: 'Selesai',
                            fontSize: '12px', fontWeight: 600, color: C.primary,
                            formatter: () => pct + '%'
                        }
                    }
                }
            }
        },
        stroke: { width: 4, colors: ['#fff'] },
        dataLabels: { enabled: false },
        legend: { show: false },
        tooltip: { enabled: false },
        states: {
            hover:  { filter: { type: 'none' } },
            active: { filter: { type: 'none' } }
        }
    });
    progressChart.render();

    document.getElementById('projectNameDisplay').textContent  = data.nama  ?? 'Semua Project';
    document.getElementById('totalTaskDisplay').textContent    = 'Total ' + data.total + ' task';
    document.getElementById('completedTaskDisplay').innerHTML  = 'Selesai (Done &amp; Approved) ' + data.selesai + ' task';
}

/* ════════════════════════════════════════════════════════
   2. STATUS TASK — PROGRESS BAR
════════════════════════════════════════════════════════ */
function updateStatusBars(key) {
    const d    = PHP_STATUS[key] ?? PHP_STATUS['all'];
    const nama = (PHP_PROGRESS[key] ?? PHP_PROGRESS['all']).nama ?? 'Semua Project';

    document.getElementById('statusTaskLabel').textContent = nama;
    document.getElementById('finalStatusLabel').textContent = nama;

    setText('todoCount',       d.todo);
    setText('todoPercent',     '(' + d.todo_pct + '%)');
    setBar ('todoBar',         d.todo_pct);

    setText('progressCount',   d.inprogress);
    setText('progressPercent', '(' + d.inprogress_pct + '%)');
    setBar ('progressBar',     d.inprogress_pct);

    setText('doneCount',       d.done);
    setText('donePercent',     '(' + d.done_pct + '%)');
    setBar ('doneBar',         d.done_pct);

    setText('totalStatusCount', d.total);

    setText('previewCount',    d.preview);
    setText('previewPercent',  '(' + d.preview_pct + '%)');
    setBar ('previewBar',      d.preview_pct);

    setText('revisiCount',     d.revisi);
    setText('revisiPercent',   '(' + d.revisi_pct + '%)');
    setBar ('revisiBar',       d.revisi_pct);

    setText('approvedCount',   d.approved);
    setText('approvedPercent', '(' + d.approved_pct + '%)');
    setBar ('approvedBar',     d.approved_pct);

    setText('totalFinalCount', d.total_final);

    // Approval Task updates (tambahkan di dalam function updateStatusBars)
setText('approvalReviewCount',    d.preview);
setText('approvalReviewPercent',  '(' + d.preview_pct + '%)');
setBar ('approvalReviewBar',      d.preview_pct);

setText('approvalRevisiCount',    d.revisi);
setText('approvalRevisiPercent',  '(' + d.revisi_pct + '%)');
setBar ('approvalRevisiBar',      d.revisi_pct);

setText('approvalApprovedCount',  d.approved);
setText('approvalApprovedPercent','(' + d.approved_pct + '%)');
setBar ('approvalApprovedBar',    d.approved_pct);

setText('approvalTotalCount',     d.total_final);
setText('approvalTaskLabel',      nama);
    
    const projectKey = projectSelect ? projectSelect.value : 'all';
    if (projectKey !== 'all' && PHP_HEALTH[projectKey]) {
        currentHealthData = PHP_HEALTH[projectKey];
    } else {
        currentHealthData = PHP_HEALTH['all'] ?? { week: [], month: [], year: [] };
    }
    
    renderHealthSvg(currentHealthPeriod);
}

function setText(id, val) {
    const el = document.getElementById(id);
    if (el) el.textContent = val;
}
function setBar(id, pct) {
    const el = document.getElementById(id);
    if (el) el.style.width = (parseFloat(pct) || 0) + '%';
}

/* ════════════════════════════════════════════════════════
   3. DROPDOWN PROJECT
════════════════════════════════════════════════════════ */
const projectSelect = document.getElementById('projectFilterSelect');
if (projectSelect) {
    projectSelect.addEventListener('change', function () {
        const key = this.value;
        renderProgressChart(key);
        updateStatusBars(key);
    });
}

/* ════════════════════════════════════════════════════════
   4. TREN PROJECT — AREA CHART
════════════════════════════════════════════════════════ */
const trendEl = document.querySelector('#trendProjectChart');
if (trendEl && typeof ApexCharts !== 'undefined') {
    const labels = PHP_TREND.map(d => d.label);
    const totals = PHP_TREND.map(d => d.total);

    trendChart = new ApexCharts(trendEl, {
        chart: { type: 'area', height: 280, toolbar: { show: false }, zoom: { enabled: false } },
        series: [{ name: 'Project Baru', data: totals }],
        colors: [C.blue],
        stroke: { curve: 'smooth', width: 3 },
        markers: { size: 5, colors: [C.blue], strokeColors: '#fff', strokeWidth: 2, hover: { size: 7 } },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] } },
        dataLabels: { enabled: false },
        grid: { borderColor: '#e0e0e0', strokeDashArray: 5 },
        xaxis: {
            categories: labels,
            labels: { style: { colors: '#566a7f', fontSize: '11px' }, rotate: -45 }
        },
        yaxis: {
            min: 0,
            tickAmount: 5,
            title: { text: 'Jumlah Project', style: { color: '#566a7f', fontSize: '11px' } },
            labels: { formatter: v => Math.round(v) }
        },
        tooltip: { y: { formatter: v => v + ' project' } },
        legend: { show: false }
    });
    trendChart.render();
}

/* ════════════════════════════════════════════════════════
   5. AKTIVITAS TASK — LINE CHART
════════════════════════════════════════════════════════ */
const activityEl = document.querySelector('#taskActivityChart');

function buildActivityChart(period) {
    if (!activityEl || typeof ApexCharts === 'undefined') return;
    const d = PHP_ACTIVITY[period] ?? PHP_ACTIVITY['week'];

    const labels  = d.map(r => r.label);
    const tepat   = d.map(r => r.tepat);
    const lambat  = d.map(r => r.terlambat);
    const sebelum = d.map(r => r.lebih_awal);

    const maxVal = Math.max(...tepat, ...lambat, ...sebelum, 1);
    const yMax   = Math.ceil(maxVal * 1.2) || 10;

    const cfg = {
        chart: {
            type: 'line', height: 260, toolbar: { show: false },
            zoom: { enabled: false },
            animations: { enabled: true, speed: 400 }
        },
        series: [
            { name: 'Tepat Waktu',      data: tepat   },
            { name: 'Terlambat',        data: lambat  },
            { name: 'Sebelum Deadline', data: sebelum },
        ],
        colors: [C.success, C.danger, C.purple],
        stroke: { curve: 'smooth', width: 2.5 },
        markers: {
            size: 4,
            colors: ['#fff', '#fff', '#fff'],
            strokeColors: [C.success, C.danger, C.purple],
            strokeWidth: 2.5, hover: { size: 6 }
        },
        dataLabels: { enabled: false },
        grid: { borderColor: '#e9ecef', strokeDashArray: 4 },
        xaxis: {
            categories: labels,
            labels: { style: { colors: '#566a7f', fontSize: '11px' }, rotate: -45 },
            axisBorder: { show: false }
        },
        yaxis: {
            min: 0, max: yMax, tickAmount: 4,
            title: { text: 'Jumlah Task', style: { color: '#566a7f', fontSize: '11px', fontWeight: 500 } },
            labels: { formatter: v => Math.round(v) }
        },
        legend: { show: false },
        tooltip: {
            shared: true, intersect: false,
            y: { formatter: v => v + ' task' }
        }
    };

    if (activityChart) { activityChart.destroy(); activityChart = null; }
    activityChart = new ApexCharts(activityEl, cfg);
    activityChart.render();
}

/* ════════════════════════════════════════════════════════
   6. HEALTH CHART — SVG
════════════════════════════════════════════════════════ */
function healthColor(pct) {
    if (pct >= 80) return '#2dbb7c';
    if (pct >= 50) return '#f5a623';
    return '#e74c3c';
}

function healthGradId(pct) {
    if (pct >= 80) return 'gradGreen';
    if (pct >= 50) return 'gradYellow';
    return 'gradRed';
}

function renderHealthSvg(period) {
    const raw = currentHealthData[period] ?? currentHealthData['week'];
    if (!raw || raw.length === 0) {
        updateHealthStatus(null);
        const xl = document.getElementById('xLabels');
        if (xl) xl.innerHTML = '';
        document.getElementById('chartContent').innerHTML = '';
        return;
    }
    
    const W = 600, H = 185, padL = 20, padR = 20;
    const n = raw.length;
    const usable = W - padL - padR;
    const step   = n > 1 ? usable / (n - 1) : 0;

    const toY = pct => H - (pct / 100) * (H - 10) - 5;

    const pts = raw.map((r, i) => ({
        x  : padL + i * step,
        y  : (r.pct !== null && r.pct !== undefined) ? toY(parseFloat(r.pct)) : null,
        pct: (r.pct !== null && r.pct !== undefined) ? parseFloat(r.pct) : null,
    }));

    const valid = pts.filter(p => p.y !== null);

    function bezierPath(points) {
        if (points.length < 2) return '';
        let d = `M ${points[0].x.toFixed(1)} ${points[0].y.toFixed(1)}`;
        for (let i = 1; i < points.length; i++) {
            const cp1x = ((points[i - 1].x + points[i].x) / 2).toFixed(1);
            const cp1y = points[i - 1].y.toFixed(1);
            const cp2x = cp1x;
            const cp2y = points[i].y.toFixed(1);
            d += ` C ${cp1x} ${cp1y} ${cp2x} ${cp2y} ${points[i].x.toFixed(1)} ${points[i].y.toFixed(1)}`;
        }
        return d;
    }

    const linePath = bezierPath(valid);
    const lastPct  = valid.length > 0 ? valid[valid.length - 1].pct : null;
    const gradId   = lastPct !== null ? healthGradId(lastPct) : 'gradGreen';

    let areaPath = '';
    if (valid.length >= 2 && linePath) {
        areaPath = linePath
            + ` L ${valid[valid.length - 1].x.toFixed(1)} ${H}`
            + ` L ${valid[0].x.toFixed(1)} ${H} Z`;
    }

    let segs = '';
    for (let i = 1; i < valid.length; i++) {
        const c = healthColor(Math.min(valid[i - 1].pct, valid[i].pct));
        const cp1x = ((valid[i - 1].x + valid[i].x) / 2).toFixed(1);
        const cp2x = cp1x;
        segs += `<path d="M ${valid[i-1].x.toFixed(1)} ${valid[i-1].y.toFixed(1)}`
              + ` C ${cp1x} ${valid[i-1].y.toFixed(1)} ${cp2x} ${valid[i].y.toFixed(1)}`
              + ` ${valid[i].x.toFixed(1)} ${valid[i].y.toFixed(1)}"`
              + ` fill="none" stroke="${c}" stroke-width="2.5" stroke-linecap="round"/>`;
    }

    let dots = '';
    valid.forEach(p => {
        const c = healthColor(p.pct);
        dots += `<circle cx="${p.x.toFixed(1)}" cy="${p.y.toFixed(1)}" r="4.5"`
              + ` fill="${c}" stroke="#fff" stroke-width="2"/>`;
        dots += `<text x="${p.x.toFixed(1)}" y="${(p.y - 11).toFixed(1)}"`
              + ` text-anchor="middle" font-size="9" fill="${c}"`
              + ` font-weight="600" font-family="sans-serif">${Math.round(p.pct)}%</text>`;
    });

    const area = areaPath
        ? `<path d="${areaPath}" fill="url(#${gradId})" opacity="0.85"/>`
        : '';

    document.getElementById('chartContent').innerHTML = area + segs + dots;

    const xl = document.getElementById('xLabels');
    xl.innerHTML = '';
    raw.forEach(r => {
        const s = document.createElement('span');
        s.textContent = r.label;
        xl.appendChild(s);
    });

    updateHealthStatus(lastPct);
}

function updateHealthStatus(pct) {
    const boxEl    = document.getElementById('healthStatusBox');
    const iconWrap = document.getElementById('healthIconWrap');
    const iconSvg  = document.getElementById('healthIconSvg');
    const labelEl  = document.getElementById('healthStatusLabel');
    const descEl   = document.getElementById('healthStatusDesc');

    if (pct === null || pct === undefined) {
        boxEl.style.background    = '#f8f9fa';
        iconWrap.style.background = '#e9ecef';
        iconSvg.setAttribute('stroke', '#8592a3');
        iconSvg.innerHTML = '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>';
        labelEl.style.color = '#566a7f';
        labelEl.textContent = 'Tidak ada data';
        descEl.style.color  = '#566a7f';
        descEl.textContent  = 'Belum ada task dengan deadline pada periode ini.';
        return;
    }

    const v = parseFloat(pct);

    if (v >= 80) {
        boxEl.style.background    = 'linear-gradient(135deg,#e8faf2 0%,#d4f5e5 100%)';
        iconWrap.style.background = '#c3efda';
        iconSvg.setAttribute('stroke', '#00a65a');
        iconSvg.innerHTML = '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 11 12 14 22 4"/>';
        labelEl.style.color = '#00a65a';
        labelEl.textContent = 'Baik';
        descEl.style.color  = '#00a65a';
        descEl.innerHTML    = `<strong>${Math.round(v)}% task</strong> diselesaikan tepat waktu atau lebih cepat dari deadline. Pertahankan kinerja yang baik!`;
    } else if (v >= 50) {
        boxEl.style.background    = 'linear-gradient(135deg,#fff8e1 0%,#fff3cd 100%)';
        iconWrap.style.background = '#ffe599';
        iconSvg.setAttribute('stroke', '#e0a800');
        iconSvg.innerHTML = '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>';
        labelEl.style.color = '#e0a800';
        labelEl.textContent = 'Perlu Perhatian';
        descEl.style.color  = '#e0a800';
        descEl.innerHTML    = `<strong>${Math.round(v)}% task</strong> tepat waktu. Ada beberapa task yang terlambat, perlu dipantau lebih lanjut.`;
    } else {
        boxEl.style.background    = 'linear-gradient(135deg,#fde8e8 0%,#fbd0d0 100%)';
        iconWrap.style.background = '#fdb0b0';
        iconSvg.setAttribute('stroke', '#e74c3c');
        iconSvg.innerHTML = '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>';
        labelEl.style.color = '#e74c3c';
        labelEl.textContent = 'Perlu Ditingkatkan';
        descEl.style.color  = '#e74c3c';
        descEl.innerHTML    = `Hanya <strong>${Math.round(v)}% task</strong> yang selesai tepat waktu. Banyak task mengalami keterlambatan.`;
    }
}

/* ════════════════════════════════════════════════════════
   7. TAB SWITCH
════════════════════════════════════════════════════════ */
window.switchActTab = function (btn, period) {
    document.querySelectorAll('.act-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    buildActivityChart(period);
};

window.switchHealthTab = function (btn, period) {
    document.querySelectorAll('#healthTabBtns .act-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentHealthPeriod = period;
    renderHealthSvg(period);
};

/* ════════════════════════════════════════════════════════
   INIT
════════════════════════════════════════════════════════ */
renderProgressChart('all');
updateStatusBars('all');
buildActivityChart('week');
renderHealthSvg('week');

if (typeof bootstrap !== 'undefined') {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });
}
</script>
@endpush