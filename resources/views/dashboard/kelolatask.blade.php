@extends('layouts.master')
@section('title', 'Kelola Task - ' . $projek->nama_projek)
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
<style>
:root {
    --primary-blue: #4F46E5; --primary-light: #EEF2FF;
    --success-green: #10B981; --success-light: #D1FAE5;
    --warning-orange: #F59E0B; --warning-light: #FEF3C7;
    --danger-red: #EF4444; --danger-light: #FEE2E2;
    --purple: #8B5CF6; --purple-light: #EDE9FE;
    --gray-50:#F9FAFB; --gray-100:#F3F4F6; --gray-200:#E5E7EB;
    --gray-300:#D1D5DB; --gray-400:#9CA3AF; --gray-500:#6B7280;
    --gray-600:#4B5563; --gray-700:#374151; --gray-800:#1F2937; --gray-900:#111827;
}
.page-content-wrapper { padding:24px; max-width:100%; box-sizing:border-box; }

/* ── Project Header ── */
.project-header-card { background:white;border-radius:16px;box-shadow:0 1px 3px rgba(0,0,0,.1);margin-bottom:24px;overflow:hidden;border:1px solid var(--gray-200); }
.project-header-top { background:linear-gradient(135deg,var(--primary-blue) 0%,var(--purple) 100%);padding:24px 28px;position:relative;overflow:hidden; }
.project-header-top::before { content:'';position:absolute;top:-50px;right:-50px;width:200px;height:200px;background:radial-gradient(circle,rgba(255,255,255,.1) 0%,transparent 70%);border-radius:50%; }
.project-header-content { position:relative;z-index:1; }
.project-icon { width:48px;height:48px;background:rgba(255,255,255,.2);backdrop-filter:blur(10px);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;border:2px solid rgba(255,255,255,.3);font-size:24px;flex-shrink:0; }
.project-title { color:white;font-weight:700;font-size:22px;margin-bottom:6px;line-height:1.2; }
.project-desc { color:rgba(255,255,255,.9);margin-bottom:0;font-size:14px; }
.project-meta { display:flex;gap:20px;flex-wrap:wrap;margin-top:12px; }
.project-meta-item { display:flex;align-items:center;gap:6px;color:rgba(255,255,255,.95);font-size:13px;font-weight:500; }
.pm-badge { display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);border-radius:20px;padding:6px 14px;color:white;font-size:13px;font-weight:600;margin-top:10px; }
.pm-avatar { width:26px;height:26px;border-radius:50%;background:rgba(255,255,255,.3);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700; }
.btn-invite { background:rgba(255,255,255,.2);backdrop-filter:blur(10px);border:2px solid rgba(255,255,255,.3);color:white;font-weight:600;padding:8px 16px;border-radius:8px;transition:all .3s;font-size:14px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;text-decoration:none; }
.btn-invite:hover { background:rgba(255,255,255,.3);color:white;transform:translateY(-2px); }

/* ── Dashboard Grid 2 Kolom ── */
.dashboard-grid-2 { display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;align-items:stretch; }
.dashboard-card { background:white;border-radius:12px;padding:24px;border:1px solid var(--gray-200);box-shadow:0 1px 3px rgba(0,0,0,.1);height:100%;display:flex;flex-direction:column; }
.chart-title { font-size:18px;font-weight:700;color:var(--gray-900);margin-bottom:4px; }
.chart-subtitle { font-size:14px;color:var(--gray-600);margin-bottom:16px; }
#employeePerformanceChart { height:280px;width:100%;flex-shrink:0; }
.chart-legend { display:flex;gap:20px;margin-top:16px;flex-wrap:wrap; }
.legend-item { display:flex;align-items:center;gap:8px;font-size:12px;font-weight:500; }
.legend-color { width:12px;height:12px;border-radius:2px; }
.legend-color.ontime { background:var(--success-green); }
.legend-color.early  { background:var(--primary-blue); }
.legend-color.late   { background:var(--danger-red); }
.chart-controls { display:flex;gap:8px;margin-top:12px;flex-wrap:wrap; }
.chart-control-btn { padding:6px 12px;background:var(--gray-100);border:1px solid var(--gray-300);border-radius:6px;font-size:12px;font-weight:600;color:var(--gray-700);cursor:pointer;transition:all .2s; }
.chart-control-btn.active { background:var(--primary-blue);color:white;border-color:var(--primary-blue); }

/* ── Progress Summary Card ── */
.progress-summary-card { background:white;border-radius:12px;padding:24px;border:1px solid var(--gray-200);box-shadow:0 1px 3px rgba(0,0,0,.1);height:100%;display:flex;flex-direction:column; }
.progress-header { display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px; }
.progress-big-number { font-size:48px;font-weight:800;color:var(--success-green);line-height:1; }
.progress-label { font-size:11px;color:var(--gray-500);font-weight:600;margin-top:2px; }
.progress-weight { font-size:11px;color:var(--gray-600);font-weight:700;margin-top:2px; }
.progress-bar-track { height:14px;background:var(--gray-100);border-radius:99px;overflow:hidden;border:1px solid var(--gray-200);margin-bottom:28px; }
.progress-bar-fill { height:100%;border-radius:99px;transition:width .6s cubic-bezier(.4,0,.2,1);background:linear-gradient(90deg,#10B981,#059669); }
.progress-bar-sm { height:8px;background:var(--gray-100);border-radius:99px;overflow:hidden;border:1px solid var(--gray-200); }
.progress-bar-fill-sm { height:100%;border-radius:99px;transition:width .5s ease; }
.progress-2col { display:grid;grid-template-columns:1fr 1fr;gap:28px;flex:1; }
.progress-col-title { font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:var(--gray-500);margin-bottom:14px;display:flex;align-items:center;gap:7px; }
.progress-col-title span { display:inline-block;width:3px;height:14px;background:var(--primary-blue);border-radius:2px;flex-shrink:0; }
.progress-col-title.right span { background:var(--success-green); }
.bars-container { display:flex;flex-direction:column;gap:14px; }

/* ── Task Sheet ── */
.task-sheet-container { background:white;border:1px solid var(--gray-200);border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.1); }
.task-sheet-header { background:var(--gray-50);border-bottom:1px solid var(--gray-200);padding:20px 24px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px; }
.task-sheet-title { font-size:20px;font-weight:700;color:var(--gray-900);margin-bottom:4px; }
.task-sheet-subtitle { font-size:13px;color:var(--gray-600);margin:0; }
.header-actions { display:flex;gap:10px; }
.btn-action { padding:9px 16px;border-radius:8px;font-size:13px;font-weight:600;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:7px;transition:all .2s; }
.btn-outline-primary { background:white;color:var(--primary-blue);border:2px solid var(--primary-blue); }
.btn-outline-primary:hover { background:var(--primary-blue);color:white; }
.btn-primary-custom { background:var(--primary-blue);color:white; }
.btn-primary-custom:hover { background:var(--purple); }

/* ── Filter Bar ── */
.filter-bar { padding:12px 20px;background:white;border-bottom:2px solid var(--gray-200);display:flex;gap:10px;align-items:center;flex-wrap:wrap;position:sticky;top:0;z-index:20; }
.filter-group { display:flex;align-items:center;gap:5px;flex-wrap:wrap; }
.filter-label { font-size:10px;font-weight:800;color:var(--gray-500);text-transform:uppercase;letter-spacing:.06em;white-space:nowrap;margin-right:2px; }
.filter-btn { padding:4px 11px;border-radius:20px;font-size:11px;font-weight:600;border:1.5px solid var(--gray-300);background:white;color:var(--gray-600);cursor:pointer;transition:all .18s;white-space:nowrap;line-height:1.5; }
.filter-btn:hover { border-color:var(--primary-blue);color:var(--primary-blue);background:var(--primary-light); }
.filter-btn.active { color:white !important; }
.filter-btn.f-todo.active    { background:var(--gray-500);border-color:var(--gray-500); }
.filter-btn.f-draft.active   { background:var(--gray-400);border-color:var(--gray-400); }
.filter-btn.f-progress.active{ background:var(--warning-orange);border-color:var(--warning-orange); }
.filter-btn.f-done.active    { background:var(--success-green);border-color:var(--success-green); }
.filter-btn.f-review.active  { background:var(--purple);border-color:var(--purple); }
.filter-btn.f-revisi.active  { background:var(--warning-orange);border-color:var(--warning-orange); }
.filter-btn.f-approved.active{ background:#0EA5E9;border-color:#0EA5E9; }
.filter-btn.f-null.active    { background:var(--gray-500);border-color:var(--gray-500); }
.filter-divider { width:1px;height:20px;background:var(--gray-200);flex-shrink:0; }
.filter-results-badge { font-size:11px;font-weight:700;color:var(--primary-blue);background:var(--primary-light);border:1px solid #C7D2FE;border-radius:20px;padding:3px 10px;white-space:nowrap; }
.btn-clear-filter { padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;border:1.5px solid var(--danger-red);background:white;color:var(--danger-red);cursor:pointer;transition:all .18s;display:inline-flex;align-items:center;gap:4px; }
.btn-clear-filter:hover { background:var(--danger-red);color:white; }

/* ── Search wrapper ── */
.search-wrapper { position:relative;display:flex;align-items:center; }
.search-wrapper i { position:absolute;left:9px;color:var(--gray-400);font-size:15px;pointer-events:none; }
.filter-search { padding:5px 10px 5px 30px;border:1.5px solid var(--gray-300);border-radius:20px;font-size:12px;font-weight:500;color:var(--gray-800);background:white;outline:none;transition:all .18s;width:200px; }
.filter-search:focus { border-color:var(--primary-blue);box-shadow:0 0 0 3px var(--primary-light); }

/* ── Sort header ── */
.th-sortable { cursor:pointer;user-select:none;transition:background .15s;position:relative; }
.th-sortable:hover { background:var(--gray-100) !important; }
.sort-icon { display:inline-flex;flex-direction:column;gap:0;margin-left:5px;vertical-align:middle;opacity:.35;transition:opacity .15s; }
.sort-icon.asc  { opacity:1;color:var(--primary-blue); }
.sort-icon.desc { opacity:1;color:var(--primary-blue); }
.sort-icon svg  { display:block; }

/* ── Task Table ── */
.task-table-wrapper { overflow-x:auto;max-height:700px; }
.task-table { width:100%;border-collapse:collapse; }
.task-table thead { position:sticky;top:0;z-index:10;background:var(--gray-50); }
.task-table th { padding:13px 10px;text-align:left;font-size:11px;font-weight:700;color:var(--gray-700);text-transform:uppercase;letter-spacing:.05em;border-bottom:2px solid var(--gray-200);white-space:nowrap; }
.task-table td { padding:10px;border-bottom:1px solid var(--gray-100);vertical-align:middle; }
.task-table tbody tr:hover { background:var(--gray-50); }
.task-table tbody tr.is-new { animation:rowHL .6s ease; }
.task-table tbody tr.is-approved { background:linear-gradient(90deg,rgba(16,185,129,.04) 0,transparent 100%); }
.task-table tbody tr.is-revisi { background:linear-gradient(90deg,rgba(245,158,11,.04) 0,transparent 100%); }
@keyframes rowHL { 0%{background:#EEF2FF}100%{background:transparent} }
.task-number { width:36px;height:36px;border-radius:8px;background:var(--primary-light);color:var(--primary-blue);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px; }
.task-number.approved { background:var(--success-light);color:#059669; }
.task-number.revisi   { background:var(--warning-light);color:#D97706; }

/* ── Action Buttons ── */
.action-cell { display:flex;flex-direction:column;gap:5px;width:56px;align-items:center; }
.action-btn { width:32px;height:32px;border-radius:7px;border:1px solid var(--gray-300);background:white;color:var(--gray-700);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s;font-size:15px; }
.action-btn:hover  { transform:translateY(-2px);box-shadow:0 3px 8px rgba(0,0,0,.1); }
.action-btn.view:hover   { border-color:var(--primary-blue);color:var(--primary-blue);background:var(--primary-light); }
.action-btn.delete:hover { border-color:var(--danger-red);color:var(--danger-red);background:var(--danger-light); }
.action-btn:disabled,.action-btn[disabled] { opacity:.38;cursor:not-allowed;pointer-events:none; }

/* ── Inline Editable ── */
.cell-input { font-size:14px;font-weight:600;color:var(--gray-900);border:1px solid transparent;background:transparent;padding:5px 8px;border-radius:6px;width:100%;transition:all .2s; }
.cell-input:hover  { background:var(--gray-50); }
.cell-input:focus  { border-color:var(--primary-blue);background:white;outline:none;box-shadow:0 0 0 3px var(--primary-light); }
.cell-input:disabled { opacity:.6;cursor:not-allowed;background:var(--gray-50); }
.cell-textarea { font-size:12px;color:var(--gray-600);border:1px solid transparent;background:transparent;padding:5px 8px;border-radius:6px;width:100%;resize:none;min-height:40px;transition:all .2s; }
.cell-textarea:hover { background:var(--gray-50); }
.cell-textarea:focus { border-color:var(--gray-300);background:white;outline:none; }
.cell-textarea:disabled { opacity:.6;cursor:not-allowed;background:var(--gray-50); }
.compact-select { border:1px solid var(--gray-300);border-radius:6px;padding:7px 10px;font-size:12px;background:white;color:var(--gray-800);cursor:pointer;width:100%;transition:all .2s;font-weight:500; }
.compact-select:focus { border-color:var(--primary-blue);outline:none;box-shadow:0 0 0 3px var(--primary-light); }
.compact-select:disabled { opacity:.55;cursor:not-allowed;background:var(--gray-100); }

/* ── Status Badges ── */
.status-badge { display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border-radius:5px;font-size:10px;font-weight:600;text-transform:capitalize;border:1px solid transparent;white-space:nowrap; }
.status-badge::before { content:'';width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0; }
.status-todo     { background:var(--gray-100);color:var(--gray-700);border-color:var(--gray-200); }
.status-draft    { background:var(--gray-100);color:var(--gray-600);border-color:var(--gray-200); }
.status-progress { background:var(--warning-light);color:#D97706;border-color:#FDE68A; }
.status-review   { background:var(--purple-light);color:var(--purple);border-color:#DDD6FE; }
.status-done     { background:var(--success-light);color:#059669;border-color:#A7F3D0; }
.sa-badge { display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:5px;font-size:10px;font-weight:700;text-transform:uppercase; }
.sa-review   { background:#EDE9FE;color:#7C3AED; }
.sa-revisi   { background:#FEF3C7;color:#D97706; }
.sa-approved { background:#D1FAE5;color:#059669; }
.sa-null     { background:var(--gray-100);color:var(--gray-500); }

/* ── Level Badge ── */
.level-badge { display:inline-flex;align-items:center;padding:3px 9px;border-radius:5px;font-size:10px;font-weight:700;text-transform:uppercase;color:white;white-space:nowrap; }
.level-mudah  { background:linear-gradient(135deg,#10B981,#059669); }
.level-medium { background:linear-gradient(135deg,var(--warning-orange),#D97706); }
.level-susah  { background:linear-gradient(135deg,var(--danger-red),#DC2626); }
.weight-badge { display:inline-flex;align-items:center;gap:4px;background:var(--gray-100);color:var(--gray-700);border-radius:5px;padding:3px 8px;font-size:10px;font-weight:700; }

/* ── Approved/Revisi lock banner ── */
.approved-lock-banner { display:flex;align-items:center;gap:5px;background:#D1FAE5;border:1px solid #A7F3D0;border-radius:6px;padding:5px 9px;font-size:10px;font-weight:700;color:#059669;margin-top:5px; }
.revisi-edit-banner { display:flex;align-items:center;gap:5px;background:#FEF3C7;border:1px solid #FDE68A;border-radius:6px;padding:5px 9px;font-size:10px;font-weight:700;color:#D97706;margin-top:5px; }

/* ── Brief/Hasil Cell ── */
.media-summary-cell { display:flex;flex-direction:column;gap:5px; }
.media-type-pill { display:inline-flex;align-items:center;gap:4px;padding:4px 9px;border-radius:20px;font-size:10px;font-weight:700;cursor:pointer;transition:all .2s;white-space:nowrap;border:1px solid transparent;width:100%;justify-content:space-between; }
.media-type-pill.brief-pill { background:var(--primary-light);color:var(--primary-blue);border-color:#C7D2FE; }
.media-type-pill.brief-pill:hover { background:var(--primary-blue);color:white; }
.media-type-pill.hasil-pill { background:var(--success-light);color:#059669;border-color:#A7F3D0; }
.media-type-pill.hasil-pill:hover { background:var(--success-green);color:white; }
.pill-left { display:flex;align-items:center;gap:4px; }
.media-count-dot { width:16px;height:16px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:9px;font-weight:800;flex-shrink:0; }
.media-count-dot.brief-dot { background:var(--primary-blue);color:white; }
.media-count-dot.hasil-dot { background:var(--success-green);color:white; }
.media-count-dot.empty-dot { background:var(--gray-300);color:var(--gray-600); }

/* ── Timeline Status ── */
.timeline-status { font-size:9px;font-weight:700;padding:2px 6px;border-radius:4px;display:inline-block;text-transform:uppercase;white-space:nowrap; }
.timeline-ontime   { background:var(--warning-light);color:#D97706; }
.timeline-late     { background:var(--danger-light);color:var(--danger-red); }
.timeline-early    { background:var(--success-light);color:var(--success-green); }
.timeline-inprogress { background:#DBEAFE;color:#1D4ED8; }
.timeline-overdue  { background:var(--danger-light);color:var(--danger-red); }
.timeline-upcoming { background:var(--warning-light);color:#D97706; }
.timeline-todo     { background:var(--gray-100);color:var(--gray-600); }
.timeline-todo_overdue { background:var(--danger-light);color:var(--danger-red); }
.timeline-todo_upcoming { background:var(--warning-light);color:#D97706; }
.done-date-row { display:flex;align-items:center;gap:4px;margin-top:3px;font-size:10px;font-weight:700;padding:3px 7px;border-radius:5px;white-space:nowrap; }
.done-date-row.early  { background:var(--success-light);color:#059669; }
.done-date-row.late   { background:var(--danger-light);color:var(--danger-red); }
.done-date-row.ontime { background:var(--warning-light);color:#D97706; }
.done-date-row.overdue{ background:#FEF3C7;color:#92400E; }

/* ── GANTT ── */
.gantt-nav-btns { display:flex;gap:3px;align-items:center;margin-bottom:4px; }
.gantt-nav-btn { padding:3px 8px;border:1px solid var(--gray-300);border-radius:5px;background:white;font-size:10px;font-weight:700;color:var(--gray-700);cursor:pointer;transition:all .15s;white-space:nowrap;line-height:1.4; }
.gantt-nav-btn:hover { background:var(--primary-blue);color:white;border-color:var(--primary-blue); }
.gantt-nav-btn.today-btn { background:var(--primary-blue);color:white;border-color:var(--primary-blue); }
.gantt-period-label { font-size:10px;font-weight:700;color:var(--gray-500);padding:2px 6px;background:var(--gray-100);border-radius:4px;border:1px solid var(--gray-200);white-space:nowrap; }
.gantt-outer { position:relative;width:100%;background:var(--gray-50);border-radius:8px;border:1px solid var(--gray-200);overflow:hidden;user-select:none; }
.gantt-ruler-area { position:relative;height:44px;background:white;border-bottom:1px solid var(--gray-200);overflow:hidden; }
.gantt-ruler-inner { position:absolute;top:0;left:0;height:100%; }
.gantt-track-area { position:relative;height:54px;background:var(--gray-50);overflow:hidden; }
.gantt-track-inner { position:absolute;top:0;left:0;height:100%; }
.gantt-bar { position:absolute;top:10px;height:34px;border-radius:6px;box-shadow:0 2px 8px rgba(0,0,0,.15);cursor:grab;z-index:5;min-width:6px;transition:box-shadow .15s; }
.gantt-bar:active { cursor:grabbing; }
.gantt-bar:hover  { box-shadow:0 4px 14px rgba(0,0,0,.22);z-index:10; }
.gantt-bar.locked { cursor:not-allowed;opacity:.7; }
.gantt-bar-inner { height:100%;border-radius:6px;display:flex;align-items:center;justify-content:space-between;padding:0 10px;overflow:hidden;white-space:nowrap;position:relative; }
.gantt-bar-inner.draft    { background:linear-gradient(135deg,var(--gray-300),var(--gray-400)); }
.gantt-bar-inner.todo     { background:linear-gradient(135deg,var(--gray-400),var(--gray-500)); }
.gantt-bar-inner.progress { background:linear-gradient(135deg,var(--warning-orange),#D97706); }
.gantt-bar-inner.review   { background:linear-gradient(135deg,var(--purple),#7C3AED); }
.gantt-bar-inner.done     { background:linear-gradient(135deg,var(--success-green),#059669); }
.gantt-bar-lbl { font-size:9px;font-weight:700;color:rgba(255,255,255,.95);flex-shrink:0; }
.gantt-bar-title { flex:1;text-align:center;font-size:9px;font-weight:700;overflow:hidden;text-overflow:ellipsis;padding:0 4px;color:white; }
.gantt-resize { position:absolute;top:0;bottom:0;width:9px;cursor:ew-resize;z-index:12;display:flex;align-items:center;justify-content:center; }
.gantt-resize.r-left  { left:0; }
.gantt-resize.r-right { right:0; }
.gantt-resize::after { content:'';width:2px;height:12px;background:rgba(255,255,255,.75);border-radius:1px;display:block; }

/* ── Add Row ── */
.add-row-btn { width:100%;padding:16px;background:var(--gray-50);border:none;border-top:1px solid var(--gray-200);color:var(--primary-blue);font-weight:700;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .2s; }
.add-row-btn:hover { background:var(--primary-light); }
.empty-state { padding:60px 24px;text-align:center; }
.empty-state i { font-size:64px;color:var(--gray-300);display:block;margin-bottom:12px; }
.empty-state p { color:var(--gray-500);margin-bottom:6px; }
.task-table-wrapper::-webkit-scrollbar { width:8px;height:8px; }
.task-table-wrapper::-webkit-scrollbar-track { background:var(--gray-100);border-radius:4px; }
.task-table-wrapper::-webkit-scrollbar-thumb { background:var(--gray-400);border-radius:4px; }

/* ══════════════════════════════════════════
   TOAST NOTIFICATION — FIXED & COMPLETE
   (sama persis dgn halaman master-data-projek)
══════════════════════════════════════════ */
#toast-notif {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 99999;
    min-width: 300px;
    max-width: 420px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,.18), 0 2px 8px rgba(0,0,0,.10);
    overflow: hidden;
    transform: translateY(120%) scale(.95);
    opacity: 0;
    transition: transform .35s cubic-bezier(.34,1.56,.64,1), opacity .3s ease;
    pointer-events: none;
    font-family: inherit;
}
#toast-notif.show {
    transform: translateY(0) scale(1);
    opacity: 1;
    pointer-events: auto;
}
#toast-notif.success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
#toast-notif.error   { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
#toast-notif.saving  { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
#toast-notif.info    { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }

#toast-accent {
    height: 3px;
    background: rgba(255,255,255,.35);
    width: 100%;
}
#toast-body {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
}
#toast-icon-wrap {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,.2);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
#toast-icon {
    font-size: 20px;
    color: white;
    display: block;
}
#toast-content {
    flex: 1;
    min-width: 0;
}
#toast-title {
    font-size: 13px;
    font-weight: 700;
    color: white;
    line-height: 1.3;
    margin: 0 0 2px;
}
#toast-msg {
    font-size: 12px;
    color: rgba(255,255,255,.88);
    line-height: 1.4;
    word-break: break-word;
    margin: 0;
}
#toast-close {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: rgba(255,255,255,.15);
    border: none;
    color: white;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: background .15s;
    padding: 0;
    line-height: 1;
}
#toast-close:hover { background: rgba(255,255,255,.30); }
#toast-progress {
    height: 3px;
    background: rgba(255,255,255,.20);
    overflow: hidden;
}
#toast-progress-bar {
    height: 100%;
    background: rgba(255,255,255,.55);
    width: 100%;
    transition: width linear;
}

/* ══════════════════════════════════════════
   MODAL STYLES
══════════════════════════════════════════ */
.modal-header-gradient {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--purple) 100%);
    padding: 20px 24px 16px;
    position: relative;
    border-radius: 12px 12px 0 0;
}
.modal-title-custom {
    font-size: 16px;
    font-weight: 700;
    color: white;
    margin: 0 0 4px;
    display: flex;
    align-items: center;
}
.modal-subtitle {
    font-size: 12px;
    color: rgba(255,255,255,.82);
    margin: 0;
    font-weight: 500;
}
.tim-member-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    margin-bottom: 7px;
    background: var(--gray-50);
    transition: background .15s;
}
.tim-member-item:hover { background: white; }
.member-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: var(--primary-blue);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 13px;
    flex-shrink: 0;
}
.member-info { flex: 1; min-width: 0; }
.member-name { font-size: 13px; font-weight: 700; color: var(--gray-900); }
.member-role { font-size: 11px; color: var(--gray-500); margin-top: 2px; }
.member-remove-btn {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    border: 1px solid var(--gray-300);
    background: white;
    color: var(--danger-red);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 15px;
    transition: all .15s;
}
.member-remove-btn:hover { background: var(--danger-light); border-color: var(--danger-red); }
.user-checkbox-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-radius: 7px;
    cursor: pointer;
    transition: background .12s;
    margin-bottom: 4px;
}
.user-checkbox-item:hover { background: var(--gray-50); }
.user-checkbox-item.selected { background: var(--primary-light); }
.user-checkbox-item input[type="checkbox"] { flex-shrink: 0; }
.form-control-sm-custom {
    width: 100%;
    padding: 8px 12px;
    border: 1.5px solid var(--gray-300);
    border-radius: 7px;
    font-size: 13px;
    outline: none;
    transition: border-color .18s;
}
.form-control-sm-custom:focus { border-color: var(--primary-blue); }

/* ── Preview Modal ── */
.preview-info-panel {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: 10px;
    padding: 18px;
    margin-bottom: 18px;
}
.preview-task-title { font-size:17px;font-weight:700;color:var(--gray-900);margin-bottom:8px;line-height:1.3; }
.preview-task-desc  { font-size:13px;color:var(--gray-600);margin-bottom:14px;line-height:1.6; }
.preview-meta-grid  { display:grid;grid-template-columns:1fr 1fr;gap:10px; }
.preview-meta-item  { display:flex;flex-direction:column;gap:3px; }
.preview-meta-label { font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-500); }
.preview-meta-value { font-size:13px;font-weight:600;color:var(--gray-900); }
.preview-media-section { border:1px solid var(--gray-200);border-radius:10px;overflow:hidden;margin-bottom:14px; }
.preview-media-header { padding:9px 14px;font-size:12px;font-weight:700;display:flex;align-items:center;gap:7px; }
.brief-hdr { background:var(--primary-light);color:var(--primary-blue); }
.hasil-hdr { background:var(--success-light);color:#059669; }
.preview-media-body { padding:12px; }
.preview-gallery { display:flex;flex-wrap:wrap;gap:8px; }
.preview-thumb { width:100px;height:70px;object-fit:cover;border-radius:6px;border:1px solid var(--gray-200);cursor:pointer;transition:transform .2s; }
.preview-thumb:hover { transform:scale(1.04); }
.preview-thumb.hasil-thumb { width:120px;height:80px; }
.preview-doc-item { display:flex;flex-direction:column;align-items:center;gap:4px;padding:10px 12px;background:var(--gray-50);border:1px solid var(--gray-200);border-radius:7px;font-size:11px;color:var(--primary-blue);text-decoration:none;font-weight:600;transition:all .2s; }
.preview-doc-item:hover { background:var(--primary-light); }
.preview-doc-item i { font-size:22px; }
.preview-empty-media { font-size:12px;color:var(--gray-400);font-style:italic; }

/* ── Upload Gallery (Modal Media) ── */
.upload-section { border:1px solid var(--gray-200);border-radius:10px;overflow:hidden;margin-bottom:16px; }
.upload-section-header { padding:9px 14px;font-size:12px;font-weight:700;display:flex;align-items:center;gap:7px; }
.upload-section-body { padding:12px; }
.gallery-grid { display:flex;flex-wrap:wrap;gap:8px;margin-bottom:10px; }
.gallery-item { position:relative;width:90px;height:65px;border-radius:7px;overflow:hidden;border:1px solid var(--gray-200); }
.gallery-item.hasil-item { width:110px;height:75px; }
.gallery-item img { width:100%;height:100%;object-fit:cover;cursor:pointer; }
.gallery-doc { display:flex;flex-direction:column;align-items:center;gap:4px;padding:8px 10px;background:var(--gray-50);border:1px solid var(--gray-200);border-radius:7px;font-size:10px;font-weight:600;color:var(--gray-700);cursor:pointer;position:relative;transition:background .15s; }
.gallery-doc:hover { background:var(--gray-100); }
.gallery-doc i { font-size:20px;color:var(--primary-blue); }
.g-remove { position:absolute;top:-5px;right:-5px;width:18px;height:18px;border-radius:50%;background:var(--danger-red);border:none;color:white;font-size:11px;display:flex;align-items:center;justify-content:center;cursor:pointer;padding:0;opacity:0;transition:opacity .15s; }
.gallery-item:hover .g-remove,
.gallery-doc:hover .g-remove { opacity:1; }
.drag-drop-zone { display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;padding:18px;border:2px dashed var(--gray-300);border-radius:8px;cursor:pointer;text-align:center;transition:all .2s;background:var(--gray-50); }
.drag-drop-zone:hover,.drag-drop-zone.dragover { border-color:var(--primary-blue);background:var(--primary-light); }
.drag-drop-zone i { font-size:28px;color:var(--primary-blue); }
.drag-drop-zone p { font-size:13px;font-weight:600;color:var(--gray-700);margin:0; }
.drag-drop-zone small { font-size:11px;color:var(--gray-400); }
.drag-drop-zone.hasil-zone i { color:#059669; }
.drag-drop-zone.hasil-zone:hover,.drag-drop-zone.hasil-zone.dragover { border-color:#059669;background:var(--success-light); }

/* ── Responsive ── */
@media(max-width:1200px){ .dashboard-grid-2{grid-template-columns:1fr} }
@media(max-width:768px) { .task-sheet-header{flex-direction:column}.filter-bar{padding:10px 14px;} }
</style>
@endpush
@section('content')
@php
    $pm       = $projek->pembuat;
    $pmNama   = optional($pm)->nama ?? '—';
    $pmAvatar = strtoupper(substr($pmNama, 0, 2));
    $tglMulai = $projek->tanggal_mulai   ? \Carbon\Carbon::parse($projek->tanggal_mulai)->format('d M Y')   : null;
    $tglAkhir = $projek->tanggal_selesai ? \Carbon\Carbon::parse($projek->tanggal_selesai)->format('d M Y') : null;
@endphp
<div class="page-content-wrapper">

{{-- ── Project Header ── --}}
<div class="project-header-card">
    <div class="project-header-top">
        <div class="project-header-content">
            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                <div class="d-flex align-items-start gap-3 flex-1">
                    <div class="project-icon"><i class="bx bx-task"></i></div>
                    <div>
                        <h4 class="project-title">{{ $projek->nama_projek }}</h4>
                        @if($projek->deskripsi)<p class="project-desc">{{ $projek->deskripsi }}</p>@endif
                        <div class="project-meta">
                            <div class="project-meta-item"><i class="bx bx-calendar"></i><span>{{ $tglMulai ?? 'Belum diatur' }} &rarr; {{ $tglAkhir ?? 'Tidak ada target' }}</span></div>
                            @if($projek->kategoriProjek)<div class="project-meta-item"><i class="bx bx-purchase-tag-alt"></i><span>{{ $projek->kategoriProjek->nama_kategori }}</span></div>@endif
                        </div>
                        <div class="pm-badge"><div class="pm-avatar">{{ $pmAvatar }}</div><span>PM: {{ $pmNama }}</span></div>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('master-data-projek.index') }}" class="btn-invite" style="background:rgba(255,255,255,.1);"><i class="bx bx-arrow-back"></i> Kembali</a>
                    <button class="btn-invite" data-bs-toggle="modal" data-bs-target="#modalInviteTim"><i class="bx bx-user-plus"></i> Kelola Tim</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Dashboard Grid 2 Kolom ── --}}
<div class="dashboard-grid-2">
    {{-- KIRI: Performance Chart --}}
    <div class="dashboard-card">
        <h5 class="chart-title">Performance Anggota Tim</h5>
        <p class="chart-subtitle">Distribusi penyelesaian task berdasarkan penanggung jawab</p>
        <div id="employeePerformanceChart"></div>
        <div class="chart-legend">
            <div class="legend-item"><div class="legend-color ontime"></div><span>Tepat Waktu</span></div>
            <div class="legend-item"><div class="legend-color early"></div><span>Sebelum Deadline</span></div>
            <div class="legend-item"><div class="legend-color late"></div><span>Terlambat</span></div>
        </div>
        <div class="chart-controls">
            <button class="chart-control-btn active" onclick="changeChartView('bar',this)">Bar Chart</button>
            <button class="chart-control-btn" onclick="changeChartView('stacked',this)">Stacked Bar</button>
            <button class="chart-control-btn" onclick="changeChartView('percentage',this)">Persentase</button>
        </div>
    </div>

    {{-- KANAN: Progress Summary --}}
    <div class="progress-summary-card">
        <div class="progress-header">
            <div>
                <h5 class="chart-title" style="margin-bottom:4px;">Ringkasan Progress Task</h5>
                <p class="chart-subtitle">Done & Approved PM ÷ Total Non-Draft (weight)</p>
            </div>
            <div style="text-align:right;">
                <div id="progressPercentageText" class="progress-big-number">0%</div>
                <div class="progress-label">Penyelesaian Proyek</div>
                <div id="progressWeightLabel" class="progress-weight">0 / 0 weight</div>
            </div>
        </div>
        <div style="margin-bottom:28px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                <span style="font-size:12px;font-weight:700;color:var(--gray-700);">
                    <i class="bx bx-trophy" style="color:var(--success-green);"></i>
                    Done & Approved PM (Weight)
                </span>
                <span id="progressPctLabel" style="font-size:13px;font-weight:800;color:var(--success-green);">0%</span>
            </div>
            <div class="progress-bar-track">
                <div id="progressBarMain" class="progress-bar-fill" style="width:0%;"></div>
            </div>
        </div>
        <div class="progress-2col">
            <div>
                <div class="progress-col-title"><span></span>Status Progress (Non-Draft)</div>
                <div id="spBarsContainer" class="bars-container"></div>
            </div>
            <div>
                <div class="progress-col-title right"><span></span>Status Penilaian PM</div>
                <div id="saBarsContainer" class="bars-container"></div>
            </div>
        </div>
    </div>
</div>

{{-- ── Task Sheet ── --}}
<div class="task-sheet-container">
    <div class="task-sheet-header">
        <div>
            <h5 class="task-sheet-title">Task Timeline — {{ $projek->nama_projek }}</h5>
            <p class="task-sheet-subtitle">Klik sel untuk mengedit langsung &bull; Perubahan tersimpan otomatis &bull; Drag gantt bar untuk ubah timeline</p>
        </div>
        <div class="header-actions">
            @include('profile.partials.task-export-btn')
            <button class="btn-action btn-primary-custom" onclick="saveAllPending()"><i class="bx bx-save"></i> Simpan Semua</button>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="filter-bar" id="filterBar">
        <div class="filter-group">
            <span class="filter-label"><i class="bx bx-slider-alt"></i> Progress:</span>
            <button class="filter-btn f-draft"    data-ftype="status_progress" data-fval="draft"       onclick="toggleFilter('status_progress','draft')">Draft</button>
            <button class="filter-btn f-todo"     data-ftype="status_progress" data-fval="To Do"        onclick="toggleFilter('status_progress','To Do')">To Do</button>
            <button class="filter-btn f-progress" data-ftype="status_progress" data-fval="In Progress"  onclick="toggleFilter('status_progress','In Progress')">In Progress</button>
            <button class="filter-btn f-done"     data-ftype="status_progress" data-fval="done"         onclick="toggleFilter('status_progress','done')">Done</button>
        </div>
        <div class="filter-divider"></div>
        <div class="filter-group">
            <span class="filter-label"><i class="bx bx-badge-check"></i> Status PM:</span>
            <button class="filter-btn f-null"     data-ftype="status_akhir" data-fval="__null__"  onclick="toggleFilter('status_akhir','__null__')">Belum</button>
            <button class="filter-btn f-review"   data-ftype="status_akhir" data-fval="review"    onclick="toggleFilter('status_akhir','review')">Review</button>
            <button class="filter-btn f-revisi"   data-ftype="status_akhir" data-fval="revisi"    onclick="toggleFilter('status_akhir','revisi')">Revisi</button>
            <button class="filter-btn f-approved" data-ftype="status_akhir" data-fval="approved"  onclick="toggleFilter('status_akhir','approved')">Approved</button>
        </div>
        <div style="margin-left:auto;display:flex;align-items:center;gap:8px;">
            <div class="search-wrapper">
                <i class="bx bx-search"></i>
                <input type="text" class="filter-search" id="taskSearchInput"
                       placeholder="Cari nama / assignee..."
                       oninput="onSearchInput(this.value)">
            </div>
            <span id="filterResultsBadge" style="display:none;" class="filter-results-badge"></span>
            <button class="btn-clear-filter" id="clearFilterBtn" style="display:none;" onclick="clearAllFilters()"><i class="bx bx-x"></i> Reset</button>
        </div>
    </div>

    <div class="task-table-wrapper">
        <table class="task-table" id="taskTable">
            <thead>
                <tr>
                    <th style="width:46px;">No</th>
                    <th style="width:76px;">Aksi</th>
                    <th class="th-sortable" style="width:220px;" onclick="toggleSort('judul_tugas')">
                        Task Info
                        <span id="sort-icon-judul_tugas" class="sort-icon">
                            <svg width="8" height="12" viewBox="0 0 8 12"><path d="M4 0L7 4H1L4 0Z" fill="currentColor" opacity=".4"/><path d="M4 12L1 8H7L4 12Z" fill="currentColor" opacity=".4"/></svg>
                        </span>
                    </th>
                    <th class="th-sortable" style="width:140px;" onclick="toggleSort('nama_assignee')">
                        Penanggung Jawab
                        <span id="sort-icon-nama_assignee" class="sort-icon">
                            <svg width="8" height="12" viewBox="0 0 8 12"><path d="M4 0L7 4H1L4 0Z" fill="currentColor" opacity=".4"/><path d="M4 12L1 8H7L4 12Z" fill="currentColor" opacity=".4"/></svg>
                        </span>
                    </th>
                    <th style="width:110px;">Brief/Lap.</th>
                    <th class="th-sortable" style="width:175px;" onclick="toggleSort('status_progress')">
                        Status
                        <span id="sort-icon-status_progress" class="sort-icon">
                            <svg width="8" height="12" viewBox="0 0 8 12"><path d="M4 0L7 4H1L4 0Z" fill="currentColor" opacity=".4"/><path d="M4 12L1 8H7L4 12Z" fill="currentColor" opacity=".4"/></svg>
                        </span>
                    </th>
                    <th class="th-sortable" style="width:115px;text-align:center;" onclick="toggleSort('level')">
                        Level &amp; Weight
                        <span id="sort-icon-level" class="sort-icon">
                            <svg width="8" height="12" viewBox="0 0 8 12"><path d="M4 0L7 4H1L4 0Z" fill="currentColor" opacity=".4"/><path d="M4 12L1 8H7L4 12Z" fill="currentColor" opacity=".4"/></svg>
                        </span>
                    </th>
                    <th class="th-sortable" style="width:145px;" onclick="toggleSort('tenggat_waktu')">
                        Tenggat Waktu
                        <span id="sort-icon-tenggat_waktu" class="sort-icon">
                            <svg width="8" height="12" viewBox="0 0 8 12"><path d="M4 0L7 4H1L4 0Z" fill="currentColor" opacity=".4"/><path d="M4 12L1 8H7L4 12Z" fill="currentColor" opacity=".4"/></svg>
                        </span>
                    </th>
                    <th style="min-width:500px;width:500px;">Timeline (Gantt)</th>
                </tr>
            </thead>
            <tbody id="taskBody">
                <tr>
                    <td colspan="9" style="text-align:center;padding:40px;color:var(--gray-400);">
                        <i class="bx bx-loader-alt bx-spin" style="font-size:24px;display:block;margin-bottom:8px;"></i>Memuat data task...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="display:flex;border-top:1px solid var(--gray-200);">
        <button class="add-row-btn" onclick="addNewTask()" style="flex:1;border-top:none;">
            <i class="bx bx-plus"></i> Tambah Task Baru
        </button>
        <div style="width:1px;background:var(--gray-200);flex-shrink:0;"></div>
        <button class="add-row-btn" onclick="saveAllPending()" style="flex:1;border-top:none;color:#059669;">
            <i class="bx bx-save"></i> Simpan Semua
        </button>
    </div>
</div>

{{-- ════════ MODAL: Kelola Tim ════════ --}}
<div class="modal fade" id="modalInviteTim" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header-gradient">
                <h5 class="modal-title-custom"><i class="bx bx-group me-2"></i>Kelola Tim Project</h5>
                <p class="modal-subtitle">{{ $projek->nama_projek }}</p>
                <button type="button" class="btn-close btn-close-white position-absolute" style="top:16px;right:16px;" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <h6 style="font-size:12px;font-weight:700;color:var(--gray-700);text-transform:uppercase;margin-bottom:12px;"><i class="bx bx-user-check me-1"></i>Anggota Tim ({{ $timProject->count() }})</h6>
                <div id="timMemberList">
                    @forelse($timProject as $tim)
                    <div class="tim-member-item" id="tim-item-{{ $tim->id_tim }}">
                        <div class="member-avatar">{{ strtoupper(substr(optional($tim->user)->nama ?? 'XX',0,2)) }}</div>
                        <div class="member-info">
                            <div class="member-name">{{ optional($tim->user)->nama ?? '—' }}</div>
                            <div class="member-role" style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
                                @if(optional(optional($tim->user)->jobRole)->nama_job_role)
                                <span style="background:var(--primary-light);color:var(--primary-blue);font-size:9px;font-weight:700;padding:2px 7px;border-radius:20px;text-transform:uppercase;letter-spacing:.04em;border:1px solid #C7D2FE;">
                                    {{ optional($tim->user)->jobRole->nama_job_role }}
                                </span>
                                @endif
                                <span style="font-size:11px;color:var(--gray-400);">{{ optional($tim->user)->email ?? '' }}</span>
                            </div>
                        </div>
                        <button class="member-remove-btn" onclick="removeTim({{ $tim->id_tim }})"><i class="bx bx-user-minus"></i></button>
                    </div>
                    @empty
                    <div style="text-align:center;padding:20px;color:var(--gray-400);"><i class="bx bx-user-x" style="font-size:28px;display:block;margin-bottom:8px;"></i>Belum ada anggota tim.</div>
                    @endforelse
                </div>
                <hr style="margin:16px 0;border-color:var(--gray-200);">
                <h6 style="font-size:12px;font-weight:700;color:var(--gray-700);text-transform:uppercase;margin-bottom:8px;"><i class="bx bx-user-plus me-1"></i>Undang Anggota Baru</h6>
                @if($userTersedia->count() > 0)
                <input type="text" class="form-control-sm-custom mb-2" placeholder="Cari nama atau email..." oninput="filterUsers(this.value)">
                <div id="userCheckboxList" style="max-height:240px;overflow-y:auto;">
                    @foreach($userTersedia as $user)
                    <label class="user-checkbox-item" id="uitem-{{ $user->id_user }}">
                        <input type="checkbox" value="{{ $user->id_user }}" class="invite-checkbox" onchange="this.closest('label').classList.toggle('selected',this.checked)">
                        <div class="member-avatar" style="width:34px;height:34px;font-size:11px;">{{ strtoupper(substr($user->nama,0,2)) }}</div>
                        <div class="member-info">
                            <div class="member-name" style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                {{ $user->nama }}
                                @if($user->jobRole)
                                <span style="background:var(--primary-light);color:var(--primary-blue);font-size:9px;font-weight:700;padding:2px 7px;border-radius:20px;text-transform:uppercase;letter-spacing:.04em;border:1px solid #C7D2FE;">
                                    {{ $user->jobRole->nama_job_role }}
                                </span>
                                @endif
                            </div>
                            <div class="member-role" style="font-size:11px;color:var(--gray-400);">{{ $user->email }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
                <button class="btn-action btn-primary-custom mt-3 w-100" onclick="inviteTim()"><i class="bx bx-user-plus"></i> Undang User Terpilih</button>
                @else
                <div style="text-align:center;padding:20px;color:var(--gray-400);"><i class="bx bx-check-circle" style="font-size:24px;display:block;margin-bottom:6px;color:var(--success-green);"></i>Semua user sudah menjadi anggota tim.</div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ════════ MODAL: Preview Detail Task ════════ --}}
<div class="modal fade" id="modalPreviewTask" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header-gradient">
                <h5 class="modal-title-custom"><i class="bx bx-show me-2"></i>Detail Task</h5>
                <p class="modal-subtitle" id="previewModalSubtitle"></p>
                <button type="button" class="btn-close btn-close-white position-absolute" style="top:16px;right:16px;" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:22px 24px;">
                <div class="preview-info-panel">
                    <div class="preview-task-title" id="pvTitle">—</div>
                    <div class="preview-task-desc" id="pvDesc" style="display:none;"></div>
                    <div class="preview-meta-grid">
                        <div class="preview-meta-item"><span class="preview-meta-label">Penanggung Jawab</span><span class="preview-meta-value" id="pvAssignee">—</span></div>
                        <div class="preview-meta-item"><span class="preview-meta-label">Level</span><span id="pvLevel">—</span></div>
                        <div class="preview-meta-item"><span class="preview-meta-label">Tanggal Mulai</span><span class="preview-meta-value" id="pvStart">—</span></div>
                        <div class="preview-meta-item"><span class="preview-meta-label">Deadline</span><span class="preview-meta-value" id="pvEnd">—</span></div>
                        <div class="preview-meta-item"><span class="preview-meta-label">Tgl. Selesai (Done)</span><span class="preview-meta-value" id="pvSelesai">—</span></div>
                        <div class="preview-meta-item"><span class="preview-meta-label">Ketepatan Waktu</span><span id="pvKetepatan">—</span></div>
                        <div class="preview-meta-item"><span class="preview-meta-label">Weight</span><span class="preview-meta-value" id="pvWeight">—</span></div>
                        <div class="preview-meta-item"><span class="preview-meta-label">Status Progress</span><span id="pvStatus">—</span></div>
                        <div class="preview-meta-item" style="grid-column:1/-1;"><span class="preview-meta-label">Status Akhir (PM)</span><span id="pvSA">—</span></div>
                    </div>
                </div>
                <div class="preview-media-section">
                    <div class="preview-media-header brief-hdr"><i class="bx bx-paperclip"></i> Foto Brief</div>
                    <div class="preview-media-body"><div class="preview-gallery" id="pvGalleryBrief"><span class="preview-empty-media">Belum ada foto brief.</span></div></div>
                </div>
                <div class="preview-media-section">
                    <div class="preview-media-header hasil-hdr"><i class="bx bx-file-blank"></i> Laporan Hasil</div>
                    <div class="preview-media-body"><div class="preview-gallery" id="pvGalleryHasil"><span class="preview-empty-media">Belum ada laporan hasil.</span></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════════ MODAL: Brief & Laporan ════════ --}}
<div class="modal fade" id="modalMediaTask" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header-gradient">
                <h5 class="modal-title-custom"><i class="bx bx-images me-2"></i>Brief &amp; Laporan Hasil</h5>
                <p class="modal-subtitle" id="mediaModalSubtitle"></p>
                <button type="button" class="btn-close btn-close-white position-absolute" style="top:16px;right:16px;" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:20px 24px;">
                <input type="hidden" id="media_id_tugas">
                <div class="upload-section">
                    <div class="upload-section-header brief-hdr"><i class="bx bx-paperclip"></i> Foto Brief</div>
                    <div class="upload-section-body">
                        <div id="galleryBrief" class="gallery-grid" style="min-height:36px;"></div>
                        <label class="drag-drop-zone" id="dropBrief"
                            ondragover="handleDragOver(event,'dropBrief')"
                            ondragleave="handleDragLeave('dropBrief')"
                            ondrop="handleDrop(event,'brief')">
                            <i class="bx bx-image-add"></i>
                            <p>Seret foto ke sini atau klik untuk memilih</p>
                            <small>JPG, PNG, WEBP — Maks 5MB</small>
                            <input type="file" id="inputBrief" multiple accept="image/*" style="display:none;" onchange="uploadFiles(this,'brief')">
                        </label>
                    </div>
                </div>
                <div class="upload-section">
                    <div class="upload-section-header hasil-hdr"><i class="bx bx-file-blank"></i> Laporan Hasil</div>
                    <div class="upload-section-body">
                        <div id="galleryHasil" class="gallery-grid" style="min-height:36px;"></div>
                        <label class="drag-drop-zone hasil-zone" id="dropHasil"
                            ondragover="handleDragOver(event,'dropHasil')"
                            ondragleave="handleDragLeave('dropHasil')"
                            ondrop="handleDrop(event,'hasil')">
                            <i class="bx bx-cloud-upload"></i>
                            <p>Seret file ke sini atau klik untuk memilih</p>
                            <small>JPG, PNG, PDF, DOC, DOCX, XLS, XLSX — Maks 10MB</small>
                            <input type="file" id="inputHasil" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx" style="display:none;" onchange="uploadFiles(this,'hasil')">
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════════ MODAL: Konfirmasi Hapus ════════ --}}
<div class="modal fade" id="modalHapusTask" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center" style="padding:36px 28px;">
                <div style="width:60px;height:60px;background:var(--danger-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:26px;color:var(--danger-red);"><i class="bx bx-trash-alt"></i></div>
                <h5 style="font-size:17px;font-weight:700;color:var(--gray-900);margin-bottom:8px;">Hapus Task?</h5>
                <p style="color:var(--gray-600);font-size:14px;margin-bottom:6px;">Anda akan menghapus task:</p>
                <div style="background:var(--gray-50);border-radius:8px;padding:9px 14px;margin-bottom:6px;font-weight:700;color:var(--gray-900);font-size:14px;" id="deleteTaskName">—</div>
                <p style="color:var(--gray-500);font-size:12px;">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer justify-content-center gap-2 border-0" style="padding-bottom:24px;">
                <button type="button" class="btn-action btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-action" id="confirmDeleteBtn" style="background:var(--danger-red);color:white;"><i class="bx bx-trash-alt"></i> Hapus</button>
            </div>
        </div>
    </div>
</div>

{{-- ════════ Export ════════ --}}
@include('profile.partials.task-export', [
    'projek'   => $projek,
    'pmNama'   => $pmNama,
    'tglMulai' => $tglMulai,
    'tglAkhir' => $tglAkhir,
    'stats'    => $stats,
])

{{-- ════════ TOAST — FIXED ════════ --}}
<div id="toast-notif">
    <div id="toast-accent"></div>
    <div id="toast-body">
        <div id="toast-icon-wrap"><i id="toast-icon" class="bx bx-check-circle"></i></div>
        <div id="toast-content">
            <div id="toast-title">Tersimpan</div>
            <div id="toast-msg">Data berhasil diperbarui</div>
        </div>
        <button id="toast-close" onclick="closeToast()"><i class="bx bx-x"></i></button>
    </div>
    <div id="toast-progress"><div id="toast-progress-bar"></div></div>
</div>

</div>{{-- end page-content-wrapper --}}
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
'use strict';
const PROJEK_ID         = {{ $projek->id_projek }};
const CSRF_TOKEN        = '{{ csrf_token() }}';
const BASE_URL          = '{{ url("projek/" . $projek->id_projek . "/task") }}';
const PROJEK_START_DATE = '{{ $projek->tanggal_mulai ? \Carbon\Carbon::parse($projek->tanggal_mulai)->format('Y-m-d') : now()->format('Y-m-d') }}';
const PROJEK_END_DATE   = '{{ $projek->tanggal_selesai ? \Carbon\Carbon::parse($projek->tanggal_selesai)->format('Y-m-d') : null }}';
const TIM_LIST = [
    @foreach($timProject as $tim)
    {
        id_tim:  {{ $tim->id_tim }},
        nama:    @json(optional($tim->user)->nama    ?? '—'),
        jabatan: @json(optional(optional($tim->user)->jobRole)->nama_job_role ?? null),
        email:   @json(optional($tim->user)->email   ?? ''),
    },
    @endforeach
];
let tasks = [], pendingChanges = {};
let performanceChart = null, currentChartView = 'bar';

/* ══════════════════════════════════════
   LOCK HELPER
══════════════════════════════════════ */
function isRowLocked(task) {
    return task.status_akhir === 'approved';
}

/* ══════════════════════════════════════
   SORT & FILTER STATE
══════════════════════════════════════ */
let sortState   = { column: null, dir: 'asc' };
let filterState = { status_progress: [], status_akhir: [] };
let searchQuery = '';
const SORT_COLUMNS = ['judul_tugas','nama_assignee','status_progress','level','tenggat_waktu'];

function toggleSort(col) {
    if (sortState.column === col) {
        sortState.dir = sortState.dir === 'asc' ? 'desc' : null;
        if (!sortState.dir) { sortState.column = null; sortState.dir = 'asc'; }
    } else {
        sortState.column = col;
        sortState.dir    = 'asc';
    }
    updateSortIcons();
    renderFilteredTasks();
}
function updateSortIcons() {
    SORT_COLUMNS.forEach(col => {
        const el = document.getElementById('sort-icon-' + col);
        if (!el) return;
        if (sortState.column === col) {
            el.classList.add(sortState.dir);
            el.classList.remove(sortState.dir === 'asc' ? 'desc' : 'asc');
            const up = sortState.dir === 'asc'  ? '1' : '.25';
            const dn = sortState.dir === 'desc' ? '1' : '.25';
            el.innerHTML = `<svg width="8" height="12" viewBox="0 0 8 12">
                <path d="M4 0L7 4H1L4 0Z" fill="var(--primary-blue)" opacity="${up}"/>
                <path d="M4 12L1 8H7L4 12Z" fill="var(--primary-blue)" opacity="${dn}"/>
            </svg>`;
        } else {
            el.classList.remove('asc','desc');
            el.innerHTML = `<svg width="8" height="12" viewBox="0 0 8 12">
                <path d="M4 0L7 4H1L4 0Z" fill="currentColor" opacity=".35"/>
                <path d="M4 12L1 8H7L4 12Z" fill="currentColor" opacity=".35"/>
            </svg>`;
        }
    });
}
function toggleFilter(type, value) {
    const arr = filterState[type];
    const idx = arr.indexOf(value);
    if (idx === -1) arr.push(value); else arr.splice(idx, 1);
    updateFilterUI();
    renderFilteredTasks();
}
function onSearchInput(val) {
    searchQuery = (val || '').trim().toLowerCase();
    updateFilterUI();
    renderFilteredTasks();
}
function clearAllFilters() {
    filterState.status_progress = [];
    filterState.status_akhir    = [];
    searchQuery = '';
    const inp = document.getElementById('taskSearchInput');
    if (inp) inp.value = '';
    updateFilterUI();
    renderFilteredTasks();
}
function updateFilterUI() {
    document.querySelectorAll('#filterBar .filter-btn[data-ftype]').forEach(btn => {
        const active = filterState[btn.getAttribute('data-ftype')]?.includes(btn.getAttribute('data-fval'));
        btn.classList.toggle('active', !!active);
    });
    const hasFilters = filterState.status_progress.length || filterState.status_akhir.length || searchQuery.length;
    document.getElementById('clearFilterBtn').style.display     = hasFilters ? '' : 'none';
    document.getElementById('filterResultsBadge').style.display = hasFilters ? '' : 'none';
}
function getFilteredSortedTasks() {
    let result = [...tasks];
    if (filterState.status_progress.length)
        result = result.filter(t => filterState.status_progress.includes(t.status_progress));
    if (filterState.status_akhir.length)
        result = result.filter(t => filterState.status_akhir.some(f => f === '__null__' ? !t.status_akhir : t.status_akhir === f));
    if (searchQuery)
        result = result.filter(t => [t.judul_tugas,t.deskripsi_tugas,t.nama_assignee,t.level,t.status_progress,t.status_akhir].join(' ').toLowerCase().includes(searchQuery));
    if (sortState.column) {
        result.sort((a, b) => {
            let va = a[sortState.column] ?? '', vb = b[sortState.column] ?? '';
            if (sortState.column === 'level') { const o={mudah:1,medium:2,susah:3}; va=o[va]??99; vb=o[vb]??99; }
            else { if (typeof va==='string') va=va.toLowerCase(); if (typeof vb==='string') vb=vb.toLowerCase(); }
            const cmp = va < vb ? -1 : va > vb ? 1 : 0;
            return sortState.dir === 'asc' ? cmp : -cmp;
        });
    }
    return result;
}
function renderFilteredTasks() {
    const tb       = document.getElementById('taskBody');
    const filtered = getFilteredSortedTasks();
    const badge    = document.getElementById('filterResultsBadge');
    if (badge) badge.textContent = `${filtered.length} dari ${tasks.length} task`;
    if (!filtered.length) {
        tb.innerHTML = `<tr><td colspan="9"><div class="empty-state">
            <i class="bx bx-search-alt-2"></i>
            <p class="fw-medium">Tidak ada task yang sesuai filter</p>
            <p><button class="btn-action btn-outline-primary" onclick="clearAllFilters()"><i class="bx bx-x"></i> Reset Filter</button></p>
        </div></td></tr>`;
        return;
    }
    if (!GANTT.winStart) GANTT.initWindow();
    tb.innerHTML = filtered.map((t, i) => renderRow(t, i)).join('');
    requestAnimationFrame(() => {
        filtered.forEach(t => {
            const container = document.getElementById('gantt-cell-' + t.id_tugas);
            if (container) GANTT.initCell(t.id_tugas, container, t);
        });
        GANTT.updatePeriodLabel();
    });
}

/* ══════════════════════════════════════════════════
   GANTT ENGINE
══════════════════════════════════════════════════ */
const GANTT = (() => {
    const MN               = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
    const PX_PER_WEEK      = 62;
    const PX_PER_MONTH     = PX_PER_WEEK * 4;
    const VIEWPORT_MONTHS  = 3;
    const EXTEND_THRESHOLD = PX_PER_MONTH * 2;
    const EXTEND_BY        = 12;
    let rangeStart = { y:0, m:0 };
    let rangeEnd   = { y:0, m:0 };
    let _scrollPx  = 0;
    const W_STARTS = [1, 8, 15, 22];
    function wEndDay(y, m, w) { return w < 3 ? W_STARTS[w+1]-1 : new Date(y, m+1, 0).getDate(); }
    function todayStr()       { return new Date().toISOString().split('T')[0]; }
    function cleanDate(s)     { return s ? String(s).trim().substring(0,10) : todayStr(); }
    function dateFromStr(s)   { return new Date(cleanDate(s)+'T00:00:00'); }
    function addMonths(y, m, delta) { let t=y*12+m+delta; return {y:Math.floor(t/12),m:((t%12)+12)%12}; }
    function monthsBetween(a, b) { return (b.y-a.y)*12+(b.m-a.m); }
    function totalMonths()       { return monthsBetween(rangeStart,rangeEnd)+1; }
    function totalWidth()        { return totalMonths()*PX_PER_MONTH; }
    function monthLeftPx(y,m)    { return monthsBetween(rangeStart,{y,m})*PX_PER_MONTH; }
    function dateToX(dateStr) {
        const d=dateFromStr(cleanDate(dateStr)||todayStr());
        const y=d.getFullYear(),m=d.getMonth(),day=d.getDate();
        const mPx=monthLeftPx(y,m); let wPx=0;
        for (let w=0;w<4;w++) { const wE=wEndDay(y,m,w); if(day<=wE){const wS=W_STARTS[w];wPx=w*PX_PER_WEEK+((day-wS)/(wE-wS+1))*PX_PER_WEEK;break;} }
        return mPx+wPx;
    }
    function xToDate(x) {
        const safeX=Math.max(0,Math.min(x,totalWidth()-1));
        const mIdx=Math.min(totalMonths()-1,Math.floor(safeX/PX_PER_MONTH));
        const rem=safeX-mIdx*PX_PER_MONTH;
        const {y,m}=addMonths(rangeStart.y,rangeStart.m,mIdx);
        const wIdx=Math.min(3,Math.floor(rem/PX_PER_WEEK));
        const wS=W_STARTS[wIdx],wE=wEndDay(y,m,wIdx);
        const pxInW=rem-wIdx*PX_PER_WEEK;
        const day=Math.max(wS,Math.min(wE,wS+Math.round((pxInW/PX_PER_WEEK)*(wE-wS+1))));
        return new Date(y,m,day).toISOString().split('T')[0];
    }
    function getProjectStart() {
        try { const d=new Date((typeof PROJEK_START_DATE!=='undefined'?PROJEK_START_DATE:'')+'T00:00:00'); if(!isNaN(d.getTime())) return{y:d.getFullYear(),m:d.getMonth()}; } catch(e){}
        const now=new Date(); return{y:now.getFullYear(),m:now.getMonth()};
    }
    function initWindow() {
        const now=new Date(),pStart=getProjectStart();
        rangeStart={y:pStart.y,m:pStart.m};
        rangeEnd=addMonths(now.getFullYear(),now.getMonth(),36);
        const nowMIdx=monthsBetween(rangeStart,{y:now.getFullYear(),m:now.getMonth()});
        _scrollPx=Math.max(0,(nowMIdx-1)*PX_PER_MONTH);
    }
    function maybeExtend() {
        if (totalWidth()-_scrollPx-VIEWPORT_MONTHS*PX_PER_MONTH < EXTEND_THRESHOLD) {
            rangeEnd=addMonths(rangeEnd.y,rangeEnd.m,EXTEND_BY); rebuildAll();
        }
    }
    function rebuildAll() {
        const tw=totalWidth();
        document.querySelectorAll('[id^="gantt-ruler-inner-"]').forEach(el=>{el.innerHTML=buildRulerHTML();});
        document.querySelectorAll('[id^="gantt-bg-"]').forEach(el=>{el.innerHTML=buildTrackBgHTML();el.style.width=tw+'px';});
        tasks.forEach(t=>placeBar(t.id_tugas,t));
        applyScrollAll();
    }
    function buildRulerHTML() {
        const tw=totalWidth(),now=new Date();
        let html=`<div style="position:relative;height:44px;width:${tw}px;background:white;">`;
        for (let i=0;i<totalMonths();i++) {
            const {y,m}=addMonths(rangeStart.y,rangeStart.m,i);
            const mLeft=i*PX_PER_MONTH,isNow=y===now.getFullYear()&&m===now.getMonth();
            html+=`<div style="position:absolute;left:${mLeft}px;width:${PX_PER_MONTH}px;top:0;height:22px;border-right:2px solid var(--gray-300);border-bottom:1px solid var(--gray-200);box-sizing:border-box;overflow:hidden;padding-left:6px;display:flex;align-items:center;background:${isNow?'#EEF2FF':'white'};">
                <span style="font-size:10px;font-weight:800;white-space:nowrap;letter-spacing:.04em;color:${isNow?'var(--primary-blue)':'var(--gray-600)'};text-transform:uppercase;">${MN[m]} ${y}</span></div>`;
            for (let w=0;w<4;w++) {
                const wLeft=mLeft+w*PX_PER_WEEK,wS=W_STARTS[w],wE=wEndDay(y,m,w);
                const isTW=isNow&&now.getDate()>=wS&&now.getDate()<=wE;
                html+=`<div style="position:absolute;left:${wLeft}px;width:${PX_PER_WEEK}px;bottom:0;height:22px;border-right:1px solid var(--gray-200);box-sizing:border-box;display:flex;align-items:center;justify-content:center;background:${isTW?'#FEF3C7':'transparent'};">
                    <span style="font-size:9px;font-weight:${isTW?800:600};color:${isTW?'#D97706':'var(--gray-400)'};">M${w+1}</span></div>`;
            }
            if(mLeft>0) html+=`<div style="position:absolute;left:${mLeft}px;top:0;bottom:0;width:2px;background:var(--gray-300);z-index:2;pointer-events:none;"></div>`;
        }
        const tx=dateToX(todayStr());
        html+=`<div style="position:absolute;left:${tx}px;top:0;bottom:0;width:2px;background:rgba(239,68,68,.45);pointer-events:none;z-index:5;"></div></div>`;
        return html;
    }
    function buildTrackBgHTML() {
        const tw=totalWidth(),now=new Date();
        let html=`<div style="position:absolute;top:0;left:0;width:${tw}px;bottom:0;pointer-events:none;">`;
        for (let i=0;i<totalMonths();i++) {
            const {y,m}=addMonths(rangeStart.y,rangeStart.m,i);
            const mLeft=i*PX_PER_MONTH,isNow=y===now.getFullYear()&&m===now.getMonth();
            if(isNow) html+=`<div style="position:absolute;left:${mLeft}px;width:${PX_PER_MONTH}px;top:0;bottom:0;background:rgba(238,242,255,.4);z-index:0;"></div>`;
            if(mLeft>0) html+=`<div style="position:absolute;left:${mLeft}px;top:0;bottom:0;width:2px;background:var(--gray-300);z-index:2;"></div>`;
            for(let w=1;w<4;w++) html+=`<div style="position:absolute;left:${mLeft+w*PX_PER_WEEK}px;top:0;bottom:0;width:1px;background:var(--gray-200);z-index:1;opacity:.7;"></div>`;
        }
        const tx=dateToX(todayStr());
        html+=`<div style="position:absolute;left:${tx}px;top:0;bottom:0;width:2px;background:var(--danger-red);z-index:8;">
            <span style="position:absolute;top:2px;left:3px;font-size:8px;font-weight:800;color:var(--danger-red);white-space:nowrap;">Hari ini</span></div></div>`;
        return html;
    }
    function applyScrollAll() {
        const tx=-_scrollPx;
        document.querySelectorAll('[id^="gantt-ruler-inner-"]').forEach(el=>{el.style.transform=`translateX(${tx}px)`;});
        document.querySelectorAll('[id^="gantt-bg-"]').forEach(el=>{el.style.transform=`translateX(${tx}px)`;});
        document.querySelectorAll('.gantt-bar').forEach(bar=>{
            const bl=parseFloat(bar.getAttribute('data-base-left'));
            if(!isNaN(bl)) bar.style.left=(bl+tx)+'px';
        });
        updatePeriodLabel();
    }
    function scrollBy(months) { _scrollPx+=months*PX_PER_MONTH; if(_scrollPx<0)_scrollPx=0; maybeExtend(); applyScrollAll(); }
    function scrollToToday() {
        const now=new Date();
        _scrollPx=Math.max(0,(monthsBetween(rangeStart,{y:now.getFullYear(),m:now.getMonth()})-1)*PX_PER_MONTH);
        applyScrollAll();
    }
    function updatePeriodLabel() {
        const mIdx=Math.min(totalMonths()-1,Math.max(0,Math.floor((_scrollPx+(VIEWPORT_MONTHS/2)*PX_PER_MONTH)/PX_PER_MONTH)));
        const {y,m}=addMonths(rangeStart.y,rangeStart.m,mIdx);
        document.querySelectorAll('[id^="gperiod-"]').forEach(el=>el.textContent=`${MN[m]} ${y}`);
    }
    function initCell(taskId, containerEl, task) {
        const tw=totalWidth();
        containerEl.innerHTML=`
            <div class="gantt-nav-btns">
                <button class="gantt-nav-btn" onclick="GANTT.scrollBy(-12)">&#171;</button>
                <button class="gantt-nav-btn" onclick="GANTT.scrollBy(-1)">&#8249;</button>
                <button class="gantt-nav-btn today-btn" onclick="GANTT.scrollToToday()">Hari Ini</button>
                <button class="gantt-nav-btn" onclick="GANTT.scrollBy(1)">&#8250;</button>
                <button class="gantt-nav-btn" onclick="GANTT.scrollBy(12)">&#187;</button>
                <span class="gantt-period-label" id="gperiod-${taskId}">—</span>
            </div>
            <div class="gantt-outer" id="gantt-outer-${taskId}">
                <div class="gantt-ruler-area" id="gantt-ruler-${taskId}" style="overflow:hidden;height:44px;background:white;border-bottom:1px solid var(--gray-200);">
                    <div id="gantt-ruler-inner-${taskId}" style="position:absolute;top:0;left:0;transform:translateX(0);">${buildRulerHTML()}</div>
                </div>
                <div class="gantt-track-area" id="gantt-track-${taskId}" style="position:relative;height:54px;background:var(--gray-50);overflow:hidden;">
                    <div id="gantt-bg-${taskId}" style="position:absolute;top:0;left:0;width:${tw}px;bottom:0;transform:translateX(0);">${buildTrackBgHTML()}</div>
                </div>
            </div>`;
        placeBar(taskId, task);
        applyScrollAll();
        updatePeriodLabel();
        const outer=containerEl.querySelector('.gantt-outer');
        if(outer) outer.addEventListener('wheel',e=>{e.preventDefault();scrollBy(e.deltaY>0?1:-1);},{passive:false});
    }
    function placeBar(taskId, task) {
        const trackEl=document.getElementById('gantt-track-'+taskId); if(!trackEl) return;
        const old=trackEl.querySelector('.gantt-bar[data-id="'+taskId+'"]'); if(old) old.remove();
        const sd=cleanDate(task.tanggal_mulai)||todayStr();
        let ed=cleanDate(task.tenggat_waktu);
        if(!ed){const fe=new Date(sd+'T00:00:00');fe.setDate(fe.getDate()+14);ed=fe.toISOString().split('T')[0];}
        const baseLeft=dateToX(sd),baseRight=dateToX(ed)+PX_PER_WEEK;
        const baseWidth=Math.max(PX_PER_WEEK*0.8,baseRight-baseLeft);
        const visLeft=baseLeft-_scrollPx;
        const spClass=statusClass(task.status_progress||'To Do');
        const locked=isRowLocked(task);
        const bar=document.createElement('div');
        bar.className='gantt-bar'+(locked?' locked':'');
        bar.setAttribute('data-id',taskId);
        bar.setAttribute('data-base-left',baseLeft);
        bar.setAttribute('data-base-width',baseWidth);
        bar.style.cssText=`left:${visLeft}px;width:${baseWidth}px;`;
        const resizeHandles = locked ? '' : `
            <div class="gantt-resize r-left"  onmousedown="GANTT.startResize(event,${taskId},'left')"></div>
            <div class="gantt-resize r-right" onmousedown="GANTT.startResize(event,${taskId},'right')"></div>`;
        bar.innerHTML=`<div class="gantt-bar-inner ${spClass}">
            ${resizeHandles}
            <span class="gantt-bar-lbl" id="gb-s-${taskId}">${fmtDate(sd)}</span>
            <span class="gantt-bar-title">${escHtml(task.judul_tugas||'—')}</span>
            <span class="gantt-bar-lbl" id="gb-e-${taskId}">${fmtDate(ed)}</span>
        </div>`;
        if (!locked) {
            bar.addEventListener('mousedown',e=>{if(!e.target.closest('.gantt-resize'))startDrag(e,taskId);});
        }
        trackEl.appendChild(bar);
    }
    /* DRAG */
    let _dragId=null,_dragStartX=0,_dragBaseLeft=0,_dragBaseWidth=0;
    function startDrag(e,id){
        e.preventDefault();e.stopPropagation();
        const task=tasks.find(t=>t.id_tugas===id);
        if(!task||isRowLocked(task))return;
        const bar=document.querySelector('.gantt-bar[data-id="'+id+'"]');if(!bar)return;
        _dragId=id;_dragStartX=e.clientX;
        _dragBaseLeft=parseFloat(bar.getAttribute('data-base-left'))||0;
        _dragBaseWidth=parseFloat(bar.getAttribute('data-base-width'))||PX_PER_WEEK;
        bar.style.opacity='.72';bar.style.zIndex='20';
        document.addEventListener('mousemove',onDrag);document.addEventListener('mouseup',stopDrag);
    }
    function onDrag(e){
        if(_dragId===null)return;
        const bar=document.querySelector('.gantt-bar[data-id="'+_dragId+'"]');if(!bar)return;
        const dx=e.clientX-_dragStartX,newBase=_dragBaseLeft+dx;
        bar.style.left=(newBase-_scrollPx)+'px';
        const ls=document.getElementById('gb-s-'+_dragId),le=document.getElementById('gb-e-'+_dragId);
        if(ls)ls.textContent=fmtDate(xToDate(newBase));
        if(le)le.textContent=fmtDate(xToDate(newBase+_dragBaseWidth));
    }
    function stopDrag(){
        if(_dragId===null)return;
        const bar=document.querySelector('.gantt-bar[data-id="'+_dragId+'"]');
        if(bar){
            bar.style.opacity='';bar.style.zIndex='';
            const absLeft=parseFloat(bar.style.left)+_scrollPx;
            const sd=xToDate(absLeft),ed=xToDate(absLeft+_dragBaseWidth);
            bar.setAttribute('data-base-left',absLeft);
            markChange(_dragId,'tanggal_mulai',sd,true);
            markChange(_dragId,'tenggat_waktu',ed,true);
            updateRowDateInputs(_dragId,sd,ed);
            const task=tasks.find(t=>t.id_tugas===_dragId);
            if(task){task.tanggal_mulai=sd;task.tenggat_waktu=ed;}
        }
        document.removeEventListener('mousemove',onDrag);document.removeEventListener('mouseup',stopDrag);
        _dragId=null;
    }
    /* RESIZE */
    let _rId=null,_rDir=null,_rStartX=0,_rBaseLeft=0,_rBaseWidth=0;
    function startResize(e,id,dir){
        e.preventDefault();e.stopPropagation();
        const task=tasks.find(t=>t.id_tugas===id);
        if(!task||isRowLocked(task))return;
        const bar=document.querySelector('.gantt-bar[data-id="'+id+'"]');if(!bar)return;
        _rId=id;_rDir=dir;_rStartX=e.clientX;
        _rBaseLeft=parseFloat(bar.getAttribute('data-base-left'))||0;
        _rBaseWidth=parseFloat(bar.getAttribute('data-base-width'))||PX_PER_WEEK;
        bar.style.opacity='.72';
        document.addEventListener('mousemove',onResize);document.addEventListener('mouseup',stopResize);
    }
    function onResize(e){
        if(!_rId)return;
        const bar=document.querySelector('.gantt-bar[data-id="'+_rId+'"]');if(!bar)return;
        const dx=e.clientX-_rStartX;
        if(_rDir==='left'){
            const newBase=Math.min(_rBaseLeft+_rBaseWidth-PX_PER_WEEK*0.5,_rBaseLeft+dx);
            const newW=Math.max(PX_PER_WEEK*0.5,_rBaseLeft+_rBaseWidth-newBase);
            bar.style.left=(newBase-_scrollPx)+'px';bar.style.width=newW+'px';
            const ls=document.getElementById('gb-s-'+_rId);if(ls)ls.textContent=fmtDate(xToDate(newBase));
        } else {
            const newW=Math.max(PX_PER_WEEK*0.5,_rBaseWidth+dx);bar.style.width=newW+'px';
            const le=document.getElementById('gb-e-'+_rId);if(le)le.textContent=fmtDate(xToDate(_rBaseLeft+newW));
        }
    }
    function stopResize(){
        if(!_rId)return;
        const bar=document.querySelector('.gantt-bar[data-id="'+_rId+'"]');
        if(bar){
            bar.style.opacity='';
            const absLeft=parseFloat(bar.style.left)+_scrollPx;
            const w=parseFloat(bar.style.width)||PX_PER_WEEK;
            const sd=xToDate(absLeft),ed=xToDate(absLeft+w);
            bar.setAttribute('data-base-left',absLeft);bar.setAttribute('data-base-width',w);
            markChange(_rId,'tanggal_mulai',sd,true);markChange(_rId,'tenggat_waktu',ed,true);
            updateRowDateInputs(_rId,sd,ed);
            const task=tasks.find(t=>t.id_tugas===_rId);
            if(task){task.tanggal_mulai=sd;task.tenggat_waktu=ed;}
        }
        document.removeEventListener('mousemove',onResize);document.removeEventListener('mouseup',stopResize);
        _rId=null;_rDir=null;
    }
    return {
        initWindow,scrollBy,scrollToToday,
        initCell,placeBar,updatePeriodLabel,startResize,
        get winStart(){return new Date(rangeStart.y,rangeStart.m,1);},
        get winEnd(){return new Date(rangeEnd.y,rangeEnd.m+1,0);},
    };
})();

/* ══════════════════════
   CONSTANTS
══════════════════════ */
const STATUS_LABELS = {'draft':'Draft','To Do':'To Do','In Progress':'In Progress','done':'Done'};
const SA_LABELS     = {review:'Review',revisi:'Revisi',approved:'Approved'};
const WEIGHT_MAP    = {mudah:1,medium:2,susah:3};
const FIELD_LABELS  = {
    judul_tugas:'Nama task', deskripsi_tugas:'Deskripsi', id_tim:'Penanggung jawab',
    status_progress:'Status progress', level:'Level & weight', weight:'Weight',
    tanggal_mulai:'Tanggal mulai', tenggat_waktu:'Deadline',
};

/* ══════════════════════
   TOAST SYSTEM — FIXED
   Identik dengan master-data-projek
══════════════════════ */
const TOAST_TITLES = {success:'Tersimpan ✓', error:'Gagal Menyimpan', saving:'Menyimpan...', info:'Informasi'};
const TOAST_ICONS  = {success:'bx bx-check-circle', error:'bx bx-error-circle', saving:'bx bx-loader-alt bx-spin', info:'bx bx-info-circle'};
let _toastTimer = null;

function showToast(msg, type = 'success', title = null, duration = 3500) {
    const el      = document.getElementById('toast-notif');
    const iconEl  = document.getElementById('toast-icon');
    const titleEl = document.getElementById('toast-title');
    const msgEl   = document.getElementById('toast-msg');
    const progBar = document.getElementById('toast-progress-bar');
    if (!el) return;
    clearTimeout(_toastTimer);
    // Set class untuk warna gradient
    el.className = type;
    iconEl.className = TOAST_ICONS[type] || TOAST_ICONS.success;
    titleEl.textContent = title || TOAST_TITLES[type] || 'Notifikasi';
    msgEl.textContent   = msg;
    // Reset progress bar
    progBar.style.transition = 'none';
    progBar.style.width      = '100%';
    requestAnimationFrame(() => {
        el.classList.add('show');
        if (type !== 'saving') {
            requestAnimationFrame(() => {
                progBar.style.transition = `width ${duration}ms linear`;
                progBar.style.width      = '0%';
            });
            _toastTimer = setTimeout(closeToast, duration);
        }
    });
}
function closeToast() {
    const el = document.getElementById('toast-notif');
    if (el) el.classList.remove('show');
    clearTimeout(_toastTimer);
}

/* ══════════════════════
   API HELPERS
══════════════════════ */
async function apiFetch(url, method = 'GET', body = null) {
    const opts = { method, headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' } };
    if (body) { opts.headers['Content-Type'] = 'application/json'; opts.body = JSON.stringify(body); }
    return (await fetch(url, opts)).json();
}
async function apiUpload(url, fd) {
    return (await fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }, body: fd })).json();
}

/* ══════════════════════
   LOAD & RENDER
══════════════════════ */
async function loadTasks() {
    const d = await apiFetch(BASE_URL + '/data');
    if (d.success) { tasks = d.data; renderAllTasks(); updateCharts(); buildPerformanceChart(currentChartView); }
}
function renderAllTasks() {
    if (!tasks.length) {
        document.getElementById('taskBody').innerHTML = `<tr><td colspan="9"><div class="empty-state">
            <i class="bx bx-task"></i>
            <p class="fw-medium">Belum ada task</p>
            <p>Klik "Tambah Task Baru" untuk membuat task pertama.</p>
        </div></td></tr>`;
        return;
    }
    if (!GANTT.winStart) GANTT.initWindow();
    renderFilteredTasks();
}
function renderRow(t, idx) {
    const locked       = isRowLocked(t);
    const isRevisi     = t.status_akhir === 'revisi';
    const isReview     = t.status_akhir === 'review';
    const disabledAttr = locked ? 'disabled title="Task sudah Approved oleh PM, tidak dapat diubah"' : '';
    const assigneeOpts = TIM_LIST.map(m => {
        const label = m.jabatan ? `${escHtml(m.nama)}  [${escHtml(m.jabatan)}]` : escHtml(m.nama);
        return `<option value="${m.id_tim}" ${m.id_tim === t.id_tim ? 'selected' : ''}>${label}</option>`;
    }).join('');
    const tlStatus   = calcTimelineStatus(t);
    const briefFotos = (t.foto || []).filter(f => f.tipe !== 'hasil');
    const hasilFotos = (t.foto || []).filter(f => f.tipe === 'hasil');
    const levelLabel = t.level || 'mudah';
    const weight     = WEIGHT_MAP[levelLabel] || 1;
    const saClass    = t.status_akhir ? `sa-${t.status_akhir}` : 'sa-null';
    const spClass    = statusClass(t.status_progress);
    const briefDot   = briefFotos.length > 0
        ? `<span class="media-count-dot brief-dot">${briefFotos.length}</span>`
        : `<span class="media-count-dot empty-dot">0</span>`;
    const hasilDot   = hasilFotos.length > 0
        ? `<span class="media-count-dot hasil-dot">${hasilFotos.length}</span>`
        : `<span class="media-count-dot empty-dot">0</span>`;
    const todayD = new Date().toISOString().split('T')[0];
    const sevenD = new Date(Date.now() + 7 * 86400000).toISOString().split('T')[0];
    const TL_MAP = {
        early:          { cls:'early',          label:'Selesai Lebih Awal' },
        ontime:         { cls:'ontime',         label:'Tepat Waktu' },
        late:           { cls:'late',           label:'Terlambat Selesai' },
        inprogress:     { cls:'inprogress',     label:'Proses Pengerjaan' },
        overdue:        { cls:'overdue',        label:'Melewati Deadline' },
        upcoming:       { cls:'upcoming',       label:'Deadline Dekat' },
        todo:           { cls:'todo',           label:'Segera Dikerjakan' },
        todo_overdue:   { cls:'todo_overdue',   label:'Lewat Deadline' },
        todo_upcoming:  { cls:'todo_upcoming',  label:'Segera Dikerjakan' },
        pending:        { cls:'',               label:'' },
    };
    const tl = TL_MAP[tlStatus] || TL_MAP.pending;
    let selesaiHtml = '';
    if (t.status_progress === 'done' && t.tanggal_selesai) {
        selesaiHtml = `<div class="done-date-row ${tl.cls}"><i class="bx bx-check-circle" style="font-size:11px;flex-shrink:0;"></i><span>Selesai: ${fmtDate(t.tanggal_selesai)}</span><span style="opacity:.75;">&bull; ${tl.label}</span></div>`;
    } else if (tl.label) {
        selesaiHtml = `<span class="timeline-status timeline-${tl.cls}">${tl.label}</span>`;
    }
    let infoBanner = '';
    if (locked) {
        infoBanner = `<div class="approved-lock-banner"><i class="bx bx-lock-alt" style="font-size:11px;"></i> Terkunci — Approved PM</div>`;
    } else if (isRevisi) {
        infoBanner = `<div class="revisi-edit-banner"><i class="bx bx-edit-alt" style="font-size:11px;"></i> Perlu Revisi — silakan edit</div>`;
    } else if (isReview) {
        infoBanner = `<div style="display:flex;align-items:center;gap:5px;background:#EDE9FE;border:1px solid #DDD6FE;border-radius:6px;padding:5px 9px;font-size:10px;font-weight:700;color:#7C3AED;margin-top:5px;">
            <i class="bx bx-search-alt" style="font-size:11px;"></i> Sedang di-Review PM</div>`;
    }
    let rowClass = '';
    if (locked)        rowClass = 'is-approved';
    else if (isRevisi) rowClass = 'is-revisi';
    let numClass = '';
    if (locked)        numClass = 'approved';
    else if (isRevisi) numClass = 'revisi';
    let numLabel = '';
    if (locked)        numLabel = `<div style="margin-top:4px;font-size:8px;font-weight:800;color:#059669;text-align:center;text-transform:uppercase;letter-spacing:.04em;">✓ Approved</div>`;
    else if (isRevisi) numLabel = `<div style="margin-top:4px;font-size:8px;font-weight:800;color:#D97706;text-align:center;text-transform:uppercase;letter-spacing:.04em;">↩ Revisi</div>`;
    return `<tr data-id="${t.id_tugas}" ${rowClass ? `class="${rowClass}"` : ''}>
        <td style="text-align:center;">
            <div class="task-number ${numClass}">${idx + 1}</div>
            ${numLabel}
        </td>
        <td>
            <div class="action-cell">
                <button class="action-btn view"   onclick="openPreviewModal(${t.id_tugas})" title="Preview"><i class="bx bx-show"></i></button>
                <button class="action-btn delete" onclick="confirmDeleteTask(${t.id_tugas})" title="Hapus"
                    ${locked ? 'disabled title="Task Approved tidak dapat dihapus"' : ''}><i class="bx bx-trash"></i></button>
            </div>
        </td>
        <td style="min-width:200px;">
            <input type="text" class="cell-input" value="${escHtml(t.judul_tugas)}" placeholder="Nama task..."
                ${disabledAttr} oninput="markChange(${t.id_tugas},'judul_tugas',this.value)">
            <textarea class="cell-textarea" rows="2" placeholder="Deskripsi..."
                ${disabledAttr} oninput="markChange(${t.id_tugas},'deskripsi_tugas',this.value)">${escHtml(t.deskripsi_tugas || '')}</textarea>
            ${infoBanner}
        </td>
        <td>
            <select class="compact-select" ${disabledAttr} onchange="markChange(${t.id_tugas},'id_tim',parseInt(this.value),true)">
                ${assigneeOpts}
            </select>
        </td>
        <td style="min-width:100px;">
            <div class="media-summary-cell">
                <span class="media-type-pill brief-pill" onclick="openMediaModal(${t.id_tugas})">
                    <span class="pill-left"><i class="bx bx-paperclip" style="font-size:10px;"></i> Brief</span>${briefDot}
                </span>
                <span class="media-type-pill hasil-pill" onclick="openMediaModal(${t.id_tugas})">
                    <span class="pill-left"><i class="bx bx-file-blank" style="font-size:10px;"></i> Lap.</span>${hasilDot}
                </span>
            </div>
        </td>
        <td style="min-width:165px;">
            <div style="display:flex;flex-direction:column;gap:5px;">
                <select class="compact-select"
                    ${locked ? 'disabled title="Task Approved tidak dapat diubah statusnya"' : ''}
                    onchange="handleStatusProgressChange(${t.id_tugas},this.value)">
                    <option value="draft"       ${t.status_progress === 'draft'       ? 'selected' : ''}>Draft</option>
                    <option value="To Do"       ${t.status_progress === 'To Do'       ? 'selected' : ''}>To Do</option>
                    <option value="In Progress" ${t.status_progress === 'In Progress' ? 'selected' : ''}>In Progress</option>
                    <option value="done"        ${t.status_progress === 'done'        ? 'selected' : ''}>Done</option>
                </select>
                <span class="status-badge status-${spClass}" id="sBadge-${t.id_tugas}">${STATUS_LABELS[t.status_progress] || t.status_progress}</span>
                <div style="height:1px;background:var(--gray-100);margin:2px 0;"></div>
                <select class="compact-select" onchange="updateStatusAkhir(${t.id_tugas},this.value)">
                    <option value=""         ${!t.status_akhir               ? 'selected' : ''}>— Belum (PM) —</option>
                    <option value="review"   ${t.status_akhir === 'review'   ? 'selected' : ''}>Review</option>
                    <option value="revisi"   ${t.status_akhir === 'revisi'   ? 'selected' : ''}>Revisi</option>
                    <option value="approved" ${t.status_akhir === 'approved' ? 'selected' : ''}>Approved</option>
                </select>
                <span class="sa-badge ${saClass}" id="saBadge-${t.id_tugas}">${SA_LABELS[t.status_akhir] || '—'}</span>
            </div>
        </td>
        <td style="text-align:center;">
            <select class="compact-select" style="width:auto;min-width:80px;" ${disabledAttr} onchange="handleLevelChange(${t.id_tugas},this.value)">
                <option value="mudah"  ${levelLabel === 'mudah'  ? 'selected' : ''}>Mudah</option>
                <option value="medium" ${levelLabel === 'medium' ? 'selected' : ''}>Medium</option>
                <option value="susah"  ${levelLabel === 'susah'  ? 'selected' : ''}>Susah</option>
            </select>
            <div class="mt-1"><span class="level-badge level-${levelLabel}" id="lBadge-${t.id_tugas}">${levelLabel}</span></div>
            <div class="mt-1 weight-badge justify-content-center" id="wBadge-${t.id_tugas}"><i class="bx bx-dumbbell" style="font-size:10px;"></i>&nbsp;${weight}</div>
        </td>
        <td>
            <div style="display:flex;flex-direction:column;gap:4px;">
                <div style="font-size:10px;color:var(--gray-500);font-weight:600;">Mulai</div>
                <input type="date" id="inp-start-${t.id_tugas}" class="cell-input" style="font-size:11px;padding:3px 6px;"
                    value="${t.tanggal_mulai || todayD}" ${disabledAttr}
                    onchange="handleDateChange(${t.id_tugas},'tanggal_mulai',this.value)">
                <div style="font-size:10px;color:var(--gray-500);font-weight:600;margin-top:3px;">Deadline</div>
                <input type="date" id="inp-end-${t.id_tugas}" class="cell-input" style="font-size:11px;padding:3px 6px;"
                    value="${t.tenggat_waktu || sevenD}" ${disabledAttr}
                    onchange="handleDateChange(${t.id_tugas},'tenggat_waktu',this.value)">
                ${selesaiHtml}
            </div>
        </td>
        <td style="padding:6px;min-width:500px;width:500px;vertical-align:top;">
            <div id="gantt-cell-${t.id_tugas}"></div>
        </td>
    </tr>`;
}
function updateRowDateInputs(id, sd, ed) {
    const si = document.getElementById('inp-start-' + id), ei = document.getElementById('inp-end-' + id);
    if (si) si.value = sd; if (ei) ei.value = ed;
}
function handleDateChange(id, field, value) {
    markChange(id, field, value, true);
    const task = tasks.find(t => t.id_tugas === id);
    if (task) { task[field] = value; GANTT.placeBar(id, task); }
}

/* ══════════════════════
   TIMELINE STATUS
══════════════════════ */
function calcTimelineStatus(task) {
    const today = new Date(); today.setHours(0, 0, 0, 0);
    const end   = task.tenggat_waktu ? new Date(task.tenggat_waktu + 'T00:00:00') : null;
    const sp    = task.status_progress;
    if (sp === 'done') {
        if (!end) return 'early';
        if (task.tanggal_selesai) {
            const s = new Date(task.tanggal_selesai + 'T00:00:00');
            if (+s < +end) return 'early'; if (+s === +end) return 'ontime'; return 'late';
        }
        return end >= today ? 'early' : 'late';
    }
    if (sp === 'In Progress') {
        if (!end) return 'inprogress'; if (end < today) return 'overdue';
        if (Math.ceil((end - today) / 86400000) <= 3) return 'upcoming'; return 'inprogress';
    }
    if (sp === 'To Do') {
        if (!end) return 'todo'; if (end < today) return 'todo_overdue';
        if (Math.ceil((end - today) / 86400000) <= 3) return 'todo_upcoming'; return 'todo';
    }
    return 'pending';
}

/* ══════════════════════
   STATUS HELPERS
══════════════════════ */
function statusClass(s) {
    return ({ 'draft':'draft', 'To Do':'todo', 'In Progress':'progress', 'review':'review', 'done':'done' }[s] || 'todo');
}
function handleStatusProgressChange(id, status) {
    const task = tasks.find(t => t.id_tugas === id); if (!task) return;
    if (isRowLocked(task)) {
        showToast('Task yang sudah Approved tidak bisa diubah statusnya.', 'error', '🔒 Terkunci', 4000);
        const sel = document.querySelector(`tr[data-id="${id}"] select`);
        if (sel) sel.value = 'done'; return;
    }
    markChange(id, 'status_progress', status, true);
    rerenderRowStatus(id, status);
}
function rerenderRowStatus(id, status) {
    const b = document.getElementById('sBadge-' + id);
    if (b) { b.className = `status-badge status-${statusClass(status)}`; b.textContent = STATUS_LABELS[status] || status; }
    const task = tasks.find(t => t.id_tugas === id);
    if (task) { task.status_progress = status; GANTT.placeBar(id, task); }
}
function handleLevelChange(id, level) {
    const weight = WEIGHT_MAP[level] || 1;
    if (!pendingChanges[id]) pendingChanges[id] = {};
    pendingChanges[id]['level']  = level;
    pendingChanges[id]['weight'] = weight;
    const task = tasks.find(t => t.id_tugas === id);
    if (task) { task.level = level; task.weight = weight; }
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (row) { row.classList.add('pending-save'); row.style.outline = '2px solid #FDE68A'; row.style.outlineOffset = '-2px'; }
    const lb = document.getElementById('lBadge-' + id); if (lb) { lb.className = `level-badge level-${level}`; lb.textContent = level; }
    const wb = document.getElementById('wBadge-' + id); if (wb) wb.innerHTML = `<i class="bx bx-dumbbell" style="font-size:10px;"></i>&nbsp;${weight}`;
    clearTimeout(_autoSaveTimers[id]); _autoSaveTimers[id] = setTimeout(() => autoSave(id), 200);
}

/* ══════════════════════
   PENDING & AUTO-SAVE
══════════════════════ */
const _autoSaveTimers = {};
function markChange(id, field, value, saveNow = false) {
    if (!pendingChanges[id]) pendingChanges[id] = {};
    pendingChanges[id][field] = value;
    const task = tasks.find(t => t.id_tugas === id); if (task) task[field] = value;
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (row) { row.classList.add('pending-save'); row.style.outline = '2px solid #FDE68A'; row.style.outlineOffset = '-2px'; }
    clearTimeout(_autoSaveTimers[id]); _autoSaveTimers[id] = setTimeout(() => autoSave(id), saveNow ? 200 : 800);
}
async function autoSave(id) {
    if (!pendingChanges[id]) return;
    const changes = { ...pendingChanges[id] };
    if (changes.level) changes.weight = WEIGHT_MAP[changes.level] || 1;
    const changedKeys   = Object.keys(pendingChanges[id]).filter(k => k !== 'weight');
    const changedLabels = changedKeys.map(k => FIELD_LABELS[k] || k);
    let fieldDesc = !changedLabels.length ? 'Data' : changedLabels.length <= 2 ? changedLabels.join(' & ') : changedLabels.slice(0, 2).join(', ') + ` +${changedLabels.length - 2} lainnya`;
    const task     = tasks.find(t => t.id_tugas === parseInt(id));
    const taskName = task ? (task.judul_tugas || 'Task').substring(0, 28) : 'Task';
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (row) { row.style.outline = '2px solid #A5B4FC'; row.style.outlineOffset = '-2px'; }
    showToast(`${fieldDesc} — "${taskName}"`, 'saving', 'Menyimpan ke server...', 0);
    try {
        const d = await apiFetch(`${BASE_URL}/${id}`, 'PUT', changes);
        if (d.success) {
            delete pendingChanges[id];
            if (row) { row.style.outline = '2px solid #6EE7B7'; setTimeout(() => { row.classList.remove('pending-save'); row.style.outline = ''; row.style.outlineOffset = ''; }, 700); }
            if (task && d.data) { Object.assign(task, d.data); if (changes.status_progress) renderAllTasks(); }
            showToast(`${fieldDesc} berhasil disimpan`, 'success', `✓ ${taskName}`, 3000);
            updateCharts(); buildPerformanceChart(currentChartView);
        } else {
            if (row) row.style.outline = '2px solid #FCA5A5';
            showToast(d.message || 'Coba "Simpan Semua" untuk mencoba ulang.', 'error', `Gagal: ${taskName}`, 5000);
        }
    } catch (e) {
        if (row) row.style.outline = '2px solid #FCA5A5';
        showToast('Koneksi bermasalah. Cek jaringan Anda.', 'error', 'Gagal terhubung ke server', 5000);
    }
}
async function saveAllPending() {
    const ids = Object.keys(pendingChanges);
    if (!ids.length) { showToast('Semua perubahan sudah tersimpan.', 'info', 'Tidak ada perubahan', 2500); return; }
    let ok = 0, fail = 0;
    showToast(`Menyimpan ${ids.length} task...`, 'saving', 'Simpan Semua', 0);
    for (const id of ids) {
        if (pendingChanges[id].level) pendingChanges[id].weight = WEIGHT_MAP[pendingChanges[id].level] || 1;
        const d = await apiFetch(`${BASE_URL}/${id}`, 'PUT', pendingChanges[id]);
        if (d.success) {
            ok++; delete pendingChanges[id];
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) { row.classList.remove('pending-save'); row.style.outline = ''; row.style.outlineOffset = ''; }
            const task = tasks.find(t => t.id_tugas === parseInt(id)); if (task && d.data) Object.assign(task, d.data);
        } else fail++;
    }
    if (ok && !fail) showToast(`${ok} task berhasil disimpan.`, 'success', '✓ Simpan Semua', 3000);
    if (ok && fail)  showToast(`${ok} berhasil, ${fail} gagal.`, 'info', 'Simpan Sebagian', 4000);
    if (!ok && fail) showToast(`${fail} task gagal disimpan.`, 'error', 'Simpan Gagal', 5000);
    renderAllTasks(); updateCharts(); buildPerformanceChart(currentChartView);
}

/* ══════════════════════
   ADD TASK
══════════════════════ */
async function addNewTask() {
    if (!TIM_LIST.length) { showToast('Tambahkan anggota tim terlebih dahulu.', 'error', 'Tim Kosong'); return; }
    const today = new Date().toISOString().split('T')[0];
    const end   = new Date(Date.now() + 7 * 86400000).toISOString().split('T')[0];
    const payload = { judul_tugas:'Task Baru', deskripsi_tugas:'', id_tim:TIM_LIST[0].id_tim, level:'mudah', weight:1, status_progress:'To Do', tanggal_mulai:today, tenggat_waktu:end };
    try {
        const d = await apiFetch(BASE_URL, 'POST', payload);
        if (d.success) {
            tasks.push(d.data); renderAllTasks(); updateCharts(); buildPerformanceChart(currentChartView);
            showToast('Task baru berhasil ditambahkan!', 'success', '+ Task Ditambahkan', 3000);
            setTimeout(() => {
                const row = document.querySelector(`tr[data-id="${d.data.id_tugas}"]`);
                if (row) { row.scrollIntoView({ behavior:'smooth', block:'center' }); row.classList.add('is-new'); row.querySelector('.cell-input')?.focus(); }
            }, 80);
        } else {
            showToast(d.message || (d.errors ? Object.values(d.errors).flat().join(' | ') : 'Gagal menambahkan task.'), 'error', 'Gagal Menambah Task', 5000);
        }
    } catch (err) { showToast('Terjadi kesalahan jaringan.', 'error', 'Error Jaringan', 5000); }
}

/* ══════════════════════
   STATUS AKHIR
══════════════════════ */
async function updateStatusAkhir(id, sa) {
    const task     = tasks.find(t => t.id_tugas === id);
    const taskName = task ? (task.judul_tugas || 'Task').substring(0, 28) : 'Task';
 
    if (sa === 'approved' && task && task.status_progress !== 'done') {
        showToast('Task harus berstatus Done sebelum dapat di-Approved.', 'error', 'Tidak Bisa Approved', 4000);
        const selects = document.querySelectorAll(`tr[data-id="${id}"] select`);
        selects.forEach(sel => { if (sel.querySelector('option[value="approved"]')) sel.value = task.status_akhir || ''; });
        return;
    }
 
    showToast('Memperbarui status akhir...', 'saving', `"${taskName}"`, 0);
 
    const d = await apiFetch(`${BASE_URL}/${id}/status-akhir`, 'PATCH', { status_akhir: sa || null });
 
    if (d.success) {
        if (task) {
            task.status_akhir = sa || null;
 
            // ── PERBAIKAN: Jika revisi, server otomatis reset status_progress ke To Do ──
            // Server mengirimkan kembali status_progress yang sudah diperbarui
            if (d.data && d.data.status_progress) {
                task.status_progress = d.data.status_progress;
            }
            if (d.data && d.data.tanggal_selesai !== undefined) {
                task.tanggal_selesai = d.data.tanggal_selesai;
            }
        }
 
        const b = document.getElementById('saBadge-' + id);
        if (b) { b.className = `sa-badge ${sa ? 'sa-' + sa : 'sa-null'}`; b.textContent = SA_LABELS[sa] || '—'; }
 
        showToast(
            sa ? `Status akhir diset ke "${SA_LABELS[sa]}"` + (sa === 'revisi' ? ' — task dikembalikan ke To Do' : '') : 'Status akhir dihapus',
            'success',
            `✓ ${taskName}`,
            3500
        );
 
        // Re-render semua baris — ini penting agar baris revisi pindah ke kolom To Do
        renderAllTasks();
        updateCharts();
        buildPerformanceChart(currentChartView);
 
    } else {
        showToast(d.message || 'Gagal memperbarui status akhir.', 'error', `Gagal: ${taskName}`, 5000);
    }
}

/* ══════════════════════
   DELETE
══════════════════════ */
let delId = null;
function confirmDeleteTask(id) {
    const task = tasks.find(t => t.id_tugas === id);
    if (task && isRowLocked(task)) { showToast('Task yang sudah Approved tidak dapat dihapus.', 'error', '🔒 Tidak Bisa Hapus', 4000); return; }
    document.getElementById('deleteTaskName').textContent = task ? task.judul_tugas : '—';
    delId = id;
    new bootstrap.Modal(document.getElementById('modalHapusTask')).show();
}
document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
    if (!delId) return;
    const task = tasks.find(t => t.id_tugas === delId), name = task ? task.judul_tugas : 'Task';
    const d = await apiFetch(`${BASE_URL}/${delId}`, 'DELETE');
    bootstrap.Modal.getInstance(document.getElementById('modalHapusTask')).hide();
    if (d.success) {
        tasks = tasks.filter(t => t.id_tugas !== delId); delete pendingChanges[delId];
        renderAllTasks(); updateCharts(); buildPerformanceChart(currentChartView);
        showToast(`"${name}" berhasil dihapus.`, 'info', '🗑 Task Dihapus', 3000);
    } else { showToast(d.message || 'Gagal menghapus task.', 'error', 'Gagal Hapus', 5000); }
    delId = null;
});

/* ══════════════════════
   MODAL PREVIEW
══════════════════════ */
function openPreviewModal(id) {
    const task = tasks.find(t => t.id_tugas === id); if (!task) return;
    document.getElementById('previewModalSubtitle').textContent = task.judul_tugas;
    document.getElementById('pvTitle').textContent = task.judul_tugas || '—';
    const descEl = document.getElementById('pvDesc');
    if (task.deskripsi_tugas) { descEl.textContent = task.deskripsi_tugas; descEl.style.display = 'block'; } else descEl.style.display = 'none';
    const member = TIM_LIST.find(m => m.id_tim === task.id_tim);
    document.getElementById('pvAssignee').innerHTML = member
        ? (member.jabatan ? `${escHtml(member.nama)} <span style="background:var(--primary-light);color:var(--primary-blue);font-size:9px;font-weight:700;padding:2px 7px;border-radius:20px;text-transform:uppercase;letter-spacing:.04em;border:1px solid #C7D2FE;margin-left:6px;">${escHtml(member.jabatan)}</span>` : escHtml(member.nama))
        : '—';
    const lv = task.level || 'mudah';
    document.getElementById('pvLevel').innerHTML  = `<span class="level-badge level-${lv}">${lv}</span>`;
    document.getElementById('pvWeight').textContent = (WEIGHT_MAP[lv] || 1) + ' poin';
    document.getElementById('pvStart').textContent = task.tanggal_mulai  ? fmtDateLong(task.tanggal_mulai)  : 'Belum diatur';
    document.getElementById('pvEnd').textContent   = task.tenggat_waktu  ? fmtDateLong(task.tenggat_waktu)  : 'Belum diatur';
    const tlStatus = calcTimelineStatus(task);
    const TL_LABELS_PV = { early:'✓ Selesai Lebih Awal',ontime:'✓ Tepat Waktu',late:'✗ Terlambat Selesai',inprogress:'🔧 Proses Pengerjaan',overdue:'⚠ Melewati Deadline',todo:'📋 Segera Dikerjakan',todo_overdue:'⚠ Lewat Deadline',todo_upcoming:'⏰ Segera Dikerjakan',upcoming:'⏰ Deadline Dekat',pending:'—' };
    const TL_COLORS    = { early:'var(--success-green)',ontime:'var(--warning-orange)',late:'var(--danger-red)',inprogress:'#D97706',overdue:'#92400E',upcoming:'#92400E',todo:'var(--primary-blue)',todo_overdue:'#B91C1C',todo_upcoming:'#92400E',pending:'var(--gray-500)' };
    if (task.status_progress === 'done' && task.tanggal_selesai) {
        document.getElementById('pvSelesai').textContent = fmtDateLong(task.tanggal_selesai);
        document.getElementById('pvKetepatan').innerHTML = `<span style="font-weight:700;color:${TL_COLORS[tlStatus]};">${TL_LABELS_PV[tlStatus]}</span>`;
    } else {
        document.getElementById('pvSelesai').textContent   = task.status_progress === 'done' ? 'Tidak dicatat' : 'Belum selesai';
        document.getElementById('pvKetepatan').textContent = task.status_progress !== 'done' ? (tlStatus === 'late' ? '⚠ Melewati Deadline' : '—') : '—';
    }
    const sp = task.status_progress || 'To Do';
    document.getElementById('pvStatus').innerHTML = `<span class="status-badge status-${statusClass(sp)}">${STATUS_LABELS[sp] || sp}</span>`;
    document.getElementById('pvSA').innerHTML = task.status_akhir
        ? `<span class="sa-badge sa-${task.status_akhir}">${SA_LABELS[task.status_akhir]}</span>`
        : `<span class="sa-badge sa-null">Belum ditentukan</span>`;
    renderPreviewGallery('pvGalleryBrief', (task.foto || []).filter(f => f.tipe !== 'hasil'), false);
    renderPreviewGallery('pvGalleryHasil', (task.foto || []).filter(f => f.tipe === 'hasil'),  true);
    new bootstrap.Modal(document.getElementById('modalPreviewTask')).show();
}
function renderPreviewGallery(elId, fotos, isHasil) {
    const g = document.getElementById(elId);
    if (!fotos.length) { g.innerHTML = `<span class="preview-empty-media">Belum ada ${isHasil ? 'laporan hasil' : 'foto brief'}.</span>`; return; }
    g.innerHTML = fotos.map(f => {
        if (isImageFile(f.nama_file || f.url)) return `<img class="preview-thumb ${isHasil ? 'hasil-thumb' : ''}" src="${escHtml(f.url)}" onclick="window.open('${escHtml(f.url)}','_blank')">`;
        return `<a class="preview-doc-item" href="${escHtml(f.url)}" target="_blank" rel="noopener"><i class="bx ${docIcon(f.nama_file || f.url)}"></i><span>${escHtml((f.nama_file || 'Dokumen').substring(0, 20))}</span></a>`;
    }).join('');
}

/* ══════════════════════
   MODAL MEDIA
══════════════════════ */
function openMediaModal(id) {
    const task = tasks.find(t => t.id_tugas === id); if (!task) return;
    document.getElementById('media_id_tugas').value = id;
    document.getElementById('mediaModalSubtitle').textContent = task.judul_tugas;
    renderGallery('galleryBrief', (task.foto || []).filter(f => f.tipe !== 'hasil'), id);
    renderGallery('galleryHasil', (task.foto || []).filter(f => f.tipe === 'hasil'),  id);
    new bootstrap.Modal(document.getElementById('modalMediaTask')).show();
}
function renderGallery(elId, fotos, id) {
    const g = document.getElementById(elId);
    if (!fotos.length) { g.innerHTML = `<div style="color:var(--gray-400);font-size:12px;padding:8px 0;">Belum ada file</div>`; return; }
    g.innerHTML = fotos.map(f => {
        if (isImageFile(f.nama_file || f.url)) return `<div class="gallery-item ${f.tipe === 'hasil' ? 'hasil-item' : ''}"><img src="${escHtml(f.url)}" onclick="window.open('${escHtml(f.url)}','_blank')"><button class="g-remove" onclick="deleteFoto(${id},${f.id_tugas_foto})"><i class="bx bx-x"></i></button></div>`;
        return `<div class="gallery-doc" onclick="window.open('${escHtml(f.url)}','_blank')"><i class="bx ${docIcon(f.nama_file || f.url)}"></i><span>${escHtml((f.nama_file || 'Doc').substring(0, 14))}</span><button class="g-remove" onclick="event.stopPropagation();deleteFoto(${id},${f.id_tugas_foto})"><i class="bx bx-x"></i></button></div>`;
    }).join('');
}
function handleDragOver(e, z) { e.preventDefault(); document.getElementById(z).classList.add('dragover'); }
function handleDragLeave(z)   { document.getElementById(z).classList.remove('dragover'); }
function handleDrop(e, tipe) {
    e.preventDefault();
    document.getElementById(tipe === 'brief' ? 'dropBrief' : 'dropHasil').classList.remove('dragover');
    if (e.dataTransfer?.files.length) uploadFilesRaw(e.dataTransfer.files, tipe);
}
function uploadFiles(input, tipe) { if (input.files.length) uploadFilesRaw(input.files, tipe); input.value = ''; }
async function uploadFilesRaw(files, tipe) {
    const id = parseInt(document.getElementById('media_id_tugas').value); if (!id) return;
    const fd = new FormData();
    Array.from(files).forEach(f => fd.append('foto[]', f));
    fd.append('tipe', tipe); fd.append('_token', CSRF_TOKEN);
    showToast('Mengupload file...', 'saving', 'Upload', 0);
    const d = await apiUpload(`${BASE_URL}/${id}/foto`, fd);
    if (d.success) {
        showToast(`${d.data.length} file berhasil diupload.`, 'success', '📎 Upload Selesai', 3000);
        const task = tasks.find(t => t.id_tugas === id);
        if (task) {
            task.foto = [...(task.foto || []), ...d.data];
            renderGallery('galleryBrief', task.foto.filter(f => f.tipe !== 'hasil'), id);
            renderGallery('galleryHasil', task.foto.filter(f => f.tipe === 'hasil'),  id);
        }
        renderAllTasks();
    } else { showToast('Upload gagal. Periksa ukuran/tipe file.', 'error', 'Upload Gagal', 5000); }
}
async function deleteFoto(id, fid) {
    const d = await apiFetch(`${BASE_URL}/${id}/foto/${fid}`, 'DELETE');
    if (d.success) {
        const task = tasks.find(t => t.id_tugas === id);
        if (task) {
            task.foto = task.foto.filter(f => f.id_tugas_foto !== fid);
            renderGallery('galleryBrief', task.foto.filter(f => f.tipe !== 'hasil'), id);
            renderGallery('galleryHasil', task.foto.filter(f => f.tipe === 'hasil'),  id);
        }
        renderAllTasks(); showToast('File berhasil dihapus.', 'info', '🗑 File Dihapus', 2500);
    } else { showToast('Gagal menghapus file.', 'error', 'Gagal Hapus', 4000); }
}

/* ══════════════════════
   TIM
══════════════════════ */
async function inviteTim() {
    const ids = Array.from(document.querySelectorAll('.invite-checkbox:checked')).map(c => c.value);
    if (!ids.length) { showToast('Pilih minimal satu user.', 'error', 'Pilih User', 3000); return; }
    const d = await apiFetch(`${BASE_URL}/tim/invite`, 'POST', { id_user: ids });
    if (d.success) { showToast(d.message, 'success', '✓ Tim Diperbarui', 2500); setTimeout(() => location.reload(), 1200); }
    else showToast(d.message || 'Gagal mengundang anggota.', 'error', 'Gagal Undang', 5000);
}
async function removeTim(id) {
    if (!confirm('Keluarkan anggota ini dari tim?')) return;
    const d = await apiFetch(`${BASE_URL}/tim/${id}`, 'DELETE');
    if (d.success) { document.getElementById('tim-item-' + id)?.remove(); showToast('Anggota berhasil dikeluarkan.', 'info', 'Tim Diperbarui', 2500); }
    else showToast(d.message || 'Gagal mengeluarkan anggota.', 'error', 'Gagal', 4000);
}
function filterUsers(q) {
    q = q.toLowerCase();
    document.querySelectorAll('#userCheckboxList .user-checkbox-item').forEach(i => {
        i.style.display = i.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}

/* ══════════════════════════════════════════════
   PERFORMANCE CHART
══════════════════════════════════════════════ */
function getPerformanceData() {
    return TIM_LIST.map(m => {
        const mt = tasks.filter(t => t.id_tim === m.id_tim);
        let onTime = 0, early = 0, late = 0;
        mt.forEach(t => {
            if (t.status_progress === 'done') {
                const tls = calcTimelineStatus(t);
                if (tls === 'early') early++; else if (tls === 'ontime') onTime++; else if (tls === 'late') late++;
            }
        });
        return { name: m.nama, onTime, early, late, total: mt.length };
    });
}
function buildPerformanceChart(view) {
    if (performanceChart) { performanceChart.destroy(); performanceChart = null; }
    const data = getPerformanceData();
    performanceChart = new ApexCharts(document.querySelector('#employeePerformanceChart'), {
        chart: { type:'bar', height:280, stacked:view !== 'bar', toolbar:{ show:false }, fontFamily:'inherit', background:'transparent' },
        series: [
            { name:'Tepat Waktu',      data:data.map(d => d.onTime), color:'#10B981' },
            { name:'Sebelum Deadline', data:data.map(d => d.early),  color:'#4F46E5' },
            { name:'Terlambat',        data:data.map(d => d.late),   color:'#EF4444' },
        ],
        xaxis: { categories:data.length ? data.map(d => d.name) : ['—'], labels:{ style:{ fontSize:'12px', fontWeight:600, colors:'#6B7280' } } },
        yaxis: { min:0, forceNiceScale:true, title:{ text:'Jumlah Task', style:{ fontSize:'11px', color:'#6B7280' } }, labels:{ style:{ fontSize:'11px', colors:'#6B7280' }, formatter:v => Math.round(v) } },
        plotOptions: { bar:{ columnWidth:'50%', borderRadius:4, borderRadiusApplication:'end' } },
        dataLabels: { enabled:view === 'percentage', formatter:v => v > 0 ? v : '' },
        grid: { borderColor:'#E5E7EB', strokeDashArray:4, padding:{ left:8, right:8 } },
        legend: { position:'top', horizontalAlign:'right', fontSize:'12px' },
        tooltip: { shared:true, intersect:false, y:{ formatter:v => v + ' task' } },
    });
    performanceChart.render();
}
function changeChartView(view, btn) {
    currentChartView = view;
    document.querySelectorAll('.chart-control-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
    buildPerformanceChart(view);
}

/* ══════════════════════════════════════════════
   UPDATE CHARTS
══════════════════════════════════════════════ */
function updateCharts() {
    const nonDraft    = tasks.filter(t => t.status_progress !== 'draft');
    const W           = t => (t.weight > 0 ? t.weight : 1);
    const totalWeight = nonDraft.reduce((s, t) => s + W(t), 0);
    const approvedW   = nonDraft
        .filter(t => t.status_progress === 'done' && t.status_akhir === 'approved')
        .reduce((s, t) => s + W(t), 0);
    const pct = totalWeight > 0 ? Math.round((approvedW / totalWeight) * 100) : 0;
    const pctEl = document.getElementById('progressPercentageText');
    if (pctEl) pctEl.textContent = pct + '%';
    const pctLabelEl = document.getElementById('progressPctLabel');
    if (pctLabelEl) pctLabelEl.textContent = pct + '%';
    const barMain = document.getElementById('progressBarMain');
    if (barMain) barMain.style.width = pct + '%';
    const weightLabel = document.getElementById('progressWeightLabel');
    if (weightLabel) weightLabel.textContent = `${approvedW} / ${totalWeight} weight`;
    renderProgressBars(nonDraft, totalWeight, W);
}
function renderProgressBars(nonDraft, totW, W) {
    const totTask = nonDraft.length;
    function barItem(label, color, n, w, totT, totW2) {
        const pn = totT  > 0 ? Math.round(n / totT  * 100) : 0;
        const pw = totW2 > 0 ? Math.round(w / totW2 * 100) : 0;
        return `<div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;">
                <div style="display:flex;align-items:center;gap:7px;">
                    <span style="display:inline-block;width:9px;height:9px;border-radius:2px;background:${color};flex-shrink:0;"></span>
                    <span style="font-size:12px;font-weight:600;color:var(--gray-800);">${label}</span>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:11px;color:var(--gray-500);">${n} task &bull; ${pn}%</span>
                    <span style="font-size:11px;font-weight:700;color:var(--gray-700);min-width:70px;text-align:right;">W: ${w} (${pw}%)</span>
                </div>
            </div>
            <div class="progress-bar-sm">
                <div class="progress-bar-fill-sm" style="width:${pw}%;background:${color};"></div>
            </div>
        </div>`;
    }
    const spDefs = [
        { label:'To Do',       val:'To Do',      color:'#6B7280' },
        { label:'In Progress', val:'In Progress', color:'#F59E0B' },
        { label:'Done',        val:'done',        color:'#10B981' },
    ];
    const spContainer = document.getElementById('spBarsContainer');
    if (spContainer) {
        spContainer.innerHTML = spDefs.map(r => {
            const filtered = nonDraft.filter(t => t.status_progress === r.val);
            return barItem(r.label, r.color, filtered.length, filtered.reduce((s, t) => s + W(t), 0), totTask, totW);
        }).join('');
    }
    const saDefs = [
        { label:'Belum Dinilai', val:'__null__', color:'#9CA3AF' },
        { label:'Review',        val:'review',   color:'#8B5CF6' },
        { label:'Revisi',        val:'revisi',   color:'#F59E0B' },
        { label:'Approved',      val:'approved', color:'#10B981' },
    ];
    const saContainer = document.getElementById('saBarsContainer');
    if (saContainer) {
        saContainer.innerHTML = saDefs.map(r => {
            const filtered = r.val === '__null__'
                ? nonDraft.filter(t => !t.status_akhir)
                : nonDraft.filter(t => t.status_akhir === r.val);
            return barItem(r.label, r.color, filtered.length, filtered.reduce((s, t) => s + W(t), 0), totTask, totW);
        }).join('');
    }
}

/* ══════════════════════
   HELPERS
══════════════════════ */
function sanitizeDate(s) { if (!s) return null; return String(s).trim().substring(0, 10); }
function fmtDate(s) {
    const clean = sanitizeDate(s);
    if (!clean || !/^\d{4}-\d{2}-\d{2}$/.test(clean)) return '—';
    const d = new Date(clean + 'T00:00:00'); if (isNaN(d.getTime())) return '—';
    const mn = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
    return `${d.getDate()} ${mn[d.getMonth()]}`;
}
function fmtDateLong(s) {
    const clean = sanitizeDate(s); if (!clean || !/^\d{4}-\d{2}-\d{2}$/.test(clean)) return '—';
    const d = new Date(clean + 'T00:00:00'); if (isNaN(d.getTime())) return '—';
    const mn = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    return `${d.getDate()} ${mn[d.getMonth()]} ${d.getFullYear()}`;
}
function escHtml(s) { if (!s) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }
function isImageFile(n) { return /\.(jpg|jpeg|png|gif|webp|bmp|svg)$/i.test(n || ''); }
function docIcon(n) {
    if (/\.pdf$/i.test(n))   return 'bx-file-pdf';
    if (/\.docx?$/i.test(n)) return 'bx-file-doc';
    if (/\.xlsx?$/i.test(n)) return 'bxs-spreadsheet';
    return 'bx-file';
}

/* ══════════════════════
   INIT
══════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
    GANTT.initWindow();
    renderProgressBars([], 0, t => 1);
    setTimeout(() => { loadTasks(); }, 150);
});
</script>
@endpush