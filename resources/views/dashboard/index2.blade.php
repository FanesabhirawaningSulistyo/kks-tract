@extends('layouts.master')
@section('title', 'Dashboard Karyawan - PM System')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
<style>
/* ════════════════════════════════════════════════════════
   KARYAWAN DASHBOARD — index2
════════════════════════════════════════════════════════ */
/* ── Stat Cards ── */
.ky-stat-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    transition: transform .2s, box-shadow .2s;
    overflow: hidden;
}
.ky-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.11);
}
.ky-stat-icon {
    width: 52px; height: 52px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 22px;
}
.ky-stat-divider {
    border: none;
    border-top: 1px solid #f0f2f5;
    margin: 10px 0 8px;
}
.ky-sub-item {
    display: flex; justify-content: space-between; align-items: center;
    font-size: 12.5px; padding: 3px 0;
}
.ky-sub-label { display: flex; align-items: center; gap: 7px; color: #6c7a8d; }
.ky-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
.ky-sub-val { font-weight: 700; color: #2c3e50; font-size: 13px; }
/* ── Row 2 cards ── */
.ky-card {
    border: none; border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.07);
    height: 100%;
}
.ky-card .card-header {
    background: transparent;
    border-bottom: 1px solid #edf0f4;
    padding: 14px 18px 12px;
}
.ky-card .card-header h6 { margin: 0; font-size: 14px; }
/* ── Progress donut ── */
.ky-donut-wrap {
    display: flex; justify-content: center; align-items: center;
    position: relative;
}
.ky-donut-wrap::before {
    content: '';
    position: absolute;
    width: 190px; height: 190px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(105,108,255,.10) 0%, transparent 70%);
    pointer-events: none;
}
.ky-tooltip-note {
    background: linear-gradient(135deg, #fff8e1 0%, #fff3cd 100%);
    border: 1px solid #ffe082;
    border-radius: 8px;
    padding: 7px 12px;
    font-size: 11px; color: #7a6500;
    line-height: 1.5; text-align: center;
}
/* ── Status progress bars ── */
.ky-status-wrap { padding: 16px 18px; }
.ky-status-item { margin-bottom: 14px; }
.ky-status-item:last-of-type { margin-bottom: 0; }
.ky-status-hdr  { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
.ky-status-lbl  { font-size: 13px; font-weight: 500; color: #2c3e50; display: flex; align-items: center; gap: 7px; }
.ky-status-nums { display: flex; gap: 6px; font-size: 12px; }
.ky-status-n    { font-weight: 700; color: #2c3e50; }
.ky-status-p    { color: #8592a3; }
.ky-bar-track   { width: 100%; height: 7px; background: #edf0f4; border-radius: 10px; overflow: hidden; }
.ky-bar-fill    { height: 100%; border-radius: 10px; transition: width .4s ease; }
.ky-status-total {
    display: flex; justify-content: space-between; align-items: center;
    padding-top: 14px; margin-top: 6px; border-top: 1px solid #edf0f4;
    font-size: 13px; font-weight: 700;
}
.ky-status-total .ky-status-p { font-weight: 400; }
/* ── Top 5 table ── */
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
/* ── Activity tabs ── */
.ky-act-tabs {
    display: flex; gap: 2px;
    background: #edf0f4; border-radius: 8px; padding: 3px;
}
.ky-act-tab {
    border: none; background: transparent; border-radius: 6px;
    padding: 3px 12px; font-size: 12px; color: #566a7f;
    cursor: pointer; font-weight: 500; transition: all .2s;
}
.ky-act-tab.active { background: #696cff; color: #fff; font-weight: 600; }
/* ── Legend dot ── */
.ky-legend-dot {
    width: 10px; height: 10px; border-radius: 50%;
    display: inline-block; flex-shrink: 0;
}
/* ── Select dropdown ── */
.ky-select {
    border: 1px solid #d9dee3; border-radius: 6px;
    padding: 4px 10px; font-size: 12px; color: #2c3e50;
    background: #fff; cursor: pointer; outline: none;
    width: 100%; margin-top: 8px;
}
/* ── Empty state ── */
.ky-empty {
    text-align: center; padding: 40px 20px;
    color: #8592a3; font-size: 13px;
}
.ky-empty i { font-size: 36px; display: block; margin-bottom: 8px; opacity: .4; }
/* ── Approval Pie Legend ── */
.approval-pie-wrap {
    display: flex; align-items: center; gap: 20px;
}
.approval-pie-chart-side { flex-shrink: 0; }
.approval-legend-side { flex: 1; min-width: 0; }
.approval-legend { list-style: none; padding: 0; margin: 0; }
.approval-legend li {
    display: flex; justify-content: space-between; align-items: center;
    padding: 9px 0; border-bottom: 1px solid #f0f0f0; font-size: 13px;
}
.approval-legend li:last-child { border-bottom: none; }
.approval-legend .leg-label { display: flex; align-items: center; gap: 8px; color: #2c3e50; }
.approval-legend .leg-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
.approval-legend .leg-count { font-weight: 700; color: #2c3e50; }
.approval-legend .leg-pct { font-size: 11px; color: #8592a3; margin-left: 4px; }
.approval-total-row {
    display: flex; justify-content: space-between; align-items: center;
    padding-top: 12px; margin-top: 4px; border-top: 1px solid #e9ecef;
    font-size: 13px; font-weight: 700; color: #2c3e50;
}

/* ════════════════════════════════════════════════════════
   HEALTH CHART (KESEHATAN PROJECT)
════════════════════════════════════════════════════════ */
.ky-health-chart-wrap {
    position: relative; height: 185px; display: flex; gap: 0;
}
.ky-health-y-axis {
    width: 36px; flex-shrink: 0; position: relative;
}
.ky-health-y-axis span {
    position: absolute; right: 4px; font-size: 9px; color: #8592a3; line-height: 1;
}
.ky-health-chart-inner { flex: 1; position: relative; overflow: hidden; }
.ky-health-x-labels {
    display: flex; padding-left: 36px; margin-top: 3px;
    margin-bottom: 6px; font-size: 9px; color: #8592a3;
    justify-content: space-between;
}
.ky-health-legend-row {
    display: flex; gap: 16px; margin-bottom: 8px; font-size: 10px;
    color: #566a7f; align-items: center; flex-wrap: wrap;
}
.ky-health-status-box {
    border-radius: 0 0 12px 12px;
    border-top: 1px solid #e9ecef;
    padding: 12px 16px;
    transition: background .4s ease;
}
.ky-health-icon-wrap {
    width: 38px; height: 38px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    transition: background .4s ease;
}

/* ── Colors ── */
.text-ky-primary { color: #696cff !important; }
.text-ky-dark    { color: #2c3e50 !important; }
</style>
@endpush
@section('content')
{{-- PAGE HEADER --}}
<div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">
            Selamat datang, <span class="text-primary">{{ Auth::user()->nama }}!</span>
        </h4>
        <small class="text-muted">
            <i class="bx bx-folder-open me-1"></i>
            Terlibat dalam <strong class="text-primary">{{ $pmTotalProjek  }}</strong> project
        </small>
    </div>
    <div class="text-muted small">
        <i class="bx bx-calendar me-1"></i>{{ now()->translatedFormat('l, d F Y') }}
    </div>
</div>

{{-- ════════════════════════════════════════════════════════
     ROW 1 — STAT CARDS (3 card)
════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">
    {{-- Total Project --}}
    <div class="col-lg-4 col-md-6">
        <div class="card ky-stat-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="ky-stat-icon me-3" style="background:#ede7f6;">
                        <i class="bx bx-folder" style="color:#7c60c8;"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Total Project</div>
                        <h3 class="fw-bold mb-0 lh-1 text-ky-dark">{{ number_format($pmTotalProjek ) }}</h3>
                    </div>
                </div>
                <hr class="ky-stat-divider">
                <div class="ky-sub-item">
                    <div class="ky-sub-label"><span class="ky-dot" style="background:#8592a3;"></span> Pending</div>
                    <span class="ky-sub-val">{{ $pmTotalProjekPending }}</span>
                </div>
                <div class="ky-sub-item">
                    <div class="ky-sub-label"><span class="ky-dot" style="background:#696cff;"></span> Aktif</div>
                    <span class="ky-sub-val">{{ $pmTotalProjekAktif }}</span>
                </div>
                <div class="ky-sub-item">
                    <div class="ky-sub-label"><span class="ky-dot" style="background:#FEB019;"></span> Dikerjakan</div>
                    <span class="ky-sub-val">{{ $pmTotalProjekDikerjakan }}</span>
                </div>
                <div class="ky-sub-item">
                    <div class="ky-sub-label"><span class="ky-dot" style="background:#00E396;"></span> Selesai</div>
                    <span class="ky-sub-val">{{ $pmTotalProjekSelesai }}</span>
                </div>
            </div>
        </div>
    </div>
    {{-- Approval Task --}}
    <div class="col-lg-4 col-md-6">
        <div class="card ky-stat-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="ky-stat-icon me-3" style="background:#e0f7e9;">
                        <i class="bx bx-check-circle text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Approval Task</div>
                        <h3 class="fw-bold mb-0 lh-1 text-ky-dark">{{ number_format($pmApprovalTotal) }}</h3>
                    </div>
                </div>
                <hr class="ky-stat-divider">
                <div class="ky-sub-item">
                    <div class="ky-sub-label"><span class="ky-dot" style="background:#00E396;"></span> Approved</div>
                    <span class="ky-sub-val text-success">{{ $pmApprovalApproved }}</span>
                </div>
                <div class="ky-sub-item">
                    <div class="ky-sub-label"><span class="ky-dot" style="background:#696cff;"></span> Review</div>
                    <span class="ky-sub-val text-primary">{{ $pmApprovalReview }}</span>
                </div>
                <div class="ky-sub-item">
                    <div class="ky-sub-label"><span class="ky-dot" style="background:#FEB019;"></span> Revisi</div>
                    <span class="ky-sub-val text-warning">{{ $pmApprovalRevisi }}</span>
                </div>
            </div>
        </div>
    </div>
    {{-- Total Task --}}
    <div class="col-lg-4 col-md-6">
        <div class="card ky-stat-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="ky-stat-icon me-3" style="background:#fff3e0;">
                        <i class="bx bx-task text-warning"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Total Task</div>
                        <h3 class="fw-bold mb-0 lh-1 text-ky-dark">{{ number_format($pmTotalTask) }}</h3>
                    </div>
                </div>
                <hr class="ky-stat-divider">
                <div class="ky-sub-item">
                    <div class="ky-sub-label"><span class="ky-dot" style="background:#696cff;"></span> To Do</div>
                    <span class="ky-sub-val">{{ $pmTotalTaskTodo }}</span>
                </div>
                <div class="ky-sub-item">
                    <div class="ky-sub-label"><span class="ky-dot" style="background:#FEB019;"></span> In Progress</div>
                    <span class="ky-sub-val">{{ $pmTotalTaskInProgress }}</span>
                </div>
                <div class="ky-sub-item">
                    <div class="ky-sub-label"><span class="ky-dot" style="background:#00E396;"></span> Done</div>
                    <span class="ky-sub-val">{{ $pmTotalTaskDone }}</span>
                </div>
            </div>
        </div>
    </div>
</div>{{-- /ROW 1 --}}

{{-- ════════════════════════════════════════════════════════
     ROW 2 — PROGRESS PROJECT + STATUS TASK + TOP 5
════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">
    {{-- KIRI: Progress Project (donut chart) --}}
    <div class="col-lg-4 d-flex">
        <div class="card ky-card w-100">
            <div class="card-header">
                <h6 class="fw-bold text-ky-dark">Progress Project</h6>
                <div class="text-muted" style="font-size:11px; margin-top:2px;">
                    Persentase task selesai (Done &amp; Approved)
                </div>
                @if($myProjeks->isNotEmpty())
                <select class="ky-select" id="kyProjectSelect">
                    <option value="all">Semua Project Saya</option>
                    @foreach($myProjeks as $p)
                        <option value="{{ $p->id_projek }}">{{ $p->nama_projek }}</option>
                    @endforeach
                </select>
                @endif
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center py-4 px-3">
                @if($myProjeks->isNotEmpty())
                    <div class="ky-donut-wrap mb-3">
                        <div id="kyProgressChart"></div>
                    </div>
                    <div class="text-center w-100">
                        <p class="mb-1 fw-bold text-ky-dark fs-6" id="kyProjectName">Semua Project Saya</p>
                        <p class="mb-0 small text-muted" id="kyTotalTaskTxt">
                            Total {{ $pmProgressData['all']['total'] }} task
                        </p>
                        <p class="mb-3 small text-muted" id="kySelesaiTxt">
                            Selesai (Done &amp; Approved) {{ $pmProgressData['all']['selesai'] }} task
                        </p>
                        <div class="ky-tooltip-note">
                            Persentase dihitung dari total task Anda yang berstatus Done &amp; Approved.
                        </div>
                    </div>
                @else
                    <div class="ky-empty">
                        <i class="bx bx-folder-open"></i>
                        Belum ada project yang dikelola.
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    {{-- KANAN: Status Task + Top 5 --}}
    <div class="col-lg-8 d-flex flex-column" style="gap:16px;">
        {{-- Baris atas: Ringkasan Status Task + Final Status Task --}}
        <div class="row g-3">
            {{-- Ringkasan Status Task --}}
            <div class="col-md-6">
                <div class="card ky-card h-100">
                    <div class="card-header">
                        <h6 class="fw-bold text-ky-dark">Ringkasan Status Task</h6>
                        <div class="text-muted" id="kyStatusLabel" style="font-size:11px; margin-top:2px;">
                            Semua Project Saya
                        </div>
                    </div>
                    <div class="ky-status-wrap">
                        <div class="ky-status-item">
                            <div class="ky-status-hdr">
                                <div class="ky-status-lbl">
                                    <span class="ky-dot" style="background:#696cff;"></span> To Do
                                </div>
                                <div class="ky-status-nums">
                                    <span class="ky-status-n" id="kyTodoCount">{{ $pmStatusTaskData['all']['todo'] }}</span>
                                    <span class="ky-status-p" id="kyTodoPct">({{ $pmStatusTaskData['all']['todo_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="ky-bar-track">
                                <div class="ky-bar-fill" id="kyTodoBar"
                                     style="width:{{ $pmStatusTaskData['all']['todo_pct'] }}%; background:#696cff;"></div>
                            </div>
                        </div>
                        <div class="ky-status-item">
                            <div class="ky-status-hdr">
                                <div class="ky-status-lbl">
                                    <span class="ky-dot" style="background:#FEB019;"></span> In Progress
                                </div>
                                <div class="ky-status-nums">
                                    <span class="ky-status-n" id="kyInProgressCount">{{ $pmStatusTaskData['all']['inprogress'] }}</span>
                                    <span class="ky-status-p" id="kyInProgressPct">({{ $pmStatusTaskData['all']['inprogress_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="ky-bar-track">
                                <div class="ky-bar-fill" id="kyInProgressBar"
                                     style="width:{{ $pmStatusTaskData['all']['inprogress_pct'] }}%; background:#FEB019;"></div>
                            </div>
                        </div>
                        <div class="ky-status-item">
                            <div class="ky-status-hdr">
                                <div class="ky-status-lbl">
                                    <span class="ky-dot" style="background:#00E396;"></span> Done
                                </div>
                                <div class="ky-status-nums">
                                    <span class="ky-status-n" id="kyDoneCount">{{ $pmStatusTaskData['all']['done'] }}</span>
                                    <span class="ky-status-p" id="kyDonePct">({{ $pmStatusTaskData['all']['done_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="ky-bar-track">
                                <div class="ky-bar-fill" id="kyDoneBar"
                                     style="width:{{ $pmStatusTaskData['all']['done_pct'] }}%; background:#00E396;"></div>
                            </div>
                        </div>
                        <div class="ky-status-total">
                            <span class="text-ky-dark">Total</span>
                            <div>
                                <span class="ky-status-n" id="kyTotalStatus">{{ $pmStatusTaskData['all']['total'] }}</span>
                                <span class="ky-status-p ms-1">(100%)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Final Status Task --}}
            <div class="col-md-6">
                <div class="card ky-card h-100">
                    <div class="card-header">
                        <h6 class="fw-bold text-ky-dark">Final Status Task</h6>
                        <div class="text-muted" id="kyFinalLabel" style="font-size:11px; margin-top:2px;">
                            Semua Project Saya
                        </div>
                    </div>
                    <div class="ky-status-wrap">
                        <div class="ky-status-item">
                            <div class="ky-status-hdr">
                                <div class="ky-status-lbl">
                                    <span class="ky-dot" style="background:#775DD0;"></span> Review
                                </div>
                                <div class="ky-status-nums">
                                    <span class="ky-status-n" id="kyReviewCount">{{ $pmStatusTaskData['all']['preview'] }}</span>
                                    <span class="ky-status-p" id="kyReviewPct">({{ $pmStatusTaskData['all']['preview_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="ky-bar-track">
                                <div class="ky-bar-fill" id="kyReviewBar"
                                     style="width:{{ $pmStatusTaskData['all']['preview_pct'] }}%; background:#775DD0;"></div>
                            </div>
                        </div>
                        <div class="ky-status-item">
                            <div class="ky-status-hdr">
                                <div class="ky-status-lbl">
                                    <span class="ky-dot" style="background:#FEB019;"></span> Revisi
                                </div>
                                <div class="ky-status-nums">
                                    <span class="ky-status-n" id="kyRevisiCount">{{ $pmStatusTaskData['all']['revisi'] }}</span>
                                    <span class="ky-status-p" id="kyRevisiPct">({{ $pmStatusTaskData['all']['revisi_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="ky-bar-track">
                                <div class="ky-bar-fill" id="kyRevisiBar"
                                     style="width:{{ $pmStatusTaskData['all']['revisi_pct'] }}%; background:#FEB019;"></div>
                            </div>
                        </div>
                        <div class="ky-status-item">
                            <div class="ky-status-hdr">
                                <div class="ky-status-lbl">
                                    <span class="ky-dot" style="background:#00E396;"></span> Approved
                                </div>
                                <div class="ky-status-nums">
                                    <span class="ky-status-n" id="kyApprovedCount">{{ $pmStatusTaskData['all']['approved'] }}</span>
                                    <span class="ky-status-p" id="kyApprovedPct">({{ $pmStatusTaskData['all']['approved_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="ky-bar-track">
                                <div class="ky-bar-fill" id="kyApprovedBar"
                                     style="width:{{ $pmStatusTaskData['all']['approved_pct'] }}%; background:#00E396;"></div>
                            </div>
                        </div>
                        <div class="ky-status-total">
                            <span class="text-ky-dark">Total</span>
                            <div>
                                <span class="ky-status-n" id="kyTotalFinal">{{ $pmStatusTaskData['all']['total_final'] }}</span>
                                <span class="ky-status-p ms-1">(100%)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>{{-- /baris atas --}}
        
        {{-- Baris bawah: Top 5 Karyawan Terbaik --}}
        <div class="card ky-card flex-grow-1" style="height:auto;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-ky-dark">Top 5 Karyawan Terbaik</h6>
                <a href="{{ route('performa-karyawan.index') }}"
                   class="small text-primary text-decoration-none fw-semibold">
                    Lihat semua &rarr;
                </a>
            </div>
            <div class="card-body p-0">
                @if($pmTop5Karyawan->isEmpty())
                    <div class="ky-empty">
                        <i class="bx bx-user-x"></i>
                        Belum ada data karyawan dari project Anda.
                    </div>
                @else
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
                        @foreach($pmTop5Karyawan as $i => $k)
                        <tr>
                            <td class="ps-3 fw-bold text-muted">{{ $i + 1 }}</td>
                            <td>
                                <div class="fw-semibold" style="color:#2c3e50;">{{ $k['nama'] }}</div>
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
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>{{-- /top 5 --}}
    </div>{{-- /col-lg-8 --}}
</div>{{-- /ROW 2 --}}

{{-- ════════════════════════════════════════════════════════
     ROW 3 — KESEHATAN PROJECT (FULL WIDTH)
════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">
    <div class="col-lg-12">
        <div class="card ky-card h-100">
            <div class="card-header d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold mb-0 text-ky-dark">
                        Kesehatan Project
                        <span class="fw-normal text-muted" style="font-size:12px;">(Berdasarkan Deadline Task)</span>
                    </h6>
                    <small class="text-muted">Persentase task yang selesai tepat waktu atau sebelum deadline.</small>
                </div>
                <div class="ky-act-tabs" id="kyHealthTabBtns">
                    <button class="ky-act-tab active" onclick="kySwitchHealthTab(this,'week')">Minggu</button>
                    <button class="ky-act-tab" onclick="kySwitchHealthTab(this,'month')">Bulan</button>
                    <button class="ky-act-tab" onclick="kySwitchHealthTab(this,'year')">Tahun</button>
                </div>
            </div>
            <div class="card-body pt-3 pb-0 px-3">
                <div class="ky-health-legend-row">
                    <span style="display:flex;align-items:center;gap:5px;">
                        <span class="health-legend-line" style="width:24px;height:3px;border-radius:2px;background:#2dbb7c;"></span>
                        Performance (%)
                    </span>
                    <span style="margin-left:auto;font-size:10px;color:#8592a3;">
                        🟢 ≥80% Baik &nbsp; 🟡 50–79% Warning &nbsp; 🔴 &lt;50% Buruk
                    </span>
                </div>
                <div class="ky-health-chart-wrap">
                    <div class="ky-health-y-axis">
                        <span style="top:0;">100%</span>
                        <span style="top:25%;">75%</span>
                        <span style="top:50%;">50%</span>
                        <span style="top:75%;">25%</span>
                        <span style="bottom:0;">0%</span>
                    </div>
                    <div class="ky-health-chart-inner">
                        <svg id="kyHealthChartSvg" width="100%" height="185"
                             preserveAspectRatio="none" viewBox="0 0 600 185">
                            <defs>
                                <linearGradient id="kyGradGreen" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#2dbb7c" stop-opacity="0.45"/>
                                    <stop offset="100%" stop-color="#2dbb7c" stop-opacity="0.03"/>
                                </linearGradient>
                                <linearGradient id="kyGradYellow" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#f5a623" stop-opacity="0.45"/>
                                    <stop offset="100%" stop-color="#f5a623" stop-opacity="0.03"/>
                                </linearGradient>
                                <linearGradient id="kyGradRed" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#e74c3c" stop-opacity="0.45"/>
                                    <stop offset="100%" stop-color="#e74c3c" stop-opacity="0.03"/>
                                </linearGradient>
                                <clipPath id="kyChartClip">
                                    <rect x="0" y="0" width="600" height="185"/>
                                </clipPath>
                            </defs>
                            <line x1="0" y1="1"   x2="600" y2="1"   stroke="#e9ecef" stroke-width="0.5"/>
                            <line x1="0" y1="47"  x2="600" y2="47"  stroke="#e9ecef" stroke-width="0.5" stroke-dasharray="4 4"/>
                            <line x1="0" y1="93"  x2="600" y2="93"  stroke="#f5a623" stroke-width="1" stroke-dasharray="6 3" opacity="0.45"/>
                            <line x1="0" y1="139" x2="600" y2="139" stroke="#e9ecef" stroke-width="0.5" stroke-dasharray="4 4"/>
                            <line x1="0" y1="184" x2="600" y2="184" stroke="#e9ecef" stroke-width="0.5"/>
                            <g id="kyChartContent" clip-path="url(#kyChartClip)"></g>
                        </svg>
                    </div>
                </div>
                <div class="ky-health-x-labels" id="kyXLabels"></div>
            </div>
            <div class="ky-health-status-box" id="kyHealthStatusBox" style="background:#f8f9fa;">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="ky-health-icon-wrap" id="kyHealthIconWrap" style="background:#e9ecef;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="#8592a3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             id="kyHealthIconSvg">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="small text-muted">Status Kesehatan Project</div>
                        <div class="fw-bold small" id="kyHealthStatusLabel" style="color:#566a7f;">Memuat data...</div>
                    </div>
                    <div class="ms-auto text-end small" id="kyHealthStatusDesc"
                         style="max-width:350px;line-height:1.5;color:#566a7f;"></div>
                </div>
            </div>
        </div>
    </div>
</div>{{-- /ROW 3 --}}

{{-- ════════════════════════════════════════════════════════
     ROW 4 — AKTIVITAS TASK + PIE APPROVAL TASK
════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">
    {{-- Aktivitas Penyelesaian Task --}}
    <div class="col-lg-6">
        <div class="card ky-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-ky-dark mb-0">Aktivitas Penyelesaian Task</h6>
                <div class="ky-act-tabs">
                    <button class="ky-act-tab active" onclick="kySwitchTab(this,'week')">Minggu</button>
                    <button class="ky-act-tab" onclick="kySwitchTab(this,'month')">Bulan</button>
                    <button class="ky-act-tab" onclick="kySwitchTab(this,'year')">Tahun</button>
                </div>
            </div>
            <div class="card-body pt-2 pb-2">
                <div class="d-flex gap-3 mb-3 flex-wrap" style="font-size:12px;">
                    <span class="d-flex align-items-center gap-1">
                        <span class="ky-legend-dot" style="background:#00E396;"></span>
                        <span class="text-ky-dark">Tepat Waktu</span>
                    </span>
                    <span class="d-flex align-items-center gap-1">
                        <span class="ky-legend-dot" style="background:#FF4560;"></span>
                        <span class="text-ky-dark">Terlambat</span>
                    </span>
                    <span class="d-flex align-items-center gap-1">
                        <span class="ky-legend-dot" style="background:#775DD0;"></span>
                        <span class="text-ky-dark">Sebelum Deadline</span>
                    </span>
                </div>
                <div id="kyActivityChart"></div>
            </div>
        </div>
    </div>
    
    {{-- Pie Chart Approval Task --}}
    <div class="col-lg-6">
        <div class="card ky-card">
            <div class="card-header">
                <h6 class="fw-bold text-ky-dark mb-0">Distribusi Approval Task</h6>
                <div class="text-muted" style="font-size:11px; margin-top:2px;">
                    Status akhir seluruh task pada project Anda
                </div>
            </div>
            <div class="card-body d-flex flex-column justify-content-center py-3">
                @php
                    $approvalTotal = $pmApprovalReview + $pmApprovalRevisi + $pmApprovalApproved;
                    $pctReview   = $approvalTotal > 0 ? round(($pmApprovalReview   / $approvalTotal) * 100, 1) : 0;
                    $pctRevisi   = $approvalTotal > 0 ? round(($pmApprovalRevisi   / $approvalTotal) * 100, 1) : 0;
                    $pctApproved = $approvalTotal > 0 ? round(($pmApprovalApproved / $approvalTotal) * 100, 1) : 0;
                @endphp
                @if($approvalTotal > 0)
                    <div class="approval-pie-wrap">
                        <div class="approval-pie-chart-side">
                            <div id="kyApprovalPieChart"></div>
                        </div>
                        <div class="approval-legend-side">
                            <ul class="approval-legend">
                                <li>
                                    <div class="leg-label">
                                        <span class="leg-dot" style="background:#775DD0;"></span>
                                        Menunggu Review
                                    </div>
                                    <div>
                                        <span class="leg-count">{{ $pmApprovalReview }}</span>
                                        <span class="leg-pct">({{ $pctReview }}%)</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="leg-label">
                                        <span class="leg-dot" style="background:#FEB019;"></span>
                                        Revisi
                                    </div>
                                    <div>
                                        <span class="leg-count">{{ $pmApprovalRevisi }}</span>
                                        <span class="leg-pct">({{ $pctRevisi }}%)</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="leg-label">
                                        <span class="leg-dot" style="background:#00E396;"></span>
                                        Approved
                                    </div>
                                    <div>
                                        <span class="leg-count">{{ $pmApprovalApproved }}</span>
                                        <span class="leg-pct">({{ $pctApproved }}%)</span>
                                    </div>
                                </li>
                            </ul>
                            <div class="approval-total-row">
                                <span>Total</span>
                                <span>{{ $approvalTotal }} task</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="ky-empty">
                        <i class="bx bx-pie-chart-alt"></i>
                        Belum ada data approval task.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>{{-- /ROW 4 --}}
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
'use strict';
/* ─── DATA DARI PHP ─── */
const KY_PROGRESS = @json($pmProgressData);
const KY_STATUS   = @json($pmStatusTaskData);
const KY_ACTIVITY = {
    week:  @json($pmActivityWeek),
    month: @json($pmActivityMonth),
    year:  @json($pmActivityYear),
};

/* ─── DATA APPROVAL PIE ─── */
const KY_APPROVAL = {
    review   : {{ $pmApprovalReview }},
    revisi   : {{ $pmApprovalRevisi }},
    approved : {{ $pmApprovalApproved }},
};

/* ─── DATA HEALTH CHART (KESEHATAN PROJECT) PER PROJECT ─── */
const KY_HEALTH_ALL = @json($pmHealthDataPerProject ?? []);
let currentHealthData = KY_HEALTH_ALL['all'] ?? { week: [], month: [], year: [] };
let currentHealthPeriod = 'week';

/* ─── WARNA ─── */
const C = {
    primary : '#696cff',
    success : '#00E396',
    warning : '#FEB019',
    danger  : '#FF4560',
    purple  : '#775DD0',
    gray    : '#8592a3',
    green   : '#2dbb7c',
    yellow  : '#f5a623',
    red     : '#e74c3c',
};

/* ─── INSTANCE ─── */
let kyDonutChart    = null;
let kyActivityChart = null;
let kyApprovalPie   = null;

/* ════════════════════════════════════════════════════════
   1. DONUT PROGRESS PROJECT
════════════════════════════════════════════════════════ */
function renderKyDonut(key) {
    const data = KY_PROGRESS[key] ?? KY_PROGRESS['all'];
    const pct  = parseFloat(data.persen) || 0;
    if (kyDonutChart) { kyDonutChart.destroy(); kyDonutChart = null; }
    const el = document.querySelector('#kyProgressChart');
    if (!el || typeof ApexCharts === 'undefined') return;
    kyDonutChart = new ApexCharts(el, {
        chart: {
            type: 'donut', height: 220, width: 220,
            toolbar: { show: false },
            animations: { enabled: true, speed: 500 }
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
                            show: true, fontSize: '11px', fontWeight: 600,
                            color: C.primary, offsetY: -6,
                            formatter: () => 'Selesai'
                        },
                        value: {
                            show: true, fontSize: '26px', fontWeight: 800,
                            color: C.primary, offsetY: 8,
                            formatter: () => pct + '%'
                        },
                        total: {
                            show: true, showAlways: true,
                            label: 'Selesai', fontSize: '11px',
                            fontWeight: 600, color: C.primary,
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
    kyDonutChart.render();
    const n = document.getElementById('kyProjectName');
    const t = document.getElementById('kyTotalTaskTxt');
    const s = document.getElementById('kySelesaiTxt');
    if (n) n.textContent = data.nama ?? 'Semua Project Saya';
    if (t) t.textContent = 'Total ' + data.total + ' task';
    if (s) s.innerHTML   = 'Selesai (Done &amp; Approved) ' + data.selesai + ' task';
}

/* ════════════════════════════════════════════════════════
   2. STATUS + FINAL BARS
════════════════════════════════════════════════════════ */
function updateKyBars(key) {
    const d    = KY_STATUS[key]    ?? KY_STATUS['all'];
    const nama = (KY_PROGRESS[key] ?? KY_PROGRESS['all']).nama ?? 'Semua Project Saya';
    setTxt('kyStatusLabel',    nama);
    setTxt('kyFinalLabel',     nama);
    setTxt('kyTodoCount',       d.todo);
    setTxt('kyTodoPct',         '(' + d.todo_pct + '%)');
    setBar('kyTodoBar',         d.todo_pct);
    setTxt('kyInProgressCount', d.inprogress);
    setTxt('kyInProgressPct',   '(' + d.inprogress_pct + '%)');
    setBar('kyInProgressBar',   d.inprogress_pct);
    setTxt('kyDoneCount',       d.done);
    setTxt('kyDonePct',         '(' + d.done_pct + '%)');
    setBar('kyDoneBar',         d.done_pct);
    setTxt('kyTotalStatus',     d.total);
    setTxt('kyReviewCount',     d.preview);
    setTxt('kyReviewPct',       '(' + d.preview_pct + '%)');
    setBar('kyReviewBar',       d.preview_pct);
    setTxt('kyRevisiCount',     d.revisi);
    setTxt('kyRevisiPct',       '(' + d.revisi_pct + '%)');
    setBar('kyRevisiBar',       d.revisi_pct);
    setTxt('kyApprovedCount',   d.approved);
    setTxt('kyApprovedPct',     '(' + d.approved_pct + '%)');
    setBar('kyApprovedBar',     d.approved_pct);
    setTxt('kyTotalFinal',      d.total_final);
}

function setTxt(id, val) {
    const el = document.getElementById(id);
    if (el) el.textContent = val;
}
function setBar(id, pct) {
    const el = document.getElementById(id);
    if (el) el.style.width = (parseFloat(pct) || 0) + '%';
}

/* ════════════════════════════════════════════════════════
   3. DROPDOWN PROJECT - UPDATE SEMUA (PROGRESS, STATUS, HEALTH)
════════════════════════════════════════════════════════ */
const kySelect = document.getElementById('kyProjectSelect');
if (kySelect) {
    kySelect.addEventListener('change', function () {
        const key = this.value;
        renderKyDonut(key);
        updateKyBars(key);
        
        // Update health chart berdasarkan project yang dipilih
        if (KY_HEALTH_ALL[key]) {
            currentHealthData = KY_HEALTH_ALL[key];
        } else {
            currentHealthData = KY_HEALTH_ALL['all'] ?? { week: [], month: [], year: [] };
        }
        renderKyHealthChart(currentHealthPeriod);
        
        // Update status box dengan nama project yang dipilih
        updateHealthStatusBoxTitle(key);
    });
}

function updateHealthStatusBoxTitle(projectKey) {
    const statusDescEl = document.getElementById('kyHealthStatusDesc');
    if (!statusDescEl) return;
    
    let projectName = 'Semua Project';
    if (projectKey !== 'all') {
        const selectEl = document.getElementById('kyProjectSelect');
        if (selectEl) {
            const selectedOption = selectEl.options[selectEl.selectedIndex];
            projectName = selectedOption ? selectedOption.text : 'Semua Project';
        }
    }
    
    // Store project name untuk digunakan di update status
    statusDescEl.setAttribute('data-project-name', projectName);
}

/* ════════════════════════════════════════════════════════
   4. PIE CHART APPROVAL TASK
════════════════════════════════════════════════════════ */
const approvalPieEl = document.querySelector('#kyApprovalPieChart');
if (approvalPieEl && typeof ApexCharts !== 'undefined') {
    const total = KY_APPROVAL.review + KY_APPROVAL.revisi + KY_APPROVAL.approved;
    if (total > 0) {
        kyApprovalPie = new ApexCharts(approvalPieEl, {
            chart: {
                type: 'pie',
                height: 220,
                width: 220,
                toolbar: { show: false },
                animations: { enabled: true, speed: 600 }
            },
            series: [KY_APPROVAL.review, KY_APPROVAL.revisi, KY_APPROVAL.approved],
            labels: ['Menunggu Review', 'Revisi', 'Approved'],
            colors: [C.purple, C.warning, C.success],
            stroke: { width: 3, colors: ['#fff'] },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return Math.round(val) + '%';
                },
                style: {
                    fontSize: '12px',
                    fontWeight: 700,
                    colors: ['#fff']
                },
                dropShadow: { enabled: false }
            },
            legend: { show: false },
            tooltip: {
                y: {
                    formatter: function(val, opts) {
                        const total = opts.globals.seriesTotals.reduce((a, b) => a + b, 0);
                        const pct   = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                        return val + ' task (' + pct + '%)';
                    }
                }
            }
        });
        kyApprovalPie.render();
    }
}

/* ════════════════════════════════════════════════════════
   5. AKTIVITAS LINE CHART
════════════════════════════════════════════════════════ */
function buildKyActivityChart(period) {
    const el = document.querySelector('#kyActivityChart');
    if (!el || typeof ApexCharts === 'undefined') return;
    const d       = KY_ACTIVITY[period] ?? KY_ACTIVITY['week'];
    const labels  = d.map(r => r.label);
    const tepat   = d.map(r => r.tepat);
    const lambat  = d.map(r => r.terlambat);
    const sebelum = d.map(r => r.lebih_awal);
    const maxVal = Math.max(...tepat, ...lambat, ...sebelum, 1);
    const yMax   = Math.ceil(maxVal * 1.25) || 10;
    const cfg = {
        chart: {
            type: 'line', height: 250,
            toolbar: { show: false }, zoom: { enabled: false },
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
        grid: { borderColor: '#edf0f4', strokeDashArray: 4 },
        xaxis: {
            categories: labels,
            labels: { style: { colors: '#8592a3', fontSize: '11px' }, rotate: -30 },
            axisBorder: { show: false }
        },
        yaxis: {
            min: 0, max: yMax, tickAmount: 4,
            title: { text: 'Jumlah Task', style: { color: '#8592a3', fontSize: '11px' } },
            labels: { formatter: v => Math.round(v) }
        },
        legend: { show: false },
        tooltip: { shared: true, intersect: false, y: { formatter: v => v + ' task' } }
    };
    if (kyActivityChart) { kyActivityChart.destroy(); kyActivityChart = null; }
    kyActivityChart = new ApexCharts(el, cfg);
    kyActivityChart.render();
}

/* ════════════════════════════════════════════════════════
   6. HEALTH CHART (KESEHATAN PROJECT) SVG - TERHUBUNG DROPDOWN
════════════════════════════════════════════════════════ */
function kyHealthColor(pct) {
    if (pct >= 80) return C.green;
    if (pct >= 50) return C.yellow;
    return C.red;
}

function kyHealthGradId(pct) {
    if (pct >= 80) return 'kyGradGreen';
    if (pct >= 50) return 'kyGradYellow';
    return 'kyGradRed';
}

function renderKyHealthChart(period) {
    const raw = currentHealthData[period] ?? currentHealthData['week'];
    if (!raw || raw.length === 0) {
        updateKyHealthStatus(null);
        const xl = document.getElementById('kyXLabels');
        if (xl) xl.innerHTML = '';
        document.getElementById('kyChartContent').innerHTML = '';
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
    const gradId   = lastPct !== null ? kyHealthGradId(lastPct) : 'kyGradGreen';
    let areaPath = '';
    if (valid.length >= 2 && linePath) {
        areaPath = linePath
            + ` L ${valid[valid.length - 1].x.toFixed(1)} ${H}`
            + ` L ${valid[0].x.toFixed(1)} ${H} Z`;
    }
    
    let segs = '';
    for (let i = 1; i < valid.length; i++) {
        const c = kyHealthColor(Math.min(valid[i - 1].pct, valid[i].pct));
        const cp1x = ((valid[i - 1].x + valid[i].x) / 2).toFixed(1);
        const cp2x = cp1x;
        segs += `<path d="M ${valid[i-1].x.toFixed(1)} ${valid[i-1].y.toFixed(1)}`
              + ` C ${cp1x} ${valid[i-1].y.toFixed(1)} ${cp2x} ${valid[i].y.toFixed(1)}`
              + ` ${valid[i].x.toFixed(1)} ${valid[i].y.toFixed(1)}"`
              + ` fill="none" stroke="${c}" stroke-width="2.5" stroke-linecap="round"/>`;
    }
    
    let dots = '';
    valid.forEach(p => {
        const c = kyHealthColor(p.pct);
        dots += `<circle cx="${p.x.toFixed(1)}" cy="${p.y.toFixed(1)}" r="4.5"`
              + ` fill="${c}" stroke="#fff" stroke-width="2"/>`;
        dots += `<text x="${p.x.toFixed(1)}" y="${(p.y - 11).toFixed(1)}"`
              + ` text-anchor="middle" font-size="9" fill="${c}"`
              + ` font-weight="600" font-family="sans-serif">${Math.round(p.pct)}%</text>`;
    });
    
    const area = areaPath
        ? `<path d="${areaPath}" fill="url(#${gradId})" opacity="0.85"/>`
        : '';
    
    document.getElementById('kyChartContent').innerHTML = area + segs + dots;
    
    const xl = document.getElementById('kyXLabels');
    xl.innerHTML = '';
    raw.forEach(r => {
        const s = document.createElement('span');
        s.textContent = r.label;
        xl.appendChild(s);
    });
    
    updateKyHealthStatus(lastPct);
}

function updateKyHealthStatus(pct) {
    const boxEl    = document.getElementById('kyHealthStatusBox');
    const iconWrap = document.getElementById('kyHealthIconWrap');
    const iconSvg  = document.getElementById('kyHealthIconSvg');
    const labelEl  = document.getElementById('kyHealthStatusLabel');
    const descEl   = document.getElementById('kyHealthStatusDesc');
    
    // Ambil nama project yang sedang dipilih
    const selectEl = document.getElementById('kyProjectSelect');
    let projectName = 'Semua Project';
    if (selectEl && selectEl.value !== 'all') {
        const selectedOption = selectEl.options[selectEl.selectedIndex];
        projectName = selectedOption ? selectedOption.text : 'Semua Project';
    }
    
    if (pct === null || pct === undefined) {
        boxEl.style.background    = '#f8f9fa';
        iconWrap.style.background = '#e9ecef';
        iconSvg.setAttribute('stroke', '#8592a3');
        iconSvg.innerHTML = '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>';
        labelEl.style.color = '#566a7f';
        labelEl.textContent = 'Tidak ada data';
        descEl.style.color  = '#566a7f';
        descEl.innerHTML    = `<strong>${projectName}</strong><br>Belum ada task dengan deadline pada periode ini.`;
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
        descEl.innerHTML    = `<strong>${projectName}</strong><br><strong>${Math.round(v)}% task</strong> diselesaikan tepat waktu atau lebih cepat dari deadline. Pertahankan kinerja yang baik!`;
    } else if (v >= 50) {
        boxEl.style.background    = 'linear-gradient(135deg,#fff8e1 0%,#fff3cd 100%)';
        iconWrap.style.background = '#ffe599';
        iconSvg.setAttribute('stroke', '#e0a800');
        iconSvg.innerHTML = '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>';
        labelEl.style.color = '#e0a800';
        labelEl.textContent = 'Perlu Perhatian';
        descEl.style.color  = '#e0a800';
        descEl.innerHTML    = `<strong>${projectName}</strong><br><strong>${Math.round(v)}% task</strong> tepat waktu. Ada beberapa task yang terlambat, perlu dipantau lebih lanjut.`;
    } else {
        boxEl.style.background    = 'linear-gradient(135deg,#fde8e8 0%,#fbd0d0 100%)';
        iconWrap.style.background = '#fdb0b0';
        iconSvg.setAttribute('stroke', '#e74c3c');
        iconSvg.innerHTML = '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>';
        labelEl.style.color = '#e74c3c';
        labelEl.textContent = 'Perlu Ditingkatkan';
        descEl.style.color  = '#e74c3c';
        descEl.innerHTML    = `<strong>${projectName}</strong><br>Hanya <strong>${Math.round(v)}% task</strong> yang selesai tepat waktu. Banyak task mengalami keterlambatan.`;
    }
}

/* ════════════════════════════════════════════════════════
   7. TAB SWITCH
════════════════════════════════════════════════════════ */
window.kySwitchTab = function (btn, period) {
    document.querySelectorAll('.ky-act-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    buildKyActivityChart(period);
};

window.kySwitchHealthTab = function (btn, period) {
    document.querySelectorAll('#kyHealthTabBtns .ky-act-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentHealthPeriod = period;
    renderKyHealthChart(period);
};

/* ════════════════════════════════════════════════════════
   INIT
════════════════════════════════════════════════════════ */
renderKyDonut('all');
updateKyBars('all');
buildKyActivityChart('week');
renderKyHealthChart('week');

if (typeof bootstrap !== 'undefined') {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });
}
</script>
@endpush