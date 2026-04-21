@extends('layouts.master')
@section('title', 'Kelola Project')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/master-dataproject.css') }}">
<style>
/* ═══════════════════════════════════════════
   EXPORT DROPDOWN
═══════════════════════════════════════════ */
.export-dropdown-wrap {
    position: relative;
    display: inline-block;
}
.export-dropdown-menu {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    z-index: 9999;
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    box-shadow: 0 8px 24px rgba(0,0,0,.12);
    min-width: 152px;
    overflow: hidden;
    display: none;
}
.export-dropdown-menu.open { display: block; }
.export-dropdown-item {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    padding: 10px 14px;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    background: white;
    border: none;
    cursor: pointer;
    text-align: left;
    transition: background .15s;
}
.export-dropdown-item:hover { background: #F3F4F6; }
/* ═══════════════════════════════════════════
   PDF PREVIEW MODAL
═══════════════════════════════════════════ */
#pdfPreviewModalProject {
    position: fixed; inset: 0; z-index: 10000;
    display: none; align-items: center; justify-content: center;
}
#pdfPreviewModalProject.open { display: flex; }
#pdfPreviewBackdropProject {
    position: absolute; inset: 0;
    background: rgba(0,0,0,.45);
    backdrop-filter: blur(2px);
}
#pdfPreviewBoxProject {
    position: relative; z-index: 1; background: white; border-radius: 8px;
    width: min(98vw, 960px); max-height: 94vh;
    display: flex; flex-direction: column;
    box-shadow: 0 16px 48px rgba(0,0,0,.2); overflow: hidden;
    border: 1px solid #D1D5DB;
}
#pdfPreviewToolbarProject {
    background: #1E2A3A;
    padding: 12px 20px; display: flex; align-items: center; gap: 10px; flex-shrink: 0;
}
#pdfPreviewToolbarProject h6 { color: #F9FAFB; font-size: 14px; font-weight: 600; margin: 0; flex: 1; }
.pdf-toolbar-btn-p {
    padding: 6px 14px; border-radius: 5px; border: 1px solid rgba(255,255,255,.25);
    background: transparent; color: #D1D5DB; font-size: 12px; font-weight: 600;
    cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all .15s;
}
.pdf-toolbar-btn-p:hover { background: rgba(255,255,255,.1); color: white; }
.pdf-toolbar-btn-p.print-btn { background: white; color: #1E2A3A; border-color: white; }
.pdf-toolbar-btn-p.print-btn:hover { background: #F3F4F6; }
#pdfPreviewContentProject { flex: 1; overflow-y: auto; padding: 24px; background: #F3F4F6; }
/* ═══════════════════════════════════════════
   PDF PAGE STYLES
═══════════════════════════════════════════ */
.pdf-wrap { font-family: 'Georgia','Times New Roman',serif; max-width: 794px; margin: 0 auto; color: #1F2937; background: white; border: 1px solid #D1D5DB; display: flex; flex-direction: column; min-height: 297mm; }
.pdf-letterhead { background: #1E2A3A; padding: 20px 28px 18px; display: flex; justify-content: space-between; align-items: flex-start; }
.pdf-letterhead-left .doc-type { font-size: 9px; font-weight: 400; text-transform: uppercase; letter-spacing: .15em; color: #9CA3AF; margin-bottom: 5px; font-family: 'Segoe UI',Arial,sans-serif; }
.pdf-letterhead-left .doc-title { font-size: 18px; font-weight: 700; color: white; line-height: 1.25; font-family: 'Georgia',serif; }
.pdf-letterhead-left .doc-sub { font-size: 11px; color: #9CA3AF; margin-top: 4px; font-family: 'Segoe UI',Arial,sans-serif; }
.pdf-letterhead-right { text-align: right; flex-shrink: 0; }
.pdf-letterhead-right .doc-num { font-size: 10px; color: #9CA3AF; font-family: 'Courier New',monospace; margin-bottom: 4px; }
.pdf-letterhead-right .doc-date { font-size: 11px; color: #D1D5DB; font-family: 'Segoe UI',Arial,sans-serif; font-weight: 500; }
.pdf-rule { border: none; border-top: 2px solid #374151; margin: 0; }
.pdf-project-info { padding: 16px 28px; background: #F9FAFB; border-bottom: 1px solid #E5E7EB; display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
.pdf-info-col { padding: 0 12px; }
.pdf-info-col:first-child { padding-left: 0; border-right: 1px solid #E5E7EB; }
.pdf-info-col:last-child { padding-left: 20px; }
.pdf-info-row { display: flex; gap: 8px; margin-bottom: 7px; font-size: 11px; align-items: flex-start; font-family: 'Segoe UI',Arial,sans-serif; }
.pdf-info-row:last-child { margin-bottom: 0; }
.pdf-info-lbl { min-width: 108px; color: #6B7280; font-weight: 500; flex-shrink: 0; }
.pdf-info-val { color: #111827; font-weight: 600; line-height: 1.5; }
.pdf-section-header { padding: 8px 28px 6px; background: white; border-bottom: 1px solid #E5E7EB; display: flex; align-items: center; gap: 10px; }
.pdf-section-header span { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .12em; color: #6B7280; font-family: 'Segoe UI',Arial,sans-serif; }
.pdf-section-header::before { content: ''; width: 3px; height: 11px; background: #1E2A3A; border-radius: 1px; flex-shrink: 0; }
.pdf-section-header::after { content: ''; flex: 1; height: 1px; background: #E5E7EB; }
.pdf-stats-wrapper { padding: 16px 28px; background: white; border-bottom: 1px solid #E5E7EB; display: flex; gap: 24px; align-items: flex-start; }
.pdf-stats-table-wrap { flex: 1; }
.pdf-stats-table { width: 100%; border-collapse: collapse; font-family: 'Segoe UI',Arial,sans-serif; font-size: 11px; }
.pdf-stats-table th { background: #1E2A3A; color: white; padding: 7px 10px; text-align: left; font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; }
.pdf-stats-table td { padding: 7px 10px; border-bottom: 1px solid #F3F4F6; color: #374151; }
.pdf-stats-table tr:last-child td { border-bottom: none; }
.pdf-stats-table tr:nth-child(even) td { background: #F9FAFB; }
.pdf-stats-count { font-weight: 700; color: #111827; }
.pdf-stats-total-row td { background: #F3F4F6 !important; font-weight: 700; color: #1F2937; border-top: 1px solid #D1D5DB; }
.pdf-completion-block { margin-top: 10px; padding: 10px 12px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 4px; font-family: 'Segoe UI',Arial,sans-serif; }
.pdf-completion-label { font-size: 9px; color: #6B7280; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 5px; }
.pdf-completion-nums { font-size: 13px; font-weight: 700; color: #1E2A3A; margin-bottom: 6px; }
.pdf-bar-bg { background: #E5E7EB; height: 6px; border-radius: 3px; overflow: hidden; }
.pdf-bar-fill { height: 100%; background: #1E2A3A; border-radius: 3px; }
.pdf-chart-wrap { width: 180px; flex-shrink: 0; display: flex; flex-direction: column; align-items: center; }
.pdf-chart-title { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #6B7280; margin-bottom: 8px; font-family: 'Segoe UI',Arial,sans-serif; text-align: center; }
.pdf-chart-legend { margin-top: 10px; width: 100%; font-family: 'Segoe UI',Arial,sans-serif; }
.pdf-legend-item { display: flex; align-items: center; gap: 6px; font-size: 9px; color: #374151; margin-bottom: 4px; }
.pdf-legend-dot { width: 10px; height: 10px; border-radius: 2px; flex-shrink: 0; }
.pdf-tasks-wrap { padding: 0 28px 24px; background: white; }
.pdf-task-card { border: 1px solid #D1D5DB; border-radius: 4px; margin-bottom: 14px; overflow: hidden; page-break-inside: avoid; }
.pdf-task-head { padding: 8px 12px; background: #F9FAFB; border-bottom: 1px solid #E5E7EB; display: flex; align-items: flex-start; gap: 10px; }
.pdf-task-no { width: 22px; height: 22px; border-radius: 3px; background: #1E2A3A; color: white; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; flex-shrink: 0; font-family: 'Segoe UI',Arial,sans-serif; }
.pdf-task-no.approved { background: #374151; }
.pdf-task-title-block { flex: 1; min-width: 0; }
.pdf-task-title { font-size: 12px; font-weight: 700; color: #111827; line-height: 1.3; font-family: 'Segoe UI',Arial,sans-serif; }
.pdf-task-desc { font-size: 10px; color: #6B7280; margin-top: 2px; line-height: 1.5; font-family: 'Segoe UI',Arial,sans-serif; }
.pdf-task-badges { display: flex; gap: 5px; flex-wrap: wrap; margin-left: auto; flex-shrink: 0; }
.pdf-badge { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 3px; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; white-space: nowrap; font-family: 'Segoe UI',Arial,sans-serif; }
.badge-draft { background: #F3F4F6; color: #6B7280; border: 1px solid #D1D5DB; }
.badge-todo { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }
.badge-inprogress { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }
.badge-done { background: #F0FDF4; color: #166534; border: 1px solid #BBF7D0; }
.badge-review { background: #F5F3FF; color: #5B21B6; border: 1px solid #DDD6FE; }
.badge-revisi { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }
.badge-approved { background: #F0FDF4; color: #166534; border: 1px solid #BBF7D0; }
.pdf-task-body { padding: 10px 12px; }
.pdf-task-meta-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; margin-bottom: 10px; }
.pdf-meta-item .lbl { font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #9CA3AF; margin-bottom: 2px; font-family: 'Segoe UI',Arial,sans-serif; }
.pdf-meta-item .val { font-size: 11px; font-weight: 600; color: #1F2937; line-height: 1.4; font-family: 'Segoe UI',Arial,sans-serif; }
.pdf-hasil-section { margin-top: 8px; }
.pdf-hasil-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #374151; margin-bottom: 7px; display: flex; align-items: center; gap: 5px; font-family: 'Segoe UI',Arial,sans-serif; border-top: 1px solid #E5E7EB; padding-top: 8px; }
.pdf-hasil-grid { display: grid; grid-template-columns: repeat(auto-fill,minmax(200px,1fr)); gap: 8px; }
.pdf-hasil-img-wrap { border-radius: 3px; overflow: hidden; border: 1px solid #D1D5DB; aspect-ratio: 16/10; background: #F9FAFB; }
.pdf-hasil-img-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
.pdf-hasil-doc { display: flex; align-items: center; gap: 8px; background: #F9FAFB; border: 1px solid #D1D5DB; border-radius: 3px; padding: 9px 11px; }
.pdf-hasil-doc .icon { font-size: 20px; }
.pdf-hasil-doc .name { font-size: 10px; font-weight: 700; color: #374151; word-break: break-all; font-family: 'Segoe UI',Arial,sans-serif; }
.pdf-hasil-doc .type { font-size: 9px; color: #9CA3AF; margin-top: 2px; text-transform: uppercase; font-family: 'Segoe UI',Arial,sans-serif; }
.pdf-empty-foto { background: #F9FAFB; border: 1px dashed #D1D5DB; border-radius: 3px; padding: 10px; text-align: center; font-size: 10px; color: #9CA3AF; font-style: italic; font-family: 'Segoe UI',Arial,sans-serif; }
.pdf-doc-footer { background: #1E2A3A; padding: 9px 28px; display: flex; justify-content: space-between; align-items: center; margin-top: auto; }
.pdf-doc-footer span { font-size: 9px; color: #9CA3AF; font-family: 'Segoe UI',Arial,sans-serif; }
</style>
@endpush

@php
    $currentUser = auth()->user();
    $isAdmin     = $currentUser->isAdmin();
    $isPM        = $currentUser->isPM();
    $isKaryawan  = $currentUser->isKaryawan();
    $isKlien     = $currentUser->isKlien();
    // Boleh akses tambah/edit
    $canCreate   = $isAdmin || $isPM;
    $canEdit     = $isAdmin || $isPM;
    // Hanya admin yang bisa hapus
    $canDelete   = $isAdmin || $isPM;
    // Export keseluruhan: admin saja (PM bisa export per-project, bukan all)
    $canExportAll = $isAdmin;
    // Export per-project: admin, PM, klien
    $canExportProject = $isAdmin || $isPM || $isKlien;
    // Sembunyikan nominal: PM dan Karyawan
    $hideNominal = $isPM || $isKaryawan;
    // Ubah status inline: admin dan PM saja
    $canChangeStatus = $isAdmin || $isPM;
@endphp

@section('content')
@if(session('success'))
<div class="alert-success-custom">
    <i class="bx bx-check-circle" style="font-size:18px;"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert-danger-custom">
    <i class="bx bx-error-circle"></i> {{ session('error') }}
</div>
@endif
@if($errors->any())
<div class="alert-danger-custom">
    <strong><i class="bx bx-error-circle"></i> Terdapat kesalahan:</strong>
    <ul class="mb-0 mt-1 ps-3">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</div>
@endif

{{-- ── Project Header Card ── --}}
<div class="project-header-card">
    <div class="project-header-top">
        <div class="project-header-content">
            <div class="project-icon"><i class="bx bx-folder-open"></i></div>
            <div>
                <h4 class="project-title">Kelola Project</h4>
                <p class="project-desc">
                    @if($isAdmin) Manajemen dan monitoring seluruh project perusahaan
                    @elseif($isPM) Project yang Anda kelola sebagai Project Manager
                    @elseif($isKaryawan) Project yang Anda ikuti sebagai tim
                    @elseif($isKlien) Daftar project perusahaan Anda
                    @endif
                </p>
            </div>
            <div class="header-actions">
                {{-- Export All: hanya Admin --}}
                @if($canExportAll)
                <button class="btn-action btn-outline-custom" onclick="exportAllProjectsPDF()">
                    <i class="bx bxs-file-pdf"></i> Export Rekap Projek
                </button>
                @endif

                {{-- Tambah Project: hanya Admin & PM --}}
                @if($canCreate)
                <button class="btn-action btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalTambahProject">
                    <i class="bx bx-plus"></i> Tambah Project
                </button>
                @endif
            </div>
        </div>
    </div>
    <div class="project-stats-bar">
        <div class="stat-item">
            <div class="stat-icon-circle total"><i class="bx bx-task"></i></div>
            <div><div class="stat-label">Total Project</div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-sub">Semua project</div></div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-circle pending"><i class="bx bx-time-five"></i></div>
            <div><div class="stat-label">Pending</div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-sub">Menunggu dimulai</div></div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-circle aktif"><i class="bx bx-check-shield"></i></div>
            <div><div class="stat-label">Aktif</div><div class="stat-value">{{ $stats['aktif'] }}</div><div class="stat-sub">Maintenance / Aktif</div></div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-circle progress"><i class="bx bx-loader-alt"></i></div>
            <div><div class="stat-label">Dalam Pengerjaan</div><div class="stat-value">{{ $stats['in_progress'] }}</div><div class="stat-sub">In Progress</div></div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-circle done"><i class="bx bx-check-double"></i></div>
            <div><div class="stat-label">Selesai</div><div class="stat-value">{{ $stats['selesai'] }}</div><div class="stat-sub">Telah selesai dikerjakan</div></div>
        </div>
    </div>
</div>

{{-- ── Filter ── --}}
<form method="GET" action="{{ route('master-data-projek.index') }}" id="filterForm">
    <div class="filter-section">
        <div class="filter-row">
            <div class="filter-group">
                <label class="filter-label">Pencarian</label>
                <input type="text" name="search" class="filter-input" placeholder="Cari nama project, deskripsi, perusahaan..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <label class="filter-label">Kategori</label>
                <select name="id_kategori_projek" class="filter-select">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                    <option value="{{ $kat->id_kategori_projek }}" {{ request('id_kategori_projek') == $kat->id_kategori_projek ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Status</label>
                <select name="status" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="pending"     {{ request('status')=='pending'     ? 'selected':'' }}>Pending</option>
                    <option value="in_progress" {{ request('status')=='in_progress' ? 'selected':'' }}>In Progress</option>
                    <option value="aktif"       {{ request('status')=='aktif'       ? 'selected':'' }}>Aktif</option>
                    <option value="selesai"     {{ request('status')=='selesai'     ? 'selected':'' }}>Selesai</option>
                </select>
            </div>
            <input type="hidden" name="sort_by"    id="sortByInput"    value="{{ request('sort_by','dibuat_pada') }}">
            <input type="hidden" name="sort_order" id="sortOrderInput" value="{{ request('sort_order','desc') }}">
            <input type="hidden" name="per_page"   id="perPageInput"   value="{{ request('per_page', 10) }}">
            <div class="filter-group">
                <label class="filter-label">&nbsp;</label>
                <button type="submit" class="btn-filter"><i class="bx bx-search"></i> Filter</button>
            </div>
            <div class="filter-group">
                <label class="filter-label">&nbsp;</label>
                <a href="{{ route('master-data-projek.index') }}" class="btn-filter reset"><i class="bx bx-refresh"></i> Reset</a>
            </div>
        </div>
    </div>
</form>

{{-- ── Table ── --}}
<div class="table-container">
    <div class="table-header">
        <div>
            <h3 class="table-title">Daftar Project</h3>
            <span class="table-info">Menampilkan <strong>{{ $projeks->firstItem() ?? 0 }}–{{ $projeks->lastItem() ?? 0 }}</strong> dari <strong>{{ $projeks->total() }}</strong> project</span>
        </div>
        <div class="table-tools" style="display:flex;align-items:center;gap:10px;">
            <div class="per-page-wrap">
                <label class="per-page-label">Tampilkan</label>
                <select class="per-page-select" onchange="changePerPage(this.value)">
                    @foreach([10, 25, 50, 100] as $n)
                    <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
                <label class="per-page-label">data</label>
            </div>
            <div class="col-settings-wrapper">
                <button class="btn-col-settings" onclick="toggleColSettings(event)"><i class="bx bx-columns"></i> Kolom</button>
                <div class="col-settings-dropdown" id="colSettingsDropdown">
                    <div class="col-settings-title">Tampilkan Kolom</div>
                    @php
                        $cols = ['col-no'=>'No','col-info'=>'Informasi Project','col-kategori'=>'Kategori / PM','col-status'=>'Status','col-timeline'=>'Timeline','col-progress'=>'Progress','col-laporan'=>'Export','col-timestamps'=>'Dibuat/Diperbarui','col-aksi'=>'Aksi'];
                        // Hapus kolom Export jika tidak boleh export per project
                    @endphp
                    @foreach($cols as $cId => $cLbl)
                        @if($cId === 'col-laporan' && !$canExportProject)
                            @continue
                        @endif
                        <div class="col-settings-item">
                            <input type="checkbox" id="chk_{{ $cId }}" {{ $cId==='col-timestamps' ? '' : 'checked' }} onchange="toggleColumn('{{ $cId }}', this.checked)">
                            <label for="chk_{{ $cId }}">{{ $cLbl }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="project-table" id="projectTable">
            <thead>
                <tr>
                    <th class="col-no" style="width:46px;">No</th>
                    <th class="col-info sortable" onclick="sortBy('nama_projek')">Informasi Project <i class="bx sort-icon {{ request('sort_by')=='nama_projek' ? 'bx-chevron-'.(request('sort_order')=='asc'?'up':'down').' active' : 'bx-chevrons-up-down' }}"></i></th>
                    <th class="col-kategori sortable" onclick="sortBy('kategori')">Kategori / PM <i class="bx sort-icon {{ request('sort_by')=='kategori' ? 'bx-chevron-'.(request('sort_order')=='asc'?'up':'down').' active' : 'bx-chevrons-up-down' }}"></i></th>
                    <th class="col-status sortable" onclick="sortBy('status')" style="min-width:145px;">Status <i class="bx sort-icon {{ request('sort_by')=='status' ? 'bx-chevron-'.(request('sort_order')=='asc'?'up':'down').' active' : 'bx-chevrons-up-down' }}"></i></th>
                    <th class="col-timeline sortable" onclick="sortBy('tanggal_mulai')">Timeline <i class="bx sort-icon {{ request('sort_by')=='tanggal_mulai' ? 'bx-chevron-'.(request('sort_order')=='asc'?'up':'down').' active' : 'bx-chevrons-up-down' }}"></i></th>
                    <th class="col-progress" style="min-width:150px;">Progress</th>
                    @if($canExportProject)
                    <th class="col-laporan">Export</th>
                    @endif
                    <th class="col-timestamps" style="display:none; min-width:160px;">Dibuat / Diperbarui</th>
                    <th class="col-aksi" style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($projeks as $index => $projek)
            @php
                $today      = \Carbon\Carbon::today();
                $mulai      = $projek->tanggal_mulai   ? \Carbon\Carbon::parse($projek->tanggal_mulai)   : null;
                $selesai    = $projek->tanggal_selesai ? \Carbon\Carbon::parse($projek->tanggal_selesai) : null;
                $dibuatPada = $projek->dibuat_pada     ? \Carbon\Carbon::parse($projek->dibuat_pada)     : null;
                $diperbarui = $projek->diperbarui_pada ? \Carbon\Carbon::parse($projek->diperbarui_pada) : null;
                $isOverdue  = $selesai && $selesai->lt($today) && $projek->status !== 'selesai';
                $nondraftTugas  = $projek->tugas->filter(fn($t) => $t->status_progress !== 'draft');
                $approvedTugas  = $nondraftTugas->filter(fn($t) => $t->status_progress === 'done' && $t->status_akhir === 'approved');
                $tw             = $nondraftTugas->sum('weight');
                $aw             = $approvedTugas->sum('weight');
                $pg             = $tw > 0 ? round(($aw / $tw) * 100, 2) : 0;
                $approvedCount  = $approvedTugas->count();
                $nondraftCount  = $nondraftTugas->count();
                $pgColor = match($projek->status) { 'aktif'=>'#16a34a','in_progress'=>'#ea580c','selesai'=>'#5145cd',default=>'#9CA3AF' };
                $statusCls = match($projek->status) { 'aktif'=>'s-aktif','in_progress'=>'s-in_progress','selesai'=>'s-selesai',default=>'s-pending' };
                $pmNama    = optional($projek->pembuat)->nama ?? null;
                $desc      = $projek->deskripsi ?? '';
                $descShort = mb_strlen($desc) > 40 ? mb_substr($desc,0,40).'...' : $desc;
            @endphp
            <tr>
                <td class="col-no" style="text-align:center;"><span class="row-no">{{ $projeks->firstItem() + $index }}</span></td>

                <td class="col-info">
                    <div class="project-info">
                        <div class="project-icon-box"><i class="bx bx-folder"></i></div>
                        <div style="min-width:0;">
                            <div class="project-name">
                                {{ $projek->nama_projek }}
                                @if(optional($projek->perusahaan)->nama_perusahaan)
                                    — {{ $projek->perusahaan->nama_perusahaan }}
                                @endif
                            </div>
                            @if($descShort)<div class="project-desc-short" title="{{ $desc }}">{{ $descShort }}</div>@endif
                        </div>
                    </div>
                </td>

                <td class="col-kategori">
                    @if($projek->kategoriProjek)<span class="category-badge"><i class="bx bx-purchase-tag-alt"></i> {{ $projek->kategoriProjek->nama_kategori }}</span>
                    @else<span style="color:var(--ink-300);font-size:13px;">—</span>@endif
                    @if($pmNama)<div class="pm-badge"><i class="bx bx-user-check"></i><span>{{ $pmNama }}</span></div>@endif
                </td>

                {{-- Status: Admin & PM bisa ubah inline, Karyawan & Klien hanya lihat --}}
                <td class="col-status">
                    @if($canChangeStatus)
                    <div class="inline-status-wrap {{ $statusCls }}" id="status-wrap-{{ $projek->id_projek }}">
                        <span class="dot"></span>
                        <select class="inline-status-select" id="status-sel-{{ $projek->id_projek }}"
                            onchange="updateStatusInline({{ $projek->id_projek }}, this)"
                            title="Klik untuk ubah status">
                            <option value="pending"     {{ $projek->status==='pending'     ? 'selected':'' }}>Pending</option>
                            <option value="in_progress" {{ $projek->status==='in_progress' ? 'selected':'' }}>In Progress</option>
                            <option value="aktif"       {{ $projek->status==='aktif'       ? 'selected':'' }}>Aktif</option>
                            <option value="selesai"     {{ $projek->status==='selesai'     ? 'selected':'' }}>Selesai</option>
                        </select>
                    </div>
                    @else
                    {{-- Karyawan & Klien: status hanya tampil, tidak bisa diubah --}}
                    <div class="inline-status-wrap {{ $statusCls }}">
                        <span class="dot"></span>
                        <span style="font-size:13px;font-weight:600;padding:4px 8px;">
                            {{ match($projek->status) { 'aktif'=>'Aktif','in_progress'=>'In Progress','selesai'=>'Selesai',default=>'Pending' } }}
                        </span>
                    </div>
                    @endif
                </td>

                <td class="col-timeline">
                    <div class="date-info">
                        <div class="date-row">
                            <i class="bx bx-calendar-plus" style="color:#16a34a;"></i>
                            @if($mulai)
                                <span class="date-value mulai">{{ $mulai->format('j F Y') }}</span>
                            @else
                                <span class="date-na">—</span>
                            @endif
                        </div>
                        <div class="date-row">
                            <i class="bx bx-calendar-check" style="color:{{ $isOverdue ? '#dc2626' : 'var(--p2)' }};"></i>
                            @if($selesai)
                                <span class="date-value {{ $isOverdue ? 'overdue' : 'selesai' }}">
                                    {{ $selesai->format('j F Y') }}
                                    @if($isOverdue)<i class="bx bx-error-circle" title="Overdue" style="font-size:11px;"></i>@endif
                                </span>
                            @else
                                <span class="date-na">—</span>
                            @endif
                        </div>
                    </div>
                </td>

                <td class="col-progress">
                    <div class="prog-wrap" id="prog-wrap-{{ $projek->id_projek }}">
                        <div class="prog-header">
                            <span class="prog-pct" id="prog-pct-{{ $projek->id_projek }}" style="color:{{ $pgColor }};">{{ $pg }}%</span>
                            <span class="prog-weight">{{ $aw }}/{{ $tw }}</span>
                        </div>
                        <div class="prog-track"><div class="prog-fill" id="prog-fill-{{ $projek->id_projek }}" style="width:{{ $pg }}%; background:{{ $pgColor }};"></div></div>
                        <div class="prog-label">{{ $approvedCount }} dari {{ $nondraftCount }} tugas approved</div>
                    </div>
                </td>

                {{-- Export per project --}}
                @if($canExportProject)
                <td class="col-laporan">
                    <button class="report-btn" onclick="exportProjectPDF({{ $projek->id_projek }})">
                        <i class="bx bxs-file-pdf"></i> Export
                    </button>
                </td>
                @endif

                <td class="col-timestamps" style="display:none;">
                    <div class="date-info">
                        <div class="date-row"><i class="bx bx-calendar-plus" style="color:var(--p1);font-size:13px;"></i><span style="font-size:11px;color:var(--ink-600);font-weight:500;">{{ $dibuatPada ? $dibuatPada->format('d M Y') : '—' }}</span></div>
                        <div class="date-row"><i class="bx bx-revision" style="color:var(--ink-400);font-size:13px;"></i><span style="font-size:11px;color:var(--ink-400);">{{ $diperbarui ? $diperbarui->format('d M Y') : '—' }}</span></div>
                    </div>
                </td>

          <td class="col-aksi">
    <div class="action-buttons">
        @if($isKlien)
            {{-- Klien: hanya View Detail --}}
            <button type="button" class="action-btn view" title="Lihat Detail"
                onclick="openViewModal({{ $projek->id_projek }})">
                <i class="bx bx-show"></i>
            </button>
        @else
            {{-- Kelola Task: PM & Admin → kelolatask | Karyawan → taskkaryawan --}}
            @if($isKaryawan)
            <a href="{{ route('dashboard.taskkaryawan') }}?id_projek={{ $projek->id_projek }}"
               class="btn btn-sm"
               style="background:#EEF2FF;color:#4F46E5;border:1px solid #C7D2FE;
               border-radius:8px;width:36px;height:36px;display:inline-flex;align-items:center;
               justify-content:center;font-size:16px;transition:all 0.2s;"
               onmouseover="this.style.background='#4F46E5';this.style.color='white';"
               onmouseout="this.style.background='#EEF2FF';this.style.color='#4F46E5';"
               title="Task Saya">
                <i class="bx bx-task"></i>
            </a>
            @else
            <a href="{{ route('task.index', $projek->id_projek) }}"
               class="btn btn-sm"
               style="background:#EEF2FF;color:#4F46E5;border:1px solid #C7D2FE;
               border-radius:8px;width:36px;height:36px;display:inline-flex;
               align-items:center;justify-content:center;font-size:16px;transition:all 0.2s;"
               onmouseover="this.style.background='#4F46E5';this.style.color='white';"
               onmouseout="this.style.background='#EEF2FF';this.style.color='#4F46E5';"
               title="Kelola Task">
                <i class="bx bx-task"></i>
            </a>
            @endif

            {{-- View Detail --}}
            <button type="button" class="action-btn view" title="Lihat Detail"
                onclick="openViewModal({{ $projek->id_projek }})">
                <i class="bx bx-show"></i>
            </button>

            {{-- Edit: hanya Admin & PM --}}
            @if($canEdit)
            <button type="button" class="action-btn edit" title="Edit Project"
                onclick="openEditModal({{ $projek->id_projek }})">
                <i class="bx bx-edit-alt"></i>
            </button>
            @endif

            {{-- Hapus: hanya Admin --}}
            @if($canDelete)
            <button type="button" class="action-btn delete" title="Hapus Project"
                onclick="confirmDelete({{ $projek->id_projek }}, '{{ addslashes($projek->nama_projek) }}', '{{ route('master-data-projek.destroy', $projek->id_projek) }}')">
                <i class="bx bx-trash-alt"></i>
            </button>
            @endif
        @endif
    </div>
</td>
            </tr>
            @empty
            <tr>
                <td colspan="9">
                    <div class="empty-state">
                        <i class="bx bx-folder-open"></i>
                        <h5>Tidak ada project ditemukan</h5>
                        <p>Coba ubah filter pencarian atau tambahkan project baru.</p>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($projeks->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Menampilkan <strong>{{ $projeks->firstItem() }}–{{ $projeks->lastItem() }}</strong> dari <strong>{{ $projeks->total() }}</strong> project
        </div>
        <nav class="custom-pagination">
            <ul class="custom-page-list">
                @if($projeks->onFirstPage())
                <li class="cp-item disabled"><span class="cp-link"><i class="bx bx-chevron-left"></i></span></li>
                @else
                <li class="cp-item"><a class="cp-link" href="{{ $projeks->appends(request()->query())->previousPageUrl() }}"><i class="bx bx-chevron-left"></i></a></li>
                @endif
                @php
                    $current = $projeks->currentPage();
                    $last    = $projeks->lastPage();
                    $window  = 2;
                    $pages   = collect();
                    $pages->push(1);
                    for ($i = max(2, $current - $window); $i < $current; $i++) { $pages->push($i); }
                    if ($current > 1) $pages->push($current);
                    for ($i = $current + 1; $i <= min($last - 1, $current + $window); $i++) { $pages->push($i); }
                    if ($last > 1) $pages->push($last);
                    $pages = $pages->unique()->sort()->values();
                @endphp
                @php $prev = null; @endphp
                @foreach($pages as $page)
                    @if($prev !== null && $page - $prev > 1)
                    <li class="cp-item cp-ellipsis"><span class="cp-link">...</span></li>
                    @endif
                    <li class="cp-item {{ $page == $current ? 'active' : '' }}">
                        <a class="cp-link" href="{{ $projeks->appends(request()->query())->url($page) }}">{{ $page }}</a>
                    </li>
                    @php $prev = $page; @endphp
                @endforeach
                @if($projeks->hasMorePages())
                <li class="cp-item"><a class="cp-link" href="{{ $projeks->appends(request()->query())->nextPageUrl() }}"><i class="bx bx-chevron-right"></i></a></li>
                @else
                <li class="cp-item disabled"><span class="cp-link"><i class="bx bx-chevron-right"></i></span></li>
                @endif
            </ul>
        </nav>
    </div>
    @endif
</div>

{{-- ══════════════════ MODAL TAMBAH (hanya Admin & PM) ══════════════════ --}}
@if($canCreate)
<div class="modal fade" id="modalTambahProject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('master-data-projek.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header-custom">
                    <span class="modal-title"><i class="bx bx-plus-circle"></i> Tambah Project Baru</span>
                    <button type="button" class="modal-close-btn" data-bs-dismiss="modal" aria-label="Tutup"><i class="bx bx-x"></i></button>
                </div>
                <div class="modal-body">
                    <div class="modal-section-title">Informasi Dasar</div>
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label class="form-label-custom">Nama Project <span style="color:#dc2626">*</span></label>
                            <input type="text" name="nama_projek" class="form-control-custom {{ $errors->has('nama_projek') ? 'is-invalid-custom' : '' }}" placeholder="Masukkan nama project" value="{{ old('nama_projek') }}" required>
                            @error('nama_projek')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>
                        {{-- Penanggung Jawab: Admin bisa pilih PM, PM otomatis dirinya sendiri --}}
                        @if($isAdmin)
                        <div class="col-12">
                            <label class="form-label-custom">
                                Penanggung Jawab (PM) <span style="color:#dc2626">*</span>
                                <span class="fc-lock-badge" style="background:#EFF6FF;color:#1D4ED8;border-color:#BFDBFE;"><i class="bx bx-user-check"></i> Role PM</span>
                            </label>
                            <div class="sd-wrap" id="sd-tambah-pm">
                                <input type="hidden" name="dibuat_oleh" id="tambah_dibuat_oleh" value="{{ old('dibuat_oleh') }}">
                                <div class="sd-input-wrap">
                                    <input type="text" class="sd-input {{ $errors->has('dibuat_oleh') ? 'sd-invalid' : '' }}" id="tambah_pm_display" placeholder="Cari &amp; pilih Project Manager..." autocomplete="off" readonly tabindex="0">
                                    <button type="button" class="sd-clear-btn" tabindex="-1"><i class="bx bx-x"></i></button>
                                    <i class="bx bx-chevron-down sd-chevron"></i>
                                </div>
                                <div class="sd-dropdown">
                                    <div class="sd-search-bar"><i class="bx bx-search"></i><input type="text" class="sd-search-input" placeholder="Ketik nama PM..."></div>
                                    @foreach($pmList as $pm)
                                    <div class="sd-option" data-value="{{ $pm->id_user }}" data-label="{{ $pm->nama }}" data-sub="{{ $pm->email ?? '' }}">
                                        <div class="sd-option-icon" style="background:#EFF6FF;color:#1D4ED8;"><i class="bx bx-user-check"></i></div>
                                        <div class="sd-option-main">
                                            <span class="sd-option-label">{{ $pm->nama }}</span>
                                            @if($pm->email)<span class="sd-option-sub">{{ $pm->email }}</span>@endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @error('dibuat_oleh')<div class="invalid-msg mt-1">{{ $message }}</div>@enderror
                            <div class="field-hint">Pilih Project Manager yang bertanggung jawab atas project ini</div>
                        </div>
                        @else
                        {{-- PM: info bahwa dirinya otomatis jadi penanggung jawab --}}
                        <div class="col-12">
                            <label class="form-label-custom">Penanggung Jawab (PM)</label>
                            <div class="form-control-custom" style="background:#F9FAFB;color:#374151;display:flex;align-items:center;gap:10px;cursor:not-allowed;">
                                <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#696cff,#5145cd);color:white;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">
                                    {{ strtoupper(substr(auth()->user()->nama ?? 'PM', 0, 2)) }}
                                </div>
                                <div>
                                    <div style="font-weight:700;font-size:13px;">{{ auth()->user()->nama }}</div>
                                    <div style="font-size:11px;color:#6B7280;">Project Manager — Anda otomatis menjadi penanggung jawab</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <label class="form-label-custom">Perusahaan <span style="color:#dc2626">*</span></label>
                            <div class="sd-wrap" id="sd-tambah-perusahaan">
                                <input type="hidden" name="id_perusahaan" id="tambah_id_perusahaan" value="{{ old('id_perusahaan') }}">
                                <div class="sd-input-wrap">
                                    <input type="text" class="sd-input {{ $errors->has('id_perusahaan') ? 'sd-invalid' : '' }}" id="tambah_perusahaan_display" placeholder="Cari &amp; pilih perusahaan..." autocomplete="off" readonly tabindex="0">
                                    <button type="button" class="sd-clear-btn" tabindex="-1"><i class="bx bx-x"></i></button>
                                    <i class="bx bx-chevron-down sd-chevron"></i>
                                </div>
                                <div class="sd-dropdown">
                                    <div class="sd-search-bar"><i class="bx bx-search"></i><input type="text" class="sd-search-input" placeholder="Ketik untuk mencari..."></div>
                                    <div class="sd-option-none" data-value="" data-label="">— Tidak ada / Kosongkan —</div>
                                    @foreach($perusahaans as $p)
                                    <div class="sd-option" data-value="{{ $p->id_perusahaan }}" data-label="{{ $p->nama_perwakilan }}{{ $p->nama_perusahaan ? ' – '.$p->nama_perusahaan : '' }}" data-sub="{{ $p->nama_perusahaan ?? '' }}">
                                        <div class="sd-option-icon"><i class="bx bx-buildings"></i></div>
                                        <div class="sd-option-main">
                                            <span class="sd-option-label">{{ $p->nama_perwakilan }}</span>
                                            @if($p->nama_perusahaan)<span class="sd-option-sub">{{ $p->nama_perusahaan }}</span>@endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @error('id_perusahaan')<div class="invalid-msg mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Kategori Project</label>
                            <div class="sd-wrap" id="sd-tambah-kategori">
                                <input type="hidden" name="id_kategori_projek" id="tambah_id_kategori_projek" value="{{ old('id_kategori_projek') }}">
                                <div class="sd-input-wrap">
                                    <input type="text" class="sd-input" id="tambah_kategori_display" placeholder="Cari &amp; pilih kategori..." autocomplete="off" readonly tabindex="0">
                                    <button type="button" class="sd-clear-btn" tabindex="-1"><i class="bx bx-x"></i></button>
                                    <i class="bx bx-chevron-down sd-chevron"></i>
                                </div>
                                <div class="sd-dropdown">
                                    <div class="sd-search-bar"><i class="bx bx-search"></i><input type="text" class="sd-search-input" placeholder="Ketik untuk mencari..."></div>
                                    <div class="sd-option-none" data-value="" data-label="">— Tanpa Kategori —</div>
                                    @foreach($kategoris as $kat)
                                    <div class="sd-option" data-value="{{ $kat->id_kategori_projek }}" data-label="{{ $kat->nama_kategori }}">
                                        <div class="sd-option-icon sd-icon-kat"><i class="bx bx-purchase-tag-alt"></i></div>
                                        <div class="sd-option-main"><span class="sd-option-label">{{ $kat->nama_kategori }}</span></div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Deskripsi</label>
                            <textarea name="deskripsi" rows="3" class="form-control-custom" placeholder="Deskripsi singkat tentang project...">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-section-title">Status &amp; Timeline</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label-custom">Status <span style="color:#dc2626">*</span></label>
                            <select name="status" class="form-control-custom" required>
                                <option value="pending"     {{ old('status','pending')=='pending'     ? 'selected':'' }}>Pending</option>
                                <option value="in_progress" {{ old('status')=='in_progress' ? 'selected':'' }}>In Progress</option>
                                <option value="aktif"       {{ old('status')=='aktif'       ? 'selected':'' }}>Aktif</option>
                                <option value="selesai"     {{ old('status')=='selesai'     ? 'selected':'' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control-custom" value="{{ old('tanggal_mulai', now()->format('Y-m-d')) }}">
                            <div class="field-hint">Default: hari ini</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Target Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control-custom" value="{{ old('tanggal_selesai') }}">
                        </div>
                    </div>
                    @if($isAdmin)
                    <div class="modal-section-title">Keuangan</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Nominal Project (Rp) <span style="color:#dc2626">*</span></label>
                            <div style="position:relative;">
                                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--ink-500);font-size:13px;pointer-events:none;">Rp</span>
                                <input type="text" id="tambah_nominal_display" class="form-control-custom" placeholder="0" style="padding-left:32px;" autocomplete="off">
                            </div>
                            <input type="hidden" name="nominal_projek" id="tambah_nominal_projek" value="{{ old('nominal_projek', 0) }}">
                            @error('nominal_projek')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Sisa Tanggungan (Rp) <span class="fc-lock-badge"><i class="bx bx-link-alt"></i> Otomatis</span></label>
                            <input type="text" id="tambah_sisa_display" class="form-control-custom" readonly placeholder="Rp 0" value="Rp {{ number_format(old('nominal_projek', 0), 0, ',', '.') }}">
                            <input type="hidden" name="sisa_tanggungan" id="tambah_sisa_tanggungan" value="{{ old('nominal_projek', 0) }}">
                            <div class="field-hint">Mengikuti nominal project secara otomatis</div>
                        </div>
                    </div>
                    @else
                    {{-- PM: nominal disembunyikan, kirim default 0 --}}
                    <input type="hidden" name="nominal_projek" value="0">
                    @endif
                    <div class="modal-section-title">Dokumen</div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Dokumen Perjanjian</label>
                            <input type="file" name="dokumen_perjanjian" class="form-control-custom" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp">
                            <div class="field-hint">Format: PDF, DOC, DOCX, JPG, PNG, WEBP — Maks. 5 MB</div>
                            @error('dokumen_perjanjian')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-action btn-outline-custom" data-bs-dismiss="modal"><i class="bx bx-x"></i> Batal</button>
                    <button type="submit" class="btn-action btn-primary-custom"><i class="bx bx-save"></i> Simpan Project</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════ MODAL EDIT (hanya Admin & PM) ══════════════════ --}}
@if($canEdit)
<div class="modal fade" id="modalEditProject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form id="editForm" action="" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <input type="hidden" name="sisa_tanggungan" id="edit_sisa_tanggungan" value="0">
                <div class="modal-header-custom">
                    <span class="modal-title"><i class="bx bx-edit-alt"></i> Edit Project</span>
                    <button type="button" class="modal-close-btn" data-bs-dismiss="modal" aria-label="Tutup"><i class="bx bx-x"></i></button>
                </div>
                <div class="modal-body">
                    <div class="modal-section-title">Informasi Dasar</div>
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label class="form-label-custom">Nama Project <span style="color:#dc2626">*</span></label>
                            <input type="text" name="nama_projek" id="edit_nama_projek" class="form-control-custom" required>
                        </div>
                        {{-- Penanggung Jawab: Admin bisa ubah, PM readonly --}}
                        @if($isAdmin)
                        <div class="col-12">
                            <label class="form-label-custom">
                                Penanggung Jawab (PM) <span style="color:#dc2626">*</span>
                                <span class="fc-lock-badge" style="background:#EFF6FF;color:#1D4ED8;border-color:#BFDBFE;"><i class="bx bx-user-check"></i> Role PM</span>
                            </label>
                            <div class="sd-wrap" id="sd-edit-pm">
                                <input type="hidden" name="dibuat_oleh" id="edit_dibuat_oleh">
                                <div class="sd-input-wrap">
                                    <input type="text" class="sd-input" id="edit_pm_display" placeholder="Cari &amp; pilih Project Manager..." autocomplete="off" readonly tabindex="0">
                                    <button type="button" class="sd-clear-btn" tabindex="-1"><i class="bx bx-x"></i></button>
                                    <i class="bx bx-chevron-down sd-chevron"></i>
                                </div>
                                <div class="sd-dropdown">
                                    <div class="sd-search-bar"><i class="bx bx-search"></i><input type="text" class="sd-search-input" placeholder="Ketik nama PM..."></div>
                                    @foreach($pmList as $pm)
                                    <div class="sd-option" data-value="{{ $pm->id_user }}" data-label="{{ $pm->nama }}" data-sub="{{ $pm->email ?? '' }}">
                                        <div class="sd-option-icon" style="background:#EFF6FF;color:#1D4ED8;"><i class="bx bx-user-check"></i></div>
                                        <div class="sd-option-main">
                                            <span class="sd-option-label">{{ $pm->nama }}</span>
                                            @if($pm->email)<span class="sd-option-sub">{{ $pm->email }}</span>@endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="field-hint">Ubah Project Manager yang bertanggung jawab atas project ini</div>
                        </div>
                        @else
                        {{-- PM: tampilkan info PM yang sedang login, tidak bisa diubah --}}
                        <div class="col-12">
                            <label class="form-label-custom">Penanggung Jawab (PM) <span class="fc-lock-badge"><i class="bx bx-lock-alt"></i> Terkunci</span></label>
                            <div id="edit_pm_readonly_display" class="form-control-custom" style="background:#F9FAFB;color:#374151;display:flex;align-items:center;gap:10px;cursor:not-allowed;">
                                <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#696cff,#5145cd);color:white;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;" id="edit_pm_avatar">PM</div>
                                <div>
                                    <div style="font-weight:700;font-size:13px;" id="edit_pm_nama_display">—</div>
                                    <div style="font-size:11px;color:#6B7280;">Project Manager — tidak dapat diubah</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <label class="form-label-custom">Perusahaan <span style="color:#dc2626">*</span></label>
                            <div class="sd-wrap" id="sd-edit-perusahaan">
                                <input type="hidden" name="id_perusahaan" id="edit_id_perusahaan" required>
                                <div class="sd-input-wrap">
                                    <input type="text" class="sd-input" id="edit_perusahaan_display" placeholder="Cari &amp; pilih perusahaan..." autocomplete="off" readonly tabindex="0">
                                    <button type="button" class="sd-clear-btn" tabindex="-1"><i class="bx bx-x"></i></button>
                                    <i class="bx bx-chevron-down sd-chevron"></i>
                                </div>
                                <div class="sd-dropdown">
                                    <div class="sd-search-bar"><i class="bx bx-search"></i><input type="text" class="sd-search-input" placeholder="Ketik untuk mencari..."></div>
                                    <div class="sd-option-none" data-value="" data-label="">— Tidak ada / Kosongkan —</div>
                                    @foreach($perusahaans as $p)
                                    <div class="sd-option" data-value="{{ $p->id_perusahaan }}" data-label="{{ $p->nama_perwakilan }}{{ $p->nama_perusahaan ? ' – '.$p->nama_perusahaan : '' }}" data-sub="{{ $p->nama_perusahaan ?? '' }}">
                                        <div class="sd-option-icon"><i class="bx bx-buildings"></i></div>
                                        <div class="sd-option-main">
                                            <span class="sd-option-label">{{ $p->nama_perwakilan }}</span>
                                            @if($p->nama_perusahaan)<span class="sd-option-sub">{{ $p->nama_perusahaan }}</span>@endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Kategori Project</label>
                            <div class="sd-wrap" id="sd-edit-kategori">
                                <input type="hidden" name="id_kategori_projek" id="edit_id_kategori_projek">
                                <div class="sd-input-wrap">
                                    <input type="text" class="sd-input" id="edit_kategori_display" placeholder="Cari &amp; pilih kategori..." autocomplete="off" readonly tabindex="0">
                                    <button type="button" class="sd-clear-btn" tabindex="-1"><i class="bx bx-x"></i></button>
                                    <i class="bx bx-chevron-down sd-chevron"></i>
                                </div>
                                <div class="sd-dropdown">
                                    <div class="sd-search-bar"><i class="bx bx-search"></i><input type="text" class="sd-search-input" placeholder="Ketik untuk mencari..."></div>
                                    <div class="sd-option-none" data-value="" data-label="">— Tanpa Kategori —</div>
                                    @foreach($kategoris as $kat)
                                    <div class="sd-option" data-value="{{ $kat->id_kategori_projek }}" data-label="{{ $kat->nama_kategori }}">
                                        <div class="sd-option-icon sd-icon-kat"><i class="bx bx-purchase-tag-alt"></i></div>
                                        <div class="sd-option-main"><span class="sd-option-label">{{ $kat->nama_kategori }}</span></div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Deskripsi</label>
                            <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="form-control-custom" placeholder="Deskripsi singkat..."></textarea>
                        </div>
                    </div>
                    <div class="modal-section-title">Status &amp; Timeline</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label-custom">Status <span style="color:#dc2626">*</span></label>
                            <select name="status" id="edit_status" class="form-control-custom" required>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="aktif">Aktif</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Tanggal Mulai <span class="fc-lock-badge"><i class="bx bx-lock-alt"></i> Terkunci</span></label>
                            <input type="date" name="tanggal_mulai" id="edit_tanggal_mulai" class="form-control-custom" readonly>
                            <div class="field-hint">Tidak dapat diubah setelah project dibuat</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Target Selesai</label>
                            <input type="date" name="tanggal_selesai" id="edit_tanggal_selesai" class="form-control-custom">
                        </div>
                    </div>
                    @if($isAdmin)
                    <div class="modal-section-title">Keuangan</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Nominal Project (Rp) <span style="color:#dc2626">*</span></label>
                            <div style="position:relative;">
                                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--ink-500);font-size:13px;pointer-events:none;">Rp</span>
                                <input type="text" id="edit_nominal_display" class="form-control-custom" placeholder="0" style="padding-left:32px;" autocomplete="off">
                            </div>
                            <input type="hidden" name="nominal_projek" id="edit_nominal_projek">
                            <div class="field-hint">Nilai kontrak total project</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Sisa Tanggungan (Rp) <span class="fc-lock-badge"><i class="bx bx-lock-alt"></i> Readonly</span></label>
                            <input type="text" id="edit_sisa_display" class="form-control-custom" readonly placeholder="Rp 0">
                            <div class="field-hint">Dikelola oleh sistem pembayaran</div>
                        </div>
                    </div>
                    @else
                    {{-- PM: nominal tidak ditampilkan, nilai lama dipertahankan oleh controller --}}
                    <input type="hidden" name="nominal_projek" id="edit_nominal_projek" value="0">
                    @endif
                    <div class="modal-section-title">Dokumen</div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Ganti Dokumen Perjanjian</label>
                            <div id="edit_current_doc" style="display:none;">
                                <a id="edit_doc_link" href="#" target="_blank"><i class="bx bx-file-blank"></i> Lihat Dokumen Saat Ini</a>
                            </div>
                            <input type="file" name="dokumen_perjanjian" class="form-control-custom" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp">
                            <div class="field-hint">Kosongkan jika tidak ingin mengganti. PDF/DOC/DOCX/JPG/PNG/WEBP, maks 5 MB</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-action btn-outline-custom" data-bs-dismiss="modal"><i class="bx bx-x"></i> Batal</button>
                    <button type="submit" class="btn-action btn-primary-custom"><i class="bx bx-save"></i> Update Project</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════ MODAL VIEW ══════════════════ --}}
<div class="modal fade" id="modalViewProject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header-custom view-header">
                <span class="modal-title"><i class="bx bx-info-circle"></i> Detail Project</span>
                <button type="button" class="modal-close-btn" data-bs-dismiss="modal" aria-label="Tutup"><i class="bx bx-x"></i></button>
            </div>
            <div class="modal-body">
                <div class="view-section">
                    <div class="view-section-title"><i class="bx bx-info-circle"></i> Informasi Umum</div>
                    <div class="view-row"><span class="view-label">Nama Project</span><span class="view-value" id="view_nama_projek">—</span></div>
                    <div class="view-row"><span class="view-label">Perusahaan</span><span class="view-value" id="view_perusahaan">—</span></div>
                    <div class="view-row"><span class="view-label">Kategori</span><span class="view-value" id="view_kategori">—</span></div>
                    <div class="view-row"><span class="view-label">Status</span><span class="view-value" id="view_status">—</span></div>
                    <div class="view-row"><span class="view-label">Dibuat Oleh (PM)</span><span class="view-value" id="view_pembuat">—</span></div>
                    <div class="view-row"><span class="view-label">Deskripsi</span><span class="view-value" id="view_deskripsi" style="font-weight:400;">—</span></div>
                </div>
                <div class="view-section">
                    <div class="view-section-title"><i class="bx bx-calendar"></i> Timeline</div>
                    <div class="view-row"><span class="view-label">Tanggal Mulai</span><span class="view-value" id="view_tanggal_mulai">—</span></div>
                    <div class="view-row"><span class="view-label">Target Selesai</span><span class="view-value" id="view_tanggal_selesai">—</span></div>
                </div>
                {{-- Nominal hanya untuk Admin & Klien --}}
                @if(!$hideNominal)
                <div class="view-section" id="view_section_keuangan">
                    <div class="view-section-title"><i class="bx bx-money"></i> Keuangan</div>
                    <div class="view-row"><span class="view-label">Nominal Project</span><span class="view-value" id="view_nominal_projek">—</span></div>
                    <div class="view-row"><span class="view-label">Sisa Tagihan</span><span class="view-value" id="view_sisa_tanggungan">—</span></div>
                </div>
                @endif
                <div class="view-section">
                    <div class="view-section-title"><i class="bx bx-bar-chart-alt-2"></i> Progress Tugas</div>
                    <div class="view-row"><span class="view-label">Persentase</span><span class="view-value" id="view_progress" style="flex:1;">—</span></div>
                    <div class="view-row"><span class="view-label">Detail Tugas</span><span class="view-value" id="view_progress_detail">—</span></div>
                </div>
                <div class="view-section">
                    <div class="view-section-title"><i class="bx bx-file"></i> Dokumen</div>
                    <div class="view-row"><span class="view-label">Dok. Perjanjian</span><span class="view-value" id="view_dokumen">—</span></div>
                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-action btn-outline-custom" data-bs-dismiss="modal"><i class="bx bx-x"></i> Tutup</button>
                @if($canEdit)
                <button type="button" class="btn-action btn-primary-custom" id="view_edit_btn"><i class="bx bx-edit-alt"></i> Edit Project</button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════ MODAL KONFIRMASI HAPUS (hanya Admin) ══════════════════ --}}
@if($canDelete)
<div class="modal fade" id="modalHapusProject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content">
            <div class="modal-header-custom delete-header">
                <span class="modal-title"><i class="bx bx-error-circle"></i> Konfirmasi Hapus</span>
                <button type="button" class="modal-close-btn" data-bs-dismiss="modal" aria-label="Tutup"><i class="bx bx-x"></i></button>
            </div>
            <div class="modal-body" style="padding:28px 24px 20px;">
                <div class="delete-confirm-body">
                    <div class="delete-icon-wrap"><i class="bx bx-trash-alt"></i></div>
                    <h5 class="delete-confirm-title">Hapus Data Project?</h5>
                    <p class="delete-confirm-desc">Anda akan menghapus project:</p>
                    <div class="delete-target-name" id="deleteProjectName">—</div>
                    <p class="delete-confirm-warn">Tindakan ini <strong>tidak dapat dibatalkan</strong> dan seluruh data terkait project ini akan ikut terhapus.</p>
                </div>
            </div>
            <div class="modal-footer-custom" style="justify-content:center;gap:12px;">
                <button type="button" class="btn-action btn-outline-custom" data-bs-dismiss="modal" style="min-width:120px;"><i class="bx bx-x"></i> Batal</button>
                <form id="deleteForm" action="" method="POST" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-action btn-danger-custom" style="min-width:120px;"><i class="bx bx-trash-alt"></i> Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════ PDF PREVIEW MODAL ══════════════════ --}}
<div id="pdfPreviewModalProject">
    <div id="pdfPreviewBackdropProject" onclick="closePdfPreviewProject()"></div>
    <div id="pdfPreviewBoxProject">
        <div id="pdfPreviewToolbarProject">
            <h6>&#128196; Preview Laporan Task</h6>
            <button class="pdf-toolbar-btn-p print-btn" onclick="printPDFProject()">&#128424; Cetak / Simpan PDF</button>
            <button class="pdf-toolbar-btn-p" onclick="closePdfPreviewProject()">&#10005; Tutup</button>
        </div>
        <div id="pdfPreviewContentProject"></div>
    </div>
</div>

<div id="__toast"><i id="__toast-icon" class="bx"></i><span id="__toast-msg"></span></div>
@endsection

@push('scripts')
<script>
'use strict';

/* ── Role flags dari server ── */
const ROLE_CAN_EDIT          = @json($canEdit);
const ROLE_CAN_DELETE        = @json($canDelete);
const ROLE_CAN_EXPORT_ALL    = @json($canExportAll);
const ROLE_CAN_EXPORT_PROJ   = @json($canExportProject);
const ROLE_CAN_CHANGE_STATUS = @json($canChangeStatus);
const ROLE_HIDE_NOMINAL      = @json($hideNominal);

/* ══════════════════════════════════════════════════════════════════
   SEARCHABLE DROPDOWN ENGINE
══════════════════════════════════════════════════════════════════ */
function initSearchDropdown(wrapEl) {
    if (!wrapEl || wrapEl.__sdInit) return;
    wrapEl.__sdInit = true;
    const hiddenInput  = wrapEl.querySelector('input[type="hidden"]');
    const displayInput = wrapEl.querySelector('.sd-input');
    const dropdown     = wrapEl.querySelector('.sd-dropdown');
    const searchInput  = wrapEl.querySelector('.sd-search-input');
    const clearBtn     = wrapEl.querySelector('.sd-clear-btn');
    const allOptions   = Array.from(wrapEl.querySelectorAll('.sd-option, .sd-option-none'));
    let focusedIdx = -1;

    function openDropdown() {
        document.querySelectorAll('.sd-wrap.open').forEach(other => {
            if (other !== wrapEl) other.__sdClose && other.__sdClose();
        });
        wrapEl.classList.add('open');
        searchInput.value = '';
        filterOptions('');
        focusedIdx = -1;
        setTimeout(() => searchInput.focus(), 40);
    }
    function closeDropdown() {
        wrapEl.classList.remove('open');
        focusedIdx = -1;
        allOptions.forEach(o => o.classList.remove('sd-focused'));
    }
    wrapEl.__sdClose = closeDropdown;

    displayInput.addEventListener('click', () => {
        wrapEl.classList.contains('open') ? closeDropdown() : openDropdown();
    });
    displayInput.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
            e.preventDefault(); openDropdown();
        }
    });

    function filterOptions(query) {
        const q = query.trim().toLowerCase();
        let visible = 0;
        allOptions.forEach(opt => {
            const label = (opt.dataset.label || '').toLowerCase();
            const sub   = (opt.dataset.sub   || '').toLowerCase();
            const match = !q || label.includes(q) || sub.includes(q);
            opt.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        let noResult = dropdown.querySelector('.sd-no-results');
        if (visible === 0 && q) {
            if (!noResult) { noResult = document.createElement('div'); noResult.className = 'sd-no-results'; dropdown.appendChild(noResult); }
            noResult.innerHTML = '<i class="bx bx-search-alt"></i>Tidak ditemukan: <strong>' + escHtml(query) + '</strong>';
            noResult.style.display = '';
        } else if (noResult) noResult.style.display = 'none';
    }

    searchInput.addEventListener('input', () => { filterOptions(searchInput.value); focusedIdx = -1; updateFocus(); });

    function getVisibleOptions() { return allOptions.filter(o => o.style.display !== 'none'); }
    function updateFocus() {
        const vis = getVisibleOptions();
        vis.forEach((o, i) => o.classList.toggle('sd-focused', i === focusedIdx));
        if (focusedIdx >= 0 && vis[focusedIdx]) vis[focusedIdx].scrollIntoView({ block:'nearest' });
    }

    searchInput.addEventListener('keydown', e => {
        const vis = getVisibleOptions();
        if      (e.key === 'ArrowDown')  { e.preventDefault(); focusedIdx = Math.min(focusedIdx+1, vis.length-1); updateFocus(); }
        else if (e.key === 'ArrowUp')    { e.preventDefault(); focusedIdx = Math.max(focusedIdx-1, 0); updateFocus(); }
        else if (e.key === 'Enter')      { e.preventDefault(); if (focusedIdx >= 0 && vis[focusedIdx]) selectOption(vis[focusedIdx]); }
        else if (e.key === 'Escape')     { closeDropdown(); displayInput.focus(); }
        else if (e.key === 'Tab')        { closeDropdown(); }
    });

    function selectOption(optEl) {
        const val   = optEl.dataset.value || '';
        const label = optEl.dataset.label || '';
        hiddenInput.value  = val;
        displayInput.value = label;
        allOptions.forEach(o => o.classList.remove('sd-selected'));
        if (val) optEl.classList.add('sd-selected');
        wrapEl.classList.toggle('has-value', !!val);
        closeDropdown();
        displayInput.focus();
        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
    }

    allOptions.forEach(opt => opt.addEventListener('click', () => selectOption(opt)));
    clearBtn.addEventListener('click', e => {
        e.stopPropagation();
        hiddenInput.value  = '';
        displayInput.value = '';
        allOptions.forEach(o => o.classList.remove('sd-selected'));
        wrapEl.classList.remove('has-value');
        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        displayInput.focus();
    });
    document.addEventListener('click', e => { if (!wrapEl.contains(e.target)) closeDropdown(); });

    wrapEl.sdSetValue = function(value, label) {
        const strVal = value ? String(value) : '';
        hiddenInput.value  = strVal;
        displayInput.value = label || '';
        allOptions.forEach(o => o.classList.toggle('sd-selected', !!strVal && o.dataset.value === strVal));
        wrapEl.classList.toggle('has-value', !!strVal);
    };
    wrapEl.sdGetValue = function() { return hiddenInput.value; };
    wrapEl.sdClear    = function() { clearBtn.click(); };
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.sd-wrap').forEach(el => initSearchDropdown(el));
    @if(old('id_perusahaan'))
    (function() {
        const sd = document.getElementById('sd-tambah-perusahaan');
        const val = '{{ old("id_perusahaan") }}';
        const opt = sd?.querySelector(`.sd-option[data-value="${val}"]`);
        if (sd && opt) sd.sdSetValue(val, opt.dataset.label);
    })();
    @endif
    @if(old('id_kategori_projek'))
    (function() {
        const sd = document.getElementById('sd-tambah-kategori');
        const val = '{{ old("id_kategori_projek") }}';
        const opt = sd?.querySelector(`.sd-option[data-value="${val}"]`);
        if (sd && opt) sd.sdSetValue(val, opt.dataset.label);
    })();
    @endif
    @if(old('dibuat_oleh'))
    (function() {
        const sd = document.getElementById('sd-tambah-pm');
        const val = '{{ old("dibuat_oleh") }}';
        const opt = sd?.querySelector(`.sd-option[data-value="${val}"]`);
        if (sd && opt) sd.sdSetValue(val, opt.dataset.label);
    })();
    @endif
});

/* ══════════════════════════════════════════════
   STATUS + PROGRESS
══════════════════════════════════════════════ */
const STATUS_COLOR   = { aktif:'#16a34a', in_progress:'#ea580c', selesai:'#5145cd', pending:'#9CA3AF' };
const STATUS_CLASSES = ['s-pending','s-in_progress','s-aktif','s-selesai'];

const projekData = Object.fromEntries(
    @json($projeksData).map(p => [p.id_projek, p])
);

/* ── Helpers ── */
const PDF_LOGO_PATH = '/images/logo1.png';
const PDF_COMPANY   = 'PT Kawan Kita Solusindo';
const PDF_TAGLINE   = 'Sistem Informasi Manajemen Project';

const PC = {
    navy:'#1E2A3A', navy2:'#2D3F52', gold:'#C9A84C', grayLn:'#D1D5DB', grayBg:'#F8F9FA',
    ink:'#1C1F2A', inkS:'#4B5563', green:'#166534', greenL:'#16A34A', amber:'#92400E',
    amberL:'#D97706', blue:'#1D4ED8', purple:'#5B21B6', red:'#991B1B',
};

/* ════════════════════════════════════════════════
   HELPERS
════════════════════════════════════════════════ */
function _pdfD(s) {
    if (!s) return '—';
    const c = String(s).includes('T') ? String(s).split('T')[0] : String(s);
    const p = c.split('-');
    if (p.length !== 3) return '—';
    const d = new Date(+p[0], +p[1]-1, +p[2]);
    if (isNaN(d)) return '—';
    const mn = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    return `${+p[2]} ${mn[d.getMonth()]} ${d.getFullYear()}`;
}
function _pdfE(s) {
    if (!s) return '';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function _pdfFmtRp(n) { return 'Rp ' + Number(n||0).toLocaleString('id-ID'); }

const _STLBL = { pending:'Pending', in_progress:'In Progress', aktif:'Aktif', selesai:'Selesai' };
const _STCLR = {
    pending    : { bg:'#F3F4F6', color:'#4B5563' },
    in_progress: { bg:'#FFFBEB', color:'#92400E' },
    aktif      : { bg:'#F0FDF4', color:'#166534' },
    selesai    : { bg:'#EFF6FF', color:'#1D4ED8' },
};

function _pdfStats(tasks) {
    const nd  = (tasks||[]).filter(t=>t.status_progress!=='draft');
    const W   = t => +t.weight>0 ? +t.weight : 1;
    const tot = nd.length;
    const dn  = nd.filter(t=>t.status_progress==='done').length;
    const pr  = nd.filter(t=>t.status_progress==='In Progress').length;
    const td  = nd.filter(t=>t.status_progress==='To Do').length;
    const tw  = nd.reduce((s,t)=>s+W(t),0);
    const ap  = nd.filter(t=>t.status_progress==='done'&&t.status_akhir==='approved').length;
    const aw  = nd.filter(t=>t.status_progress==='done'&&t.status_akhir==='approved').reduce((s,t)=>s+W(t),0);
    const saA = nd.filter(t=>t.status_akhir==='approved').length;
    const saRv= nd.filter(t=>t.status_akhir==='review').length;
    const saRs= nd.filter(t=>t.status_akhir==='revisi').length;
    const saN = nd.filter(t=>!t.status_akhir).length;
    const pct = tw>0 ? Math.round((aw/tw)*100) : 0;
    return { tot,dn,pr,td,tw,ap,aw,pct,saA,saRv,saRs,saN };
}

function _filterProjects(projects, opts) {
    let list = [...projects];
    if (opts.statuses && opts.statuses.length>0 && !opts.statuses.includes('all')) {
        list = list.filter(p=>opts.statuses.includes(p.status));
    }
    if (opts.dateFrom) {
        const df = new Date(opts.dateFrom);
        list = list.filter(p => { const d = p.tanggal_mulai ? new Date(String(p.tanggal_mulai).split('T')[0]) : null; return d && d >= df; });
    }
    if (opts.dateTo) {
        const dt = new Date(opts.dateTo);
        list = list.filter(p => { const d = p.tanggal_mulai ? new Date(String(p.tanggal_mulai).split('T')[0]) : null; return d && d <= dt; });
    }
    if (opts.pm && opts.pm !== 'all')           list = list.filter(p=>(p.pembuat_nama||'')===opts.pm);
    if (opts.kategori && opts.kategori !== 'all') list = list.filter(p=>(p.kategori_nama||'')===opts.kategori);
    return list;
}

function _pdfMonthly(projects) {
    const now = new Date();
    const MN  = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
    const list = [];
    for (let i=-5; i<=6; i++) {
        const m  = new Date(now.getFullYear(), now.getMonth()+i, 1);
        const mS = new Date(m.getFullYear(), m.getMonth(), 1);
        const mE = new Date(m.getFullYear(), m.getMonth()+1, 0, 23,59,59);
        let count=0;
        projects.forEach(p=>{
            if (p.status==='selesai') return;
            const effS = p.tanggal_mulai   ? new Date(String(p.tanggal_mulai).split('T')[0])   : new Date(now.getFullYear(),now.getMonth(),1);
            const effE = p.tanggal_selesai ? new Date(String(p.tanggal_selesai).split('T')[0]) : new Date(now.getFullYear(),now.getMonth()+3,1);
            if (effS<=mE && effE>=mS) count++;
        });
        list.push({ label:`${MN[m.getMonth()]} '${String(m.getFullYear()).slice(2)}`, count, isNow: m.getMonth()===now.getMonth()&&m.getFullYear()===now.getFullYear() });
    }
    return list;
}

/* ════════════════════════════════════════════════
   BUILD REPORT HTML ALL PROJECTS (hanya admin)
════════════════════════════════════════════════ */
function _pdfBuildReport(projects, logoDataUrl, opts) {
    const now    = new Date();
    const nowFmt = _pdfD(now.toISOString().split('T')[0]);
    const docNum = `RPT-KKS-${now.getFullYear()}${String(now.getMonth()+1).padStart(2,'0')}${String(now.getDate()).padStart(2,'0')}`;

    let filterDesc = 'Semua project';
    const parts = [];
    if (opts.dateFrom||opts.dateTo) {
        parts.push(`Periode: ${opts.dateFrom?_pdfD(opts.dateFrom):'…'} – ${opts.dateTo?_pdfD(opts.dateTo):'…'}`);
    }
    if (opts.pm && opts.pm!=='all')             parts.push(`PM: ${opts.pm}`);
    if (opts.kategori && opts.kategori!=='all') parts.push(`Kategori: ${opts.kategori}`);
    if (opts.statuses && !opts.statuses.includes('all') && opts.statuses.length>0)
        parts.push(`Status: ${opts.statuses.map(s=>_STLBL[s]||s).join(', ')}`);
    if (parts.length) filterDesc = parts.join(' · ');

    const total    = projects.length;
    const stPend   = projects.filter(p=>p.status==='pending').length;
    const stAktif  = projects.filter(p=>p.status==='aktif').length;
    const stInProg = projects.filter(p=>p.status==='in_progress').length;
    const stSel    = projects.filter(p=>p.status==='selesai').length;

    const allNd = projects.flatMap(p=>(p.tasks||[]).filter(t=>t.status_progress!=='draft'));
    const gTd   = allNd.filter(t=>t.status_progress==='To Do').length;
    const gPr   = allNd.filter(t=>t.status_progress==='In Progress').length;
    const gDn   = allNd.filter(t=>t.status_progress==='done').length;
    const gAppr = allNd.filter(t=>t.status_akhir==='approved').length;
    const gRev  = allNd.filter(t=>t.status_akhir==='review').length;
    const gRvs  = allNd.filter(t=>t.status_akhir==='revisi').length;

    const kMap = {};
    projects.forEach(p=>{
        (p.tim_list||[]).forEach(m=>{
            const key=m.nama||'—';
            if(!kMap[key]) kMap[key]={nama:key,role:m.job_role||m.jabatan||'—',projSet:new Set(),tasks:0,td:0,pr:0,dn:0};
            kMap[key].projSet.add(p.nama_projek);
            const nd=(p.tasks||[]).filter(t=>t.status_progress!=='draft'&&t.id_tim===m.id_tim);
            kMap[key].tasks+=nd.length;
            kMap[key].td+=nd.filter(t=>t.status_progress==='To Do').length;
            kMap[key].pr+=nd.filter(t=>t.status_progress==='In Progress').length;
            kMap[key].dn+=nd.filter(t=>t.status_progress==='done').length;
        });
    });
    const kList = Object.values(kMap).map(k=>({...k,projCount:[...k.projSet].length})).sort((a,b)=>b.tasks-a.tasks);

    const monthly  = _pdfMonthly(projects);
    const mLabels  = JSON.stringify(monthly.map(m=>m.label));
    const mCounts  = JSON.stringify(monthly.map(m=>m.count));
    const mIsNow   = JSON.stringify(monthly.map(m=>m.isNow));

    const logoHtml = logoDataUrl
        ? `<img src="${logoDataUrl}" alt="Logo" style="height:50px;object-fit:contain;">`
        : `<div style="font-size:22px;font-weight:700;color:white;font-family:'Times New Roman',serif;letter-spacing:2px;">KKS</div>`;

    const SH = (num, ttl, sub) => `
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;padding-bottom:8px;border-bottom:2.5px solid ${PC.navy};">
        <div style="width:4px;height:22px;background:${PC.gold};border-radius:2px;flex-shrink:0;"></div>
        <div>
            <div style="font-size:14px;font-weight:700;color:${PC.navy};text-transform:uppercase;letter-spacing:.3px;">${num}. ${ttl}</div>
            ${sub?`<div style="font-size:12px;color:${PC.inkS};margin-top:2px;">${sub}</div>`:''}
        </div>
    </div>`;

    const kpiHtml = `
    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:10px;margin-bottom:22px;">
        ${[
            {val:total,    lbl:'Total Project',   sub:'Semua project',    top:PC.navy,  vc:PC.navy  },
            {val:stPend,   lbl:'Pending',          sub:'Menunggu dimulai', top:'#9CA3AF',vc:'#6B7280'},
            {val:stAktif,  lbl:'Aktif',            sub:'Maintenance/Aktif',top:PC.gold,  vc:PC.amber },
            {val:stInProg, lbl:'Dalam Pengerjaan', sub:'In Progress',      top:PC.amberL,vc:PC.amberL},
            {val:stSel,    lbl:'Selesai',           sub:'Telah selesai',    top:PC.green, vc:PC.green },
        ].map(k=>`
        <div style="border:1px solid ${PC.grayLn};border-radius:8px;padding:14px 8px;text-align:center;background:white;border-top:4px solid ${k.top};">
            <div style="font-size:28px;font-weight:800;color:${k.vc};line-height:1.1;">${k.val}</div>
            <div style="font-size:12px;font-weight:700;color:${PC.ink};margin-top:4px;">${k.lbl}</div>
            <div style="font-size:11px;color:#9CA3AF;margin-top:2px;">${k.sub}</div>
        </div>`).join('')}
    </div>`;

    const barMonthHtml = `
    <div style="border:1px solid ${PC.grayLn};border-radius:8px;padding:16px 18px 12px;background:white;margin-bottom:20px;">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
            <span style="width:4px;height:14px;background:${PC.gold};border-radius:2px;display:inline-block;flex-shrink:0;"></span>
            <span style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:${PC.navy};">Jumlah Project Aktif per Bulan</span>
        </div>
        <canvas id="cvMonthly" width="680" height="190" style="max-width:100%;display:block;"></canvas>
    </div>`;

    const pieHtml = `
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
        <div style="border:1px solid ${PC.grayLn};border-radius:8px;padding:16px;background:white;">
            <div style="display:flex;align-items:center;gap:7px;margin-bottom:10px;">
                <span style="width:4px;height:13px;background:${PC.gold};border-radius:2px;display:inline-block;"></span>
                <span style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.3px;color:${PC.navy};">Distribusi Status Task</span>
            </div>
            <canvas id="cvPie1" width="320" height="190"></canvas>
            <div style="display:flex;flex-wrap:wrap;gap:5px 12px;margin-top:8px;">
                <span style="display:flex;align-items:center;gap:4px;font-size:12px;color:${PC.inkS};"><span style="width:10px;height:10px;border-radius:2px;background:#6B7280;display:inline-block;"></span>To Do: <strong>${gTd}</strong></span>
                <span style="display:flex;align-items:center;gap:4px;font-size:12px;color:${PC.inkS};"><span style="width:10px;height:10px;border-radius:2px;background:${PC.amberL};display:inline-block;"></span>In Progress: <strong>${gPr}</strong></span>
                <span style="display:flex;align-items:center;gap:4px;font-size:12px;color:${PC.inkS};"><span style="width:10px;height:10px;border-radius:2px;background:${PC.greenL};display:inline-block;"></span>Done: <strong>${gDn}</strong></span>
            </div>
        </div>
        <div style="border:1px solid ${PC.grayLn};border-radius:8px;padding:16px;background:white;">
            <div style="display:flex;align-items:center;gap:7px;margin-bottom:10px;">
                <span style="width:4px;height:13px;background:${PC.gold};border-radius:2px;display:inline-block;"></span>
                <span style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.3px;color:${PC.navy};">Status Penilaian PM</span>
            </div>
            <canvas id="cvPie2" width="320" height="190"></canvas>
            <div style="display:flex;flex-wrap:wrap;gap:5px 12px;margin-top:8px;">
                <span style="display:flex;align-items:center;gap:4px;font-size:12px;color:${PC.inkS};"><span style="width:10px;height:10px;border-radius:2px;background:${PC.greenL};display:inline-block;"></span>Disetujui: <strong>${gAppr}</strong></span>
                <span style="display:flex;align-items:center;gap:4px;font-size:12px;color:${PC.inkS};"><span style="width:10px;height:10px;border-radius:2px;background:${PC.purple};display:inline-block;"></span>Review PM: <strong>${gRev}</strong></span>
                <span style="display:flex;align-items:center;gap:4px;font-size:12px;color:${PC.inkS};"><span style="width:10px;height:10px;border-radius:2px;background:${PC.amberL};display:inline-block;"></span>Revisi: <strong>${gRvs}</strong></span>
            </div>
        </div>
    </div>`;

    const kRows = kList.map((k,i) => {
        const bg=i%2===0?'white':PC.grayBg, tot=k.tasks;
        const pTd=tot>0?Math.round(k.td/tot*100):0, pPr=tot>0?Math.round(k.pr/tot*100):0, pDn=tot>0?Math.round(k.dn/tot*100):0;
        const bar=(v,p,c,l)=>`<div style="display:flex;align-items:center;gap:6px;margin-bottom:4px;"><span style="font-size:12px;font-weight:700;color:${c};min-width:16px;text-align:right;">${v}</span><div style="width:70px;height:7px;background:#E5E7EB;border-radius:3px;overflow:hidden;flex-shrink:0;"><div style="width:${p}%;height:100%;background:${c};border-radius:3px;"></div></div><span style="font-size:12px;color:${PC.inkS};">${l}</span><span style="font-size:12px;color:#9CA3AF;margin-left:auto;">(${p}%)</span></div>`;
        return `<tr style="background:${bg};vertical-align:top;">
            <td style="padding:10px;border-bottom:1px solid ${PC.grayLn};border-right:1px solid ${PC.grayLn};">
                <div style="display:flex;align-items:flex-start;gap:8px;">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#696cff,#5145cd);color:white;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;margin-top:2px;">${_pdfE(k.nama.substring(0,2).toUpperCase())}</div>
                    <div>
                        <div style="font-size:13px;font-weight:700;color:${PC.ink};">${_pdfE(k.nama)}</div>
                        <div style="font-size:12px;color:${PC.purple};font-style:italic;margin-top:1px;">${_pdfE(k.role)}</div>
                        <div style="font-size:12px;color:${PC.inkS};margin-top:4px;font-weight:600;">${k.projCount} project &nbsp;·&nbsp; ${tot} task</div>
                    </div>
                </div>
            </td>
            <td style="padding:10px 12px;border-bottom:1px solid ${PC.grayLn};vertical-align:middle;">${bar(k.td,pTd,'#6B7280','To Do')}${bar(k.pr,pPr,PC.amberL,'In Progress')}${bar(k.dn,pDn,PC.greenL,'Done')}</td>
        </tr>`;
    }).join('');

    const tblKaryawan = `
    <div style="border:1px solid ${PC.grayLn};border-radius:8px;overflow:hidden;background:white;margin-bottom:20px;">
        <div style="background:${PC.navy};padding:10px 12px;display:flex;align-items:center;gap:8px;">
            <span style="width:4px;height:13px;background:${PC.gold};border-radius:2px;display:inline-block;flex-shrink:0;"></span>
            <span style="font-size:13px;font-weight:700;color:white;text-transform:uppercase;letter-spacing:.3px;">Karyawan &amp; Distribusi Task</span>
            <span style="margin-left:auto;font-size:12px;color:#9CA3AF;">${kList.length} karyawan aktif</span>
        </div>
        <table style="width:100%;border-collapse:collapse;font-family:'Times New Roman',Times,serif;font-size:13px;">
            <thead><tr style="background:${PC.navy2};"><th style="padding:8px 10px;text-align:left;font-size:12px;font-weight:600;color:#D1D5DB;border-right:1px solid #374151;width:45%;">Karyawan</th><th style="padding:8px 10px;text-align:left;font-size:12px;font-weight:600;color:#D1D5DB;">Statistik Penyelesaian Task</th></tr></thead>
            <tbody>${kRows||`<tr><td colspan="2" style="text-align:center;padding:18px;color:#9CA3AF;font-size:13px;">Tidak ada data</td></tr>`}</tbody>
        </table>
    </div>`;

    const statCell = (v,lbl,clr)=>`<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;"><span style="font-size:12px;color:${PC.inkS};">${lbl}</span><span style="font-size:13px;font-weight:700;color:${clr};min-width:20px;text-align:right;">${v}</span></div>`;

    const projRows = projects.map((p,i) => {
        const s  = _pdfStats(p.tasks);
        const st = _STCLR[p.status]||{bg:'#F3F4F6',color:'#6B7280'};
        const pctC = s.pct>=75?PC.greenL:s.pct>=40?PC.amberL:PC.red;
        const bg   = i%2===0?'white':PC.grayBg;
        const pmNama = _pdfE(p.pembuat_nama||'—');
        const pmMember = (p.tim_list||[]).find(m=>(m.nama||'').toLowerCase()===(p.pembuat_nama||'').toLowerCase());
        const pmRole = pmMember ? _pdfE(pmMember.job_role||pmMember.jabatan||'Project Manager') : 'Project Manager';
        return `<tr style="background:${bg};vertical-align:top;">
            <td style="padding:9px 6px;text-align:center;border-bottom:1px solid ${PC.grayLn};font-size:13px;font-weight:700;color:${PC.inkS};white-space:nowrap;">${i+1}</td>
            <td style="padding:9px 10px;border-bottom:1px solid ${PC.grayLn};">
                <div style="font-weight:700;font-size:13px;color:${PC.ink};line-height:1.4;">${_pdfE(p.nama_projek)}</div>
                <div style="font-size:12px;color:${PC.inkS};margin-top:2px;">${_pdfE(p.perusahaan_pt||p.perusahaan_nama||'—')}</div>
                <div style="margin-top:5px;"><span style="padding:2px 7px;border-radius:10px;font-size:11px;background:#F5F3FF;color:${PC.purple};font-weight:600;">${_pdfE(p.kategori_nama||'—')}</span></div>
                <div style="margin-top:5px;font-size:12px;color:${PC.inkS};"><span style="color:#9CA3AF;">PM:</span> <strong style="color:${PC.ink};">${pmNama}</strong> <span style="color:#9CA3AF;font-style:italic;margin-left:4px;">${pmRole}</span></div>
            </td>
            <td style="padding:9px 10px;border-bottom:1px solid ${PC.grayLn};text-align:center;white-space:nowrap;">
                <span style="padding:3px 9px;border-radius:12px;font-size:12px;font-weight:700;background:${st.bg};color:${st.color};display:inline-block;margin-bottom:8px;">${_STLBL[p.status]||p.status}</span>
                <div style="font-size:22px;font-weight:800;color:${pctC};line-height:1;">${s.pct}%</div>
                <div style="height:5px;background:#E5E7EB;border-radius:3px;overflow:hidden;margin:5px 4px 3px;"><div style="width:${s.pct}%;height:100%;background:${pctC};border-radius:3px;"></div></div>
                <div style="font-size:11px;color:${PC.inkS};">${s.ap}/${s.tot} approved</div>
            </td>
            <td style="padding:9px 10px;border-bottom:1px solid ${PC.grayLn};min-width:105px;">${statCell(s.td,'To Do','#6B7280')}${statCell(s.pr,'In Progress',PC.amberL)}${statCell(s.dn,'Done',PC.greenL)}<div style="border-top:1px solid ${PC.grayLn};margin-top:5px;padding-top:4px;font-size:11px;color:#9CA3AF;">Total: ${s.tot} task</div></td>
            <td style="padding:9px 10px;border-bottom:1px solid ${PC.grayLn};min-width:100px;">${statCell(s.saA,'Disetujui',PC.greenL)}${statCell(s.saRv,'Review PM',PC.purple)}${statCell(s.saRs,'Revisi',PC.amberL)}<div style="border-top:1px solid ${PC.grayLn};margin-top:5px;padding-top:4px;font-size:11px;color:#9CA3AF;">Belum dinilai: ${s.saN}</div></td>
        </tr>`;
    }).join('');

    const tblRekap = `
    <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;font-family:'Times New Roman',Times,serif;font-size:13px;">
        <thead><tr style="background:${PC.navy};">
            <th style="padding:9px 6px;text-align:center;font-size:12px;font-weight:700;color:white;text-transform:uppercase;letter-spacing:.3px;">No</th>
            <th style="padding:9px 10px;text-align:left;font-size:12px;font-weight:700;color:white;text-transform:uppercase;letter-spacing:.3px;">Nama Project &amp; Detail</th>
            <th style="padding:9px 10px;text-align:center;font-size:12px;font-weight:700;color:white;text-transform:uppercase;letter-spacing:.3px;">Status &amp; %</th>
            <th style="padding:9px 10px;text-align:left;font-size:12px;font-weight:700;color:white;text-transform:uppercase;letter-spacing:.3px;min-width:105px;">Status Progress</th>
            <th style="padding:9px 10px;text-align:left;font-size:12px;font-weight:700;color:white;text-transform:uppercase;letter-spacing:.3px;min-width:100px;">Penilaian PM</th>
        </tr></thead>
        <tbody>${projRows||'<tr><td colspan="5" style="text-align:center;padding:20px;color:#9CA3AF;">Tidak ada data project</td></tr>'}</tbody>
    </table>
    </div>`;

    const scripts = `
<script>
function drawDonut(id, segs, centerLbl) {
    const c=document.getElementById(id); if(!c)return;
    const ctx=c.getContext('2d');
    const vis=segs.filter(s=>s.v>0);
    const total=segs.reduce((a,s)=>a+s.v,0);
    const cx=95,cy=95,r=82;
    if(!total){ctx.fillStyle='#E5E7EB';ctx.beginPath();ctx.arc(cx,cy,r,0,Math.PI*2);ctx.fill();}
    else{
        let ang=-Math.PI/2;
        vis.forEach(seg=>{
            const sl=(seg.v/total)*Math.PI*2;
            ctx.beginPath();ctx.moveTo(cx,cy);ctx.arc(cx,cy,r,ang,ang+sl);ctx.closePath();
            ctx.fillStyle=seg.c;ctx.fill();ctx.strokeStyle='white';ctx.lineWidth=2;ctx.stroke();
            if(seg.v/total>=0.06){const mid=ang+sl/2;ctx.fillStyle='white';ctx.font='bold 12px Times New Roman';ctx.textAlign='center';ctx.textBaseline='middle';ctx.fillText(Math.round(seg.v/total*100)+'%',cx+r*.60*Math.cos(mid),cy+r*.60*Math.sin(mid));}
            ang+=sl;
        });
        ctx.beginPath();ctx.arc(cx,cy,r*.36,0,Math.PI*2);ctx.fillStyle='white';ctx.fill();
        ctx.fillStyle='${PC.navy}';ctx.font='bold 18px Times New Roman';ctx.textAlign='center';ctx.textBaseline='middle';ctx.fillText(total,cx,cy-7);
        ctx.font='11px Times New Roman';ctx.fillStyle='#9CA3AF';ctx.fillText(centerLbl,cx,cy+10);
    }
    const bx=202,bw=88,bh=17,bgap=28;
    vis.forEach((seg,i)=>{
        const y=10+i*bgap,w=total>0?(seg.v/total)*bw:0;
        ctx.fillStyle='#E5E7EB';ctx.beginPath();ctx.roundRect(bx,y,bw,bh,3);ctx.fill();
        if(w>0){ctx.fillStyle=seg.c;ctx.beginPath();ctx.roundRect(bx,y,w,bh,3);ctx.fill();}
        ctx.fillStyle='${PC.ink}';ctx.font='11px Times New Roman';ctx.textAlign='left';ctx.textBaseline='alphabetic';ctx.fillText(seg.l,bx,y-2);
        ctx.fillStyle=seg.c;ctx.font='bold 12px Times New Roman';ctx.fillText(seg.v+' task',bx+bw+7,y+bh/2+4);
    });
}
drawDonut('cvPie1',[{v:${gTd},c:'#6B7280',l:'To Do'},{v:${gPr},c:'${PC.amberL}',l:'In Progress'},{v:${gDn},c:'${PC.greenL}',l:'Done'}],'task');
drawDonut('cvPie2',[{v:${gAppr},c:'${PC.greenL}',l:'Disetujui'},{v:${gRev},c:'${PC.purple}',l:'Review PM'},{v:${gRvs},c:'${PC.amberL}',l:'Revisi'}],'penilaian');
(function(){
    const c=document.getElementById('cvMonthly');if(!c)return;
    const ctx=c.getContext('2d');
    const lbls=${mLabels},vals=${mCounts},isNow=${mIsNow};
    const W=c.width,H=c.height,padL=40,padR=130,padT=20,padB=40;
    const cW=W-padL-padR,cH=H-padT-padB,n=lbls.length;
    const maxV=Math.max(...vals,1),step=cW/n,bw=Math.max(16,Math.floor(step*.56));
    ctx.strokeStyle='#E5E7EB';ctx.lineWidth=1;
    for(let i=0;i<=5;i++){const y=padT+cH-(i/5)*cH;ctx.beginPath();ctx.moveTo(padL,y);ctx.lineTo(W-padR,y);ctx.stroke();ctx.fillStyle='#9CA3AF';ctx.font='10px Times New Roman';ctx.textAlign='right';ctx.textBaseline='middle';ctx.fillText(Math.round(maxV*(i/5)),padL-5,y);}
    vals.forEach((v,i)=>{
        const x=padL+i*step+step/2-bw/2,bh=cH*(v/maxV),y=padT+cH-bh;
        ctx.fillStyle=isNow[i]?'${PC.gold}':'${PC.navy}';
        if(bh>0){ctx.beginPath();ctx.roundRect(x,y,bw,bh,[3,3,0,0]);ctx.fill();}
        if(v>0){ctx.fillStyle=isNow[i]?'${PC.amber}':'${PC.navy}';ctx.font='bold 11px Times New Roman';ctx.textAlign='center';ctx.textBaseline='bottom';ctx.fillText(v,x+bw/2,y-2);}
        ctx.fillStyle=isNow[i]?'${PC.amber}':'${PC.inkS}';ctx.font=isNow[i]?'bold 11px Times New Roman':'11px Times New Roman';ctx.textAlign='center';ctx.textBaseline='top';ctx.fillText(lbls[i],x+bw/2,padT+cH+7);
    });
    ctx.strokeStyle='${PC.navy}';ctx.lineWidth=1.5;ctx.beginPath();ctx.moveTo(padL,padT);ctx.lineTo(padL,padT+cH);ctx.lineTo(W-padR,padT+cH);ctx.stroke();
    const lx=W-padR+12,ly=padT+8;
    ctx.fillStyle='${PC.gold}';ctx.fillRect(lx,ly,12,10);ctx.fillStyle='${PC.ink}';ctx.font='11px Times New Roman';ctx.textAlign='left';ctx.fillText('Bulan ini',lx+15,ly+9);
    ctx.fillStyle='${PC.navy}';ctx.fillRect(lx,ly+18,12,10);ctx.fillText('Bulan lain',lx+15,ly+27);
})();
<\/script>`;

    return `<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><title>Laporan Internal — ${PDF_COMPANY}</title>
<style>*{box-sizing:border-box;margin:0;padding:0;}body{font-family:'Times New Roman',Times,serif;background:#F3F4F6;color:${PC.ink};font-size:13px;line-height:1.6;}.page{max-width:780px;margin:0 auto;background:white;}.sec{padding:22px 28px 0;}.gap{height:22px;}@media print{body{background:white;}@page{margin:12mm 10mm;size:A4 portrait;}.page{max-width:100%;}.cvr,thead tr{-webkit-print-color-adjust:exact;print-color-adjust:exact;}.sec{padding:14px 16px 0;}.gap{height:12px;}}</style>
</head><body><div class="page">
<div class="cvr" style="background:${PC.navy};">
    <div style="height:5px;background:linear-gradient(90deg,${PC.gold},#E8C97A 45%,${PC.gold});"></div>
    <div style="padding:22px 28px 16px;display:flex;justify-content:space-between;align-items:flex-start;">
        <div style="flex:1;">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px;">${logoHtml}<div style="width:1px;height:44px;background:rgba(255,255,255,.15);"></div><div><div style="font-size:10px;letter-spacing:2px;text-transform:uppercase;color:${PC.gold};">Dokumen Resmi</div><div style="font-size:15px;font-weight:700;color:white;">${PDF_COMPANY}</div></div></div>
            <div style="font-size:10px;letter-spacing:2px;text-transform:uppercase;color:${PC.gold};margin-bottom:5px;">Laporan Internal Pengelolaan Project</div>
            <div style="font-size:22px;font-weight:700;color:white;line-height:1.2;margin-bottom:4px;">Rekap &amp; Analisis Manajemen Project</div>
            <div style="font-size:13px;color:#9CA3AF;margin-bottom:12px;">${PDF_TAGLINE}</div>
            <div style="font-size:12px;color:#D1D5DB;"><span style="color:#9CA3AF;">Filter:</span> ${filterDesc}</div>
        </div>
        <div style="text-align:right;flex-shrink:0;margin-left:16px;">
            <div style="font-size:11px;color:#9CA3AF;font-family:'Courier New',monospace;margin-bottom:4px;">${docNum}</div>
            <div style="font-size:12px;color:#D1D5DB;margin-bottom:10px;">${nowFmt}</div>
            <div style="display:flex;flex-direction:column;gap:4px;align-items:flex-end;">
                <span style="padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700;background:rgba(105,108,255,.25);color:#A5B4FC;">&#9679; ${total} Project</span>
                <span style="padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700;background:rgba(34,197,94,.2);color:#86EFAC;">&#10003; ${stSel} Selesai</span>
            </div>
        </div>
    </div>
    <div style="padding:9px 28px 14px;border-top:1px solid #2D3F52;display:flex;justify-content:space-between;">
        <div style="font-size:12px;color:#9CA3AF;">Disiapkan: <strong style="color:#D1D5DB;">${PDF_COMPANY}</strong></div>
        <div style="font-size:11px;color:#6B7280;font-style:italic;">Dokumen rahasia — penggunaan internal</div>
    </div>
</div>
<div class="sec" style="padding-top:22px;">${SH('I','Ringkasan Eksekutif',filterDesc)}${kpiHtml}${barMonthHtml}${pieHtml}${tblKaryawan}</div>
<div class="sec">${SH('II','Rekap Seluruh Project',`${total} project terdaftar`)}${tblRekap}</div>
<div class="gap"></div>
<div class="cvr" style="background:${PC.navy};padding:10px 28px;display:flex;justify-content:space-between;align-items:center;margin-top:22px;">
    <span style="font-size:12px;color:${PC.gold};font-weight:600;">${PDF_COMPANY}</span>
    <span style="font-size:11px;color:#9CA3AF;">${docNum}</span>
    <span style="font-size:11px;color:#9CA3AF;">${PDF_TAGLINE} &mdash; ${now.toLocaleString('id-ID')}</span>
</div>
</div>${scripts}</body></html>`;
}

/* ════════════════════════════════════════════════════════════════════════════
   MODAL FILTER EXPORT (hanya admin)
════════════════════════════════════════════════════════════════════════════ */
function _injectExportModal() {
    if (!ROLE_CAN_EXPORT_ALL) return;
    if (document.getElementById('pdfExportModalOverlay')) return;

    const overlay = document.createElement('div');
    overlay.id = 'pdfExportModalOverlay';
    overlay.style.cssText = 'display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,.55);backdrop-filter:blur(3px);align-items:center;justify-content:center;';
    overlay.innerHTML = `
    <div id="pdfExportModal" style="background:white;border-radius:14px;width:520px;max-width:95vw;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.35);font-family:'Segoe UI',sans-serif;">
        <div style="background:#1E2A3A;border-radius:14px 14px 0 0;padding:18px 22px;display:flex;justify-content:space-between;align-items:center;">
            <div><div style="color:#C9A84C;font-size:11px;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:3px;">Laporan Internal</div><div style="color:white;font-size:16px;font-weight:700;">Export PDF Project</div></div>
            <button onclick="hideExportModal()" style="background:rgba(255,255,255,.1);border:none;color:white;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center;">&times;</button>
        </div>
        <div style="padding:22px;">
            <div style="margin-bottom:20px;">
                <div style="font-size:13px;font-weight:700;color:#1E2A3A;margin-bottom:10px;display:flex;align-items:center;gap:7px;"><span style="width:3px;height:14px;background:#C9A84C;border-radius:2px;display:inline-block;"></span>Periode Waktu</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div><label style="font-size:12px;color:#4B5563;display:block;margin-bottom:4px;">Dari (Tanggal Mulai)</label><input type="date" id="pdfDateFrom" style="width:100%;padding:8px 10px;border:1px solid #D1D5DB;border-radius:7px;font-size:13px;color:#1C1F2A;font-family:'Segoe UI',sans-serif;outline:none;"></div>
                    <div><label style="font-size:12px;color:#4B5563;display:block;margin-bottom:4px;">Sampai (Tanggal Mulai)</label><input type="date" id="pdfDateTo" style="width:100%;padding:8px 10px;border:1px solid #D1D5DB;border-radius:7px;font-size:13px;color:#1C1F2A;font-family:'Segoe UI',sans-serif;outline:none;"></div>
                </div>
            </div>
            <div style="margin-bottom:20px;">
                <div style="font-size:13px;font-weight:700;color:#1E2A3A;margin-bottom:10px;display:flex;align-items:center;gap:7px;"><span style="width:3px;height:14px;background:#C9A84C;border-radius:2px;display:inline-block;"></span>Status Project</div>
                <div style="display:flex;flex-wrap:wrap;gap:8px;" id="pdfStatusGroup">
                    ${['all','pending','in_progress','aktif','selesai'].map(s=>`<label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:6px 12px;border-radius:20px;border:1.5px solid #E5E7EB;font-size:12px;font-weight:600;color:#4B5563;user-select:none;transition:all .15s;" class="pdf-status-chip" data-val="${s}"><input type="checkbox" value="${s}" style="display:none;" ${s==='all'?'checked':''}>${({'all':'Semua Status','pending':'Pending','in_progress':'In Progress','aktif':'Aktif','selesai':'Selesai'})[s]}</label>`).join('')}
                </div>
            </div>
            <div style="margin-bottom:20px;">
                <div style="font-size:13px;font-weight:700;color:#1E2A3A;margin-bottom:10px;display:flex;align-items:center;gap:7px;"><span style="width:3px;height:14px;background:#C9A84C;border-radius:2px;display:inline-block;"></span>Project Manager</div>
                <select id="pdfPmFilter" style="width:100%;padding:8px 10px;border:1px solid #D1D5DB;border-radius:7px;font-size:13px;color:#1C1F2A;font-family:'Segoe UI',sans-serif;outline:none;background:white;">
                    <option value="all">Semua PM</option>
                    ${[...new Set(Object.values(projekData).map(p=>p.pembuat_nama).filter(Boolean))].sort().map(pm=>`<option value="${pm}">${pm}</option>`).join('')}
                </select>
            </div>
            <div style="margin-bottom:22px;">
                <div style="font-size:13px;font-weight:700;color:#1E2A3A;margin-bottom:10px;display:flex;align-items:center;gap:7px;"><span style="width:3px;height:14px;background:#C9A84C;border-radius:2px;display:inline-block;"></span>Kategori Project</div>
                <select id="pdfKategoriFilter" style="width:100%;padding:8px 10px;border:1px solid #D1D5DB;border-radius:7px;font-size:13px;color:#1C1F2A;font-family:'Segoe UI',sans-serif;outline:none;background:white;">
                    <option value="all">Semua Kategori</option>
                    ${[...new Set(Object.values(projekData).map(p=>p.kategori_nama).filter(Boolean))].sort().map(k=>`<option value="${k}">${k}</option>`).join('')}
                </select>
            </div>
            <div id="pdfPreviewInfo" style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:8px;padding:11px 14px;font-size:12px;color:#166534;margin-bottom:18px;display:flex;align-items:center;gap:8px;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" style="flex-shrink:0;"><circle cx="12" cy="12" r="10" stroke="#16A34A" stroke-width="2"/><path d="M12 8v4M12 16h.01" stroke="#16A34A" stroke-width="2" stroke-linecap="round"/></svg>
                <span id="pdfPreviewTxt">Klik <strong>Preview</strong> untuk melihat jumlah project yang akan diekspor.</span>
            </div>
        </div>
        <div style="padding:0 22px 20px;display:flex;gap:10px;justify-content:flex-end;">
            <button onclick="hideExportModal()" style="padding:9px 20px;border-radius:8px;border:1.5px solid #D1D5DB;background:white;color:#4B5563;font-size:13px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif;">Batal</button>
            <button onclick="_pdfPreviewFilter()" style="padding:9px 20px;border-radius:8px;border:none;background:#F3F4F6;color:#1C1F2A;font-size:13px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif;">Preview</button>
            <button onclick="doExportPDF()" style="padding:9px 22px;border-radius:8px;border:none;background:#1E2A3A;color:white;font-size:13px;font-weight:700;cursor:pointer;font-family:'Segoe UI',sans-serif;display:flex;align-items:center;gap:7px;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M12 16l-4-4h2.5V4h3v8H16l-4 4z" fill="white"/><path d="M4 18h16v2H4v-2z" fill="white"/></svg>Export PDF</button>
        </div>
    </div>`;
    document.body.appendChild(overlay);

    overlay.querySelectorAll('.pdf-status-chip').forEach(chip => {
        chip.addEventListener('click', () => {
            const val = chip.dataset.val;
            const allChip = overlay.querySelector('.pdf-status-chip[data-val="all"]');
            const cb = chip.querySelector('input');
            if (val==='all') {
                overlay.querySelectorAll('.pdf-status-chip').forEach(c=>{c.querySelector('input').checked=false;_pdfChipStyle(c,false);});
                cb.checked=true; _pdfChipStyle(chip,true);
            } else {
                const allCb=allChip.querySelector('input'); allCb.checked=false; _pdfChipStyle(allChip,false);
                cb.checked=!cb.checked; _pdfChipStyle(chip,cb.checked);
                const anyChecked=[...overlay.querySelectorAll('.pdf-status-chip:not([data-val="all"]) input')].some(i=>i.checked);
                if(!anyChecked){allCb.checked=true;_pdfChipStyle(allChip,true);}
            }
        });
    });
    _pdfChipStyle(overlay.querySelector('.pdf-status-chip[data-val="all"]'), true);
}

function _pdfChipStyle(chip, active) {
    chip.style.background  = active ? '#1E2A3A' : 'white';
    chip.style.color       = active ? 'white'   : '#4B5563';
    chip.style.borderColor = active ? '#1E2A3A' : '#E5E7EB';
}
function _pdfGetOpts() {
    const statuses = [...document.querySelectorAll('.pdf-status-chip input:checked')].map(i=>i.value);
    return {
        dateFrom : document.getElementById('pdfDateFrom')?.value || '',
        dateTo   : document.getElementById('pdfDateTo')?.value   || '',
        pm       : document.getElementById('pdfPmFilter')?.value || 'all',
        kategori : document.getElementById('pdfKategoriFilter')?.value || 'all',
        statuses,
    };
}
function _pdfPreviewFilter() {
    const opts     = _pdfGetOpts();
    const all      = Object.values(projekData);
    const filtered = _filterProjects(all, opts);
    const info = document.getElementById('pdfPreviewInfo');
    const txt  = document.getElementById('pdfPreviewTxt');
    if (filtered.length===0) {
        info.style.background='#FEF2F2'; info.style.borderColor='#FECACA'; info.style.color='#991B1B';
        txt.innerHTML = '⚠️ Tidak ada project yang cocok dengan filter ini.';
    } else {
        info.style.background='#F0FDF4'; info.style.borderColor='#BBF7D0'; info.style.color='#166534';
        txt.innerHTML = `✅ <strong>${filtered.length} project</strong> akan disertakan dalam laporan PDF.`;
    }
}
function showExportModal() {
    if (!ROLE_CAN_EXPORT_ALL) { showToast('❌ Anda tidak memiliki akses export keseluruhan.', 'error'); return; }
    _injectExportModal();
    document.getElementById('pdfExportModalOverlay').style.display = 'flex';
    const txt = document.getElementById('pdfPreviewTxt');
    if (txt) txt.innerHTML = 'Klik <strong>Preview</strong> untuk melihat jumlah project yang akan diekspor.';
}
function hideExportModal() {
    const el = document.getElementById('pdfExportModalOverlay');
    if (el) el.style.display = 'none';
}
document.addEventListener('click', e => { if (e.target?.id==='pdfExportModalOverlay') hideExportModal(); });

function doExportPDF() {
    if (!ROLE_CAN_EXPORT_ALL) return;
    const opts = _pdfGetOpts();
    const all  = Object.values(projekData);
    const filtered = _filterProjects(all, opts);
    if (!filtered.length) { _pdfPreviewFilter(); return; }
    hideExportModal();
    if (typeof showToast === 'function') showToast('⏳ Menyiapkan laporan PDF...', 'info');
    const proceed = (logoUrl) => {
        const html = _pdfBuildReport(filtered, logoUrl, opts);
        const win  = window.open('', '_blank', 'width=900,height=820');
        if (!win) { if (typeof showToast==='function') showToast('❌ Pop-up diblokir.','error'); return; }
        win.document.write(html);
        win.document.close();
        win.focus();
        setTimeout(()=>win.print(), 1000);
        if (typeof showToast==='function') showToast('✅ Laporan siap — pilih "Simpan sebagai PDF" di dialog print.','success');
    };
    const img = new Image();
    img.crossOrigin = 'anonymous';
    img.onload = function() { try { const cv=document.createElement('canvas'); cv.width=img.naturalWidth||200; cv.height=img.naturalHeight||80; cv.getContext('2d').drawImage(img,0,0); proceed(cv.toDataURL('image/png')); } catch(e){ proceed(null); } };
    img.onerror = ()=>proceed(null);
    img.src = PDF_LOGO_PATH+'?t='+Date.now();
}

if (document.readyState==='loading') { document.addEventListener('DOMContentLoaded', _injectExportModal); } else { _injectExportModal(); }

function exportAllProjectsPDF() { showExportModal(); }

/* ════════════════════════════════════════════════
   UTILITY
════════════════════════════════════════════════ */
function fmtDate(d) {
    if (!d) return '—';
    let dateStr = String(d).trim();
    if (dateStr.includes('T')) dateStr = dateStr.split('T')[0];
    const parts = dateStr.split('-');
    if (parts.length !== 3) return '—';
    const dt = new Date(+parts[0], +parts[1]-1, +parts[2]);
    if (isNaN(dt.getTime())) return '—';
    const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    return `${+parts[2]} ${bulan[dt.getMonth()]} ${+parts[0]}`;
}
function fmtRupiah(n) { return 'Rp ' + Number(n||0).toLocaleString('id-ID'); }
function formatRibuan(val) { const num = val.replace(/\D/g, ''); return num.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }
function parseRibuan(val) { return parseInt(val.replace(/\./g, ''), 10) || 0; }
function escHtml(s) { if (!s) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }
function isImageFile(n) { return /\.(jpg|jpeg|png|gif|webp|bmp|svg)$/i.test(n || ''); }

/* ── Auto-sync sisa = nominal (Tambah) ── */
document.addEventListener('DOMContentLoaded', () => {
    const displayEl = document.getElementById('tambah_nominal_display');
    const hiddenEl  = document.getElementById('tambah_nominal_projek');
    if (displayEl) {
        const initVal = parseInt('{{ old("nominal_projek", 0) }}') || 0;
        if (initVal > 0) displayEl.value = formatRibuan(String(initVal));
        displayEl.addEventListener('input', function () {
            const raw = parseRibuan(this.value);
            this.value = formatRibuan(this.value);
            hiddenEl.value = raw;
            document.getElementById('tambah_sisa_display').value    = fmtRupiah(raw);
            document.getElementById('tambah_sisa_tanggungan').value = raw;
        });
        displayEl.addEventListener('keydown', function (e) {
            if (!['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Home','End'].includes(e.key) && !/^\d$/.test(e.key)) e.preventDefault();
        });
    }
});

function updateProgressColor(id, status) {
    const color  = STATUS_COLOR[status] || '#9CA3AF';
    const fillEl = document.getElementById('prog-fill-' + id);
    const pctEl  = document.getElementById('prog-pct-'  + id);
    if (fillEl) fillEl.style.background = color;
    if (pctEl)  pctEl.style.color = color;
}

/* ── Toast ── */
function showToast(msg, type) {
    const t = document.getElementById('__toast');
    const icon = document.getElementById('__toast-icon');
    const msgEl = document.getElementById('__toast-msg');
    msgEl.textContent = msg;
    t.className = '';
    t.classList.add('show', 't-' + type);
    if      (type === 'success') { icon.className = 'bx bx-check-circle';   t.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)'; }
    else if (type === 'error')   { icon.className = 'bx bx-error-circle';   t.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)'; }
    else                         { icon.className = 'bx bx-info-circle';    t.style.background = 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)'; }
    clearTimeout(t.__tmr);
    t.__tmr = setTimeout(() => { t.className = ''; }, 3000);
}

/* ── Hapus ── */
function confirmDelete(id, nama, route) {
    if (!ROLE_CAN_DELETE) { showToast('❌ Anda tidak memiliki akses untuk menghapus.', 'error'); return; }
    document.getElementById('deleteProjectName').textContent = nama;
    document.getElementById('deleteForm').action = route;
    new bootstrap.Modal(document.getElementById('modalHapusProject')).show();
}

/* ── Inline status PATCH ── */
function updateStatusInline(id, selectEl) {
    if (!ROLE_CAN_CHANGE_STATUS) { showToast('❌ Anda tidak memiliki akses mengubah status.', 'error'); selectEl.value = projekData[id]?.status ?? 'pending'; return; }
    const newStatus = selectEl.value;
    const wrap = document.getElementById('status-wrap-' + id);
    wrap.classList.remove(...STATUS_CLASSES);
    wrap.classList.add('s-' + newStatus);
    updateProgressColor(id, newStatus);
    const statusText = { aktif:'Aktif', in_progress:'In Progress', selesai:'Selesai', pending:'Pending' };
    fetch('{{ url("master-data-projek") }}/' + id + '/status', {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ status: newStatus }),
    })
    .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
    .then(data => {
        if (data.success) {
            if (projekData[id]) projekData[id].status = newStatus;
            showToast('✅ Status berhasil diperbarui menjadi ' + statusText[newStatus], 'success');
        } else throw new Error('Gagal');
    })
    .catch(() => {
        const prev = projekData[id]?.status ?? 'pending';
        wrap.classList.remove(...STATUS_CLASSES);
        wrap.classList.add('s-' + prev);
        selectEl.value = prev;
        updateProgressColor(id, prev);
        showToast('❌ Gagal memperbarui status. Silakan coba lagi.', 'error');
    });
}

/* ── VIEW Modal ── */
function openViewModal(id) {
    const p = projekData[id];
    if (!p) { showToast('❌ Data tidak ditemukan.', 'error'); return; }
    const slMap = { aktif:'Aktif', in_progress:'In Progress', selesai:'Selesai', pending:'Pending' };
    const scMap = { aktif:'status-aktif', in_progress:'status-in_progress', selesai:'status-selesai', pending:'status-pending' };

    document.getElementById('view_nama_projek').textContent     = p.nama_projek || '—';
    document.getElementById('view_perusahaan').textContent      = p.perusahaan_nama + (p.perusahaan_pt ? ' — ' + p.perusahaan_pt : '');
    document.getElementById('view_kategori').textContent        = p.kategori_nama || '—';
    document.getElementById('view_deskripsi').textContent       = p.deskripsi || '—';
    document.getElementById('view_tanggal_mulai').textContent   = fmtDate(p.tanggal_mulai);
    document.getElementById('view_tanggal_selesai').textContent = fmtDate(p.tanggal_selesai);
    document.getElementById('view_status').innerHTML = `<span class="status-badge ${scMap[p.status]||'status-pending'}"><span class="dot"></span>${slMap[p.status]||p.status}</span>`;

    // Nominal hanya tampil jika role tidak disembunyikan
    if (!ROLE_HIDE_NOMINAL) {
        const nomEl  = document.getElementById('view_nominal_projek');
        const sisaEl = document.getElementById('view_sisa_tanggungan');
        if (nomEl)  nomEl.textContent  = fmtRupiah(p.nominal_projek  || 0);
        if (sisaEl) sisaEl.textContent = fmtRupiah(p.sisa_tanggungan || 0);
    }

    const pEl = document.getElementById('view_pembuat');
    if (p.pembuat_nama && p.pembuat_nama !== '—') {
        const ini = p.pembuat_nama.substring(0,2).toUpperCase();
        pEl.innerHTML = `<div style="display:flex;align-items:center;gap:10px;"><div class="av" style="font-size:13px;">${ini}</div><div><div style="font-weight:700;font-size:13px;color:var(--ink-900);">${p.pembuat_nama}</div>${p.pembuat_email?`<div style="font-size:11px;color:var(--p1);">${p.pembuat_email}</div>`:''}</div></div>`;
    } else pEl.textContent = '—';

    const color  = STATUS_COLOR[p.status] || '#9CA3AF';
    const progEl = document.getElementById('view_progress');
    const detEl  = document.getElementById('view_progress_detail');
    if (p.total_weight > 0) {
        progEl.innerHTML = `<div style="display:flex;align-items:center;gap:12px;width:100%;"><div class="prog-track" style="flex:1;height:10px;"><div class="prog-fill" style="width:${p.progress}%;background:${color};"></div></div><span style="font-weight:800;color:${color};min-width:44px;text-align:right;font-size:15px;">${p.progress}%</span></div>`;
        detEl.innerHTML  = `<span style="font-weight:700;color:${color};">${p.approved_count} tugas done+approved</span><span style="color:var(--ink-400);"> dari ${p.total_count} tugas (weight: ${p.approved_weight}/${p.total_weight})</span>`;
    } else {
        progEl.innerHTML = `<span style="color:var(--ink-300);font-weight:600;">0% — Belum ada tugas aktif</span>`;
        detEl.textContent = '—';
    }

    if (p.dokumen_perjanjian) {
        const ext = p.dokumen_perjanjian.split('.').pop().toLowerCase();
        const isImage = ['jpg','jpeg','png','webp'].includes(ext);
        document.getElementById('view_dokumen').innerHTML = isImage
            ? `<a href="/storage/${p.dokumen_perjanjian}" target="_blank"><img src="/storage/${p.dokumen_perjanjian}" style="max-width:100%;max-height:200px;border-radius:8px;border:1px solid var(--ink-200);cursor:pointer;"></a>`
            : `<a href="/storage/${p.dokumen_perjanjian}" target="_blank" class="file-btn"><i class="bx bx-file-blank"></i> Preview Dokumen</a>`;
    } else {
        document.getElementById('view_dokumen').innerHTML = `<span style="color:var(--ink-300);font-style:italic;font-size:13px;">Belum ada dokumen</span>`;
    }

    // Tombol edit di modal view, hanya tampil jika bisa edit
    const editBtn = document.getElementById('view_edit_btn');
    if (editBtn) {
        editBtn.onclick = () => {
            bootstrap.Modal.getInstance(document.getElementById('modalViewProject')).hide();
            setTimeout(() => openEditModal(id), 300);
        };
    }

    new bootstrap.Modal(document.getElementById('modalViewProject')).show();
}

/* ── EDIT Modal ── */
function openEditModal(id) {
    if (!ROLE_CAN_EDIT) { showToast('❌ Anda tidak memiliki akses untuk mengedit.', 'error'); return; }
    const p = projekData[id];
    if (!p) { showToast('❌ Data tidak ditemukan.', 'error'); return; }

    document.getElementById('editForm').action            = '{{ url("master-data-projek") }}/' + id;
    document.getElementById('edit_nama_projek').value     = p.nama_projek     || '';
    document.getElementById('edit_status').value          = p.status          || 'pending';
    document.getElementById('edit_deskripsi').value       = p.deskripsi       || '';
    document.getElementById('edit_tanggal_mulai').value   = p.tanggal_mulai   ? p.tanggal_mulai.substring(0,10)   : '';
    document.getElementById('edit_tanggal_selesai').value = p.tanggal_selesai ? p.tanggal_selesai.substring(0,10) : '';

    const editDisplayEl = document.getElementById('edit_nominal_display');
    const editHiddenEl  = document.getElementById('edit_nominal_projek');
    // Nominal hanya tampil/diisi untuk Admin
    if (editDisplayEl && editHiddenEl) {
        editDisplayEl.value = formatRibuan(String(Math.round(p.nominal_projek || 0)));
        editHiddenEl.value  = Math.round(p.nominal_projek || 0);
        document.getElementById('edit_sisa_display').value = fmtRupiah(Math.round(p.sisa_tanggungan || 0));

        const originalNominal = Math.round(p.nominal_projek  || 0);
        const originalSisa    = Math.round(p.sisa_tanggungan || 0);

        const newDisplayEl = editDisplayEl.cloneNode(true);
        editDisplayEl.parentNode.replaceChild(newDisplayEl, editDisplayEl);
        newDisplayEl.addEventListener('input', function () {
            const raw   = parseRibuan(this.value);
            this.value  = formatRibuan(this.value);
            editHiddenEl.value = raw;
            const selisih = raw - originalNominal;
            const newSisa = Math.max(0, originalSisa + selisih);
            document.getElementById('edit_sisa_display').value = fmtRupiah(newSisa);
        });
        newDisplayEl.addEventListener('keydown', function (e) {
            if (!['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Home','End'].includes(e.key) && !/^\d$/.test(e.key)) e.preventDefault();
        });
    }

    const sdPerusahaan = document.getElementById('sd-edit-perusahaan');
    const sdKategori   = document.getElementById('sd-edit-kategori');
    if (sdPerusahaan?.sdSetValue) sdPerusahaan.sdSetValue(p.id_perusahaan, p.perusahaan_label);
    if (sdKategori?.sdSetValue)   sdKategori.sdSetValue(p.id_kategori_projek, p.id_kategori_projek ? p.kategori_nama : '');

    // ── Penanggung Jawab (PM) ──
    const sdPm = document.getElementById('sd-edit-pm');
    if (sdPm?.sdSetValue) {
        // Admin: isi dropdown search PM
        sdPm.sdSetValue(p.dibuat_oleh, p.pembuat_nama || '');
    } else {
        // PM (readonly): tampilkan nama PM saat ini
        const pmNamaEl   = document.getElementById('edit_pm_nama_display');
        const pmAvatarEl = document.getElementById('edit_pm_avatar');
        if (pmNamaEl)   pmNamaEl.textContent   = p.pembuat_nama || '—';
        if (pmAvatarEl) pmAvatarEl.textContent = (p.pembuat_nama || 'PM').substring(0,2).toUpperCase();
    }

    const docWrap = document.getElementById('edit_current_doc');
    if (p.dokumen_perjanjian) {
        document.getElementById('edit_doc_link').href = '/storage/' + p.dokumen_perjanjian;
        docWrap.style.display = 'block';
    } else docWrap.style.display = 'none';

    new bootstrap.Modal(document.getElementById('modalEditProject')).show();
}

/* ── Sort ── */
function sortBy(col) {
    const cur = document.getElementById('sortByInput').value;
    const ord = document.getElementById('sortOrderInput').value;
    document.getElementById('sortByInput').value    = col;
    document.getElementById('sortOrderInput').value = (cur === col && ord === 'asc') ? 'desc' : 'asc';
    document.getElementById('filterForm').submit();
}

/* ── Per Page ── */
function changePerPage(val) {
    document.getElementById('perPageInput').value = val;
    document.getElementById('filterForm').submit();
}

/* ── Column Toggle ── */
function toggleColSettings(e) {
    e.stopPropagation();
    document.getElementById('colSettingsDropdown').classList.toggle('open');
}
document.addEventListener('click', e => {
    const dd = document.getElementById('colSettingsDropdown');
    if (dd && !dd.contains(e.target)) dd.classList.remove('open');
});
function toggleColumn(cls, visible) {
    document.querySelectorAll('.' + cls).forEach(el => el.style.display = visible ? '' : 'none');
    try {
        const s = JSON.parse(localStorage.getItem('projColSettings') || '{}');
        s[cls] = visible;
        localStorage.setItem('projColSettings', JSON.stringify(s));
    } catch(e) {}
}
document.addEventListener('DOMContentLoaded', () => {
    try {
        const s = JSON.parse(localStorage.getItem('projColSettings') || '{}');
        if (!('col-timestamps' in s)) { s['col-timestamps'] = false; localStorage.setItem('projColSettings', JSON.stringify(s)); }
        Object.entries(s).forEach(([cls, vis]) => {
            document.querySelectorAll('.' + cls).forEach(el => el.style.display = vis ? '' : 'none');
            const chk = document.getElementById('chk_' + cls);
            if (chk) chk.checked = !!vis;
        });
    } catch(e) {}
});

@if($errors->any() && !old('_method'))
document.addEventListener('DOMContentLoaded', () => {
    @if($canCreate)
    new bootstrap.Modal(document.getElementById('modalTambahProject')).show();
    @endif
});
@endif

/* ════════════════════════════════════════════════
   EXPORT PER PROJECT (PDF)
════════════════════════════════════════════════ */
const SP_LABEL_PDF = { 'draft':'Draft', 'To Do':'Belum Pengerjaan', 'In Progress':'Proses Pengerjaan', 'done':'Selesai' };
const SA_LABEL_PDF = { 'review':'Review PM', 'revisi':'Revisi PM', 'approved':'Disetujui' };
const SP_BADGE_CLASS = { 'draft':'badge-draft', 'To Do':'badge-todo', 'In Progress':'badge-inprogress', 'done':'badge-done' };
const SA_BADGE_CLASS = { 'review':'badge-review', 'revisi':'badge-revisi', 'approved':'badge-approved' };
const PIE_COLORS_P  = { 'done':'#3B7DD8', 'In Progress':'#E8A838', 'To Do':'#9CA3AF' };

function _fmtDateLong(s) {
    if (!s) return '—';
    let clean = String(s).trim();
    if (clean.includes('T')) clean = clean.split('T')[0];
    if (!/^\d{4}-\d{2}-\d{2}$/.test(clean)) return '—';
    const parts = clean.split('-');
    const d = new Date(+parts[0], +parts[1]-1, +parts[2]);
    if (isNaN(d.getTime())) return '—';
    const mn = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    return `${+parts[2]} ${mn[d.getMonth()]} ${+parts[0]}`;
}
function _docExt(f)   { return ((f||'').split('.').pop()||'').toLowerCase(); }
function _docEmoji(f) { const e=_docExt(f); return {pdf:'📄',doc:'📝',docx:'📝',xls:'📊',xlsx:'📊',ppt:'📋',pptx:'📋'}[e]||'📎'; }

function _calcTaskStats(tasks) {
    const nonDraftTasks = tasks.filter(t => t.status_progress !== 'draft');
    const W = t => t.weight > 0 ? t.weight : 1;
    const tot  = nonDraftTasks.length;
    const done = nonDraftTasks.filter(t => t.status_progress === 'done').length;
    const prog = nonDraftTasks.filter(t => t.status_progress === 'In Progress').length;
    const todo = nonDraftTasks.filter(t => t.status_progress === 'To Do').length;
    const wDone = nonDraftTasks.filter(t => t.status_progress === 'done').reduce((s,t)=>s+W(t),0);
    const wProg = nonDraftTasks.filter(t => t.status_progress === 'In Progress').reduce((s,t)=>s+W(t),0);
    const wTodo = nonDraftTasks.filter(t => t.status_progress === 'To Do').reduce((s,t)=>s+W(t),0);
    const totalWeight = nonDraftTasks.reduce((s,t)=>s+W(t),0);
    const saApproved = nonDraftTasks.filter(t => t.status_akhir === 'approved').length;
    const saRevisi   = nonDraftTasks.filter(t => t.status_akhir === 'revisi').length;
    const saReview   = nonDraftTasks.filter(t => t.status_akhir === 'review').length;
    const saNull     = nonDraftTasks.filter(t => !t.status_akhir).length;
    const wSaApproved = nonDraftTasks.filter(t => t.status_akhir === 'approved').reduce((s,t)=>s+W(t),0);
    const wSaRevisi   = nonDraftTasks.filter(t => t.status_akhir === 'revisi').reduce((s,t)=>s+W(t),0);
    const wSaReview   = nonDraftTasks.filter(t => t.status_akhir === 'review').reduce((s,t)=>s+W(t),0);
    const appr = nonDraftTasks.filter(t => t.status_progress === 'done' && t.status_akhir === 'approved').length;
    const approvedWeight = nonDraftTasks.filter(t => t.status_progress === 'done' && t.status_akhir === 'approved').reduce((s,t)=>s+W(t),0);
    const pct = totalWeight > 0 ? Math.round((approvedWeight / totalWeight) * 100) : 0;
    return { tot,done,prog,todo,wDone,wProg,wTodo,totalWeight,appr,approvedWeight,pct,saApproved,saRevisi,saReview,saNull,wSaApproved,wSaRevisi,wSaReview };
}

const PDF_PRINT_CSS_P = `*{box-sizing:border-box;margin:0;padding:0;}body{font-family:'Georgia','Times New Roman',serif;background:#F3F4F6;padding:20px;}.pdf-wrap{max-width:794px;margin:0 auto;color:#1F2937;background:white;border:1px solid #D1D5DB;}.pdf-letterhead{background:#1E2A3A;padding:20px 28px 18px;display:flex;justify-content:space-between;align-items:flex-start;}.pdf-letterhead-left .doc-type{font-size:9px;text-transform:uppercase;letter-spacing:.15em;color:#9CA3AF;margin-bottom:5px;font-family:'Segoe UI',Arial,sans-serif;}.pdf-letterhead-left .doc-title{font-size:18px;font-weight:700;color:white;line-height:1.25;}.pdf-letterhead-left .doc-sub{font-size:11px;color:#9CA3AF;margin-top:4px;font-family:'Segoe UI',Arial,sans-serif;}.pdf-letterhead-right{text-align:right;flex-shrink:0;}.pdf-letterhead-right .doc-num{font-size:10px;color:#9CA3AF;font-family:'Courier New',monospace;margin-bottom:4px;}.pdf-letterhead-right .doc-date{font-size:11px;color:#D1D5DB;font-family:'Segoe UI',Arial,sans-serif;font-weight:500;}.pdf-rule{border:none;border-top:2px solid #374151;margin:0;}.pdf-project-info{padding:16px 28px;background:#F9FAFB;border-bottom:1px solid #E5E7EB;display:grid;grid-template-columns:1fr 1fr;gap:0;}.pdf-info-col{padding:0 12px;}.pdf-info-col:first-child{padding-left:0;border-right:1px solid #E5E7EB;}.pdf-info-col:last-child{padding-left:20px;}.pdf-info-row{display:flex;gap:8px;margin-bottom:7px;font-size:11px;align-items:flex-start;font-family:'Segoe UI',Arial,sans-serif;}.pdf-info-row:last-child{margin-bottom:0;}.pdf-info-lbl{min-width:108px;color:#6B7280;font-weight:500;flex-shrink:0;}.pdf-info-val{color:#111827;font-weight:600;line-height:1.5;}.pdf-section-header{padding:8px 28px 6px;background:white;border-bottom:1px solid #E5E7EB;display:flex;align-items:center;gap:10px;}.pdf-section-header span{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:#6B7280;font-family:'Segoe UI',Arial,sans-serif;}.pdf-section-header::before{content:'';width:3px;height:11px;background:#1E2A3A;border-radius:1px;flex-shrink:0;}.pdf-section-header::after{content:'';flex:1;height:1px;background:#E5E7EB;}.pdf-stats-table{width:100%;border-collapse:collapse;font-family:'Segoe UI',Arial,sans-serif;font-size:11px;}.pdf-stats-table th{background:#1E2A3A;color:white;padding:7px 10px;text-align:left;font-size:9px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;}.pdf-stats-table td{padding:7px 10px;border-bottom:1px solid #F3F4F6;color:#374151;}.pdf-stats-table tr:nth-child(even) td{background:#F9FAFB;}.pdf-stats-total-row td{background:#F3F4F6!important;font-weight:700;color:#1F2937;border-top:1px solid #D1D5DB;}.pdf-completion-block{margin-top:10px;padding:10px 12px;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:4px;font-family:'Segoe UI',Arial,sans-serif;}.pdf-completion-label{font-size:9px;color:#6B7280;text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px;}.pdf-completion-nums{font-size:13px;font-weight:700;color:#1E2A3A;margin-bottom:6px;}.pdf-bar-bg{background:#E5E7EB;height:6px;border-radius:3px;overflow:hidden;}.pdf-bar-fill{height:100%;background:#1E2A3A;border-radius:3px;}.pdf-tasks-wrap{padding:0 28px 24px;background:white;}.pdf-task-card{border:1px solid #D1D5DB;border-radius:4px;margin-bottom:14px;overflow:hidden;page-break-inside:avoid;}.pdf-task-head{padding:8px 12px;background:#F9FAFB;border-bottom:1px solid #E5E7EB;display:flex;align-items:flex-start;gap:10px;}.pdf-task-no{width:22px;height:22px;border-radius:3px;background:#1E2A3A;color:white;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0;}.pdf-task-no.approved{background:#374151;}.pdf-task-title-block{flex:1;min-width:0;}.pdf-task-title{font-size:12px;font-weight:700;color:#111827;line-height:1.3;font-family:'Segoe UI',Arial,sans-serif;}.pdf-task-desc{font-size:10px;color:#6B7280;margin-top:2px;line-height:1.5;font-family:'Segoe UI',Arial,sans-serif;}.pdf-task-badges{display:flex;gap:5px;flex-wrap:wrap;margin-left:auto;flex-shrink:0;}.pdf-badge{display:inline-flex;align-items:center;padding:2px 8px;border-radius:3px;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;font-family:'Segoe UI',Arial,sans-serif;}.badge-draft{background:#F3F4F6;color:#6B7280;border:1px solid #D1D5DB;}.badge-todo{background:#EFF6FF;color:#1D4ED8;border:1px solid #BFDBFE;}.badge-inprogress{background:#FFFBEB;color:#92400E;border:1px solid #FDE68A;}.badge-done{background:#F0FDF4;color:#166534;border:1px solid #BBF7D0;}.badge-review{background:#F5F3FF;color:#5B21B6;border:1px solid #DDD6FE;}.badge-revisi{background:#FFFBEB;color:#92400E;border:1px solid #FDE68A;}.badge-approved{background:#F0FDF4;color:#166534;border:1px solid #BBF7D0;}.pdf-task-body{padding:10px 12px;}.pdf-task-meta-row{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:10px;}.pdf-meta-item .lbl{font-size:8px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9CA3AF;margin-bottom:2px;font-family:'Segoe UI',Arial,sans-serif;}.pdf-meta-item .val{font-size:11px;font-weight:600;color:#1F2937;line-height:1.4;font-family:'Segoe UI',Arial,sans-serif;}.pdf-hasil-section{margin-top:8px;}.pdf-hasil-label{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#374151;margin-bottom:7px;display:flex;align-items:center;gap:5px;font-family:'Segoe UI',Arial,sans-serif;border-top:1px solid #E5E7EB;padding-top:8px;}.pdf-hasil-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:8px;}.pdf-hasil-img-wrap{border-radius:3px;overflow:hidden;border:1px solid #D1D5DB;aspect-ratio:16/10;background:#F9FAFB;}.pdf-hasil-img-wrap img{width:100%;height:100%;object-fit:cover;display:block;}.pdf-hasil-doc{display:flex;align-items:center;gap:8px;background:#F9FAFB;border:1px solid #D1D5DB;border-radius:3px;padding:9px 11px;}.pdf-hasil-doc .name{font-size:10px;font-weight:700;color:#374151;word-break:break-all;font-family:'Segoe UI',Arial,sans-serif;}.pdf-hasil-doc .type{font-size:9px;color:#9CA3AF;margin-top:2px;text-transform:uppercase;}.pdf-empty-foto{background:#F9FAFB;border:1px dashed #D1D5DB;border-radius:3px;padding:10px;text-align:center;font-size:10px;color:#9CA3AF;font-style:italic;font-family:'Segoe UI',Arial,sans-serif;}.pdf-doc-footer{background:#1E2A3A;padding:9px 28px;display:flex;justify-content:space-between;align-items:center;margin-top:auto;}.pdf-doc-footer span{font-size:9px;color:#9CA3AF;font-family:'Segoe UI',Arial,sans-serif;}#pdfPieChartP{display:block;}@media print{body{background:white;padding:0;}@page{margin:10mm 8mm;size:A4;}.pdf-wrap{max-width:100%;border:none;display:flex;flex-direction:column;min-height:277mm;}.pdf-doc-footer{margin-top:auto;}.pdf-task-card{page-break-inside:avoid;}.pdf-letterhead,.pdf-doc-footer,.pdf-stats-table th{-webkit-print-color-adjust:exact;print-color-adjust:exact;}}`;

function _buildPdfForProject(p) {
    const tasks   = (p.tasks || []).filter(t => t.status_progress !== 'draft');
    const timList = p.tim_list || [];
    const s       = _calcTaskStats(tasks);
    const now     = new Date();
    const nowFmt  = _fmtDateLong(now.toISOString().split('T')[0]);
    const docNum  = `DOC-${p.id_projek}-${now.getFullYear()}${String(now.getMonth()+1).padStart(2,'0')}`;
    const mulai   = _fmtDateLong(p.tanggal_mulai);
    const akhir   = _fmtDateLong(p.tanggal_selesai);
    const perus   = p.perusahaan_pt || p.perusahaan_nama || 'PT Kawan Kita Solusindo';

    let html = `<div class="pdf-wrap">
    <div class="pdf-letterhead">
        <div class="pdf-letterhead-left"><div class="doc-type">Laporan Manajemen Task</div><div class="doc-title">${escHtml(p.nama_projek)}</div><div class="doc-sub">${escHtml(perus)}</div></div>
        <div class="pdf-letterhead-right"><div class="doc-num">${docNum}</div><div class="doc-date">Diterbitkan: ${nowFmt}</div></div>
    </div><hr class="pdf-rule">`;

    html += `<div class="pdf-project-info">
        <div class="pdf-info-col">
            <div class="pdf-info-row"><span class="pdf-info-lbl">Project Manager</span><span class="pdf-info-val">${escHtml(p.pembuat_nama||'—')}</span></div>
            <div class="pdf-info-row"><span class="pdf-info-lbl">Tanggal Mulai</span><span class="pdf-info-val">${mulai}</span></div>
            <div class="pdf-info-row"><span class="pdf-info-lbl">Target Selesai</span><span class="pdf-info-val">${akhir}</span></div>
            <div class="pdf-info-row"><span class="pdf-info-lbl">Kategori</span><span class="pdf-info-val">${escHtml(p.kategori_nama||'—')}</span></div>
        </div>
        <div class="pdf-info-col">
            <div class="pdf-info-row"><span class="pdf-info-lbl">Perusahaan</span><span class="pdf-info-val">${escHtml(p.perusahaan_pt||'—')}</span></div>
            <div class="pdf-info-row"><span class="pdf-info-lbl">Pembuat Sistem</span><span class="pdf-info-val">PT Kawan Kita Solusindo</span></div>
            <div class="pdf-info-row"><span class="pdf-info-lbl">Deskripsi</span><span class="pdf-info-val">${escHtml(p.deskripsi||'Tidak ada deskripsi.')}</span></div>
        </div>
    </div>`;

    const progressRows = [
        { label:'Selesai (Done)',    n:s.done, w:s.wDone, key:'done'        },
        { label:'Proses Pengerjaan', n:s.prog, w:s.wProg, key:'In Progress' },
        { label:'Belum Pengerjaan',  n:s.todo, w:s.wTodo, key:'To Do'       },
    ];
    const SA_COLORS = { approved:'#22C55E', review:'#8B5CF6', revisi:'#F59E0B', null:'#9CA3AF' };
    const saRows = [
        { label:'Disetujui (Approved)', n:s.saApproved, w:s.wSaApproved, key:'approved', color:'#166534', bg:'#F0FDF4', border:'#BBF7D0' },
        { label:'Review PM',            n:s.saReview,   w:s.wSaReview,   key:'review',   color:'#5B21B6', bg:'#F5F3FF', border:'#DDD6FE' },
        { label:'Revisi PM',            n:s.saRevisi,   w:s.wSaRevisi,   key:'revisi',   color:'#92400E', bg:'#FFFBEB', border:'#FDE68A' },
        { label:'Belum Dinilai',        n:s.saNull,     w:Math.max(0,s.totalWeight-(s.wSaApproved+s.wSaRevisi+s.wSaReview)), key:'null', color:'#6B7280', bg:'#F9FAFB', border:'#E5E7EB' },
    ].filter(r => r.n > 0);

    const legendPie1 = progressRows.filter(r=>r.n>0).map(r=>{const pct=s.tot>0?Math.round((r.n/s.tot)*100):0;return `<div class="pdf-legend-item"><div class="pdf-legend-dot" style="background:${PIE_COLORS_P[r.key]};"></div><span>${r.label}: <strong>${r.n}</strong> task (${pct}%)</span></div>`;}).join('');
    const legendPie2 = saRows.map(r=>{const pct=s.tot>0?Math.round((r.n/s.tot)*100):0;return `<div class="pdf-legend-item"><div class="pdf-legend-dot" style="background:${SA_COLORS[r.key]};"></div><span>${r.label}: <strong>${r.n}</strong> task (${pct}%)</span></div>`;}).join('');

    html += `<div class="pdf-section-header"><span>Statistik &amp; Distribusi Status</span></div>
    <div style="padding:16px 28px;background:white;border-bottom:1px solid #E5E7EB;">
        <div style="display:flex;gap:16px;margin-bottom:16px;">
            <div style="flex:1;"><div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#1E2A3A;margin-bottom:5px;font-family:'Segoe UI',Arial,sans-serif;">Status Progress (Weight)</div>
                <table class="pdf-stats-table"><thead><tr><th>Status Progress</th><th style="text-align:center;">Total Weight</th><th style="text-align:center;">% Weight</th></tr></thead>
                <tbody>${progressRows.map(r=>{const wpct=s.totalWeight>0?Math.round((r.w/s.totalWeight)*100):0;const dot=PIE_COLORS_P[r.key]||'#D1D5DB';return `<tr><td style="display:flex;align-items:center;gap:6px;"><span style="display:inline-block;width:8px;height:8px;border-radius:2px;background:${dot};flex-shrink:0;"></span>${r.label}</td><td style="text-align:center;font-weight:700;color:#1E2A3A;">${r.w}</td><td style="text-align:center;font-weight:700;color:#374151;">${s.totalWeight>0?wpct+'%':'—'}</td></tr>`;}).join('')}
                <tr class="pdf-stats-total-row"><td>Total</td><td style="text-align:center;">${s.totalWeight}</td><td style="text-align:center;">100%</td></tr></tbody></table>
            </div>
            <div style="flex:1;"><div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#1E2A3A;margin-bottom:5px;font-family:'Segoe UI',Arial,sans-serif;">Status Penilaian PM (Weight)</div>
                <table class="pdf-stats-table"><thead><tr><th>Status Penilaian</th><th style="text-align:center;">Total Weight</th><th style="text-align:center;">% Weight</th></tr></thead>
                <tbody>${saRows.map(r=>{const wpct=s.totalWeight>0?Math.round((r.w/s.totalWeight)*100):0;return `<tr><td><span style="display:inline-flex;align-items:center;padding:1px 7px;border-radius:3px;font-size:9px;font-weight:700;background:${r.bg};color:${r.color};border:1px solid ${r.border};">${r.label}</span></td><td style="text-align:center;font-weight:700;color:#1E2A3A;">${r.w}</td><td style="text-align:center;font-weight:700;color:#374151;">${s.totalWeight>0?wpct+'%':'—'}</td></tr>`;}).join('')}
                <tr class="pdf-stats-total-row"><td>Total</td><td style="text-align:center;">${s.totalWeight}</td><td style="text-align:center;">100%</td></tr></tbody></table>
            </div>
        </div>
        <div style="display:flex;gap:16px;align-items:flex-start;">
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;"><div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;margin-bottom:6px;font-family:'Segoe UI',Arial,sans-serif;text-align:center;">Distribusi Status Progress</div><canvas id="pdfPieChartP" width="130" height="130"></canvas><div style="margin-top:8px;width:100%;font-family:'Segoe UI',Arial,sans-serif;">${legendPie1}</div></div>
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;"><div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;margin-bottom:6px;font-family:'Segoe UI',Arial,sans-serif;text-align:center;">Distribusi Status Penilaian PM</div><canvas id="pdfPieChartSA" width="130" height="130"></canvas><div style="margin-top:8px;width:100%;font-family:'Segoe UI',Arial,sans-serif;">${legendPie2}</div></div>
        </div>
        <div class="pdf-completion-block" style="margin-top:14px;">
            <div class="pdf-completion-label">Tingkat Penyelesaian Proyek (Done + Approved PM / Total)</div>
            <div class="pdf-completion-nums">${s.pct}% &mdash; ${s.appr} dari ${s.tot} task done &amp; disetujui PM (Weight: ${s.approvedWeight}/${s.totalWeight})</div>
            <div class="pdf-bar-bg"><div class="pdf-bar-fill" style="width:${s.pct}%;"></div></div>
        </div>
    </div>`;

    html += `<div class="pdf-section-header"><span>Detail Task (${tasks.length} task)</span></div><div class="pdf-tasks-wrap">`;
    if (!tasks.length) {
        html += `<div style="padding:20px;text-align:center;color:#9CA3AF;font-size:12px;font-family:'Segoe UI',Arial,sans-serif;">Belum ada task aktif dalam proyek ini.</div>`;
    } else {
        tasks.forEach((t, i) => {
            const member   = timList.find(m => m.id_tim === t.id_tim);
            const assignee = member ? (member.jabatan ? `${member.nama} (${member.jabatan})` : member.nama) : '—';
            const spLabel  = SP_LABEL_PDF[t.status_progress] || t.status_progress || '—';
            const spClass  = SP_BADGE_CLASS[t.status_progress] || 'badge-draft';
            const saLabel  = t.status_akhir ? (SA_LABEL_PDF[t.status_akhir] || t.status_akhir) : null;
            const saClass  = t.status_akhir ? (SA_BADGE_CLASS[t.status_akhir] || 'badge-draft') : '';
            const hasilF   = (t.foto || []).filter(f => f.tipe === 'hasil');
            let hasilHtml  = '';
            if (hasilF.length) {
                const items = hasilF.map(f => {
                    if (isImageFile(f.nama_file || f.url)) return `<div class="pdf-hasil-img-wrap"><img src="${escHtml(f.url)}" alt="${escHtml(f.nama_file||'Hasil')}" onerror="this.style.display='none';this.parentElement.style.display='none'"></div>`;
                    const ext = _docExt(f.nama_file || f.url);
                    return `<div class="pdf-hasil-doc"><span class="icon">${_docEmoji(f.nama_file||f.url)}</span><div><div class="name">${escHtml((f.nama_file||'Dokumen').split('/').pop())}</div><div class="type">${ext||'file'}</div></div></div>`;
                }).join('');
                hasilHtml = `<div class="pdf-hasil-section"><div class="pdf-hasil-label">Laporan Hasil (${hasilF.length} file)</div><div class="pdf-hasil-grid">${items}</div></div>`;
            } else {
                hasilHtml = `<div class="pdf-hasil-section"><div class="pdf-hasil-label">Laporan Hasil</div><div class="pdf-empty-foto">Belum ada foto/dokumen laporan hasil untuk task ini.</div></div>`;
            }
            html += `<div class="pdf-task-card">
                <div class="pdf-task-head">
                    <div class="pdf-task-no ${t.status_akhir==='approved'&&t.status_progress==='done'?'approved':''}">${i+1}</div>
                    <div class="pdf-task-title-block"><div class="pdf-task-title">${escHtml(t.judul_tugas||'—')}</div>${t.deskripsi_tugas?`<div class="pdf-task-desc">${escHtml(t.deskripsi_tugas.substring(0,220))}${t.deskripsi_tugas.length>220?'...':''}</div>`:''}</div>
                    <div class="pdf-task-badges"><span class="pdf-badge ${spClass}">${spLabel}</span>${saLabel?`<span class="pdf-badge ${saClass}">${saLabel}</span>`:''}</div>
                </div>
                <div class="pdf-task-body">
                    <div class="pdf-task-meta-row">
                        <div class="pdf-meta-item"><div class="lbl">Penanggung Jawab</div><div class="val">${escHtml(assignee)}</div></div>
                        <div class="pdf-meta-item"><div class="lbl">Tanggal Mulai</div><div class="val">${_fmtDateLong(t.tanggal_mulai)}</div></div>
                        <div class="pdf-meta-item"><div class="lbl">Tenggat Waktu</div><div class="val">${_fmtDateLong(t.tenggat_waktu)}</div></div>
                        <div class="pdf-meta-item"><div class="lbl">Tanggal Selesai</div><div class="val" style="${t.status_progress==='done'&&t.status_akhir==='approved'?'color:#166534;font-weight:700;':'color:#9CA3AF;'}">${t.tanggal_selesai?_fmtDateLong(t.tanggal_selesai):'—'}</div></div>
                    </div>
                    ${hasilHtml}
                </div>
            </div>`;
        });
    }
    html += `</div><div class="pdf-doc-footer"><span>PT Kawan Kita Solusindo</span><span>Sistem Manajemen Task &mdash; ${new Date().toLocaleString('id-ID')}</span></div></div>`;
    return html;
}

function _drawPieDonut(canvasId, data, colorMap, total) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const filtered = data.filter(d => d.n > 0);
    if (total === 0 || !filtered.length) { ctx.fillStyle='#E5E7EB'; ctx.beginPath(); ctx.arc(65,65,60,0,Math.PI*2); ctx.fill(); return; }
    let startAngle = -Math.PI/2;
    const cx=65, cy=65, r=58;
    filtered.forEach(d => {
        const slice = (d.n/total)*Math.PI*2;
        ctx.beginPath(); ctx.moveTo(cx,cy); ctx.arc(cx,cy,r,startAngle,startAngle+slice); ctx.closePath();
        ctx.fillStyle=colorMap[d.key]||'#9CA3AF'; ctx.fill(); ctx.strokeStyle='white'; ctx.lineWidth=2; ctx.stroke();
        if (d.n/total>=0.07) { const mid=startAngle+slice/2; ctx.fillStyle=d.key==='To Do'?'#374151':'white'; ctx.font='bold 9px Segoe UI,Arial,sans-serif'; ctx.textAlign='center'; ctx.textBaseline='middle'; ctx.fillText(Math.round((d.n/total)*100)+'%',cx+(r*.62)*Math.cos(mid),cy+(r*.62)*Math.sin(mid)); }
        startAngle+=slice;
    });
    ctx.beginPath(); ctx.arc(cx,cy,r*.36,0,Math.PI*2); ctx.fillStyle='white'; ctx.fill();
    ctx.fillStyle='#1E2A3A'; ctx.font='bold 15px Georgia,serif'; ctx.textAlign='center'; ctx.textBaseline='middle'; ctx.fillText(total,cx,cy-5);
    ctx.font='8px Segoe UI,Arial,sans-serif'; ctx.fillStyle='#9CA3AF'; ctx.fillText('task',cx,cy+9);
}

function _drawPieChartProject(tasks) {
    const s = _calcTaskStats(tasks);
    _drawPieDonut('pdfPieChartP',[{key:'done',n:s.done},{key:'In Progress',n:s.prog},{key:'To Do',n:s.todo}],PIE_COLORS_P,s.tot);
    const SA_C = { approved:'#22C55E', review:'#8B5CF6', revisi:'#F59E0B', null:'#9CA3AF' };
    _drawPieDonut('pdfPieChartSA',[{key:'approved',n:s.saApproved},{key:'review',n:s.saReview},{key:'revisi',n:s.saRevisi},{key:'null',n:s.saNull}],SA_C,s.tot);
}

let _currentExportProjekId = null;

function exportProjectPDF(id) {
    if (!ROLE_CAN_EXPORT_PROJ) { showToast('❌ Anda tidak memiliki akses export project.', 'error'); return; }
    const p = projekData[id];
    if (!p) { showToast('❌ Data project tidak ditemukan.', 'error'); return; }
    _currentExportProjekId = id;
    const content = _buildPdfForProject(p);
    document.getElementById('pdfPreviewToolbarProject').querySelector('h6').textContent = `📄 Preview Laporan Task — ${p.nama_projek}`;
    document.getElementById('pdfPreviewContentProject').innerHTML = content;
    document.getElementById('pdfPreviewModalProject').classList.add('open');
    const filteredTasks = (p.tasks || []).filter(t => t.status_progress !== 'draft');
    requestAnimationFrame(() => _drawPieChartProject(filteredTasks));
}

function closePdfPreviewProject() {
    document.getElementById('pdfPreviewModalProject').classList.remove('open');
}

function printPDFProject() {
    const id = _currentExportProjekId;
    const p  = id ? projekData[id] : null;
    const canvas1 = document.getElementById('pdfPieChartP');
    const canvas2 = document.getElementById('pdfPieChartSA');
    let content = document.getElementById('pdfPreviewContentProject').innerHTML;
    if (canvas1) { const img1=canvas1.toDataURL('image/png'); content=content.replace(/<canvas id="pdfPieChartP"[^>]*><\/canvas>/,`<img src="${img1}" width="130" height="130" style="display:block;">`); }
    if (canvas2) { const img2=canvas2.toDataURL('image/png'); content=content.replace(/<canvas id="pdfPieChartSA"[^>]*><\/canvas>/,`<img src="${img2}" width="130" height="130" style="display:block;">`); }
    const win   = window.open('', '_blank', 'width=960,height=720');
    const title = p ? `Laporan Task — ${p.nama_projek}` : 'Laporan Task';
    win.document.write(`<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><title>${escHtml(title)}</title><style>${PDF_PRINT_CSS_P}</style></head><body>${content}</body></html>`);
    win.document.close();
    setTimeout(() => { win.focus(); win.print(); }, 700);
}
</script>
@endpush