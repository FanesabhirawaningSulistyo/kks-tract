@extends('layouts.master')
@section('title', 'Master Data Pembayaran Termin')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/master-dataproject.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/master-data-pembayaran.css') }}">
@endpush
@section('content')

{{-- ══════════════════ HEADER CARD ══════════════════ --}}
<div class="project-header-card">
    <div class="project-header-top">
        <div class="project-header-content">
            <div class="project-icon"><i class="bx bx-receipt"></i></div>
            <div>
                <h4 class="project-title">Master Data Pembayaran Termin</h4>
                <p class="project-desc">Kelola dan pantau pembayaran termin dari seluruh project perusahaan</p>
            </div>
        </div>
    </div>
    <div class="project-stats-bar" style="grid-template-columns: repeat(4, 1fr);">
        <div class="stat-item">
            <div class="stat-icon-circle total"><i class="bx bx-task"></i></div>
            <div>
                <div class="stat-label">Total Project</div>
                <div class="stat-value">{{ $stats['total_projek'] }}</div>
                <div class="stat-sub">Semua project</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-circle progress"><i class="bx bx-time-five"></i></div>
            <div>
                <div class="stat-label">Belum Lunas</div>
                <div class="stat-value">{{ $stats['belum_lunas'] }}</div>
                <div class="stat-sub">Masih ada tanggungan</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-circle done"><i class="bx bx-check-double"></i></div>
            <div>
                <div class="stat-label">Lunas</div>
                <div class="stat-value">{{ $stats['lunas'] }}</div>
                <div class="stat-sub">Terbayar penuh</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-circle aktif"><i class="bx bx-money"></i></div>
            <div>
                <div class="stat-label">Total Sisa Tagihan</div>
                <div class="stat-value" style="font-size:15px;">Rp {{ number_format($stats['total_sisa'], 0, ',', '.') }}</div>
                <div class="stat-sub">Dari project belum lunas</div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════ FILTER ══════════════════ --}}
<form method="GET" action="{{ route('pembayaran-projek.index') }}" id="filterFormBayar">
    <div class="filter-section">
        <div class="filter-row" style="grid-template-columns: 2fr 1fr 1fr auto auto auto;">
            <div class="filter-group">
                <label class="filter-label">Pencarian</label>
                <input type="text" name="search" class="filter-input" placeholder="Cari nama project atau perusahaan..." value="{{ request('search') }}">
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
                <label class="filter-label">Status Project</label>
                <select name="status" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="pending"     {{ request('status')=='pending'     ? 'selected':'' }}>Pending</option>
                    <option value="in_progress" {{ request('status')=='in_progress' ? 'selected':'' }}>In Progress</option>
                    <option value="aktif"       {{ request('status')=='aktif'       ? 'selected':'' }}>Aktif</option>
                    <option value="selesai"     {{ request('status')=='selesai'     ? 'selected':'' }}>Selesai</option>
                </select>
            </div>
            <input type="hidden" name="per_page"     value="{{ request('per_page', 10) }}">
            <input type="hidden" name="filter_lunas" id="filterLunasInput" value="{{ $filterStatus }}">
            <div class="filter-group">
                <label class="filter-label">&nbsp;</label>
                <button type="submit" class="btn-filter"><i class="bx bx-search"></i> Filter</button>
            </div>
            <div class="filter-group">
                <label class="filter-label">&nbsp;</label>
                <a href="{{ route('pembayaran-projek.index') }}" class="btn-filter reset"><i class="bx bx-refresh"></i> Reset</a>
            </div>
        </div>
    </div>
</form>

{{-- ══════════════════ TABLE ══════════════════ --}}
<div class="table-container">
    <div class="table-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        {{-- KIRI --}}
        <div style="display:flex;flex-direction:column;gap:6px;">
            <h3 class="table-title" style="margin:0;">Daftar Project — Pembayaran Termin</h3>
            <span class="table-info">
                Menampilkan <strong>{{ $projeks->firstItem() ?? 0 }}–{{ $projeks->lastItem() ?? 0 }}</strong>
                dari <strong>{{ $projeks->total() }}</strong> project
            </span>
            <div class="per-page-wrap" style="margin-top:2px;">
                <label class="per-page-label">Tampilkan</label>
                <select class="per-page-select" onchange="changePerPageBayar(this.value)">
                    @foreach([10, 25, 50, 100] as $n)
                    <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
                <label class="per-page-label">data</label>
            </div>
        </div>
        {{-- KANAN --}}
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;justify-content:flex-end;">
            <div class="lunas-toggle-group">
                <button type="button"
                    class="lunas-toggle-btn {{ $filterStatus === 'belum_lunas' ? 'active' : '' }}"
                    onclick="switchFilter('belum_lunas')">
                    <i class="bx bx-time-five"></i> Belum Lunas
                    <span class="toggle-count">{{ $stats['belum_lunas'] }}</span>
                </button>
                <button type="button"
                    class="lunas-toggle-btn lunas {{ $filterStatus === 'lunas' ? 'active' : '' }}"
                    onclick="switchFilter('lunas')">
                    <i class="bx bx-check-circle"></i> Lunas
                    <span class="toggle-count">{{ $stats['lunas'] }}</span>
                </button>
            </div>
            <button type="button" class="btn-export-laporan btn-laporan-pendapatan"
                    onclick="openExportPendapatan()">
                <i class="bx bx-bar-chart-alt-2"></i>
                <span>Laporan Pendapatan</span>
            </button>
            <button type="button" class="btn-export-laporan" onclick="openExportLaporan()">
                <i class="bx bx-export"></i>
                <span>Export Laporan</span>
            </button>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="project-table" id="bayarTable">
            <thead>
                <tr>
                    <th style="width:46px;text-align:center;">No</th>
                    <th style="min-width:260px;">Nama Project</th>
                    <th style="min-width:140px;">Kategori</th>
                    <th style="min-width:160px;">Status Project</th>
                    <th style="min-width:190px;">Nominal Kontrak</th>
                    <th style="min-width:190px;">Terbayar</th>
                    <th style="text-align:right;min-width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($projeks as $index => $projek)
            @php
                $pg         = collect($projeksData)->firstWhere('id_projek', $projek->id_projek);
                $progress   = $pg ? $pg['progress'] : 0;
                $isLunas    = (float)$projek->sisa_tanggungan <= 0;
                $nominal    = (float)$projek->nominal_projek;
                $sisa       = (float)$projek->sisa_tanggungan;
                $terbayar   = max(0, $nominal - $sisa);
                $pctBayar   = $nominal > 0 ? round(($terbayar / $nominal) * 100, 1) : 0;
                $statusCls  = match($projek->status) {
                    'aktif'       => 's-aktif',
                    'in_progress' => 's-in_progress',
                    'selesai'     => 's-selesai',
                    default       => 's-pending',
                };
                $statusLabel = match($projek->status) {
                    'aktif'       => 'Aktif',
                    'in_progress' => 'In Progress',
                    'selesai'     => 'Selesai',
                    default       => 'Pending',
                };
                $pgColor = match($projek->status) {
                    'aktif'       => '#16a34a',
                    'in_progress' => '#ea580c',
                    'selesai'     => '#5145cd',
                    default       => '#9CA3AF',
                };
                $pctColor = $pctBayar >= 100 ? '#16a34a' : ($pctBayar >= 50 ? '#2563eb' : ($pctBayar >= 25 ? '#ea580c' : '#dc2626'));
            @endphp
            <tr>
                <td style="text-align:center;">
                    <span class="row-no">{{ $projeks->firstItem() + $index }}</span>
                </td>
                <td>
                    <div class="project-info">
                        <div class="project-icon-box"><i class="bx bx-folder"></i></div>
                        <div style="min-width:0;">
                            <div class="project-name">{{ $projek->nama_projek }}</div>
                            @if(optional($projek->perusahaan)->nama_perusahaan || optional($projek->perusahaan)->nama_perwakilan)
                            <div class="project-company">
                                <i class="bx bx-buildings"></i>
                                {{ optional($projek->perusahaan)->nama_perwakilan ?? '' }}
                                @if(optional($projek->perusahaan)->nama_perusahaan)
                                    — {{ $projek->perusahaan->nama_perusahaan }}
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    @if($projek->kategoriProjek)
                    <span class="category-badge">
                        <i class="bx bx-purchase-tag-alt"></i>
                        {{ $projek->kategoriProjek->nama_kategori }}
                    </span>
                    @else
                    <span style="color:var(--ink-300);font-size:13px;">—</span>
                    @endif
                </td>
                <td>
                    <div>
                        <span class="status-badge {{ $statusCls }}" style="margin-bottom:8px;display:inline-flex;">
                            <span class="dot"></span>{{ $statusLabel }}
                        </span>
                        <div class="prog-wrap" style="min-width:120px;margin-top:6px;">
                            <div class="prog-header">
                                <span class="prog-pct" style="color:{{ $pgColor }};">{{ $progress }}%</span>
                            </div>
                            <div class="prog-track">
                                <div class="prog-fill" style="width:{{ $progress }}%;background:{{ $pgColor }};"></div>
                            </div>
                            <div class="prog-label">selesai</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="dual-nominal-cell">
                        <div class="dual-nominal-top">
                            <span class="dual-label">Nominal Awal</span>
                            <span class="dual-value nominal-awal">Rp {{ number_format($nominal, 0, ',', '.') }}</span>
                        </div>
                        <div class="dual-divider"></div>
                        <div class="dual-nominal-bottom">
                            <span class="dual-label">Sisa Tanggungan</span>
                            @if($isLunas)
                            <span class="dual-value lunas-badge-inline">
                                <i class="bx bx-check-circle"></i> LUNAS
                            </span>
                            @else
                            <span class="dual-value sisa-merah">Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    <div class="dual-nominal-cell">
                        <div class="dual-nominal-top">
                            <span class="dual-label">Total Terbayar</span>
                            <span class="dual-value terbayar-hijau">Rp {{ number_format($terbayar, 0, ',', '.') }}</span>
                        </div>
                        <div class="dual-divider"></div>
                        <div class="dual-nominal-bottom">
                            <span class="dual-label">Persentase Bayar</span>
                            <div class="pct-bar-wrap">
                                <div class="pct-bar-track">
                                    <div class="pct-bar-fill" style="width:{{ min(100,$pctBayar) }}%;background:{{ $pctColor }};"></div>
                                </div>
                                <span class="pct-text" style="color:{{ $pctColor }};">{{ $pctBayar }}%</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td style="text-align:right;">
                    <button type="button"
                        class="btn-bayar-main"
                        onclick="openBayarModal({{ $projek->id_projek }})"
                        title="{{ $isLunas ? 'Lihat Riwayat' : 'Bayar / Riwayat' }}">
                        <i class="bx {{ $isLunas ? 'bx-history' : 'bx-credit-card' }}"></i>
                        {{ $isLunas ? 'Riwayat' : 'Bayar' }}
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <i class="bx bx-receipt"></i>
                        <h5>Tidak ada project ditemukan</h5>
                        <p>Coba ubah filter atau periksa kembali data project.</p>
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
            Menampilkan <strong>{{ $projeks->firstItem() }}–{{ $projeks->lastItem() }}</strong>
            dari <strong>{{ $projeks->total() }}</strong> project
        </div>
        <nav class="custom-pagination">
            <ul class="custom-page-list">
                @if($projeks->onFirstPage())
                <li class="cp-item disabled"><span class="cp-link"><i class="bx bx-chevron-left"></i></span></li>
                @else
                <li class="cp-item"><a class="cp-link" href="{{ $projeks->appends(request()->query())->previousPageUrl() }}"><i class="bx bx-chevron-left"></i></a></li>
                @endif
                @php
                    $current = $projeks->currentPage(); $last = $projeks->lastPage();
                    $pages   = collect([1]);
                    for ($i = max(2, $current-2); $i < $current; $i++) $pages->push($i);
                    if ($current > 1) $pages->push($current);
                    for ($i = $current+1; $i <= min($last-1, $current+2); $i++) $pages->push($i);
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

{{-- ══════════════════ MODAL BAYAR ══════════════════ --}}
<div id="modalBayarOverlay" class="bayar-overlay" onclick="closeBayarModal()"></div>
<div id="modalBayar" class="bayar-modal">
    <div class="bayar-modal-inner">
        <div class="bayar-modal-header">
            <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;">
                <div class="bayar-header-icon"><i class="bx bx-receipt"></i></div>
                <div style="min-width:0;">
                    <div class="bayar-modal-title" id="bm_nama_projek">—</div>
                    <div class="bayar-modal-sub" id="bm_perusahaan">—</div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                <button type="button" class="btn-cetak-riwayat" id="btnCetakRiwayat" onclick="cetakRiwayatPDF()">
                    <i class="bx bx-printer"></i> Cetak Riwayat
                </button>
                <button type="button" class="bayar-close-btn" onclick="closeBayarModal()"><i class="bx bx-x"></i></button>
            </div>
        </div>
        <div class="bayar-info-grid">
            <div class="bayar-info-item">
                <div class="bayar-info-label">Kategori Project</div>
                <div class="bayar-info-val" id="bm_kategori">—</div>
            </div>
            <div class="bayar-info-item">
                <div class="bayar-info-label">Nominal Kontrak</div>
                <div class="bayar-info-val fw-bold" id="bm_nominal">—</div>
            </div>
            <div class="bayar-info-item">
                <div class="bayar-info-label">Sisa Tanggungan</div>
                <div class="bayar-info-val sisa-val" id="bm_sisa">—</div>
            </div>
            <div class="bayar-info-item">
                <div class="bayar-info-label">Status Project</div>
                <div class="bayar-info-val" id="bm_status">—</div>
            </div>
            <div class="bayar-info-item" style="grid-column: span 2;">
                <div class="bayar-info-label">Progress Selesai</div>
                <div style="display:flex;align-items:center;gap:10px;margin-top:4px;">
                    <div class="prog-track" style="flex:1;height:8px;">
                        <div class="prog-fill" id="bm_prog_fill" style="width:0%;background:#5145cd;"></div>
                    </div>
                    <span class="bayar-prog-pct" id="bm_prog_pct">0%</span>
                </div>
            </div>
        </div>
        <div class="bayar-modal-body">
            <div class="bayar-form-section" id="bayarFormSection">
                <div class="bayar-section-title">
                    <i class="bx bx-plus-circle"></i> Input Nominal Pembayaran
                </div>
                <div class="bayar-form-row">
                    <div class="bayar-form-group" style="flex:2;">
                        <label class="bayar-form-label">Nominal Pembayaran <span style="color:#dc2626">*</span></label>
                        <div style="position:relative;">
                            <span class="bayar-rp-prefix">Rp</span>
                            <input type="text" id="input_nominal_bayar" class="bayar-form-input" placeholder="0" style="padding-left:38px;" autocomplete="off">
                        </div>
                        <div class="bayar-form-hint" id="hint_sisa">Maks: Rp 0</div>
                    </div>
                    <div class="bayar-form-group" style="flex:1.5;">
                        <label class="bayar-form-label">Tanggal Bayar <span style="color:#dc2626">*</span></label>
                        <input type="date" id="input_tanggal_bayar" class="bayar-form-input" value="{{ now()->format('Y-m-d') }}">
                    </div>
                    <div class="bayar-form-group" style="flex:1.5;">
                        <label class="bayar-form-label">Metode Pembayaran <span style="color:#dc2626">*</span></label>
                        <select id="input_metode" class="bayar-form-input">
                            <option value="">— Pilih Metode —</option>
                            @foreach($metodes as $m)
                            <option value="{{ $m->id_metode_pembayaran }}">{{ $m->nama_metode }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="bayar-form-row">
                    <div class="bayar-form-group" style="flex:1;">
                        <label class="bayar-form-label">Catatan (opsional)</label>
                        <input type="text" id="input_catatan" class="bayar-form-input" placeholder="Catatan tambahan...">
                    </div>
                    <div class="bayar-form-group" style="flex:none;align-self:flex-end;">
                        <button type="button" class="btn-simpan-bayar" id="btnSimpanBayar" onclick="simpanPembayaran()">
                            <i class="bx bx-save"></i> Simpan Pembayaran
                        </button>
                    </div>
                </div>
            </div>
            <div class="bayar-riwayat-section">
                <div class="bayar-section-title">
                    <i class="bx bx-history"></i> Riwayat Pembayaran
                    <span class="riwayat-count-badge" id="riwayatCountBadge">0</span>
                </div>
                <div id="riwayatLoading" style="text-align:center;padding:32px 0;display:none;">
                    <div class="bayar-spinner"></div>
                    <div style="font-size:13px;color:var(--ink-400);margin-top:10px;">Memuat riwayat...</div>
                </div>
                <div id="riwayatEmpty" style="text-align:center;padding:32px 0;display:none;">
                    <i class="bx bx-receipt" style="font-size:40px;color:var(--ink-300);display:block;margin-bottom:8px;"></i>
                    <div style="font-size:13px;color:var(--ink-400);">Belum ada riwayat pembayaran</div>
                </div>
                <div style="overflow-x:auto;" id="riwayatTableWrap">
                    <table class="riwayat-table" id="riwayatTable">
                        <thead>
                            <tr>
                                <th style="width:40px;">No</th>
                                <th style="min-width:130px;">Nominal Bayar</th>
                                <th style="min-width:130px;">Sisa Pembayaran</th>
                                <th style="min-width:110px;">Tanggal Bayar</th>
                                <th style="min-width:130px;">Nama Petugas</th>
                                <th style="min-width:80px;">Status</th>
                                <th style="min-width:100px;text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="riwayatTbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════ MODAL EXPORT LAPORAN ══════════════════ --}}
<div id="exportLaporanOverlay" style="position:fixed;inset:0;z-index:9000;background:rgba(0,0,0,.5);backdrop-filter:blur(3px);display:none;align-items:center;justify-content:center;" onclick="closeExportLaporan()">
    <div onclick="event.stopPropagation()" style="position:relative;z-index:1;background:white;border-radius:14px;width:min(98vw,520px);box-shadow:0 24px 64px rgba(0,0,0,.3);overflow:hidden;">
        <div style="background:linear-gradient(135deg,#1E2A3A 0%,#2D3F52 100%);padding:20px 24px;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;border-radius:10px;background:rgba(105,108,255,.25);display:flex;align-items:center;justify-content:center;color:white;font-size:20px;border:1px solid rgba(105,108,255,.3);">
                    <i class="bx bx-export"></i>
                </div>
                <div>
                    <div style="font-size:15px;font-weight:700;color:white;">Export Laporan Pembayaran</div>
                    <div style="font-size:11px;color:#9CA3AF;margin-top:2px;">Pilih opsi laporan yang ingin diekspor</div>
                </div>
            </div>
            <button onclick="closeExportLaporan()" style="width:32px;height:32px;border-radius:50%;border:1.5px solid rgba(255,255,255,.3);background:rgba(255,255,255,.12);color:white;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;"><i class="bx bx-x"></i></button>
        </div>
        <div style="padding:20px 24px;">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#6B7280;margin-bottom:12px;">Sumber Data</div>
            <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:20px;">
                <label class="export-radio-item">
                    <input type="radio" name="export_scope" value="semua" checked>
                    <div class="export-radio-content">
                        <div class="export-radio-title"><i class="bx bx-data"></i> Semua Project</div>
                        <div class="export-radio-desc">Laporan mencakup seluruh project (lunas & belum lunas)</div>
                    </div>
                </label>
                <label class="export-radio-item">
                    <input type="radio" name="export_scope" value="belum_lunas">
                    <div class="export-radio-content">
                        <div class="export-radio-title"><i class="bx bx-time-five"></i> Hanya Belum Lunas</div>
                        <div class="export-radio-desc">Project yang masih memiliki sisa tanggungan</div>
                    </div>
                </label>
                <label class="export-radio-item">
                    <input type="radio" name="export_scope" value="lunas">
                    <div class="export-radio-content">
                        <div class="export-radio-title"><i class="bx bx-check-circle"></i> Hanya Lunas</div>
                        <div class="export-radio-desc">Project yang sudah terbayar penuh</div>
                    </div>
                </label>
            </div>
            <button onclick="generateExportPDF()" class="btn-generate-export">
                <i class="bx bx-file-pdf"></i> Generate &amp; Print Laporan PDF
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════ MODAL EXPORT LAPORAN PENDAPATAN ══════════════════ --}}
<div id="exportPendapatanOverlay"
     style="position:fixed;inset:0;z-index:9100;background:rgba(0,0,0,.5);backdrop-filter:blur(3px);display:none;align-items:center;justify-content:center;"
     onclick="closeExportPendapatan()">
    <div onclick="event.stopPropagation()"
         style="position:relative;z-index:1;background:white;border-radius:14px;width:min(98vw,560px);box-shadow:0 24px 64px rgba(0,0,0,.3);overflow:hidden;">
        <div style="background:linear-gradient(135deg,#1E2A3A 0%,#2D3F52 100%);padding:20px 24px;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;border-radius:10px;background:rgba(105,108,255,.25);display:flex;align-items:center;justify-content:center;color:white;font-size:20px;border:1px solid rgba(105,108,255,.3);">
                    <i class="bx bx-bar-chart-alt-2"></i>
                </div>
                <div>
                    <div style="font-size:15px;font-weight:700;color:white;">Laporan Pendapatan</div>
                    <div style="font-size:11px;color:#9CA3AF;margin-top:2px;">Pilih periode laporan pendapatan</div>
                </div>
            </div>
            <button onclick="closeExportPendapatan()" style="width:32px;height:32px;border-radius:50%;border:1.5px solid rgba(255,255,255,.3);background:rgba(255,255,255,.12);color:white;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;"><i class="bx bx-x"></i></button>
        </div>
        <div style="padding:20px 24px;">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#6B7280;margin-bottom:10px;">Jenis Laporan</div>
            <div style="display:flex;gap:8px;margin-bottom:20px;">
                <label style="flex:1;display:flex;align-items:center;gap:8px;padding:10px 14px;border-radius:8px;border:1.5px solid #E5E7EB;cursor:pointer;transition:all .15s;" id="lbl_bulanan">
                    <input type="radio" name="pendapatan_type" value="bulanan" checked onchange="togglePendapatanType('bulanan')" style="accent-color:#696cff;">
                    <div>
                        <div style="font-size:13px;font-weight:700;color:#1F2937;"><i class="bx bx-calendar-range"></i> Per Rentang Bulan</div>
                        <div style="font-size:11px;color:#9CA3AF;">Pilih dari bulan–tahun ke bulan–tahun</div>
                    </div>
                </label>
                <label style="flex:1;display:flex;align-items:center;gap:8px;padding:10px 14px;border-radius:8px;border:1.5px solid #E5E7EB;cursor:pointer;transition:all .15s;" id="lbl_tahunan">
                    <input type="radio" name="pendapatan_type" value="tahunan" onchange="togglePendapatanType('tahunan')" style="accent-color:#696cff;">
                    <div>
                        <div style="font-size:13px;font-weight:700;color:#1F2937;"><i class="bx bx-calendar"></i> Per Tahun</div>
                        <div style="font-size:11px;color:#9CA3AF;">Pilih satu atau beberapa tahun</div>
                    </div>
                </label>
            </div>
            <div id="panel_bulanan">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#6B7280;margin-bottom:10px;">Rentang Periode</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:8px;">
                    <div>
                        <div style="font-size:11px;font-weight:600;color:#374151;margin-bottom:5px;">Dari</div>
                        <div style="display:flex;gap:6px;">
                            <select id="pend_dari_bulan" style="flex:1;padding:8px 10px;border:1px solid #E5E7EB;border-radius:6px;font-size:13px;background:white;" onchange="updatePendapatanHint()">
                                <option value="1">Jan</option><option value="2">Feb</option><option value="3">Mar</option>
                                <option value="4">Apr</option><option value="5">Mei</option><option value="6">Jun</option>
                                <option value="7">Jul</option><option value="8">Agu</option><option value="9">Sep</option>
                                <option value="10">Okt</option><option value="11">Nov</option><option value="12">Des</option>
                            </select>
                            <select id="pend_dari_tahun" style="flex:1;padding:8px 10px;border:1px solid #E5E7EB;border-radius:6px;font-size:13px;background:white;" onchange="updatePendapatanHint()"></select>
                        </div>
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:600;color:#374151;margin-bottom:5px;">Sampai</div>
                        <div style="display:flex;gap:6px;">
                            <select id="pend_sampai_bulan" style="flex:1;padding:8px 10px;border:1px solid #E5E7EB;border-radius:6px;font-size:13px;background:white;" onchange="updatePendapatanHint()">
                                <option value="1">Jan</option><option value="2">Feb</option><option value="3">Mar</option>
                                <option value="4">Apr</option><option value="5">Mei</option><option value="6">Jun</option>
                                <option value="7">Jul</option><option value="8">Agu</option><option value="9">Sep</option>
                                <option value="10">Okt</option><option value="11">Nov</option><option value="12">Des</option>
                            </select>
                            <select id="pend_sampai_tahun" style="flex:1;padding:8px 10px;border:1px solid #E5E7EB;border-radius:6px;font-size:13px;background:white;" onchange="updatePendapatanHint()"></select>
                        </div>
                    </div>
                </div>
                <div id="pend_hint" style="font-size:11px;color:#6B7280;margin-bottom:12px;padding:6px 10px;background:#F9FAFB;border-radius:5px;border:1px dashed #E5E7EB;"></div>
            </div>
            <div id="panel_tahunan" style="display:none;">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#6B7280;margin-bottom:10px;">Pilih Tahun</div>
                <div id="pend_tahun_checkboxes" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px;"></div>
                <div style="font-size:11px;color:#9CA3AF;">Klik tahun untuk memilih/membatalkan. Bisa pilih lebih dari satu.</div>
            </div>
            <button onclick="generateLaporanPendapatan()"
                style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:11px 20px;border-radius:8px;border:none;background:linear-gradient(135deg,#696cff 0%,#5145cd 100%);color:white;font-size:14px;font-weight:700;cursor:pointer;margin-top:16px;box-shadow:0 2px 8px rgba(105,108,255,.3);">
                <i class="bx bx-file-pdf"></i> Generate &amp; Print Laporan Pendapatan
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════ TOAST ══════════════════ --}}
<div id="__toast_bayar"><i id="__toast_bayar_icon" class="bx"></i><span id="__toast_bayar_msg"></span></div>

{{-- Data PHP untuk JS --}}
<script>
    const PHP_PROJEKS_DATA       = @json($projeksData);
    const PHP_ALL_PROJEKS_DATA   = @json($allProjeksData ?? []);
    const PHP_STATS              = @json($stats);
    const PHP_TODAY              = '{{ now()->format("d/m/Y H:i") }}';
    const PHP_PAYMENT_MONTHLY    = @json($monthlyPayments ?? []);
    const PHP_DETAILED_PAYMENTS  = @json($detailedPayments ?? []);
    const PHP_LOGO_URL           = '{{ $logoUrl }}';
</script>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
'use strict';
const BAYAR_METODES  = @json($metodes);
const BAYAR_CSRF     = '{{ csrf_token() }}';
const BAYAR_BASE_URL = '{{ url("pembayaran-projek") }}';
let _currentProjekId   = null;
let _currentProjekData = null;
let _riwayatData       = [];

/* ─── FORMAT HELPERS ─── */
function fRp(n)  { return 'Rp\u00A0' + Number(n || 0).toLocaleString('id-ID'); }
function fDate(d) {
    if (!d) return '—';
    const s = String(d).trim().split('T')[0];
    const parts = s.split('-');
    if (parts.length !== 3) return '—';
    const mn = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    return `${parseInt(parts[2])} ${mn[parseInt(parts[1])-1]} ${parts[0]}`;
}
function fDateShort(d) {
    if (!d) return '—';
    const s = String(d).trim().split('T')[0];
    const parts = s.split('-');
    if (parts.length !== 3) return '—';
    const mn = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    return `${parseInt(parts[2])} ${mn[parseInt(parts[1])-1]} ${parts[0]}`;
}
function fRibuan(v)     { return (v+'').replace(/\D/g,'').replace(/\B(?=(\d{3})+(?!\d))/g,'.'); }
function parseRibuan(v) { return parseInt((v+'').replace(/\./g,''),10)||0; }

/* ─── TOAST ─── */
function showToastBayar(msg, type) {
    const t  = document.getElementById('__toast_bayar');
    const ic = document.getElementById('__toast_bayar_icon');
    const ms = document.getElementById('__toast_bayar_msg');
    ms.textContent = msg;
    t.className = '';
    const cfg = { success:['bx-check-circle','#10b981','#059669'], error:['bx-error-circle','#ef4444','#dc2626'], info:['bx-info-circle','#3b82f6','#2563eb'] };
    const [iconCls, c1, c2] = cfg[type] || cfg.info;
    ic.className = 'bx ' + iconCls;
    t.style.background = `linear-gradient(135deg,${c1} 0%,${c2} 100%)`;
    t.classList.add('show');
    clearTimeout(t.__tmr);
    t.__tmr = setTimeout(() => t.className = '', 3000);
}

/* ─── FILTER & NAVIGATION ─── */
function switchFilter(val) {
    document.getElementById('filterLunasInput').value = val;
    document.getElementById('filterFormBayar').submit();
}
function changePerPageBayar(val) {
    document.querySelector('[name="per_page"]').value = val;
    document.getElementById('filterFormBayar').submit();
}
function openBayarModal(id) {
    window.location.href = '/pembayaran-projek/' + id + '/detail';
}
function closeBayarModal() {
    document.getElementById('modalBayarOverlay').classList.remove('show');
    document.getElementById('modalBayar').classList.remove('show');
    document.body.style.overflow = '';
    _currentProjekId = null; _currentProjekData = null;
}
function resetBayarForm() {
    document.getElementById('input_nominal_bayar').value = '';
    document.getElementById('input_tanggal_bayar').value = new Date().toISOString().split('T')[0];
    document.getElementById('input_metode').value = '';
    document.getElementById('input_catatan').value = '';
}

/* ─── RENDER INFO PROJEK ─── */
function renderBayarInfo(p) {
    document.getElementById('bm_nama_projek').textContent = p.nama_projek || '—';
    document.getElementById('bm_perusahaan').textContent  = (p.perusahaan_nama || '') + (p.perusahaan_pt ? ' — ' + p.perusahaan_pt : '');
    document.getElementById('bm_kategori').textContent    = p.kategori_nama || '—';
    document.getElementById('bm_nominal').textContent     = fRp(p.nominal_projek);
    document.getElementById('bm_prog_fill').style.width   = (p.progress || 0) + '%';
    document.getElementById('bm_prog_pct').textContent    = (p.progress || 0) + '%';
    const sisa = parseFloat(p.sisa_tanggungan) || 0;
    const sisaEl = document.getElementById('bm_sisa');
    if (sisa <= 0) {
        sisaEl.innerHTML = '<span style="color:#16a34a;font-weight:700;"><i class="bx bx-check-circle"></i> LUNAS</span>';
    } else {
        sisaEl.textContent = fRp(sisa); sisaEl.style.color = '#dc2626';
    }
    const STATUS_MAP = { aktif:'Aktif', in_progress:'In Progress', selesai:'Selesai', pending:'Pending' };
    const STATUS_CLS = { aktif:'status-aktif', in_progress:'status-in_progress', selesai:'status-selesai', pending:'status-pending' };
    document.getElementById('bm_status').innerHTML =
        `<span class="status-badge ${STATUS_CLS[p.status]||'status-pending'}"><span class="dot"></span>${STATUS_MAP[p.status]||p.status}</span>`;
}

/* ─── RENDER RIWAYAT TABLE ─── */
function renderRiwayat(riwayat) {
    const tbody = document.getElementById('riwayatTbody');
    const emptyEl = document.getElementById('riwayatEmpty');
    const tableWrap = document.getElementById('riwayatTableWrap');
    const badge = document.getElementById('riwayatCountBadge');
    badge.textContent = riwayat.length;
    if (!riwayat.length) {
        emptyEl.style.display = 'block'; tableWrap.style.display = 'none'; tbody.innerHTML = ''; return;
    }
    emptyEl.style.display = 'none'; tableWrap.style.display = '';
    tbody.innerHTML = riwayat.map((item) => {
        const isBatal = item.status === 'batal';
        return `<tr class="${isBatal ? 'rw-batal-row' : ''}">
            <td style="text-align:center;color:var(--ink-500);font-size:12px;">${riwayat.length - riwayat.indexOf(item)}</td>
            <td>
                <div style="font-weight:700;color:${isBatal?'var(--ink-400)':'var(--ink-900)'};font-size:13px;${isBatal?'text-decoration:line-through;':''}">
                    ${fRp(item.jumlah_bayar)}</div>
                <div style="font-size:10px;color:var(--ink-400);">${item.kode_pembayaran}</div>
            </td>
            <td>
                <div style="font-size:13px;font-weight:600;color:${isBatal?'var(--ink-400)':(item.sisa_setelah<=0?'#16a34a':'#dc2626')};">
                    ${isBatal?'<span style="font-size:11px;color:var(--ink-400);">—</span>':fRp(item.sisa_setelah)}</div>
                ${item.sisa_setelah<=0&&!isBatal?'<div style="font-size:10px;color:#16a34a;font-weight:600;">✓ LUNAS</div>':''}
            </td>
            <td style="font-size:12px;color:var(--ink-700);">${fDate(item.tanggal_bayar)}</td>
            <td style="font-size:12px;color:var(--ink-700);">${item.nama_petugas}</td>
            <td>
                <select class="rw-status-sel rw-status-sel-${item.status}"
                    onchange="updateStatusRiwayat(${item.id_pembayaran}, this.value, this)">
                    <option value="draft" ${item.status==='draft'?'selected':''}>Draft</option>
                    <option value="valid" ${item.status==='valid'?'selected':''}>Valid</option>
                    <option value="batal" ${item.status==='batal'?'selected':''}>Batal</option>
                </select>
            </td>
            <td style="text-align:right;">
                <button class="btn-struk" onclick="cetakStrukSingle(${item.id_pembayaran})">
                    <i class="bx bx-printer"></i>
                </button>
            </td>
        </tr>`;
    }).join('');
}

/* ─── INPUT FORMAT ─── */
document.getElementById('input_nominal_bayar').addEventListener('input', function () {
    const raw = parseRibuan(this.value);
    this.value = fRibuan(String(raw));
    const sisa = _currentProjekData ? parseFloat(_currentProjekData.sisa_tanggungan) : 0;
    this.style.borderColor = (raw > sisa && sisa > 0) ? '#dc2626' : '';
});
document.getElementById('input_nominal_bayar').addEventListener('keydown', function (e) {
    if (!['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Home','End'].includes(e.key) && !/^\d$/.test(e.key)) e.preventDefault();
});

/* ─── SIMPAN PEMBAYARAN ─── */
async function simpanPembayaran() {
    if (!_currentProjekId) return;
    const nominal = parseRibuan(document.getElementById('input_nominal_bayar').value);
    const tanggal = document.getElementById('input_tanggal_bayar').value;
    const metode  = document.getElementById('input_metode').value;
    const catatan = document.getElementById('input_catatan').value;
    const sisa    = _currentProjekData ? parseFloat(_currentProjekData.sisa_tanggungan) : 0;
    if (!nominal || nominal <= 0) { showToastBayar('Masukkan nominal pembayaran.', 'error'); return; }
    if (!tanggal) { showToastBayar('Pilih tanggal bayar.', 'error'); return; }
    if (!metode)  { showToastBayar('Pilih metode pembayaran.', 'error'); return; }
    if (nominal > sisa) { showToastBayar(`Jumlah melebihi sisa tanggungan (${fRp(sisa)}).`, 'error'); return; }
    const btn = document.getElementById('btnSimpanBayar');
    btn.disabled = true; btn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Menyimpan...';
    try {
        const res  = await fetch(`${BAYAR_BASE_URL}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': BAYAR_CSRF },
            body: JSON.stringify({ id_projek: _currentProjekId, jumlah_bayar: nominal, tanggal_bayar: tanggal, id_metode_pembayaran: parseInt(metode), catatan }),
        });
        const data = await res.json();
        if (!data.success) throw new Error(data.message || 'Gagal');
        showToastBayar(data.message, 'success');
        _currentProjekData.sisa_tanggungan = data.sisa_tanggungan;
        document.getElementById('hint_sisa').textContent = 'Maks: ' + fRp(data.sisa_tanggungan);
        const res2  = await fetch(`${BAYAR_BASE_URL}/${_currentProjekId}/riwayat`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': BAYAR_CSRF } });
        const data2 = await res2.json();
        if (data2.success) {
            _currentProjekData = data2.projek; _riwayatData = data2.riwayat || [];
            renderBayarInfo(data2.projek); renderRiwayat(_riwayatData);
        }
        resetBayarForm();
        if (parseFloat(data.sisa_tanggungan) <= 0) document.getElementById('bayarFormSection').style.display = 'none';
        setTimeout(() => window.location.reload(), 1800);
    } catch (err) {
        showToastBayar(err.message, 'error');
    } finally {
        btn.disabled = false; btn.innerHTML = '<i class="bx bx-save"></i> Simpan Pembayaran';
    }
}

/* ─── UPDATE STATUS RIWAYAT ─── */
async function updateStatusRiwayat(idPembayaran, newStatus, selectEl) {
    const prev = selectEl.dataset.prev || selectEl.value;
    selectEl.dataset.prev = newStatus;
    try {
        const res  = await fetch(`${BAYAR_BASE_URL}/${idPembayaran}/status`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': BAYAR_CSRF },
            body: JSON.stringify({ status: newStatus }),
        });
        const data = await res.json();
        if (!data.success) throw new Error(data.message || 'Gagal');
        showToastBayar(data.message, 'success');
        const res2  = await fetch(`${BAYAR_BASE_URL}/${_currentProjekId}/riwayat`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': BAYAR_CSRF } });
        const data2 = await res2.json();
        if (data2.success) {
            _currentProjekData = data2.projek; _riwayatData = data2.riwayat || [];
            renderBayarInfo(data2.projek); renderRiwayat(_riwayatData);
            document.getElementById('hint_sisa').textContent = 'Maks: ' + fRp(data2.projek.sisa_tanggungan);
            document.getElementById('bayarFormSection').style.display = parseFloat(data2.projek.sisa_tanggungan) <= 0 ? 'none' : '';
        }
        setTimeout(() => window.location.reload(), 1800);
    } catch (err) {
        selectEl.value = prev; showToastBayar(err.message, 'error');
    }
}

/* ─── CETAK STRUK SINGLE ─── */
async function cetakStrukSingle(idPembayaran) {
    try {
        const res  = await fetch(`${BAYAR_BASE_URL}/${idPembayaran}/struk`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': BAYAR_CSRF } });
        const data = await res.json();
        if (!data.success) throw new Error(data.message || 'Gagal');
        printStrukHtml(data.struk);
    } catch (err) { showToastBayar(err.message, 'error'); }
}

function printStrukHtml(s) {
    const sc = { valid:{label:'VALID',color:'#166534',bg:'#F0FDF4',border:'#BBF7D0'}, draft:{label:'DRAFT',color:'#d97706',bg:'#FFFBEB',border:'#FDE68A'}, batal:{label:'BATAL',color:'#dc2626',bg:'#FEF2F2',border:'#FECACA'} }[s.status] || { label:'DRAFT',color:'#d97706',bg:'#FFFBEB',border:'#FDE68A' };
    const pct = s.nominal_projek > 0 ? Math.round(((s.nominal_projek - s.sisa_setelah) / s.nominal_projek) * 100) : 0;
    const win = window.open('', '_blank', 'width=440,height=680');
    win.document.write(`<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><title>Struk ${s.kode_pembayaran}</title>
    <style>*{box-sizing:border-box;margin:0;padding:0;}body{font-family:'Courier New',monospace;background:#f5f5f5;padding:20px;display:flex;justify-content:center;}
    .struk{background:white;width:360px;border-radius:4px;box-shadow:0 2px 12px rgba(0,0,0,.12);overflow:hidden;}
    .sh{background:#1E2A3A;padding:16px 20px;text-align:center;display:flex;align-items:center;gap:10px;justify-content:center;}
    .sh img{width:44px;height:44px;object-fit:contain;background:white;border-radius:6px;padding:2px;}
    .sh-text{text-align:left;}.sh .co{font-size:13px;font-weight:700;color:white;}.sh .sub{font-size:10px;color:#9CA3AF;margin-top:3px;}
    .sb{padding:14px 20px;}.st{text-align:center;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#6B7280;padding-bottom:10px;border-bottom:1px dashed #E5E7EB;margin-bottom:10px;}
    .sr{display:flex;justify-content:space-between;margin-bottom:6px;font-size:11px;}.lbl{color:#6B7280;}.val{color:#111827;font-weight:600;text-align:right;max-width:55%;}
    .total{background:#1E2A3A;margin:10px -20px -14px;padding:12px 20px;}
    .tr{display:flex;justify-content:space-between;align-items:center;}.tl{font-size:10px;color:#9CA3AF;text-transform:uppercase;}.tv{font-size:16px;font-weight:700;color:white;}
    .sisa{display:flex;justify-content:space-between;align-items:center;margin-top:8px;padding-top:8px;border-top:1px solid rgba(255,255,255,.15);}
    .sl{font-size:10px;color:#9CA3AF;}.sv{font-size:13px;font-weight:700;color:${s.sisa_setelah<=0?'#4ade80':'#fca5a5'};}
    .badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:3px;font-size:10px;font-weight:700;background:${sc.bg};color:${sc.color};border:1px solid ${sc.border};}
    .sf{padding:12px 20px;text-align:center;font-size:9px;color:#9CA3AF;border-top:1px dashed #E5E7EB;}
    .bbg{background:#374151;height:4px;border-radius:2px;margin-top:6px;overflow:hidden;}.bf{height:100%;background:#4ade80;border-radius:2px;width:${pct}%;}
    @media print{body{background:white;padding:0;}.struk{box-shadow:none;}}</style></head><body>
    <div class="struk">
    <div class="sh">
        <img src="${window.location.origin}/assets/img/ttd/logo1.png" alt="KKS" onerror="this.style.display='none'">
        <div class="sh-text"><div class="co">PT KAWAN KITA SOLUSINDO</div><div class="sub">Bukti Pembayaran Termin Project</div></div>
    </div>
    <div class="sb"><div class="st">STRUK PEMBAYARAN</div>
    <div class="sr"><span class="lbl">Kode</span><span class="val">${s.kode_pembayaran}</span></div>
    <div class="sr"><span class="lbl">Tanggal</span><span class="val">${fDate(s.tanggal_bayar)}</span></div>
    <div class="sr"><span class="lbl">Metode</span><span class="val">${s.nama_metode}</span></div>
    <div class="sr"><span class="lbl">Petugas</span><span class="val">${s.nama_petugas}</span></div>
    <div class="sr" style="margin-top:8px;padding-top:8px;border-top:1px dashed #E5E7EB;"><span class="lbl">Project</span><span class="val">${s.nama_projek}</span></div>
    <div class="sr"><span class="lbl">Perusahaan</span><span class="val">${s.perusahaan_nama||s.perusahaan}</span></div>
    <div class="sr"><span class="lbl">Nilai Kontrak</span><span class="val">${fRp(s.nominal_projek)}</span></div>
    <div class="sr" style="margin-top:4px;"><span class="lbl">Status</span><span class="val"><span class="badge">${sc.label}</span></span></div>
    <div class="total">
        <div class="tr"><span class="tl">JUMLAH DIBAYAR</span><span class="tv">${fRp(s.jumlah_bayar)}</span></div>
        <div class="sisa"><span class="sl">Sisa Tanggungan</span><span class="sv">${s.sisa_setelah<=0?'✓ LUNAS':fRp(s.sisa_setelah)}</span></div>
        <div class="bbg"><div class="bf"></div></div>
    </div></div>
    <div class="sf">Dicetak: ${s.dicetak_pada} · PT Kawan Kita Solusindo</div></div>
    <script>window.onload=()=>{window.print();}<\/script></body></html>`);
    win.document.close();
}

/* ─── CETAK RIWAYAT (langsung print) ─── */
async function cetakRiwayatPDF() {
    if (!_currentProjekId) return;
    try {
        const res  = await fetch(`${BAYAR_BASE_URL}/${_currentProjekId}/cetak-riwayat`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': BAYAR_CSRF } });
        const data = await res.json();
        if (!data.success) throw new Error(data.message || 'Gagal');
        printRiwayatLangsung(data.projek, data.riwayat, data.dicetak_pada);
    } catch (err) { showToastBayar(err.message, 'error'); }
}

function printRiwayatLangsung(p, riwayat, dicetak) {
    const STATUS_STYLE = {
        valid: 'background:#F0FDF4;color:#166534;border:1px solid #BBF7D0;',
        draft: 'background:#FFFBEB;color:#92400E;border:1px solid #FDE68A;',
        batal: 'background:#FEF2F2;color:#dc2626;border:1px solid #FECACA;',
    };
    const rows = riwayat.map(item => {
        const ss = STATUS_STYLE[item.status] || STATUS_STYLE.draft;
        const isBatal = item.status === 'batal';
        return `<tr style="${isBatal?'opacity:.5;':''}">
            <td style="text-align:center;padding:8px 10px;border-bottom:1px solid #F3F4F6;font-size:11px;color:#6B7280;">${item.no}</td>
            <td style="padding:8px 10px;border-bottom:1px solid #F3F4F6;">
                <div style="font-weight:700;font-size:12px;color:#111827;${isBatal?'text-decoration:line-through;':''}">${fRp(item.jumlah_bayar)}</div>
                <div style="font-size:9px;color:#9CA3AF;">${item.kode_pembayaran}</div>
            </td>
            <td style="padding:8px 10px;border-bottom:1px solid #F3F4F6;font-weight:700;font-size:12px;color:${item.sisa_setelah<=0&&!isBatal?'#16a34a':'#dc2626'};">${isBatal?'—':fRp(item.sisa_setelah)}</td>
            <td style="padding:8px 10px;border-bottom:1px solid #F3F4F6;font-size:11px;color:#374151;">${fDate(item.tanggal_bayar)}</td>
            <td style="padding:8px 10px;border-bottom:1px solid #F3F4F6;font-size:11px;color:#374151;">${item.nama_petugas}</td>
            <td style="padding:8px 10px;border-bottom:1px solid #F3F4F6;font-size:11px;color:#374151;">${item.nama_metode}</td>
            <td style="padding:8px 10px;border-bottom:1px solid #F3F4F6;text-align:center;">
                <span style="display:inline-flex;padding:2px 8px;border-radius:3px;font-size:9px;font-weight:700;${ss}">${item.status.toUpperCase()}</span>
            </td>
        </tr>`;
    }).join('');
    const html = `<div style="font-family:'Times New Roman',Times,serif;max-width:760px;margin:0 auto;background:white;">
        <div style="background:#1E2A3A;padding:18px 24px;display:flex;justify-content:space-between;align-items:flex-start;">
            <div style="display:flex;align-items:center;gap:12px;">
                <img src="${window.location.origin}/assets/img/ttd/logo1.png" alt="KKS" style="width:48px;height:48px;object-fit:contain;background:white;border-radius:8px;padding:4px;" onerror="this.style.display='none'">
                <div>
                    <div style="font-size:9px;color:#9CA3AF;text-transform:uppercase;letter-spacing:.12em;margin-bottom:4px;">Riwayat Pembayaran Termin</div>
                    <div style="font-size:17px;font-weight:700;color:white;">${p.nama_projek}</div>
                    <div style="font-size:11px;color:#9CA3AF;margin-top:3px;">${p.perusahaan_nama||'—'}${p.perusahaan?' — '+p.perusahaan:''}</div>
                </div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:10px;color:#9CA3AF;font-family:monospace;">Dicetak: ${dicetak}</div>
                <div style="font-size:11px;color:#D1D5DB;margin-top:3px;">Kategori: ${p.kategori||'—'}</div>
            </div>
        </div>
        <div style="padding:14px 24px;background:#F9FAFB;border-bottom:1px solid #E5E7EB;display:grid;grid-template-columns:repeat(4,1fr);gap:16px;">
            ${[['Nominal Kontrak',fRp(p.nominal_projek),'#1E2A3A'],['Total Terbayar (Valid)',fRp(p.total_terbayar),'#166534'],['Sisa Tanggungan',p.sisa_tanggungan<=0?'LUNAS':fRp(p.sisa_tanggungan),p.sisa_tanggungan<=0?'#166534':'#dc2626'],['Progress Project',(p.progress||0)+'%','#5145cd']]
            .map(([lbl,val,col])=>`<div><div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;margin-bottom:4px;">${lbl}</div><div style="font-size:13px;font-weight:700;color:${col};">${val}</div></div>`).join('')}
        </div>
        <div style="padding:0 24px 20px;background:white;">
            <table style="width:100%;border-collapse:collapse;font-size:11px;margin-top:12px;">
                <thead><tr style="background:#1E2A3A;">
                    ${['No','Nominal Bayar','Sisa Stlh Bayar','Tanggal Bayar','Petugas','Metode','Status']
                    .map(h=>`<th style="padding:8px 10px;color:white;font-size:9px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;text-align:${h==='No'?'center':'left'};">${h}</th>`).join('')}
                </tr></thead>
                <tbody>${rows||`<tr><td colspan="7" style="text-align:center;padding:24px;color:#9CA3AF;font-style:italic;">Belum ada riwayat</td></tr>`}</tbody>
            </table>
        </div>
        <div style="background:#1E2A3A;padding:8px 24px;display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:9px;color:#9CA3AF;">PT Kawan Kita Solusindo</span>
            <span style="font-size:9px;color:#9CA3AF;">${dicetak}</span>
        </div>
    </div>`;
    const win = window.open('', '_blank', 'width=900,height=700');
    win.document.write(`<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><title>Riwayat Pembayaran</title>
    <style>*{box-sizing:border-box;}body{background:white;padding:20px;margin:0;font-family:'Times New Roman',Times,serif;}
    @media print{body{padding:0;}@page{margin:10mm 8mm;size:A4;}
    *{-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important;}}</style>
    </head><body>${html}<script>window.onload=()=>{window.print();}<\/script></body></html>`);
    win.document.close();
}

/* ═══════════════════════════════════════════════════
   CHART → BASE64 HELPER
   ═══════════════════════════════════════════════════ */
function renderChartToBase64(type, chartData, extraOptions = {}) {
    return new Promise(resolve => {
        const { width = 400, height = 220, ...chartOptions } = extraOptions;
        const canvas = document.createElement('canvas');
        canvas.width  = width;
        canvas.height = height;
        canvas.style.cssText = 'position:absolute;left:-9999px;top:-9999px;visibility:hidden;pointer-events:none;';
        document.body.appendChild(canvas);
        const chart = new Chart(canvas, {
            type,
            data: chartData,
            options: {
                responsive: false,
                animation: { duration: 0 },
                plugins: { legend: { display: false } },
                ...chartOptions
            }
        });
        setTimeout(() => {
            const base64 = canvas.toDataURL('image/png');
            chart.destroy();
            document.body.removeChild(canvas);
            resolve(base64);
        }, 400);
    });
}

/* ═══════════════════════════════════════════════════
   KOP LAPORAN HELPER
   ═══════════════════════════════════════════════════ */
function buildKopHTML(judulLaporan, subJudul, scopeLabel, tanggalCetak) {
    return `
    <div style="background:#1E2A3A;">
        <div style="display:flex;align-items:stretch;">
            <div style="width:90px;background:#16304a;display:flex;align-items:center;justify-content:center;flex-shrink:0;padding:10px;">
                <img src="${window.location.origin}/assets/img/ttd/logo1.png" alt="KKS"
                     style="width:64px;height:64px;object-fit:contain;background:white;border-radius:8px;padding:4px;display:block;"
                     onerror="this.outerHTML='<div style=width:64px;height:64px;border-radius:8px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:900;color:white;font-style:italic;font-family:serif>KK</div>'">
            </div>
            <div style="flex:1;padding:14px 20px;border-left:1px solid rgba(255,255,255,.1);">
                <div style="font-size:7px;color:#9CA3AF;text-transform:uppercase;letter-spacing:.14em;margin-bottom:3px;font-family:'Times New Roman',serif;">Dokumen Resmi Perusahaan</div>
                <div style="font-size:16px;font-weight:700;color:white;letter-spacing:.03em;font-family:'Times New Roman',serif;">PT KAWAN KITA SOLUSINDO</div>
                <div style="font-size:10px;color:#9CA3AF;margin-top:2px;font-family:'Times New Roman',serif;">Sistem Manajemen Project &amp; Pembayaran Termin</div>
                <div style="margin-top:6px;display:inline-block;padding:2px 10px;background:rgba(105,108,255,.25);border:1px solid rgba(105,108,255,.4);border-radius:3px;font-size:9px;color:#c7d2fe;font-family:'Times New Roman',serif;">${scopeLabel}</div>
            </div>
            <div style="text-align:right;padding:14px 20px;flex-shrink:0;border-left:1px solid rgba(255,255,255,.08);">
                <div style="font-size:9px;color:#9CA3AF;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px;">Dicetak Pada</div>
                <div style="font-size:11px;color:#D1D5DB;">${tanggalCetak}</div>
            </div>
        </div>
        <div style="background:#16304a;padding:8px 20px 8px 110px;border-top:1px solid rgba(255,255,255,.1);">
            <div style="font-size:14px;font-weight:700;color:white;font-family:'Times New Roman',serif;">${judulLaporan}</div>
            <div style="font-size:10px;color:#9CA3AF;margin-top:1px;font-family:'Times New Roman',serif;">${subJudul}</div>
        </div>
    </div>`;
}

/* ═══════════════════════════════════════════════════
   EXPORT LAPORAN — langsung print
   ═══════════════════════════════════════════════════ */
function openExportLaporan()  { document.getElementById('exportLaporanOverlay').style.display = 'flex'; }
function closeExportLaporan() { document.getElementById('exportLaporanOverlay').style.display = 'none'; }

async function generateExportPDF() {
    const scope = document.querySelector('input[name="export_scope"]:checked').value;
    let data = PHP_ALL_PROJEKS_DATA;
    if (scope === 'lunas')       data = data.filter(p => p.sisa_tanggungan <= 0);
    if (scope === 'belum_lunas') data = data.filter(p => p.sisa_tanggungan > 0);

    const totalNominal  = data.reduce((s, p) => s + p.nominal_projek, 0);
    const totalTerbayar = data.reduce((s, p) => s + Math.max(0, p.nominal_projek - p.sisa_tanggungan), 0);
    const totalSisa     = data.reduce((s, p) => s + Math.max(0, p.sisa_tanggungan), 0);
    const jmlLunas      = data.filter(p => p.sisa_tanggungan <= 0).length;
    const jmlBelumLunas = data.filter(p => p.sisa_tanggungan > 0).length;
    const pctOverall    = totalNominal > 0 ? Math.round((totalTerbayar / totalNominal) * 100) : 0;

    closeExportLaporan();
    showToastBayar('Membangun laporan...', 'info');

    /* PIE CHART */
    const pieBase64 = await renderChartToBase64('pie', {
        labels: ['Lunas', 'Belum Lunas'],
        datasets: [{ data: [jmlLunas, jmlBelumLunas], backgroundColor: ['#166534','#dc2626'], borderColor: ['#fff','#fff'], borderWidth: 3 }]
    }, { width: 200, height: 200 });

    /* BAR CHART */
    const defaultYear = getAvailableYears()[0] || new Date().getFullYear();
    const { labels: bLabels, values: bValues } = buildMonthlyData(defaultYear);
    const toJt = v => +(v / 1000000).toFixed(1);
    const barBase64 = await renderChartToBase64('bar', {
        labels: bLabels,
        datasets: [{
            data: bValues.map(toJt),
            backgroundColor: bValues.map(v => v > 0 ? 'rgba(30,42,58,.80)' : 'rgba(209,213,219,.4)'),
            borderColor: '#1E2A3A', borderWidth: 1, borderRadius: 3,
        }]
    }, {
        width: 520, height: 200,
        scales: {
            x: { ticks: { font:{ size:9 } }, grid:{ color:'#F3F4F6' } },
            y: { beginAtZero:true, ticks:{ font:{ size:9 }, callback: v => v+' Jt' }, grid:{ color:'#F3F4F6' } }
        }
    });

    const scopeLabel = scope === 'semua' ? 'Semua Project' : scope === 'lunas' ? 'Project Lunas' : 'Project Belum Lunas';
    const html = buildLaporanHTML({ data, scopeLabel, totalNominal, totalTerbayar, totalSisa, jmlLunas, jmlBelumLunas, pctOverall, pieBase64, barBase64, defaultYear });

    /* ── LANGSUNG BUKA WINDOW BARU & PRINT ── */
    const win = window.open('', '_blank', 'width=1100,height=800');
    win.document.write(`<!DOCTYPE html><html lang="id"><head><meta charset="utf-8">
    <title>Laporan Pembayaran Termin</title>
    <style>*{box-sizing:border-box;}body{background:white;padding:20px;margin:0;font-family:'Times New Roman',Times,serif;}
    @media print{*{-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important;}body{padding:0;}@page{margin:8mm 6mm;size:A4 landscape;}}</style>
    </head><body>${html}<script>window.onload=()=>{window.print();}<\/script></body></html>`);
    win.document.close();
}

function buildLaporanHTML({ data, scopeLabel, totalNominal, totalTerbayar, totalSisa, jmlLunas, jmlBelumLunas, pctOverall, pieBase64, barBase64, defaultYear }) {
    const tabelRows = data.map((p, i) => {
        const terbayar = Math.max(0, p.nominal_projek - p.sisa_tanggungan);
        const pct = p.nominal_projek > 0 ? Math.round((terbayar / p.nominal_projek) * 100) : 0;
        const isLunas = p.sisa_tanggungan <= 0;
        const pctColor = pct >= 100 ? '#166534' : pct >= 50 ? '#1a3a6c' : '#dc2626';
        return `<tr style="background:${i%2===0?'white':'#FAFAF9'};">
            <td style="text-align:center;padding:7px 8px;border-bottom:1px solid #E5E7EB;font-size:11px;color:#6B7280;">${i+1}</td>
            <td style="padding:7px 8px;border-bottom:1px solid #E5E7EB;">
                <div style="font-weight:700;font-size:12px;color:#111827;">${p.nama_projek}</div>
                <div style="font-size:10px;color:#6B7280;margin-top:1px;">${p.perusahaan_pt||'—'}${p.perusahaan_nama?' ('+p.perusahaan_nama+')':''}</div>
            </td>
            <td style="padding:7px 8px;border-bottom:1px solid #E5E7EB;text-align:right;font-size:11px;color:#374151;">${fRp(p.nominal_projek)}</td>
            <td style="padding:7px 8px;border-bottom:1px solid #E5E7EB;text-align:right;font-weight:700;font-size:11px;color:${isLunas?'#166534':'#dc2626'};">${isLunas?'LUNAS':fRp(p.sisa_tanggungan)}</td>
            <td style="padding:7px 8px;border-bottom:1px solid #E5E7EB;text-align:right;font-weight:700;font-size:11px;color:#166534;">${fRp(terbayar)}</td>
            <td style="padding:7px 8px;border-bottom:1px solid #E5E7EB;text-align:center;font-weight:700;font-size:11px;color:${pctColor};">${pct}%</td>
        </tr>`;
    }).join('');

    return `<div style="font-family:'Times New Roman',Times,serif;max-width:1000px;margin:0 auto;background:white;border:1px solid #C8C8C8;">
    ${buildKopHTML('Laporan Pembayaran Termin Project','Rekap status pembayaran dan sisa tanggungan seluruh project',scopeLabel,PHP_TODAY)}
    <!-- RINGKASAN -->
    <div style="padding:16px 28px 0;">
        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#1E2A3A;border-bottom:2px solid #1E2A3A;padding-bottom:5px;margin-bottom:12px;">I. Ringkasan Keseluruhan</div>
        <table style="width:100%;border-collapse:collapse;font-size:12px;font-family:'Times New Roman',Times,serif;">
            <tbody>
                <tr>
                    <td style="padding:5px 8px;color:#374151;font-weight:600;width:160px;">Total Project</td>
                    <td style="padding:5px 8px;width:14px;color:#6B7280;">:</td>
                    <td style="padding:5px 8px;font-weight:600;padding-right:30px;">${data.length} Project</td>
                    <td style="padding:5px 8px;color:#374151;font-weight:600;width:160px;">Nominal Kontrak</td>
                    <td style="padding:5px 8px;width:14px;color:#6B7280;">:</td>
                    <td style="padding:5px 8px;font-weight:700;color:#1E2A3A;">${fRp(totalNominal)}</td>
                </tr>
                <tr>
                    <td style="padding:5px 8px;color:#374151;font-weight:600;">Project Lunas</td>
                    <td style="padding:5px 8px;color:#6B7280;">:</td>
                    <td style="padding:5px 8px;font-weight:700;color:#166534;padding-right:30px;">${jmlLunas} Project</td>
                    <td style="padding:5px 8px;color:#374151;font-weight:600;">Total Terbayar</td>
                    <td style="padding:5px 8px;color:#6B7280;">:</td>
                    <td style="padding:5px 8px;font-weight:700;color:#166534;">${fRp(totalTerbayar)}</td>
                </tr>
                <tr>
                    <td style="padding:5px 8px;color:#374151;font-weight:600;">Belum Lunas</td>
                    <td style="padding:5px 8px;color:#6B7280;">:</td>
                    <td style="padding:5px 8px;font-weight:700;color:#dc2626;padding-right:30px;">${jmlBelumLunas} Project</td>
                    <td style="padding:5px 8px;color:#374151;font-weight:600;">Sisa Tunggakan</td>
                    <td style="padding:5px 8px;color:#6B7280;">:</td>
                    <td style="padding:5px 8px;font-weight:700;color:#dc2626;">${fRp(totalSisa)}</td>
                </tr>
                <tr>
                    <td style="padding:5px 8px;color:#374151;font-weight:600;">Persentase Terbayar</td>
                    <td style="padding:5px 8px;color:#6B7280;">:</td>
                    <td colspan="4" style="padding:5px 8px;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="flex:1;max-width:240px;background:#E5E7EB;height:8px;border-radius:4px;overflow:hidden;">
                                <div style="width:${pctOverall}%;height:100%;background:${pctOverall>=75?'#166534':pctOverall>=50?'#1a3a6c':'#dc2626'};border-radius:4px;"></div>
                            </div>
                            <strong style="color:${pctOverall>=75?'#166534':pctOverall>=50?'#1a3a6c':'#dc2626'};">${pctOverall}%</strong>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- GRAFIK -->
    <div style="padding:16px 28px 0;">
        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#1E2A3A;border-bottom:2px solid #1E2A3A;padding-bottom:5px;margin-bottom:12px;">II. Grafik &amp; Diagram</div>
        <div style="display:grid;grid-template-columns:210px 1fr;gap:16px;">
            <div style="background:#FAFAF8;border:1px solid #E5E7EB;border-radius:6px;padding:12px 14px;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;margin-bottom:8px;">Status Pelunasan</div>
                <div style="display:flex;flex-direction:column;align-items:center;gap:8px;">
                    <img src="${pieBase64}" width="140" height="140" style="display:block;">
                    <div style="font-size:10px;line-height:1.9;">
                        <div style="display:flex;align-items:center;gap:5px;"><div style="width:10px;height:10px;border-radius:2px;background:#166534;flex-shrink:0;"></div><strong style="color:#166534;">${jmlLunas}</strong> <span style="color:#6B7280;">Lunas (${Math.round(jmlLunas/((jmlLunas+jmlBelumLunas)||1)*100)}%)</span></div>
                        <div style="display:flex;align-items:center;gap:5px;"><div style="width:10px;height:10px;border-radius:2px;background:#dc2626;flex-shrink:0;"></div><strong style="color:#dc2626;">${jmlBelumLunas}</strong> <span style="color:#6B7280;">Belum Lunas (${Math.round(jmlBelumLunas/((jmlLunas+jmlBelumLunas)||1)*100)}%)</span></div>
                    </div>
                </div>
            </div>
            <div style="background:#FAFAF8;border:1px solid #E5E7EB;border-radius:6px;padding:12px 14px;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;margin-bottom:8px;">Grafik Pembayaran per Bulan — ${defaultYear}</div>
                <img src="${barBase64}" style="width:100%;max-height:180px;object-fit:contain;display:block;">
            </div>
        </div>
    </div>
    <!-- DETAIL TABLE -->
    <div style="padding:16px 28px 0;">
        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#1E2A3A;border-bottom:2px solid #1E2A3A;padding-bottom:5px;margin-bottom:12px;">III. Detail Data Project (${data.length} Project)</div>
        <table style="width:100%;border-collapse:collapse;font-size:11px;font-family:'Times New Roman',Times,serif;">
            <thead>
                <tr style="background:#1E2A3A;">
                    <th style="padding:9px 8px;color:white;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;text-align:center;width:32px;">No</th>
                    <th style="padding:9px 8px;color:white;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;text-align:left;">Nama Project &amp; Perusahaan</th>
                    <th style="padding:9px 8px;color:white;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;text-align:right;">Nominal Awal</th>
                    <th style="padding:9px 8px;color:white;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;text-align:right;">Sisa Tunggakan</th>
                    <th style="padding:9px 8px;color:white;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;text-align:right;">Terbayar</th>
                    <th style="padding:9px 8px;color:white;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;text-align:center;width:60px;">% Bayar</th>
                </tr>
            </thead>
            <tbody>${tabelRows}</tbody>
        </table>
    </div>
    <!-- TOTAL KESELURUHAN - PALING BAWAH -->
    <div style="padding:16px 28px 20px;">
        <div style="background:#1E2A3A;border-radius:8px;overflow:hidden;">
            <div style="padding:7px 16px;background:#16304a;border-bottom:1px solid rgba(255,255,255,.1);">
                <span style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#9CA3AF;font-family:'Times New Roman',serif;">
                    Rekapitulasi Total Keseluruhan — ${data.length} Project
                </span>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;">
                <div style="padding:14px 18px;border-right:1px solid rgba(255,255,255,.08);">
                    <div style="font-size:9px;color:#9CA3AF;text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px;">Nominal Kontrak</div>
                    <div style="font-size:15px;font-weight:800;color:white;">${fRp(totalNominal)}</div>
                </div>
                <div style="padding:14px 18px;border-right:1px solid rgba(255,255,255,.08);">
                    <div style="font-size:9px;color:#9CA3AF;text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px;">Sisa Tunggakan</div>
                    <div style="font-size:15px;font-weight:800;color:#fca5a5;">${fRp(totalSisa)}</div>
                </div>
                <div style="padding:14px 18px;border-right:1px solid rgba(255,255,255,.08);">
                    <div style="font-size:9px;color:#9CA3AF;text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px;">Total Terbayar</div>
                    <div style="font-size:15px;font-weight:800;color:#4ade80;">${fRp(totalTerbayar)}</div>
                </div>
                <div style="padding:14px 18px;">
                    <div style="font-size:9px;color:#9CA3AF;text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px;">% Terbayar</div>
                    <div style="font-size:15px;font-weight:800;color:${pctOverall>=75?'#4ade80':pctOverall>=50?'#93c5fd':'#fca5a5'};">${pctOverall}%</div>
                    <div style="margin-top:6px;background:#374151;height:4px;border-radius:2px;overflow:hidden;">
                        <div style="width:${pctOverall}%;height:100%;background:${pctOverall>=75?'#4ade80':pctOverall>=50?'#93c5fd':'#fca5a5'};border-radius:2px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FOOTER -->
    <div style="background:#1E2A3A;padding:9px 28px;display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:9px;color:#9CA3AF;font-family:'Times New Roman',Times,serif;">PT Kawan Kita Solusindo — Laporan Pembayaran Termin</span>
        <span style="font-size:9px;color:#9CA3AF;font-family:'Times New Roman',Times,serif;">Dicetak: ${PHP_TODAY}</span>
    </div>
    </div>`;
}

/* ═══════════════════════════════════════════════════
   MONTHLY DATA HELPERS
   ═══════════════════════════════════════════════════ */
const BULAN_NAMES = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
const BULAN_SHORT = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

function buildMonthlyData(yearFilter) {
    const monthMap = {};
    BULAN_SHORT.forEach((b, i) => { monthMap[i + 1] = 0; });
    if (PHP_PAYMENT_MONTHLY && PHP_PAYMENT_MONTHLY.length > 0) {
        PHP_PAYMENT_MONTHLY.forEach(row => {
            if (String(row.year) === String(yearFilter)) {
                monthMap[parseInt(row.month)] = parseFloat(row.total) || 0;
            }
        });
    }
    return { labels: BULAN_SHORT, values: Object.values(monthMap) };
}
function getAvailableYears() {
    const years = new Set();
    if (PHP_PAYMENT_MONTHLY && PHP_PAYMENT_MONTHLY.length > 0) {
        PHP_PAYMENT_MONTHLY.forEach(r => years.add(String(r.year)));
    }
    if (years.size === 0) years.add(String(new Date().getFullYear()));
    return [...years].sort((a, b) => Number(a) - Number(b));
}
function getPaymentValue(year, month) {
    if (!PHP_PAYMENT_MONTHLY) return 0;
    const row = PHP_PAYMENT_MONTHLY.find(r => String(r.year) === String(year) && parseInt(r.month) === parseInt(month));
    return row ? parseFloat(row.total) || 0 : 0;
}
function filterDetailedPayments(year, fromMonth, toMonth) {
    return PHP_DETAILED_PAYMENTS.filter(item => {
        if (!item.tanggal_bayar) return false;
        const d = new Date(item.tanggal_bayar);
        const y = d.getFullYear();
        const m = d.getMonth() + 1;
        return y === Number(year) && m >= fromMonth && m <= toMonth;
    });
}

/* ═══════════════════════════════════════════════════
   LAPORAN PENDAPATAN
   ═══════════════════════════════════════════════════ */
function openExportPendapatan() {
    initPendapatanModal();
    document.getElementById('exportPendapatanOverlay').style.display = 'flex';
}
function closeExportPendapatan() {
    document.getElementById('exportPendapatanOverlay').style.display = 'none';
}

function initPendapatanModal() {
    const years = getAvailableYears();
    const now   = new Date();
    ['pend_dari_tahun','pend_sampai_tahun'].forEach(id => {
        const sel = document.getElementById(id);
        sel.innerHTML = '';
        years.forEach(y => {
            const opt = document.createElement('option');
            opt.value = y; opt.textContent = y;
            sel.appendChild(opt);
        });
    });
    document.getElementById('pend_dari_bulan').value   = '1';
    document.getElementById('pend_dari_tahun').value   = years[0] || now.getFullYear();
    document.getElementById('pend_sampai_bulan').value = String(now.getMonth() + 1);
    document.getElementById('pend_sampai_tahun').value = years[years.length - 1] || now.getFullYear();
    const container = document.getElementById('pend_tahun_checkboxes');
    container.innerHTML = '';
    years.forEach(y => {
        const btn = document.createElement('button');
        btn.type = 'button'; btn.dataset.year = y; btn.dataset.selected = '1';
        btn.className = 'pend-year-btn';
        btn.textContent = y;
        btn.style.cssText = 'padding:8px 18px;border-radius:99px;border:1.5px solid transparent;background:linear-gradient(135deg,#696cff 0%,#5145cd 100%);color:white;font-size:13px;font-weight:600;cursor:pointer;transition:all .15s;';
        btn.onclick = () => updateYearCheckboxStyle(btn);
        container.appendChild(btn);
    });
    updatePendapatanHint();
    togglePendapatanType('bulanan');
}
function togglePendapatanType(val) {
    document.getElementById('panel_bulanan').style.display = val === 'bulanan' ? '' : 'none';
    document.getElementById('panel_tahunan').style.display = val === 'tahunan' ? '' : 'none';
    ['bulanan','tahunan'].forEach(t => {
        const lbl = document.getElementById('lbl_' + t);
        if (!lbl) return;
        lbl.style.borderColor = val === t ? '#696cff' : '#E5E7EB';
        lbl.style.background  = val === t ? '#f0efff'  : 'white';
    });
}
function updateYearCheckboxStyle(btn) {
    const isSelected = btn.dataset.selected === '1';
    btn.dataset.selected = isSelected ? '0' : '1';
    if (!isSelected) {
        btn.style.background = 'linear-gradient(135deg,#696cff 0%,#5145cd 100%)';
        btn.style.color = 'white'; btn.style.borderColor = 'transparent';
    } else {
        btn.style.background = 'white'; btn.style.color = '#374151'; btn.style.borderColor = '#E5E7EB';
    }
}
function updatePendapatanHint() {
    const dari_b = parseInt(document.getElementById('pend_dari_bulan').value);
    const dari_y = document.getElementById('pend_dari_tahun').value;
    const smpi_b = parseInt(document.getElementById('pend_sampai_bulan').value);
    const smpi_y = document.getElementById('pend_sampai_tahun').value;
    const hint   = document.getElementById('pend_hint');
    if (!hint) return;
    const durasi = (Number(smpi_y) - Number(dari_y)) * 12 + (smpi_b - dari_b) + 1;
    hint.textContent = `Periode: ${BULAN_NAMES[dari_b-1]} ${dari_y} — ${BULAN_NAMES[smpi_b-1]} ${smpi_y} (${Math.max(1,durasi)} bulan)`;
}
function buildPeriodeList(type) {
    if (type === 'tahunan') {
        const selectedYears = [...document.querySelectorAll('#pend_tahun_checkboxes .pend-year-btn')]
            .filter(b => b.dataset.selected === '1').map(b => b.dataset.year);
        if (!selectedYears.length) { showToastBayar('Pilih minimal satu tahun.', 'error'); return null; }
        return selectedYears.map(y => ({ year: Number(y), fromMonth: 1, toMonth: 12, label: `Tahun ${y}` }));
    }
    const dari_b = parseInt(document.getElementById('pend_dari_bulan').value);
    const dari_y = parseInt(document.getElementById('pend_dari_tahun').value);
    const smpi_b = parseInt(document.getElementById('pend_sampai_bulan').value);
    const smpi_y = parseInt(document.getElementById('pend_sampai_tahun').value);
    if (dari_y > smpi_y || (dari_y === smpi_y && dari_b > smpi_b)) {
        showToastBayar('Periode awal tidak boleh lebih besar dari periode akhir.', 'error'); return null;
    }
    const periodes = [];
    for (let y = dari_y; y <= smpi_y; y++) {
        const fmB = y === dari_y ? dari_b : 1;
        const toB = y === smpi_y ? smpi_b : 12;
        periodes.push({ year: y, fromMonth: fmB, toMonth: toB,
            label: fmB === 1 && toB === 12 ? `Tahun ${y}` : `${BULAN_SHORT[fmB-1]}–${BULAN_SHORT[toB-1]} ${y}` });
    }
    return periodes;
}

async function generateLaporanPendapatan() {
    const type = document.querySelector('input[name="pendapatan_type"]:checked').value;
    const periodeList = buildPeriodeList(type);
    if (!periodeList) return;
    closeExportPendapatan();
    showToastBayar('Membuat laporan pendapatan...', 'info');

    const chartImgMap = {};
    for (const periode of periodeList) {
        const labels = [], values = [];
        for (let m = periode.fromMonth; m <= periode.toMonth; m++) {
            labels.push(BULAN_SHORT[m-1]);
            values.push(getPaymentValue(periode.year, m) / 1_000_000);
        }
        const base64 = await renderChartToBase64('bar', {
            labels,
            datasets: [{
                data: values,
                backgroundColor: values.map(v => v > 0 ? 'rgba(30,42,58,.82)' : 'rgba(209,213,219,.5)'),
                borderColor: values.map(v => v > 0 ? '#1E2A3A' : '#D1D5DB'),
                borderWidth: 1, borderRadius: 4,
            }]
        }, {
            width: 900, height: 200,
            scales: {
                x: { ticks:{ font:{ size:10 } }, grid:{ color:'#F3F4F6' } },
                y: { beginAtZero:true, ticks:{ font:{ size:10 }, callback: v => 'Rp '+v.toFixed(0)+' Jt' }, grid:{ color:'#F3F4F6' } }
            }
        });
        chartImgMap[`${periode.year}_${periode.fromMonth}_${periode.toMonth}`] = base64;
    }

    const typeLabel = buildTypeLabel(type);
    const html = buildLaporanPendapatanHTML(periodeList, chartImgMap, type, typeLabel);

    /* ── LANGSUNG BUKA WINDOW BARU & PRINT ── */
    const win = window.open('', '_blank', 'width=1100,height=800');
    win.document.write(`<!DOCTYPE html><html lang="id"><head><meta charset="utf-8">
    <title>Laporan Pendapatan</title>
    <style>
        *{box-sizing:border-box;}
        body{background:white;padding:20px;margin:0;font-family:'Times New Roman',Times,serif;}
        @media print{
            *{-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important;}
            body{padding:0!important;margin:0!important;}
            @page{margin:8mm 6mm;size:A4 landscape;}
            .laporan-container{border:none!important;}
        }
    </style>
    </head><body>${html}<script>window.onload=()=>{window.print();}<\/script></body></html>`);
    win.document.close();
}

function buildTypeLabel(type) {
    if (type === 'tahunan') {
        const selected = [...document.querySelectorAll('#pend_tahun_checkboxes .pend-year-btn')]
            .filter(b => b.dataset.selected === '1').map(b => b.dataset.year);
        return selected.join(', ');
    }
    const dari_b = parseInt(document.getElementById('pend_dari_bulan').value);
    const dari_y = document.getElementById('pend_dari_tahun').value;
    const smpi_b = parseInt(document.getElementById('pend_sampai_bulan').value);
    const smpi_y = document.getElementById('pend_sampai_tahun').value;
    return `${BULAN_NAMES[dari_b-1]} ${dari_y} — ${BULAN_NAMES[smpi_b-1]} ${smpi_y}`;
}

function buildLaporanPendapatanHTML(periodeList, chartImgMap, type, typeLabel) {
    let grandTotal = 0;
    const allFilteredPayments = [];

    const periodeBlocks = periodeList.map(periode => {
        const key = `${periode.year}_${periode.fromMonth}_${periode.toMonth}`;
        const imgSrc = chartImgMap[key] || '';

        const detailPeriode = filterDetailedPayments(periode.year, periode.fromMonth, periode.toMonth);
        allFilteredPayments.push(...detailPeriode);

        let subtotal = 0;
        const rows = [];
        for (let m = periode.fromMonth; m <= periode.toMonth; m++) {
            const val = getPaymentValue(periode.year, m);
            subtotal += val;
            rows.push({ bulan: BULAN_NAMES[m-1], val });
        }
        grandTotal += subtotal;

        /* Rekap metode */
        const metodeMap = {};
        detailPeriode.forEach(item => {
            const nm = item.nama_metode || 'Tidak Diketahui';
            metodeMap[nm] = (metodeMap[nm] || 0) + item.jumlah_bayar;
        });
        const totalMetode = Object.values(metodeMap).reduce((a, b) => a + b, 0);

        const metodeHTML = Object.entries(metodeMap).length > 0
            ? `<div style="margin-top:12px;padding:10px 14px;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:6px;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;margin-bottom:8px;">Rekap Metode Pembayaran</div>
                <table style="width:100%;border-collapse:collapse;font-size:11px;font-family:'Times New Roman',Times,serif;">
                    <thead><tr style="border-bottom:1.5px solid #E5E7EB;">
                        <th style="padding:5px 8px;text-align:left;font-size:9px;font-weight:700;text-transform:uppercase;color:#6B7280;">Metode</th>
                        <th style="padding:5px 8px;text-align:right;font-size:9px;font-weight:700;text-transform:uppercase;color:#6B7280;">Nominal Masuk</th>
                        <th style="padding:5px 8px;text-align:center;font-size:9px;font-weight:700;text-transform:uppercase;color:#6B7280;">%</th>
                    </tr></thead>
                    <tbody>
                        ${Object.entries(metodeMap).map(([nm, val], idx) => {
                            const pct = totalMetode > 0 ? Math.round((val / totalMetode) * 100) : 0;
                            return `<tr style="background:${idx%2===0?'white':'#FAFAF8'};">
                                <td style="padding:5px 8px;font-weight:600;color:#374151;">${nm}</td>
                                <td style="padding:5px 8px;text-align:right;font-weight:700;color:#166534;">${fRp(val)}</td>
                                <td style="padding:5px 8px;text-align:center;color:#6B7280;">${pct}%</td>
                            </tr>`;
                        }).join('')}
                    </tbody>
                    <tfoot>
                        <tr style="background:#1E2A3A;">
                            <td style="padding:6px 8px;font-weight:700;color:#D1D5DB;font-size:10px;">TOTAL PEMASUKAN</td>
                            <td style="padding:6px 8px;text-align:right;font-weight:800;color:#4ade80;font-size:11px;">${fRp(totalMetode)}</td>
                            <td style="padding:6px 8px;text-align:center;color:#4ade80;font-weight:700;">100%</td>
                        </tr>
                    </tfoot>
                </table>
               </div>`
            : '';

        /* Tabel detail transaksi (TANPA tfoot subtotal — dipindah ke bawah) */
        const detailRows = detailPeriode.map((item, idx) => `
            <tr style="background:${idx%2===0?'white':'#FAFAF9'};">
                <td style="padding:6px 8px;text-align:center;font-size:10px;color:#6B7280;border-bottom:1px solid #F3F4F6;">${idx+1}</td>
                <td style="padding:6px 8px;border-bottom:1px solid #F3F4F6;">
                    <div style="font-weight:700;font-size:11px;color:#111827;">${item.nama_projek}${item.sisa_tanggungan===0?'<span style="margin-left:5px;display:inline-block;padding:1px 6px;border-radius:99px;background:#F0FDF4;border:1px solid #BBF7D0;color:#166534;font-size:9px;font-weight:700;">LUNAS</span>':''}</div>
                    <div style="font-size:9px;color:#6B7280;margin-top:1px;">${item.nama_perusahaan||'—'}${item.nama_perwakilan?' ('+item.nama_perwakilan+')':''}</div>
                </td>
                <td style="padding:6px 8px;font-size:10px;color:#374151;border-bottom:1px solid #F3F4F6;font-family:monospace;">${item.kode_pembayaran||'—'}</td>
                <td style="padding:6px 8px;text-align:right;font-weight:700;font-size:11px;color:#166634;border-bottom:1px solid #F3F4F6;">${fRp(item.jumlah_bayar)}</td>
                <td style="padding:6px 8px;font-size:10px;color:#374151;border-bottom:1px solid #F3F4F6;">${item.nama_petugas}</td>
                <td style="padding:6px 8px;font-size:10px;color:#374151;border-bottom:1px solid #F3F4F6;">${fDateShort(item.tanggal_bayar)}</td>
                <td style="padding:6px 8px;font-size:10px;color:#374151;border-bottom:1px solid #F3F4F6;">${item.nama_metode}</td>
            </tr>`).join('');

        const detailTableHTML = detailPeriode.length > 0
            ? `<div style="margin-top:12px;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;margin-bottom:6px;">Daftar Transaksi (${detailPeriode.length} transaksi)</div>
                <table style="width:100%;border-collapse:collapse;font-size:11px;font-family:'Times New Roman',Times,serif;">
                    <thead><tr style="background:#374151;">
                        <th style="padding:7px 8px;color:white;font-size:9px;font-weight:600;text-transform:uppercase;text-align:center;width:30px;">No</th>
                        <th style="padding:7px 8px;color:white;font-size:9px;font-weight:600;text-transform:uppercase;text-align:left;">Nama Project &amp; Perusahaan</th>
                        <th style="padding:7px 8px;color:white;font-size:9px;font-weight:600;text-transform:uppercase;text-align:left;">Kode Pembayaran</th>
                        <th style="padding:7px 8px;color:white;font-size:9px;font-weight:600;text-transform:uppercase;text-align:right;">Nominal Bayar</th>
                        <th style="padding:7px 8px;color:white;font-size:9px;font-weight:600;text-transform:uppercase;text-align:left;">Petugas</th>
                        <th style="padding:7px 8px;color:white;font-size:9px;font-weight:600;text-transform:uppercase;text-align:left;">Tgl Bayar</th>
                        <th style="padding:7px 8px;color:white;font-size:9px;font-weight:600;text-transform:uppercase;text-align:left;">Metode</th>
                    </tr></thead>
                    <tbody>${detailRows}</tbody>
                </table>
               </div>`
            : `<div style="text-align:center;padding:12px;color:#9CA3AF;font-size:11px;font-style:italic;background:#F9FAFB;border-radius:4px;margin-top:10px;">Tidak ada transaksi pada periode ini</div>`;

        const maxRow = Math.max(...rows.map(r => r.val), 1);
        const monthRows = rows.map((r, i) => {
            const pct  = subtotal > 0 ? Math.round((r.val / subtotal) * 100) : 0;
            const barW = Math.min(100, Math.round((r.val / maxRow) * 100));
            return `<tr style="background:${i%2===0?'white':'#FAFAF8'};">
                <td style="padding:5px 8px;font-size:11px;font-weight:600;color:#374151;border-bottom:1px solid #F3F4F6;width:100px;">${r.bulan}</td>
                <td style="padding:5px 8px;border-bottom:1px solid #F3F4F6;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="flex:1;max-width:120px;background:#E5E7EB;height:5px;border-radius:3px;overflow:hidden;">
                            <div style="width:${barW}%;height:100%;background:${r.val>0?'#1E2A3A':'#E5E7EB'};border-radius:3px;"></div>
                        </div>
                        <span style="font-size:11px;font-weight:700;color:${r.val>0?'#1E2A3A':'#9CA3AF'};min-width:120px;">${r.val>0?fRp(r.val):'—'}</span>
                    </div>
                </td>
                <td style="padding:5px 8px;text-align:right;border-bottom:1px solid #F3F4F6;font-size:11px;font-weight:600;color:${r.val>0?'#374151':'#9CA3AF'};width:55px;">${r.val>0?pct+'%':'—'}</td>
            </tr>`;
        }).join('');

        return `<div class="periode-block" style="margin-bottom:28px;">
            <div style="background:#1E2A3A;padding:10px 20px;border-radius:6px 6px 0 0;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:13px;font-weight:700;color:white;font-family:'Times New Roman',serif;">${periode.label}</span>
                <span style="font-size:11px;color:#9CA3AF;">Subtotal Valid: <strong style="color:#4ade80;font-size:13px;">${fRp(subtotal)}</strong></span>
            </div>
            <!-- BAR CHART -->
            <div style="background:#F9FAFB;border:1px solid #E5E7EB;border-top:none;padding:12px 16px;">
                <img src="${imgSrc}" style="width:100%;max-height:170px;object-fit:contain;display:block;border-radius:4px;">
            </div>
            <!-- TABEL BULANAN (tanpa subtotal di tfoot, dipindah ke bawah) -->
            <table style="width:100%;border-collapse:collapse;border:1px solid #E5E7EB;border-top:none;font-family:'Times New Roman',Times,serif;">
                <thead><tr style="background:#F3F4F6;">
                    <th style="padding:6px 8px;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;text-align:left;">Bulan</th>
                    <th style="padding:6px 8px;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;text-align:left;">Pendapatan</th>
                    <th style="padding:6px 8px;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;text-align:right;">% Kontribusi</th>
                </tr></thead>
                <tbody>${monthRows}</tbody>
            </table>
            <!-- TABEL DETAIL TRANSAKSI -->
            ${detailTableHTML}
            <!-- REKAP METODE -->
            ${metodeHTML}
            <!-- SUBTOTAL PERIODE - PALING BAWAH BLOK -->
            <div style="background:#1E2A3A;padding:12px 20px;border-radius:0 0 6px 6px;display:flex;justify-content:space-between;align-items:center;margin-top:12px;">
                <span style="font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.08em;font-family:'Times New Roman',serif;">
                    Subtotal Periode — ${periode.label}
                </span>
                <span style="font-size:18px;font-weight:800;color:#4ade80;font-family:'Times New Roman',serif;">${fRp(subtotal)}</span>
            </div>
        </div>`;
    }).join('');

    return `<div class="laporan-container" style="font-family:'Times New Roman',Times,serif;max-width:1000px;margin:0 auto;background:white;border:1px solid #C8C8C8;">
    ${buildKopHTML('Laporan Pendapatan Project',`Rekap pendapatan pembayaran termin valid — ${typeLabel}`,type==='tahunan'?'Per Tahun':'Per Rentang Bulan',PHP_TODAY)}
    <!-- META LAPORAN -->
    <div style="padding:12px 28px;background:#F9FAFB;border-bottom:1.5px solid #E5E7EB;display:flex;gap:28px;align-items:center;">
        <div style="font-size:10px;color:#374151;font-family:'Times New Roman',serif;">Periode: <strong style="color:#1E2A3A;">${typeLabel}</strong></div>
        <div style="font-size:10px;color:#374151;font-family:'Times New Roman',serif;">Jenis: <strong style="color:#1E2A3A;">${type==='tahunan'?'Per Tahun':'Per Rentang Bulan'}</strong></div>
        <div style="font-size:10px;color:#374151;font-family:'Times New Roman',serif;">Dicetak: <strong style="color:#1E2A3A;">${PHP_TODAY}</strong></div>
        <div style="font-size:10px;color:#374151;font-family:'Times New Roman',serif;">Sumber: <strong style="color:#166534;">Pembayaran Berstatus Valid</strong></div>
    </div>
    <!-- BODY -->
    <div style="padding:20px 28px 24px;">
        ${periodeBlocks}
        <!-- GRAND TOTAL BOX -->
        <div style="padding:18px 24px;background:linear-gradient(135deg,#1E2A3A 0%,#2D3F52 100%);border-radius:8px;display:flex;justify-content:space-between;align-items:center;margin-top:8px;">
            <div>
                <div style="font-size:10px;color:#9CA3AF;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px;font-family:'Times New Roman',serif;">Grand Total Pendapatan</div>
                <div style="font-size:12px;color:#D1D5DB;font-family:'Times New Roman',serif;">${typeLabel}</div>
                <div style="font-size:10px;color:#9CA3AF;margin-top:2px;font-family:'Times New Roman',serif;">${periodeList.length} periode · hanya pembayaran berstatus valid</div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:28px;font-weight:800;color:#4ade80;font-family:'Times New Roman',serif;">${fRp(grandTotal)}</div>
                <div style="font-size:10px;color:#9CA3AF;margin-top:2px;">${allFilteredPayments.length} transaksi</div>
            </div>
        </div>
    </div>
    <!-- FOOTER -->
    <div style="background:#1E2A3A;padding:9px 28px;display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:9px;color:#9CA3AF;font-family:'Times New Roman',Times,serif;">PT Kawan Kita Solusindo — Laporan Pendapatan</span>
        <span style="font-size:9px;color:#9CA3AF;font-family:'Times New Roman',Times,serif;">Dicetak: ${PHP_TODAY}</span>
    </div>
    </div>`;
}

/* ─── KEYBOARD CLOSE ─── */
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        if (document.getElementById('exportPendapatanOverlay').style.display !== 'none') closeExportPendapatan();
        else if (document.getElementById('exportLaporanOverlay').style.display !== 'none') closeExportLaporan();
        else closeBayarModal();
    }
});
</script>
@endpush