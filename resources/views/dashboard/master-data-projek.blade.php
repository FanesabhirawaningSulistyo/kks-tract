@extends('layouts.master')
@section('title', 'Kelola Project')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/master-dataproject.css') }}">
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
            {{-- Per Page Selector --}}
            <div class="per-page-wrap">
                <label class="per-page-label">Tampilkan</label>
                <select class="per-page-select" onchange="changePerPage(this.value)">
                    @foreach([10, 25, 50, 100] as $n)
                    <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
                <label class="per-page-label">data</label>
            </div>
            {{-- Column Settings --}}
            <div class="col-settings-wrapper">
                <button class="btn-col-settings" onclick="toggleColSettings(event)"><i class="bx bx-columns"></i> Kolom</button>
                <div class="col-settings-dropdown" id="colSettingsDropdown">
                    <div class="col-settings-title">Tampilkan Kolom</div>
                    @foreach(['col-no'=>'No','col-info'=>'Informasi Project','col-kategori'=>'Kategori / PM','col-status'=>'Status','col-timeline'=>'Timeline','col-progress'=>'Progress','col-laporan'=>'Laporan','col-timestamps'=>'Dibuat/Diperbarui','col-aksi'=>'Aksi'] as $cId => $cLbl)
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
                    <th class="col-laporan">Laporan</th>
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
                $tw         = $projek->tugas->sum('weight');
                $aw         = $projek->tugas->where('status_akhir','approved')->sum('weight');
                $pg         = $tw > 0 ? round(($aw/$tw)*100,2) : 0;
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
                            @if($mulai)<span class="date-value mulai">{{ $mulai->format('d M Y') }}</span>@else<span class="date-na">—</span>@endif
                        </div>
                        <div class="date-row">
                            <i class="bx bx-calendar-check" style="color:{{ $isOverdue ? '#dc2626' : 'var(--p2)' }};"></i>
                            @if($selesai)<span class="date-value {{ $isOverdue ? 'overdue' : 'selesai' }}">{{ $selesai->format('d M Y') }} @if($isOverdue)<i class="bx bx-error-circle" title="Overdue" style="font-size:11px;"></i>@endif</span>
                            @else<span class="date-na">—</span>@endif
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
                        <div class="prog-label">{{ $projek->tugas->where('status_akhir','approved')->count() }} dari {{ $projek->tugas->count() }} tugas approved</div>
                    </div>
                </td>
                <td class="col-laporan">
                    <button class="report-btn" onclick="downloadLaporan({{ $projek->id_projek }})"><i class="bx bx-file-export"></i> Laporan</button>
                </td>
                <td class="col-timestamps" style="display:none;">
                    <div class="date-info">
                        <div class="date-row"><i class="bx bx-calendar-plus" style="color:var(--p1);font-size:13px;"></i><span style="font-size:11px;color:var(--ink-600);font-weight:500;">{{ $dibuatPada ? $dibuatPada->format('d M Y') : '—' }}</span></div>
                        <div class="date-row"><i class="bx bx-revision" style="color:var(--ink-400);font-size:13px;"></i><span style="font-size:11px;color:var(--ink-400);">{{ $diperbarui ? $diperbarui->format('d M Y') : '—' }}</span></div>
                    </div>
                </td>
                <td class="col-aksi">
                    <div class="action-buttons">
                        <button type="button" class="action-btn view" title="Lihat Detail" onclick="openViewModal({{ $projek->id_projek }})"><i class="bx bx-show"></i></button>
                        <button type="button" class="action-btn edit" title="Edit Project" onclick="openEditModal({{ $projek->id_projek }})"><i class="bx bx-edit-alt"></i></button>
                        {{-- ✅ Ganti onsubmit confirm() dengan modal konfirmasi custom --}}
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
        {{-- ── Custom Pagination ── --}}
        <nav class="custom-pagination">
            <ul class="custom-page-list">
                {{-- Prev --}}
                @if($projeks->onFirstPage())
                <li class="cp-item disabled">
                    <span class="cp-link"><i class="bx bx-chevron-left"></i></span>
                </li>
                @else
                <li class="cp-item">
                    <a class="cp-link" href="{{ $projeks->appends(request()->query())->previousPageUrl() }}"><i class="bx bx-chevron-left"></i></a>
                </li>
                @endif

                {{-- Page Numbers --}}
                @php
                    $current  = $projeks->currentPage();
                    $last     = $projeks->lastPage();
                    $window   = 2; // pages each side of current
                    $pages    = collect();

                    // Always show first page
                    $pages->push(1);

                    // Left side of current
                    for ($i = max(2, $current - $window); $i < $current; $i++) {
                        $pages->push($i);
                    }

                    // Current
                    if ($current > 1) $pages->push($current);

                    // Right side of current
                    for ($i = $current + 1; $i <= min($last - 1, $current + $window); $i++) {
                        $pages->push($i);
                    }

                    // Always show last page
                    if ($last > 1) $pages->push($last);

                    $pages = $pages->unique()->sort()->values();
                @endphp

                @php $prev = null; @endphp
                @foreach($pages as $page)
                    @if($prev !== null && $page - $prev > 1)
                    <li class="cp-item cp-ellipsis"><span class="cp-link">…</span></li>
                    @endif
                    <li class="cp-item {{ $page == $current ? 'active' : '' }}">
                        <a class="cp-link" href="{{ $projeks->appends(request()->query())->url($page) }}">{{ $page }}</a>
                    </li>
                    @php $prev = $page; @endphp
                @endforeach

                {{-- Next --}}
                @if($projeks->hasMorePages())
                <li class="cp-item">
                    <a class="cp-link" href="{{ $projeks->appends(request()->query())->nextPageUrl() }}"><i class="bx bx-chevron-right"></i></a>
                </li>
                @else
                <li class="cp-item disabled">
                    <span class="cp-link"><i class="bx bx-chevron-right"></i></span>
                </li>
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
                        {{-- ── Perusahaan Search Dropdown TAMBAH ── --}}
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
                        {{-- ── Kategori Search Dropdown TAMBAH ── --}}
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
                        {{-- ── Perusahaan Search Dropdown EDIT ── --}}
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
                        {{-- ── Kategori Search Dropdown EDIT ── --}}
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
                    <div class="delete-icon-wrap">
                        <i class="bx bx-trash-alt"></i>
                    </div>
                    <h5 class="delete-confirm-title">Hapus Data Project?</h5>
                    <p class="delete-confirm-desc">Anda akan menghapus project:</p>
                    <div class="delete-target-name" id="deleteProjectName">—</div>
                    <p class="delete-confirm-warn">Tindakan ini <strong>tidak dapat dibatalkan</strong> dan seluruh data terkait project ini akan ikut terhapus.</p>
                </div>
            </div>
            <div class="modal-footer-custom" style="justify-content:center;gap:12px;">
                <button type="button" class="btn-action btn-outline-custom" data-bs-dismiss="modal" style="min-width:120px;">
                    <i class="bx bx-x"></i> Batal
                </button>
                <form id="deleteForm" action="" method="POST" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-action btn-danger-custom" style="min-width:120px;">
                        <i class="bx bx-trash-alt"></i> Ya, Hapus
                    </button>
                </form>
            </div>
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

/* ── Init all dropdowns ── */
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

/* ══════════════════════════════════════════════════════════════
   STATUS + PROGRESS
══════════════════════════════════════════════════════════════ */
const STATUS_COLOR   = { aktif:'#16a34a', in_progress:'#ea580c', selesai:'#5145cd', pending:'#9CA3AF' };
const STATUS_CLASSES = ['s-pending','s-in_progress','s-aktif','s-selesai'];

/* ── Server data ── */
const projekData = {
@foreach($projeks as $projek)
@php
    $tw = $projek->tugas->sum('weight');
    $aw = $projek->tugas->where('status_akhir','approved')->sum('weight');
    $pg = $tw > 0 ? round(($aw/$tw)*100,2) : 0;
    $pmNama  = optional($projek->pembuat)->nama  ?? '—';
    $pmEmail = optional($projek->pembuat)->email ?? '';
    $pLabel  = (optional($projek->perusahaan)->nama_perwakilan ?? '—') .
               (optional($projek->perusahaan)->nama_perusahaan ? ' – '.optional($projek->perusahaan)->nama_perusahaan : '');
@endphp
    {{ $projek->id_projek }}: {
        id_projek:          {{ $projek->id_projek }},
        nama_projek:        @json($projek->nama_projek),
        id_perusahaan:      {{ $projek->id_perusahaan ?? 'null' }},
        perusahaan_label:   @json($pLabel),
        perusahaan_nama:    @json(optional($projek->perusahaan)->nama_perwakilan ?? '—'),
        perusahaan_pt:      @json(optional($projek->perusahaan)->nama_perusahaan ?? ''),
        id_kategori_projek: {{ $projek->id_kategori_projek ?? 'null' }},
        kategori_nama:      @json(optional($projek->kategoriProjek)->nama_kategori ?? '—'),
        status:             @json($projek->status),
        nominal_projek:     {{ $projek->nominal_projek }},
        sisa_tanggungan:    {{ $projek->sisa_tanggungan }},
        tanggal_mulai:      @json($projek->tanggal_mulai),
        tanggal_selesai:    @json($projek->tanggal_selesai),
        deskripsi:          @json($projek->deskripsi),
        dokumen_perjanjian: @json($projek->dokumen_perjanjian),
        pembuat_nama:       @json($pmNama),
        pembuat_email:      @json($pmEmail),
        progress:           {{ $pg }},
        approved_weight:    {{ $aw }},
        total_weight:       {{ $tw }},
        approved_count:     {{ $projek->tugas->where('status_akhir','approved')->count() }},
        total_count:        {{ $projek->tugas->count() }},
    },
@endforeach
};

/* ── Helpers ── */
function fmtDate(d) {
    if (!d) return '—';
    const dt = new Date(d);
    return dt.getDate() + ' ' + ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'][dt.getMonth()] + ' ' + dt.getFullYear();
}
function fmtRupiah(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); }

function formatRibuan(val) {
    const num = val.replace(/\D/g, '');
    return num.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
function parseRibuan(val) {
    return parseInt(val.replace(/\./g, ''), 10) || 0;
}

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

document.addEventListener('DOMContentLoaded', () => {
    const nominalInput = document.getElementById('tambah_nominal_projek');
    if (nominalInput) {
        nominalInput.addEventListener('input', function() {
            const val = parseFloat(this.value) || 0;
            document.getElementById('tambah_sisa_display').value = fmtRupiah(val);
            document.getElementById('tambah_sisa_tanggungan').value = val;
        });
        nominalInput.dispatchEvent(new Event('input'));
    }
});

/* ── Progress color update ── */
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

/* ══════════════════════════════════════════════════════════════
   HAPUS — Modal Konfirmasi Custom
══════════════════════════════════════════════════════════════ */
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
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
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
        detEl.innerHTML  = `<span style="font-weight:700;color:${color};">${p.approved_count} tugas approved</span><span style="color:var(--ink-400);"> dari ${p.total_count} tugas (weight: ${p.approved_weight}/${p.total_weight})</span>`;
    } else {
        progEl.innerHTML = `<span style="color:var(--ink-300);font-weight:600;">0% — Belum ada tugas</span>`;
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

/* ── Re-open tambah modal on validation error ── */
@if($errors->any() && !old('_method'))
document.addEventListener('DOMContentLoaded', () => {
    new bootstrap.Modal(document.getElementById('modalTambahProject')).show();
});
@endif

/* ── Export ── */
function exportData() {
    const p = new URLSearchParams({
        search: document.querySelector('[name="search"]')?.value || '',
        status: document.querySelector('[name="status"]')?.value || '',
        id_kategori_projek: document.querySelector('[name="id_kategori_projek"]')?.value || '',
        export: '1',
    });
    window.location.href = '{{ route("master-data-projek.index") }}?' + p.toString();
}

/* ── Laporan ── */
function downloadLaporan(id) {
    window.open('{{ url("master-data-projek") }}/' + id + '/laporan', '_blank');
}
</script>
@endpush