@extends('layouts.master')
@section('title', 'Task Saya' . ($currentProjek ? ' — ' . $currentProjek->nama_projek : ''))
@push('styles')
<style>
:root {
    --blue: #4F46E5; --blue-light: #EEF2FF;
    --green: #10B981; --green-light: #D1FAE5;
    --amber: #F59E0B; --amber-light: #FEF3C7;
    --orange: #EA580C; --orange-light: #FFF7ED;
    --yellow: #EAB308; --yellow-light: #FEFCE8;
    --red: #EF4444; --red-light: #FEE2E2;
    --purple: #8B5CF6; --purple-light: #EDE9FE;
    --g50:#F9FAFB;--g100:#F3F4F6;--g200:#E5E7EB;--g300:#D1D5DB;
    --g400:#9CA3AF;--g500:#6B7280;--g600:#4B5563;--g700:#374151;
    --g800:#1F2937;--g900:#111827;
}
body { background: var(--g50); }

/* ── Project Selector ── */
.project-selector-wrap {
    background: white; border: 1px solid var(--g200); border-radius: 12px;
    padding: 16px 24px; margin-bottom: 20px;
    display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
    box-shadow: 0 1px 3px rgba(0,0,0,.08);
}
.selector-label { font-size: 13px; font-weight: 700; color: var(--g600); text-transform: uppercase; letter-spacing: .05em; white-space: nowrap; }
.project-select-box {
    flex: 1; min-width: 220px; max-width: 420px;
    padding: 9px 14px; border: 1.5px solid var(--g300); border-radius: 8px;
    font-size: 14px; font-weight: 600; color: var(--g800); background: white;
    outline: none; cursor: pointer; transition: border-color .18s;
}
.project-select-box:focus { border-color: var(--blue); }
.selector-current-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--blue-light); color: var(--blue);
    border: 1px solid #C7D2FE; border-radius: 20px; padding: 4px 12px;
    font-size: 12px; font-weight: 700;
}

/* ── Project Info Card ── */
.proj-info-card {
    background: linear-gradient(135deg, var(--purple) 0%, #6D28D9 100%);
    border-radius: 14px; padding: 22px 28px; margin-bottom: 20px;
    position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,.1);
}
.proj-info-card::before {
    content: ''; position: absolute; top: -60px; right: -60px;
    width: 220px; height: 220px;
    background: radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 70%);
    border-radius: 50%;
}
.proj-info-title { font-size: 22px; font-weight: 800; color: white; margin-bottom: 6px; }
.proj-info-meta { display: flex; gap: 20px; flex-wrap: wrap; margin-top: 12px; }
.proj-info-item { display: flex; align-items: center; gap: 7px; color: rgba(255,255,255,.85); font-size: 13px; font-weight: 500; }
.proj-info-item i { font-size: 16px; color: #C9A84C; }
.proj-info-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
    border-radius: 20px; padding: 4px 12px; color: white; font-size: 12px; font-weight: 600;
}
.proj-info-badge.pm { background: rgba(255,255,255,.2); border-color: rgba(255,255,255,.4); color: #F3E8FF; }

/* ── Stats Bar ── */
.stats-bar { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
.stat-card {
    background: white; border: 1px solid var(--g200); border-radius: 12px;
    padding: 20px; display: flex; align-items: center; gap: 14px;
    box-shadow: 0 1px 3px rgba(0,0,0,.07); transition: transform .2s;
}
.stat-card:hover { transform: translateY(-2px); }
.stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
.si-blue { background: linear-gradient(135deg, var(--blue), var(--purple)); color: white; }
.si-green { background: linear-gradient(135deg, var(--green), #059669); color: white; }
.si-amber { background: linear-gradient(135deg, var(--amber), #D97706); color: white; }
.si-red { background: linear-gradient(135deg, var(--red), #DC2626); color: white; }
.stat-val { font-size: 28px; font-weight: 800; color: var(--g900); line-height: 1; }
.stat-lbl { font-size: 12px; font-weight: 700; color: var(--g600); text-transform: uppercase; letter-spacing: .04em; margin-bottom: 2px; }
.stat-sub { font-size: 11px; color: var(--g400); margin-top: 2px; }

/* ── Dashboard Grid 2-col ── */
.dash-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
.dash-card { background: white; border: 1px solid var(--g200); border-radius: 12px; padding: 22px 24px; box-shadow: 0 1px 3px rgba(0,0,0,.07); }
.dash-card-title { font-size: 17px; font-weight: 700; color: var(--g900); margin-bottom: 4px; }
.dash-card-sub { font-size: 13px; color: var(--g500); margin-bottom: 16px; }

/* ── Performance bars ── */
.perf-item { margin-bottom: 14px; }
.perf-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
.perf-name { font-size: 13px; font-weight: 700; color: var(--g800); }
.perf-me-tag { font-size: 9px; font-weight: 800; background: var(--blue); color: white; border-radius: 10px; padding: 2px 6px; margin-left: 5px; text-transform: uppercase; }
.perf-jabatan { font-size: 10px; color: var(--g400); background: var(--g100); border: 1px solid var(--g200); border-radius: 10px; padding: 2px 7px; margin-left: 5px; }
.perf-stat { display: flex; align-items: center; gap: 8px; }
.perf-fraction { font-size: 11px; font-weight: 700; color: var(--g500); }
.perf-pct { font-size: 13px; font-weight: 800; min-width: 40px; text-align: right; }
.perf-track { height: 8px; background: var(--g100); border-radius: 99px; overflow: hidden; border: 1px solid var(--g200); }
.perf-fill { height: 100%; border-radius: 99px; transition: width .5s ease; }
.perf-detail { display: flex; gap: 8px; margin-top: 4px; flex-wrap: wrap; }
.perf-dot-item { display: flex; align-items: center; gap: 3px; font-size: 10px; font-weight: 700; }
.perf-dot { width: 8px; height: 8px; border-radius: 2px; }

/* ── Progress Summary ── */
.big-pct { font-size: 44px; font-weight: 800; color: var(--green); line-height: 1; }
.progress-track { height: 14px; background: var(--g100); border-radius: 99px; overflow: hidden; border: 1px solid var(--g200); margin-bottom: 20px; }
.progress-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, var(--blue), var(--purple)); transition: width .6s ease; }
.status-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.status-col-title { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: var(--g500); margin-bottom: 12px; display: flex; align-items: center; gap: 6px; }
.status-col-title::before { content: ''; display: inline-block; width: 3px; height: 13px; background: var(--blue); border-radius: 2px; }
.status-col-title.right::before { background: var(--green); }
.bar-item { margin-bottom: 10px; }
.bar-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
.bar-label { display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: var(--g700); }
.bar-dot { width: 8px; height: 8px; border-radius: 2px; flex-shrink: 0; }
.bar-stats { display: flex; align-items: center; gap: 8px; }
.bar-n { font-size: 11px; color: var(--g500); }
.bar-w { font-size: 11px; font-weight: 700; color: var(--g700); min-width: 55px; text-align: right; }
.bar-track-sm { height: 6px; background: var(--g100); border-radius: 99px; overflow: hidden; border: 1px solid var(--g200); }
.bar-fill-sm { height: 100%; border-radius: 99px; }

/* ── Kanban ── */
.kanban-wrapper { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 18px; margin-bottom: 24px; }
.kanban-col { background: white; border: 1px solid var(--g200); border-radius: 12px; display: flex; flex-direction: column; min-height: 500px; max-height: 780px; }
.kanban-head { padding: 18px 20px; border-bottom: 1px solid var(--g200); background: var(--g50); border-radius: 12px 12px 0 0; }
.kanban-head-title { font-size: 15px; font-weight: 700; display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
.kanban-head-title i { font-size: 18px; }
.col-todo .kanban-head-title { color: var(--g700); }
.col-progress .kanban-head-title { color: #D97706; }
.col-done .kanban-head-title { color: #059669; }
.kanban-count { font-size: 12px; font-weight: 700; background: white; border: 1px solid var(--g200); border-radius: 6px; padding: 2px 10px; display: inline-block; color: var(--g600); }
.kanban-body { padding: 14px; flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; }
.kanban-body::-webkit-scrollbar { width: 6px; }
.kanban-body::-webkit-scrollbar-track { background: var(--g100); border-radius: 3px; }
.kanban-body::-webkit-scrollbar-thumb { background: var(--g300); border-radius: 3px; }
.kanban-body.drag-over { background: var(--blue-light); border: 2px dashed var(--blue); border-radius: 8px; }
.kanban-empty { padding: 32px 16px; text-align: center; color: var(--g400); }
.kanban-empty i { font-size: 40px; display: block; margin-bottom: 8px; opacity: .5; }
.kanban-empty p { font-size: 12px; font-weight: 500; margin: 0; }

/* ══════════════════════════════════════════════════
   TASK CARD — Warna berbeda untuk kondisi berbeda
   ══════════════════════════════════════════════════
   • Normal            → border abu (default)
   • is-revisi         → KUNING (revisi dari PM)
   • deadline-near     → ORANGE (mendekati deadline, ≤3 hari)
   • deadline-overdue  → MERAH (sudah lewat deadline)
   • is-approved       → HIJAU muda (locked)
   ══════════════════════════════════════════════════ */
.task-card {
    background: white; border: 1.5px solid var(--g200); border-radius: 10px;
    padding: 14px; cursor: grab; transition: all .2s;
    box-shadow: 0 1px 3px rgba(0,0,0,.05); position: relative;
}
.task-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.1); border-color: var(--blue); }
.task-card:active { cursor: grabbing; }
.task-card.dragging { opacity: .5; transform: rotate(2deg); }

/* REVISI dari PM → Kuning */
.task-card.is-revisi {
    border-color: #EAB308 !important;
    background: linear-gradient(135deg, white 0%, #FEFCE8 100%);
    box-shadow: 0 0 0 1px rgba(234,179,8,.25), 0 2px 8px rgba(234,179,8,.15);
}
.task-card.is-revisi:hover {
    border-color: #CA8A04 !important;
    box-shadow: 0 4px 12px rgba(234,179,8,.25);
}

/* Deadline dekat (≤ 3 hari) → Orange */
.task-card.deadline-near {
    border-color: #EA580C;
    background: linear-gradient(135deg, white 0%, #FFF7ED 100%);
}
.task-card.deadline-near::before {
    content: '⏰';
    position: absolute; top: 10px; right: 10px; font-size: 14px;
}
.task-card.deadline-near:hover { border-color: #C2410C; }

/* Deadline sudah lewat → Merah */
.task-card.deadline-overdue {
    border-color: #EF4444;
    background: linear-gradient(135deg, white 0%, #FEF2F2 100%);
}
.task-card.deadline-overdue::before {
    content: '🔴';
    position: absolute; top: 10px; right: 10px; font-size: 12px;
}

/* Approved → Hijau muda, locked */
.task-card.is-approved {
    opacity: .75; cursor: not-allowed;
    border-color: #A7F3D0;
    background: linear-gradient(135deg, white 0%, #F0FDF4 100%);
}

/* Project label di dalam card */
.task-card-project {
    display: flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 700; color: var(--blue);
    background: var(--blue-light); border: 1px solid #C7D2FE;
    border-radius: 5px; padding: 2px 8px;
    margin-bottom: 6px; width: fit-content;
    max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.task-card-project i { font-size: 11px; flex-shrink: 0; }

.task-card-title { font-size: 14px; font-weight: 700; color: var(--g900); line-height: 1.4; margin-bottom: 6px; padding-right: 20px; }
.task-card-desc { font-size: 12px; color: var(--g500); line-height: 1.5; margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.task-card-meta { display: flex; flex-direction: column; gap: 6px; padding-top: 10px; border-top: 1px solid var(--g100); }
.task-card-deadline { display: flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 600; color: var(--g600); }
.task-card-deadline i { font-size: 13px; color: var(--g400); }

.level-badge { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 5px; font-size: 10px; font-weight: 700; color: white; text-transform: uppercase; }
.lv-mudah  { background: linear-gradient(135deg, var(--green), #059669); }
.lv-medium { background: linear-gradient(135deg, var(--amber), #D97706); }
.lv-susah  { background: linear-gradient(135deg, var(--red), #DC2626); }

.sa-chip { display: inline-flex; align-items: center; gap: 3px; padding: 2px 7px; border-radius: 5px; font-size: 9px; font-weight: 700; text-transform: uppercase; }
.sa-review   { background: var(--purple-light); color: #7C3AED; }
.sa-revisi   { background: var(--yellow-light); color: #A16207; border: 1px solid #FDE047; }
.sa-approved { background: var(--green-light); color: #059669; }

.task-card-actions { display: flex; gap: 6px; margin-top: 10px; }
.tca-btn {
    flex: 1; padding: 6px; border-radius: 6px; border: 1px solid var(--g300);
    background: white; font-size: 11px; font-weight: 600; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 5px;
    transition: all .18s;
}
.tca-btn.detail { border-color: var(--blue); color: var(--blue); }
.tca-btn.detail:hover { background: var(--blue); color: white; }
.tca-btn.upload { border-color: var(--green); color: #059669; }
.tca-btn.upload:hover { background: var(--green); color: white; }

/* Banner di dalam card */
.approved-lock {
    display: flex; align-items: center; gap: 5px;
    background: var(--green-light); border: 1px solid #A7F3D0;
    border-radius: 6px; padding: 4px 8px;
    font-size: 10px; font-weight: 700; color: #059669; margin-top: 6px;
}
.revisi-banner {
    display: flex; align-items: center; gap: 5px;
    background: #FEF9C3; border: 1px solid #FDE047;
    border-radius: 6px; padding: 4px 8px;
    font-size: 10px; font-weight: 700; color: #A16207; margin-top: 6px;
}
.review-banner {
    display: flex; align-items: center; gap: 5px;
    background: var(--purple-light); border: 1px solid #DDD6FE;
    border-radius: 6px; padding: 4px 8px;
    font-size: 10px; font-weight: 700; color: #7C3AED; margin-top: 6px;
}

/* ── Modal ── */
.modal-grad-header { background: linear-gradient(135deg, var(--blue) 0%, var(--purple) 100%); padding: 20px 24px 16px; border-radius: 12px 12px 0 0; }
.modal-grad-title { font-size: 17px; font-weight: 700; color: white; margin-bottom: 4px; }
.modal-grad-sub { font-size: 12px; color: rgba(255,255,255,.82); margin: 0; }
.upload-drop-zone {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 8px; padding: 24px; border: 2px dashed var(--g300); border-radius: 10px;
    cursor: pointer; text-align: center; transition: all .2s; background: var(--g50);
}
.upload-drop-zone:hover, .upload-drop-zone.dragover { border-color: var(--green); background: var(--green-light); }
.upload-drop-zone i { font-size: 32px; color: var(--green); }
.upload-drop-zone p { font-size: 13px; font-weight: 600; color: var(--g700); margin: 0; }
.upload-drop-zone small { font-size: 11px; color: var(--g400); }
.upload-preview-grid { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
.detail-field { margin-bottom: 18px; }
.detail-lbl { font-size: 11px; font-weight: 700; color: var(--g500); text-transform: uppercase; letter-spacing: .05em; margin-bottom: 5px; }
.detail-val { font-size: 14px; font-weight: 500; color: var(--g900); }
.gallery-row { display: flex; flex-wrap: wrap; gap: 8px; }
.gallery-img { width: 90px; height: 65px; object-fit: cover; border-radius: 6px; border: 1px solid var(--g200); cursor: pointer; transition: transform .2s; }
.gallery-img:hover { transform: scale(1.05); }
.gallery-doc { display: flex; flex-direction: column; align-items: center; gap: 3px; padding: 8px 10px; background: var(--g50); border: 1px solid var(--g200); border-radius: 6px; font-size: 10px; font-weight: 600; color: var(--blue); text-decoration: none; transition: background .15s; }
.gallery-doc:hover { background: var(--blue-light); }
.gallery-doc i { font-size: 20px; }

/* ── Toast ── */
#tk-toast { position: fixed; bottom: 24px; right: 24px; z-index: 99999; min-width: 280px; border-radius: 10px; box-shadow: 0 8px 32px rgba(0,0,0,.18); overflow: hidden; transform: translateY(120%) scale(.95); opacity: 0; transition: transform .3s cubic-bezier(.34,1.56,.64,1), opacity .25s ease; pointer-events: none; }
#tk-toast.show { transform: translateY(0) scale(1); opacity: 1; pointer-events: auto; }
#tk-toast.success { background: linear-gradient(135deg,#10b981,#059669); }
#tk-toast.error   { background: linear-gradient(135deg,#ef4444,#dc2626); }
#tk-toast.saving  { background: linear-gradient(135deg,#6366f1,#4f46e5); }
#tk-toast.info    { background: linear-gradient(135deg,#3b82f6,#2563eb); }
.toast-body { display: flex; align-items: center; gap: 10px; padding: 12px 14px; }
.toast-icon { font-size: 20px; color: white; flex-shrink: 0; }
.toast-text { flex: 1; min-width: 0; }
.toast-title { font-size: 13px; font-weight: 700; color: white; margin: 0 0 2px; }
.toast-msg { font-size: 12px; color: rgba(255,255,255,.88); margin: 0; }
.toast-close { background: rgba(255,255,255,.15); border: none; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 14px; flex-shrink: 0; }
.toast-close:hover { background: rgba(255,255,255,.25); }

/* ── Legend ── */
.card-legend { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 16px; background: white; border: 1px solid var(--g200); border-radius: 10px; padding: 10px 16px; }
.legend-item { display: flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 600; color: var(--g600); }
.legend-dot { width: 12px; height: 12px; border-radius: 3px; flex-shrink: 0; }

@media(max-width:1200px) { .dash-grid { grid-template-columns: 1fr; } }
@media(max-width:992px)  { .kanban-wrapper { grid-template-columns: 1fr; } .stats-bar { grid-template-columns: repeat(2,1fr); } }
@media(max-width:768px)  { .stats-bar { grid-template-columns: 1fr; } }
</style>
@endpush

@php
    $user = auth()->user();
    $today = \Carbon\Carbon::today();
    $threeDays = $today->copy()->addDays(3);

    $W = fn($t) => max(1, (int)($t->weight ?? 1));

    $myNonDraftTasks = $myTasks->filter(fn($t) => $t->status_progress !== 'draft');
    $todoTasks       = $myTasks->filter(fn($t) => $t->status_progress === 'To Do');
    $progTasks       = $myTasks->filter(fn($t) => $t->status_progress === 'In Progress');
    $doneTasks       = $myTasks->filter(fn($t) => $t->status_progress === 'done');

    // Timeline stats
    $earlyCount = 0; $ontimeCount = 0; $lateCount = 0;
    foreach ($myTasks->where('status_progress', '!=', 'draft') as $t) {
        $end = $t->tenggat_waktu ? \Carbon\Carbon::parse($t->tenggat_waktu) : null;
        if ($t->status_progress === 'done') {
            $sel = $t->tanggal_selesai ? \Carbon\Carbon::parse($t->tanggal_selesai) : $today;
            if ($end) {
                if ($sel->lt($end)) $earlyCount++;
                elseif ($sel->lte($end)) $ontimeCount++;
                else $lateCount++;
            } else { $earlyCount++; }
        } else {
            if ($end && $end->lt($today)) $lateCount++;
        }
    }

    // Progress ringkasan
    $myTotalWeight    = $myNonDraftTasks->sum($W);
    $myApprovedWeight = $myNonDraftTasks->filter(fn($t) => $t->status_progress === 'done' && $t->status_akhir === 'approved')->sum($W);
    $myPctProject     = $myTotalWeight > 0 ? round(($myApprovedWeight / $myTotalWeight) * 100) : 0;

    $myTodoTasks      = $myNonDraftTasks->filter(fn($t) => $t->status_progress === 'To Do');
    $myProgressTasks  = $myNonDraftTasks->filter(fn($t) => $t->status_progress === 'In Progress');
    $myDoneTasks      = $myNonDraftTasks->filter(fn($t) => $t->status_progress === 'done');
    $myTodoWeight     = $myTodoTasks->sum($W);
    $myProgressWeight = $myProgressTasks->sum($W);
    $myDoneWeight     = $myDoneTasks->sum($W);
    $myTodoCount      = $myTodoTasks->count();
    $myProgressCount  = $myProgressTasks->count();
    $myDoneCount      = $myDoneTasks->count();
    $myTotalTaskCount = $myNonDraftTasks->count();

    $myBelumTasks        = $myNonDraftTasks->filter(fn($t) => !$t->status_akhir);
    $myReviewTasks       = $myNonDraftTasks->filter(fn($t) => $t->status_akhir === 'review');
    $myRevisiTasks       = $myNonDraftTasks->filter(fn($t) => $t->status_akhir === 'revisi');
    $myApprovedTasks     = $myNonDraftTasks->filter(fn($t) => $t->status_akhir === 'approved');
    $myBelumWeight       = $myBelumTasks->sum($W);
    $myReviewWeight      = $myReviewTasks->sum($W);
    $myRevisiWeight      = $myRevisiTasks->sum($W);
    $myApprovedWeightOnly = $myApprovedTasks->sum($W);
    $myBelumCount        = $myBelumTasks->count();
    $myReviewCount       = $myReviewTasks->count();
    $myRevisiCount       = $myRevisiTasks->count();
    $myApprovedCount     = $myApprovedTasks->count();

    // JS task data — sertakan nama_projek
    $tasksJs = $myTasks->map(function($t) {
        $hasilFoto = $t->foto ? $t->foto->filter(fn($f) => $f->tipe === 'hasil') : collect();
        return [
            'id_tugas'        => $t->id_tugas,
            'id_projek'       => $t->id_projek,
            'nama_projek'     => $t->nama_projek ?? '—',   // ← tambahan
            'judul_tugas'     => $t->judul_tugas,
            'deskripsi_tugas' => $t->deskripsi_tugas,
            'status_progress' => $t->status_progress,
            'status_akhir'    => $t->status_akhir,
            'level'           => $t->level ?? 'mudah',
            'weight'          => $t->weight ?? 1,
            'tanggal_mulai'   => $t->tanggal_mulai   ? \Carbon\Carbon::parse($t->tanggal_mulai)->format('Y-m-d')   : null,
            'tenggat_waktu'   => $t->tenggat_waktu   ? \Carbon\Carbon::parse($t->tenggat_waktu)->format('Y-m-d')   : null,
            'tanggal_selesai' => $t->tanggal_selesai ? \Carbon\Carbon::parse($t->tanggal_selesai)->format('Y-m-d') : null,
            'has_hasil'       => $hasilFoto->count() > 0,
            'foto'            => $t->foto ? $t->foto->map(fn($f) => [
                'id_tugas_foto' => $f->id_tugas_foto,
                'url'           => \Illuminate\Support\Facades\Storage::url($f->nama_file),
                'nama_file'     => $f->nama_file,
                'tipe'          => $f->tipe,
            ])->values()->toArray() : [],
        ];
    })->values();
@endphp

@section('content')

{{-- Project Selector — hanya menampilkan project yang karyawan ini benar-benar ikut --}}
<div class="project-selector-wrap">
    <span class="selector-label">
        <i class="bx bx-folder-open" style="margin-right:4px;font-size:16px;vertical-align:middle;color:var(--blue);"></i>Project Aktif
    </span>
    <form method="GET" action="{{ route('dashboard.taskkaryawan') }}" id="projSelectForm"
          style="flex:1;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">

        <select name="id_projek" class="project-select-box"
                onchange="document.getElementById('projSelectForm').submit()">
            <option value="all" {{ $showAll ? 'selected' : '' }}>
                Semua Project Saya ({{ $myProjeks->count() }} project)
            </option>
            @foreach($myProjeks as $p)
            <option value="{{ $p->id_projek }}"
                    {{ !$showAll && $selectedProjekId == $p->id_projek ? 'selected' : '' }}>
                {{ $p->nama_projek }}
            </option>
            @endforeach
        </select>

        @if(!$showAll && $currentProjek)
        <span class="selector-current-badge">
            <i class="bx bx-check-circle"></i>
            {{ $currentProjek->nama_projek }}
        </span>
        @endif
    </form>

    @if($myProjeks->isEmpty())
    <span style="font-size:13px;color:var(--g400);font-style:italic;">
        Anda belum tergabung di project manapun.
    </span>
    @endif
</div>

{{-- Project Info Card --}}
@if($currentProjek)
<div class="proj-info-card">
    <div style="position:relative;z-index:1;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div>
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#FFFFFF;margin-bottom:6px;">
                    <i class="bx bx-folder-open" style="margin-right:4px;"></i>Project Saat Ini
                </div>
                <div class="proj-info-title">{{ $currentProjek->nama_projek }}</div>
                @if($currentProjek->deskripsi)
                <div style="font-size:13px;color:rgba(255,255,255,.75);margin-top:4px;max-width:600px;line-height:1.5;">
                    {{ Str::limit($currentProjek->deskripsi, 120) }}
                </div>
                @endif
                <div class="proj-info-meta">
                    @if($currentProjek->pembuat)
                    <span class="proj-info-badge pm">
                        <i class="bx bx-user-check"></i>PM: {{ $currentProjek->pembuat->nama }}
                    </span>
                    @endif
                    @if($currentProjek->tanggal_mulai)
                    <span class="proj-info-item">
                        <i class="bx bx-calendar-plus" style="color:white;"></i>
                        {{ \Carbon\Carbon::parse($currentProjek->tanggal_mulai)->format('d M Y') }}
                    </span>
                    @endif
                    @if($currentProjek->tanggal_selesai)
                    <span class="proj-info-item">
                        <i class="bx bx-calendar-check" style="color:white;"></i>
                        → {{ \Carbon\Carbon::parse($currentProjek->tanggal_selesai)->format('d M Y') }}
                    </span>
                    @endif
                    @if(optional($currentProjek->kategoriProjek)->nama_kategori)
                    <span class="proj-info-badge">
                        <i class="bx bx-purchase-tag-alt"></i>{{ $currentProjek->kategoriProjek->nama_kategori }}
                    </span>
                    @endif
                </div>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <div style="font-size:11px;color:rgba(255,255,255,.6);margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">Status Project</div>
                @php
                    $stClr = match($currentProjek->status) { 'aktif'=>'#22C55E','in_progress'=>'#F59E0B','selesai'=>'#60A5FA',default=>'#9CA3AF' };
                    $stLbl = match($currentProjek->status) { 'aktif'=>'Aktif','in_progress'=>'In Progress','selesai'=>'Selesai',default=>'Pending' };
                @endphp
                <span style="font-size:15px;font-weight:800;color:{{ $stClr }};">● {{ $stLbl }}</span>
            </div>
        </div>
    </div>
</div>
@else
<div style="background:white;border:1px solid var(--g200);border-radius:12px;padding:16px 24px;margin-bottom:20px;display:flex;align-items:center;gap:12px;">
    <div style="width:42px;height:42px;border-radius:10px;background:linear-gradient(135deg,var(--blue),var(--purple));color:white;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">
        <i class="bx bx-grid-alt"></i>
    </div>
    <div>
        <div style="font-size:16px;font-weight:700;color:var(--g900);">Semua Task Saya</div>
        <div style="font-size:13px;color:var(--g500);">Menampilkan task dari {{ $myProjeks->count() }} project yang Anda ikuti</div>
    </div>
</div>
@endif

{{-- Stats Bar --}}
<div class="stats-bar">
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="bx bx-task"></i></div>
        <div>
            <div class="stat-lbl">Total Task Saya</div>
            <div class="stat-val">{{ $myNonDraftTasks->count() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green"><i class="bx bx-trending-up"></i></div>
        <div>
            <div class="stat-lbl">Sebelum Deadline</div>
            <div class="stat-val">{{ $earlyCount }}</div>
            <div class="stat-sub">Task selesai lebih cepat</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-amber"><i class="bx bx-check-double"></i></div>
        <div>
            <div class="stat-lbl">Tepat Waktu</div>
            <div class="stat-val">{{ $ontimeCount }}</div>
            <div class="stat-sub">Task selesai on time</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-red"><i class="bx bx-error-circle"></i></div>
        <div>
            <div class="stat-lbl">Terlambat</div>
            <div class="stat-val">{{ $lateCount }}</div>
            <div class="stat-sub">Task melewati deadline</div>
        </div>
    </div>
</div>

{{-- Dashboard Grid --}}
<div class="dash-grid">
    {{-- Performance Anggota Tim --}}
    <div class="dash-card">
        <div class="dash-card-title">Performance Anggota Tim</div>
        <div class="dash-card-sub">Distribusi penyelesaian task & persentase per anggota</div>
        @if(!$showAll && isset($timPerformance) && count($timPerformance) > 0)
            @foreach($timPerformance as $member)
            @php
                $pct = $member['total'] > 0 ? round($member['done'] / $member['total'] * 100) : 0;
                $pctColor = $pct >= 80 ? '#059669' : ($pct >= 50 ? '#D97706' : '#DC2626');
                $pctBg    = $pct >= 80 ? 'linear-gradient(90deg,#10B981,#059669)' : ($pct >= 50 ? 'linear-gradient(90deg,#F59E0B,#D97706)' : 'linear-gradient(90deg,#EF4444,#DC2626)');
            @endphp
            <div class="perf-item" style="{{ $member['is_me'] ? 'background:var(--blue-light);border:1px solid #C7D2FE;border-radius:8px;padding:10px;' : '' }}">
                <div class="perf-top">
                    <div style="display:flex;align-items:center;flex-wrap:wrap;gap:4px;">
                        <span class="perf-name">{{ $member['nama'] }}</span>
                        @if($member['is_me'])<span class="perf-me-tag">Saya</span>@endif
                        @if($member['jabatan'])<span class="perf-jabatan">{{ $member['jabatan'] }}</span>@endif
                    </div>
                    <div class="perf-stat">
                        <span class="perf-fraction">{{ $member['done'] }}/{{ $member['total'] }}</span>
                        <span class="perf-pct" style="color:{{ $pctColor }};">{{ $pct }}%</span>
                    </div>
                </div>
                <div class="perf-track"><div class="perf-fill" style="width:{{ $pct }}%;background:{{ $pctBg }};"></div></div>
                <div class="perf-detail">
                    @if($member['todo'] > 0)<span class="perf-dot-item"><span class="perf-dot" style="background:#6B7280;"></span>To Do: {{ $member['todo'] }}</span>@endif
                    @if($member['in_progress'] > 0)<span class="perf-dot-item"><span class="perf-dot" style="background:#F59E0B;"></span>Progress: {{ $member['in_progress'] }}</span>@endif
                    @if($member['done'] > 0)<span class="perf-dot-item"><span class="perf-dot" style="background:#10B981;"></span>Done: {{ $member['done'] }}</span>@endif
                </div>
            </div>
            @endforeach
        @elseif($showAll)
            <div style="text-align:center;padding:24px;color:var(--g400);">
                <i class="bx bx-grid-alt" style="font-size:32px;display:block;margin-bottom:8px;"></i>
                <div style="font-size:13px;font-weight:600;">Pilih project spesifik untuk melihat performa tim</div>
            </div>
        @else
            <div style="text-align:center;padding:24px;color:var(--g400);">
                <i class="bx bx-group" style="font-size:32px;display:block;margin-bottom:8px;"></i>
                <div style="font-size:13px;">Belum ada anggota tim</div>
            </div>
        @endif
    </div>

    {{-- Progress Summary --}}
    <div class="dash-card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
            <div>
                <div class="dash-card-title">Ringkasan Progress Task</div>
                <div style="font-size:12px;color:var(--g500);">Done & Approved PM ÷ Total Non-Draft (weight)</div>
            </div>
            <div style="text-align:right;">
                <div class="big-pct">{{ $myPctProject }}%</div>
                <div style="font-size:11px;color:var(--g500);font-weight:600;">Penyelesaian Proyek</div>
                <div style="font-size:11px;font-weight:700;color:var(--g600);">{{ $myApprovedWeight }} / {{ $myTotalWeight }} weight</div>
            </div>
        </div>
        <div class="progress-track">
            <div class="progress-fill" style="width:{{ $myPctProject }}%;"></div>
        </div>
        @php $totN = $myTotalTaskCount; $totW = max(1, $myTotalWeight); @endphp
        <div class="status-2col">
            <div>
                <div class="status-col-title">Status Progress (Non-Draft)</div>
                @foreach([
                    ['To Do', '#6B7280', $myTodoCount, $myTodoWeight],
                    ['In Progress', '#F59E0B', $myProgressCount, $myProgressWeight],
                    ['Done', '#10B981', $myDoneCount, $myDoneWeight],
                ] as [$lbl, $clr, $n, $w])
                <div class="bar-item">
                    <div class="bar-top">
                        <div class="bar-label"><span class="bar-dot" style="background:{{ $clr }};"></span>{{ $lbl }}</div>
                        <div class="bar-stats">
                            <span class="bar-n">{{ $n }} task • {{ $totN > 0 ? round($n/$totN*100) : 0 }}%</span>
                            <span class="bar-w" style="color:{{ $clr }};">W: {{ $w }} ({{ $totW > 0 ? round($w/$totW*100) : 0 }}%)</span>
                        </div>
                    </div>
                    <div class="bar-track-sm">
                        <div class="bar-fill-sm" style="width:{{ $totW > 0 ? round($w/$totW*100) : 0 }}%;background:{{ $clr }};"></div>
                    </div>
                </div>
                @endforeach
            </div>
            <div>
                <div class="status-col-title right">Status Penilaian PM</div>
                @foreach([
                    ['Belum Dinilai', '#9CA3AF', $myBelumCount, $myBelumWeight],
                    ['Review', '#8B5CF6', $myReviewCount, $myReviewWeight],
                    ['Revisi', '#EAB308', $myRevisiCount, $myRevisiWeight],
                    ['Approved', '#10B981', $myApprovedCount, $myApprovedWeightOnly],
                ] as [$lbl, $clr, $n, $w])
                <div class="bar-item">
                    <div class="bar-top">
                        <div class="bar-label"><span class="bar-dot" style="background:{{ $clr }};"></span>{{ $lbl }}</div>
                        <div class="bar-stats">
                            <span class="bar-n">{{ $n }} task • {{ $totN > 0 ? round($n/$totN*100) : 0 }}%</span>
                            <span class="bar-w" style="color:{{ $clr }};">W: {{ $w }} ({{ $totW > 0 ? round($w/$totW*100) : 0 }}%)</span>
                        </div>
                    </div>
                    <div class="bar-track-sm">
                        <div class="bar-fill-sm" style="width:{{ $totW > 0 ? round($w/$totW*100) : 0 }}%;background:{{ $clr }};"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Legend warna kartu --}}
<div class="card-legend">
    <span style="font-size:11px;font-weight:800;color:var(--g500);text-transform:uppercase;margin-right:6px;">Keterangan Warna Card:</span>
    <span class="legend-item"><span class="legend-dot" style="background:#EAB308;"></span>Revisi PM</span>
    <span class="legend-item"><span class="legend-dot" style="background:#EA580C;"></span>Deadline &le; 3 Hari</span>
    <span class="legend-item"><span class="legend-dot" style="background:#EF4444;"></span>Lewat Deadline</span>
    <span class="legend-item"><span class="legend-dot" style="background:#A7F3D0;"></span>Approved (Terkunci)</span>
    <span class="legend-item"><span class="legend-dot" style="background:var(--g300);"></span>Normal</span>
</div>

{{-- Kanban Board --}}
<div class="kanban-wrapper">
    {{-- To Do --}}
    <div class="kanban-col col-todo">
        <div class="kanban-head">
            <div class="kanban-head-title"><i class="bx bx-time-five" style="color:#6B7280;"></i>To Do</div>
            <span class="kanban-count" id="cnt-todo">0 Task</span>
        </div>
        <div class="kanban-body" id="col-todo"
             ondrop="drop(event,'To Do')" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
        </div>
    </div>
    {{-- In Progress --}}
    <div class="kanban-col col-progress">
        <div class="kanban-head">
            <div class="kanban-head-title"><i class="bx bx-loader-circle" style="color:#D97706;"></i>In Progress</div>
            <span class="kanban-count" id="cnt-progress">0 Task</span>
        </div>
        <div class="kanban-body" id="col-progress"
             ondrop="drop(event,'In Progress')" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
        </div>
    </div>
    {{-- Done --}}
    <div class="kanban-col col-done">
        <div class="kanban-head">
            <div class="kanban-head-title"><i class="bx bx-check-circle" style="color:#059669;"></i>Done</div>
            <span class="kanban-count" id="cnt-done">0 Task</span>
        </div>
        <div class="kanban-body" id="col-done"
             ondrop="drop(event,'done')" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
        </div>
    </div>
</div>

{{-- ══ MODAL: Task Detail ══ --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-grad-header">
                <div class="modal-grad-title" id="dtl-title">—</div>
                <p class="modal-grad-sub" id="dtl-sub"></p>
                <button type="button" class="btn-close btn-close-white position-absolute" style="top:14px;right:14px;" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:22px 24px;">
                <div class="row">
                    <div class="col-md-8">
                        <div class="detail-field">
                            <div class="detail-lbl">Project</div>
                            <div class="detail-val" id="dtl-project" style="color:var(--blue);font-weight:700;"></div>
                        </div>
                        <div class="detail-field">
                            <div class="detail-lbl">Deskripsi</div>
                            <div class="detail-val" id="dtl-desc" style="font-size:14px;color:var(--g600);line-height:1.6;"></div>
                        </div>
                        <div class="row">
                            <div class="col-6 detail-field">
                                <div class="detail-lbl">Status Progress</div>
                                <div id="dtl-status"></div>
                            </div>
                            <div class="col-6 detail-field">
                                <div class="detail-lbl">Level & Weight</div>
                                <div id="dtl-level"></div>
                            </div>
                        </div>
                        <div class="detail-field">
                            <div class="detail-lbl">Penilaian PM</div>
                            <div id="dtl-sa"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-field">
                            <div class="detail-lbl">Tanggal Mulai</div>
                            <div class="detail-val fw-bold" id="dtl-start"></div>
                        </div>
                        <div class="detail-field">
                            <div class="detail-lbl">Deadline</div>
                            <div class="detail-val fw-bold" id="dtl-end"></div>
                        </div>
                        <div class="detail-field">
                            <div class="detail-lbl">Tgl. Selesai</div>
                            <div class="detail-val" id="dtl-done"></div>
                        </div>
                    </div>
                </div>
                <div style="border:1px solid var(--g200);border-radius:8px;overflow:hidden;margin-bottom:12px;">
                    <div style="background:var(--blue-light);color:var(--blue);padding:8px 14px;font-size:12px;font-weight:700;">📎 Foto Brief</div>
                    <div style="padding:12px;"><div class="gallery-row" id="dtl-brief"><span style="font-size:12px;color:var(--g400);font-style:italic;">Belum ada foto brief.</span></div></div>
                </div>
                <div style="border:1px solid var(--g200);border-radius:8px;overflow:hidden;">
                    <div style="background:var(--green-light);color:#059669;padding:8px 14px;font-size:12px;font-weight:700;">📋 Laporan Hasil</div>
                    <div style="padding:12px;"><div class="gallery-row" id="dtl-hasil"><span style="font-size:12px;color:var(--g400);font-style:italic;">Belum ada laporan hasil.</span></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ MODAL: Upload Lampiran untuk Done ══ --}}
<div class="modal fade" id="modalUploadDone" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-grad-header" style="background:linear-gradient(135deg,#059669,#10B981);">
                <div class="modal-grad-title">📋 Lampiran Hasil Wajib</div>
                <p class="modal-grad-sub">Upload bukti pengerjaan sebelum task dipindah ke Done</p>
                <button type="button" class="btn-close btn-close-white position-absolute" style="top:14px;right:14px;" data-bs-dismiss="modal" onclick="cancelDone()"></button>
            </div>
            <div class="modal-body" style="padding:22px 24px;">
                <div style="background:var(--amber-light);border:1px solid #FDE68A;border-radius:8px;padding:12px 14px;margin-bottom:16px;display:flex;align-items:flex-start;gap:10px;">
                    <i class="bx bx-info-circle" style="font-size:18px;color:#D97706;flex-shrink:0;margin-top:2px;"></i>
                    <div style="font-size:13px;color:#92400E;line-height:1.5;">
                        Task <strong id="upload-done-task-name">—</strong> perlu lampiran hasil pengerjaan sebelum bisa dipindahkan ke <strong>Done</strong>. Upload minimal 1 file di bawah.
                    </div>
                </div>
                <input type="hidden" id="upload-done-task-id">
                <input type="hidden" id="upload-done-projek-id">
                <label class="upload-drop-zone" id="uploadDoneZone"
                    ondragover="event.preventDefault();this.classList.add('dragover')"
                    ondragleave="this.classList.remove('dragover')"
                    ondrop="handleUploadDoneDrop(event)">
                    <i class="bx bx-cloud-upload"></i>
                    <p>Seret file atau klik untuk pilih</p>
                    <small>JPG, PNG, PDF, DOC, DOCX — Maks 10MB</small>
                    <input type="file" id="inputUploadDone" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx" style="display:none;" onchange="handleUploadDoneChange(this)">
                </label>
                <div class="upload-preview-grid" id="uploadDonePreview"></div>
            </div>
            <div class="modal-footer border-0 justify-content-end gap-2" style="padding-bottom:20px;">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" onclick="cancelDone()">Batal</button>
                <button type="button" class="btn btn-success fw-bold" id="btnConfirmDone" onclick="confirmDone()" disabled>
                    <i class="bx bx-check-circle me-1"></i> Upload & Pindahkan ke Done
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="tk-toast">
    <div class="toast-body">
        <i class="toast-icon bx" id="tk-toast-icon"></i>
        <div class="toast-text">
            <p class="toast-title" id="tk-toast-title"></p>
            <p class="toast-msg" id="tk-toast-msg"></p>
        </div>
        <button class="toast-close" onclick="closeToast()"><i class="bx bx-x"></i></button>
    </div>
</div>
@endsection

@push('scripts')
<script>
'use strict';
const CSRF_TOKEN = '{{ csrf_token() }}';
const TODAY_STR  = '{{ $today->format("Y-m-d") }}';

// Tasks data dari server (sudah termasuk nama_projek)
let tasks = @json($tasksJs);

let draggedId = null;
let pendingDoneTaskId = null;
let uploadDoneFiles = [];

/* ─── HELPERS ─── */
function fmtDate(s) {
    if (!s) return '—';
    const d = new Date(s + 'T00:00:00');
    if (isNaN(d.getTime())) return '—';
    const mn = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
    return `${d.getDate()} ${mn[d.getMonth()]} ${d.getFullYear()}`;
}
function escHtml(s) {
    if (!s) return '';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
function isImgFile(n) { return /\.(jpg|jpeg|png|gif|webp|svg)$/i.test(n || ''); }
function docIcon(n) {
    if (/\.pdf$/i.test(n)) return 'bx-file-pdf';
    if (/\.docx?$/i.test(n)) return 'bx-file-doc';
    if (/\.xlsx?$/i.test(n)) return 'bxs-spreadsheet';
    return 'bx-file-blank';
}
function getTask(id) { return tasks.find(t => t.id_tugas === id); }

/* ─── TOAST ─── */
const TOAST_ICONS = { success:'bx-check-circle', error:'bx-error-circle', saving:'bx-loader-alt bx-spin', info:'bx-info-circle' };
let _toastTimer = null;
function showToast(msg, type='success', title=null, dur=3000) {
    const el = document.getElementById('tk-toast');
    document.getElementById('tk-toast-icon').className = 'toast-icon bx ' + (TOAST_ICONS[type] || TOAST_ICONS.success);
    document.getElementById('tk-toast-title').textContent = title || {success:'Berhasil',error:'Gagal',saving:'Menyimpan…',info:'Info'}[type] || 'Notifikasi';
    document.getElementById('tk-toast-msg').textContent = msg;
    el.className = type;
    el.classList.add('show');
    clearTimeout(_toastTimer);
    if (type !== 'saving') _toastTimer = setTimeout(closeToast, dur);
}
function closeToast() {
    const el = document.getElementById('tk-toast');
    if (el) el.classList.remove('show');
    clearTimeout(_toastTimer);
}

/* ─── API ─── */
async function apiFetch(url, method = 'GET', body = null) {
    const opts = { method, headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' } };
    if (body) { opts.headers['Content-Type'] = 'application/json'; opts.body = JSON.stringify(body); }
    const r = await fetch(url, opts);
    return r.json();
}
async function apiUpload(url, fd) {
    const r = await fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }, body: fd });
    return r.json();
}

/* ─── DEADLINE & REVISI CLASS ─── */
/**
 * Menentukan class kartu berdasarkan kondisi:
 * - is-revisi      → kuning  (revisi dari PM, prioritas di atas deadline)
 * - deadline-overdue → merah (sudah lewat deadline)
 * - deadline-near  → orange  (≤ 3 hari sebelum deadline)
 */
function cardClasses(task) {
    const isApproved = task.status_progress === 'done' && task.status_akhir === 'approved';
    const isRevisi   = task.status_akhir === 'revisi';

    if (isApproved) return 'is-approved';
    if (isRevisi)   return 'is-revisi';   // kuning — revisi PM

    if (task.status_progress === 'done') return '';

    if (!task.tenggat_waktu) return '';

    const end   = new Date(task.tenggat_waktu + 'T00:00:00');
    const today = new Date(TODAY_STR + 'T00:00:00');
    const diff  = Math.ceil((end - today) / 86400000);

    if (diff < 0)   return 'deadline-overdue';  // merah
    if (diff <= 3)  return 'deadline-near';      // orange
    return '';
}

/* ─── RENDER TASK CARD ─── */
const STATUS_LABELS = { 'draft':'Draft', 'To Do':'To Do', 'In Progress':'In Progress', 'done':'Done' };
const SA_LABELS     = { 'review':'Review PM', 'revisi':'Revisi PM', 'approved':'Approved PM' };
const LV_WEIGHT     = { mudah: 1, medium: 2, susah: 3 };

function renderCard(task) {
    const isApproved  = task.status_progress === 'done' && task.status_akhir === 'approved';
    const isRevisi    = task.status_akhir === 'revisi';
    const isReview    = task.status_akhir === 'review';
    const extraClass  = cardClasses(task);
    const lv          = task.level || 'mudah';
    const hasilFotos  = (task.foto || []).filter(f => f.tipe === 'hasil');
    const hasHasil    = hasilFotos.length > 0;

    // Banner di dalam card
    let banner = '';
    if (isApproved) {
        banner = `<div class="approved-lock"><i class="bx bx-lock-alt"></i>Terkunci — Approved PM</div>`;
    } else if (isRevisi) {
        banner = `<div class="revisi-banner"><i class="bx bx-pencil"></i>Perlu Revisi — silakan perbaiki & upload ulang</div>`;
    } else if (isReview) {
        banner = `<div class="review-banner"><i class="bx bx-hourglass"></i>Sedang ditinjau PM</div>`;
    }

    // Badge status akhir
    let saHtml = '';
    if (task.status_akhir) {
        saHtml = `<span class="sa-chip sa-${task.status_akhir}">${SA_LABELS[task.status_akhir] || task.status_akhir}</span>`;
    }

    // Deadline text
    let deadlineTxt = '';
    if (task.tenggat_waktu) {
        let col = 'var(--g600)';
        if (extraClass === 'deadline-overdue') col = '#EF4444';
        else if (extraClass === 'deadline-near') col = '#EA580C';
        else if (isRevisi) col = '#A16207';
        deadlineTxt = `<div class="task-card-deadline" style="color:${col}">
            <i class="bx bx-calendar-x" style="color:${col};"></i>${fmtDate(task.tenggat_waktu)}
        </div>`;
    }

    // Nama project
    const projHtml = task.nama_projek && task.nama_projek !== '—'
        ? `<div class="task-card-project"><i class="bx bx-folder"></i>${escHtml(task.nama_projek)}</div>`
        : '';

    const uploadBtn = !isApproved && task.status_progress === 'done'
        ? `<button class="tca-btn upload" onclick="openMediaUpload(${task.id_tugas})"><i class="bx bx-upload"></i>Lampiran</button>`
        : '';

    return `<div class="task-card ${extraClass}"
        data-id="${task.id_tugas}"
        draggable="${isApproved ? 'false' : 'true'}"
        ondragstart="dragStart(event,${task.id_tugas})"
        ondragend="dragEnd(event)">
        ${projHtml}
        <div class="task-card-title">${escHtml(task.judul_tugas || 'Task')}</div>
        ${task.deskripsi_tugas ? `<div class="task-card-desc">${escHtml(task.deskripsi_tugas)}</div>` : ''}
        <div class="task-card-meta">
            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                <span class="level-badge lv-${lv}">${lv}</span>
                <span style="background:var(--g100);color:var(--g600);font-size:10px;font-weight:700;padding:2px 7px;border-radius:5px;">W:${task.weight || LV_WEIGHT[lv] || 1}</span>
                ${hasHasil ? `<span style="background:var(--green-light);color:#059669;font-size:10px;font-weight:700;padding:2px 7px;border-radius:5px;"><i class="bx bx-paperclip" style="font-size:11px;"></i>${hasilFotos.length}</span>` : ''}
                ${saHtml}
            </div>
            ${deadlineTxt}
        </div>
        ${banner}
        <div class="task-card-actions">
            <button class="tca-btn detail" onclick="openDetail(${task.id_tugas})"><i class="bx bx-info-circle"></i>Detail</button>
            ${uploadBtn}
        </div>
    </div>`;
}

/* ─── RENDER ALL COLUMNS ─── */
function renderKanban() {
    const cols = {
        'To Do':       document.getElementById('col-todo'),
        'In Progress': document.getElementById('col-progress'),
        'done':        document.getElementById('col-done'),
    };
    const counts = { 'To Do': 0, 'In Progress': 0, 'done': 0 };
    Object.values(cols).forEach(c => { c.innerHTML = ''; });

    tasks.filter(t => t.status_progress !== 'draft').forEach(task => {
        const sp = task.status_progress;
        if (cols[sp]) {
            cols[sp].innerHTML += renderCard(task);
            counts[sp]++;
        }
    });

    // Empty states
    Object.entries(cols).forEach(([sp, el]) => {
        if (counts[sp] === 0) {
            const icons = { 'To Do':'bx-time-five', 'In Progress':'bx-loader-circle', 'done':'bx-check-circle' };
            const msgs  = { 'To Do':'Tidak ada task', 'In Progress':'Tidak ada task sedang dikerjakan', 'done':'Belum ada task selesai' };
            el.innerHTML = `<div class="kanban-empty"><i class="bx ${icons[sp]}"></i><p>${msgs[sp]}</p></div>`;
        }
    });

    document.getElementById('cnt-todo').textContent     = counts['To Do'] + ' Task';
    document.getElementById('cnt-progress').textContent = counts['In Progress'] + ' Task';
    document.getElementById('cnt-done').textContent     = counts['done'] + ' Task';
}

/* ─── DRAG & DROP ─── */
function dragStart(e, id) {
    draggedId = id;
    setTimeout(() => {
        const el = document.querySelector(`[data-id="${id}"]`);
        if (el) el.classList.add('dragging');
    }, 0);
    e.dataTransfer.effectAllowed = 'move';
}
function dragEnd(e) { e.target.classList.remove('dragging'); draggedId = null; }
function allowDrop(e) { e.preventDefault(); e.currentTarget.classList.add('drag-over'); }
function dragLeave(e) { e.currentTarget.classList.remove('drag-over'); }
function drop(e, newStatus) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');
    if (!draggedId) return;
    moveTask(draggedId, newStatus);
    draggedId = null;
}

/* ─── MOVE TASK ─── */
async function moveTask(id, newStatus) {
    const task = getTask(id);
    if (!task) return;
    const curStatus = task.status_progress;
    if (curStatus === newStatus) return;

    if (task.status_akhir === 'approved' && curStatus === 'done') {
        showToast('Task yang sudah Approved tidak bisa dipindahkan.', 'error', 'Terkunci');
        return;
    }

    // Moving to Done: cek apakah sudah ada lampiran hasil
    if (newStatus === 'done') {
        const hasilFotos = (task.foto || []).filter(f => f.tipe === 'hasil');
        if (hasilFotos.length === 0) {
            pendingDoneTaskId = id;
            document.getElementById('upload-done-task-name').textContent = task.judul_tugas || 'Task';
            document.getElementById('upload-done-task-id').value   = id;
            document.getElementById('upload-done-projek-id').value = task.id_projek;
            uploadDoneFiles = [];
            document.getElementById('uploadDonePreview').innerHTML = '';
            document.getElementById('btnConfirmDone').disabled = true;
            document.querySelector('#modalUploadDone .modal-grad-title').textContent = '📋 Lampiran Hasil Wajib';
            document.querySelector('#modalUploadDone .modal-grad-sub').textContent   = 'Upload bukti pengerjaan sebelum task dipindah ke Done';
            new bootstrap.Modal(document.getElementById('modalUploadDone')).show();
            return;
        }
    }

    await doMoveTask(id, newStatus);
}

async function doMoveTask(id, newStatus) {
    const task = getTask(id);
    if (!task) return;

    showToast('Memperbarui status task…', 'saving', 'Menyimpan', 0);

    const body = { status_progress: newStatus };
    if (newStatus === 'done') {
        body.status_akhir    = 'review';
        body.tanggal_selesai = TODAY_STR;
    } else if (task.status_progress === 'done') {
        body.status_akhir = null;
    }

    try {
        const url = `/projek/${task.id_projek}/task/${id}`;
        const d = await apiFetch(url, 'PUT', body);
        if (d.success) {
            task.status_progress = newStatus;
            if (body.status_akhir !== undefined) task.status_akhir = body.status_akhir;
            if (newStatus === 'done') task.tanggal_selesai = TODAY_STR;
            else task.tanggal_selesai = null;
            renderKanban();
            showToast(`Task dipindahkan ke ${newStatus === 'done' ? 'Done (Review PM)' : newStatus}`, 'success', 'Status Diperbarui');
        } else {
            showToast(d.message || 'Gagal memperbarui status.', 'error', 'Gagal');
        }
    } catch (e) {
        showToast('Koneksi bermasalah.', 'error', 'Error');
    }
}

/* ─── UPLOAD FOR DONE MODAL ─── */
function handleUploadDoneChange(input) {
    Array.from(input.files).forEach(f => addUploadFile(f));
    input.value = '';
}
function handleUploadDoneDrop(e) {
    e.preventDefault();
    document.getElementById('uploadDoneZone').classList.remove('dragover');
    Array.from(e.dataTransfer.files).forEach(f => addUploadFile(f));
}
function addUploadFile(f) {
    uploadDoneFiles.push(f);
    renderUploadPreviews();
    document.getElementById('btnConfirmDone').disabled = uploadDoneFiles.length === 0;
}
function renderUploadPreviews() {
    const container = document.getElementById('uploadDonePreview');
    container.innerHTML = uploadDoneFiles.map((f, i) => {
        if (f.type.startsWith('image/')) {
            const url = URL.createObjectURL(f);
            return `<div style="position:relative;width:80px;height:60px;border-radius:6px;overflow:hidden;border:1px solid var(--g200);">
                <img src="${url}" style="width:100%;height:100%;object-fit:cover;">
                <button onclick="removeUploadFile(${i})" style="position:absolute;top:-5px;right:-5px;width:16px;height:16px;border-radius:50%;background:var(--red);border:none;color:white;font-size:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;opacity:1;z-index:5;">×</button>
            </div>`;
        }
        return `<div style="position:relative;display:flex;flex-direction:column;align-items:center;gap:3px;padding:8px;background:var(--g50);border:1px solid var(--g200);border-radius:6px;font-size:10px;font-weight:600;color:var(--g600);min-width:70px;">
            <i class="bx bx-file-blank" style="font-size:20px;color:var(--blue);"></i>
            <span style="max-width:65px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${escHtml(f.name.substring(0,14))}</span>
            <button onclick="removeUploadFile(${i})" style="position:absolute;top:-5px;right:-5px;width:16px;height:16px;border-radius:50%;background:var(--red);border:none;color:white;font-size:10px;cursor:pointer;display:flex;align-items:center;justify-content:center;">×</button>
        </div>`;
    }).join('');
    document.getElementById('btnConfirmDone').disabled = uploadDoneFiles.length === 0;
}
function removeUploadFile(idx) {
    uploadDoneFiles.splice(idx, 1);
    renderUploadPreviews();
    document.getElementById('btnConfirmDone').disabled = uploadDoneFiles.length === 0;
}
function cancelDone() { pendingDoneTaskId = null; uploadDoneFiles = []; }

async function confirmDone() {
    if (!pendingDoneTaskId || uploadDoneFiles.length === 0) return;
    const id   = pendingDoneTaskId;
    const task = getTask(id);
    if (!task) return;

    const btn = document.getElementById('btnConfirmDone');
    btn.disabled = true;
    btn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Mengupload…';
    showToast('Mengupload lampiran…', 'saving', 'Upload', 0);

    try {
        const fd = new FormData();
        uploadDoneFiles.forEach(f => fd.append('foto[]', f));
        fd.append('tipe', 'hasil');
        const upData = await apiUpload(`/projek/${task.id_projek}/task/${id}/foto`, fd);

        if (!upData.success) {
            showToast(upData.message || 'Upload gagal.', 'error', 'Gagal Upload');
            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-check-circle me-1"></i>Upload & Pindahkan ke Done';
            return;
        }

        if (!task.foto) task.foto = [];
        task.foto.push(...upData.data);
        task.has_hasil = true;

        bootstrap.Modal.getInstance(document.getElementById('modalUploadDone'))?.hide();
        pendingDoneTaskId = null;
        uploadDoneFiles   = [];
        await doMoveTask(id, 'done');
    } catch (e) {
        showToast('Koneksi bermasalah saat upload.', 'error', 'Error');
        btn.disabled = false;
        btn.innerHTML = '<i class="bx bx-check-circle me-1"></i>Upload & Pindahkan ke Done';
    }
}

/* ─── OPEN MEDIA UPLOAD (tambah lampiran ke task Done) ─── */
function openMediaUpload(id) {
    const task = getTask(id);
    if (!task) return;
    pendingDoneTaskId = id;
    document.getElementById('upload-done-task-name').textContent = task.judul_tugas || 'Task';
    document.getElementById('upload-done-task-id').value   = id;
    document.getElementById('upload-done-projek-id').value = task.id_projek;
    uploadDoneFiles = [];
    document.getElementById('uploadDonePreview').innerHTML = '';
    document.getElementById('btnConfirmDone').disabled = true;
    document.querySelector('#modalUploadDone .modal-grad-title').textContent = '📋 Tambah Lampiran Hasil';
    document.querySelector('#modalUploadDone .modal-grad-sub').textContent   = 'Upload file laporan hasil tambahan';
    new bootstrap.Modal(document.getElementById('modalUploadDone')).show();
}

/* ─── TASK DETAIL ─── */
function openDetail(id) {
    const task = getTask(id);
    if (!task) return;

    document.getElementById('dtl-title').textContent   = task.judul_tugas || '—';
    document.getElementById('dtl-sub').textContent     = `Level: ${task.level || '—'} | Weight: ${task.weight || 1}`;
    document.getElementById('dtl-project').textContent = task.nama_projek || '—';
    document.getElementById('dtl-desc').textContent    = task.deskripsi_tugas || 'Tidak ada deskripsi.';
    document.getElementById('dtl-start').textContent   = fmtDate(task.tanggal_mulai);
    document.getElementById('dtl-end').textContent     = fmtDate(task.tenggat_waktu);
    document.getElementById('dtl-done').textContent    = task.tanggal_selesai ? fmtDate(task.tanggal_selesai) : 'Belum selesai';

    const sp = task.status_progress;
    const spColors = { 'draft':'#6B7280','To Do':'#6B7280','In Progress':'#D97706','done':'#059669' };
    const spBg     = { 'draft':'#F3F4F6','To Do':'#F3F4F6','In Progress':'#FEF3C7','done':'#D1FAE5' };
    document.getElementById('dtl-status').innerHTML = `<span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:5px;font-size:12px;font-weight:700;background:${spBg[sp]||'#F3F4F6'};color:${spColors[sp]||'#6B7280'};">
        <span style="width:6px;height:6px;border-radius:50%;background:currentColor;"></span>${STATUS_LABELS[sp]||sp}
    </span>`;

    const lv = task.level || 'mudah';
    document.getElementById('dtl-level').innerHTML = `<span class="level-badge lv-${lv}">${lv}</span> <span style="font-size:12px;color:var(--g600);margin-left:6px;">Weight: ${task.weight || LV_WEIGHT[lv] || 1}</span>`;

    document.getElementById('dtl-sa').innerHTML = task.status_akhir
        ? `<span class="sa-chip sa-${task.status_akhir}">${SA_LABELS[task.status_akhir]||task.status_akhir}</span>`
        : `<span style="font-size:12px;color:var(--g400);font-style:italic;">Belum dinilai</span>`;

    const briefFotos = (task.foto || []).filter(f => f.tipe !== 'hasil');
    const hasilFotos = (task.foto || []).filter(f => f.tipe === 'hasil');

    document.getElementById('dtl-brief').innerHTML = briefFotos.length
        ? briefFotos.map(f => isImgFile(f.nama_file || f.url)
            ? `<img class="gallery-img" src="${escHtml(f.url)}" onclick="window.open('${escHtml(f.url)}','_blank')">`
            : `<a class="gallery-doc" href="${escHtml(f.url)}" target="_blank"><i class="bx ${docIcon(f.nama_file||f.url)}"></i>${escHtml((f.nama_file||'Doc').split('/').pop().substring(0,14))}</a>`
          ).join('')
        : '<span style="font-size:12px;color:var(--g400);font-style:italic;">Belum ada foto brief.</span>';

    document.getElementById('dtl-hasil').innerHTML = hasilFotos.length
        ? hasilFotos.map(f => isImgFile(f.nama_file || f.url)
            ? `<img class="gallery-img" src="${escHtml(f.url)}" onclick="window.open('${escHtml(f.url)}','_blank')">`
            : `<a class="gallery-doc" href="${escHtml(f.url)}" target="_blank"><i class="bx ${docIcon(f.nama_file||f.url)}"></i>${escHtml((f.nama_file||'Doc').split('/').pop().substring(0,14))}</a>`
          ).join('')
        : '<span style="font-size:12px;color:var(--g400);font-style:italic;">Belum ada laporan hasil.</span>';

    new bootstrap.Modal(document.getElementById('modalDetail')).show();
}

/* ─── INIT ─── */
document.addEventListener('DOMContentLoaded', () => {
    renderKanban();
});
</script>
@endpush