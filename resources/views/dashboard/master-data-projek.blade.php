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
.export-dropdown-menu.open {
    display: block;
}
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
.export-dropdown-item:hover {
    background: #F3F4F6;
}
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
                <p class="project-desc">Manajemen dan monitoring seluruh project perusahaan</p>
            </div>
            <div class="header-actions">
                <button class="btn-action btn-outline-custom" onclick="exportData()"><i class="bx bx-download"></i> Export</button>
                <button class="btn-action btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalTambahProject"><i class="bx bx-plus"></i> Tambah Project</button>
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
                    @foreach(['col-no'=>'No','col-info'=>'Informasi Project','col-kategori'=>'Kategori / PM','col-status'=>'Status','col-timeline'=>'Timeline','col-progress'=>'Progress','col-laporan'=>'Export','col-timestamps'=>'Dibuat/Diperbarui','col-aksi'=>'Aksi'] as $cId => $cLbl)
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
                    <th class="col-laporan">Export</th>
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

                // ─── PROGRESS: hanya task non-draft, syarat selesai = done + approved ───
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
                <td class="col-status">
                    <div class="inline-status-wrap {{ $statusCls }}" id="status-wrap-{{ $projek->id_projek }}">
                        <span class="dot"></span>
                        <select class="inline-status-select" id="status-sel-{{ $projek->id_projek }}" onchange="updateStatusInline({{ $projek->id_projek }}, this)" title="Klik untuk ubah status">
                            <option value="pending"     {{ $projek->status==='pending'     ? 'selected':'' }}>Pending</option>
                            <option value="in_progress" {{ $projek->status==='in_progress' ? 'selected':'' }}>In Progress</option>
                            <option value="aktif"       {{ $projek->status==='aktif'       ? 'selected':'' }}>Aktif</option>
                            <option value="selesai"     {{ $projek->status==='selesai'     ? 'selected':'' }}>Selesai</option>
                        </select>
                    </div>
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
                    @if($isOverdue)
                        <i class="bx bx-error-circle" title="Overdue" style="font-size:11px;"></i>
                    @endif
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
                        {{-- Label: task done+approved dari total non-draft --}}
                        <div class="prog-label">{{ $approvedCount }} dari {{ $nondraftCount }} tugas approved</div>
                    </div>
                </td>
                {{-- ══ EXPORT DROPDOWN ══ --}}
                <td class="col-laporan">
                    <div class="export-dropdown-wrap" id="edw-{{ $projek->id_projek }}">
                        <button class="report-btn" onclick="toggleExportDropdown({{ $projek->id_projek }}, event)">
                            <i class="bx bx-file-export"></i> Export
                            <i class="bx bx-chevron-down" style="font-size:11px;margin-left:2px;"></i>
                        </button>
                        <div class="export-dropdown-menu" id="edm-{{ $projek->id_projek }}">
                            <button class="export-dropdown-item"
                                onclick="exportProjectPDF({{ $projek->id_projek }}); closeAllExportDropdowns()">
                                <i class="bx bx-file-pdf" style="color:#EF4444;font-size:16px;"></i> Export PDF
                            </button>
                            <button class="export-dropdown-item"
                                onclick="exportProjectExcel({{ $projek->id_projek }}); closeAllExportDropdowns()">
                                <i class="bx bx-spreadsheet" style="color:#16a34a;font-size:16px;"></i> Export Excel
                            </button>
                        </div>
                    </div>
                </td>
                <td class="col-timestamps" style="display:none;">
                    <div class="date-info">
                        <div class="date-row"><i class="bx bx-calendar-plus" style="color:var(--p1);font-size:13px;"></i><span style="font-size:11px;color:var(--ink-600);font-weight:500;">{{ $dibuatPada ? $dibuatPada->format('d M Y') : '—' }}</span></div>
                        <div class="date-row"><i class="bx bx-revision" style="color:var(--ink-400);font-size:13px;"></i><span style="font-size:11px;color:var(--ink-400);">{{ $diperbarui ? $diperbarui->format('d M Y') : '—' }}</span></div>
                    </div>
                </td>
                <td class="col-aksi">
                    <div class="action-buttons">
                        <a href="{{ route('task.index', $projek->id_projek) }}"
                           class="btn btn-sm"
                           style="background:#EEF2FF;color:#4F46E5;border:1px solid #C7D2FE;border-radius:8px;width:36px;height:36px;display:inline-flex;align-items:center;justify-content:center;font-size:16px;transition:all 0.2s;"
                           onmouseover="this.style.background='#4F46E5';this.style.color='white';"
                           onmouseout="this.style.background='#EEF2FF';this.style.color='#4F46E5';"
                           title="Kelola Task">
                            <i class="bx bx-task"></i>
                        </a>
                        <button type="button" class="action-btn view" title="Lihat Detail" onclick="openViewModal({{ $projek->id_projek }})"><i class="bx bx-show"></i></button>
                        <button type="button" class="action-btn edit" title="Edit Project" onclick="openEditModal({{ $projek->id_projek }})"><i class="bx bx-edit-alt"></i></button>
                        <button type="button" class="action-btn delete" title="Hapus Project"
                            onclick="confirmDelete({{ $projek->id_projek }}, '{{ addslashes($projek->nama_projek) }}', '{{ route('master-data-projek.destroy', $projek->id_projek) }}')">
                            <i class="bx bx-trash-alt"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9">
                    <div class="empty-state"><i class="bx bx-folder-open"></i><h5>Tidak ada project ditemukan</h5><p>Coba ubah filter pencarian atau tambahkan project baru.</p></div>
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
                    $current  = $projeks->currentPage();
                    $last     = $projeks->lastPage();
                    $window   = 2;
                    $pages    = collect();
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
{{-- ══════════════════ MODAL TAMBAH ══════════════════ --}}
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
{{-- ══════════════════ MODAL EDIT ══════════════════ --}}
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
                <div class="view-section">
                    <div class="view-section-title"><i class="bx bx-money"></i> Keuangan</div>
                    <div class="view-row"><span class="view-label">Nominal Project</span><span class="view-value" id="view_nominal_projek">—</span></div>
                    <div class="view-row"><span class="view-label">Sisa Tagihan</span><span class="view-value" id="view_sisa_tanggungan">—</span></div>
                </div>
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
                <button type="button" class="btn-action btn-primary-custom" id="view_edit_btn"><i class="bx bx-edit-alt"></i> Edit Project</button>
            </div>
        </div>
    </div>
</div>
{{-- ══════════════════ MODAL KONFIRMASI HAPUS ══════════════════ --}}
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
{{-- ══════════════════ PDF PREVIEW MODAL ══════════════════ --}}
<div id="pdfPreviewModalProject">
    <div id="pdfPreviewBackdropProject" onclick="closePdfPreviewProject()"></div>
    <div id="pdfPreviewBoxProject">
        <div id="pdfPreviewToolbarProject">
            <h6>&#128196; Preview Laporan Task</h6>
            <button class="pdf-toolbar-btn-p print-btn" onclick="printPDFProject()">
                &#128424; Cetak / Simpan PDF
            </button>
            <button class="pdf-toolbar-btn-p" onclick="closePdfPreviewProject()">
                &#10005; Tutup
            </button>
        </div>
        <div id="pdfPreviewContentProject">
            {{-- diisi oleh JS --}}
        </div>
    </div>
</div>
<div id="__toast"><i id="__toast-icon" class="bx"></i><span id="__toast-msg"></span></div>
@endsection
@push('scripts')
<script>
'use strict';
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
            if (!noResult) {
                noResult = document.createElement('div');
                noResult.className = 'sd-no-results';
                dropdown.appendChild(noResult);
            }
            noResult.innerHTML = '<i class="bx bx-search-alt"></i>Tidak ditemukan: <strong>' + escHtml(query) + '</strong>';
            noResult.style.display = '';
        } else if (noResult) {
            noResult.style.display = 'none';
        }
    }
    function escHtml(t) {
        return t.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }
    searchInput.addEventListener('input', () => {
        filterOptions(searchInput.value);
        focusedIdx = -1;
        updateFocus();
    });
    function getVisibleOptions() {
        return allOptions.filter(o => o.style.display !== 'none');
    }
    function updateFocus() {
        const vis = getVisibleOptions();
        vis.forEach((o, i) => o.classList.toggle('sd-focused', i === focusedIdx));
        if (focusedIdx >= 0 && vis[focusedIdx]) vis[focusedIdx].scrollIntoView({ block:'nearest' });
    }
    searchInput.addEventListener('keydown', e => {
        const vis = getVisibleOptions();
        if (e.key === 'ArrowDown')  { e.preventDefault(); focusedIdx = Math.min(focusedIdx+1, vis.length-1); updateFocus(); }
        else if (e.key === 'ArrowUp')   { e.preventDefault(); focusedIdx = Math.max(focusedIdx-1, 0); updateFocus(); }
        else if (e.key === 'Enter') { e.preventDefault(); if (focusedIdx >= 0 && vis[focusedIdx]) selectOption(vis[focusedIdx]); }
        else if (e.key === 'Escape') { closeDropdown(); displayInput.focus(); }
        else if (e.key === 'Tab')    { closeDropdown(); }
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
    document.addEventListener('click', e => {
        if (!wrapEl.contains(e.target)) closeDropdown();
    });
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
        const sd  = document.getElementById('sd-tambah-perusahaan');
        const val = '{{ old("id_perusahaan") }}';
        const opt = sd?.querySelector(`.sd-option[data-value="${val}"]`);
        if (sd && opt) sd.sdSetValue(val, opt.dataset.label);
    })();
    @endif
    @if(old('id_kategori_projek'))
    (function() {
        const sd  = document.getElementById('sd-tambah-kategori');
        const val = '{{ old("id_kategori_projek") }}';
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

function fmtDate(d) {
    if (!d) return '—';
    
    // Handle format ISO dengan atau tanpa waktu
    let dateStr = String(d).trim();
    
    // Ambil bagian tanggal saja (YYYY-MM-DD)
    if (dateStr.includes('T')) {
        dateStr = dateStr.split('T')[0];
    }
    
    // Parse tanggal
    const parts = dateStr.split('-');
    if (parts.length !== 3) return '—';
    
    const year = parseInt(parts[0]);
    const month = parseInt(parts[1]) - 1; // Bulan dimulai dari 0
    const day = parseInt(parts[2]);
    
    const dt = new Date(year, month, day);
    if (isNaN(dt.getTime())) return '—';
    
    // Nama bulan dalam Bahasa Indonesia
    const bulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    return `${day} ${bulan[dt.getMonth()]} ${year}`;
}
function fmtRupiah(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); }
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
            const raw    = parseRibuan(this.value);
            this.value   = formatRibuan(this.value);
            hiddenEl.value = raw;
            document.getElementById('tambah_sisa_display').value    = fmtRupiah(raw);
            document.getElementById('tambah_sisa_tanggungan').value = raw;
        });
        displayEl.addEventListener('keydown', function (e) {
            const allow = ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Home','End'];
            if (!allow.includes(e.key) && !/^\d$/.test(e.key)) e.preventDefault();
        });
    }
});
function updateProgressColor(id, status) {
    const color = STATUS_COLOR[status] || '#9CA3AF';
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
    if (type === 'success') {
        icon.className = 'bx bx-check-circle';
        t.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
    } else if (type === 'error') {
        icon.className = 'bx bx-error-circle';
        t.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
    } else {
        icon.className = 'bx bx-info-circle';
        t.style.background = 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
    }
    clearTimeout(t.__tmr);
    t.__tmr = setTimeout(() => { t.className = ''; }, 3000);
}
/* ── Hapus ── */
function confirmDelete(id, nama, route) {
    document.getElementById('deleteProjectName').textContent = nama;
    document.getElementById('deleteForm').action = route;
    new bootstrap.Modal(document.getElementById('modalHapusProject')).show();
}
/* ── Inline status PATCH ── */
function updateStatusInline(id, selectEl) {
    const newStatus = selectEl.value;
    const wrap = document.getElementById('status-wrap-' + id);
    wrap.classList.remove(...STATUS_CLASSES);
    wrap.classList.add('s-' + newStatus);
    updateProgressColor(id, newStatus);
    const statusText = { 'aktif':'Aktif', 'in_progress':'In Progress', 'selesai':'Selesai', 'pending':'Pending' };
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
 document.getElementById('view_nominal_projek').textContent  = fmtRupiah(p.nominal_projek  || 0);
    document.getElementById('view_sisa_tanggungan').textContent = fmtRupiah(p.sisa_tanggungan || 0);
    document.getElementById('view_status').innerHTML = `<span class="status-badge ${scMap[p.status]||'status-pending'}"><span class="dot"></span>${slMap[p.status]||p.status}</span>`;
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
        // Keterangan: done+approved / total non-draft
        detEl.innerHTML  = `<span style="font-weight:700;color:${color};">${p.approved_count} tugas done+approved</span><span style="color:var(--ink-400);"> dari ${p.total_count} tugas non-draft (weight: ${p.approved_weight}/${p.total_weight})</span>`;
    } else {
        progEl.innerHTML = `<span style="color:var(--ink-300);font-weight:600;">0% — Belum ada tugas aktif</span>`;
        detEl.textContent = '—';
    }
    if (p.dokumen_perjanjian) {
        const ext = p.dokumen_perjanjian.split('.').pop().toLowerCase();
        const isImage = ['jpg','jpeg','png','webp'].includes(ext);
        document.getElementById('view_dokumen').innerHTML = isImage
            ? `<a href="/storage/${p.dokumen_perjanjian}" target="_blank"><img src="/storage/${p.dokumen_perjanjian}" style="max-width:100%;max-height:200px;border-radius:8px;border:1px solid var(--ink-200);cursor:pointer;" title="Klik untuk buka"></a>`
            : `<a href="/storage/${p.dokumen_perjanjian}" target="_blank" class="file-btn"><i class="bx bx-file-blank"></i> Preview Dokumen</a>`;
    } else {
        document.getElementById('view_dokumen').innerHTML = `<span style="color:var(--ink-300);font-style:italic;font-size:13px;">Belum ada dokumen</span>`;
    }
    document.getElementById('view_edit_btn').onclick = () => {
        bootstrap.Modal.getInstance(document.getElementById('modalViewProject')).hide();
        setTimeout(() => openEditModal(id), 300);
    };
    new bootstrap.Modal(document.getElementById('modalViewProject')).show();
}
/* ── EDIT Modal ── */
function openEditModal(id) {
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
        const allow = ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Home','End'];
        if (!allow.includes(e.key) && !/^\d$/.test(e.key)) e.preventDefault();
    });
    const sdPerusahaan = document.getElementById('sd-edit-perusahaan');
    const sdKategori   = document.getElementById('sd-edit-kategori');
    if (sdPerusahaan?.sdSetValue) sdPerusahaan.sdSetValue(p.id_perusahaan, p.perusahaan_label);
    if (sdKategori?.sdSetValue)   sdKategori.sdSetValue(p.id_kategori_projek, p.id_kategori_projek ? p.kategori_nama : '');
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
    const s = JSON.parse(localStorage.getItem('projColSettings') || '{}');
    s[cls] = visible;
    localStorage.setItem('projColSettings', JSON.stringify(s));
}
document.addEventListener('DOMContentLoaded', () => {
    const s = JSON.parse(localStorage.getItem('projColSettings') || '{}');
    if (!('col-timestamps' in s)) {
        s['col-timestamps'] = false;
        localStorage.setItem('projColSettings', JSON.stringify(s));
    }
    Object.entries(s).forEach(([cls, vis]) => {
        document.querySelectorAll('.' + cls).forEach(el => el.style.display = vis ? '' : 'none');
        const chk = document.getElementById('chk_' + cls);
        if (chk) chk.checked = !!vis;
    });
});
@if($errors->any() && !old('_method'))
document.addEventListener('DOMContentLoaded', () => {
    new bootstrap.Modal(document.getElementById('modalTambahProject')).show();
});
@endif
/* ── Export (tabel project) ── */
function exportData() {
    const p = new URLSearchParams({
        search: document.querySelector('[name="search"]')?.value || '',
        status: document.querySelector('[name="status"]')?.value || '',
        id_kategori_projek: document.querySelector('[name="id_kategori_projek"]')?.value || '',
        export: '1',
    });
    window.location.href = '{{ route("master-data-projek.index") }}?' + p.toString();
}
/* ════════════════════════════════════════════════════════════════
   EXPORT DROPDOWN PER BARIS
════════════════════════════════════════════════════════════════ */
function toggleExportDropdown(id, e) {
    e.stopPropagation();
    document.querySelectorAll('.export-dropdown-menu').forEach(el => {
        if (el.id !== 'edm-' + id) el.classList.remove('open');
    });
    const menu = document.getElementById('edm-' + id);
    if (menu) menu.classList.toggle('open');
}
function closeAllExportDropdowns() {
    document.querySelectorAll('.export-dropdown-menu').forEach(el => el.classList.remove('open'));
}
document.addEventListener('click', closeAllExportDropdowns);
/* ════════════════════════════════════════════════════════════════
   KONSTANTA LABEL PDF/EXCEL
════════════════════════════════════════════════════════════════ */
const SP_LABEL_PDF = {
    'draft':'Draft', 'To Do':'Belum Pengerjaan',
    'In Progress':'Proses Pengerjaan', 'done':'Selesai',
};
const SA_LABEL_PDF = { 'review':'Review PM', 'revisi':'Revisi PM', 'approved':'Disetujui' };
const SP_BADGE_CLASS = {
    'draft':'badge-draft', 'To Do':'badge-todo',
    'In Progress':'badge-inprogress', 'done':'badge-done',
};
const SA_BADGE_CLASS = { 'review':'badge-review', 'revisi':'badge-revisi', 'approved':'badge-approved' };
const PIE_COLORS_P = {
    'done':'#3B7DD8', 'In Progress':'#E8A838', 'To Do':'#9CA3AF',
};
/* ════════════════════════════════════════════════════════════════
   HELPERS EXPORT
════════════════════════════════════════════════════════════════ */
function _fmtDateLong(s) {
    if (!s) return '—';
    
    // Bersihkan string tanggal
    let clean = String(s).trim();
    
    // Ambil bagian YYYY-MM-DD saja
    if (clean.includes('T')) {
        clean = clean.split('T')[0];
    }
    
    // Validasi format YYYY-MM-DD
    if (!/^\d{4}-\d{2}-\d{2}$/.test(clean)) return '—';
    
    const parts = clean.split('-');
    const year = parseInt(parts[0]);
    const month = parseInt(parts[1]) - 1;
    const day = parseInt(parts[2]);
    
    const d = new Date(year, month, day);
    if (isNaN(d.getTime())) return '—';
    
    const mn = ['Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember'];
    
    return `${day} ${mn[d.getMonth()]} ${year}`;
}
function _docExt(f) { return ((f||'').split('.').pop()||'').toLowerCase(); }
function _docEmoji(f) {
    const e = _docExt(f);
    return { pdf:'📄', doc:'📝', docx:'📝', xls:'📊', xlsx:'📊', ppt:'📋', pptx:'📋' }[e] || '📎';
}

/* ════════════════════════════════════════════════════════════════
   _calcTaskStats — LOGIKA BARU
   - Exclude draft dari semua perhitungan
   - Selesai = status_progress "done" DAN status_akhir "approved"
   - Persentase = weight(done+approved) / weight(non-draft) × 100
════════════════════════════════════════════════════════════════ */
function _calcTaskStats(tasks) {
    // Exclude draft dari semua perhitungan progress & laporan
    const nonDraftTasks = tasks.filter(t => t.status_progress !== 'draft');

    const W = t => t.weight > 0 ? t.weight : 1;

    // ── COUNT per status_progress ──
    const tot  = nonDraftTasks.length;
    const done = nonDraftTasks.filter(t => t.status_progress === 'done').length;
    const prog = nonDraftTasks.filter(t => t.status_progress === 'In Progress').length;
    const todo = nonDraftTasks.filter(t => t.status_progress === 'To Do').length;

    // ── WEIGHT per status_progress ──
    const wDone = nonDraftTasks.filter(t => t.status_progress === 'done').reduce((s,t)=>s+W(t),0);
    const wProg = nonDraftTasks.filter(t => t.status_progress === 'In Progress').reduce((s,t)=>s+W(t),0);
    const wTodo = nonDraftTasks.filter(t => t.status_progress === 'To Do').reduce((s,t)=>s+W(t),0);

    // ── Weight total non-draft (penyebut) ──
    const totalWeight = nonDraftTasks.reduce((s,t)=>s+W(t),0);

    // ── Status akhir breakdown (dari SEMUA non-draft, bukan hanya done) ──
    const saApproved = nonDraftTasks.filter(t => t.status_akhir === 'approved').length;
    const saRevisi   = nonDraftTasks.filter(t => t.status_akhir === 'revisi').length;
    const saReview   = nonDraftTasks.filter(t => t.status_akhir === 'review').length;
    const saNull     = nonDraftTasks.filter(t => !t.status_akhir).length;

    const wSaApproved = nonDraftTasks.filter(t => t.status_akhir === 'approved').reduce((s,t)=>s+W(t),0);
    const wSaRevisi   = nonDraftTasks.filter(t => t.status_akhir === 'revisi').reduce((s,t)=>s+W(t),0);
    const wSaReview   = nonDraftTasks.filter(t => t.status_akhir === 'review').reduce((s,t)=>s+W(t),0);

    // ── Done + Approved (progress sesungguhnya) ──
    const appr = nonDraftTasks.filter(
        t => t.status_progress === 'done' && t.status_akhir === 'approved'
    ).length;
    const approvedWeight = nonDraftTasks
        .filter(t => t.status_progress === 'done' && t.status_akhir === 'approved')
        .reduce((s,t)=>s+W(t),0);

    const pct = totalWeight > 0 ? Math.round((approvedWeight / totalWeight) * 100) : 0;

    return {
        tot, done, prog, todo,
        wDone, wProg, wTodo, totalWeight,
        appr, approvedWeight, pct,
        saApproved, saRevisi, saReview, saNull,
        wSaApproved, wSaRevisi, wSaReview,
    };
}

/* ════════════════════════════════════════════════════════════════
   PDF EXPORT
════════════════════════════════════════════════════════════════ */
const PDF_PRINT_CSS_P = `
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Georgia','Times New Roman',serif;background:#F3F4F6;padding:20px;}
.pdf-wrap{max-width:794px;margin:0 auto;color:#1F2937;background:white;border:1px solid #D1D5DB;}
.pdf-letterhead{background:#1E2A3A;padding:20px 28px 18px;display:flex;justify-content:space-between;align-items:flex-start;}
.pdf-letterhead-left .doc-type{font-size:9px;font-weight:400;text-transform:uppercase;letter-spacing:.15em;color:#9CA3AF;margin-bottom:5px;font-family:'Segoe UI',Arial,sans-serif;}
.pdf-letterhead-left .doc-title{font-size:18px;font-weight:700;color:white;line-height:1.25;}
.pdf-letterhead-left .doc-sub{font-size:11px;color:#9CA3AF;margin-top:4px;font-family:'Segoe UI',Arial,sans-serif;}
.pdf-letterhead-right{text-align:right;flex-shrink:0;}
.pdf-letterhead-right .doc-num{font-size:10px;color:#9CA3AF;font-family:'Courier New',monospace;margin-bottom:4px;}
.pdf-letterhead-right .doc-date{font-size:11px;color:#D1D5DB;font-family:'Segoe UI',Arial,sans-serif;font-weight:500;}
.pdf-rule{border:none;border-top:2px solid #374151;margin:0;}
.pdf-project-info{padding:16px 28px;background:#F9FAFB;border-bottom:1px solid #E5E7EB;display:grid;grid-template-columns:1fr 1fr;gap:0;}
.pdf-info-col{padding:0 12px;}.pdf-info-col:first-child{padding-left:0;border-right:1px solid #E5E7EB;}.pdf-info-col:last-child{padding-left:20px;}
.pdf-info-row{display:flex;gap:8px;margin-bottom:7px;font-size:11px;align-items:flex-start;font-family:'Segoe UI',Arial,sans-serif;}
.pdf-info-row:last-child{margin-bottom:0;}
.pdf-info-lbl{min-width:108px;color:#6B7280;font-weight:500;flex-shrink:0;}
.pdf-info-val{color:#111827;font-weight:600;line-height:1.5;}
.pdf-section-header{padding:8px 28px 6px;background:white;border-bottom:1px solid #E5E7EB;display:flex;align-items:center;gap:10px;}
.pdf-section-header span{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:#6B7280;font-family:'Segoe UI',Arial,sans-serif;}
.pdf-section-header::before{content:'';width:3px;height:11px;background:#1E2A3A;border-radius:1px;flex-shrink:0;}
.pdf-section-header::after{content:'';flex:1;height:1px;background:#E5E7EB;}
.pdf-stats-wrapper{padding:16px 28px;background:white;border-bottom:1px solid #E5E7EB;display:flex;gap:24px;align-items:flex-start;}
.pdf-stats-table-wrap{flex:1;}
.pdf-stats-table{width:100%;border-collapse:collapse;font-family:'Segoe UI',Arial,sans-serif;font-size:11px;}
.pdf-stats-table th{background:#1E2A3A;color:white;padding:7px 10px;text-align:left;font-size:9px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;}
.pdf-stats-table td{padding:7px 10px;border-bottom:1px solid #F3F4F6;color:#374151;}
.pdf-stats-table tr:nth-child(even) td{background:#F9FAFB;}
.pdf-stats-count{font-weight:700;color:#111827;}
.pdf-stats-total-row td{background:#F3F4F6!important;font-weight:700;color:#1F2937;border-top:1px solid #D1D5DB;}
.pdf-completion-block{margin-top:10px;padding:10px 12px;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:4px;font-family:'Segoe UI',Arial,sans-serif;}
.pdf-completion-label{font-size:9px;color:#6B7280;text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px;}
.pdf-completion-nums{font-size:13px;font-weight:700;color:#1E2A3A;margin-bottom:6px;}
.pdf-bar-bg{background:#E5E7EB;height:6px;border-radius:3px;overflow:hidden;}
.pdf-bar-fill{height:100%;background:#1E2A3A;border-radius:3px;}
.pdf-chart-wrap{width:180px;flex-shrink:0;display:flex;flex-direction:column;align-items:center;}
.pdf-chart-title{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;margin-bottom:8px;font-family:'Segoe UI',Arial,sans-serif;text-align:center;}
.pdf-chart-legend{margin-top:10px;width:100%;font-family:'Segoe UI',Arial,sans-serif;}
.pdf-legend-item{display:flex;align-items:center;gap:6px;font-size:9px;color:#374151;margin-bottom:4px;}
.pdf-legend-dot{width:10px;height:10px;border-radius:2px;flex-shrink:0;}
.pdf-tasks-wrap{padding:0 28px 24px;background:white;}
.pdf-task-card{border:1px solid #D1D5DB;border-radius:4px;margin-bottom:14px;overflow:hidden;page-break-inside:avoid;}
.pdf-task-head{padding:8px 12px;background:#F9FAFB;border-bottom:1px solid #E5E7EB;display:flex;align-items:flex-start;gap:10px;}
.pdf-task-no{width:22px;height:22px;border-radius:3px;background:#1E2A3A;color:white;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0;}
.pdf-task-no.approved{background:#374151;}
.pdf-task-title-block{flex:1;min-width:0;}
.pdf-task-title{font-size:12px;font-weight:700;color:#111827;line-height:1.3;font-family:'Segoe UI',Arial,sans-serif;}
.pdf-task-desc{font-size:10px;color:#6B7280;margin-top:2px;line-height:1.5;font-family:'Segoe UI',Arial,sans-serif;}
.pdf-task-badges{display:flex;gap:5px;flex-wrap:wrap;margin-left:auto;flex-shrink:0;}
.pdf-badge{display:inline-flex;align-items:center;padding:2px 8px;border-radius:3px;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;font-family:'Segoe UI',Arial,sans-serif;}
.badge-draft{background:#F3F4F6;color:#6B7280;border:1px solid #D1D5DB;}
.badge-todo{background:#EFF6FF;color:#1D4ED8;border:1px solid #BFDBFE;}
.badge-inprogress{background:#FFFBEB;color:#92400E;border:1px solid #FDE68A;}
.badge-done{background:#F0FDF4;color:#166534;border:1px solid #BBF7D0;}
.badge-review{background:#F5F3FF;color:#5B21B6;border:1px solid #DDD6FE;}
.badge-revisi{background:#FFFBEB;color:#92400E;border:1px solid #FDE68A;}
.badge-approved{background:#F0FDF4;color:#166534;border:1px solid #BBF7D0;}
.pdf-task-body{padding:10px 12px;}
.pdf-task-meta-row{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:10px;}
.pdf-meta-item .lbl{font-size:8px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9CA3AF;margin-bottom:2px;font-family:'Segoe UI',Arial,sans-serif;}
.pdf-meta-item .val{font-size:11px;font-weight:600;color:#1F2937;line-height:1.4;font-family:'Segoe UI',Arial,sans-serif;}
.pdf-hasil-section{margin-top:8px;}
.pdf-hasil-label{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#374151;margin-bottom:7px;display:flex;align-items:center;gap:5px;font-family:'Segoe UI',Arial,sans-serif;border-top:1px solid #E5E7EB;padding-top:8px;}
.pdf-hasil-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:8px;}
.pdf-hasil-img-wrap{border-radius:3px;overflow:hidden;border:1px solid #D1D5DB;aspect-ratio:16/10;background:#F9FAFB;}
.pdf-hasil-img-wrap img{width:100%;height:100%;object-fit:cover;display:block;}
.pdf-hasil-doc{display:flex;align-items:center;gap:8px;background:#F9FAFB;border:1px solid #D1D5DB;border-radius:3px;padding:9px 11px;}
.pdf-hasil-doc .name{font-size:10px;font-weight:700;color:#374151;word-break:break-all;font-family:'Segoe UI',Arial,sans-serif;}
.pdf-hasil-doc .type{font-size:9px;color:#9CA3AF;margin-top:2px;text-transform:uppercase;}
.pdf-empty-foto{background:#F9FAFB;border:1px dashed #D1D5DB;border-radius:3px;padding:10px;text-align:center;font-size:10px;color:#9CA3AF;font-style:italic;font-family:'Segoe UI',Arial,sans-serif;}
.pdf-doc-footer{background:#1E2A3A;padding:9px 28px;display:flex;justify-content:space-between;align-items:center;margin-top:auto;}
.pdf-doc-footer span{font-size:9px;color:#9CA3AF;font-family:'Segoe UI',Arial,sans-serif;}
#pdfPieChartP{display:block;}
@media print{body{background:white;padding:0;}@page{margin:10mm 8mm;size:A4;}.pdf-wrap{max-width:100%;border:none;display:flex;flex-direction:column;min-height:277mm;}.pdf-doc-footer{margin-top:auto;}.pdf-task-card{page-break-inside:avoid;}.pdf-letterhead,.pdf-doc-footer,.pdf-stats-table th{-webkit-print-color-adjust:exact;print-color-adjust:exact;}}`;

function _buildPdfForProject(p) {
    // ── EXCLUDE DRAFT dari laporan PDF ──
    const tasks   = (p.tasks || []).filter(t => t.status_progress !== 'draft');
    const timList = p.tim_list || [];
    const s       = _calcTaskStats(tasks);
    const now     = new Date();
    const nowFmt  = _fmtDateLong(now.toISOString().split('T')[0]);
    const docNum  = `DOC-${p.id_projek}-${now.getFullYear()}${String(now.getMonth()+1).padStart(2,'0')}`;
    const mulai   = _fmtDateLong(p.tanggal_mulai);
    const akhir   = _fmtDateLong(p.tanggal_selesai);
    const perus   = p.perusahaan_pt || p.perusahaan_nama || 'PT Kawan Kita Solusindo';
    // ── Letterhead
    let html = `<div class="pdf-wrap">
    <div class="pdf-letterhead">
        <div class="pdf-letterhead-left">
            <div class="doc-type">Laporan Manajemen Task</div>
            <div class="doc-title">${escHtml(p.nama_projek)}</div>
            <div class="doc-sub">${escHtml(perus)}</div>
        </div>
        <div class="pdf-letterhead-right">
            <div class="doc-num">${docNum}</div>
            <div class="doc-date">Diterbitkan: ${nowFmt}</div>
        </div>
    </div>
    <hr class="pdf-rule">`;
    // ── Info Proyek
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
    // ── Data baris progress & status akhir
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

    // Legend pie 1: hanya jumlah task + % task (tanpa weight)
    const legendPie1 = progressRows.filter(r => r.n > 0).map(r => {
        const pct = s.tot > 0 ? Math.round((r.n/s.tot)*100) : 0;
        return `<div class="pdf-legend-item"><div class="pdf-legend-dot" style="background:${PIE_COLORS_P[r.key]};"></div><span>${r.label}: <strong>${r.n}</strong> task (${pct}%)</span></div>`;
    }).join('');
    // Legend pie 2: status akhir, hanya jumlah task + %
    const legendPie2 = saRows.map(r => {
        const pct = s.tot > 0 ? Math.round((r.n/s.tot)*100) : 0;
        return `<div class="pdf-legend-item"><div class="pdf-legend-dot" style="background:${SA_COLORS[r.key]};"></div><span>${r.label}: <strong>${r.n}</strong> task (${pct}%)</span></div>`;
    }).join('');

    html += `<div class="pdf-section-header"><span>Statistik &amp; Distribusi Status (Tidak Termasuk Draft)</span></div>
    <div style="padding:16px 28px;background:white;border-bottom:1px solid #E5E7EB;">

        <!-- Baris atas: 2 tabel berdampingan -->
        <div style="display:flex;gap:16px;margin-bottom:16px;">

            <!-- Tabel 1: Status Progress — Weight -->
            <div style="flex:1;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#1E2A3A;margin-bottom:5px;font-family:'Segoe UI',Arial,sans-serif;">Status Progress (Weight)</div>
                <table class="pdf-stats-table">
                    <thead><tr>
                        <th>Status Progress</th>
                        <th style="text-align:center;">Total Weight</th>
                        <th style="text-align:center;">% Weight</th>
                    </tr></thead>
                    <tbody>
                    ${progressRows.map(r => {
                        const wpct = s.totalWeight > 0 ? Math.round((r.w/s.totalWeight)*100) : 0;
                        const dot  = PIE_COLORS_P[r.key] || '#D1D5DB';
                        return `<tr>
                            <td style="display:flex;align-items:center;gap:6px;">
                                <span style="display:inline-block;width:8px;height:8px;border-radius:2px;background:${dot};flex-shrink:0;"></span>${r.label}
                            </td>
                            <td style="text-align:center;font-weight:700;color:#1E2A3A;">${r.w}</td>
                            <td style="text-align:center;font-weight:700;color:#374151;">${s.totalWeight > 0 ? wpct+'%' : '—'}</td>
                        </tr>`;
                    }).join('')}
                    <tr class="pdf-stats-total-row"><td>Total</td><td style="text-align:center;">${s.totalWeight}</td><td style="text-align:center;">100%</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Tabel 2: Status Penilaian PM — Weight -->
            <div style="flex:1;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#1E2A3A;margin-bottom:5px;font-family:'Segoe UI',Arial,sans-serif;">Status Penilaian PM (Weight)</div>
                <table class="pdf-stats-table">
                    <thead><tr>
                        <th>Status Penilaian</th>
                        <th style="text-align:center;">Total Weight</th>
                        <th style="text-align:center;">% Weight</th>
                    </tr></thead>
                    <tbody>
                    ${saRows.map(r => {
                        const wpct = s.totalWeight > 0 ? Math.round((r.w/s.totalWeight)*100) : 0;
                        return `<tr>
                            <td><span style="display:inline-flex;align-items:center;padding:1px 7px;border-radius:3px;font-size:9px;font-weight:700;background:${r.bg};color:${r.color};border:1px solid ${r.border};">${r.label}</span></td>
                            <td style="text-align:center;font-weight:700;color:#1E2A3A;">${r.w}</td>
                            <td style="text-align:center;font-weight:700;color:#374151;">${s.totalWeight > 0 ? wpct+'%' : '—'}</td>
                        </tr>`;
                    }).join('')}
                    <tr class="pdf-stats-total-row"><td>Total</td><td style="text-align:center;">${s.totalWeight}</td><td style="text-align:center;">100%</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Baris bawah: 2 pie chart berdampingan -->
        <div style="display:flex;gap:16px;align-items:flex-start;">

            <!-- Pie 1: Status Progress -->
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;margin-bottom:6px;font-family:'Segoe UI',Arial,sans-serif;text-align:center;">Distribusi Status Progress</div>
                <canvas id="pdfPieChartP" width="130" height="130"></canvas>
                <div style="margin-top:8px;width:100%;font-family:'Segoe UI',Arial,sans-serif;">${legendPie1}</div>
            </div>

            <!-- Pie 2: Status Akhir -->
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;margin-bottom:6px;font-family:'Segoe UI',Arial,sans-serif;text-align:center;">Distribusi Status Penilaian PM</div>
                <canvas id="pdfPieChartSA" width="130" height="130"></canvas>
                <div style="margin-top:8px;width:100%;font-family:'Segoe UI',Arial,sans-serif;">${legendPie2}</div>
            </div>
        </div>

        <!-- Progress bar penyelesaian -->
        <div class="pdf-completion-block" style="margin-top:14px;">
            <div class="pdf-completion-label">Tingkat Penyelesaian Proyek (Done + Approved PM / Total Non-Draft)</div>
            <div class="pdf-completion-nums">${s.pct}% &mdash; ${s.appr} dari ${s.tot} task done &amp; disetujui PM (Weight: ${s.approvedWeight}/${s.totalWeight})</div>
            <div class="pdf-bar-bg"><div class="pdf-bar-fill" style="width:${s.pct}%;"></div></div>
        </div>
    </div>`;
    // ── Detail Task (sudah filtered, tanpa draft)
    html += `<div class="pdf-section-header"><span>Detail Task (${tasks.length} task, tidak termasuk draft)</span></div>
    <div class="pdf-tasks-wrap">`;
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
                    if (isImageFile(f.nama_file || f.url)) {
                        return `<div class="pdf-hasil-img-wrap"><img src="${escHtml(f.url)}" alt="${escHtml(f.nama_file||'Hasil')}" onerror="this.style.display='none';this.parentElement.style.display='none'"></div>`;
                    }
                    const ext = _docExt(f.nama_file || f.url);
                    return `<div class="pdf-hasil-doc"><span class="icon">${_docEmoji(f.nama_file||f.url)}</span><div><div class="name">${escHtml((f.nama_file||'Dokumen').split('/').pop())}</div><div class="type">${ext||'file'}</div></div></div>`;
                }).join('');
                hasilHtml = `<div class="pdf-hasil-section"><div class="pdf-hasil-label">Laporan Hasil (${hasilF.length} file)</div><div class="pdf-hasil-grid">${items}</div></div>`;
            } else {
                hasilHtml = `<div class="pdf-hasil-section"><div class="pdf-hasil-label">Laporan Hasil</div><div class="pdf-empty-foto">Belum ada foto/dokumen laporan hasil untuk task ini.</div></div>`;
            }
            html += `<div class="pdf-task-card">
                <div class="pdf-task-head">
                    <div class="pdf-task-no ${t.status_akhir==='approved' && t.status_progress==='done' ? 'approved':''}">${i+1}</div>
                    <div class="pdf-task-title-block">
                        <div class="pdf-task-title">${escHtml(t.judul_tugas||'—')}</div>
                        ${t.deskripsi_tugas ? `<div class="pdf-task-desc">${escHtml(t.deskripsi_tugas.substring(0,220))}${t.deskripsi_tugas.length>220?'...':''}</div>` : ''}
                    </div>
                    <div class="pdf-task-badges">
                        <span class="pdf-badge ${spClass}">${spLabel}</span>
                        ${saLabel ? `<span class="pdf-badge ${saClass}">${saLabel}</span>` : ''}
                    </div>
                </div>
                <div class="pdf-task-body">
                    <div class="pdf-task-meta-row">
                        <div class="pdf-meta-item"><div class="lbl">Penanggung Jawab</div><div class="val">${escHtml(assignee)}</div></div>
                        <div class="pdf-meta-item"><div class="lbl">Tanggal Mulai</div><div class="val">${_fmtDateLong(t.tanggal_mulai)}</div></div>
                        <div class="pdf-meta-item"><div class="lbl">Tenggat Waktu</div><div class="val">${_fmtDateLong(t.tenggat_waktu)}</div></div>
                        <div class="pdf-meta-item"><div class="lbl">Tanggal Selesai</div><div class="val" style="${t.status_progress==='done' && t.status_akhir==='approved' ?'color:#166534;font-weight:700;':'color:#9CA3AF;'}">${t.tanggal_selesai ? _fmtDateLong(t.tanggal_selesai) : '—'}</div></div>
                    </div>
                    ${hasilHtml}
                </div>
            </div>`;
        });
    }
    html += `</div>
    <div class="pdf-doc-footer">
        <span>PT Kawan Kita Solusindo</span>
        <span>Sistem Manajemen Task &mdash; ${new Date().toLocaleString('id-ID')}</span>
    </div>
    </div>`;
    return html;
}

function _drawPieDonut(canvasId, data, colorMap, total) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const filtered = data.filter(d => d.n > 0);
    if (total === 0 || !filtered.length) {
        ctx.fillStyle = '#E5E7EB';
        ctx.beginPath(); ctx.arc(65,65,60,0,Math.PI*2); ctx.fill();
        return;
    }
    let startAngle = -Math.PI/2;
    const cx=65, cy=65, r=58;
    filtered.forEach(d => {
        const slice = (d.n/total)*Math.PI*2;
        ctx.beginPath(); ctx.moveTo(cx,cy);
        ctx.arc(cx,cy,r,startAngle,startAngle+slice);
        ctx.closePath();
        ctx.fillStyle = colorMap[d.key] || '#9CA3AF';
        ctx.fill(); ctx.strokeStyle='white'; ctx.lineWidth=2; ctx.stroke();
        if (d.n/total >= 0.07) {
            const mid = startAngle+slice/2;
            ctx.fillStyle = d.key==='To Do' ? '#374151' : 'white';
            ctx.font='bold 9px Segoe UI,Arial,sans-serif';
            ctx.textAlign='center'; ctx.textBaseline='middle';
            ctx.fillText(Math.round((d.n/total)*100)+'%', cx+(r*.62)*Math.cos(mid), cy+(r*.62)*Math.sin(mid));
        }
        startAngle += slice;
    });
    ctx.beginPath(); ctx.arc(cx,cy,r*.36,0,Math.PI*2); ctx.fillStyle='white'; ctx.fill();
    ctx.fillStyle='#1E2A3A'; ctx.font='bold 15px Georgia,serif';
    ctx.textAlign='center'; ctx.textBaseline='middle'; ctx.fillText(total,cx,cy-5);
    ctx.font='8px Segoe UI,Arial,sans-serif'; ctx.fillStyle='#9CA3AF'; ctx.fillText('task',cx,cy+9);
}

function _drawPieChartProject(tasks) {
    const s = _calcTaskStats(tasks);
    // Pie 1: Status Progress
    _drawPieDonut('pdfPieChartP', [
        { key:'done',        n:s.done },
        { key:'In Progress', n:s.prog },
        { key:'To Do',       n:s.todo },
    ], PIE_COLORS_P, s.tot);
    // Pie 2: Status Akhir
    const SA_C = { approved:'#22C55E', review:'#8B5CF6', revisi:'#F59E0B', null:'#9CA3AF' };
    _drawPieDonut('pdfPieChartSA', [
        { key:'approved', n:s.saApproved },
        { key:'review',   n:s.saReview   },
        { key:'revisi',   n:s.saRevisi   },
        { key:'null',     n:s.saNull     },
    ], SA_C, s.tot);
}

let _currentExportProjekId = null;
function exportProjectPDF(id) {
    const p = projekData[id];
    if (!p) { showToast('❌ Data project tidak ditemukan.', 'error'); return; }
    _currentExportProjekId = id;
    const content = _buildPdfForProject(p);
    document.getElementById('pdfPreviewToolbarProject').querySelector('h6').textContent =
        `📄 Preview Laporan Task — ${p.nama_projek}`;
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
    // Convert both canvases to images
    const canvas1 = document.getElementById('pdfPieChartP');
    const canvas2 = document.getElementById('pdfPieChartSA');
    let content = document.getElementById('pdfPreviewContentProject').innerHTML;
    if (canvas1) {
        const img1 = canvas1.toDataURL('image/png');
        content = content.replace(
            /<canvas id="pdfPieChartP"[^>]*><\/canvas>/,
            `<img src="${img1}" width="130" height="130" style="display:block;">`
        );
    }
    if (canvas2) {
        const img2 = canvas2.toDataURL('image/png');
        content = content.replace(
            /<canvas id="pdfPieChartSA"[^>]*><\/canvas>/,
            `<img src="${img2}" width="130" height="130" style="display:block;">`
        );
    }
    const win = window.open('', '_blank', 'width=960,height=720');
    const title = p ? `Laporan Task — ${p.nama_projek}` : 'Laporan Task';
    win.document.write(`<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><title>${escHtml(title)}</title><style>${PDF_PRINT_CSS_P}</style></head><body>${content}</body></html>`);
    win.document.close();
    setTimeout(() => { win.focus(); win.print(); }, 700);
}
/* ════════════════════════════════════════════════════════════════
   EXCEL EXPORT
════════════════════════════════════════════════════════════════ */
async function exportProjectExcel(id) {
    const p = projekData[id];
    if (!p) { showToast('❌ Data project tidak ditemukan.', 'error'); return; }
    showToast('Membuat file Excel...', 'info');
    if (typeof ExcelJS === 'undefined') {
        await new Promise((res, rej) => {
            const s  = document.createElement('script');
            s.src    = 'https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js';
            s.onload = res; s.onerror = rej;
            document.head.appendChild(s);
        });
    }
    // ── EXCLUDE DRAFT dari laporan Excel ──
    const tasks   = (p.tasks || []).filter(t => t.status_progress !== 'draft');
    const timList = p.tim_list || [];
    const s       = _calcTaskStats(tasks);
    const now     = new Date();
    const perus   = p.perusahaan_pt || p.perusahaan_nama || 'PT Kawan Kita Solusindo';
    const wb  = new ExcelJS.Workbook();
    wb.creator  = 'PT Kawan Kita Solusindo';
    wb.company  = perus;
    wb.created  = now;
    wb.modified = now;
    const dateStr = now.toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' });
    /* ── Style constants ── */
    const FONT_BASE  = { name:'Times New Roman', size:12 };
    const FONT_BOLD  = { name:'Times New Roman', size:12, bold:true };
    const FONT_SM    = { name:'Times New Roman', size:11 };
    const FONT_SM_B  = { name:'Times New Roman', size:11, bold:true };
    const FONT_HDR   = { name:'Times New Roman', size:12, bold:true, color:{ argb:'FFFFFFFF' } };
    const FONT_HDR_SM= { name:'Times New Roman', size:11, bold:true, color:{ argb:'FFFFFFFF' } };
    const FONT_GREEN = { name:'Times New Roman', size:11, bold:true, color:{ argb:'FF166534' } };
    const FONT_AMBER = { name:'Times New Roman', size:11, bold:true, color:{ argb:'FF92400E' } };
    const FONT_BLUE  = { name:'Times New Roman', size:11, bold:true, color:{ argb:'FF1D4ED8' } };
    const FONT_PURP  = { name:'Times New Roman', size:11, bold:true, color:{ argb:'FF5B21B6' } };
    const FILL_NAVY   = { type:'pattern', pattern:'solid', fgColor:{ argb:'FF1E2A3A' } };
    const FILL_NAVY2  = { type:'pattern', pattern:'solid', fgColor:{ argb:'FF2D3F52' } };
    const FILL_WHITE  = { type:'pattern', pattern:'solid', fgColor:{ argb:'FFFFFFFF' } };
    const FILL_ALT    = { type:'pattern', pattern:'solid', fgColor:{ argb:'FFEEF2F7' } };
    const FILL_GREEN  = { type:'pattern', pattern:'solid', fgColor:{ argb:'FFDCFCE7' } };
    const FILL_AMBER  = { type:'pattern', pattern:'solid', fgColor:{ argb:'FFFEF3C7' } };
    const FILL_BLUE   = { type:'pattern', pattern:'solid', fgColor:{ argb:'FFDBEAFE' } };
    const FILL_PURP   = { type:'pattern', pattern:'solid', fgColor:{ argb:'FFEDE9FE' } };
    const FILL_TOTAL  = { type:'pattern', pattern:'solid', fgColor:{ argb:'FFE2E8F0' } };
    const BORDER_THIN = {
        top:{ style:'thin', color:{ argb:'FFD1D5DB' } }, left:{ style:'thin', color:{ argb:'FFD1D5DB' } },
        bottom:{ style:'thin', color:{ argb:'FFD1D5DB' } }, right:{ style:'thin', color:{ argb:'FFD1D5DB' } },
    };
    const BORDER_MED = {
        top:{ style:'medium', color:{ argb:'FF9CA3AF' } }, left:{ style:'medium', color:{ argb:'FF9CA3AF' } },
        bottom:{ style:'medium', color:{ argb:'FF9CA3AF' } }, right:{ style:'medium', color:{ argb:'FF9CA3AF' } },
    };
    const BORDER_HDR = {
        top:{ style:'medium', color:{ argb:'FF1E2A3A' } }, left:{ style:'medium', color:{ argb:'FF1E2A3A' } },
        bottom:{ style:'medium', color:{ argb:'FF1E2A3A' } }, right:{ style:'medium', color:{ argb:'FF1E2A3A' } },
    };
    const ALIGN_CC = { horizontal:'center', vertical:'middle' };
    const ALIGN_LC = { horizontal:'left',   vertical:'middle' };
    const ALIGN_RC = { horizontal:'right',  vertical:'middle' };
    const ALIGN_LT = { horizontal:'left',   vertical:'top', wrapText:true };
    function stl(cell, opts) {
        if (opts.font)      cell.font      = opts.font;
        if (opts.fill)      cell.fill      = opts.fill;
        if (opts.border)    cell.border    = opts.border;
        if (opts.alignment) cell.alignment = opts.alignment;
        if (opts.numFmt)    cell.numFmt    = opts.numFmt;
    }
    function mergeWrite(sheet, r1, c1, r2, c2, value, opts) {
        sheet.mergeCells(r1, c1, r2, c2);
        const cell = sheet.getCell(r1, c1);
        cell.value = value;
        stl(cell, opts);
    }
    /* ════ SHEET 1 — SAMPUL ════ */
    const ws1 = wb.addWorksheet('Sampul & Ringkasan', {
        properties:{ tabColor:{ argb:'FF1E2A3A' } },
        views:[{ showGridLines:false }],
    });
    ws1.columns = [
        { width:3 }, { width:26 }, { width:36 },
        { width:2 }, { width:24 }, { width:22 }, { width:3 },
    ];
    ws1.getRow(1).height = 6;
    ws1.getRow(2).height = 6;
    for (let c=1;c<=7;c++) {
        ws1.getCell(1,c).fill = FILL_NAVY;
        ws1.getCell(2,c).fill = { type:'pattern', pattern:'solid', fgColor:{ argb:'FF3B7DD8' } };
    }
    for (let r=3;r<=8;r++) for (let c=1;c<=7;c++) ws1.getCell(r,c).fill = FILL_NAVY;
    ws1.getRow(3).height = 10; ws1.getRow(4).height = 40;
    ws1.getRow(5).height = 22; ws1.getRow(6).height = 18;
    ws1.getRow(7).height = 18; ws1.getRow(8).height = 12;
    mergeWrite(ws1,4,2,4,6,'LAPORAN MANAJEMEN TASK',{ font:{ name:'Times New Roman', size:20, bold:true, color:{ argb:'FFFFFFFF' } }, fill:FILL_NAVY, alignment:ALIGN_LC });
    mergeWrite(ws1,5,2,5,6, p.nama_projek, { font:{ name:'Times New Roman', size:14, color:{ argb:'FF9CA3AF' } }, fill:FILL_NAVY, alignment:ALIGN_LC });
    mergeWrite(ws1,6,2,6,3, perus, { font:{ name:'Times New Roman', size:11, color:{ argb:'FFD1D5DB' } }, fill:FILL_NAVY, alignment:ALIGN_LC });
    const docNum = `DOC-${p.id_projek}-${now.getFullYear()}${String(now.getMonth()+1).padStart(2,'0')}`;
    ws1.getCell(6,5).value = `No. Dokumen: ${docNum}`;
    stl(ws1.getCell(6,5), { font:{ name:'Times New Roman', size:10, color:{ argb:'FF9CA3AF' }, italic:true }, fill:FILL_NAVY, alignment:ALIGN_RC });
    ws1.getCell(7,2).value = `Diterbitkan: ${dateStr}`;
    stl(ws1.getCell(7,2), { font:{ name:'Times New Roman', size:11, color:{ argb:'FFD1D5DB' } }, fill:FILL_NAVY, alignment:ALIGN_LC });
    ws1.getCell(7,5).value = `PM: ${p.pembuat_nama||'—'}`;
    stl(ws1.getCell(7,5), { font:{ name:'Times New Roman', size:10, color:{ argb:'FF9CA3AF' } }, fill:FILL_NAVY, alignment:ALIGN_RC });
    ws1.getRow(9).height = 8;
    ws1.getRow(10).height = 14;
    for (let c=1;c<=7;c++) {
        ws1.getCell(9,c).fill = { type:'pattern', pattern:'solid', fgColor:{ argb:'FF3B7DD8' } };
        ws1.getCell(10,c).fill = FILL_NAVY;
    }
    // Header info
    ws1.getRow(11).height = 22;
    mergeWrite(ws1,11,2,11,6,'▌  INFORMASI PROYEK',{
        font:{ name:'Times New Roman', size:12, bold:true, color:{ argb:'FF1E2A3A' } },
        fill:{ type:'pattern', pattern:'solid', fgColor:{ argb:'FFE2E8F0' } },
        alignment:ALIGN_LC,
        border:{ bottom:{ style:'medium', color:{ argb:'FF1E2A3A' } } },
    });
    const infoData = [
        ['Project Manager', p.pembuat_nama||'—',         'Tanggal Mulai',  _fmtDateLong(p.tanggal_mulai)||'—'],
        ['Perusahaan',      p.perusahaan_pt||'—',        'Target Selesai', _fmtDateLong(p.tanggal_selesai)||'—'],
        ['Kategori',        p.kategori_nama||'—',        'Pembuat Sistem', 'PT Kawan Kita Solusindo'],
        ['Deskripsi',       p.deskripsi||'Tidak ada deskripsi.', '', ''],
    ];
    let infoRow = 12;
    infoData.forEach((row, idx) => {
        ws1.getRow(infoRow+idx).height = idx===3 ? 32 : 20;
        const fillRow = idx%2===1 ? FILL_ALT : FILL_WHITE;
        ws1.getCell(infoRow+idx,2).value = row[0];
        stl(ws1.getCell(infoRow+idx,2), { font:FONT_SM_B, fill:fillRow, alignment:ALIGN_LC, border:BORDER_THIN });
        ws1.getCell(infoRow+idx,3).value = row[1];
        stl(ws1.getCell(infoRow+idx,3), { font:FONT_SM, fill:fillRow, alignment:ALIGN_LT, border:BORDER_THIN });
        if (row[2]) {
            ws1.getCell(infoRow+idx,5).value = row[2];
            stl(ws1.getCell(infoRow+idx,5), { font:FONT_SM_B, fill:fillRow, alignment:ALIGN_LC, border:BORDER_THIN });
            ws1.getCell(infoRow+idx,6).value = row[3];
            stl(ws1.getCell(infoRow+idx,6), { font:FONT_SM, fill:fillRow, alignment:ALIGN_LC, border:BORDER_THIN });
        } else {
            ws1.mergeCells(infoRow+idx,3,infoRow+idx,6);
            stl(ws1.getCell(infoRow+idx,3), { font:FONT_SM, fill:fillRow, alignment:{ horizontal:'left', vertical:'top', wrapText:true }, border:BORDER_THIN });
        }
    });
    ws1.getRow(17).height = 14;
    ws1.getRow(18).height = 22;
    mergeWrite(ws1,18,2,18,8,'▌  STATISTIK TASK (TIDAK TERMASUK DRAFT)',{
        font:{ name:'Times New Roman', size:12, bold:true, color:{ argb:'FF1E2A3A' } },
        fill:{ type:'pattern', pattern:'solid', fgColor:{ argb:'FFE2E8F0' } },
        alignment:ALIGN_LC,
        border:{ bottom:{ style:'medium', color:{ argb:'FF1E2A3A' } } },
    });

    // Extend columns untuk 8 kolom
    ws1.columns = [
        { width:3 }, { width:28 }, { width:16 }, { width:14 },
        { width:3 }, { width:28 }, { width:16 }, { width:14 },
    ];

    // ── Data ──
    const spItems = [
        { label:'Selesai (Done)',    n:s.done, w:s.wDone, fill:FILL_GREEN, font:FONT_GREEN },
        { label:'Proses Pengerjaan', n:s.prog, w:s.wProg, fill:FILL_AMBER, font:FONT_AMBER },
        { label:'Belum Pengerjaan',  n:s.todo, w:s.wTodo, fill:FILL_BLUE,  font:FONT_BLUE  },
    ];
    const wNull = Math.max(0, s.totalWeight - (s.wSaApproved + s.wSaRevisi + s.wSaReview));
    const FONT_GRAY = { name:'Times New Roman', size:11, bold:true, color:{ argb:'FF6B7280' } };
    const FILL_GRAY = { type:'pattern', pattern:'solid', fgColor:{ argb:'FFF3F4F6' } };
    const saItemsXL = [
        { label:'Disetujui (Approved)', n:s.saApproved, w:s.wSaApproved, fill:FILL_GREEN, font:FONT_GREEN },
        { label:'Review PM',            n:s.saReview,   w:s.wSaReview,   fill:FILL_PURP,  font:FONT_PURP  },
        { label:'Revisi PM',            n:s.saRevisi,   w:s.wSaRevisi,   fill:FILL_AMBER, font:FONT_AMBER  },
        { label:'Belum Dinilai',        n:s.saNull,     w:wNull,         fill:FILL_GRAY,  font:FONT_GRAY  },
    ];

    // Helper: tulis 1 tabel (header + rows + total) di kolom c1..c3 mulai baris startR
    // mode 'task' → kolom: Status | Jumlah Task | Persentase
    // mode 'weight' → kolom: Status | Jumlah Weight | Persentase
    function xlTable(ws, startR, c1, items, total, totalVal, mode, titleText) {
        // Title
        ws.mergeCells(startR, c1, startR, c1+2);
        const titleCell = ws.getCell(startR, c1);
        titleCell.value = titleText;
        stl(titleCell, { font:{ name:'Times New Roman', size:10, bold:true, color:{ argb:'FFFFFFFF' } },
            fill:FILL_NAVY, alignment:ALIGN_CC,
            border:{ bottom:{ style:'thin', color:{ argb:'FF3B7DD8' } } } });
        ws.getRow(startR).height = 20;

        // Header
        const colLabel = mode==='task' ? 'Jumlah Task' : 'Jumlah Weight';
        const hdrR = startR+1;
        ws.getRow(hdrR).height = 18;
        [{ v:'Status', c:c1 }, { v:colLabel, c:c1+1 }, { v:'Persentase', c:c1+2 }].forEach(h => {
            ws.getCell(hdrR, h.c).value = h.v;
            stl(ws.getCell(hdrR, h.c), { font:FONT_HDR, fill:FILL_NAVY2, alignment:ALIGN_CC, border:BORDER_HDR });
        });

        // Data rows
        items.forEach((item, i) => {
            const r = hdrR+1+i;
            ws.getRow(r).height = 20;
            const val = mode==='task' ? item.n : item.w;
            const pct = totalVal > 0 ? Math.round((val/totalVal)*100) : 0;
            ws.getCell(r, c1).value = item.label;
            stl(ws.getCell(r, c1), { font:item.font, fill:item.fill, alignment:ALIGN_LC, border:BORDER_THIN });
            ws.getCell(r, c1+1).value = val;
            stl(ws.getCell(r, c1+1), { font:{ ...item.font, size:12 }, fill:item.fill, alignment:ALIGN_CC, border:BORDER_THIN });
            ws.getCell(r, c1+2).value = pct/100;
            stl(ws.getCell(r, c1+2), { font:item.font, fill:item.fill, alignment:ALIGN_CC, border:BORDER_THIN, numFmt:'0%' });
        });

        // Total row
        const totR = hdrR+1+items.length;
        ws.getRow(totR).height = 20;
        ws.getCell(totR, c1).value = 'TOTAL';
        stl(ws.getCell(totR, c1), { font:{ name:'Times New Roman', size:11, bold:true, color:{ argb:'FF1E2A3A' } }, fill:FILL_TOTAL, alignment:ALIGN_LC, border:BORDER_MED });
        ws.getCell(totR, c1+1).value = totalVal;
        stl(ws.getCell(totR, c1+1), { font:{ name:'Times New Roman', size:12, bold:true, color:{ argb:'FF1E2A3A' } }, fill:FILL_TOTAL, alignment:ALIGN_CC, border:BORDER_MED });
        ws.getCell(totR, c1+2).value = 1;
        stl(ws.getCell(totR, c1+2), { font:{ name:'Times New Roman', size:11, bold:true, color:{ argb:'FF1E2A3A' } }, fill:FILL_TOTAL, alignment:ALIGN_CC, border:BORDER_MED, numFmt:'0%' });

        return totR; // return last row used
    }

    // ══ 4 TABEL BERDAMPINGAN (2 kiri, 2 kanan) ══
    // Tabel 1 (kiri atas): Status Progress — Jumlah Task | kolom 2-4
    // Tabel 2 (kiri bawah): Status Progress — Jumlah Weight | kolom 2-4
    // Tabel 3 (kanan atas): Status Penilaian PM — Jumlah Task | kolom 6-8
    // Tabel 4 (kanan bawah): Status Penilaian PM — Jumlah Weight | kolom 6-8

    const T1_START = 19;
    const T1_END = xlTable(ws1, T1_START, 2, spItems, s.tot, s.tot, 'task', '📊 Status Progress — Jumlah Task');
    const T2_START = T1_END + 2;
    const T2_END = xlTable(ws1, T2_START, 2, spItems, s.totalWeight, s.totalWeight, 'weight', '⚖️ Status Progress — Jumlah Weight');

    const T3_START = 19;
    const T3_END = xlTable(ws1, T3_START, 6, saItemsXL, s.tot, s.tot, 'task', '📊 Status Penilaian PM — Jumlah Task');
    const T4_START = T3_END + 2;
    const T4_END = xlTable(ws1, T4_START, 6, saItemsXL, s.totalWeight, s.totalWeight, 'weight', '⚖️ Status Penilaian PM — Jumlah Weight');

    // Progress bar row (setelah semua tabel)
    const lastDataRow = Math.max(T2_END, T4_END);
    const barRowNum = lastDataRow + 2;
    ws1.getRow(barRowNum).height = 28;
    const barFill  = '█'.repeat(Math.round(s.pct/5));
    const barEmpty = '░'.repeat(20-Math.round(s.pct/5));
    ws1.mergeCells(barRowNum,2,barRowNum,8);
    const barCell = ws1.getCell(barRowNum,2);
    barCell.value = `PENYELESAIAN PROYEK   ${barFill}${barEmpty}   ${s.pct}%  (${s.appr} dari ${s.tot} task done & approved PM · Weight: ${s.approvedWeight}/${s.totalWeight})`;
    stl(barCell, { font:{ name:'Courier New', size:10, bold:true, color:{ argb:'FF1E2A3A' } },
      fill:{ type:'pattern', pattern:'solid', fgColor:{ argb:'FFE2E8F0' } },
      alignment:ALIGN_CC,
      border:{ top:{ style:'medium', color:{ argb:'FF1E2A3A' } }, bottom:{ style:'medium', color:{ argb:'FF1E2A3A' } } } });

    // Footer accent rows
    const footR1 = barRowNum + 2;
    const footR2 = barRowNum + 3;
    ws1.getRow(footR1).height = 6;
    ws1.getRow(footR2).height = 6;
    for (let c=1;c<=8;c++) {
        ws1.getCell(footR1,c).fill = { type:'pattern', pattern:'solid', fgColor:{ argb:'FF3B7DD8' } };
        ws1.getCell(footR2,c).fill = FILL_NAVY;
    }
    /* ════ SHEET 2 — DAFTAR TASK ════ */
    const ws2 = wb.addWorksheet('Daftar Task', {
        properties:{ tabColor:{ argb:'FF3B7DD8' } },
        views:[{ showGridLines:false, state:'frozen', ySplit:5 }],
    });
    ws2.columns = [
        { width:5 }, { width:32 }, { width:36 }, { width:22 }, { width:18 },
        { width:18 }, { width:16 }, { width:15 }, { width:15 }, { width:15 },
        { width:22 }, { width:10 }, { width:52 },
    ];
    ws2.getRow(1).height = 8; ws2.getRow(2).height = 8;
    for (let c=1;c<=13;c++) {
        ws2.getCell(1,c).fill = FILL_NAVY;
        ws2.getCell(2,c).fill = { type:'pattern', pattern:'solid', fgColor:{ argb:'FF3B7DD8' } };
    }
    ws2.getRow(3).height = 32;
    mergeWrite(ws2,3,1,3,13,
        `DAFTAR TASK PROYEK  ·  ${p.nama_projek}  ·  ${tasks.length} task (tidak termasuk draft)`,
        { font:{ name:'Times New Roman', size:14, bold:true, color:{ argb:'FFFFFFFF' } }, fill:FILL_NAVY, alignment:ALIGN_LC }
    );
    ws2.getRow(4).height = 18;
    const grupData = [
        { c1:1,  c2:5,  label:'IDENTITAS TASK',   fill:{ type:'pattern', pattern:'solid', fgColor:{ argb:'FF2D3F52' } } },
        { c1:6,  c2:7,  label:'STATUS',            fill:{ type:'pattern', pattern:'solid', fgColor:{ argb:'FF374151' } } },
        { c1:8,  c2:11, label:'WAKTU & KETEPATAN', fill:{ type:'pattern', pattern:'solid', fgColor:{ argb:'FF2D3F52' } } },
        { c1:12, c2:13, label:'LAPORAN HASIL',      fill:{ type:'pattern', pattern:'solid', fgColor:{ argb:'FF374151' } } },
    ];
    grupData.forEach(({ c1, c2, label, fill }) => {
        ws2.mergeCells(4,c1,4,c2);
        const cell = ws2.getCell(4,c1);
        cell.value = label;
        stl(cell, { font:{ name:'Times New Roman', size:10, bold:true, color:{ argb:'FFADB5C0' } }, fill, alignment:ALIGN_CC });
    });
    ws2.getRow(5).height = 26;
    ['No','Judul Task','Deskripsi','Penanggung Jawab','Jabatan','Status Progress','Status PM','Tgl. Mulai','Tenggat Waktu','Tgl. Selesai','Ketepatan Waktu','Jml Hasil','URL Laporan Hasil']
        .forEach((h, i) => {
            const c = ws2.getCell(5, i+1);
            c.value = h;
            stl(c, { font:FONT_HDR, fill:FILL_NAVY, alignment:ALIGN_CC, border:BORDER_HDR });
        });
    const SP_EXCEL = {
        'done':        { fill:FILL_GREEN, font:FONT_GREEN },
        'In Progress': { fill:FILL_AMBER, font:FONT_AMBER },
        'To Do':       { fill:FILL_BLUE,  font:FONT_BLUE  },
    };
    const SA_EXCEL = {
        'review':  { fill:FILL_PURP,  font:FONT_PURP  },
        'revisi':  { fill:FILL_AMBER, font:FONT_AMBER },
        'approved':{ fill:FILL_GREEN, font:FONT_GREEN },
    };
    const TL_LABEL_XL = {
        early:'Selesai Lebih Awal', ontime:'Tepat Waktu', late:'Terlambat',
        inprogress:'Proses Pengerjaan', overdue:'Melewati Deadline',
        upcoming:'Deadline Dekat', todo:'Segera Dikerjakan',
        todo_overdue:'Lewat Deadline Belum Mulai', todo_upcoming:'Segera Dikerjakan', pending:'—',
    };
    function calcTlSimple(t) {
        const today = new Date(); today.setHours(0,0,0,0);
        const end   = t.tenggat_waktu ? new Date(t.tenggat_waktu+'T00:00:00') : null;
        const sp    = t.status_progress;
        if (sp === 'done') {
            if (!end) return 'early';
            if (t.tanggal_selesai) {
                const sel = new Date(t.tanggal_selesai+'T00:00:00');
                if (+sel < +end) return 'early'; if (+sel === +end) return 'ontime'; return 'late';
            }
            return end >= today ? 'early' : 'late';
        }
        if (sp === 'In Progress') {
            if (!end) return 'inprogress'; if (end < today) return 'overdue';
            if (Math.ceil((end-today)/86400000) <= 3) return 'upcoming'; return 'inprogress';
        }
        if (sp === 'To Do') {
            if (!end) return 'todo'; if (end < today) return 'todo_overdue';
            if (Math.ceil((end-today)/86400000) <= 3) return 'todo_upcoming'; return 'todo';
        }
        return 'pending';
    }
    tasks.forEach((t, i) => {
        const r      = 6+i;
        const isAlt  = i%2===1;
        const fillRow = isAlt ? FILL_ALT : FILL_WHITE;
        const member = timList.find(m => m.id_tim === t.id_tim);
        const tlSts  = calcTlSimple(t);
        const hasilF = (t.foto||[]).filter(f => f.tipe === 'hasil');
        const spSty  = SP_EXCEL[t.status_progress] || { fill:fillRow, font:FONT_BASE };
        const saSty  = t.status_akhir ? (SA_EXCEL[t.status_akhir] || { fill:fillRow, font:FONT_BASE }) : null;
        ws2.getRow(r).height = 22;
        ws2.getCell(r,1).value = i+1;
        stl(ws2.getCell(r,1), { font:FONT_BASE, fill:fillRow, alignment:ALIGN_CC, border:BORDER_THIN });
        ws2.getCell(r,2).value = t.judul_tugas||'—';
        stl(ws2.getCell(r,2), { font:FONT_BOLD, fill:fillRow, alignment:ALIGN_LT, border:BORDER_THIN });
        ws2.getCell(r,3).value = (t.deskripsi_tugas||'—').substring(0,300);
        stl(ws2.getCell(r,3), { font:FONT_SM, fill:fillRow, alignment:{ horizontal:'left', vertical:'top', wrapText:true }, border:BORDER_THIN });
        ws2.getCell(r,4).value = member?.nama||'—';
        stl(ws2.getCell(r,4), { font:FONT_BASE, fill:fillRow, alignment:ALIGN_LC, border:BORDER_THIN });
        ws2.getCell(r,5).value = member?.jabatan||'—';
        stl(ws2.getCell(r,5), { font:FONT_SM, fill:fillRow, alignment:ALIGN_LC, border:BORDER_THIN });
        ws2.getCell(r,6).value = SP_LABEL_PDF[t.status_progress]||t.status_progress||'—';
        stl(ws2.getCell(r,6), { font:spSty.font, fill:spSty.fill, alignment:ALIGN_CC, border:BORDER_THIN });
        ws2.getCell(r,7).value = t.status_akhir ? (SA_LABEL_PDF[t.status_akhir]||t.status_akhir) : '—';
        stl(ws2.getCell(r,7), {
            font: saSty ? saSty.font : { name:'Times New Roman', size:11, color:{ argb:'FF9CA3AF' } },
            fill: saSty ? saSty.fill : fillRow,
            alignment:ALIGN_CC, border:BORDER_THIN,
        });
       ws2.getCell(r,8).value  = _fmtDateLong(t.tanggal_mulai)   || '—';
ws2.getCell(r,9).value  = _fmtDateLong(t.tenggat_waktu)   || '—';
ws2.getCell(r,10).value = _fmtDateLong(t.tanggal_selesai) || '—';
   [8,9,10].forEach(c => stl(ws2.getCell(r,c), { font:FONT_SM, fill:fillRow, alignment:ALIGN_CC, border:BORDER_THIN }));
        const tlLabel = TL_LABEL_XL[tlSts]||'—';
        ws2.getCell(r,11).value = tlLabel;
        let tlFill=fillRow, tlFont=FONT_SM;
        if (tlSts==='early'||tlSts==='ontime')                          { tlFill=FILL_GREEN; tlFont=FONT_GREEN; }
        else if (tlSts==='late'||tlSts==='overdue'||tlSts==='todo_overdue') { tlFill={ type:'pattern', pattern:'solid', fgColor:{ argb:'FFFEE2E2' } }; tlFont={ name:'Times New Roman', size:11, bold:true, color:{ argb:'FF991B1B' } }; }
        else if (tlSts==='upcoming'||tlSts==='todo_upcoming')           { tlFill=FILL_AMBER; tlFont=FONT_AMBER; }
        stl(ws2.getCell(r,11), { font:tlFont, fill:tlFill, alignment:ALIGN_CC, border:BORDER_THIN });
        ws2.getCell(r,12).value = hasilF.length;
        stl(ws2.getCell(r,12), {
            font: hasilF.length>0 ? FONT_GREEN : { name:'Times New Roman', size:11, color:{ argb:'FF9CA3AF' } },
            fill: hasilF.length>0 ? FILL_GREEN : fillRow,
            alignment:ALIGN_CC, border:BORDER_THIN,
        });
        ws2.getCell(r,13).value = hasilF.map(f => f.url).join('\n')||'—';
        stl(ws2.getCell(r,13), { font:{ name:'Times New Roman', size:10, color:{ argb:'FF1D4ED8' } }, fill:fillRow, alignment:{ horizontal:'left', vertical:'top', wrapText:true }, border:BORDER_THIN });
    });
    // Total row
    const totRow = 6+tasks.length;
    ws2.getRow(totRow).height = 26;
    mergeWrite(ws2,totRow,1,totRow,5,
        `TOTAL: ${tasks.length} TASK  |  Selesai: ${s.done}  |  Proses: ${s.prog}  |  Belum: ${s.todo}  |  Done+Approved: ${s.appr}`,
        { font:FONT_HDR, fill:FILL_NAVY, alignment:ALIGN_LC, border:BORDER_HDR }
    );
    ws2.getCell(totRow,6).value  = `${s.done} selesai`;
    ws2.getCell(totRow,7).value  = `${s.appr} disetujui`;
    ws2.getCell(totRow,11).value = `${s.pct}% (done+approved)`;
    ws2.getCell(totRow,12).value = tasks.reduce((a,t)=>a+(t.foto||[]).filter(f=>f.tipe==='hasil').length, 0);
    [6,7,11,12].forEach(c => stl(ws2.getCell(totRow,c), { font:FONT_HDR, fill:FILL_NAVY, alignment:ALIGN_CC, border:BORDER_HDR }));
    [8,9,10,13].forEach(c => { ws2.getCell(totRow,c).fill = FILL_NAVY; });
    // Download
    const filename = `laporan-task-${p.id_projek}-${now.toISOString().split('T')[0]}.xlsx`;
    const buffer   = await wb.xlsx.writeBuffer();
    const blob     = new Blob([buffer], { type:'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    const url      = URL.createObjectURL(blob);
    const a        = document.createElement('a');
    a.href = url; a.download = filename;
    document.body.appendChild(a); a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    showToast('✅ Excel berhasil diunduh!', 'success');
}
/* ── Re-open tambah modal on validation error ── */
@if($errors->any() && !old('_method'))
document.addEventListener('DOMContentLoaded', () => {
    new bootstrap.Modal(document.getElementById('modalTambahProject')).show();
});
@endif
</script>
@endpush