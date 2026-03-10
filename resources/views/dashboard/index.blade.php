@extends('layouts.master')
@section('title', 'Dashboard - Project Management')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
<style>
    .status-badge { padding: .25rem .75rem; border-radius: .375rem; font-size: .75rem; font-weight: 600; }
    .project-card { transition: transform .2s, box-shadow .2s; border: none; box-shadow: 0 2px 6px rgba(0,0,0,.08); }
    .project-card:hover { transform: translateY(-5px); box-shadow: 0 4px 12px rgba(0,0,0,.15); }
    .stat-icon { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 12px; }
    .task-modal-item { padding: 15px; border-left: 3px solid #696cff; background: #f8f9fa; border-radius: 8px; margin-bottom: 12px; transition: all .3s; }
    .task-modal-item:hover { background: #e9ecef; transform: translateX(5px); }
    .modern-card { border-radius: 12px; border: none; box-shadow: 0 2px 8px rgba(0,0,0,.08); transition: all .3s; }
    .modern-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.12); }
    .chart-container { padding: 20px; background: linear-gradient(135deg,#f5f7fa 0%,#fff 100%); border-radius: 12px; }
    .equal-height-card { height: 100%; display: flex; flex-direction: column; }
    .equal-height-card .card-body { flex: 1; display: flex; flex-direction: column; }
    .performer-item { padding: 16px; border-radius: 10px; background: #fff; border: 1px solid #e7e7e7; margin-bottom: 12px; transition: all .3s; }
    .performer-item:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); transform: translateX(5px); }
    .performer-rank { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px; }
    .performer-rank.rank-1 { background: linear-gradient(135deg,#FFD700,#FFA500); color:#fff; }
    .performer-rank.rank-2 { background: linear-gradient(135deg,#C0C0C0,#808080); color:#fff; }
    .performer-rank.rank-3 { background: linear-gradient(135deg,#CD7F32,#8B4513); color:#fff; }
    .performer-rank.rank-4 { background:#fff4e6; color:#ff9f43; border:2px solid #ff9f43; }
    .performer-rank.rank-5 { background:#f0f0f0; color:#666;    border:2px solid #999; }
    .avatar-initials { width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; color:#fff; background:linear-gradient(135deg,#696cff,#8a6fdf); flex-shrink:0; }
    .role-item { padding:14px; border-radius:10px; background:#fff; border:1px solid #e7e7e7; }
    .role-icon { width:38px; height:38px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; }
    .empty-performers { text-align:center; padding:40px 20px; color:#8592a3; }
    .empty-performers i { font-size:3rem; margin-bottom:12px; display:block; opacity:.4; }

    /* ── Deadline Filter Tabs ── */
    .deadline-filter-tabs {
        display: flex;
        gap: 6px;
        background: #f3f4f6;
        border-radius: 10px;
        padding: 4px;
    }
    .deadline-filter-tabs .tab-btn {
        flex: 1;
        padding: 6px 14px;
        border: none;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        background: transparent;
        color: #6b7280;
        transition: all .2s;
        white-space: nowrap;
    }
    .deadline-filter-tabs .tab-btn:hover {
        background: rgba(105,108,255,.1);
        color: #696cff;
    }
    .deadline-filter-tabs .tab-btn.active {
        background: #fff;
        color: #696cff;
        box-shadow: 0 1px 4px rgba(0,0,0,.12);
    }
    .deadline-filter-tabs .tab-btn:disabled {
        opacity: .5;
        cursor: not-allowed;
    }

    /* ── Loading overlay ── */
    .chart-loading {
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,.75);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        backdrop-filter: blur(2px);
    }
    .chart-wrapper { position: relative; }

    /* ── Summary badges bawah chart ── */
    .deadline-summary {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid #f0f0f0;
    }
    .summary-badge {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        flex: 1;
        min-width: 90px;
        justify-content: center;
    }
    .summary-badge.tepat    { background:#d1fae5; color:#065f46; }
    .summary-badge.lebih    { background:#dbeafe; color:#1e40af; }
    .summary-badge.terlambat { background:#fee2e2; color:#991b1b; }
    .summary-badge .dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
    .summary-badge.tepat     .dot { background:#10b981; }
    .summary-badge.lebih     .dot { background:#3b82f6; }
    .summary-badge.terlambat .dot { background:#ef4444; }
</style>
@endpush

@section('content')

{{-- ═══════════════ WELCOME ═══════════════ --}}
<div class="row">
    <div class="col-12 mb-4">
        <div class="card modern-card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary mb-3">
                            Selamat Datang Kembali, <b class="text-warning">{{ Auth::user()->nama }}</b>! 🎯
                        </h5>
                        <div class="alert alert-info mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-bell-ring me-2"></i>
                                <div>
                                    <strong>Informasi Baru!</strong> Anda mendapatkan
                                    <span class="fw-bold text-primary">{{ $newProjekWeek }} proyek baru</span> dan
                                    <span class="fw-bold text-warning">{{ $newTaskWeek }} task baru</span> minggu ini.
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-primary me-2"
                            onclick="location.href='{{ route('master-data-projek.index') }}'">
                            <i class="bx bx-folder-open me-1"></i> Lihat Semua Proyek
                        </button>
                        @if($reviewTaskList->count() > 0)
                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                            data-bs-target="#reviewTaskModal">
                            <i class="bx bx-bell me-1"></i> Lihat Task Review
                            <span class="badge bg-warning text-dark ms-1">{{ $reviewTaskList->count() }}</span>
                        </button>
                        @endif
                    </div>
                </div>
                <div class="col-sm-5 text-center">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}"
                             height="140" alt="Dashboard" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════ STAT CARDS ═══════════════ --}}
<div class="row">
    @php
        $cards = [
            ['label'=>'Total Proyek',      'val'=>$statsProyek['total'],       'sub'=>$statsProyek['aktif'].' aktif saat ini', 'icon'=>'bx-folder-open',  'color'=>'primary'],
            ['label'=>'Proyek Aktif',       'val'=>$statsProyek['aktif'],       'sub'=>'Tahap Pemeliharaan',                   'icon'=>'bx-rocket',       'color'=>'success'],
            ['label'=>'Proyek Selesai',     'val'=>$statsProyek['selesai'],     'sub'=>'Telah Selesai',                        'icon'=>'bx-check-shield', 'color'=>'info'],
            ['label'=>'Proyek Dikerjakan',  'val'=>$statsProyek['in_progress'], 'sub'=>'Sedang Berjalan',                      'icon'=>'bx-task',         'color'=>'warning'],
        ];
    @endphp
    @foreach($cards as $card)
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card project-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <span class="fw-semibold d-block mb-1 text-muted">{{ $card['label'] }}</span>
                        <h3 class="card-title mb-2">{{ $card['val'] }}</h3>
                        <small class="text-{{ $card['color'] }} fw-semibold">
                            <i class="bx bx-timer"></i> {{ $card['sub'] }}
                        </small>
                    </div>
                    <div class="stat-icon bg-label-{{ $card['color'] }}">
                        <i class="bx {{ $card['icon'] }} text-{{ $card['color'] }}" style="font-size:1.75rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ═══════════════ PIE + ACQUISITION ═══════════════ --}}
<div class="row">
    {{-- Task Status Pie --}}
    <div class="col-lg-6 mb-4">
        <div class="card modern-card equal-height-card">
            <div class="card-header">
                <h5 class="card-title m-0">Status Task</h5>
                <small class="text-muted">Distribusi semua task</small>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label small mb-1">Filter Proyek:</label>
                    <select class="form-select form-select-sm" id="projectFilter" onchange="updateTaskChart()">
                        <option value="all">Semua Proyek Aktif</option>
                        @foreach($taskPerProjek as $proj)
                            <option value="{{ $proj['id'] }}">{{ $proj['nama'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 order-md-2">
                        <div id="taskStatusChart"></div>
                    </div>
                    <div class="col-md-6 order-md-1">
                        @php
                            $tot = $statsTasks['total'];
                            $pct = fn($n) => $tot > 0 ? round($n / $tot * 100) : 0;
                        @endphp
                        @foreach([
                            ['id'=>'todo',       'label'=>'To Do',       'bg'=>'#f8f9fa', 'badge'=>'secondary', 'val'=>$statsTasks['todo']],
                            ['id'=>'inprogress', 'label'=>'In Progress', 'bg'=>'#fff5e6', 'badge'=>'warning',   'val'=>$statsTasks['inprogress']],
                            ['id'=>'review',     'label'=>'Review',      'bg'=>'#e6f7ff', 'badge'=>'info',      'val'=>$statsTasks['review']],
                            ['id'=>'done',       'label'=>'Done',        'bg'=>'#e6ffe6', 'badge'=>'success',   'val'=>$statsTasks['done']],
                        ] as $leg)
                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded"
                             style="background:{{ $leg['bg'] }};">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-{{ $leg['badge'] }} me-2" style="width:14px;height:14px;"></span>
                                <span class="fw-semibold">{{ $leg['label'] }}</span>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold" id="legend-{{ $leg['id'] }}">{{ $leg['val'] }}</div>
                                <small class="text-muted" id="legend-{{ $leg['id'] }}-pct">{{ $pct($leg['val']) }}%</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Project Acquisition --}}
    <div class="col-lg-6 mb-4">
        <div class="card modern-card equal-height-card">
            <div class="card-header">
                <h5 class="card-title m-0">Perolehan Proyek 12 Bulan Terakhir</h5>
                <small class="text-muted">Proyek Didapat, Aktif, dan Selesai</small>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <div id="projectAcquisitionChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════ DEADLINE CHART + TOP PERFORMERS ═══════════════ --}}
<div class="row">
    {{-- ── DEADLINE PERFORMANCE ── --}}
    <div class="col-lg-6 mb-4">
        <div class="card modern-card equal-height-card">
            <div class="card-header d-flex align-items-start justify-content-between pb-0">
                <div>
                    <h5 class="card-title m-0">Aktivitas Penyelesaian Task Karyawan</h5>
                    <small class="text-muted" id="deadlineSubtitle">Tracking deadline performance (7 hari terakhir)</small>
                </div>

                {{-- ── Filter Tabs ── --}}
                <div class="deadline-filter-tabs ms-3 flex-shrink-0">
                    <button class="tab-btn active" data-period="week"  onclick="switchDeadlinePeriod('week',  this)">Minggu</button>
                    <button class="tab-btn"         data-period="month" onclick="switchDeadlinePeriod('month', this)">Bulan</button>
                    <button class="tab-btn"         data-period="year"  onclick="switchDeadlinePeriod('year',  this)">Tahun</button>
                </div>
            </div>

            <div class="card-body">
                {{-- Chart --}}
                <div class="chart-wrapper">
                    <div id="deadlineChartLoading" class="chart-loading" style="display:none;">
                        <div class="spinner-border text-primary" role="status" style="width:1.8rem;height:1.8rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="chart-container">
                        <div id="employeeDeadlineChart"></div>
                    </div>
                </div>

                {{-- Summary Badges --}}
                <div class="deadline-summary">
                    <div class="summary-badge tepat">
                        <div class="dot"></div>
                        <span>Tepat Waktu</span>
                        <strong id="sum-tepat">0</strong>
                    </div>
                    <div class="summary-badge lebih">
                        <div class="dot"></div>
                        <span>Lebih Awal</span>
                        <strong id="sum-lebih">0</strong>
                    </div>
                    <div class="summary-badge terlambat">
                        <div class="dot"></div>
                        <span>Terlambat</span>
                        <strong id="sum-terlambat">0</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Performers --}}
    <div class="col-lg-6 mb-4">
        <div class="card modern-card equal-height-card">
            <div class="card-header">
                <h5 class="card-title m-0">Performa Karyawan Terbaik</h5>
                <small class="text-muted">Top 5 karyawan tepat waktu / sebelum deadline</small>
            </div>
            <div class="card-body">
                @if($topPerformers->isEmpty())
                    <div class="empty-performers">
                        <i class="bx bx-trophy"></i>
                        <p class="mb-0 fw-semibold">Belum ada data performa</p>
                        <small>Data muncul setelah karyawan menyelesaikan task</small>
                    </div>
                @else
                    @php
                        $rankClasses = ['rank-1','rank-2','rank-3','rank-4','rank-5'];
                        $rankLabels  = ['🏆','#2','#3','#4','#5'];
                        $taskColors  = ['text-info','text-primary','text-success','text-warning','text-info'];
                    @endphp
                    @foreach($topPerformers as $i => $p)
                    <div class="performer-item">
                        <div class="d-flex align-items-center">
                            <div class="performer-rank {{ $rankClasses[$i] ?? 'rank-5' }} me-3">
                                {{ $rankLabels[$i] ?? '#'.($i+1) }}
                            </div>
                            <div class="avatar-initials me-3">
                                @if($p->foto)
                                    <img src="{{ asset('storage/'.$p->foto) }}"
                                         class="rounded-circle" width="40" height="40"
                                         style="object-fit:cover;" alt="{{ $p->nama }}"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                    <span style="display:none;">{{ strtoupper(substr($p->nama,0,2)) }}</span>
                                @else
                                    {{ strtoupper(substr($p->nama,0,2)) }}
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $p->nama }}</h6>
                                <small class="text-muted">{{ $p->nama_job_role ?? '—' }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold {{ $taskColors[$i] ?? 'text-secondary' }}">
                                    {{ $p->task_count }} Task
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════ DAFTAR KARYAWAN ═══════════════ --}}
<div class="row">
    <div class="col-12">
        <div class="card modern-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title m-0">Daftar Karyawan</h5>
                    <small class="text-muted">Tim berdasarkan role dan spesialisasi</small>
                </div>
                <div class="text-end">
                    <h1 class="mb-0 text-primary">{{ $totalKaryawan }}</h1>
                    <small class="text-muted">Total Karyawan</small>
                </div>
            </div>
            <div class="card-body">
                @if($karyawanPerRole->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bx bx-group" style="font-size:3rem;opacity:.4;display:block;margin-bottom:12px;"></i>
                        <p class="mb-0">Belum ada data karyawan</p>
                    </div>
                @else
                    @php
                        $roleColors = ['primary','success','warning','info','danger','secondary','primary','success','warning'];
                        $iconMap = ['developer'=>'bx-code-alt','designer'=>'bx-palette','marketing'=>'bx-line-chart',
                                    'content'=>'bx-camera','social'=>'bxl-instagram','qa'=>'bx-bug',
                                    'devops'=>'bx-server','data'=>'bx-data','manager'=>'bx-briefcase-alt','admin'=>'bx-shield'];
                    @endphp
                    <div class="row">
                        @foreach($karyawanPerRole as $idx => $role)
                            @php
                                $color     = $roleColors[$idx % count($roleColors)];
                                $lower     = strtolower($role->nama_job_role);
                                $icon      = 'bx-briefcase';
                                foreach ($iconMap as $kw => $ic) { if (str_contains($lower, $kw)) { $icon=$ic; break; } }
                                $pct       = $totalKaryawan > 0 ? round($role->jumlah / $totalKaryawan * 100) : 0;
                            @endphp
                            <div class="col-md-4 mb-3">
                                <div class="role-item">
                                    <div class="d-flex align-items-center">
                                        <div class="role-icon bg-label-{{ $color }} me-3">
                                            <i class="bx {{ $icon }} text-{{ $color }}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ $role->nama_job_role }}</h6>
                                            <small class="text-muted">{{ $pct }}% dari total</small>
                                        </div>
                                        <div class="text-end">
                                            <h5 class="mb-0 text-{{ $color }}">{{ $role->jumlah }}</h5>
                                            <small class="text-muted">Orang</small>
                                        </div>
                                    </div>
                                    <div class="progress mt-2" style="height:6px;">
                                        <div class="progress-bar bg-{{ $color }}" style="width:{{ $pct }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════ MODAL REVIEW TASK ═══════════════ --}}
<div class="modal fade" id="reviewTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-bell text-warning me-2"></i>
                    Task yang Perlu Ditinjau
                    <span class="badge bg-warning text-dark ms-2">{{ $reviewTaskList->count() }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @forelse($reviewTaskList as $task)
                <div class="task-modal-item">
                    <div class="d-flex">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle bg-label-success">
                                <i class="bx bx-check"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $task->judul_tugas }}</h6>
                            <small class="text-muted d-block mb-1">
                                <strong>{{ optional(optional($task->tim)->user)->nama ?? '—' }}</strong>
                                — proyek <strong>{{ optional($task->projek)->nama_projek ?? '—' }}</strong>
                            </small>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">
                                    <i class="bx bx-time"></i>
                                    {{ $task->dibuat_pada?->diffForHumans() ?? '—' }}
                                </small>
                                <div>
                                    <button class="btn btn-sm btn-success me-1"
                                        onclick="approveTask({{ $task->id_tugas }}, {{ optional($task->projek)->id_projek ?? 0 }}, this)">
                                        <i class="bx bx-check"></i> Approve
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger"
                                        onclick="rejectTask({{ $task->id_tugas }}, {{ optional($task->projek)->id_projek ?? 0 }}, this)">
                                        <i class="bx bx-x"></i> Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bx bx-check-double" style="font-size:3rem;opacity:.4;display:block;margin-bottom:12px;"></i>
                        <p class="mb-0 fw-semibold">Tidak ada task yang menunggu review</p>
                    </div>
                @endforelse
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                @if($reviewTaskList->count() > 0)
                <button type="button" class="btn btn-primary" onclick="approveAllTasks()">
                    <i class="bx bx-check-double me-1"></i> Approve Semua
                </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
'use strict';

/* ─── DATA DARI SERVER ──────────────────────────────── */
const TASK_PER_PROJEK   = @json($taskPerProjek);
const STATS_TASKS_ALL   = @json($statsTasks);
const CHART_ACQUISITION = @json($chartAcquisition);
const CHART_DEADLINE_INIT = @json($chartDeadline);   // default: minggu

/* ─── CSRF ──────────────────────────────────────────── */
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

/* ─── COLOR PALETTE ─────────────────────────────────── */
const C = {
    primary:'#696cff', success:'#71dd37', warning:'#ffab00',
    danger:'#ff3e1d',  info:'#03c3ec',    secondary:'#8592a3',
};

/* ════════════════════════════════════════════════════════
 | 1. PROJECT ACQUISITION LINE CHART
════════════════════════════════════════════════════════ */
(function () {
    const el = document.querySelector('#projectAcquisitionChart');
    if (!el) return;
    new ApexCharts(el, {
        chart: { type:'line', height:300, toolbar:{show:false}, zoom:{enabled:false} },
        series: [
            { name:'Proyek Didapat', data: CHART_ACQUISITION.map(d => d.didapat) },
            { name:'Proyek Aktif',   data: CHART_ACQUISITION.map(d => d.aktif)   },
            { name:'Proyek Selesai', data: CHART_ACQUISITION.map(d => d.selesai) },
        ],
        colors: [C.primary, C.warning, C.success],
        stroke: { curve:'smooth', width:3 },
        markers: { size:5, strokeWidth:2, strokeColors:'#fff', hover:{size:7} },
        dataLabels: { enabled:false },
        grid: { borderColor:'#f0f0f0', strokeDashArray:4 },
        xaxis: {
            categories: CHART_ACQUISITION.map(d => d.label),
            labels: { style:{ colors:'#8592a3', fontSize:'13px' } }
        },
        yaxis: {
            min: 0,
            title: { text:'Jumlah Proyek', style:{ color:'#8592a3', fontSize:'13px', fontWeight:500 } },
            labels: { style:{ colors:'#8592a3', fontSize:'13px' } }
        },
        legend: { position:'top', horizontalAlign:'left', fontSize:'13px', fontWeight:500,
                  markers:{ width:10, height:10, radius:12 } },
        tooltip: { shared:true, intersect:false, y:{ formatter: v => v+' proyek' } }
    }).render();
})();

/* ════════════════════════════════════════════════════════
 | 2. TASK STATUS PIE CHART (dengan filter proyek)
════════════════════════════════════════════════════════ */
let taskChartInstance;

function renderTaskChart(todo, inprogress, review, done) {
    if (taskChartInstance) taskChartInstance.destroy();
    const el = document.querySelector('#taskStatusChart');
    if (!el) return;
    taskChartInstance = new ApexCharts(el, {
        chart: { type:'donut', height:250, toolbar:{show:false} },
        series: [todo, inprogress, review, done],
        labels: ['To Do','In Progress','Review','Done'],
        colors: [C.secondary, C.warning, C.info, C.success],
        plotOptions: { pie: { donut: { size:'75%',
            labels: { show:true,
                name:  { show:true, fontSize:'14px', fontWeight:600, offsetY:-5 },
                value: { show:true, fontSize:'24px', fontWeight:700, offsetY:5, formatter:v=>v },
                total: { show:true, label:'Total Tasks', fontSize:'13px', fontWeight:500, color:'#8592a3',
                         formatter:w => w.globals.seriesTotals.reduce((a,b)=>a+b,0) }
            }
        }}},
        legend: { show:false },
        dataLabels: { enabled:true,
            formatter:(v,opts) => opts.w.config.series[opts.seriesIndex],
            style:{ fontSize:'12px', fontWeight:600, colors:['#fff'] },
            dropShadow:{ enabled:false }
        },
        tooltip: { y:{ formatter:(v,{w}) => {
            const t = w.globals.seriesTotals.reduce((a,b)=>a+b,0);
            return v+' task ('+(t>0?((v/t)*100).toFixed(1):0)+'%)';
        }}}
    });
    taskChartInstance.render();
}

renderTaskChart(STATS_TASKS_ALL.todo, STATS_TASKS_ALL.inprogress,
                STATS_TASKS_ALL.review, STATS_TASKS_ALL.done);

window.updateTaskChart = function () {
    const val = document.getElementById('projectFilter').value;
    let todo, inprogress, review, done;
    if (val === 'all') {
        ({ todo, inprogress, review, done } = STATS_TASKS_ALL);
    } else {
        const p = TASK_PER_PROJEK.find(x => x.id == val);
        todo = p?.todo ?? 0; inprogress = p?.inprogress ?? 0;
        review = p?.review ?? 0; done = p?.done ?? 0;
    }
    renderTaskChart(todo, inprogress, review, done);
    const total = todo + inprogress + review + done;
    const pct   = n => total > 0 ? Math.round(n/total*100) : 0;
    document.getElementById('legend-todo').textContent        = todo;
    document.getElementById('legend-inprogress').textContent  = inprogress;
    document.getElementById('legend-review').textContent      = review;
    document.getElementById('legend-done').textContent        = done;
    document.getElementById('legend-todo-pct').textContent        = pct(todo)+'%';
    document.getElementById('legend-inprogress-pct').textContent  = pct(inprogress)+'%';
    document.getElementById('legend-review-pct').textContent      = pct(review)+'%';
    document.getElementById('legend-done-pct').textContent        = pct(done)+'%';
};

/* ════════════════════════════════════════════════════════
 | 3. DEADLINE PERFORMANCE CHART
 |    — period: 'week' | 'month' | 'year'
 |    — Filter: tab Minggu / Bulan / Tahun
════════════════════════════════════════════════════════ */
let deadlineChartInstance;
let currentDeadlinePeriod = 'week';

const SUBTITLE_MAP = {
    week:  'Tracking deadline performance (7 hari terakhir)',
    month: 'Tracking deadline performance (30 hari terakhir)',
    year:  'Tracking deadline performance (12 bulan terakhir)',
};

/* Render (atau re-render) chart deadline */
function renderDeadlineChart(data) {
    if (deadlineChartInstance) deadlineChartInstance.destroy();
    const el = document.querySelector('#employeeDeadlineChart');
    if (!el) return;

    const labels    = data.map(d => d.label);
    const tepat     = data.map(d => d.tepat);
    const lebih     = data.map(d => d.lebih_awal);
    const terlambat = data.map(d => d.terlambat);

    /* Update summary badges */
    document.getElementById('sum-tepat').textContent    = tepat.reduce((a,b)=>a+b,0);
    document.getElementById('sum-lebih').textContent    = lebih.reduce((a,b)=>a+b,0);
    document.getElementById('sum-terlambat').textContent = terlambat.reduce((a,b)=>a+b,0);

    /* Tooltip tambahan untuk mode bulan (tampilkan sub-label tanggal) */
    const hasSubLabel = data[0]?.sublabel !== undefined;

    deadlineChartInstance = new ApexCharts(el, {
        chart: {
            type: currentDeadlinePeriod === 'year' ? 'area' : 'line',
            height: 280,
            toolbar: { show:false },
            zoom: { enabled:false },
            animations: { enabled:true, easing:'easeinout', speed:600 }
        },
        series: [
            { name:'Tepat Waktu',       data: tepat },
            { name:'Sebelum Deadline',  data: lebih },
            { name:'Melewati Deadline', data: terlambat },
        ],
        colors: [C.success, C.info, C.danger],
        stroke: { curve:'smooth', width: currentDeadlinePeriod === 'year' ? 2 : 3 },
        fill: currentDeadlinePeriod === 'year'
            ? { type:'gradient', gradient:{ shadeIntensity:.1, opacityFrom:.4, opacityTo:.05 } }
            : { opacity:1 },
        markers: { size:5, strokeWidth:2, strokeColors:'#fff', hover:{size:7} },
        dataLabels: { enabled: currentDeadlinePeriod !== 'year' },
        grid: { borderColor:'#f0f0f0', strokeDashArray:4, padding:{ left:10 } },
        xaxis: {
            categories: labels,
            labels: {
                style: { colors:'#8592a3', fontSize: currentDeadlinePeriod === 'year' ? '11px' : '12px' },
                rotate: currentDeadlinePeriod === 'year' ? -30 : 0,
            },
            tooltip: { enabled: false }
        },
        yaxis: {
            min: 0,
            title: { text:'Jumlah Task', style:{ color:'#8592a3', fontSize:'13px', fontWeight:500 } },
            labels: { style:{ colors:'#8592a3', fontSize:'13px' } }
        },
        legend: {
            position:'top', horizontalAlign:'left', fontSize:'13px', fontWeight:500,
            markers:{ width:10, height:10, radius:12 }
        },
        tooltip: {
            shared:true, intersect:false,
            x: {
                formatter: (val, { dataPointIndex }) => {
                    if (hasSubLabel && data[dataPointIndex]) {
                        return `${data[dataPointIndex].label} (${data[dataPointIndex].sublabel})`;
                    }
                    return val;
                }
            },
            y: { formatter: v => v+' task' }
        }
    });
    deadlineChartInstance.render();
}

/* Initial render */
renderDeadlineChart(CHART_DEADLINE_INIT);

/* Tab click handler */
window.switchDeadlinePeriod = function (period, btnEl) {
    if (currentDeadlinePeriod === period) return;
    currentDeadlinePeriod = period;

    /* Update active tab */
    document.querySelectorAll('.deadline-filter-tabs .tab-btn').forEach(b => b.classList.remove('active'));
    btnEl.classList.add('active');

    /* Update subtitle */
    document.getElementById('deadlineSubtitle').textContent = SUBTITLE_MAP[period] ?? '';

    /* Disable tabs during loading */
    document.querySelectorAll('.deadline-filter-tabs .tab-btn').forEach(b => b.disabled = true);
    document.getElementById('deadlineChartLoading').style.display = 'flex';

    /* AJAX request */
    fetch(`/dashboard/deadline-chart?period=${period}`, {
        headers: { 'Accept':'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) renderDeadlineChart(res.data);
    })
    .catch(err => console.error('Deadline chart fetch error:', err))
    .finally(() => {
        document.getElementById('deadlineChartLoading').style.display = 'none';
        document.querySelectorAll('.deadline-filter-tabs .tab-btn').forEach(b => b.disabled = false);
    });
};

/* ════════════════════════════════════════════════════════
 | 4. APPROVE / REJECT TASK (AJAX)
════════════════════════════════════════════════════════ */
function approveTask(taskId, projekId, btn) {
    if (!projekId) return;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    fetch(`/projek/${projekId}/task/${taskId}/status-akhir`, {
        method:'PATCH',
        headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
        body: JSON.stringify({ status_akhir:'approved' })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const item = btn.closest('.task-modal-item');
            item.style.transition = 'all .4s';
            item.style.opacity    = '0';
            item.style.transform  = 'translateX(20px)';
            setTimeout(() => item.remove(), 400);
        } else {
            alert(data.message ?? 'Gagal approve task.');
            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-check"></i> Approve';
        }
    })
    .catch(() => { btn.disabled=false; btn.innerHTML='<i class="bx bx-check"></i> Approve'; });
}

function rejectTask(taskId, projekId, btn) {
    if (!projekId) return;
    btn.disabled = true;
    fetch(`/projek/${projekId}/task/${taskId}/status-akhir`, {
        method:'PATCH',
        headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
        body: JSON.stringify({ status_akhir:'revisi' })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const item = btn.closest('.task-modal-item');
            item.style.borderLeftColor = '#ff3e1d';
            item.querySelector('h6').textContent += ' [Dikembalikan]';
        } else { alert(data.message ?? 'Gagal reject task.'); }
        btn.disabled = false;
    })
    .catch(() => { btn.disabled=false; });
}

function approveAllTasks() {
    const btns = document.querySelectorAll('#reviewTaskModal .task-modal-item button[onclick*="approveTask"]');
    if (!btns.length) return;
    if (!confirm(`Approve semua ${btns.length} task?`)) return;
    btns.forEach(b => b.click());
}
</script>
@endpush