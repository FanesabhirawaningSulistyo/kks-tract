@extends('layouts.master')
@section('title', 'Detail Pembayaran — ' . $projek->nama_projek)
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/master-data-pembayaran.css') }}">
<style>
    .detail-page-wrapper { padding: 0 0 40px 0; }
    .detail-back-bar { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
    .detail-back-btn { display:inline-flex; align-items:center; gap:8px; padding:8px 18px; background:transparent; border:1.5px solid var(--ink-300,#D1D5DB); border-radius:8px; color:var(--ink-600,#4B5563); font-size:13.5px; font-weight:500; text-decoration:none; transition:all .2s; }
    .detail-back-btn:hover { border-color:var(--p1,#4F46E5); color:var(--p1,#4F46E5); background:rgba(79,70,229,.06); text-decoration:none; }
    .detail-page-title { font-size:20px; font-weight:700; color:var(--ink-900,#111827); margin:0; }
    .project-info-card { background:linear-gradient(135deg,#1E2A3A 0%,#2D3F55 100%); border-radius:16px; padding:28px 32px; margin-bottom:24px; color:#fff; position:relative; overflow:hidden; }
    .project-info-card::before { content:''; position:absolute; top:-40px; right:-40px; width:180px; height:180px; background:rgba(255,255,255,.04); border-radius:50%; }
    .project-info-card::after  { content:''; position:absolute; bottom:-60px; right:60px; width:240px; height:240px; background:rgba(255,255,255,.03); border-radius:50%; }
    .pic-header { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
    .pic-icon-title { display:flex; align-items:center; gap:14px; }
    .pic-icon { width:48px; height:48px; background:rgba(255,255,255,.15); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0; }
    .pic-nama { font-size:20px; font-weight:700; margin:0 0 4px 0; line-height:1.2; }
    .pic-sub { font-size:13px; color:rgba(255,255,255,.65); margin:0; }
    .pic-actions { display:flex; gap:10px; flex-wrap:wrap; }
    .btn-cetak-riwayat { display:inline-flex; align-items:center; gap:8px; padding:8px 18px; background:rgba(255,255,255,.15); border:1.5px solid rgba(255,255,255,.3); border-radius:8px; color:#fff; font-size:13.5px; font-weight:500; cursor:pointer; transition:all .2s; white-space:nowrap; }
    .btn-cetak-riwayat:hover { background:rgba(255,255,255,.25); border-color:rgba(255,255,255,.5); }
    .pic-stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; }
    .pic-stat-item { background:rgba(255,255,255,.08); border-radius:10px; padding:14px 16px; }
    .pic-stat-label { font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:.6px; color:rgba(255,255,255,.55); margin-bottom:6px; }
    .pic-stat-value { font-size:16px; font-weight:700; color:#fff; line-height:1; }
    .pic-stat-value.sisa-value { color:#FCA5A5; }
    .pic-stat-value.lunas-value { color:#86EFAC; }
    .pic-progress-track { height:5px; background:rgba(255,255,255,.15); border-radius:99px; overflow:hidden; margin-top:6px; }
    .pic-progress-fill { height:100%; background:linear-gradient(90deg,#818CF8,#A78BFA); border-radius:99px; transition:width .6s ease; }
    .pic-status-badge { display:inline-flex; align-items:center; gap:6px; padding:4px 12px; border-radius:20px; font-size:11.5px; font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-top:4px; }
    .pic-status-badge.inprogress { background:rgba(251,191,36,.2); color:#FCD34D; }
    .pic-status-badge.done       { background:rgba(52,211,153,.2); color:#6EE7B7; }
    .pic-status-badge.pending    { background:rgba(156,163,175,.2); color:#D1D5DB; }
    .pic-status-badge.cancelled  { background:rgba(248,113,113,.2); color:#FCA5A5; }
    .detail-two-col { display:grid; grid-template-columns:420px 1fr; gap:24px; align-items:start; }
    .form-card { background:#fff; border-radius:14px; border:1.5px solid var(--ink-100,#F3F4F6); overflow:hidden; position:sticky; top:20px; }
    .form-card-header { background:linear-gradient(135deg,#F0F3FF 0%,#E8EBFF 100%); padding:18px 22px; border-bottom:1.5px solid #DDE2FF; display:flex; align-items:center; gap:10px; }
    .form-card-header i { font-size:18px; color:var(--p1,#4F46E5); }
    .form-card-title { font-size:14px; font-weight:700; color:var(--p1,#4F46E5); margin:0; }
    .form-card-body { padding:22px; }
    .form-group-dp { margin-bottom:16px; }
    .form-label-dp { display:block; font-size:11.5px; font-weight:600; color:var(--ink-500,#6B7280); text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; }
    .form-label-dp span.req { color:#EF4444; }
    .input-rp-wrap { display:flex; align-items:center; border:1.5px solid var(--ink-200,#E5E7EB); border-radius:9px; overflow:hidden; transition:border-color .2s; }
    .input-rp-wrap:focus-within { border-color:var(--p1,#4F46E5); box-shadow:0 0 0 3px rgba(79,70,229,.1); }
    .rp-prefix { padding:0 14px; background:#F9FAFB; font-size:13px; font-weight:600; color:var(--ink-500,#6B7280); border-right:1.5px solid var(--ink-200,#E5E7EB); height:42px; display:flex; align-items:center; white-space:nowrap; }
    .input-dp { flex:1; border:none; outline:none; padding:0 14px; height:42px; font-size:14px; color:var(--ink-900,#111827); background:transparent; }
    .input-dp::placeholder { color:var(--ink-400,#9CA3AF); }
    .input-maks { font-size:11.5px; color:var(--ink-400,#9CA3AF); margin-top:5px; }
    .input-maks span { font-weight:600; color:var(--ink-600,#4B5563); }
    .input-field-dp { width:100%; border:1.5px solid var(--ink-200,#E5E7EB); border-radius:9px; padding:0 14px; height:42px; font-size:13.5px; color:var(--ink-900,#111827); background:#fff; outline:none; transition:border-color .2s; box-sizing:border-box; }
    .input-field-dp:focus { border-color:var(--p1,#4F46E5); box-shadow:0 0 0 3px rgba(79,70,229,.1); }
    /* ── UPLOAD BUKTI ── */
    .upload-bukti-wrap { border:1.5px dashed var(--ink-300,#D1D5DB); border-radius:9px; padding:14px 16px; background:#FAFBFF; transition:border-color .2s; cursor:pointer; }
    .upload-bukti-wrap:hover, .upload-bukti-wrap.drag-over { border-color:var(--p1,#4F46E5); background:rgba(79,70,229,.04); }
    .upload-bukti-inner { display:flex; align-items:center; gap:10px; }
    .upload-bukti-icon { font-size:24px; color:var(--ink-400); flex-shrink:0; }
    .upload-bukti-text .upload-title { font-size:13px; font-weight:600; color:var(--ink-700); }
    .upload-bukti-text .upload-sub { font-size:11px; color:var(--ink-400); margin-top:2px; }
    .upload-img-preview-wrap { display:none; margin-top:10px; text-align:center; }
    .upload-img-preview-wrap.show { display:block; }
    .upload-img-preview { max-width:100%; max-height:160px; border-radius:7px; border:1.5px solid rgba(79,70,229,.2); object-fit:contain; cursor:default; }
    .upload-bukti-preview { display:none; margin-top:10px; padding:8px 12px; background:rgba(79,70,229,.06); border-radius:7px; border:1px solid rgba(79,70,229,.15); align-items:center; gap:8px; font-size:12px; color:var(--p1,#4F46E5); font-weight:600; }
    .upload-bukti-preview.show { display:flex; }
    .btn-remove-bukti { background:none; border:none; color:#EF4444; cursor:pointer; padding:2px 4px; font-size:14px; display:flex; align-items:center; margin-left:auto; }
    .input-file-hidden { display:none; }
    .btn-simpan-dp { width:100%; padding:11px 20px; background:linear-gradient(135deg,var(--p1,#4F46E5) 0%,#6366F1 100%); border:none; border-radius:9px; color:#fff; font-size:14px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:all .2s; margin-top:4px; }
    .btn-simpan-dp:hover:not(:disabled) { opacity:.9; transform:translateY(-1px); box-shadow:0 4px 14px rgba(79,70,229,.35); }
    .btn-simpan-dp:disabled { opacity:.55; cursor:not-allowed; }
    .lunas-notice { text-align:center; padding:24px; color:var(--ink-500,#6B7280); font-size:14px; }
    .lunas-notice i { font-size:36px; color:#10B981; display:block; margin-bottom:8px; }
    .lunas-notice strong { color:#10B981; }
    /* ── RIWAYAT CARD ── */
    .riwayat-card { background:#fff; border-radius:14px; border:1.5px solid var(--ink-100,#F3F4F6); overflow:hidden; }
    .riwayat-card-header { padding:18px 22px; border-bottom:1.5px solid var(--ink-100,#F3F4F6); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; }
    .rh-left { display:flex; align-items:center; gap:10px; }
    .rh-left i { font-size:18px; color:var(--p1,#4F46E5); }
    .rh-title { font-size:14px; font-weight:700; color:var(--ink-800,#1F2937); margin:0; }
    .rh-count { display:inline-flex; align-items:center; justify-content:center; min-width:24px; height:24px; padding:0 7px; background:var(--p1,#4F46E5); color:#fff; border-radius:99px; font-size:11.5px; font-weight:700; }
    .rh-summary { display:flex; gap:16px; flex-wrap:wrap; }
    .rh-sum-item { font-size:12px; color:var(--ink-500,#6B7280); }
    .rh-sum-item strong { color:var(--ink-800,#1F2937); }
    .rh-sum-item.valid strong { color:#059669; }
    .rh-sum-item.draft strong { color:#D97706; }
    /* ── RIWAYAT TABLE ── */
    .riwayat-table-wrap { overflow-x:auto; }
    .riwayat-tbl { width:100%; border-collapse:collapse; font-size:13px; table-layout:fixed; }
    .riwayat-tbl thead tr { background:#F9FAFB; }
    .riwayat-tbl thead th { padding:10px 8px; text-align:left; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.6px; color:var(--ink-500,#6B7280); border-bottom:1.5px solid var(--ink-100,#F3F4F6); white-space:nowrap; overflow:hidden; }
    .riwayat-tbl tbody tr { border-bottom:1px solid var(--ink-50,#F9FAFB); transition:background .15s; }
    .riwayat-tbl tbody tr:hover { background:#FAFBFF; }
    .riwayat-tbl tbody tr:last-child { border-bottom:none; }
    .riwayat-tbl td { padding:8px 8px; vertical-align:middle; color:var(--ink-700,#374151); overflow:hidden; }
    /* ── Column widths — fixed layout ── */
    .col-no      { width:36px;  text-align:center; }
    .col-nominal { width:150px; }
    .col-sisa    { width:120px; }
    .col-tgl     { width:90px; }
    .col-petugas { width:90px; }
    .col-metode  { width:90px; }
    .col-bukti   { width:52px;  text-align:center; }
    .col-status  { width:90px; }
    .col-aksi    { width:44px;  text-align:center; }
    .td-kode { font-size:10px; color:var(--ink-400,#9CA3AF); font-family:monospace; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; }
    .td-nominal { font-weight:700; color:var(--ink-900,#111827); white-space:nowrap; }
    .td-sisa { font-weight:600; white-space:nowrap; }
    .td-sisa.merah { color:#EF4444; }
    .td-sisa.hijau { color:#10B981; }
    .td-text-sm { font-size:12px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; }
    /* Bukti — compact thumbnail */
    .bukti-thumb { width:32px; height:32px; border-radius:4px; object-fit:cover; border:1.5px solid var(--ink-200); cursor:pointer; transition:transform .15s; display:block; margin:0 auto; }
    .bukti-thumb:hover { transform:scale(1.15); }
    .btn-upload-bukti-row { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:6px; border:1.5px dashed var(--ink-300); background:#FAFBFF; color:var(--ink-500); font-size:13px; cursor:pointer; transition:all .2s; }
    .btn-upload-bukti-row:hover { border-color:var(--p1,#4F46E5); color:var(--p1,#4F46E5); background:rgba(79,70,229,.05); }
    .bukti-pdf-link { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:6px; border:1px solid rgba(79,70,229,.2); background:rgba(79,70,229,.06); color:var(--p1,#4F46E5); font-size:14px; text-decoration:none; transition:all .2s; }
    .bukti-pdf-link:hover { background:rgba(79,70,229,.12); }
    /* Status select */
    .status-sel { border:1.5px solid; border-radius:6px; padding:4px 20px 4px 7px; font-size:11px; font-weight:600; cursor:pointer; outline:none; -webkit-appearance:none; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 5px center; transition:all .2s; width:100%; }
    .status-sel.s-draft { background-color:#FFFBEB; border-color:#FDE68A; color:#92400E; }
    .status-sel.s-valid { background-color:#ECFDF5; border-color:#A7F3D0; color:#065F46; }
    .status-sel.s-batal { background-color:#FEF2F2; border-color:#FECACA; color:#991B1B; }
    .btn-print-struk { background:none; border:1.5px solid var(--ink-200,#E5E7EB); border-radius:7px; width:30px; height:30px; display:inline-flex; align-items:center; justify-content:center; color:var(--ink-500,#6B7280); cursor:pointer; transition:all .2s; }
    .btn-print-struk:hover { border-color:var(--p1,#4F46E5); color:var(--p1,#4F46E5); background:rgba(79,70,229,.06); }
    .riwayat-empty { text-align:center; padding:48px 24px; color:var(--ink-400,#9CA3AF); }
    .riwayat-empty i { font-size:40px; display:block; margin-bottom:10px; }
    /* TOAST */
    .dp-toast { position:fixed; bottom:28px; right:28px; padding:12px 18px; border-radius:10px; font-size:13.5px; font-weight:500; color:#fff; z-index:9999; box-shadow:0 8px 24px rgba(0,0,0,.18); display:flex; align-items:center; gap:10px; opacity:0; transform:translateY(14px); transition:all .28s cubic-bezier(.34,1.56,.64,1); pointer-events:none; }
    .dp-toast.show { opacity:1; transform:translateY(0); }
    .dp-toast.success { background:#059669; }
    .dp-toast.error   { background:#DC2626; }
    .dp-toast.info    { background:var(--p1,#4F46E5); }
    /* LIGHTBOX */
    .lightbox-overlay { position:fixed; inset:0; background:rgba(0,0,0,.88); z-index:99999; display:none; align-items:center; justify-content:center; }
    .lightbox-overlay.open { display:flex; }
    .lightbox-img { max-width:90vw; max-height:90vh; border-radius:8px; box-shadow:0 24px 64px rgba(0,0,0,.5); }
    .lightbox-close { position:absolute; top:20px; right:24px; background:rgba(255,255,255,.15); border:1.5px solid rgba(255,255,255,.3); border-radius:50%; width:36px; height:36px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:20px; cursor:pointer; }
    /* MODAL CETAK */
    .cetak-overlay { position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:1050; display:flex; align-items:center; justify-content:center; opacity:0; pointer-events:none; transition:opacity .25s; }
    .cetak-overlay.open { opacity:1; pointer-events:auto; }
    .cetak-modal { background:#fff; border-radius:14px; width:90%; max-width:780px; max-height:85vh; display:flex; flex-direction:column; transform:scale(.96); transition:transform .25s; overflow:hidden; }
    .cetak-overlay.open .cetak-modal { transform:scale(1); }
    .cetak-modal-header { background:#1E2A3A; padding:16px 22px; display:flex; align-items:center; justify-content:space-between; color:#fff; flex-shrink:0; }
    .cetak-modal-title { font-size:15px; font-weight:700; margin:0; }
    .cetak-modal-close { background:rgba(255,255,255,.15); border:none; border-radius:6px; width:30px; height:30px; color:#fff; font-size:16px; cursor:pointer; display:flex; align-items:center; justify-content:center; }
    .cetak-modal-body { flex:1; overflow-y:auto; padding:20px 24px; }
    .cetak-modal-footer { padding:14px 22px; border-top:1px solid #F3F4F6; display:flex; justify-content:flex-end; gap:10px; flex-shrink:0; }
    .btn-print-now { padding:9px 22px; background:linear-gradient(135deg,#1E2A3A,#2D3F55); border:none; border-radius:8px; color:#fff; font-size:13.5px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:8px; }
    .btn-cancel-cetak { padding:9px 22px; background:#fff; border:1.5px solid #E5E7EB; border-radius:8px; color:#374151; font-size:13.5px; font-weight:500; cursor:pointer; }
    @media (max-width:960px) { .detail-two-col { grid-template-columns:1fr; } .form-card { position:static; } .pic-stats-grid { grid-template-columns:repeat(2,1fr); } }
    @media (max-width:576px)  { .pic-stats-grid { grid-template-columns:1fr 1fr; } .pic-header { flex-direction:column; } .project-info-card { padding:20px; } }
</style>
@endpush
@section('content')
<div class="detail-page-wrapper">
    {{-- ── BACK + TITLE ── --}}
    <div class="detail-back-bar">
        <a href="{{ route('pembayaran-projek.index') }}" class="detail-back-btn">
            <i class='bx bx-arrow-back'></i> Kembali
        </a>
        <h1 class="detail-page-title">Detail Pembayaran Project</h1>
    </div>
    {{-- ── PROJECT INFO CARD ── --}}
    <div class="project-info-card">
        <div class="pic-header">
            <div class="pic-icon-title">
                <div class="pic-icon"><i class='bx bx-receipt'></i></div>
                <div>
                    <p class="pic-nama">{{ $projek->nama_projek }}</p>
                    <p class="pic-sub">
                        {{ optional($projek->perusahaan)->nama_perwakilan ?? '—' }}
                        @if(optional($projek->perusahaan)->nama_perusahaan)
                            — {{ $projek->perusahaan->nama_perusahaan }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="pic-actions">
                <button class="btn-cetak-riwayat" onclick="bukaModalCetak()">
                    <i class='bx bx-printer'></i> Cetak Riwayat
                </button>
            </div>
        </div>
        <div class="pic-stats-grid">
            <div class="pic-stat-item">
                <div class="pic-stat-label">Kategori Project</div>
                <div class="pic-stat-value" style="font-size:14px">{{ optional($projek->kategoriProjek)->nama_kategori ?? '—' }}</div>
            </div>
            <div class="pic-stat-item">
                <div class="pic-stat-label">Nominal Kontrak</div>
                <div class="pic-stat-value">Rp {{ number_format($projek->nominal_projek, 0, ',', '.') }}</div>
            </div>
            <div class="pic-stat-item">
                <div class="pic-stat-label">Sisa Tanggungan</div>
                <div class="pic-stat-value {{ $projek->sisa_tanggungan <= 0 ? 'lunas-value' : 'sisa-value' }}" id="sisa-tanggungan-display">
                    @if($projek->sisa_tanggungan <= 0) ✓ LUNAS
                    @else Rp {{ number_format($projek->sisa_tanggungan, 0, ',', '.') }}
                    @endif
                </div>
            </div>
            <div class="pic-stat-item">
                @php
                    $progressBayar = $projek->nominal_projek > 0
                        ? min(100, round(($totalValid / $projek->nominal_projek) * 100, 1))
                        : 0;
                @endphp
                <div class="pic-stat-label">Progres Pembayaran</div>
                <div class="pic-stat-value" style="font-size:20px;" id="progress-bayar-display">
                    {{ $progressBayar }}%
                </div>
                <div style="margin-top:8px;">
                    <div style="font-size:10px;color:rgba(255,255,255,.5);">
                        Rp {{ number_format($totalValid,0,',','.') }} dari Rp {{ number_format($projek->nominal_projek,0,',','.') }}
                    </div>
                    <div class="pic-progress-track" style="margin-top:5px;">
                        <div class="pic-progress-fill" id="progress-bayar-fill" style="width:{{ $progressBayar }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ── TWO COLUMN ── --}}
    <div class="detail-two-col">
        {{-- ── FORM INPUT PEMBAYARAN ── --}}
        <div class="form-card" id="formCard">
            <div class="form-card-header">
                <i class='bx bx-plus-circle'></i>
                <h3 class="form-card-title">Input Nominal Pembayaran</h3>
            </div>
            @if($projek->sisa_tanggungan <= 0)
            <div class="lunas-notice">
                <i class='bx bx-check-circle'></i>
                Project ini sudah <strong>LUNAS</strong>.<br>Tidak ada pembayaran yang perlu dilakukan.
            </div>
            @else
            <div class="form-card-body">
                <div class="form-group-dp">
                    <label class="form-label-dp">Nominal Pembayaran <span class="req">*</span></label>
                    <div class="input-rp-wrap">
                        <span class="rp-prefix">Rp</span>
                        <input type="text" id="inputNominal" class="input-dp" placeholder="0"
                            oninput="formatRibuan(this)"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                    </div>
                    <div class="input-maks">Maks: <span id="lblMaks">Rp {{ number_format($projek->sisa_tanggungan, 0, ',', '.') }}</span></div>
                </div>
                <div class="form-group-dp">
                    <label class="form-label-dp">Tanggal Bayar <span class="req">*</span></label>
                    <input type="date" id="inputTanggal" class="input-field-dp" value="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group-dp">
                    <label class="form-label-dp">Metode Pembayaran <span class="req">*</span></label>
                    <select id="inputMetode" class="input-field-dp">
                        <option value="">— Pilih Metode —</option>
                        @foreach($metodes as $m)
                            <option value="{{ $m->id_metode_pembayaran }}">{{ $m->nama_metode }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- UPLOAD BUKTI dengan Preview --}}
                <div class="form-group-dp">
                    <label class="form-label-dp">
                        Bukti Pembayaran
                        <span style="color:var(--ink-400);font-weight:400;text-transform:none;font-size:11px;">(Opsional)</span>
                    </label>
                    <div class="upload-bukti-wrap" id="uploadWrap"
                         onclick="document.getElementById('inputBukti').click()"
                         ondragover="event.preventDefault();this.classList.add('drag-over')"
                         ondragleave="this.classList.remove('drag-over')"
                         ondrop="handleDrop(event)">
                        <div class="upload-bukti-inner" id="uploadInner">
                            <i class='bx bx-cloud-upload upload-bukti-icon' id="uploadIcon"></i>
                            <div class="upload-bukti-text">
                                <div class="upload-title" id="uploadTitle">Klik atau seret file ke sini</div>
                                <div class="upload-sub">JPG, PNG, PDF — maks. 5 MB</div>
                            </div>
                        </div>
                        <div class="upload-img-preview-wrap" id="imgPreviewWrap">
                            <img id="imgPreviewEl" class="upload-img-preview" src="" alt="Preview Bukti"
                                 onclick="event.stopPropagation()">
                        </div>
                        <div class="upload-bukti-preview" id="buktiPreview">
                            <i class='bx bx-file-blank' style="font-size:16px;" id="buktiFileIcon"></i>
                            <span id="buktiFileName">—</span>
                            <button type="button" class="btn-remove-bukti" onclick="event.stopPropagation();removeBukti()">
                                <i class='bx bx-x'></i>
                            </button>
                        </div>
                    </div>
                    <input type="file" id="inputBukti" class="input-file-hidden"
                           accept="image/jpeg,image/png,image/jpg,application/pdf"
                           onchange="onBuktiChange(this)">
                </div>
                <button class="btn-simpan-dp" id="btnSimpan" onclick="simpanPembayaran()">
                    <i class='bx bx-save'></i> Simpan Pembayaran
                </button>
            </div>
            @endif
        </div>
        {{-- ── RIWAYAT PEMBAYARAN ── --}}
        <div class="riwayat-card">
            <div class="riwayat-card-header">
                <div class="rh-left">
                    <i class='bx bx-history'></i>
                    <h3 class="rh-title">Riwayat Pembayaran</h3>
                    <span class="rh-count" id="rh-count">{{ $riwayat->count() }}</span>
                </div>
                <div class="rh-summary">
                    <div class="rh-sum-item valid">
                        Terbayar (Valid): <strong>Rp {{ number_format($totalValid, 0, ',', '.') }}</strong>
                    </div>
                    @if($totalDraft > 0)
                    <div class="rh-sum-item draft">
                        Draft: <strong>Rp {{ number_format($totalDraft, 0, ',', '.') }}</strong>
                    </div>
                    @endif
                </div>
            </div>
            <div class="riwayat-table-wrap" id="riwayatTableWrap">
                @if($riwayat->isEmpty())
                <div class="riwayat-empty">
                    <i class='bx bx-receipt'></i>
                    Belum ada riwayat pembayaran.
                </div>
                @else
                <table class="riwayat-tbl">
                    <thead>
                        <tr>
                            <th class="col-no">#</th>
                            <th class="col-nominal">Nominal Bayar</th>
                            <th class="col-sisa">Sisa</th>
                            <th class="col-tgl">Tanggal</th>
                            <th class="col-petugas">Petugas</th>
                            <th class="col-metode">Metode</th>
                            <th class="col-bukti">Bukti</th>
                            <th class="col-status">Status</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="riwayatTbody">
                        @foreach($riwayat as $idx => $item)
                        <tr id="row-{{ $item->id_pembayaran }}">
                            <td class="col-no" style="text-align:center;color:var(--ink-400);">{{ $idx + 1 }}</td>
                            <td class="col-nominal">
                                <div class="td-nominal">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</div>
                                <span class="td-kode">{{ $item->kode_pembayaran }}</span>
                            </td>
                            <td class="col-sisa">
                                @php $sisa = $sisaMap[$item->id_pembayaran] ?? 0; @endphp
                                <span class="td-sisa {{ $sisa <= 0 ? 'hijau' : 'merah' }}">
                                    Rp {{ number_format($sisa, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="col-tgl">
                                <span class="td-text-sm">
                                @php
                                    $tgl = $item->tanggal_bayar instanceof \Carbon\Carbon
                                        ? $item->tanggal_bayar
                                        : \Carbon\Carbon::parse($item->tanggal_bayar);
                                @endphp
                                {{ $tgl->format('d M Y') }}
                                </span>
                            </td>
                            <td class="col-petugas">
                                <span class="td-text-sm">{{ optional($item->petugas)->nama ?? '—' }}</span>
                            </td>
                            <td class="col-metode">
                                <span class="td-text-sm">{{ optional($item->metode)->nama_metode ?? '—' }}</span>
                            </td>
                            <td class="col-bukti">
                                @if($item->bukti_bayar)
                                    @php $ext = strtolower(pathinfo($item->bukti_bayar, PATHINFO_EXTENSION)); @endphp
                                    @if(in_array($ext, ['jpg','jpeg','png','webp']))
                                        <img src="{{ asset('storage/' . $item->bukti_bayar) }}"
                                             class="bukti-thumb"
                                             onclick="openLightbox('{{ asset('storage/' . $item->bukti_bayar) }}')"
                                             alt="Bukti Pembayaran">
                                    @else
                                        <a href="{{ asset('storage/' . $item->bukti_bayar) }}" target="_blank" class="bukti-pdf-link" title="Lihat PDF">
                                            <i class='bx bxs-file-pdf'></i>
                                        </a>
                                    @endif
                                @else
                                    <label class="btn-upload-bukti-row" title="Upload Bukti" onclick="event.stopPropagation()">
                                        <i class='bx bx-upload'></i>
                                        <input type="file" accept="image/jpeg,image/png,image/jpg,application/pdf"
                                               style="display:none"
                                               onchange="uploadBuktiRow(this, {{ $item->id_pembayaran }})">
                                    </label>
                                @endif
                            </td>
                            <td class="col-status">
                                <select class="status-sel s-{{ $item->status }}"
                                    data-id="{{ $item->id_pembayaran }}"
                                    onchange="updateStatus(this)">
                                    <option value="draft" {{ $item->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="valid" {{ $item->status === 'valid' ? 'selected' : '' }}>Valid</option>
                                    <option value="batal" {{ $item->status === 'batal' ? 'selected' : '' }}>Batal</option>
                                </select>
                            </td>
                            <td class="col-aksi" style="text-align:center;">
                                <button class="btn-print-struk" onclick="cetakStruk({{ $item->id_pembayaran }})" title="Cetak A4">
                                    <i class='bx bx-printer'></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>
{{-- TOAST --}}
<div class="dp-toast" id="dpToast"><i class='bx bx-check-circle'></i> <span id="dpToastMsg"></span></div>
{{-- LIGHTBOX --}}
<div class="lightbox-overlay" id="lightboxOverlay" onclick="closeLightbox()">
    <button class="lightbox-close" onclick="closeLightbox()"><i class='bx bx-x'></i></button>
    <img src="" class="lightbox-img" id="lightboxImg" onclick="event.stopPropagation()">
</div>
{{-- MODAL CETAK RIWAYAT --}}
<div class="cetak-overlay" id="cetakOverlay" onclick="tutupModalCetak(event)">
    <div class="cetak-modal">
        <div class="cetak-modal-header">
            <h4 class="cetak-modal-title"><i class='bx bx-printer' style="margin-right:8px"></i>Preview Cetak Riwayat</h4>
            <button class="cetak-modal-close" onclick="tutupModalCetakForce()">&times;</button>
        </div>
        <div class="cetak-modal-body" id="cetakPreviewBody">
            <div style="text-align:center;padding:30px;color:#9CA3AF">
                <i class='bx bx-loader-alt bx-spin' style="font-size:32px;display:block;margin-bottom:10px"></i>Memuat data...
            </div>
        </div>
        <div class="cetak-modal-footer">
            <button class="btn-cancel-cetak" onclick="tutupModalCetakForce()">Tutup</button>
            <button class="btn-print-now" onclick="printRiwayat()">
                <i class='bx bx-printer'></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const PROJEK_ID  = {{ $projek->id_projek }};
const CSRF_TOKEN = '{{ csrf_token() }}';
const SISA_AWAL  = {{ $projek->sisa_tanggungan }};
let sisaTerkini  = SISA_AWAL;
let cetakHtml    = '';
/* ── Helpers ── */
function fRp(n) { return 'Rp ' + Number(n||0).toLocaleString('id-ID'); }
function fDate(str) {
    if (!str) return '—';
    const d = new Date(String(str).split('T')[0]);
    const bln = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    return d.getDate() + ' ' + bln[d.getMonth()] + ' ' + d.getFullYear();
}
function formatRibuan(el) {
    let v = el.value.replace(/\D/g,'');
    el.value = v ? Number(v).toLocaleString('id-ID') : '';
}
function getNominalRaw() {
    return parseInt((document.getElementById('inputNominal')?.value||'0').replace(/\D/g,''))||0;
}
/* ── Toast ── */
function showToast(msg, type='success') {
    const t = document.getElementById('dpToast');
    t.className = 'dp-toast ' + type;
    document.getElementById('dpToastMsg').textContent = msg;
    t.classList.add('show');
    clearTimeout(t._tmr);
    t._tmr = setTimeout(() => t.classList.remove('show'), 3800);
}
/* ── Lightbox ── */
function openLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightboxOverlay').classList.add('open');
}
function closeLightbox() {
    document.getElementById('lightboxOverlay').classList.remove('open');
}
/* ── UPLOAD BUKTI dengan Image Preview ── */
function onBuktiChange(input) {
    if (!input.files?.[0]) return;
    const file = input.files[0];
    if (file.size > 5 * 1024 * 1024) {
        showToast('File melebihi batas 5 MB.', 'error');
        input.value = '';
        return;
    }
    const isImage = /\.(jpg|jpeg|png)$/i.test(file.name) || file.type.startsWith('image/');
    const isPdf   = file.type === 'application/pdf' || /\.pdf$/i.test(file.name);
    document.getElementById('buktiFileName').textContent = file.name;
    document.getElementById('buktiPreview').classList.add('show');
    const fileIcon = document.getElementById('buktiFileIcon');
    if (isPdf) {
        fileIcon.className = 'bx bxs-file-pdf';
        fileIcon.style.color = '#DC2626';
    } else {
        fileIcon.className = 'bx bx-image';
        fileIcon.style.color = '#4F46E5';
    }
    document.getElementById('uploadTitle').textContent = 'File dipilih ✓';
    const icon = document.getElementById('uploadIcon');
    icon.className = 'bx bx-check-circle upload-bukti-icon';
    icon.style.color = '#10B981';
    const imgWrap = document.getElementById('imgPreviewWrap');
    const imgEl   = document.getElementById('imgPreviewEl');
    if (isImage) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imgEl.src = e.target.result;
            imgWrap.classList.add('show');
        };
        reader.readAsDataURL(file);
    } else {
        imgWrap.classList.remove('show');
        imgEl.src = '';
    }
}
function removeBukti() {
    document.getElementById('inputBukti').value = '';
    document.getElementById('buktiPreview').classList.remove('show');
    document.getElementById('imgPreviewWrap').classList.remove('show');
    document.getElementById('imgPreviewEl').src = '';
    document.getElementById('uploadTitle').textContent = 'Klik atau seret file ke sini';
    const icon = document.getElementById('uploadIcon');
    icon.className = 'bx bx-cloud-upload upload-bukti-icon';
    icon.style.color = '';
}
function handleDrop(e) {
    e.preventDefault();
    document.getElementById('uploadWrap').classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (!file) return;
    const dt = new DataTransfer();
    dt.items.add(file);
    const input = document.getElementById('inputBukti');
    input.files = dt.files;
    onBuktiChange(input);
}
/* ── SIMPAN PEMBAYARAN ── */
function simpanPembayaran() {
    const nominal   = getNominalRaw();
    const tanggal   = document.getElementById('inputTanggal')?.value;
    const metode    = document.getElementById('inputMetode')?.value;
    const buktiFile = document.getElementById('inputBukti')?.files[0];
    if (!nominal || nominal <= 0) return showToast('Masukkan nominal pembayaran.', 'error');
    if (nominal > sisaTerkini)    return showToast('Nominal melebihi sisa tanggungan (' + fRp(sisaTerkini) + ').', 'error');
    if (!tanggal)                 return showToast('Pilih tanggal bayar.', 'error');
    if (!metode)                  return showToast('Pilih metode pembayaran.', 'error');
    const btn = document.getElementById('btnSimpan');
    btn.disabled = true;
    btn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Menyimpan...';
    const fd = new FormData();
    fd.append('id_projek',            PROJEK_ID);
    fd.append('jumlah_bayar',         nominal);
    fd.append('tanggal_bayar',        tanggal);
    fd.append('id_metode_pembayaran', metode);
    fd.append('_token',               CSRF_TOKEN);
    if (buktiFile) fd.append('bukti_bayar', buktiFile);
    fetch('/pembayaran-projek', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('Pembayaran berhasil! Kode: ' + data.kode_pembayaran, 'success');
            sisaTerkini = data.sisa_tanggungan;
            const displayEl = document.getElementById('sisa-tanggungan-display');
            const lblMaks   = document.getElementById('lblMaks');
            if (sisaTerkini <= 0) {
                if (displayEl) {
                    displayEl.textContent = '✓ LUNAS';
                    displayEl.className = 'pic-stat-value lunas-value';
                }
                document.getElementById('formCard').innerHTML = `
                    <div class="form-card-header">
                        <i class='bx bx-check-circle' style="color:#10B981"></i>
                        <h3 class="form-card-title" style="color:#10B981">Project Lunas</h3>
                    </div>
                    <div class="lunas-notice">
                        <i class='bx bx-check-circle'></i>
                        Project ini sudah <strong>LUNAS</strong>.
                    </div>`;
            } else {
                if (displayEl) displayEl.textContent = fRp(sisaTerkini);
                if (lblMaks)   lblMaks.textContent   = fRp(sisaTerkini);
            }
            if (document.getElementById('inputNominal')) document.getElementById('inputNominal').value = '';
            removeBukti();
            refreshRiwayat();
        } else {
            showToast(data.message || 'Gagal menyimpan.', 'error');
        }
    })
    .catch(() => showToast('Kesalahan koneksi.', 'error'))
    .finally(() => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-save"></i> Simpan Pembayaran';
        }
    });
}
/* ── Upload Bukti di Baris Riwayat ── */
function uploadBuktiRow(input, idPembayaran) {
    if (!input.files?.[0]) return;
    const file = input.files[0];
    if (file.size > 5 * 1024 * 1024) { showToast('File melebihi 5 MB.', 'error'); return; }
    const fd = new FormData();
    fd.append('bukti_bayar', file);
    fd.append('_token', CSRF_TOKEN);
    fetch('/pembayaran-projek/' + idPembayaran + '/bukti', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('Bukti berhasil diupload!', 'success');
            refreshRiwayat();
        } else {
            showToast(data.message || 'Gagal upload.', 'error');
        }
    })
    .catch(() => showToast('Koneksi error.', 'error'));
}
/* ── Refresh Riwayat AJAX ── */
function refreshRiwayat() {
    fetch('/pembayaran-projek/' + PROJEK_ID + '/riwayat')
    .then(r => r.json())
    .then(data => {
        if (!data.success) return;
        renderRiwayat(data.riwayat);
        const el = document.getElementById('rh-count');
        if (el) el.textContent = data.riwayat.length;
    });
}
function renderRiwayat(list) {
    const wrap = document.getElementById('riwayatTableWrap');
    if (!list || !list.length) {
        wrap.innerHTML = `<div class="riwayat-empty"><i class='bx bx-receipt'></i>Belum ada riwayat pembayaran.</div>`;
        return;
    }
    const stOps = (r) => ['draft','valid','batal'].map(s =>
        `<option value="${s}" ${r.status===s?'selected':''}>${s.charAt(0).toUpperCase()+s.slice(1)}</option>`
    ).join('');
    const buktiCell = (r) => {
        if (r.bukti_url) {
            return /\.(jpg|jpeg|png|webp)$/i.test(r.bukti_url)
                ? `<img src="${r.bukti_url}" class="bukti-thumb" onclick="openLightbox('${r.bukti_url}')" alt="Bukti">`
                : `<a href="${r.bukti_url}" target="_blank" class="bukti-pdf-link" title="Lihat PDF"><i class='bx bxs-file-pdf'></i></a>`;
        }
        return `<label class="btn-upload-bukti-row" title="Upload Bukti">
            <i class='bx bx-upload'></i>
            <input type="file" accept="image/jpeg,image/png,image/jpg,application/pdf" style="display:none"
                   onchange="uploadBuktiRow(this,${r.id_pembayaran})">
        </label>`;
    };
    const rows = list.map((r, i) => `
        <tr id="row-${r.id_pembayaran}">
            <td class="col-no" style="text-align:center;color:#9CA3AF;">${i+1}</td>
            <td class="col-nominal">
                <div class="td-nominal">${fRp(r.jumlah_bayar)}</div>
                <span class="td-kode">${r.kode_pembayaran}</span>
            </td>
            <td class="col-sisa"><span class="td-sisa ${r.sisa_setelah<=0?'hijau':'merah'}">${fRp(r.sisa_setelah)}</span></td>
            <td class="col-tgl"><span class="td-text-sm">${fDate(r.tanggal_bayar)}</span></td>
            <td class="col-petugas"><span class="td-text-sm">${r.nama_petugas}</span></td>
            <td class="col-metode"><span class="td-text-sm">${r.nama_metode}</span></td>
            <td class="col-bukti">${buktiCell(r)}</td>
            <td class="col-status">
                <select class="status-sel s-${r.status}" data-id="${r.id_pembayaran}" onchange="updateStatus(this)">
                    ${stOps(r)}
                </select>
            </td>
            <td class="col-aksi" style="text-align:center;">
                <button class="btn-print-struk" onclick="cetakStruk(${r.id_pembayaran})" title="Cetak A4">
                    <i class='bx bx-printer'></i>
                </button>
            </td>
        </tr>`
    ).join('');
    wrap.innerHTML = `
        <table class="riwayat-tbl">
            <thead>
                <tr>
                    <th class="col-no">#</th>
                    <th class="col-nominal">Nominal Bayar</th>
                    <th class="col-sisa">Sisa</th>
                    <th class="col-tgl">Tanggal</th>
                    <th class="col-petugas">Petugas</th>
                    <th class="col-metode">Metode</th>
                    <th class="col-bukti">Bukti</th>
                    <th class="col-status">Status</th>
                    <th class="col-aksi">Aksi</th>
                </tr>
            </thead>
            <tbody id="riwayatTbody">${rows}</tbody>
        </table>`;
}
/* ── Update Status ── */
function updateStatus(sel) {
    const id         = sel.dataset.id;
    const statusBaru = sel.value;
    const statusLama = (sel.className.match(/s-(\w+)/) || [])[1];
    sel.disabled = true;
    fetch('/pembayaran-projek/' + id + '/status', {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ status: statusBaru })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            sel.className = 'status-sel s-' + statusBaru;
            showToast('Status diubah ke ' + statusBaru, 'success');
            sisaTerkini = data.sisa_tanggungan;
            const de = document.getElementById('sisa-tanggungan-display');
            if (de) {
                if (sisaTerkini <= 0) {
                    de.textContent = '✓ LUNAS';
                    de.className = 'pic-stat-value lunas-value';
                } else {
                    de.textContent = fRp(sisaTerkini);
                    de.className = 'pic-stat-value sisa-value';
                }
            }
            const lm = document.getElementById('lblMaks');
            if (lm) lm.textContent = fRp(sisaTerkini);
            refreshRiwayat();
        } else {
            showToast(data.message || 'Gagal.', 'error');
            sel.value = statusLama;
        }
    })
    .catch(() => {
        showToast('Koneksi error.', 'error');
        sel.value = statusLama;
    })
    .finally(() => { sel.disabled = false; });
}
/* ═══════════════════════════════════════════════
   CETAK STRUK — Format A4 Formal
   ═══════════════════════════════════════════════ */
function cetakStruk(id) {
    fetch('/pembayaran-projek/' + id + '/struk')
    .then(r => r.json())
    .then(data => {
        if (!data.success) return showToast('Gagal memuat data.', 'error');
        const s  = data.struk;
        const SC = {
            valid: { label:'VALID', bg:'#D1FAE5', color:'#065F46' },
            draft: { label:'DRAFT', bg:'#FEF3C7', color:'#92400E' },
            batal: { label:'BATAL', bg:'#FEE2E2', color:'#991B1B' }
        };
        const sc  = SC[s.status] || SC.draft;
        const pct = s.nominal_projek > 0
            ? Math.min(100, Math.round(((s.nominal_projek - s.sisa_setelah) / s.nominal_projek) * 100))
            : 0;
        /* ✅ FIX: gunakan s.perusahaan (nama PT), bukan s.perusahaan_nama (nama perwakilan) */
        const html = `<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8">
<title>Bukti Pembayaran — ${s.kode_pembayaran}</title>
<style>
@page { size:A4; margin:12mm 14mm 0mm 14mm; }
* { box-sizing:border-box; margin:0; padding:0; }
html { height:100%; }
body {
  font-family:'Times New Roman', Georgia, serif;
  font-size:10.5pt;
  color:#1F2937;
  background:white;
  display:flex;
  flex-direction:column;
  min-height:267mm;
}
.content-wrap { flex:1; }
.kop { display:flex; align-items:center; gap:12px; padding-bottom:10px; border-bottom:3px solid #1E2A3A; margin-bottom:6px; }
.kop-logo-img { width:46px; height:46px; object-fit:contain; flex-shrink:0; }
.kop-logo-fb { width:46px; height:46px; border-radius:6px; background:#1E2A3A; display:flex; align-items:center; justify-content:center; color:white; font-size:15px; font-weight:700; flex-shrink:0; }
.kop-co { font-size:14pt; font-weight:700; color:#1E2A3A; }
.kop-sub { font-size:8.5pt; color:#6B7280; margin-top:2px; }
.kop-right { margin-left:auto; text-align:right; }
.doc-label { font-size:7.5pt; text-transform:uppercase; letter-spacing:.08em; color:#9CA3AF; }
.doc-no { font-size:11pt; font-weight:700; color:#1E2A3A; letter-spacing:.04em; }
.doc-title-block { text-align:center; margin:10px 0 12px; }
.doc-title { font-size:14pt; font-weight:700; color:#1E2A3A; text-transform:uppercase; letter-spacing:.06em; }
.doc-sub { font-size:8.5pt; color:#6B7280; margin-top:3px; }
.nominal-box { background:#1E2A3A; border-radius:7px; padding:14px 20px; margin:0 0 14px; display:flex; justify-content:space-between; align-items:center; }
.nom-label { font-size:8pt; color:#9CA3AF; text-transform:uppercase; letter-spacing:.06em; }
.nom-val { font-size:21pt; font-weight:700; color:#F9FAFB; }
.nom-meta { display:flex; gap:24px; margin-top:8px; }
.nom-meta-item label { font-size:7.5pt; color:#9CA3AF; display:block; }
.nom-meta-item span { font-size:9.5pt; font-weight:600; color:#E5E7EB; }
.status-badge { display:inline-block; padding:4px 14px; border-radius:4px; font-size:9pt; font-weight:700; letter-spacing:.04em; }
.sisa-val { font-size:12pt; font-weight:700; }
.prog-section { margin-bottom:12px; }
.sec-label { font-size:8pt; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#6B7280; margin-bottom:6px; padding-bottom:4px; border-bottom:1px solid #E5E7EB; }
.prog-track { background:#E5E7EB; height:8px; border-radius:4px; overflow:hidden; margin:4px 0; }
.prog-fill { height:100%; background:linear-gradient(90deg,#4F46E5,#818CF8); border-radius:4px; }
.prog-lbl { display:flex; justify-content:space-between; font-size:8pt; color:#6B7280; margin-top:3px; }
.two-col { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px; }
.info-box { border:1px solid #E5E7EB; border-radius:5px; padding:12px 14px; }
.info-tbl { width:100%; border-collapse:collapse; }
.info-tbl td { padding:5.5px 7px; font-size:10pt; vertical-align:top; }
.info-tbl td:first-child { color:#6B7280; font-size:9.5pt; width:42%; }
.info-tbl td:nth-child(2) { width:3%; color:#9CA3AF; text-align:center; }
.info-tbl td:last-child { font-weight:600; color:#111827; }
.info-tbl tr:nth-child(even) td { background:#F9FAFB; }
.bukti-section { margin-bottom:14px; padding:10px 14px; border:1px solid #E5E7EB; border-radius:5px; }
.bukti-img { max-height:200px; max-width:100%; border-radius:5px; border:1px solid #E5E7EB; margin-top:8px; display:block; }
.doc-footer {
  margin-top:auto;
  padding:10px 0 10px 0;
  border-top:2px solid #1E2A3A;
  display:flex;
  justify-content:space-between;
  align-items:flex-end;
  background:white;
}
.footer-left { font-size:8pt; color:#9CA3AF; line-height:1.7; }
.ttd { text-align:center; min-width:155px; }
.ttd-label { font-size:8.5pt; color:#6B7280; margin-bottom:4px; }
.stamp-wrap { display:inline-block; width:135px; height:72px; }
.stamp-logo { width:135px; height:72px; object-fit:contain; opacity:0.2; filter:sepia(30%) hue-rotate(195deg) saturate(2) brightness(0.85); mix-blend-mode:multiply; display:block; }
.ttd-line { border-top:1.5px solid #374151; width:155px; margin:4px auto 0; }
.ttd-name { font-size:9pt; font-weight:700; color:#1F2937; margin-top:3px; }
@media print {
  body { print-color-adjust:exact; -webkit-print-color-adjust:exact; min-height:100vh; }
  .doc-footer { position:fixed; bottom:0; left:14mm; right:14mm; margin-top:0; padding-bottom:8px; }
  .content-wrap { padding-bottom:45mm; }
}
</style></head><body>
<div class="content-wrap">
<div class="kop">
  <img class="kop-logo-img" src="${window.location.origin}/assets/img/ttd/logo1.png" alt="KKS"
       onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
  <div class="kop-logo-fb" style="display:none;">KKS</div>
  <div>
    <div class="kop-co">PT KAWAN KITA SOLUSINDO</div>
    <div class="kop-sub">Sistem Manajemen Project — Divisi Keuangan</div>
  </div>
  <div class="kop-right">
    <div class="doc-label">No. Dokumen</div>
    <div class="doc-no">${s.kode_pembayaran}</div>
    <div style="font-size:7.5pt;color:#9CA3AF;margin-top:2px;">Dicetak: ${s.dicetak_pada}</div>
  </div>
</div>
<div class="doc-title-block">
  <div class="doc-title">Bukti Pembayaran Termin</div>
  <div class="doc-sub">Dokumen resmi pembayaran project — PT Kawan Kita Solusindo</div>
</div>
<div class="nominal-box">
  <div>
    <div class="nom-label">Jumlah Dibayarkan</div>
    <div class="nom-val">${fRp(s.jumlah_bayar)}</div>
    <div class="nom-meta">
      <div class="nom-meta-item"><label>Tanggal Bayar</label><span>${fDate(s.tanggal_bayar)}</span></div>
      <div class="nom-meta-item"><label>Metode</label><span>${s.nama_metode}</span></div>
      <div class="nom-meta-item"><label>Petugas</label><span>${s.nama_petugas}</span></div>
    </div>
  </div>
  <div style="text-align:right;">
    <div style="margin-bottom:8px;"><span class="status-badge" style="background:${sc.bg};color:${sc.color};">${sc.label}</span></div>
    <div style="font-size:8pt;color:#9CA3AF;">Sisa Tanggungan</div>
    <div class="sisa-val" style="color:${s.sisa_setelah<=0?'#4ade80':'#FCA5A5'};">
      ${s.sisa_setelah<=0?'&#10003; LUNAS':fRp(s.sisa_setelah)}
    </div>
  </div>
</div>
<div class="prog-section">
  <div class="sec-label">PROGRES PEMBAYARAN</div>
  <div class="prog-track"><div class="prog-fill" style="width:${pct}%;"></div></div>
  <div class="prog-lbl"><span>Progres pembayaran termin</span><span>${pct}% terbayar dari nilai kontrak</span></div>
</div>
<div class="two-col">
  <div class="info-box">
    <div class="sec-label">INFORMASI PROJECT</div>
    <table class="info-tbl">
      <tr><td>Nama Project</td><td>:</td><td>${s.nama_projek}</td></tr>
      <tr><td>Perusahaan</td><td>:</td><td>${s.perusahaan}</td></tr>
      <tr><td>Perwakilan</td><td>:</td><td>${s.perusahaan_nama}</td></tr>
      <tr><td>Kategori</td><td>:</td><td>${s.kategori}</td></tr>
      <tr><td>Nilai Kontrak</td><td>:</td><td>${fRp(s.nominal_projek)}</td></tr>
    </table>
  </div>
  <div class="info-box">
    <div class="sec-label">INFORMASI PEMBAYARAN</div>
    <table class="info-tbl">
      <tr><td>Kode</td><td>:</td><td style="font-family:monospace;font-size:9pt;">${s.kode_pembayaran}</td></tr>
      <tr><td>Petugas</td><td>:</td><td>${s.nama_petugas}</td></tr>
      <tr><td>Metode</td><td>:</td><td>${s.nama_metode}</td></tr>
      <tr><td>Status</td><td>:</td><td><span class="status-badge" style="background:${sc.bg};color:${sc.color};font-size:8pt;">${sc.label}</span></td></tr>
    </table>
  </div>
</div>
${s.bukti_url ? `<div class="bukti-section">
  <div class="sec-label">BUKTI PEMBAYARAN</div>
  ${/\.(jpg|jpeg|png|webp)$/i.test(s.bukti_url)
    ? `<img src="${s.bukti_url}" class="bukti-img" alt="Bukti Pembayaran">`
    : `<a href="${s.bukti_url}" style="color:#4F46E5;font-size:10pt;display:inline-flex;align-items:center;gap:6px;margin-top:6px;">&#128206; Lihat File Bukti (PDF)</a>`}
</div>` : ''}
</div><!-- end content-wrap -->
<div class="doc-footer">
  <div class="footer-left">
    <strong style="color:#374151;font-size:9pt;">PT KAWAN KITA SOLUSINDO</strong><br>
    Dokumen diterbitkan otomatis oleh sistem informasi.<br>
    Dicetak: ${s.dicetak_pada}
  </div>
  <div class="ttd">
    <div class="ttd-label">Hormat Kami,</div>
    <div class="stamp-wrap">
      <img class="stamp-logo" src="${window.location.origin}/assets/img/ttd/logo.png" alt="Stempel KKS" onerror="this.style.display='none'">
    </div>
    <div class="ttd-line"></div>
    <div class="ttd-name">PT KAWAN KITA SOLUSINDO</div>
  </div>
</div>
<script>window.onload=function(){window.print();};<\/script>
</body></html>`;
        const w = window.open('', '_blank', 'width=900,height=700');
        w.document.write(html);
        w.document.close();
    })
    .catch(() => showToast('Gagal memuat struk.', 'error'));
}
/* ═══════════════════════════════════════════════
   CETAK RIWAYAT — Format A4 Landscape Formal
   ═══════════════════════════════════════════════ */
function bukaModalCetak() {
    const overlay = document.getElementById('cetakOverlay');
    const body    = document.getElementById('cetakPreviewBody');
    overlay.classList.add('open');
    body.innerHTML = `<div style="text-align:center;padding:30px;color:#9CA3AF">
        <i class='bx bx-loader-alt bx-spin' style="font-size:32px;display:block;margin-bottom:10px"></i>Memuat data...</div>`;
    fetch('/pembayaran-projek/' + PROJEK_ID + '/cetak-riwayat')
    .then(r => r.json())
    .then(data => {
        if (!data.success) { body.innerHTML = '<p style="color:red;padding:20px">Gagal memuat data.</p>'; return; }
        const p = data.projek;
        body.innerHTML = `
            <div style="background:#F9FAFB;border-radius:8px;padding:16px;font-size:13px">
                <p style="margin:0 0 8px;font-weight:700;">${p.nama_projek}</p>
                <p style="margin:0 0 4px;color:#6B7280;">${p.perusahaan_nama||p.perusahaan} — ${p.kategori}</p>
                <div style="display:flex;gap:16px;margin-top:10px;flex-wrap:wrap;">
                    <span>Nominal: <strong>${fRp(p.nominal_projek)}</strong></span>
                    <span style="color:#059669;">Terbayar: <strong>${fRp(p.total_terbayar)}</strong></span>
                    <span style="color:${p.sisa_tanggungan<=0?'#10B981':'#EF4444'};">
                        Sisa: <strong>${p.sisa_tanggungan<=0?'LUNAS':fRp(p.sisa_tanggungan)}</strong>
                    </span>
                    <span>Transaksi: <strong>${data.riwayat.length}</strong></span>
                </div>
            </div>
            <p style="font-size:12px;color:#9CA3AF;margin-top:12px;">
                Format A4 Landscape — Klik "Cetak / Simpan PDF" untuk mencetak.
            </p>`;
        cetakHtml = buildCetakRiwayatA4(p, data.riwayat, data.dicetak_pada);
    })
    .catch(() => { body.innerHTML = '<p style="color:red;padding:20px">Gagal memuat data.</p>'; });
}
function buildCetakRiwayatA4(p, riwayat, dicetak) {
    const pct = p.nominal_projek > 0
        ? Math.min(100, Math.round((p.total_terbayar / p.nominal_projek) * 100))
        : 0;
    const SC = {
        valid: { bg:'#D1FAE5', color:'#065F46' },
        draft: { bg:'#FEF3C7', color:'#92400E' },
        batal: { bg:'#FEE2E2', color:'#991B1B' }
    };
    const rows = riwayat.map(item => {
        const sc  = SC[item.status] || SC.draft;
        const isB = item.status === 'batal';
        return `<tr>
            <td style="text-align:center;color:#6B7280;">${item.no}</td>
            <td>
                <div style="font-weight:700;font-size:10pt;color:${isB?'#9CA3AF':'#111827'};${isB?'text-decoration:line-through;':''}">
                    ${fRp(item.jumlah_bayar)}
                </div>
                <div style="font-size:7.5pt;color:#9CA3AF;font-family:monospace;">${item.kode_pembayaran}</div>
            </td>
            <td style="font-weight:600;color:${isB?'#9CA3AF':(item.sisa_setelah<=0?'#059669':'#DC2626')};">
                ${isB?'—':fRp(item.sisa_setelah)}
            </td>
            <td>${fDate(item.tanggal_bayar)}</td>
            <td>${item.nama_metode}</td>
            <td>${item.nama_petugas}</td>
            <td style="text-align:center;">
                <span style="display:inline-block;padding:2px 10px;border-radius:4px;font-size:7.5pt;font-weight:700;background:${sc.bg};color:${sc.color};">
                    ${item.status.toUpperCase()}
                </span>
            </td>
        </tr>`;
    }).join('');
    return `<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8">
<title>Riwayat Pembayaran — ${p.nama_projek}</title>
<style>
@page { size:A4 landscape; margin:14mm 16mm; }
* { box-sizing:border-box; margin:0; padding:0; }
body { font-family:'Times New Roman', Georgia, serif; font-size:10pt; color:#1F2937; background:white; }
.kop { display:flex; align-items:center; gap:14px; padding-bottom:12px; border-bottom:3px solid #1E2A3A; margin-bottom:4px; }
.kop-logo-img { width:44px; height:44px; max-width:44px; max-height:44px; object-fit:contain; flex-shrink:0; }
.kop-logo-fb  { width:44px; height:44px; border-radius:6px; background:#1E2A3A; display:flex; align-items:center; justify-content:center; color:white; font-size:16px; font-weight:700; flex-shrink:0; }
.kop-co { font-size:13pt; font-weight:700; color:#1E2A3A; }
.kop-sub { font-size:8pt; color:#6B7280; margin-top:2px; }
.kop-right { margin-left:auto; text-align:right; }
.kop-right .doc-type { font-size:11.5pt; font-weight:700; color:#1E2A3A; text-transform:uppercase; letter-spacing:.04em; }
.kop-right .doc-date { font-size:8pt; color:#9CA3AF; margin-top:3px; }
.project-meta { background:#F9FAFB; border:1px solid #E5E7EB; border-radius:5px; padding:10px 14px; margin:12px 0; display:flex; gap:32px; flex-wrap:wrap; }
.meta-item label { font-size:7.5pt; color:#9CA3AF; display:block; text-transform:uppercase; letter-spacing:.06em; }
.meta-item span { font-size:9.5pt; font-weight:600; color:#111827; }
.meta-item.prog { flex:1; min-width:180px; }
.prog-track { background:#E5E7EB; height:6px; border-radius:3px; overflow:hidden; margin-top:4px; }
.prog-fill { height:100%; background:#1E2A3A; border-radius:3px; }
.summary-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:10px; margin-bottom:14px; }
.sum-box { border:1px solid #E5E7EB; border-radius:5px; padding:10px 12px; }
.sum-label { font-size:7pt; text-transform:uppercase; letter-spacing:.08em; color:#6B7280; margin-bottom:4px; }
.sum-value { font-size:11pt; font-weight:700; color:#1E2A3A; }
table.rw { width:100%; border-collapse:collapse; font-size:9.5pt; }
table.rw thead tr { background:#1E2A3A; }
table.rw thead th { padding:8px 10px; color:white; font-size:7.5pt; font-weight:700; text-transform:uppercase; letter-spacing:.06em; text-align:left; }
table.rw thead th:first-child { text-align:center; }
table.rw tbody td { padding:8px 10px; border-bottom:1px solid #F3F4F6; vertical-align:middle; }
table.rw tbody tr:nth-child(even) td { background:#FAFAFA; }
table.rw tbody tr:last-child td { border-bottom:none; }
.doc-footer { margin-top:18px; padding-top:10px; border-top:2px solid #1E2A3A; display:flex; justify-content:space-between; align-items:center; }
.footer-left { font-size:7.5pt; color:#9CA3AF; }
.footer-right { font-size:7.5pt; color:#9CA3AF; text-align:right; }
@media print { body { print-color-adjust:exact; -webkit-print-color-adjust:exact; } }
</style></head><body>
<div class="kop">
  <img class="kop-logo-img" src="${window.location.origin}/assets/img/ttd/logo1.png" alt="KKS"
       onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
  <div class="kop-logo-fb" style="display:none;">KKS</div>
  <div>
    <div class="kop-co">PT KAWAN KITA SOLUSINDO</div>
    <div class="kop-sub">Laporan Riwayat Pembayaran Termin Project</div>
  </div>
  <div class="kop-right">
    <div class="doc-type">Riwayat Pembayaran</div>
    <div class="doc-date">Dicetak: ${dicetak}</div>
  </div>
</div>
<div class="project-meta">
  <div class="meta-item"><label>Nama Project</label><span>${p.nama_projek}</span></div>
  <div class="meta-item"><label>Perusahaan</label><span>${p.perusahaan}</span></div>
  <div class="meta-item"><label>Perwakilan</label><span>${p.perusahaan_nama||'—'}</span></div>
  <div class="meta-item"><label>Kategori</label><span>${p.kategori}</span></div>
  <div class="meta-item"><label>Status</label><span>${p.status||'—'}</span></div>
  <div class="meta-item prog">
    <label>Progres Pembayaran — ${pct}% terbayar</label>
    <div class="prog-track"><div class="prog-fill" style="width:${pct}%;"></div></div>
  </div>
</div>
<div class="summary-grid">
  <div class="sum-box"><div class="sum-label">Nilai Kontrak</div><div class="sum-value">${fRp(p.nominal_projek)}</div></div>
  <div class="sum-box"><div class="sum-label">Terbayar (Valid)</div><div class="sum-value" style="color:#059669;">${fRp(p.total_terbayar)}</div></div>
  <div class="sum-box"><div class="sum-label">Total Draft</div><div class="sum-value" style="color:#D97706;">${fRp(p.total_draft)}</div></div>
  <div class="sum-box">
    <div class="sum-label">Sisa Tanggungan</div>
    <div class="sum-value" style="color:${p.sisa_tanggungan<=0?'#059669':'#DC2626'};">
      ${p.sisa_tanggungan<=0?'LUNAS':fRp(p.sisa_tanggungan)}
    </div>
  </div>
  <div class="sum-box"><div class="sum-label">Jumlah Transaksi</div><div class="sum-value">${riwayat.length}</div></div>
</div>
<table class="rw">
  <thead>
    <tr>
      <th style="width:4%;">No</th>
      <th style="min-width:17%;">Nominal Bayar</th>
      <th style="min-width:17%;">Sisa Setelah Bayar</th>
      <th style="min-width:13%;">Tanggal Bayar</th>
      <th style="min-width:13%;">Metode</th>
      <th style="min-width:14%;">Petugas</th>
      <th style="min-width:10%;text-align:center;">Status</th>
    </tr>
  </thead>
  <tbody>
    ${rows || `<tr><td colspan="7" style="text-align:center;padding:20px;color:#9CA3AF;font-style:italic;">Belum ada riwayat pembayaran.</td></tr>`}
  </tbody>
</table>
<div class="doc-footer">
  <div class="footer-left"><strong>PT KAWAN KITA SOLUSINDO</strong> · Sistem Manajemen Project<br>Dokumen diterbitkan otomatis oleh sistem informasi.</div>
  <div class="footer-right">Dicetak: ${dicetak}</div>
</div>
<script>window.onload=function(){window.print();};<\/script>
</body></html>`;
}
function printRiwayat() {
    if (!cetakHtml) return;
    const w = window.open('', '_blank', 'width=1100,height=750');
    w.document.write(cetakHtml);
    w.document.close();
}
function tutupModalCetak(e) {
    if (e.target === document.getElementById('cetakOverlay')) tutupModalCetakForce();
}
function tutupModalCetakForce() {
    document.getElementById('cetakOverlay').classList.remove('open');
    cetakHtml = '';
}
</script>
@endpush