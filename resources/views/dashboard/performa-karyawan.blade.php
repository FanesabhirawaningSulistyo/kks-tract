@extends('layouts.master')
@section('title', 'Performa Karyawan')
@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<style>
/* ══════════════════════════════════════════════
   PERFORMA KARYAWAN — Modern Minimalist Purple
══════════════════════════════════════════════ */
:root {
    --p1: #696cff;
    --p2: #5145cd;
    --p3: #3a2d9f;
    --p4: #b984db;
    --p-light: #f0efff;
    --p-soft:  #e8e7ff;
    --bg:       #f8f8fc;
    --white:    #ffffff;
    --border:   #e9e8f5;
    --text:     #1e1b3a;
    --sub:      #6b6894;
    --muted:    #a8a5c0;
    --green:    #22c55e;
    --amber:    #f59e0b;
    --red:      #ef4444;
    --blue:     #3b82f6;
    --radius:   10px;
    --shadow:   0 1px 3px rgba(105,108,255,.06), 0 4px 16px rgba(105,108,255,.06);
    --shadow-md:0 8px 32px rgba(105,108,255,.12);
}
/* ── Page header ── */
.pk-header {
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}
.pk-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--text);
    margin: 0;
    letter-spacing: -.02em;
}
.pk-sub {
    font-size: .8rem;
    color: var(--sub);
    margin-top: 3px;
}
/* ── Info note ── */
.pk-note {
    background: var(--p-light);
    border: 1px solid var(--p-soft);
    border-radius: var(--radius);
    padding: 14px 18px;
    margin-bottom: 24px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.pk-note-icon {
    width: 26px; height: 26px;
    border-radius: 50%;
    background: var(--p1);
    color: #fff;
    font-size: .72rem;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-weight: 700;
    margin-top: 1px;
}
.pk-note-body { flex: 1; }
.pk-note-title {
    font-size: .8rem;
    font-weight: 700;
    color: var(--p2);
    margin-bottom: 8px;
}
.pk-note-list {
    list-style: none;
    margin: 0; padding: 0;
    display: flex; flex-wrap: wrap; gap: 5px 20px;
}
.pk-note-list li {
    font-size: .77rem;
    color: var(--sub);
    display: flex; align-items: center; gap: 7px;
}
.pk-note-list li::before {
    content: '';
    width: 7px; height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
}
.pk-note-list .dot-green::before  { background: var(--green); }
.pk-note-list .dot-purple::before { background: var(--p1); }
.pk-note-list .dot-blue::before   { background: var(--blue); }
.pk-note-list .dot-amber::before  { background: var(--amber); }
.pk-note-list .dot-red::before    { background: var(--red); }
.pk-note-list strong { color: var(--text); }
/* ── Charts grid ── */
.pk-charts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 24px;
}
@media(max-width:768px) { .pk-charts-grid { grid-template-columns: 1fr; } }
/* ── Card ── */
.pk-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.pk-card-head {
    padding: 13px 20px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    gap: 10px;
}
.pk-card-title {
    font-size: .84rem;
    font-weight: 700;
    color: var(--text);
    margin: 0;
}
.pk-card-badge {
    font-size: .7rem;
    font-weight: 600;
    color: var(--muted);
    background: var(--bg);
    border: 1px solid var(--border);
    padding: 2px 9px;
    border-radius: 20px;
    white-space: nowrap;
}
.pk-chart-body { padding: 14px 18px; }
/* ── Sort toggle button ── */
.pk-sort-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 6px;
    border: 1.5px solid var(--border);
    background: var(--white); color: var(--sub);
    font-size: .72rem; font-weight: 700;
    cursor: pointer; transition: all .18s;
    letter-spacing: .03em;
    user-select: none;
    white-space: nowrap;
}
.pk-sort-btn:hover { border-color: var(--p1); color: var(--p1); background: var(--p-light); }
.pk-sort-btn.active-desc { border-color: var(--p1); color: var(--p1); background: var(--p-light); }
.pk-sort-btn.active-asc  { border-color: var(--blue); color: var(--blue); background: #eff6ff; }
.pk-sort-btn .bx { font-size: .95rem; transition: transform .25s; }
.pk-sort-btn.active-asc .bx { transform: rotate(180deg); }
/* Sort indicator on Rank header */
.pk-rank-header-btn {
    display: inline-flex; align-items: center; gap: 4px;
    background: none; border: none; padding: 0;
    cursor: pointer; color: var(--muted);
    font-size: .69rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    transition: color .15s;
}
.pk-rank-header-btn:hover { color: var(--p1); }
.pk-rank-header-btn .bx  { font-size: .9rem; }
/* ── Table ── */
.pk-table { width: 100%; border-collapse: collapse; }
.pk-table thead th {
    padding: 10px 13px;
    font-size: .69rem;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .06em;
    background: var(--bg);
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.pk-table thead th:first-child { padding-left: 20px; }
.pk-table thead th:last-child  { padding-right: 20px; }
.pk-table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background .12s;
}
.pk-table tbody tr:last-child { border-bottom: none; }
.pk-table tbody tr:hover { background: var(--p-light); }
.pk-table tbody td {
    padding: 11px 13px;
    font-size: .83rem;
    color: var(--text);
    vertical-align: middle;
    transition: background .12s;
}
.pk-table tbody td:first-child { padding-left: 20px; }
.pk-table tbody td:last-child  { padding-right: 20px; }
/* Row slide animation when re-sorted */
@keyframes pk-row-slide {
    from { opacity: 0; transform: translateX(-8px); }
    to   { opacity: 1; transform: translateX(0); }
}
.pk-row-animate { animation: pk-row-slide .22s ease both; }
/* Initial load animation */
@keyframes pk-row-in {
    from { opacity:0; transform:translateY(6px); }
    to   { opacity:1; transform:translateY(0); }
}
.pk-table tbody tr { animation: pk-row-in .3s both; }
.pk-table tbody tr:nth-child(1)  { animation-delay:.03s; }
.pk-table tbody tr:nth-child(2)  { animation-delay:.06s; }
.pk-table tbody tr:nth-child(3)  { animation-delay:.09s; }
.pk-table tbody tr:nth-child(4)  { animation-delay:.12s; }
.pk-table tbody tr:nth-child(5)  { animation-delay:.15s; }
.pk-table tbody tr:nth-child(6)  { animation-delay:.18s; }
.pk-table tbody tr:nth-child(7)  { animation-delay:.21s; }
.pk-table tbody tr:nth-child(8)  { animation-delay:.24s; }
.pk-table tbody tr:nth-child(9)  { animation-delay:.27s; }
.pk-table tbody tr:nth-child(10) { animation-delay:.30s; }
/* ── Sort direction pill (next to table title) ── */
.pk-sort-pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 20px;
    font-size: .68rem; font-weight: 700;
    transition: all .2s;
}
.pk-sort-pill-desc { background: var(--p-light); color: var(--p2); border: 1px solid var(--p-soft); }
.pk-sort-pill-asc  { background: #eff6ff; color: var(--blue); border: 1px solid #bfdbfe; }
/* ── Rank ── */
.pk-rank {
    display: inline-flex; align-items: center; justify-content: center;
    width: 26px; height: 26px; border-radius: 50%;
    font-weight: 700; font-size: .74rem;
}
.pk-rank-1 { background: #fef3c7; color: #92400e; }
.pk-rank-2 { background: #f1f5f9; color: #475569; }
.pk-rank-3 { background: #fff7ed; color: #9a3412; }
.pk-rank-n { background: var(--bg); color: var(--muted); border: 1px solid var(--border); }
/* ── Avatar ── */
.pk-av {
    width: 32px; height: 32px; border-radius: 50%;
    object-fit: cover; border: 1.5px solid var(--border); flex-shrink: 0;
}
.pk-av-init {
    width: 32px; height: 32px; border-radius: 50%;
    background: var(--p2); color: #fff;
    font-weight: 700; font-size: .68rem;
    display: inline-flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.pk-name  { font-weight: 600; color: var(--text); font-size: .82rem; line-height: 1.3; }
.pk-email { font-size: .71rem; color: var(--muted); }
/* ── Role badge ── */
.pk-badge {
    display: inline-block;
    padding: 2px 8px; border-radius: 4px;
    font-size: .69rem; font-weight: 600;
    background: var(--p-soft); color: var(--p2);
}
/* ── Numbers ── */
.pk-num         { font-weight: 700; color: var(--text); }
.pk-num-green   { color: var(--green); }
.pk-num-amber   { color: var(--amber); }
.pk-num-red     { color: var(--red); }
.pk-num-purple  { color: var(--p1); }
.pk-num-muted   { color: var(--muted); }
/* ── Poin chip ── */
.pk-poin {
    display: inline-block;
    padding: 2px 9px; border-radius: 4px;
    font-weight: 800; font-size: .8rem;
}
.pk-poin-pos { background: #f0fdf4; color: var(--green); }
.pk-poin-neg { background: #fef2f2; color: var(--red); }
.pk-poin-zer { background: var(--bg); color: var(--muted); }
/* ── Progress mini ── */
.pk-prog-wrap { display: flex; align-items: center; gap: 7px; }
.pk-prog-bg   { flex: 1; height: 4px; background: var(--border); border-radius: 2px; overflow: hidden; min-width: 60px; }
.pk-prog-fill { height: 100%; border-radius: 2px; background: var(--p1); }
.pk-prog-pct  { font-size: .71rem; font-weight: 700; color: var(--p1); white-space: nowrap; }
/* ── Detail button ── */
.pk-btn-detail {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px; border-radius: 6px;
    border: 1px solid var(--border);
    background: var(--white); color: var(--muted);
    cursor: pointer; transition: all .15s; font-size: .8rem;
}
.pk-btn-detail:hover { border-color: var(--p1); color: var(--p1); background: var(--p-light); }
/* ══════════════════════════════════════════════
   KARYAWAN SELF-BANNER
══════════════════════════════════════════════ */
.pk-self-banner {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 20px;
}
.pk-self-banner-top {
    background: linear-gradient(135deg, var(--p2) 0%, var(--p3) 100%);
    padding: 18px 22px;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 16px;
}
.pk-self-banner-left { display: flex; align-items: center; gap: 14px; }
.pk-self-av-lg {
    width: 48px; height: 48px; border-radius: 50%;
    border: 2px solid rgba(255,255,255,.4);
    object-fit: cover; flex-shrink: 0;
}
.pk-self-av-init-lg {
    width: 48px; height: 48px; border-radius: 50%;
    background: rgba(255,255,255,.18); color: #fff;
    font-weight: 800; font-size: .95rem;
    display: inline-flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    border: 2px solid rgba(255,255,255,.3);
}
.pk-self-name  { font-size: .95rem; font-weight: 700; color: #fff; line-height: 1.3; }
.pk-self-jabatan { font-size: .75rem; color: rgba(255,255,255,.65); margin-top: 2px; }
.pk-self-poin-block { text-align: right; }
.pk-self-poin-label { font-size: .72rem; color: rgba(255,255,255,.65); }
.pk-self-poin-val   { font-size: 2rem; font-weight: 900; color: #fff; letter-spacing: -.03em; line-height: 1.1; }
.pk-self-rank-txt   { font-size: .75rem; color: rgba(255,255,255,.75); margin-top: 2px; }
.pk-self-banner-bottom { padding: 16px 22px; border-top: 1px solid var(--border); }
.pk-self-prog-row {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 6px;
}
.pk-self-prog-label { font-size: .78rem; font-weight: 600; color: var(--sub); }
.pk-self-prog-pct   { font-size: .9rem; font-weight: 800; color: var(--p1); }
.pk-self-prog-bg  { height: 8px; background: var(--border); border-radius: 4px; overflow: hidden; }
.pk-self-prog-fill { height: 100%; border-radius: 4px; background: linear-gradient(90deg, var(--p1), var(--p4)); transition: width 1s ease; }
.pk-self-stats {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
    margin-top: 16px;
}
@media(max-width:640px) { .pk-self-stats { grid-template-columns: repeat(3, 1fr); } }
.pk-self-stat-item {
    text-align: center;
    padding: 10px 8px;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 8px;
}
.pk-self-stat-val   { font-size: 1.3rem; font-weight: 800; line-height: 1.1; }
.pk-self-stat-label { font-size: .65rem; color: var(--muted); margin-top: 3px; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; }
.pk-self-detail-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: 7px;
    border: 1px solid var(--p1); color: var(--p1);
    background: var(--p-light); font-size: .79rem; font-weight: 700;
    cursor: pointer; transition: all .15s; text-decoration: none;
    margin-top: 16px;
}
.pk-self-detail-btn:hover { background: var(--p1); color: #fff; }
/* ══════════════════════════════════════════════
   MODAL
══════════════════════════════════════════════ */
.pk-modal .modal-content {
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow: var(--shadow-md);
    overflow: hidden;
}
.pk-modal .modal-header {
    background: linear-gradient(135deg, var(--p2) 0%, var(--p3) 100%);
    padding: 18px 24px;
    border-bottom: none;
    position: relative;
}
.pk-modal .modal-title { color: #fff; font-weight: 700; font-size: .95rem; margin: 0; }
.pk-modal .modal-sub   { color: rgba(255,255,255,.58); font-size: .75rem; margin-top: 3px; }
.pk-modal .btn-close {
    position: absolute; top: 50%; right: 18px; transform: translateY(-50%);
    width: 28px; height: 28px; border-radius: 6px; border: none;
    background: rgba(255,255,255,.18) url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") center/11px no-repeat;
    cursor: pointer; opacity: 1; transition: background .15s;
}
.pk-modal .btn-close:hover { background-color: rgba(255,255,255,.32); }
.pk-modal .modal-body { padding: 20px 24px; background: var(--bg); }
.pk-modal .modal-footer { background: var(--white); border-top: 1px solid var(--border); padding: 12px 20px; }
/* ── Calc box ── */
.pk-calc-box {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 14px 16px;
    margin-bottom: 14px;
}
.pk-sec {
    font-size: .69rem; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: .07em;
    margin-bottom: 10px; margin-top: 0;
}
.pk-calc-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
    font-size: .8rem;
    gap: 16px;
}
.pk-calc-row:last-child { border-bottom: none; padding-bottom: 0; }
.pk-calc-label {
    color: var(--sub);
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
    min-width: 150px;
    font-weight: 500;
}
.pk-calc-dot {
    width: 10px; height: 10px;
    border-radius: 50%; flex-shrink: 0;
}
.pk-calc-formula {
    flex: 1; text-align: left;
    font-size: .8rem; color: var(--text);
    font-family: 'Courier New', monospace;
    font-weight: 500; padding-left: 8px;
}
.pk-calc-result {
    font-weight: 800; font-size: .85rem;
    min-width: 85px; text-align: right; flex-shrink: 0;
}
.pk-calc-total {
    display: flex; align-items: center; justify-content: space-between;
    margin-top: 12px; padding-top: 12px;
    border-top: 2px solid var(--border);
    font-size: .9rem;
}
.pk-calc-total-label { font-weight: 800; color: var(--text); font-size: .85rem; }
.pk-calc-total-val   { font-weight: 900; font-size: 1.1rem; }
.pk-prog-section { margin-top: 14px; padding-top: 14px; border-top: 1px dashed var(--border); }
.pk-prog-hdr {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 6px;
}
.pk-prog-hdr-label { font-size: .79rem; font-weight: 600; color: var(--sub); }
.pk-prog-hdr-pct   { font-size: .85rem; font-weight: 800; color: var(--p1); }
.pk-overall-bg { height: 8px; background: var(--border); border-radius: 4px; overflow: hidden; }
.pk-overall-fill { height: 100%; border-radius: 4px; background: linear-gradient(90deg, var(--p1), var(--p4)); transition: width 1s ease; }
.pk-prog-detail { font-size: .72rem; color: var(--muted); margin-top: 4px; }
.pk-charts-2col {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 14px; margin-bottom: 14px;
}
@media(max-width:600px) { .pk-charts-2col { grid-template-columns: 1fr; } }
.pk-pie-box {
    background: var(--white); border: 1px solid var(--border);
    border-radius: 8px; padding: 14px 12px;
    display: flex; flex-direction: column; align-items: center;
    justify-content: center; gap: 12px;
}
.pk-pie-legend { display: flex; flex-direction: column; gap: 8px; width: 100%; }
.pk-pie-leg-item { display: flex; align-items: center; gap: 8px; }
.pk-pie-leg-dot  { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.pk-pie-leg-label { font-size: .74rem; color: var(--sub); flex: 1; }
.pk-pie-leg-pct  { font-size: .8rem; font-weight: 800; min-width: 36px; text-align: right; }
.pk-pie-leg-count { font-size: .7rem; color: var(--muted); min-width: 50px; text-align: right; }
.pk-chart-box {
    background: var(--white); border: 1px solid var(--border);
    border-radius: 8px; padding: 14px 16px;
}
.pk-proj-item {
    background: var(--white); border: 1px solid var(--border);
    border-radius: 8px; padding: 11px 13px; margin-bottom: 8px;
    transition: border-color .15s;
}
.pk-proj-item:last-child { margin-bottom: 0; }
.pk-proj-item:hover { border-color: var(--p1); }
.pk-proj-name { font-weight: 700; font-size: .82rem; color: var(--text); }
.pk-status-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; margin-right: 5px; }
.pk-status-aktif       { background: var(--green); }
.pk-status-in_progress { background: var(--p1); }
.pk-status-pending     { background: var(--amber); }
.pk-status-selesai     { background: var(--muted); }
.pk-spin {
    width: 28px; height: 28px; border-radius: 50%;
    border: 2.5px solid var(--border); border-top-color: var(--p1);
    animation: pk-spin .7s linear infinite; margin: 0 auto 10px;
}
@keyframes pk-spin { to { transform: rotate(360deg); } }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- ── Page Header ── --}}
    <div class="pk-header">
        <div>
            <h4 class="pk-title">Performa Karyawan</h4>
            <p class="pk-sub mb-0">
                @if($isLimitedView)
                    Menampilkan Top 5 karyawan dengan poin tertinggi
                @else
                    Ranking keseluruhan berdasarkan akumulasi poin
                @endif
            </p>
        </div>
    </div>

    {{-- ── Info Note ── --}}
    <div class="pk-note">
        <div class="pk-note-icon">i</div>
        <div class="pk-note-body">
            <div class="pk-note-title">Sistem Perhitungan Poin</div>
            <ul class="pk-note-list">
                <li class="dot-purple"><span>Bergabung project <strong>+{{ \App\Http\Controllers\PerformaKaryawanController::POIN_PROJECT }} poin</strong></span></li>
                <li class="dot-blue"><span>Per task ditugaskan <strong>+{{ \App\Http\Controllers\PerformaKaryawanController::POIN_TASK }} poin</strong></span></li>
                <li class="dot-green"><span>Selesai lebih awal <strong>+{{ \App\Http\Controllers\PerformaKaryawanController::POIN_BEFORE_DEADLINE }} poin</strong></span></li>
                <li class="dot-amber"><span>Tepat waktu <strong>+{{ \App\Http\Controllers\PerformaKaryawanController::POIN_ON_TIME }} poin</strong></span></li>
                <li class="dot-red"><span>Terlambat <strong>{{ \App\Http\Controllers\PerformaKaryawanController::POIN_LATE }} poin</strong></span></li>
            </ul>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         KARYAWAN SELF-BANNER (hanya role karyawan)
    ══════════════════════════════════════════════ --}}
    @if($isLimitedView && $myPerforma)
    @php
        $me = $myPerforma;
        $meSelesai = ($me['sebelum_deadline'] ?? 0) + ($me['tepat_waktu'] ?? 0) + ($me['terlambat'] ?? 0);
        $mePct = $me['jumlah_task'] > 0 ? round($meSelesai / $me['jumlah_task'] * 100) : 0;
        $totalKaryawan = \App\Models\User::where('role', 'karyawan')->where('status', true)->count();
    @endphp
    <div class="pk-self-banner">
        <div class="pk-self-banner-top">
            <div class="pk-self-banner-left">
                @if($me['foto'])
                    <img src="{{ asset('storage/' . $me['foto']) }}" class="pk-self-av-lg" alt="">
                @else
                    <div class="pk-self-av-init-lg">{{ strtoupper(substr($me['nama'], 0, 2)) }}</div>
                @endif
                <div>
                    <div class="pk-self-name">{{ $me['nama'] }}</div>
                    <div class="pk-self-jabatan">{{ $me['jabatan'] }} &nbsp;·&nbsp; {{ $me['email'] }}</div>
                </div>
            </div>
            <div class="pk-self-poin-block">
                <div class="pk-self-poin-label">Total Poin Kamu</div>
                <div class="pk-self-poin-val">{{ $me['poin'] > 0 ? '+' : '' }}{{ $me['poin'] }}</div>
                <div class="pk-self-rank-txt">Rank #{{ $me['rank'] }} dari {{ $totalKaryawan }} karyawan</div>
            </div>
        </div>
        <div class="pk-self-banner-bottom">
            <div class="pk-self-prog-row">
                <span class="pk-self-prog-label">
                    <i class="bx bx-check-circle" style="color:var(--green);"></i>
                    Progres Task &nbsp;·&nbsp; {{ $meSelesai }}/{{ $me['jumlah_task'] }} selesai
                </span>
                <span class="pk-self-prog-pct">{{ $mePct }}%</span>
            </div>
            <div class="pk-self-prog-bg">
                <div class="pk-self-prog-fill" style="width:{{ $mePct }}%;"></div>
            </div>
            <div class="pk-self-stats">
                <div class="pk-self-stat-item">
                    <div class="pk-self-stat-val" style="color:var(--p1);">{{ $me['jumlah_project'] }}</div>
                    <div class="pk-self-stat-label">Project</div>
                </div>
                <div class="pk-self-stat-item">
                    <div class="pk-self-stat-val" style="color:var(--sub);">{{ $me['jumlah_task'] }}</div>
                    <div class="pk-self-stat-label">Total Task</div>
                </div>
                <div class="pk-self-stat-item">
                    <div class="pk-self-stat-val" style="color:var(--green);">{{ $me['sebelum_deadline'] }}</div>
                    <div class="pk-self-stat-label">Lebih Awal</div>
                </div>
                <div class="pk-self-stat-item">
                    <div class="pk-self-stat-val" style="color:var(--amber);">{{ $me['tepat_waktu'] }}</div>
                    <div class="pk-self-stat-label">Tepat Waktu</div>
                </div>
                <div class="pk-self-stat-item">
                    <div class="pk-self-stat-val" style="color:var(--red);">{{ $me['terlambat'] }}</div>
                    <div class="pk-self-stat-label">Terlambat</div>
                </div>
            </div>
            <button class="pk-self-detail-btn"
                onclick="bukaDetail({{ $me['id_user'] }}, '{{ addslashes($me['nama']) }}')">
                <i class="bx bx-bar-chart-alt-2"></i>
                Lihat Detail Performa Lengkap Saya
            </button>
        </div>
    </div>
    @endif

    {{-- ── Charts (PM & Admin only) ── --}}
    @if(!$isLimitedView && $performaData->count() > 0)
    <div class="pk-charts-grid">
        <div class="pk-card">
            <div class="pk-card-head">
                <h5 class="pk-card-title">Perbandingan Poin & Breakdown Task</h5>
                <span class="pk-card-badge">Stacked Bar</span>
            </div>
            <div class="pk-chart-body">
                <canvas id="chartAllPoin" height="220"></canvas>
            </div>
        </div>
        <div class="pk-card">
            <div class="pk-card-head">
                <h5 class="pk-card-title">Persentase Penyelesaian Task</h5>
                <span class="pk-card-badge">Horizontal Bar</span>
            </div>
            <div class="pk-chart-body">
                <canvas id="chartAllCompletion" height="220"></canvas>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Main Table ── --}}
    <div class="pk-card">
        <div class="pk-card-head">
            <h5 class="pk-card-title">
                {{ $isLimitedView ? 'Top 5 Karyawan' : 'Ranking Semua Karyawan' }}
            </h5>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                @if(!$isLimitedView)
                <span class="pk-card-badge">{{ $performaData->count() }} karyawan</span>
                @endif

                {{-- ══ SORT TOGGLE BUTTON ══ --}}
                <button id="btnSortToggle" class="pk-sort-btn active-desc" onclick="toggleSort()" title="Klik untuk ubah urutan ranking">
                    <i class="bx bx-sort-down" id="sortIcon"></i>
                    <span id="sortLabel">Poin Tertinggi</span>
                </button>
            </div>
        </div>

        @if($performaData->isEmpty())
            <div class="text-center py-5">
                <i class="bx bx-group" style="font-size:2rem;color:var(--border);"></i>
                <p style="font-size:.8rem;color:var(--muted);" class="mt-2 mb-0">Belum ada data karyawan</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="pk-table">
                <thead>
                    <tr>
                        <th style="width:46px;">
                            {{-- Rank header dengan hint sort --}}
                            <button class="pk-rank-header-btn" onclick="toggleSort()" title="Klik untuk ubah urutan">
                                Rank
                                <i class="bx bx-sort-down" id="rankHeaderIcon"></i>
                            </button>
                        </th>
                        <th>Karyawan</th>
                        <th>Job Role</th>
                        <th class="text-center">Project</th>
                        <th class="text-center">Task</th>
                        <th class="text-center">Lebih Awal</th>
                        <th class="text-center">Tepat Waktu</th>
                        <th class="text-center">Terlambat</th>
                        <th class="text-center">Poin</th>
                        <th style="min-width:130px;">% Selesai</th>
                        @if(!$isLimitedView)
                        <th class="text-center">Detail</th>
                        @endif
                    </tr>
                </thead>
                <tbody id="rankingTbody">
                    @foreach($performaData as $k)
                    @php
                        $selesai   = ($k['sebelum_deadline'] ?? 0) + ($k['tepat_waktu'] ?? 0) + ($k['terlambat'] ?? 0);
                        $totalTask = $k['jumlah_task'];
                        $pct       = $totalTask > 0 ? round($selesai / $totalTask * 100) : 0;
                    @endphp
                    <tr data-rank="{{ $k['rank'] }}" data-poin="{{ $k['poin'] }}">
                        <td>
                            <span class="pk-rank {{ $k['rank'] === 1 ? 'pk-rank-1' : ($k['rank'] === 2 ? 'pk-rank-2' : ($k['rank'] === 3 ? 'pk-rank-3' : 'pk-rank-n')) }}">
                                @if($k['rank'] === 1) 🥇
                                @elseif($k['rank'] === 2) 🥈
                                @elseif($k['rank'] === 3) 🥉
                                @else {{ $k['rank'] }}
                                @endif
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($k['foto'])
                                    <img src="{{ asset('storage/' . $k['foto']) }}" class="pk-av" alt="">
                                @else
                                    <div class="pk-av-init">{{ strtoupper(substr($k['nama'],0,2)) }}</div>
                                @endif
                                <div>
                                    <div class="pk-name">{{ $k['nama'] }}</div>
                                    <div class="pk-email">{{ $k['email'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="pk-badge">{{ $k['jabatan'] }}</span></td>
                        <td class="text-center"><span class="pk-num pk-num-purple">{{ $k['jumlah_project'] }}</span></td>
                        <td class="text-center"><span class="pk-num pk-num-muted">{{ $k['jumlah_task'] }}</span></td>
                        <td class="text-center"><span class="pk-num pk-num-green">{{ $k['sebelum_deadline'] }}</span></td>
                        <td class="text-center"><span class="pk-num pk-num-amber">{{ $k['tepat_waktu'] }}</span></td>
                        <td class="text-center"><span class="pk-num pk-num-red">{{ $k['terlambat'] }}</span></td>
                        <td class="text-center">
                            <span class="pk-poin {{ $k['poin'] > 0 ? 'pk-poin-pos' : ($k['poin'] < 0 ? 'pk-poin-neg' : 'pk-poin-zer') }}">
                                {{ $k['poin'] > 0 ? '+' : '' }}{{ $k['poin'] }}
                            </span>
                        </td>
                        <td>
                            <div class="pk-prog-wrap">
                                <div class="pk-prog-bg">
                                    <div class="pk-prog-fill" style="width:{{ $pct }}%;"></div>
                                </div>
                                <span class="pk-prog-pct">{{ $pct }}%</span>
                            </div>
                            <div style="font-size:.68rem;color:var(--muted);margin-top:2px;">{{ $selesai }}/{{ $totalTask }} task</div>
                        </td>
                        @if(!$isLimitedView)
                        <td class="text-center">
                            <button class="pk-btn-detail"
                                onclick="bukaDetail({{ $k['id_user'] }}, '{{ addslashes($k['nama']) }}')"
                                title="Lihat Detail">
                                <i class="bx bx-bar-chart-alt-2"></i>
                            </button>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>

{{-- ══════════════════════════════════════════════
     MODAL DETAIL PERFORMA
══════════════════════════════════════════════ --}}
<div class="modal fade pk-modal" id="modalDetailPerforma" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="modal-title" id="mdNama">—</div>
                    <div class="modal-sub" id="mdSub"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mdLoading" class="text-center py-5">
                    <div class="pk-spin"></div>
                    <p style="font-size:.77rem;color:var(--muted);" class="mb-0">Memuat data…</p>
                </div>
                <div id="mdContent" class="d-none">
                    {{-- ── 1. Rincian Perhitungan Poin + Progres ── --}}
                    <p class="pk-sec">Rincian Perhitungan Poin & Progres Task</p>
                    <div class="pk-calc-box">
                        <div class="pk-calc-row">
                            <span class="pk-calc-label">
                                <span class="pk-calc-dot" style="background:var(--p1);"></span>
                                Bergabung Project
                            </span>
                            <span class="pk-calc-formula" id="md_proj_formula">0 × 5 = 0</span>
                            <span class="pk-calc-result" style="color:var(--p1);" id="md_proj_result">+0 poin</span>
                        </div>
                        <div class="pk-calc-row">
                            <span class="pk-calc-label">
                                <span class="pk-calc-dot" style="background:var(--blue);"></span>
                                Task Ditugaskan
                            </span>
                            <span class="pk-calc-formula" id="md_task_formula">0 × 2 = 0</span>
                            <span class="pk-calc-result" style="color:var(--blue);" id="md_task_result">+0 poin</span>
                        </div>
                        <div class="pk-calc-row">
                            <span class="pk-calc-label">
                                <span class="pk-calc-dot" style="background:var(--green);"></span>
                                Selesai Lebih Awal
                            </span>
                            <span class="pk-calc-formula" id="md_sblm_formula">0 × 3 = 0</span>
                            <span class="pk-calc-result" style="color:var(--green);" id="md_sblm_result">+0 poin</span>
                        </div>
                        <div class="pk-calc-row">
                            <span class="pk-calc-label">
                                <span class="pk-calc-dot" style="background:var(--amber);"></span>
                                Tepat Waktu
                            </span>
                            <span class="pk-calc-formula" id="md_tepat_formula">0 × 2 = 0</span>
                            <span class="pk-calc-result" style="color:var(--amber);" id="md_tepat_result">+0 poin</span>
                        </div>
                        <div class="pk-calc-row">
                            <span class="pk-calc-label">
                                <span class="pk-calc-dot" style="background:var(--red);"></span>
                                Terlambat
                            </span>
                            <span class="pk-calc-formula" id="md_late_formula">0 × (−2) = 0</span>
                            <span class="pk-calc-result" style="color:var(--red);" id="md_late_result">0 poin</span>
                        </div>
                        <div class="pk-calc-total">
                            <span class="pk-calc-total-label">Total Poin</span>
                            <span class="pk-calc-total-val" id="md_total_poin">0</span>
                        </div>
                        <div class="pk-prog-section">
                            <div class="pk-prog-hdr">
                                <span class="pk-prog-hdr-label">
                                    <i class="bx bx-check-circle" style="color:var(--green);"></i>
                                    Progres Task Keseluruhan
                                    &nbsp;·&nbsp;
                                    <span id="md_prog_label">0 / 0 task selesai</span>
                                </span>
                                <span class="pk-prog-hdr-pct" id="md_prog_pct">0%</span>
                            </div>
                            <div class="pk-overall-bg">
                                <div class="pk-overall-fill" id="md_prog_bar" style="width:0%;"></div>
                            </div>
                            <div class="pk-prog-detail" id="md_prog_detail"></div>
                        </div>
                    </div>
                    {{-- ── 2. Pie chart + Bar poin per project ── --}}
                    <p class="pk-sec">Distribusi & Poin per Project</p>
                    <div class="pk-charts-2col">
                        <div class="pk-pie-box">
                            <div style="font-size:.77rem;font-weight:700;color:var(--sub);margin-bottom:4px;">Distribusi Penyelesaian Task</div>
                            <canvas id="chartBreakdown" width="160" height="160" style="max-width:160px;max-height:160px;flex-shrink:0;"></canvas>
                            <div class="pk-pie-legend">
                                <div class="pk-pie-leg-item">
                                    <div class="pk-pie-leg-dot" style="background:var(--green);"></div>
                                    <span class="pk-pie-leg-label">Lebih Awal</span>
                                    <span class="pk-pie-leg-count" id="pie_sblm_count" style="color:var(--green);">0 task</span>
                                    <span class="pk-pie-leg-pct" style="color:var(--green);" id="pie_sblm_pct">0%</span>
                                </div>
                                <div class="pk-pie-leg-item">
                                    <div class="pk-pie-leg-dot" style="background:var(--amber);"></div>
                                    <span class="pk-pie-leg-label">Tepat Waktu</span>
                                    <span class="pk-pie-leg-count" id="pie_tepat_count" style="color:var(--amber);">0 task</span>
                                    <span class="pk-pie-leg-pct" style="color:var(--amber);" id="pie_tepat_pct">0%</span>
                                </div>
                                <div class="pk-pie-leg-item">
                                    <div class="pk-pie-leg-dot" style="background:var(--red);"></div>
                                    <span class="pk-pie-leg-label">Terlambat</span>
                                    <span class="pk-pie-leg-count" id="pie_late_count" style="color:var(--red);">0 task</span>
                                    <span class="pk-pie-leg-pct" style="color:var(--red);" id="pie_late_pct">0%</span>
                                </div>
                            </div>
                        </div>
                        <div class="pk-chart-box" style="display:flex;flex-direction:column;">
                            <div style="font-size:.77rem;font-weight:700;color:var(--sub);margin-bottom:8px;">Poin per Project</div>
                            <div style="flex:1;min-height:160px;position:relative;">
                                <canvas id="chartPoinProject"></canvas>
                            </div>
                        </div>
                    </div>
                    {{-- ── 3. Rincian per Project ── --}}
                    <p class="pk-sec">Rincian per Project</p>
                    <div id="md_projects"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal"
                    style="font-size:.78rem;">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
/* ═══════════════════════════════════════════════
   Unregister datalabels globally
═══════════════════════════════════════════════ */
Chart.unregister(ChartDataLabels);

/* ═══════════════════════════════════════════════
   KONSTANTA POIN
═══════════════════════════════════════════════ */
const POIN_PROJECT   = {{ \App\Http\Controllers\PerformaKaryawanController::POIN_PROJECT }};
const POIN_TASK      = {{ \App\Http\Controllers\PerformaKaryawanController::POIN_TASK }};
const POIN_BEFORE    = {{ \App\Http\Controllers\PerformaKaryawanController::POIN_BEFORE_DEADLINE }};
const POIN_ON_TIME   = {{ \App\Http\Controllers\PerformaKaryawanController::POIN_ON_TIME }};
const POIN_LATE_RATE = {{ \App\Http\Controllers\PerformaKaryawanController::POIN_LATE }};

/* ═══════════════════════════════════════════════
   SORT TOGGLE — ASC / DESC
   - DESC (default) : Rank 1 → N  (poin tertinggi di atas)
   - ASC            : Rank N → 1  (poin terendah di atas)
═══════════════════════════════════════════════ */
let currentSortDir = 'desc';

function toggleSort() {
    currentSortDir = currentSortDir === 'desc' ? 'asc' : 'desc';
    applySort();
    updateSortUI();
}

function applySort() {
    const tbody = document.getElementById('rankingTbody');
    if (!tbody) return;

    const rows = Array.from(tbody.querySelectorAll('tr'));

    rows.sort((a, b) => {
        const rankA = parseInt(a.dataset.rank, 10) || 0;
        const rankB = parseInt(b.dataset.rank, 10) || 0;
        // DESC → rank kecil (poin tinggi) di atas: rankA - rankB
        // ASC  → rank besar (poin rendah) di atas: rankB - rankA
        return currentSortDir === 'desc' ? rankA - rankB : rankB - rankA;
    });

    // Tambahkan animasi slide dan re-append
    rows.forEach((row, i) => {
        row.classList.remove('pk-row-animate');
        // Trigger reflow agar animasi restart
        void row.offsetWidth;
        row.style.animationDelay = (i * 0.025) + 's';
        row.classList.add('pk-row-animate');
        tbody.appendChild(row);
    });
}

function updateSortUI() {
    const btn         = document.getElementById('btnSortToggle');
    const icon        = document.getElementById('sortIcon');
    const label       = document.getElementById('sortLabel');
    const headerIcon  = document.getElementById('rankHeaderIcon');

    if (!btn) return;

    if (currentSortDir === 'asc') {
        // Tampilkan ASC: poin terendah di atas
        icon.className        = 'bx bx-sort-up';
        label.textContent     = 'Poin Terendah';
        btn.classList.remove('active-desc');
        btn.classList.add('active-asc');
        if (headerIcon) headerIcon.className = 'bx bx-sort-up';
    } else {
        // Tampilkan DESC: poin tertinggi di atas
        icon.className        = 'bx bx-sort-down';
        label.textContent     = 'Poin Tertinggi';
        btn.classList.remove('active-asc');
        btn.classList.add('active-desc');
        if (headerIcon) headerIcon.className = 'bx bx-sort-down';
    }
}

// Inisialisasi UI saat halaman pertama kali load (state DESC)
document.addEventListener('DOMContentLoaded', () => {
    updateSortUI();
    // Pastikan urutan tabel sudah DESC saat load
    applySort();
});

/* ═══════════════════════════════════════════════
   MAIN PAGE CHARTS (PM & Admin only)
═══════════════════════════════════════════════ */
@if(!$isLimitedView && $performaData->count() > 0)
(function () {
    const raw = @json($performaData->values());

    function getSelesai(k) {
        return (k.sebelum_deadline || 0) + (k.tepat_waktu || 0) + (k.terlambat || 0);
    }
    function getPct(k) {
        return k.jumlah_task > 0 ? Math.round(getSelesai(k) / k.jumlah_task * 100) : 0;
    }

    /* ── Chart 1: Stacked Bar ── */
    const sorted1    = [...raw].sort((a, b) => b.poin - a.poin);
    const names1     = sorted1.map(d => truncate(d.nama, 14));
    const dataSblm   = sorted1.map(d => d.sebelum_deadline || 0);
    const dataTepat  = sorted1.map(d => d.tepat_waktu      || 0);
    const dataTelat  = sorted1.map(d => d.terlambat        || 0);
    const totalPoins = sorted1.map(d => d.poin);

    const poinAboveBarPlugin = {
        id: 'poinAboveBar',
        afterDatasetsDraw(chart) {
            const { ctx, scales: { x, y } } = chart;
            ctx.save();
            ctx.font         = '700 10px system-ui, sans-serif';
            ctx.fillStyle    = '#1e1b3a';
            ctx.textAlign    = 'center';
            ctx.textBaseline = 'bottom';
            sorted1.forEach((_, i) => {
                const stackTotal = (dataSblm[i] || 0) + (dataTepat[i] || 0) + (dataTelat[i] || 0);
                const p     = totalPoins[i];
                const label = (p > 0 ? '+' : '') + p + ' poin';
                const xPos  = x.getPixelForValue(i);
                const yPos  = y.getPixelForValue(stackTotal) - 5;
                ctx.fillText(label, xPos, yPos);
            });
            ctx.restore();
        }
    };

    new Chart(document.getElementById('chartAllPoin').getContext('2d'), {
        type: 'bar',
        plugins: [ChartDataLabels, poinAboveBarPlugin],
        data: {
            labels: names1,
            datasets: [
                {
                    label: 'Lebih Awal',
                    data: dataSblm,
                    backgroundColor: 'rgba(34,197,94,.75)',
                    borderColor: '#22c55e', borderWidth: 1.5,
                    borderRadius: { topLeft: 0, topRight: 0, bottomLeft: 4, bottomRight: 4 },
                    stack: 'tasks',
                    datalabels: {
                        display: ctx => ctx.dataset.data[ctx.dataIndex] > 0,
                        formatter: v => v, color: '#fff',
                        font: { weight: '700', size: 10 },
                        anchor: 'center', align: 'center',
                    }
                },
                {
                    label: 'Tepat Waktu',
                    data: dataTepat,
                    backgroundColor: 'rgba(245,158,11,.75)',
                    borderColor: '#f59e0b', borderWidth: 1.5,
                    stack: 'tasks',
                    datalabels: {
                        display: ctx => ctx.dataset.data[ctx.dataIndex] > 0,
                        formatter: v => v, color: '#fff',
                        font: { weight: '700', size: 10 },
                        anchor: 'center', align: 'center',
                    }
                },
                {
                    label: 'Terlambat',
                    data: dataTelat,
                    backgroundColor: 'rgba(239,68,68,.75)',
                    borderColor: '#ef4444', borderWidth: 1.5,
                    borderRadius: { topLeft: 4, topRight: 4, bottomLeft: 0, bottomRight: 0 },
                    stack: 'tasks',
                    datalabels: {
                        display: ctx => ctx.dataset.data[ctx.dataIndex] > 0,
                        formatter: v => v, color: '#fff',
                        font: { weight: '700', size: 10 },
                        anchor: 'center', align: 'center',
                    }
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: true,
            layout: { padding: { top: 22 } },
            plugins: {
                legend: {
                    display: true, position: 'bottom',
                    labels: { font: { size: 11 }, color: '#6b6894', padding: 12 }
                },
                tooltip: {
                    callbacks: {
                        afterBody(items) {
                            const idx = items[0].dataIndex;
                            const p   = totalPoins[idx];
                            return ['Total Poin: ' + (p > 0 ? '+' : '') + p + ' poin'];
                        }
                    }
                },
                datalabels: {}
            },
            scales: {
                x: { stacked: true, grid: { color: '#f0efff' }, ticks: { color: '#6b6894', font: { size: 11 } } },
                y: { stacked: true, grid: { color: '#f0efff' }, ticks: { color: '#6b6894', font: { size: 11 } }, beginAtZero: true }
            }
        }
    });

    /* ── Chart 2: Horizontal Bar ── */
    const sorted2 = [...raw].sort((a, b) => getPct(b) - getPct(a));
    const names2  = sorted2.map(d => truncate(d.nama, 18));
    const pcts    = sorted2.map(d => getPct(d));

    new Chart(document.getElementById('chartAllCompletion').getContext('2d'), {
        type: 'bar',
        plugins: [ChartDataLabels],
        data: {
            labels: names2,
            datasets: [{
                label: '% Selesai',
                data: pcts,
                backgroundColor: pcts.map(v => v >= 80 ? 'rgba(34,197,94,.7)' : v >= 50 ? 'rgba(105,108,255,.7)' : 'rgba(245,158,11,.7)'),
                borderColor:     pcts.map(v => v >= 80 ? '#22c55e' : v >= 50 ? '#696cff' : '#f59e0b'),
                borderWidth: 1.5, borderRadius: 4,
                datalabels: {
                    display: true,
                    formatter: v => v + '%',
                    anchor: 'end', align: 'end',
                    color: '#1e1b3a',
                    font: { weight: '800', size: 10 },
                    offset: 4,
                }
            }]
        },
        options: {
            indexAxis: 'y', responsive: true, maintainAspectRatio: true,
            layout: { padding: { right: 50, left: 8 } },
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ' ' + ctx.raw + '% selesai' } },
                datalabels: {}
            },
            scales: {
                x: { max: 115, grid: { color: '#f0efff' }, ticks: { color: '#6b6894', font: { size: 11 }, callback: v => v + '%' } },
                y: { grid: { display: false }, ticks: { color: '#1e1b3a', font: { size: 11 } } }
            }
        }
    });
})();
@endif

/* ═══════════════════════════════════════════════
   MODAL DETAIL
═══════════════════════════════════════════════ */
let chartPieModal = null;
let chartBarModal = null;

function bukaDetail(idUser, nama) {
    document.getElementById('mdNama').textContent = nama;
    document.getElementById('mdSub').textContent  = '';
    document.getElementById('mdLoading').innerHTML =
        '<div class="pk-spin"></div><p style="font-size:.77rem;color:var(--muted);" class="mb-0">Memuat data…</p>';
    document.getElementById('mdLoading').classList.remove('d-none');
    document.getElementById('mdContent').classList.add('d-none');

    if (chartPieModal) { chartPieModal.destroy(); chartPieModal = null; }
    if (chartBarModal) { chartBarModal.destroy(); chartBarModal = null; }

    new bootstrap.Modal(document.getElementById('modalDetailPerforma')).show();

    fetch(`/performa-karyawan/${idUser}/detail`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(res => {
        if (!res.success) throw new Error(res.message || 'Gagal memuat data');
        renderDetail(res.data);
    })
    .catch(err => {
        document.getElementById('mdLoading').innerHTML =
            `<div class="text-center py-4" style="color:var(--red);">
                <i class="bx bx-error-circle" style="font-size:1.8rem;"></i>
                <p class="mt-2 mb-0" style="font-size:.8rem;">${escHtml(err.message)}</p>
             </div>`;
    });
}

function renderDetail(d) {
    document.getElementById('mdLoading').classList.add('d-none');
    document.getElementById('mdContent').classList.remove('d-none');
    document.getElementById('mdSub').textContent = (d.jabatan || '') + '  ·  ' + (d.email || '');

    /* ── Hitung poin per komponen ── */
    const nProjek = d.total_project    || 0;
    const nTask   = d.total_task       || 0;
    const nSblm   = d.sebelum_deadline || 0;
    const nTepat  = d.tepat_waktu      || 0;
    const nLate   = d.terlambat        || 0;

    const pProjek = nProjek * POIN_PROJECT;
    const pTask   = nTask   * POIN_TASK;
    const pSblm   = nSblm   * POIN_BEFORE;
    const pTepat  = nTepat  * POIN_ON_TIME;
    const pLate   = nLate   * POIN_LATE_RATE;

    document.getElementById('md_proj_formula').textContent  = `${nProjek} × ${POIN_PROJECT} = ${pProjek}`;
    document.getElementById('md_proj_result').textContent   = `${pProjek >= 0 ? '+' : ''}${pProjek} poin`;
    document.getElementById('md_task_formula').textContent  = `${nTask} × ${POIN_TASK} = ${pTask}`;
    document.getElementById('md_task_result').textContent   = `${pTask >= 0 ? '+' : ''}${pTask} poin`;
    document.getElementById('md_sblm_formula').textContent  = `${nSblm} × ${POIN_BEFORE} = ${pSblm}`;
    document.getElementById('md_sblm_result').textContent   = `${pSblm >= 0 ? '+' : ''}${pSblm} poin`;
    document.getElementById('md_tepat_formula').textContent = `${nTepat} × ${POIN_ON_TIME} = ${pTepat}`;
    document.getElementById('md_tepat_result').textContent  = `${pTepat >= 0 ? '+' : ''}${pTepat} poin`;
    document.getElementById('md_late_formula').textContent  = `${nLate} × (${POIN_LATE_RATE}) = ${pLate}`;
    document.getElementById('md_late_result').textContent   = `${pLate} poin`;

    const tp   = d.total_poin;
    const elTP = document.getElementById('md_total_poin');
    elTP.textContent = (tp > 0 ? '+' : '') + tp + ' poin';
    elTP.style.color = tp > 0 ? 'var(--green)' : (tp < 0 ? 'var(--red)' : 'var(--muted)');

    /* ── Progress bar ── */
    const pct          = d.completion_rate || 0;
    const totalSelesai = d.total_selesai   || 0;
    document.getElementById('md_prog_label').textContent  = `${totalSelesai} / ${nTask} task selesai`;
    document.getElementById('md_prog_pct').textContent    = pct + '%';
    document.getElementById('md_prog_detail').textContent =
        `Lebih awal: ${nSblm}  ·  Tepat: ${nTepat}  ·  Terlambat: ${nLate}  ·  Belum selesai: ${nTask - totalSelesai}`;
    setTimeout(() => {
        document.getElementById('md_prog_bar').style.width = pct + '%';
    }, 80);

    /* ── Pie legend ── */
    const tf = nSblm + nTepat + nLate;
    const po = v => tf > 0 ? Math.round(v / tf * 100) : 0;
    document.getElementById('pie_sblm_pct').textContent    = po(nSblm)  + '%';
    document.getElementById('pie_sblm_count').textContent  = nSblm      + ' task';
    document.getElementById('pie_tepat_pct').textContent   = po(nTepat) + '%';
    document.getElementById('pie_tepat_count').textContent = nTepat     + ' task';
    document.getElementById('pie_late_pct').textContent    = po(nLate)  + '%';
    document.getElementById('pie_late_count').textContent  = nLate      + ' task';

    /* ── Doughnut chart ── */
    const ctxD = document.getElementById('chartBreakdown').getContext('2d');
    chartPieModal = new Chart(ctxD, {
        type: 'doughnut',
        plugins: [ChartDataLabels],
        data: {
            labels: ['Lebih Awal', 'Tepat Waktu', 'Terlambat'],
            datasets: [{
                data: [nSblm, nTepat, nLate],
                backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
                borderColor: '#fff', borderWidth: 3, hoverOffset: 6,
            }]
        },
        options: {
            cutout: '58%', responsive: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const p = tf > 0 ? Math.round(ctx.raw / tf * 100) : 0;
                            return ` ${ctx.label}: ${ctx.raw} task (${p}%)`;
                        }
                    }
                },
                datalabels: {
                    display: ctx => ctx.dataset.data[ctx.dataIndex] > 0,
                    formatter(value) {
                        const p = tf > 0 ? Math.round(value / tf * 100) : 0;
                        return value + '\n(' + p + '%)';
                    },
                    color: '#fff',
                    font: { weight: '700', size: 10 },
                    textAlign: 'center',
                }
            }
        }
    });

    /* ── Bar poin per project ── */
    const pd   = (d.project_detail || []).slice().sort((a, b) => b.poin_project - a.poin_project);
    const lbls = pd.map(p => truncate(p.nama_projek, 20));
    const vals = pd.map(p => p.poin_project);
    const bc   = vals.map(v => v >= 0 ? 'rgba(105,108,255,.7)' : 'rgba(239,68,68,.65)');
    const bb   = vals.map(v => v >= 0 ? '#696cff' : '#ef4444');

    const ctxB = document.getElementById('chartPoinProject').getContext('2d');
    chartBarModal = new Chart(ctxB, {
        type: 'bar',
        plugins: [ChartDataLabels],
        data: {
            labels: lbls,
            datasets: [{
                label: 'Poin',
                data: vals,
                backgroundColor: bc, borderColor: bb, borderWidth: 1.5, borderRadius: 4,
                datalabels: {
                    display: true,
                    formatter: v => (v > 0 ? '+' : '') + v + ' poin',
                    anchor: 'end', align: 'right',
                    color: '#1e1b3a',
                    font: { weight: '700', size: 10 },
                    offset: 4, clip: false,
                }
            }]
        },
        options: {
            indexAxis: 'y', responsive: true, maintainAspectRatio: false,
            layout: { padding: { right: 80, left: 8, top: 4, bottom: 4 } },
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ' ' + (ctx.raw > 0 ? '+' : '') + ctx.raw + ' poin' } },
                datalabels: {}
            },
            scales: {
                x: { grid: { color: '#f0efff' }, ticks: { color: '#6b6894', font: { size: 10 } }, grace: '15%' },
                y: { grid: { display: false }, ticks: { color: '#1e1b3a', font: { size: 10 } } }
            }
        }
    });

    /* ── Project list ── */
    const container = document.getElementById('md_projects');
    const rawPd     = d.project_detail || [];
    if (!rawPd.length) {
        container.innerHTML = '<p class="text-center py-3" style="font-size:.8rem;color:var(--muted);">Belum tergabung di project manapun.</p>';
        return;
    }
    const statusMap = {
        pending:     { cls: 'pk-status-pending',     label: 'Pending' },
        aktif:       { cls: 'pk-status-aktif',       label: 'Aktif' },
        in_progress: { cls: 'pk-status-in_progress', label: 'In Progress' },
        selesai:     { cls: 'pk-status-selesai',     label: 'Selesai' },
    };
    container.innerHTML = rawPd.map(p => {
        const st = statusMap[p.status_projek] || { cls: 'pk-status-selesai', label: p.status_projek };
        const sp = p.total_task > 0 ? Math.round(p.selesai / p.total_task * 100) : 0;
        const pc = p.poin_project >= 0 ? 'pk-poin-pos' : 'pk-poin-neg';
        return `
        <div class="pk-proj-item">
            <div class="d-flex align-items-start justify-content-between mb-1">
                <div>
                    <div class="pk-proj-name">${escHtml(p.nama_projek)}</div>
                    <div style="font-size:.72rem;color:var(--muted);margin-top:2px;">
                        <span class="pk-status-dot ${st.cls}"></span>${st.label}
                    </div>
                </div>
                <span class="pk-poin ${pc}" style="font-size:.76rem;flex-shrink:0;margin-left:8px;">
                    ${p.poin_project >= 0 ? '+' : ''}${p.poin_project} poin
                </span>
            </div>
            <div class="d-flex flex-wrap gap-2 mt-1" style="font-size:.73rem;color:var(--muted);">
                <span>Task: <strong style="color:var(--text)">${p.total_task}</strong></span>
                <span style="color:var(--green)">Selesai: <strong>${p.selesai}</strong></span>
                <span>Belum: <strong style="color:var(--text)">${p.belum_selesai}</strong></span>
                <span style="color:var(--green)">Lebih awal: <strong>${p.sebelum_deadline}</strong></span>
                <span style="color:var(--amber)">Tepat: <strong>${p.tepat_waktu}</strong></span>
                <span style="color:var(--red)">Terlambat: <strong>${p.terlambat}</strong></span>
            </div>
            ${p.total_task > 0 ? `
            <div style="height:4px;background:var(--border);border-radius:2px;overflow:hidden;margin-top:8px;">
                <div style="height:100%;width:${sp}%;background:var(--p1);border-radius:2px;"></div>
            </div>
            <div style="font-size:.67rem;color:var(--muted);margin-top:3px;text-align:right;">${sp}% selesai</div>
            ` : ''}
        </div>`;
    }).join('');
}

/* ── Helpers ── */
function escHtml(str) {
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}
function truncate(str, n) {
    return str && str.length > n ? str.slice(0, n) + '…' : str;
}
</script>
@endpush