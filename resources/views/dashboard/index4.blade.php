@extends('layouts.master')
@section('title', 'Dashboard Klien - Klien System')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
<style>
/* ===== BASE CARDS ===== */
.stat-card  { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); }
.chart-card { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); height: 100%; }
.row-2-card { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); }
.chart-card .card-header,
.row-2-card .card-header { background: transparent; border-bottom: 1px solid #e9ecef; padding: 14px 18px; }
.chart-card .card-header h6,
.row-2-card .card-header h6 { margin-bottom: 0; }

/* ===== STAT ICON BOX ===== */
.stat-icon-box {
    width: 46px; height: 46px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}

/* ===== STAT LIST ===== */
.stat-list { border-top: 1px solid #e9ecef; padding-top: 8px; }
.stat-item { display: flex; align-items: center; justify-content: space-between; padding: 5px 0; font-size: 12px; }
.stat-label { display: flex; align-items: center; gap: 8px; color: #566a7f; }
.bullet { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }
.bullet-primary   { background: #696cff; }
.bullet-success   { background: #00E396; }
.bullet-warning   { background: #FEB019; }
.bullet-danger    { background: #FF4560; }
.bullet-secondary { background: #8592a3; }
.text-dark-custom { color: #2c3e50 !important; }

/* ===== PROGRESS CHART ===== */
.chart-container { display: flex; justify-content: center; align-items: center; position: relative; }
.chart-container::before {
    content: '';
    position: absolute; width: 200px; height: 200px; border-radius: 50%;
    background: radial-gradient(circle, rgba(105,108,255,0.12) 0%, rgba(105,108,255,0) 70%);
    pointer-events: none; z-index: 0;
}
#karyawanProgressChart { position: relative; z-index: 1; }
.tooltip-note {
    background: linear-gradient(135deg, #fff8e1 0%, #fff3cd 100%);
    border: 1px solid #ffe082; border-radius: 8px;
    padding: 8px 12px; font-size: 11px; color: #7a6500;
    line-height: 1.5; text-align: center; box-shadow: 0 2px 6px rgba(255,200,0,0.1);
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

/* ===== HEADER SELECT ===== */
.header-select {
    border: 1px solid #d9dee3; border-radius: 6px;
    padding: 4px 10px; font-size: 12px; color: #2c3e50;
    background: #fff; cursor: pointer; outline: none;
}

/* ===== TABS ===== */
.act-tabs { display: flex; gap: 2px; background: #f0f1f5; border-radius: 8px; padding: 3px; }
.act-tab {
    border: none; background: transparent; border-radius: 6px;
    padding: 3px 12px; font-size: 12px; color: #566a7f;
    cursor: pointer; font-weight: 500; transition: all .2s;
}
.act-tab.active { background: #696cff; color: #fff; font-weight: 600; }

/* ===== HEALTH STATUS BOX ===== */
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

/* ===== LEGEND DOT ===== */
.legend-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; flex-shrink: 0; }

/* ===== NOMINAL BADGE ===== */
.nominal-badge {
    background: linear-gradient(135deg, #e7e8ff 0%, #d4d6ff 100%);
    border: 1px solid #b0b3ff; border-radius: 8px;
    padding: 4px 10px; font-size: 12px; color: #696cff; font-weight: 700;
}

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

/* ===== PAYMENT CARD ===== */
.pay-summary-body {
    padding: 18px 20px;
}
.pay-donut-wrap {
    position: relative; width: 150px; height: 150px; flex-shrink: 0;
}
.pay-donut-center {
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%); text-align: center; pointer-events: none;
}
.pay-donut-pct { font-size: 22px; font-weight: 700; color: #2c3e50; line-height: 1; }
.pay-donut-lbl { font-size: 10px; color: #8592a3; margin-top: 2px; }
.pay-legend-row {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 0; border-bottom: 1px solid #e9ecef;
}
.pay-legend-row:last-child { border-bottom: none; }
.pay-legend-dot { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }
.pay-legend-name { font-size: 13px; font-weight: 600; color: #2c3e50; }
.pay-legend-amount { font-size: 11px; color: #8592a3; margin-top: 1px; }
.pay-legend-pct { margin-left: auto; font-size: 13px; font-weight: 700; }
.pay-total-box {
    background: linear-gradient(135deg, #f0f4ff 0%, #e8ecff 100%);
    border: 1px solid #d0d4ff; border-radius: 10px;
    padding: 14px 18px; display: flex; align-items: center; gap: 14px;
    margin-top: 16px;
}
.pay-total-icon { font-size: 28px; color: #696cff; flex-shrink: 0; }
.pay-total-label { font-size: 11px; color: #8592a3; }
.pay-total-amount { font-size: 18px; font-weight: 700; color: #696cff; line-height: 1.2; }
.pay-total-sub { font-size: 11px; color: #8592a3; margin-top: 2px; }
.pay-progress-section { margin-left: auto; text-align: right; flex-shrink: 0; }
.pay-progress-pct { font-size: 18px; font-weight: 700; color: #00a65a; }
.pay-progress-lbl { font-size: 10px; color: #8592a3; }
</style>
@endpush

@section('content')

{{-- ────────────────────────────────────────────────────
     PAGE HEADER
──────────────────────────────────────────────────── --}}
<div class="mb-4">
    <h4 class="fw-bold mb-1">
        Selamat datang, <span class="text-primary">{{ Auth::user()->nama }}!</span>
    </h4>
    <small class="text-muted">
        Ringkasan aktivitas task dan project Anda
        &nbsp;·&nbsp;
        <span class="nominal-badge">{{ $totalProjek }} Project Bergabung</span>
    </small>
</div>

{{-- ════════════════════════════════════════════════════
     SECTION 1 — STAT CARDS
════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- Card 1: Total Project --}}
    <div class="col-lg-6">
        <div class="card stat-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="stat-icon-box me-3" style="background:#ede7f6;">
                        <i class="bx bx-folder-open fs-4" style="color:#7c60c8;"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Total Project</div>
                        <h3 class="fw-bold mb-0 lh-1">{{ number_format($totalProjek) }}</h3>
                    </div>
                </div>
                <div class="stat-list mt-2">
                    <div class="stat-item">
                        <div class="stat-label"><span class="bullet bullet-secondary"></span> Pending</div>
                        <span class="fw-bold">{{ $totalPending }}</span>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><span class="bullet bullet-primary"></span> Aktif</div>
                        <span class="fw-bold">{{ $totalAktif }}</span>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><span class="bullet bullet-warning"></span> Dikerjakan</div>
                        <span class="fw-bold">{{ $totalDikerjakan }}</span>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><span class="bullet bullet-success"></span> Selesai</div>
                        <span class="fw-bold">{{ $totalSelesai }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Status Pembayaran --}}
    <div class="col-lg-6">
        <div class="card stat-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="stat-icon-box me-3" style="background:#e8f0fe;">
                        <i class="bx bx-credit-card fs-4" style="color:#1a73e8;"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Total Nominal Project</div>
                        <h3 class="fw-bold mb-0 lh-1 text-primary">Rp {{ number_format($totalNominal, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div style="background:linear-gradient(135deg,#f8f9fd 0%,#f0f2f6 100%);border-radius:10px;padding:12px;margin-top:8px;">
                    <div class="row g-2">
                        <div class="col-6">
                            <div style="font-size:11px;color:#566a7f;margin-bottom:3px;">
                                <i class="bx bx-check-circle text-success me-1"></i> Total Terbayar
                            </div>
                            <div style="font-size:18px;font-weight:800;color:#00a65a;letter-spacing:-0.5px;">
                                Rp {{ number_format($totalTerbayar, 0, ',', '.') }}
                            </div>
                            <small class="text-muted">({{ $totalTerbayarPersen }}% dari total)</small>
                        </div>
                        <div class="col-6">
                            <div style="font-size:11px;color:#566a7f;margin-bottom:3px;">
                                <i class="bx bx-time text-warning me-1"></i> Belum Terbayar
                            </div>
                            <div style="font-size:18px;font-weight:800;color:#e0a800;letter-spacing:-0.5px;">
                                Rp {{ number_format($totalBelumTerbayar, 0, ',', '.') }}
                            </div>
                            <small class="text-muted">({{ $totalBelumTerbayarPersen }}% dari total)</small>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height:8px;border-radius:10px;background:#e9ecef;">
                            <div class="progress-bar bg-success"
                                 style="width:{{ $totalTerbayarPersen }}%;border-radius:10px;"
                                 role="progressbar"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-2 text-center">
                    <small class="text-muted">
                        <i class="bx bx-info-circle me-1"></i>
                        Dari <strong>{{ $totalProjek }}</strong> project yang Anda ikuti
                    </small>
                </div>
            </div>
        </div>
    </div>

</div>{{-- /SECTION 1 --}}

{{-- ════════════════════════════════════════════════════
     SECTION 2 — PROGRESS + STATUS TASK + HEALTH CHART
════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- KIRI: Progress Project (donut chart) --}}
    <div class="col-lg-4 d-flex">
        <div class="card row-2-card w-100">
            <div class="card-header">
                <h6 class="fw-bold mb-0 text-dark-custom">Progress Project</h6>
                <div class="text-muted" style="font-size:11px;margin-top:2px;">
                    Persentase task selesai (Done &amp; Approved)
                </div>
                <select class="header-select mt-2 w-100" id="karyawanProjectFilterSelect">
                    <option value="all">Semua Project</option>
                    @foreach($allProjeks as $p)
                        <option value="{{ $p->id_projek }}">{{ $p->nama_projek }}</option>
                    @endforeach
                </select>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center py-4 px-3">
                <div class="chart-container mb-3">
                    <div id="karyawanProgressChart"></div>
                </div>
                <div class="text-center w-100">
                    <p class="mb-1 fw-bold text-dark-custom fs-6" id="karyawanProjectNameDisplay">Semua Project</p>
                    <p class="mb-0 small text-muted" id="karyawanTotalTaskDisplay">
                        Total {{ $progressData['all']['total'] }} task
                    </p>
                    <p class="mb-3 small text-muted" id="karyawanCompletedTaskDisplay">
                        Selesai (Done &amp; Approved) {{ $progressData['all']['selesai'] }} task
                    </p>
                    <div class="tooltip-note">
                        Persentase dihitung dari total task yang berstatus Done &amp; Approved.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KANAN: Status Task + Health Chart --}}
    <div class="col-lg-8 d-flex flex-column" style="gap:16px;">

        {{-- Baris atas: Ringkasan Status Task + Final Status Task --}}
        <div class="row g-3">

            {{-- Ringkasan Status Task --}}
            <div class="col-md-6">
                <div class="card row-2-card h-100">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0 text-dark-custom">Ringkasan Status Task</h6>
                        <div class="text-muted" id="karyawanStatusTaskLabel" style="font-size:11px;margin-top:2px;">Semua Project</div>
                    </div>
                    <div class="status-container">
                        <div class="status-item">
                            <div class="status-header">
                                <div class="status-label">To Do</div>
                                <div class="status-values">
                                    <span class="status-number" id="kyTodoCount">{{ $statusTaskData['all']['todo'] }}</span>
                                    <span class="status-percent" id="kyTodoPercent">({{ $statusTaskData['all']['todo_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" id="kyTodoBar"
                                     style="width:{{ $statusTaskData['all']['todo_pct'] }}%;background:#696cff;"></div>
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="status-header">
                                <div class="status-label">In Progress</div>
                                <div class="status-values">
                                    <span class="status-number" id="kyProgressCount">{{ $statusTaskData['all']['inprogress'] }}</span>
                                    <span class="status-percent" id="kyProgressPercent">({{ $statusTaskData['all']['inprogress_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" id="kyProgressBar"
                                     style="width:{{ $statusTaskData['all']['inprogress_pct'] }}%;background:#FEB019;"></div>
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="status-header">
                                <div class="status-label">Done</div>
                                <div class="status-values">
                                    <span class="status-number" id="kyDoneCount">{{ $statusTaskData['all']['done'] }}</span>
                                    <span class="status-percent" id="kyDonePercent">({{ $statusTaskData['all']['done_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" id="kyDoneBar"
                                     style="width:{{ $statusTaskData['all']['done_pct'] }}%;background:#00E396;"></div>
                            </div>
                        </div>
                        <div class="status-total">
                            <span class="status-total-label">Total</span>
                            <div>
                                <span class="status-total-number" id="kyTotalStatusCount">{{ $statusTaskData['all']['total'] }}</span>
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
                        <div class="text-muted" id="karyawanFinalStatusLabel" style="font-size:11px;margin-top:2px;">Semua Project</div>
                    </div>
                    <div class="status-container">
                        <div class="status-item">
                            <div class="status-header">
                                <div class="status-label">Review</div>
                                <div class="status-values">
                                    <span class="status-number" id="kyPreviewCount">{{ $statusTaskData['all']['preview'] }}</span>
                                    <span class="status-percent" id="kyPreviewPercent">({{ $statusTaskData['all']['preview_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" id="kyPreviewBar"
                                     style="width:{{ $statusTaskData['all']['preview_pct'] }}%;background:#775DD0;"></div>
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="status-header">
                                <div class="status-label">Revisi</div>
                                <div class="status-values">
                                    <span class="status-number" id="kyRevisiCount">{{ $statusTaskData['all']['revisi'] }}</span>
                                    <span class="status-percent" id="kyRevisiPercent">({{ $statusTaskData['all']['revisi_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" id="kyRevisiBar"
                                     style="width:{{ $statusTaskData['all']['revisi_pct'] }}%;background:#FEB019;"></div>
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="status-header">
                                <div class="status-label">Approved</div>
                                <div class="status-values">
                                    <span class="status-number" id="kyApprovedCount">{{ $statusTaskData['all']['approved'] }}</span>
                                    <span class="status-percent" id="kyApprovedPercent">({{ $statusTaskData['all']['approved_pct'] }}%)</span>
                                </div>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" id="kyApprovedBar"
                                     style="width:{{ $statusTaskData['all']['approved_pct'] }}%;background:#00E396;"></div>
                            </div>
                        </div>
                        <div class="status-total">
                            <span class="status-total-label">Total</span>
                            <div>
                                <span class="status-total-number" id="kyTotalFinalCount">{{ $statusTaskData['all']['total_final'] }}</span>
                                <span class="status-total-percent ms-1">(100%)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /baris atas --}}

        {{-- Baris bawah: Kesehatan Project Chart (SVG Custom) --}}
        <div class="card row-2-card flex-grow-1">
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
                <div class="act-tabs" id="kyHealthTabBtns">
                    <button class="act-tab active" onclick="switchKyHealthTab(this,'week')">Minggu</button>
                    <button class="act-tab" onclick="switchKyHealthTab(this,'month')">Bulan</button>
                    <button class="act-tab" onclick="switchKyHealthTab(this,'year')">Tahun</button>
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
                    {{-- Y-axis labels --}}
                    <div class="health-y-axis">
                        <span style="top:0;">100%</span>
                        <span style="top:25%;">75%</span>
                        <span style="top:50%;">50%</span>
                        <span style="top:75%;">25%</span>
                        <span style="bottom:0;">0%</span>
                    </div>
                    {{-- SVG chart --}}
                    <div class="health-chart-inner">
                        <svg id="kyHealthSvg" width="100%" height="185"
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
                            {{-- Grid lines --}}
                            <line x1="0" y1="1"   x2="600" y2="1"   stroke="#e9ecef" stroke-width="0.5"/>
                            <line x1="0" y1="47"  x2="600" y2="47"  stroke="#e9ecef" stroke-width="0.5" stroke-dasharray="4 4"/>
                            {{-- 50% threshold (warning line) --}}
                            <line x1="0" y1="93"  x2="600" y2="93"  stroke="#f5a623" stroke-width="1" stroke-dasharray="6 3" opacity="0.45"/>
                            <line x1="0" y1="139" x2="600" y2="139" stroke="#e9ecef" stroke-width="0.5" stroke-dasharray="4 4"/>
                            <line x1="0" y1="184" x2="600" y2="184" stroke="#e9ecef" stroke-width="0.5"/>
                            <g id="kyChartContent" clip-path="url(#kyChartClip)"></g>
                        </svg>
                    </div>
                </div>

                {{-- X labels --}}
                <div class="health-x-labels" id="kyXLabels"></div>

            </div>{{-- /card-body --}}

            {{-- Status bar bawah --}}
            <div class="health-status-box" id="kyHealthStatusBox" style="background:#f8f9fa;">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="health-icon-wrap" id="kyHealthIconWrap" style="background:#e9ecef;">
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
                         style="max-width:280px;line-height:1.5;color:#566a7f;"></div>
                </div>
            </div>

        </div>{{-- /health card --}}

    </div>{{-- /col-lg-8 --}}

</div>{{-- /SECTION 2 --}}

{{-- ════════════════════════════════════════════════════
     SECTION 3 — PAYMENT CHARTS
════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- Kolom 1: Ringkasan Pembayaran --}}
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header">
                <h6 class="fw-bold mb-0 text-dark-custom">Ringkasan Pembayaran</h6>
                <small class="text-muted">Total nilai pembayaran seluruh project yang Anda ikuti</small>
            </div>
            <div class="pay-summary-body">

                {{-- Donut + Legend --}}
                <div class="d-flex align-items-center gap-4 flex-wrap">

                    {{-- Donut chart --}}
                    <div class="pay-donut-wrap">
                        <svg viewBox="0 0 160 160" width="150" height="150">
                            {{-- Track (gray) --}}
                            <circle cx="80" cy="80" r="58" fill="none"
                                    stroke="#e9ecef" stroke-width="19"/>
                            {{-- Belum terbayar (yellow) --}}
                            <circle cx="80" cy="80" r="58" fill="none"
                                    stroke="#f5a623" stroke-width="19"
                                    stroke-dasharray="364.4"
                                    stroke-dashoffset="0"
                                    transform="rotate(-90 80 80)"
                                    opacity="0.9"/>
                            {{-- Terbayar (green) --}}
                            <circle cx="80" cy="80" r="58" fill="none"
                                    stroke="#2dbb7c" stroke-width="19"
                                    stroke-dasharray="364.4"
                                    stroke-dashoffset="{{ round(364.4 * (100 - $totalTerbayarPersen) / 100, 2) }}"
                                    transform="rotate(-90 80 80)"/>
                        </svg>
                        <div class="pay-donut-center">
                            <div class="pay-donut-pct">{{ $totalTerbayarPersen }}%</div>
                            <div class="pay-donut-lbl">Terbayar</div>
                        </div>
                    </div>

                    {{-- Legend --}}
                    <div style="flex:1;min-width:150px;">
                        <div class="pay-legend-row">
                            <span class="pay-legend-dot" style="background:#2dbb7c;"></span>
                            <div>
                                <div class="pay-legend-name">Terbayar</div>
                                <div class="pay-legend-amount">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</div>
                            </div>
                            <span class="pay-legend-pct" style="color:#2dbb7c;">{{ $totalTerbayarPersen }}%</span>
                        </div>
                        <div class="pay-legend-row">
                            <span class="pay-legend-dot" style="background:#f5a623;"></span>
                            <div>
                                <div class="pay-legend-name">Belum Terbayar</div>
                                <div class="pay-legend-amount">Rp {{ number_format($totalBelumTerbayar, 0, ',', '.') }}</div>
                            </div>
                            <span class="pay-legend-pct" style="color:#f5a623;">{{ $totalBelumTerbayarPersen }}%</span>
                        </div>
                    </div>

                </div>

                {{-- Total Box — FULL WIDTH --}}
                <div class="pay-total-box">
                    <div class="pay-total-icon">
                        <i class="bx bx-file-blank"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div class="pay-total-label">Total nilai invoice</div>
                        <div class="pay-total-amount">Rp {{ number_format($totalNominal, 0, ',', '.') }}</div>
                        <div class="pay-total-sub">Total invoice seluruh project Anda.</div>
                    </div>
                    <div class="pay-progress-section">
                        <div class="pay-progress-lbl">Progress Bayar</div>
                        <div class="pay-progress-pct">{{ $totalTerbayarPersen }}%</div>
                        <div class="progress mt-1" style="height:5px;width:80px;border-radius:4px;background:#e9ecef;">
                            <div class="progress-bar" style="width:{{ $totalTerbayarPersen }}%;background:#2dbb7c;border-radius:4px;"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Kolom 2: Status Pembayaran Project --}}
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header">
                <h6 class="fw-bold mb-0 text-dark-custom">Status Pembayaran Project</h6>
                <small class="text-muted">Distribusi project berdasarkan status pembayaran</small>
            </div>
            <div class="card-body d-flex align-items-center gap-4 py-3">

                {{-- Pie Chart --}}
                <div style="min-width:200px;flex-shrink:0;">
                    <div id="kyProjectStatusPieChart"></div>
                </div>

                {{-- Legend --}}
                @php
                    $totalPieProject = $lunasCount + $dicicilCount + $belumDicilCount;
                    $pctLunas   = $totalPieProject > 0 ? round(($lunasCount / $totalPieProject) * 100) : 0;
                    $pctDicicil = $totalPieProject > 0 ? round(($dicicilCount / $totalPieProject) * 100) : 0;
                    $pctBelumD  = $totalPieProject > 0 ? round(($belumDicilCount / $totalPieProject) * 100) : 0;
                @endphp
                <div class="flex-grow-1">
                    <ul style="list-style:none;padding:0;margin:0;">
                        <li class="d-flex align-items-center gap-2 py-2 border-bottom" style="font-size:13px;">
                            <span style="width:12px;height:12px;border-radius:3px;background:#2dbb7c;flex-shrink:0;"></span>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark-custom">Lunas</div>
                                <div class="small text-muted">{{ $lunasCount }} Project ({{ $pctLunas }}%)</div>
                            </div>
                        </li>
                        <li class="d-flex align-items-center gap-2 py-2 border-bottom" style="font-size:13px;">
                            <span style="width:12px;height:12px;border-radius:3px;background:#FEB019;flex-shrink:0;"></span>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark-custom">Dicicil</div>
                                <div class="small text-muted">{{ $dicicilCount }} Project ({{ $pctDicicil }}%)</div>
                            </div>
                        </li>
                        <li class="d-flex align-items-center gap-2 py-2" style="font-size:13px;">
                            <span style="width:12px;height:12px;border-radius:3px;background:#FF4560;flex-shrink:0;"></span>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark-custom">Belum Dicil</div>
                                <div class="small text-muted">{{ $belumDicilCount }} Project ({{ $pctBelumD }}%)</div>
                            </div>
                        </li>
                    </ul>
                    <div class="mt-3 pt-2 border-top d-flex align-items-center gap-2">
                        <i class="bx bx-info-circle text-muted small"></i>
                        <small class="text-muted">
                            Total <strong>{{ $totalPieProject }}</strong> Project dengan data pembayaran
                        </small>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>{{-- /SECTION 3 --}}

@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
'use strict';

/* ════════════════════════════════════════════════════════
   DATA DARI PHP
════════════════════════════════════════════════════════ */
/* ════════════════════════════════════════════════════════
   DATA DARI PHP (UPDATE bagian HEALTH)
════════════════════════════════════════════════════════ */
const KY_PROGRESS = @json($progressData);
const KY_STATUS   = @json($statusTaskData);
const KY_HEALTH_ALL = {
    week  : @json($healthWeek),
    month : @json($healthMonth),
    year  : @json($healthYear),
};
// Data health per project
const KY_HEALTH_BY_PROJECT = @json($healthDataPerProject);

// Variable untuk menyimpan health data yang aktif
let currentHealthData = KY_HEALTH_ALL;

const KY_PAY = {
    terbayar     : {{ $totalTerbayar }},
    belumTerbayar: {{ $totalBelumTerbayar }},
    totalNominal : {{ $totalNominal }},
};
const KY_STATUS_PIE = {
    lunas     : {{ $lunasCount }},
    dicicil   : {{ $dicicilCount }},
    belumDicil: {{ $belumDicilCount }},
};

/* ════════════════════════════════════════════════════════
   WARNA
════════════════════════════════════════════════════════ */
const C = {
    primary  : '#696cff',
    success  : '#00E396',
    warning  : '#FEB019',
    danger   : '#FF4560',
    purple   : '#775DD0',
    secondary: '#8592a3',
};

/* ════════════════════════════════════════════════════════
   HELPERS
════════════════════════════════════════════════════════ */
let kyProgressChart = null;

function setText(id, val) {
    const el = document.getElementById(id);
    if (el) el.textContent = val;
}
function setBar(id, pct) {
    const el = document.getElementById(id);
    if (el) el.style.width = (parseFloat(pct) || 0) + '%';
}

/* ════════════════════════════════════════════════════════
   1. PROGRESS PROJECT — DONUT (ApexCharts)
════════════════════════════════════════════════════════ */
function renderKyProgressChart(key) {
    const data = KY_PROGRESS[key] ?? KY_PROGRESS['all'];
    const pct  = parseFloat(data.persen) || 0;

    if (kyProgressChart) { kyProgressChart.destroy(); kyProgressChart = null; }

    const el = document.querySelector('#karyawanProgressChart');
    if (!el || typeof ApexCharts === 'undefined') return;

    kyProgressChart = new ApexCharts(el, {
        chart: {
            type: 'donut', height: 240, width: 240,
            toolbar: { show: false },
            animations: { enabled: true, speed: 600 },
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
                            color: C.primary, offsetY: -6, formatter: () => 'Selesai',
                        },
                        value: {
                            show: true, fontSize: '28px', fontWeight: 800,
                            color: C.primary, offsetY: 8, formatter: () => pct + '%',
                        },
                        total: {
                            show: true, showAlways: true, label: 'Selesai',
                            fontSize: '12px', fontWeight: 600, color: C.primary,
                            formatter: () => pct + '%',
                        },
                    },
                },
            },
        },
        stroke: { width: 4, colors: ['#fff'] },
        dataLabels: { enabled: false },
        legend: { show: false },
        tooltip: { enabled: false },
        states: {
            hover:  { filter: { type: 'none' } },
            active: { filter: { type: 'none' } },
        },
    });
    kyProgressChart.render();

    document.getElementById('karyawanProjectNameDisplay').textContent  = data.nama ?? 'Semua Project';
    document.getElementById('karyawanTotalTaskDisplay').textContent     = 'Total ' + data.total + ' task';
    document.getElementById('karyawanCompletedTaskDisplay').innerHTML   = 'Selesai (Done &amp; Approved) ' + data.selesai + ' task';
}

/* ════════════════════════════════════════════════════════
   2. STATUS TASK — PROGRESS BARS
════════════════════════════════════════════════════════ */
function updateKyStatusBars(key) {
    const d    = KY_STATUS[key] ?? KY_STATUS['all'];
    const nama = (KY_PROGRESS[key] ?? KY_PROGRESS['all']).nama ?? 'Semua Project';

    document.getElementById('karyawanStatusTaskLabel').textContent  = nama;
    document.getElementById('karyawanFinalStatusLabel').textContent = nama;

    setText('kyTodoCount',       d.todo);
    setText('kyTodoPercent',     '(' + d.todo_pct + '%)');
    setBar ('kyTodoBar',         d.todo_pct);

    setText('kyProgressCount',   d.inprogress);
    setText('kyProgressPercent', '(' + d.inprogress_pct + '%)');
    setBar ('kyProgressBar',     d.inprogress_pct);

    setText('kyDoneCount',       d.done);
    setText('kyDonePercent',     '(' + d.done_pct + '%)');
    setBar ('kyDoneBar',         d.done_pct);

    setText('kyTotalStatusCount', d.total);

    setText('kyPreviewCount',    d.preview);
    setText('kyPreviewPercent',  '(' + d.preview_pct + '%)');
    setBar ('kyPreviewBar',      d.preview_pct);

    setText('kyRevisiCount',     d.revisi);
    setText('kyRevisiPercent',   '(' + d.revisi_pct + '%)');
    setBar ('kyRevisiBar',       d.revisi_pct);

    setText('kyApprovedCount',   d.approved);
    setText('kyApprovedPercent', '(' + d.approved_pct + '%)');
    setBar ('kyApprovedBar',     d.approved_pct);

    setText('kyTotalFinalCount', d.total_final);
    
    // UPDATE HEALTH CHART berdasarkan project yang dipilih
    const projectKey = kyProjectSelect ? kyProjectSelect.value : 'all';
    if (projectKey !== 'all' && KY_HEALTH_BY_PROJECT[projectKey]) {
        currentHealthData = KY_HEALTH_BY_PROJECT[projectKey];
    } else {
        currentHealthData = KY_HEALTH_ALL;
    }
    
    // Refresh health chart dengan period yang sedang aktif
    const activeTab = document.querySelector('#kyHealthTabBtns .act-tab.active');
    let activePeriod = 'week';
    if (activeTab) {
        if (activeTab.textContent.includes('Bulan')) activePeriod = 'month';
        else if (activeTab.textContent.includes('Tahun')) activePeriod = 'year';
    }
    renderKyHealthSvg(activePeriod);
}
/* ════════════════════════════════════════════════════════
   3. PROJECT FILTER DROPDOWN
════════════════════════════════════════════════════════ */
const kyProjectSelect = document.getElementById('karyawanProjectFilterSelect');
if (kyProjectSelect) {
    kyProjectSelect.addEventListener('change', function () {
        const key = this.value;
        renderKyProgressChart(key);
        updateKyStatusBars(key);
    });
}

/* ════════════════════════════════════════════════════════
   4. HEALTH CHART — SVG CUSTOM
════════════════════════════════════════════════════════ */

function healthColor(pct) {
    if (pct >= 80) return '#2dbb7c';
    if (pct >= 50) return '#f5a623';
    return '#e74c3c';
}

function healthGradId(pct) {
    if (pct >= 80) return 'kyGradGreen';
    if (pct >= 50) return 'kyGradYellow';
    return 'kyGradRed';
}

function renderKyHealthSvg(period) {
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
    if (n === 0) { updateKyHealthStatus(null); return; }

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
    const gradId   = lastPct !== null ? healthGradId(lastPct) : 'kyGradGreen';

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

    document.getElementById('kyChartContent').innerHTML = area + segs + dots;

    const xl = document.getElementById('kyXLabels');
    xl.innerHTML = '';
    raw.forEach(r => {
        const s = document.createElement('span');
        s.textContent = r.label;
        xl.appendChild(s);
    });

    updateKyHealthStatus(lastPct, valid.length > 0 ? valid[valid.length - 1] : null);
}

function updateKyHealthStatus(pct) {
    const boxEl    = document.getElementById('kyHealthStatusBox');
    const iconWrap = document.getElementById('kyHealthIconWrap');
    const iconSvg  = document.getElementById('kyHealthIconSvg');
    const labelEl  = document.getElementById('kyHealthStatusLabel');
    const descEl   = document.getElementById('kyHealthStatusDesc');

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

window.switchKyHealthTab = function (btn, period) {
    document.querySelectorAll('#kyHealthTabBtns .act-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    renderKyHealthSvg(period);
};

/* ════════════════════════════════════════════════════════
   5. STATUS PIE CHART (ApexCharts)
════════════════════════════════════════════════════════ */
const kyProjectPieEl = document.querySelector('#kyProjectStatusPieChart');
if (kyProjectPieEl && typeof ApexCharts !== 'undefined') {
    const series  = [KY_STATUS_PIE.lunas, KY_STATUS_PIE.dicicil, KY_STATUS_PIE.belumDicil];
    const hasData = series.some(v => v > 0);

    new ApexCharts(kyProjectPieEl, {
        chart: {
            type: 'pie', height: 240,
            toolbar: { show: false },
            animations: { enabled: true, speed: 700 },
        },
        series: hasData ? series : [1],
        labels: hasData ? ['Lunas', 'Dicicil', 'Belum Dicil'] : ['Belum ada data'],
        colors: hasData ? [C.success, C.warning, C.danger] : ['#e9ecef'],
        stroke: { width: 3, colors: ['#fff'] },
        dataLabels: {
            enabled: hasData,
            formatter: (val, opts) => {
                const count = opts.w.config.series[opts.seriesIndex];
                return count + '\n(' + Math.round(val) + '%)';
            },
            style: { fontSize: '12px', fontWeight: 700 },
            dropShadow: { enabled: false },
        },
        legend: { show: false },
        tooltip: {
            enabled: hasData,
            y: { formatter: v => v + ' Project' },
        },
        states: {
            hover:  { filter: { type: 'lighten', value: 0.05 } },
            active: { filter: { type: 'none' } },
        },
        plotOptions: {
            pie: { dataLabels: { offset: -15 } },
        },
    }).render();
}

/* ════════════════════════════════════════════════════════
   INISIALISASI
════════════════════════════════════════════════════════ */
renderKyProgressChart('all');
updateKyStatusBars('all');
renderKyHealthSvg('week');

if (typeof bootstrap !== 'undefined') {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });
}
</script>
@endpush