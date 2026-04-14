@extends('layouts.master')
@section('title', 'Approval Task')
@push('styles')
<style>
:root {
    --blue:        #4F46E5;
    --blue-light:  #EEF2FF;
    --green:       #10B981;
    --green-light: #D1FAE5;
    --amber:       #F59E0B;
    --amber-light: #FEF3C7;
    --red:         #EF4444;
    --red-light:   #FEE2E2;
    --purple:      #8B5CF6;
    --purple-light:#EDE9FE;
    --yellow:      #EAB308;
    --yellow-light:#FEFCE8;
    --teal:        #0D9488;
    --teal-light:  #CCFBF1;
    --g50:  #F9FAFB; --g100: #F3F4F6; --g200: #E5E7EB;
    --g300: #D1D5DB; --g400: #9CA3AF; --g500: #6B7280;
    --g600: #4B5563; --g700: #374151; --g800: #1F2937;
    --g900: #111827;
    --r-sm: 8px; --r-md: 12px; --r-lg: 16px;
    --sh-sm: 0 1px 3px rgba(0,0,0,.07);
    --sh-md: 0 4px 16px rgba(79,70,229,.15);
}
body { background: var(--g50); }

/* ── PAGE HEADER ── */
.page-header-wrap { display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px; margin-bottom:24px; }
.page-header-wrap h4 { font-size:22px; font-weight:800; color:var(--g900); margin:0 0 4px; letter-spacing:-.4px; }
.page-header-wrap p  { font-size:13px; color:var(--g500); margin:0; }

/* ── ALERTS ── */
.session-alert { border-radius:var(--r-md); padding:12px 16px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:10px; margin-bottom:16px; border:none; }
.session-alert.success { background:var(--green-light); color:#065F46; }
.session-alert.error   { background:var(--red-light);   color:#991B1B; }

/* ── DATA CARD WRAPPER ── */
.data-card { overflow: visible !important; }

/* ══════════════════════════════════════
   TOP CONTROL BAR
   ══════════════════════════════════════ */
.top-control-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: nowrap;
    padding: 14px 20px;
    border-bottom: 1px solid var(--g200);
    background: white;
    overflow: visible;
    position: relative;
    z-index: 200;
}

/* ── TABS ── */
.approval-tabs { display:flex; gap:6px; flex-shrink:0; }
.approval-tab {
    display:inline-flex; align-items:center; gap:6px;
    padding:8px 16px; border-radius:99px;
    border:1.5px solid var(--g200); background:white;
    font-size:12px; font-weight:700; cursor:pointer;
    transition:all .2s; text-decoration:none; color:var(--g600);
    white-space: nowrap; flex-shrink:0;
}
.approval-tab:hover { border-color:var(--blue); color:var(--blue); background:var(--blue-light); }
.approval-tab.active {
    background:linear-gradient(135deg,var(--blue) 0%,var(--purple) 100%);
    border-color:transparent; color:white;
    box-shadow:0 3px 12px rgba(79,70,229,.35);
}
.approval-tab.riwayat-tab:hover { border-color:var(--teal); color:var(--teal); background:var(--teal-light); }
.approval-tab.riwayat-tab.active {
    background:linear-gradient(135deg,var(--teal) 0%,#059669 100%);
    box-shadow:0 3px 12px rgba(13,148,136,.35);
}
.tab-badge {
    display:inline-flex; align-items:center; justify-content:center;
    min-width:20px; height:20px; padding:0 6px;
    border-radius:99px; font-size:10px; font-weight:800;
    background:rgba(0,0,0,.1); color:inherit;
}
.approval-tab.active .tab-badge { background:rgba(255,255,255,.22); color:white; }
.bar-divider { width:1px; height:28px; background:var(--g200); flex-shrink:0; margin:0 4px; }

/* ── CUSTOM DROPDOWN ── */
.proj-label { font-size:12px; color:var(--g500); font-weight:700; white-space:nowrap; flex-shrink:0; display:flex; align-items:center; gap:4px; }
.proj-label i { font-size:14px; }
.custom-select-wrap { position:relative; flex-shrink:0; min-width:0; z-index:300; }
.custom-select-trigger {
    display:flex; align-items:center; gap:8px;
    padding:8px 12px;
    border:1.5px solid var(--g300); border-radius:var(--r-sm);
    background:white; cursor:pointer;
    font-size:13px; font-weight:600; color:var(--g800);
    white-space:nowrap; min-width:180px; max-width:220px;
    transition:border-color .2s,box-shadow .2s; user-select:none;
}
.custom-select-trigger:hover,
.custom-select-wrap.open .custom-select-trigger { border-color:var(--blue); box-shadow:0 0 0 3px rgba(79,70,229,.1); }
.custom-select-trigger .trigger-text { flex:1; overflow:hidden; text-overflow:ellipsis; }
.custom-select-trigger .trigger-caret { font-size:16px; color:var(--g400); transition:transform .2s; flex-shrink:0; }
.custom-select-wrap.open .trigger-caret { transform:rotate(180deg); }
.custom-select-dropdown {
    display:none; position:absolute; top:calc(100% + 6px); left:0;
    min-width:260px; max-width:340px;
    background:white; border:1.5px solid var(--g200); border-radius:var(--r-md);
    box-shadow:0 8px 30px rgba(0,0,0,.12); z-index:9999; overflow:hidden;
}
.custom-select-wrap.open .custom-select-dropdown { display:block; }
.custom-select-search-wrap { padding:10px 10px 6px; border-bottom:1px solid var(--g100); position:relative; }
.custom-select-search { width:100%; padding:7px 10px 7px 32px; border:1.5px solid var(--g200); border-radius:7px; font-size:13px; font-weight:500; color:var(--g800); outline:none; transition:border-color .2s; box-sizing:border-box; }
.custom-select-search:focus { border-color:var(--blue); }
.custom-select-search-icon { position:absolute; left:19px; top:50%; transform:translateY(-50%); font-size:15px; color:var(--g400); pointer-events:none; }
.custom-select-list { max-height:220px; overflow-y:auto; padding:6px 0; scrollbar-width:thin; scrollbar-color:var(--g200) transparent; }
.custom-select-list::-webkit-scrollbar { width:4px; }
.custom-select-list::-webkit-scrollbar-thumb { background:var(--g200); border-radius:99px; }
.custom-select-option { padding:9px 14px; font-size:13px; font-weight:600; color:var(--g700); cursor:pointer; display:flex; align-items:center; gap:8px; transition:background .12s; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.custom-select-option:hover { background:var(--g50); }
.custom-select-option.selected { background:var(--blue-light); color:var(--blue); }
.custom-select-option.selected::after { content:'\eabc'; font-family:'boxicons'; font-size:14px; margin-left:auto; color:var(--blue); }
.custom-select-option i { font-size:14px; color:var(--g400); flex-shrink:0; }
.custom-select-option.selected i { color:var(--blue); }
.custom-select-empty { padding:16px 14px; font-size:12px; color:var(--g400); text-align:center; font-style:italic; }

/* ── SEARCH INPUT ── */
.search-wrap { position:relative; display:flex; align-items:center; flex-shrink:0; }
.search-input { padding:8px 30px 8px 34px; border:1.5px solid var(--g300); border-radius:var(--r-sm); font-size:13px; font-weight:500; color:var(--g800); background:white; outline:none; width:200px; transition:border-color .2s,box-shadow .2s,width .25s; }
.search-input:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(79,70,229,.1); width:240px; }
.search-input::placeholder { color:var(--g400); font-weight:400; }
.search-icon-left { position:absolute; left:11px; font-size:15px; color:var(--g400); pointer-events:none; }
.search-clear-btn { position:absolute; right:8px; font-size:15px; color:var(--g400); cursor:pointer; background:none; border:none; padding:0; display:none; line-height:1; transition:color .15s; }
.search-clear-btn:hover { color:var(--g700); }

/* ── SEARCH RESULT BAR ── */
.search-result-bar {
    display:none; align-items:center; gap:8px;
    padding:9px 20px;
    margin:10px 20px;
    font-size:12px; font-weight:600; color:var(--blue);
    background:var(--blue-light);
    border:1px solid #C7D2FE;
    border-radius:var(--r-sm);
}
.search-result-bar i { font-size:14px; }
.search-result-bar .reset-btn { margin-left:auto; background:none; border:none; cursor:pointer; font-size:12px; font-weight:700; color:var(--blue); padding:0; display:flex; align-items:center; gap:3px; }

/* ══════════════════════════════════════
   TASK GRID & CARD — COMPACT STYLE
   ══════════════════════════════════════ */
.task-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(320px,1fr)); gap:14px; padding:20px 24px; }

.task-card {
    background:white;
    border:1px solid var(--g200);
    border-radius:var(--r-md);
    padding:14px 16px;
    transition:all .2s;
    position:relative;
    box-shadow:var(--sh-sm);
    cursor:pointer;
    display:flex;
    flex-direction:column;
    gap:10px;
}
.task-card:hover { transform:translateY(-2px); box-shadow:var(--sh-md); border-color:var(--blue); }
.task-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; border-radius:var(--r-md) var(--r-md) 0 0; }
.task-card.status-review::before   { background:linear-gradient(90deg,var(--blue),var(--purple)); }
.task-card.status-revisi::before   { background:linear-gradient(90deg,var(--amber),#EA580C); }
.task-card.status-approved::before { background:linear-gradient(90deg,var(--green),var(--teal)); }

/* Project badge row */
.tc-top {
    display:flex; align-items:center; justify-content:space-between; gap:8px;
}
.tc-project-badge {
    display:inline-flex; align-items:center; gap:4px;
    font-size:10px; font-weight:700; color:var(--blue);
    background:var(--blue-light); border:1px solid #C7D2FE;
    border-radius:5px; padding:2px 8px;
    max-width:calc(100% - 40px); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
}
.tc-dot {
    width:10px; height:10px; border-radius:50%; flex-shrink:0;
}
.tc-dot.review   { background:var(--blue); }
.tc-dot.revisi   { background:var(--amber); }
.tc-dot.approved { background:var(--green); }

/* Title */
.tc-title {
    font-size:15px; font-weight:800; color:var(--g900);
    line-height:1.35; margin:0;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
}

/* Deskripsi singkat */
.tc-desc {
    font-size:12px; color:var(--g500); line-height:1.5; margin:0;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
}

/* Meta pills row */
.tc-pills {
    display:flex; align-items:center; flex-wrap:wrap; gap:6px;
}
.tc-pill {
    display:inline-flex; align-items:center; gap:4px;
    padding:3px 9px; border-radius:99px;
    font-size:11px; font-weight:700; white-space:nowrap;
}
.tc-pill.level-mudah  { background:var(--green-light); color:#059669; }
.tc-pill.level-medium { background:var(--amber-light); color:#B45309; }
.tc-pill.level-susah  { background:var(--red-light);   color:#DC2626; }
.tc-pill.weight       { background:var(--g100); color:var(--g600); }
.tc-pill.status-review-pm   { background:var(--purple-light); color:#7C3AED; }
.tc-pill.status-revisi-pm   { background:var(--yellow-light); color:#A16207; border:1px solid #FDE047; }
.tc-pill.status-approved-pm { background:var(--green-light);  color:#065F46; }
.tc-pill.pernah-approved    { background:var(--green-light); color:#059669; border:1px solid #6EE7B7; }

/* Assignee row */
.tc-assignee {
    display:flex; align-items:center; gap:7px;
}
.tc-avatar {
    width:26px; height:26px; border-radius:50%;
    background:linear-gradient(135deg,var(--blue),var(--purple));
    color:white; font-size:10px; font-weight:800;
    display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;
}
.tc-assignee-name {
    font-size:12px; font-weight:700; color:var(--g800);
    overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
}
.tc-assignee-role {
    font-size:11px; color:var(--g500);
}

/* Dates row */
.tc-dates {
    display:flex; align-items:center; gap:12px; flex-wrap:wrap;
}
.tc-date-item {
    display:flex; align-items:center; gap:4px;
    font-size:12px; font-weight:600; color:var(--g600);
}
.tc-date-item i { font-size:13px; }
.tc-date-item.overdue { color:var(--red); }
.tc-date-item.selesai { color:var(--green); }

/* Banner status */
.tc-banner {
    display:flex; align-items:center; gap:6px;
    border-radius:6px; padding:6px 10px;
    font-size:11px; font-weight:700;
}
.tc-banner.review   { background:var(--purple-light); border:1px solid #DDD6FE; color:#7C3AED; }
.tc-banner.revisi   { background:var(--yellow-light); border:1px solid #FDE047; color:#A16207; }
.tc-banner.approved { background:var(--green-light);  border:1px solid #6EE7B7; color:#065F46; }

/* Footer actions */
.tc-footer {
    display:flex; align-items:center; justify-content:flex-end; gap:6px;
    padding-top:10px; border-top:1px solid var(--g100);
    flex-wrap:wrap;
}
.tca-btn {
    padding:6px 13px; border-radius:7px; font-size:12px; font-weight:700;
    cursor:pointer; display:inline-flex; align-items:center; gap:5px;
    transition:all .18s; border:1.5px solid; pointer-events:auto;
}
.tca-btn.btn-detail  { border-color:var(--g200); color:var(--g600); background:white; }
.tca-btn.btn-detail:hover { border-color:var(--blue); color:var(--blue); background:var(--blue-light); }
.tca-btn.btn-revisi  { border-color:#FECACA; color:#DC2626; background:#FEF2F2; }
.tca-btn.btn-revisi:hover { background:var(--red-light); border-color:#FCA5A5; }
.tca-btn.btn-edit    { border-color:var(--amber); color:#B45309; background:var(--amber-light); }
.tca-btn.btn-edit:hover { background:var(--amber); color:white; }
.tca-btn.btn-approve { border-color:transparent; color:white; background:linear-gradient(135deg,var(--green),#059669); }
.tca-btn.btn-approve:hover { transform:translateY(-1px); box-shadow:0 3px 10px rgba(16,185,129,.3); }

/* Approved stamp (riwayat) */
.approved-stamp {
    position:absolute; top:12px; right:12px;
    display:flex; align-items:center; gap:4px;
    font-size:10px; font-weight:800; color:#059669;
    background:var(--green-light); border:1.5px solid #6EE7B7;
    border-radius:6px; padding:2px 8px; pointer-events:none;
}

/* ── EMPTY STATE ── */
.approval-empty { grid-column:1/-1; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:60px 24px; gap:12px; text-align:center; }
.approval-empty i { font-size:48px; color:var(--g300); }
.approval-empty p { font-size:14px; color:var(--g400); font-weight:600; margin:0; }
.approval-empty small { font-size:12px; color:var(--g300); }

/* ══════════════════════════════════════
   MODAL
   ══════════════════════════════════════ */
.modal-content { border:none; border-radius:var(--r-lg); box-shadow:0 20px 60px rgba(0,0,0,.18); overflow:hidden; }
.modal-grad-header { background:linear-gradient(135deg,var(--blue) 0%,var(--purple) 100%); padding:22px 24px 18px; }
.modal-grad-header.amber { background:linear-gradient(135deg,var(--amber),#EA580C); }
.modal-grad-header.green { background:linear-gradient(135deg,var(--green),#059669); }
.modal-grad-header.teal  { background:linear-gradient(135deg,var(--teal),#059669); }
.modal-grad-title { font-size:17px; font-weight:800; color:white; margin-bottom:4px; }
.modal-grad-sub   { font-size:12px; color:rgba(255,255,255,.82); margin:0; }
.detail-field { margin-bottom:16px; }
.detail-lbl { font-size:11px; font-weight:700; color:var(--g400); margin-bottom:5px; display:flex; align-items:center; gap:5px; }
.detail-lbl::before { content:''; display:inline-block; width:3px; height:12px; background:var(--blue); border-radius:2px; }
.detail-val { font-size:14px; font-weight:600; color:var(--g900); }
.detail-desc-box { background:var(--g50); border:1px solid var(--g200); border-radius:8px; padding:12px 14px; font-size:13px; color:var(--g700); line-height:1.6; }
.gallery-section { border:1px solid var(--g200); border-radius:10px; overflow:hidden; margin-bottom:12px; }
.gallery-section-head { padding:9px 14px; font-size:12px; font-weight:700; display:flex; align-items:center; gap:6px; }
.gallery-section-head.brief { background:var(--blue-light); color:var(--blue); }
.gallery-section-head.hasil { background:var(--green-light); color:#059669; }
.gallery-body { padding:12px; }
.gallery-row { display:flex; flex-wrap:wrap; gap:8px; }
.gallery-img { width:88px; height:65px; object-fit:cover; border-radius:7px; border:1px solid var(--g200); cursor:pointer; transition:transform .2s,box-shadow .2s; }
.gallery-img:hover { transform:scale(1.05); box-shadow:var(--sh-md); }
.gallery-doc { display:flex; flex-direction:column; align-items:center; gap:3px; padding:8px 10px; background:var(--g50); border:1px solid var(--g200); border-radius:7px; font-size:10px; font-weight:700; color:var(--blue); text-decoration:none; transition:background .15s; }
.gallery-doc:hover { background:var(--blue-light); }
.gallery-doc i { font-size:20px; }
.form-group-modal { margin-bottom:16px; }
.form-label-modal { display:block; font-size:11px; font-weight:700; color:var(--g500); margin-bottom:6px; }
.form-control-modal { width:100%; padding:9px 12px; border:1.5px solid var(--g300); border-radius:8px; font-size:13px; font-weight:500; color:var(--g800); background:white; outline:none; transition:border-color .2s,box-shadow .2s; font-family:inherit; }
.form-control-modal:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(79,70,229,.12); }
textarea.form-control-modal { resize:vertical; min-height:80px; }
select.form-control-modal { cursor:pointer; }
.upload-drop-zone { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px; padding:20px; border:2px dashed var(--g300); border-radius:10px; cursor:pointer; text-align:center; transition:all .2s; background:var(--g50); }
.upload-drop-zone:hover { border-color:var(--blue); background:var(--blue-light); }
.upload-drop-zone i { font-size:28px; color:var(--blue); }
.upload-drop-zone p { font-size:13px; font-weight:600; color:var(--g700); margin:0; }
.upload-drop-zone small { font-size:11px; color:var(--g400); }
.revisi-note { width:100%; min-height:90px; border:1.5px solid var(--g300); border-radius:8px; padding:10px 12px; font-size:13px; font-family:inherit; resize:vertical; color:var(--g700); background:white; outline:none; transition:border-color .2s,box-shadow .2s; }
.revisi-note:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(79,70,229,.12); }
.fhint { font-size:11px; color:var(--g400); margin-top:5px; }
.modal-btn { display:inline-flex; align-items:center; gap:6px; padding:9px 18px; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; transition:all .2s; border:1.5px solid; }
.modal-btn.outline   { border-color:var(--g200); background:white; color:var(--g600); }
.modal-btn.outline:hover { border-color:var(--g400); background:var(--g100); }
.modal-btn.primary   { border-color:transparent; background:linear-gradient(135deg,var(--blue),var(--purple)); color:white; }
.modal-btn.primary:hover { transform:translateY(-1px); }
.modal-btn.amber-btn { border-color:transparent; background:linear-gradient(135deg,var(--amber),#EA580C); color:white; }
.modal-btn.amber-btn:hover { transform:translateY(-1px); }
.modal-btn.success   { border-color:transparent; background:linear-gradient(135deg,var(--green),#059669); color:white; }
.modal-btn.success:hover { transform:translateY(-1px); }
.modal-btn.danger    { border-color:transparent; background:linear-gradient(135deg,var(--red),#DC2626); color:white; }
.modal-section-title { font-size:11px; font-weight:800; color:var(--g400); padding:10px 0 8px; border-bottom:1px solid var(--g100); margin-bottom:14px; display:flex; align-items:center; gap:6px; }
.modal-section-title::before { content:''; width:3px; height:14px; border-radius:2px; flex-shrink:0; background:linear-gradient(180deg,var(--blue),var(--purple)); }
.existing-foto-item { position:relative; width:80px; display:flex; flex-direction:column; align-items:center; gap:4px; }
.existing-foto-item img { width:80px; height:60px; object-fit:cover; border-radius:6px; border:1px solid var(--g200); }
.existing-foto-item .delete-foto-btn { position:absolute; top:-6px; right:-6px; width:18px; height:18px; border-radius:50%; background:var(--red); border:none; color:white; font-size:10px; cursor:pointer; display:flex; align-items:center; justify-content:center; z-index:2; }
.upload-preview-item { position:relative; width:80px; display:flex; flex-direction:column; align-items:center; gap:4px; }
.upload-preview-item img { width:80px; height:60px; object-fit:cover; border-radius:6px; border:1.5px dashed var(--blue); }
.upload-preview-item .rm-upload-btn { position:absolute; top:-6px; right:-6px; width:18px; height:18px; border-radius:50%; background:var(--g500); border:none; color:white; font-size:10px; cursor:pointer; display:flex; align-items:center; justify-content:center; }
.edit-mode-indicator { display:inline-flex; align-items:center; gap:5px; font-size:10px; font-weight:700; padding:3px 10px; border-radius:99px; background:rgba(255,255,255,.2); color:white; margin-top:6px; }
.approved-info-box { background:var(--green-light); border:1px solid #6EE7B7; border-radius:10px; padding:12px 16px; display:flex; align-items:flex-start; gap:10px; margin-bottom:16px; }
.approved-info-box i { font-size:20px; color:#059669; flex-shrink:0; margin-top:1px; }
.approved-info-box div { font-size:13px; font-weight:600; color:#065F46; line-height:1.5; }
.tc-approved-date { font-size:11px; color:#059669; font-weight:700; display:flex; align-items:center; gap:4px; }

/* ── RESPONSIVE ── */
@media (max-width:900px) {
    .top-control-bar { flex-wrap:wrap; overflow-x:auto; }
    .bar-divider { display:none; }
    .approval-tabs { width:100%; overflow-x:auto; }
    .search-input { width:160px; }
    .search-input:focus { width:200px; }
}
@media (max-width:600px) {
    .task-grid { grid-template-columns:1fr; padding:14px; }
    .custom-select-trigger { min-width:140px; }
    .search-input { width:130px; }
}
</style>
@endpush

@section('content')
<div class="page-header-wrap">
    <div>
        <h4>Approval Task</h4>
        <p>Kelola dan tinjau task yang menunggu persetujuan dari karyawan</p>
    </div>
</div>

@if(session('success'))
<div class="session-alert success">
    <i class="bx bx-check-circle" style="font-size:18px;flex-shrink:0;"></i>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="session-alert error">
    <i class="bx bx-error-circle" style="font-size:18px;flex-shrink:0;"></i>
    {{ session('error') }}
</div>
@endif

<div class="data-card">

    {{-- TOP CONTROL BAR --}}
    <div class="top-control-bar">
        <div class="approval-tabs">
            <a href="{{ route('approval-task.index', array_merge(request()->except('tab'), ['tab' => 'menunggu', 'id_projek' => $filterProjek ?? 'all'])) }}"
               class="approval-tab {{ $tab === 'menunggu' ? 'active' : '' }}">
                <i class="bx bx-time-five"></i>Menunggu Approval
                <span class="tab-badge">{{ $countMenunggu }}</span>
            </a>
            <a href="{{ route('approval-task.index', array_merge(request()->except('tab'), ['tab' => 'revisi', 'id_projek' => $filterProjek ?? 'all'])) }}"
               class="approval-tab {{ $tab === 'revisi' ? 'active' : '' }}">
                <i class="bx bx-pencil"></i>Revisi
                <span class="tab-badge">{{ $countRevisi }}</span>
            </a>
            <a href="{{ route('approval-task.index', array_merge(request()->except('tab'), ['tab' => 'riwayat', 'id_projek' => $filterProjek ?? 'all'])) }}"
               class="approval-tab riwayat-tab {{ $tab === 'riwayat' ? 'active' : '' }}">
                <i class="bx bx-check-shield"></i>Riwayat Approve
                <span class="tab-badge">{{ $countRiwayat }}</span>
            </a>
        </div>
        <div class="bar-divider"></div>
        <span class="proj-label"><i class="bx bx-folder-open"></i>Project:</span>
        <div class="custom-select-wrap" id="customSelectWrap">
            <div class="custom-select-trigger" id="customSelectTrigger" onclick="toggleCustomSelect()">
                <span class="trigger-text" id="customSelectText">
                    {{ (!$filterProjek || $filterProjek === 'all') ? 'Semua Project' : ($semuaProjek->firstWhere('id_projek', $filterProjek)?->nama_projek ?? 'Semua Project') }}
                </span>
                <i class="bx bx-chevron-down trigger-caret"></i>
            </div>
            <div class="custom-select-dropdown" id="customSelectDropdown">
                <div class="custom-select-search-wrap">
                    <i class="bx bx-search custom-select-search-icon"></i>
                    <input type="text" class="custom-select-search" id="customSelectSearch"
                        placeholder="Cari project..." oninput="filterCustomOptions(this.value)"
                        onclick="event.stopPropagation()" autocomplete="off">
                </div>
                <div class="custom-select-list" id="customSelectList">
                    <div class="custom-select-option {{ (!$filterProjek || $filterProjek === 'all') ? 'selected' : '' }}"
                         data-value="all" data-label="Semua Project"
                         onclick="selectCustomOption('all', 'Semua Project')">
                        <i class="bx bx-grid-alt"></i>Semua Project
                    </div>
                    @foreach($semuaProjek as $proj)
                    <div class="custom-select-option {{ $filterProjek == $proj->id_projek ? 'selected' : '' }}"
                         data-value="{{ $proj->id_projek }}"
                         data-label="{{ $proj->nama_projek }}{{ $proj->pembuat ? ' · ' . ($proj->pembuat->nama ?? '') : '' }}"
                         onclick="selectCustomOption('{{ $proj->id_projek }}', '{{ addslashes($proj->nama_projek) }}')">
                        <i class="bx bx-folder"></i>
                        {{ $proj->nama_projek }}{{ $proj->pembuat ? ' · ' . ($proj->pembuat->nama ?? '') : '' }}
                    </div>
                    @endforeach
                    <div class="custom-select-empty" id="customSelectEmpty" style="display:none;">
                        Tidak ada project ditemukan
                    </div>
                </div>
            </div>
        </div>
        <div class="search-wrap">
            <i class="bx bx-search search-icon-left"></i>
            <input type="text" id="searchInput" class="search-input"
                placeholder="Cari task..." autocomplete="off" oninput="handleSearch(this.value)">
            <button class="search-clear-btn" id="searchClear" onclick="clearSearch()">
                <i class="bx bx-x"></i>
            </button>
        </div>
    </div>

    {{-- Search result bar --}}
    <div class="search-result-bar" id="searchResultBar">
        <i class="bx bx-search-alt"></i>
        <span id="searchResultText"></span>
        <button class="reset-btn" onclick="clearSearch()">
            <i class="bx bx-x" style="font-size:14px;"></i>Reset
        </button>
    </div>

    {{-- TASK GRID --}}
    <div class="task-grid" id="taskGrid">
        @forelse($tugasList as $tugas)
        @php
            $assignee       = optional(optional($tugas->tim)->user);
            $namaAssign     = $assignee->nama ?? '—';
            $jabatan        = optional(optional($assignee)->jobRole)->nama_job_role ?? null;
            $avatar         = strtoupper(substr($namaAssign, 0, 2));
            $namaProjek     = optional($tugas->projek)->nama_projek ?? '—';
            $tenggat        = $tugas->tenggat_waktu;
            $isOverdue      = $tenggat && \Carbon\Carbon::parse($tenggat)->isPast() && $tugas->status_progress !== 'done';
            $pernahApproved = (bool)($tugas->pernah_approved ?? false);
            $fotoArr = $tugas->foto ? $tugas->foto->map(fn($f) => [
                'id'   => $f->id_tugas_foto,
                'url'  => Storage::url($f->nama_file),
                'tipe' => $f->tipe,
                'nama' => $f->nama_file,
            ])->values()->toArray() : [];
            $timProjek = \App\Models\ProjekTim::with('user')
                ->where('id_projek', $tugas->id_projek)->get();
            $taskJson = json_encode([
                'id_tugas'        => $tugas->id_tugas,
                'id_projek'       => $tugas->id_projek,
                'judul_tugas'     => $tugas->judul_tugas,
                'deskripsi_tugas' => $tugas->deskripsi_tugas ?? '',
                'nama_projek'     => $namaProjek,
                'nama_assignee'   => $namaAssign,
                'jabatan'         => $jabatan,
                'avatar'          => $avatar,
                'id_tim'          => $tugas->id_tim,
                'level'           => $tugas->level,
                'weight'          => $tugas->weight,
                'status_progress' => $tugas->status_progress,
                'status_akhir'    => $tugas->status_akhir,
                'pernah_approved' => $pernahApproved,
                'tanggal_mulai'   => $tugas->tanggal_mulai?->format('Y-m-d'),
                'tenggat_waktu'   => $tugas->tenggat_waktu?->format('Y-m-d'),
                'tanggal_selesai' => $tugas->tanggal_selesai?->format('Y-m-d'),
                'diubah_pada'     => $tugas->diubah_pada?->format('Y-m-d H:i'),
                'dibuat_pada'     => $tugas->dibuat_pada?->format('Y-m-d H:i'),
                'foto'            => $fotoArr,
                'tim_list'        => $timProjek->map(fn($t) => [
                    'id_tim' => $t->id_tim,
                    'nama'   => optional($t->user)->nama ?? '—',
                ])->values()->toArray(),
            ]);
        @endphp

        <div class="task-card status-{{ $tugas->status_akhir }}"
             id="card-{{ $tugas->id_tugas }}"
             data-search-judul="{{ strtolower($tugas->judul_tugas) }}"
             data-search-projek="{{ strtolower($namaProjek) }}"
             data-search-assignee="{{ strtolower($namaAssign) }}"
             onclick="showDetail({{ $taskJson }})">

            @if($tab === 'riwayat')
            <div class="approved-stamp">
                <i class="bx bx-check-circle"></i>Approved
            </div>
            @endif

            {{-- Baris atas: project badge + dot status --}}
            <div class="tc-top">
                <div class="tc-project-badge">
                    <i class="bx bx-folder" style="font-size:10px;"></i>
                    {{ Str::limit($namaProjek, 38) }}
                </div>
                <div class="tc-dot {{ $tugas->status_akhir }}"></div>
            </div>

            {{-- Judul task --}}
            <p class="tc-title">{{ $tugas->judul_tugas }}</p>

            {{-- Deskripsi singkat --}}
            @if($tugas->deskripsi_tugas)
            <p class="tc-desc">{{ $tugas->deskripsi_tugas }}</p>
            @endif

            {{-- Pills: level + weight + status PM + pernah approved --}}
            <div class="tc-pills">
                <span class="tc-pill level-{{ $tugas->level }}">{{ ucfirst($tugas->level) }}</span>
                <span class="tc-pill weight">W: {{ $tugas->weight }}</span>
                @if($tugas->status_akhir === 'review')
                    <span class="tc-pill status-review-pm"><i class="bx bx-loader-circle" style="font-size:11px;"></i>Review PM</span>
                @elseif($tugas->status_akhir === 'revisi')
                    <span class="tc-pill status-revisi-pm"><i class="bx bx-undo" style="font-size:11px;"></i>Revisi</span>
                @elseif($tugas->status_akhir === 'approved')
                    <span class="tc-pill status-approved-pm"><i class="bx bx-check-circle" style="font-size:11px;"></i>Approved</span>
                @endif
                @if($tab !== 'riwayat' && $pernahApproved)
                    <span class="tc-pill pernah-approved"><i class="bx bx-check-shield" style="font-size:11px;"></i>Pernah Approved</span>
                @endif
            </div>

            {{-- Assignee --}}
            <div class="tc-assignee">
                <div class="tc-avatar">{{ $avatar }}</div>
                <div>
                    <div class="tc-assignee-name">{{ $namaAssign }}</div>
                    @if($jabatan)<div class="tc-assignee-role">{{ $jabatan }}</div>@endif
                </div>
            </div>

            {{-- Tanggal --}}
            <div class="tc-dates">
                <div class="tc-date-item {{ $isOverdue ? 'overdue' : '' }}">
                    <i class="bx bx-calendar-x"></i>
                    {{ $tenggat ? \Carbon\Carbon::parse($tenggat)->translatedFormat('d M Y') : '—' }}
                    @if($isOverdue)
                        <span style="font-size:10px;background:var(--red-light);color:var(--red);padding:1px 6px;border-radius:4px;margin-left:2px;">Terlambat</span>
                    @endif
                </div>
                @if($tugas->tanggal_selesai)
                <div class="tc-date-item selesai">
                    <i class="bx bx-check-circle"></i>
                    {{ \Carbon\Carbon::parse($tugas->tanggal_selesai)->translatedFormat('d M Y') }}
                </div>
                @endif
                @if($tab === 'riwayat' && $tugas->diubah_pada)
                <div class="tc-date-item selesai">
                    <i class="bx bx-calendar-check"></i>
                    {{ $tugas->diubah_pada->translatedFormat('d M Y') }}
                </div>
                @endif
            </div>

            {{-- Banner status --}}
            @if($tugas->status_akhir === 'review')
            <div class="tc-banner review"><i class="bx bx-hourglass"></i>Sedang ditinjau PM</div>
            @elseif($tugas->status_akhir === 'revisi')
            <div class="tc-banner revisi">
                <i class="bx bx-pencil"></i>Dikembalikan untuk revisi
                @if($pernahApproved)<span style="font-size:9px;opacity:.75;margin-left:4px;">(pernah approved)</span>@endif
            </div>
            @elseif($tugas->status_akhir === 'approved')
            <div class="tc-banner approved"><i class="bx bx-check-circle"></i>Task telah disetujui PM</div>
            @endif

            {{-- Footer actions --}}
            <div class="tc-footer" onclick="event.stopPropagation()">
                @if($tugas->status_akhir === 'review')
                    <button type="button" class="tca-btn btn-revisi"
                            onclick="openEditModal({{ $tugas->id_tugas }},'{{ addslashes($tugas->judul_tugas) }}',{{ $taskJson }},'revisi')">
                        <i class="bx bx-undo"></i>Revisi
                    </button>
                    <button type="button" class="tca-btn btn-approve"
                            onclick="confirmApprove({{ $tugas->id_tugas }},'{{ addslashes($tugas->judul_tugas) }}')">
                        <i class="bx bx-check-circle"></i>Approve
                    </button>
                @elseif($tugas->status_akhir === 'revisi')
                    <button type="button" class="tca-btn btn-detail"
                            onclick="showDetail({{ $taskJson }})">
                        <i class="bx bx-info-circle"></i>Detail
                    </button>
                    <button type="button" class="tca-btn btn-edit"
                            onclick="openEditModal({{ $tugas->id_tugas }},'{{ addslashes($tugas->judul_tugas) }}',{{ $taskJson }},'edit')">
                        <i class="bx bx-edit"></i>Edit
                    </button>
                @else
                    <button type="button" class="tca-btn btn-detail"
                            onclick="showDetail({{ $taskJson }})">
                        <i class="bx bx-info-circle"></i>Detail
                    </button>
                @endif
            </div>
        </div>

        @empty
        <div class="approval-empty" id="emptyStateServer">
            <i class="bx bx-{{ $tab === 'riwayat' ? 'check-shield' : 'task-x' }}"></i>
            <p>
                @if($tab === 'menunggu') Tidak ada task yang menunggu approval saat ini.
                @elseif($tab === 'revisi') Tidak ada task dengan status Revisi saat ini.
                @else Belum ada task yang pernah diapprove.
                @endif
            </p>
            @if($tab === 'menunggu')
            <small>Task akan muncul di sini ketika karyawan menandai task sebagai Done.</small>
            @endif
        </div>
        @endforelse
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="modalDetailTask" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-grad-header" id="dt_header_el">
                <div style="position:relative;">
                    <div class="modal-grad-title" id="dt_judul">Detail Task</div>
                    <p class="modal-grad-sub" id="dt_sub">—</p>
                    <div id="dt_header_badges" style="display:flex;gap:6px;flex-wrap:wrap;margin-top:6px;"></div>
                    <button type="button" class="btn-close btn-close-white position-absolute" style="top:0;right:0;" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body" style="padding:22px 24px;">
                <div id="dt_approved_box" style="display:none;" class="approved-info-box">
                    <i class="bx bx-check-shield"></i>
                    <div>Task ini telah disetujui oleh PM.<br><small id="dt_approved_date"></small></div>
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px;" id="dt_badges"></div>
                <div class="row">
                    <div class="col-md-7">
                        <div class="modal-section-title">Informasi Task</div>
                        <div class="detail-field"><div class="detail-lbl">Project</div><div class="detail-val" id="dt_projek">—</div></div>
                        <div class="detail-field"><div class="detail-lbl">Penanggung Jawab</div><div id="dt_assignee">—</div></div>
                        <div class="detail-field"><div class="detail-lbl">Deskripsi</div><div class="detail-desc-box" id="dt_deskripsi">—</div></div>
                    </div>
                    <div class="col-md-5">
                        <div class="modal-section-title">Waktu & Status</div>
                        <div class="detail-field"><div class="detail-lbl">Tanggal Mulai</div><div class="detail-val" id="dt_mulai">—</div></div>
                        <div class="detail-field"><div class="detail-lbl">Tenggat Waktu</div><div class="detail-val" id="dt_tenggat">—</div></div>
                        <div class="detail-field"><div class="detail-lbl">Tanggal Selesai</div><div class="detail-val" id="dt_selesai">—</div></div>
                        <div class="detail-field"><div class="detail-lbl">Bobot (Weight)</div><div class="detail-val" id="dt_weight">—</div></div>
                        <div class="detail-field"><div class="detail-lbl">Status Progress</div><div id="dt_status_progress">—</div></div>
                        <div class="detail-field"><div class="detail-lbl">Penilaian PM</div><div id="dt_status_akhir">—</div></div>
                    </div>
                </div>
                <div class="gallery-section">
                    <div class="gallery-section-head brief"><i class="bx bx-paperclip"></i>Foto Brief / Instruksi</div>
                    <div class="gallery-body"><div class="gallery-row" id="dt_foto_brief"><span style="font-size:12px;color:var(--g400);font-style:italic;">Belum ada foto brief.</span></div></div>
                </div>
                <div class="gallery-section">
                    <div class="gallery-section-head hasil"><i class="bx bx-image-alt"></i>Laporan Hasil Kerja</div>
                    <div class="gallery-body"><div class="gallery-row" id="dt_foto_hasil"><span style="font-size:12px;color:var(--g400);font-style:italic;">Belum ada laporan hasil.</span></div></div>
                </div>
            </div>
            <div class="modal-footer border-0" style="padding:0 24px 20px;justify-content:space-between;gap:8px;flex-wrap:wrap;">
                <div id="dt_action_btns" style="display:flex;gap:8px;flex-wrap:wrap;"></div>
                <button type="button" class="modal-btn outline" data-bs-dismiss="modal"><i class="bx bx-x"></i>Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="modalEditTask" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-grad-header amber">
                <div style="position:relative;">
                    <div class="modal-grad-title" id="edit_modal_title"><i class="bx bx-edit-alt" style="margin-right:6px;"></i>Edit Task</div>
                    <p class="modal-grad-sub" id="edit_sub">—</p>
                    <div id="edit_mode_badge" style="margin-top:6px;"></div>
                    <button type="button" class="btn-close btn-close-white position-absolute" style="top:0;right:0;" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body" style="padding:22px 24px;">
                <input type="hidden" id="edit_id_tugas">
                <input type="hidden" id="edit_id_projek">
                <input type="hidden" id="edit_mode">
                <div class="modal-section-title">Informasi Dasar</div>
                <div class="form-group-modal">
                    <label class="form-label-modal">Nama Task <span style="color:var(--red);">*</span></label>
                    <input type="text" id="edit_judul_tugas" class="form-control-modal" placeholder="Masukkan nama task..." maxlength="255">
                </div>
                <div class="form-group-modal">
                    <label class="form-label-modal">Deskripsi Task</label>
                    <textarea id="edit_deskripsi_tugas" class="form-control-modal" rows="4" placeholder="Masukkan deskripsi task..."></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-modal">
                            <label class="form-label-modal">Penanggung Jawab <span style="color:var(--red);">*</span></label>
                            <select id="edit_id_tim" class="form-control-modal"><option value="">-- Pilih Anggota --</option></select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-modal">
                            <label class="form-label-modal">Level <span style="color:var(--red);">*</span></label>
                            <select id="edit_level" class="form-control-modal">
                                <option value="mudah">Mudah (W: 1)</option>
                                <option value="medium">Medium (W: 2)</option>
                                <option value="susah">Susah (W: 3)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-modal">
                            <label class="form-label-modal">Tanggal Mulai</label>
                            <input type="date" id="edit_tanggal_mulai" class="form-control-modal">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-modal">
                            <label class="form-label-modal">Tenggat (Deadline)</label>
                            <input type="date" id="edit_tenggat_waktu" class="form-control-modal">
                        </div>
                    </div>
                </div>
                <div class="form-group-modal">
                    <label class="form-label-modal" id="edit_catatan_label">Catatan / Keterangan</label>
                    <textarea id="revisi_catatan_edit" class="revisi-note" placeholder="Tuliskan catatan atau keterangan tambahan..."></textarea>
                    <div class="fhint" id="edit_catatan_hint"><i class="bx bx-info-circle"></i> Catatan akan dikirim ke karyawan.</div>
                </div>
                <div class="modal-section-title" style="margin-top:8px;">Foto Brief / Instruksi</div>
                <div style="margin-bottom:10px;">
                    <div style="font-size:11px;color:var(--g500);margin-bottom:8px;font-weight:600;">Foto existing:</div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;" id="edit_existing_brief"></div>
                </div>
                <label class="upload-drop-zone" id="editBriefZone"
                    ondragover="event.preventDefault();this.classList.add('hover')"
                    ondragleave="this.classList.remove('hover')"
                    ondrop="handleEditBriefDrop(event)">
                    <i class="bx bx-cloud-upload"></i>
                    <p>Upload foto brief baru</p>
                    <small>JPG, PNG, PDF — Maks 10MB</small>
                    <input type="file" id="inputEditBrief" multiple accept="image/*,.pdf,.doc,.docx" style="display:none;" onchange="handleEditBriefChange(this)">
                </label>
                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:10px;" id="edit_brief_preview"></div>
                <div id="edit_error_box" style="display:none;background:var(--red-light);color:#991B1B;border-radius:8px;padding:10px 14px;font-size:13px;font-weight:600;margin-top:12px;"></div>
            </div>
            <div class="modal-footer border-0" style="padding:0 24px 20px;justify-content:flex-end;gap:8px;">
                <button type="button" class="modal-btn outline" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="modal-btn amber-btn" id="btn_save_edit" onclick="submitEdit()">
                    <i class="bx bx-save"></i><span id="btn_save_edit_label">Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL APPROVE --}}
<div class="modal fade" id="modalApproveConfirm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-grad-header green">
                <div style="position:relative;">
                    <div class="modal-grad-title"><i class="bx bx-check-circle" style="margin-right:6px;"></i>Approve Task</div>
                    <p class="modal-grad-sub" id="approve_judul">Konfirmasi persetujuan</p>
                    <button type="button" class="btn-close btn-close-white position-absolute" style="top:0;right:0;" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body" style="padding:22px 24px;text-align:center;">
                <i class="bx bx-check-circle" style="font-size:48px;color:var(--green);margin-bottom:12px;"></i>
                <p style="font-size:14px;color:var(--g700);margin-bottom:20px;">Task ini akan disetujui dan masuk ke Riwayat Approve.</p>
                <input type="hidden" id="approve_id_tugas">
                <input type="hidden" id="approve_judul_tugas">
            </div>
            <div class="modal-footer border-0" style="padding:0 24px 20px;justify-content:center;gap:8px;">
                <button type="button" class="modal-btn outline" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="modal-btn success" onclick="submitApprove()">
                    <i class="bx bx-check"></i>Ya, Approve
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
'use strict';
const CSRF_TOKEN     = '{{ csrf_token() }}';
const CURRENT_TAB    = '{{ $tab }}';
let _currentTaskData = null;
let _editBriefFiles  = [];
let _deletedFotoIds  = [];
let _editMode        = 'revisi';
let _searchTimer     = null;

/* ── CUSTOM DROPDOWN ── */
function toggleCustomSelect() {
    const wrap = document.getElementById('customSelectWrap');
    if (wrap.classList.contains('open')) { closeCustomSelect(); return; }
    wrap.classList.add('open');
    setTimeout(() => {
        const s = document.getElementById('customSelectSearch');
        s.focus(); s.value = ''; filterCustomOptions('');
    }, 50);
}
function closeCustomSelect() {
    document.getElementById('customSelectWrap').classList.remove('open');
    document.getElementById('customSelectSearch').value = '';
    filterCustomOptions('');
}
function filterCustomOptions(q) {
    const query = q.toLowerCase().trim();
    const opts  = document.querySelectorAll('#customSelectList .custom-select-option');
    const empty = document.getElementById('customSelectEmpty');
    let visible = 0;
    opts.forEach(o => {
        const show = !query || (o.dataset.label||'').toLowerCase().includes(query);
        o.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    empty.style.display = visible === 0 ? 'block' : 'none';
}
function selectCustomOption(value, label) {
    document.getElementById('customSelectText').textContent = label;
    document.querySelectorAll('#customSelectList .custom-select-option').forEach(o =>
        o.classList.toggle('selected', o.dataset.value === value));
    closeCustomSelect();
    const url = new URL(window.location.href);
    url.searchParams.set('id_projek', value);
    url.searchParams.set('tab', CURRENT_TAB);
    window.location.href = url.toString();
}
document.addEventListener('click', e => {
    const wrap = document.getElementById('customSelectWrap');
    if (wrap && !wrap.contains(e.target)) closeCustomSelect();
});

/* ── SEARCH ── */
function handleSearch(val) {
    clearTimeout(_searchTimer);
    _searchTimer = setTimeout(() => doSearch(val), 180);
    document.getElementById('searchClear').style.display = val.length ? 'block' : 'none';
}
function doSearch(query) {
    const q     = query.trim().toLowerCase();
    const cards = document.querySelectorAll('#taskGrid .task-card');
    const bar   = document.getElementById('searchResultBar');
    const barTx = document.getElementById('searchResultText');
    if (!cards.length) return;
    const oldEmpty = document.getElementById('searchEmptyState');
    if (oldEmpty) oldEmpty.remove();
    if (!q) { cards.forEach(c => c.style.display = ''); bar.style.display = 'none'; return; }
    let visible = 0;
    cards.forEach(card => {
        const hit = (card.dataset.searchJudul||'').includes(q)
                 || (card.dataset.searchProjek||'').includes(q)
                 || (card.dataset.searchAssignee||'').includes(q);
        card.style.display = hit ? '' : 'none';
        if (hit) visible++;
    });
    bar.style.display = 'flex';
    barTx.textContent = visible
        ? `Menampilkan ${visible} task untuk "${query}"`
        : `Tidak ada task yang cocok dengan "${query}"`;
    if (visible === 0) {
        const grid = document.getElementById('taskGrid');
        const div  = document.createElement('div');
        div.id = 'searchEmptyState'; div.className = 'approval-empty';
        div.innerHTML = `<i class="bx bx-search-alt"></i><p>Tidak ada task cocok dengan "<strong>${escHtml(query)}</strong>"</p><small>Coba kata kunci lain atau <button onclick="clearSearch()" style="background:none;border:none;color:var(--blue);font-weight:700;cursor:pointer;padding:0;font-size:inherit;">reset pencarian</button>.</small>`;
        grid.appendChild(div);
    }
}
function clearSearch() {
    const input = document.getElementById('searchInput');
    input.value = '';
    document.getElementById('searchClear').style.display = 'none';
    document.getElementById('searchResultBar').style.display = 'none';
    const e = document.getElementById('searchEmptyState'); if (e) e.remove();
    document.querySelectorAll('#taskGrid .task-card').forEach(c => c.style.display = '');
    input.focus();
}

/* ── HELPERS ── */
function escHtml(s) { return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
function fmtDate(str) {
    if (!str) return '—';
    const d = new Date(str+'T00:00:00');
    if (isNaN(d.getTime())) return '—';
    const mn=['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
    return `${d.getDate()} ${mn[d.getMonth()]} ${d.getFullYear()}`;
}
function fmtDateTime(str) {
    if (!str) return '—';
    const d = new Date(str.replace(' ','T'));
    if (isNaN(d.getTime())) return '—';
    const mn=['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
    return `${d.getDate()} ${mn[d.getMonth()]} ${d.getFullYear()}, ${String(d.getHours()).padStart(2,'0')}:${String(d.getMinutes()).padStart(2,'0')}`;
}
function isImgFile(name) { return /\.(jpg|jpeg|png|gif|webp|svg)$/i.test(name||''); }
function docIcon(name) {
    if (/\.pdf$/i.test(name)) return 'bx-file-pdf';
    if (/\.docx?$/i.test(name)) return 'bx-file-doc';
    if (/\.xlsx?$/i.test(name)) return 'bxs-spreadsheet';
    return 'bx-file-blank';
}
function buildGallery(fotos, container) {
    if (!fotos||!fotos.length) { container.innerHTML='<span style="font-size:12px;color:var(--g400);font-style:italic;">Belum ada lampiran.</span>'; return; }
    container.innerHTML = fotos.map(f => {
        const nama = f.nama||f.url||'';
        if (isImgFile(nama)) return `<img class="gallery-img" src="${escHtml(f.url)}" onclick="window.open('${escHtml(f.url)}','_blank')">`;
        return `<a class="gallery-doc" href="${escHtml(f.url)}" target="_blank"><i class="bx ${docIcon(nama)}"></i>${escHtml(nama.split('/').pop().substring(0,14))}</a>`;
    }).join('');
}
async function apiFetch(url, method='GET', body=null) {
    const opts = {method, headers:{'X-CSRF-TOKEN':CSRF_TOKEN,'Accept':'application/json'}};
    if (body) { opts.headers['Content-Type']='application/json'; opts.body=JSON.stringify(body); }
    return (await fetch(url, opts)).json();
}
async function apiUpload(url, fd) {
    return (await fetch(url, {method:'POST',headers:{'X-CSRF-TOKEN':CSRF_TOKEN,'Accept':'application/json'},body:fd})).json();
}
function removeCard(id) {
    const c = document.getElementById('card-'+id);
    if (c) { c.style.transition='opacity .3s,transform .3s'; c.style.opacity='0'; c.style.transform='scale(0.95)'; setTimeout(()=>{ c.remove(); checkEmptyGrid(); },300); }
}
function checkEmptyGrid() {
    const grid = document.getElementById('taskGrid');
    if (!grid||grid.querySelectorAll('.task-card').length) return;
    const msgs={menunggu:'Tidak ada task yang menunggu approval saat ini.',revisi:'Tidak ada task dengan status Revisi saat ini.',riwayat:'Belum ada task yang pernah diapprove.'};
    grid.innerHTML=`<div class="approval-empty"><i class="bx bx-task-x"></i><p>${msgs[CURRENT_TAB]||''}</p></div>`;
}
function updateBadgeCount(tabName, delta) {
    document.querySelectorAll('.approval-tab').forEach(tab => {
        if ((tab.getAttribute('href')||'').includes('tab='+tabName)) {
            const b=tab.querySelector('.tab-badge');
            if (b) b.textContent=Math.max(0,(parseInt(b.textContent)||0)+delta);
        }
    });
}
function getModal(id) { return bootstrap.Modal.getOrCreateInstance(document.getElementById(id)); }

/* ── MODAL DETAIL ── */
function showDetail(task) {
    _currentTaskData = task;
    const hdr = document.getElementById('dt_header_el');
    hdr.className = 'modal-grad-header'+(task.status_akhir==='approved'?' teal':task.status_akhir==='revisi'?' amber':'');
    document.getElementById('dt_judul').textContent = task.judul_tugas;
    document.getElementById('dt_sub').textContent   = `Project: ${task.nama_projek} · Level: ${task.level} · W: ${task.weight}`;
    let hdrBadges = '';
    if (task.pernah_approved && task.status_akhir !== 'approved')
        hdrBadges += `<span style="display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:3px 10px;border-radius:99px;background:rgba(255,255,255,.2);color:white;"><i class="bx bx-check-shield" style="font-size:12px;"></i>Pernah Approved</span>`;
    if (task.status_akhir === 'approved')
        hdrBadges += `<span style="display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:3px 10px;border-radius:99px;background:rgba(255,255,255,.2);color:white;"><i class="bx bx-check-circle" style="font-size:12px;"></i>Sudah Approved</span>`;
    document.getElementById('dt_header_badges').innerHTML = hdrBadges;
    const apvBox = document.getElementById('dt_approved_box');
    if (task.status_akhir === 'approved') {
        apvBox.style.display = 'flex';
        document.getElementById('dt_approved_date').textContent = task.diubah_pada ? 'Diapprove pada: '+fmtDateTime(task.diubah_pada) : '';
    } else { apvBox.style.display = 'none'; }
    const SP_C = {'draft':'#6B7280','To Do':'#6B7280','In Progress':'#D97706','done':'#059669'};
    const SP_B = {'draft':'#F3F4F6','To Do':'#F3F4F6','In Progress':'#FEF3C7','done':'#D1FAE5'};
    let badges = `<span style="display:inline-flex;align-items:center;padding:4px 12px;border-radius:99px;font-size:11px;font-weight:800;background:${task.level==='mudah'?'var(--green-light)':task.level==='medium'?'var(--amber-light)':'var(--red-light)'};color:${task.level==='mudah'?'#059669':task.level==='medium'?'#B45309':'#DC2626'};">${task.level}</span>`;
    badges += `<span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:99px;font-size:11px;font-weight:800;background:${SP_B[task.status_progress]||'#F3F4F6'};color:${SP_C[task.status_progress]||'#6B7280'};"><span style="width:7px;height:7px;border-radius:50%;background:currentColor;"></span>${task.status_progress}</span>`;
    if (task.status_akhir) {
        const bgM={review:'var(--purple-light)',revisi:'var(--yellow-light)',approved:'var(--green-light)'};
        const clM={review:'#7C3AED',revisi:'#A16207',approved:'#059669'};
        const txM={review:'Review PM',revisi:'Revisi PM',approved:'Approved PM'};
        badges += `<span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:99px;font-size:11px;font-weight:800;background:${bgM[task.status_akhir]};color:${clM[task.status_akhir]};">${txM[task.status_akhir]||task.status_akhir}</span>`;
    }
    document.getElementById('dt_badges').innerHTML = badges;
    document.getElementById('dt_projek').textContent = task.nama_projek;
    document.getElementById('dt_assignee').innerHTML = `<div style="display:flex;align-items:center;gap:8px;"><div class="tc-avatar">${escHtml(task.avatar)}</div><div><div style="font-size:14px;font-weight:700;color:var(--g900);">${escHtml(task.nama_assignee)}</div>${task.jabatan?`<div style="font-size:12px;color:var(--g500);">${escHtml(task.jabatan)}</div>`:''}</div></div>`;
    document.getElementById('dt_deskripsi').textContent = task.deskripsi_tugas||'(Tidak ada deskripsi)';
    document.getElementById('dt_mulai').textContent   = fmtDate(task.tanggal_mulai);
    document.getElementById('dt_tenggat').textContent = fmtDate(task.tenggat_waktu);
    document.getElementById('dt_selesai').textContent = fmtDate(task.tanggal_selesai)||'Belum selesai';
    document.getElementById('dt_weight').innerHTML    = `<span style="background:var(--g100);padding:3px 10px;border-radius:6px;font-weight:700;">${task.weight} poin</span>`;
    document.getElementById('dt_status_progress').innerHTML = `<span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:6px;font-size:12px;font-weight:700;background:${SP_B[task.status_progress]||'#F3F4F6'};color:${SP_C[task.status_progress]||'#6B7280'};">${task.status_progress}</span>`;
    const saT={review:'Review PM',revisi:'Revisi PM',approved:'Approved PM'};
    document.getElementById('dt_status_akhir').innerHTML = task.status_akhir
        ? `<span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:6px;font-size:12px;font-weight:700;background:${({review:'var(--purple-light)',revisi:'var(--yellow-light)',approved:'var(--green-light)'})[task.status_akhir]};color:${({review:'#7C3AED',revisi:'#A16207',approved:'#059669'})[task.status_akhir]};">${saT[task.status_akhir]||task.status_akhir}</span>`
        : `<span style="font-size:12px;color:var(--g400);font-style:italic;">Belum dinilai</span>`;
    buildGallery((task.foto||[]).filter(f=>f.tipe!=='hasil'), document.getElementById('dt_foto_brief'));
    buildGallery((task.foto||[]).filter(f=>f.tipe==='hasil'), document.getElementById('dt_foto_hasil'));
    const ab = document.getElementById('dt_action_btns');
    if (task.status_akhir === 'review') {
        ab.innerHTML = `
            <button type="button" class="modal-btn" style="border:1.5px solid #FECACA;background:#FEF2F2;color:#DC2626;"
                onclick="getModal('modalDetailTask').hide();openEditModal(${task.id_tugas},'${task.judul_tugas.replace(/'/g,"\\'")}',_currentTaskData,'revisi')">
                <i class="bx bx-undo"></i>Revisi
            </button>
            <button type="button" class="modal-btn success"
                onclick="getModal('modalDetailTask').hide();confirmApprove(${task.id_tugas},'${task.judul_tugas.replace(/'/g,"\\'")}')">
                <i class="bx bx-check-circle"></i>Approve
            </button>`;
    } else if (task.status_akhir === 'revisi') {
        ab.innerHTML = `
            <button type="button" class="modal-btn amber-btn"
                onclick="getModal('modalDetailTask').hide();openEditModal(${task.id_tugas},'${task.judul_tugas.replace(/'/g,"\\'")}',_currentTaskData,'edit')">
                <i class="bx bx-edit"></i>Edit Task
            </button>`;
    } else { ab.innerHTML = ''; }
    getModal('modalDetailTask').show();
}

/* ── EDIT MODAL ── */
function openEditModal(id, judul, taskData, mode) {
    _currentTaskData = taskData; _editMode = mode||'revisi';
    _editBriefFiles=[]; _deletedFotoIds=[];
    document.getElementById('edit_brief_preview').innerHTML='';
    document.getElementById('edit_error_box').style.display='none';
    document.getElementById('inputEditBrief').value='';
    document.getElementById('revisi_catatan_edit').value='';
    document.getElementById('edit_id_tugas').value  = taskData.id_tugas;
    document.getElementById('edit_id_projek').value = taskData.id_projek;
    document.getElementById('edit_mode').value      = _editMode;
    document.getElementById('edit_judul_tugas').value    = taskData.judul_tugas||'';
    document.getElementById('edit_deskripsi_tugas').value= taskData.deskripsi_tugas||'';
    document.getElementById('edit_level').value         = taskData.level||'mudah';
    document.getElementById('edit_tanggal_mulai').value = taskData.tanggal_mulai||'';
    document.getElementById('edit_tenggat_waktu').value = taskData.tenggat_waktu||'';
    const timSel = document.getElementById('edit_id_tim');
    timSel.innerHTML = '<option value="">-- Pilih Anggota --</option>';
    (taskData.tim_list||[]).forEach(t => {
        const o=document.createElement('option'); o.value=t.id_tim; o.textContent=t.nama;
        if (t.id_tim==taskData.id_tim) o.selected=true;
        timSel.appendChild(o);
    });
    if (_editMode==='revisi') {
        document.getElementById('edit_modal_title').innerHTML='<i class="bx bx-undo" style="margin-right:6px;"></i>Revisi Task';
        document.getElementById('edit_sub').textContent=`Kirim revisi: ${taskData.judul_tugas}`;
        document.getElementById('btn_save_edit_label').textContent='Simpan & Kirim Revisi';
        document.getElementById('edit_catatan_label').textContent='Catatan Revisi';
        document.getElementById('edit_catatan_hint').innerHTML='<i class="bx bx-info-circle"></i> Catatan dikirim ke karyawan sebagai notifikasi.';
        document.getElementById('edit_mode_badge').innerHTML=`<span class="edit-mode-indicator"><i class="bx bx-send" style="font-size:11px;"></i>Task akan dikembalikan ke karyawan</span>`;
    } else {
        document.getElementById('edit_modal_title').innerHTML='<i class="bx bx-edit-alt" style="margin-right:6px;"></i>Edit Task';
        document.getElementById('edit_sub').textContent=`Edit: ${taskData.judul_tugas}`;
        document.getElementById('btn_save_edit_label').textContent='Simpan Perubahan';
        document.getElementById('edit_catatan_label').textContent='Keterangan Tambahan';
        document.getElementById('edit_catatan_hint').innerHTML='<i class="bx bx-info-circle"></i> Task tetap berstatus Revisi setelah disimpan.';
        document.getElementById('edit_mode_badge').innerHTML=taskData.pernah_approved
            ? `<span class="edit-mode-indicator"><i class="bx bx-check-shield" style="font-size:11px;"></i>Task ini pernah diapprove sebelumnya</span>`
            : `<span class="edit-mode-indicator"><i class="bx bx-edit" style="font-size:11px;"></i>Status tetap Revisi setelah disimpan</span>`;
    }
    renderExistingBrief((taskData.foto||[]).filter(f=>f.tipe!=='hasil'));
    getModal('modalEditTask').show();
}
function renderExistingBrief(fotos) {
    const c=document.getElementById('edit_existing_brief'); c.innerHTML='';
    if (!fotos||!fotos.length){c.innerHTML='<span style="font-size:12px;color:var(--g400);font-style:italic;">Belum ada foto brief.</span>';return;}
    fotos.forEach(f=>{
        if (_deletedFotoIds.includes(f.id)) return;
        const w=document.createElement('div'); w.className='existing-foto-item'; w.id='existing-foto-'+f.id;
        const nama=(f.nama||f.url||'').split('/').pop();
        w.innerHTML=isImgFile(nama)
            ?`<img src="${escHtml(f.url)}" onclick="window.open('${escHtml(f.url)}','_blank')"><button class="delete-foto-btn" onclick="markDeleteFoto(${f.id},this.closest('.existing-foto-item'))">&times;</button><span>${escHtml(nama.substring(0,14))}</span>`
            :`<div style="width:80px;height:60px;background:var(--g100);border-radius:6px;border:1px solid var(--g200);display:flex;align-items:center;justify-content:center;cursor:pointer;" onclick="window.open('${escHtml(f.url)}','_blank')"><i class="bx ${docIcon(nama)}" style="font-size:24px;color:var(--blue);"></i></div><button class="delete-foto-btn" onclick="markDeleteFoto(${f.id},this.closest('.existing-foto-item'))">&times;</button><span>${escHtml(nama.substring(0,14))}</span>`;
        c.appendChild(w);
    });
}
function markDeleteFoto(id,el){_deletedFotoIds.push(id);if(el){el.style.opacity='0';setTimeout(()=>el.remove(),200);}}
function handleEditBriefChange(input){Array.from(input.files).forEach(f=>_editBriefFiles.push(f));input.value='';renderEditBriefPreview();}
function handleEditBriefDrop(e){e.preventDefault();document.getElementById('editBriefZone').classList.remove('hover');Array.from(e.dataTransfer.files).forEach(f=>_editBriefFiles.push(f));renderEditBriefPreview();}
function renderEditBriefPreview(){
    document.getElementById('edit_brief_preview').innerHTML=_editBriefFiles.map((f,i)=>{
        const isImg=f.type.startsWith('image/'),url=isImg?URL.createObjectURL(f):null;
        return `<div class="upload-preview-item">${isImg?`<img src="${url}">`:`<div style="width:80px;height:60px;background:var(--g100);border-radius:6px;border:1.5px dashed var(--blue);display:flex;align-items:center;justify-content:center;"><i class="bx ${docIcon(f.name)}" style="font-size:24px;color:var(--blue);"></i></div>`}<button class="rm-upload-btn" onclick="removeEditBriefFile(${i})">&times;</button><span style="font-size:9px;color:var(--g500);max-width:80px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;text-align:center;">${escHtml(f.name.substring(0,14))}</span></div>`;
    }).join('');
}
function removeEditBriefFile(idx){_editBriefFiles.splice(idx,1);renderEditBriefPreview();}

/* ── SUBMIT EDIT ── */
async function submitEdit() {
    const id=document.getElementById('edit_id_tugas').value;
    const projek=document.getElementById('edit_id_projek').value;
    const mode=document.getElementById('edit_mode').value;
    const judul=document.getElementById('edit_judul_tugas').value.trim();
    const timId=document.getElementById('edit_id_tim').value;
    const level=document.getElementById('edit_level').value;
    const mulai=document.getElementById('edit_tanggal_mulai').value;
    const tenggat=document.getElementById('edit_tenggat_waktu').value;
    const deskrip=document.getElementById('edit_deskripsi_tugas').value.trim();
    const catatan=document.getElementById('revisi_catatan_edit').value.trim();
    const errBox=document.getElementById('edit_error_box');
    errBox.style.display='none';
    if (!judul){errBox.textContent='Nama task tidak boleh kosong.';errBox.style.display='block';return;}
    if (!timId){errBox.textContent='Pilih penanggung jawab terlebih dahulu.';errBox.style.display='block';return;}
    const btn=document.getElementById('btn_save_edit');
    btn.disabled=true; btn.innerHTML='<i class="bx bx-loader-alt bx-spin"></i>Menyimpan...';
    try {
        const d=await apiFetch(`/projek/${projek}/task/${id}`,'PUT',{judul_tugas:judul,deskripsi_tugas:deskrip,id_tim:timId,level,tanggal_mulai:mulai||null,tenggat_waktu:tenggat||null});
        if (!d.success){errBox.textContent=d.message||Object.values(d.errors||{}).flat().join(' ');errBox.style.display='block';return;}
        for (const fotoId of _deletedFotoIds) await apiFetch(`/projek/${projek}/task/${id}/foto/${fotoId}`,'DELETE');
        if (_editBriefFiles.length){const fd=new FormData();_editBriefFiles.forEach(f=>fd.append('foto[]',f));fd.append('tipe','brief');await apiUpload(`/projek/${projek}/task/${id}/foto`,fd);}
        if (mode==='revisi'){
            const r=await apiFetch(`/approval-task/${id}/revisi`,'POST',{catatan_revisi:catatan});
            getModal('modalEditTask').hide();
            if (r.success){removeCard(id);updateBadgeCount('menunggu',-1);updateBadgeCount('revisi',1);Swal.fire({icon:'success',title:'Berhasil!',text:'Task diperbarui dan dikembalikan untuk revisi.',confirmButtonColor:'#4F46E5',timer:2500,timerProgressBar:true,showConfirmButton:false});}
        } else {
            getModal('modalEditTask').hide();
            const cardEl=document.getElementById('card-'+id);
            if (cardEl){const t=cardEl.querySelector('.tc-title');if(t)t.textContent=judul;}
            Swal.fire({icon:'success',title:'Tersimpan!',text:'Perubahan task berhasil disimpan.',confirmButtonColor:'#F59E0B',timer:2000,timerProgressBar:true,showConfirmButton:false});
        }
    } catch(e){errBox.textContent='Koneksi bermasalah, coba lagi.';errBox.style.display='block';}
    finally{btn.disabled=false;btn.innerHTML=`<i class="bx bx-save"></i><span id="btn_save_edit_label">${mode==='revisi'?'Simpan & Kirim Revisi':'Simpan Perubahan'}</span>`;}
}

/* ── APPROVE ── */
function confirmApprove(id, judul) {
    document.getElementById('approve_id_tugas').value=id;
    document.getElementById('approve_judul_tugas').value=judul;
    document.getElementById('approve_judul').textContent=`"${judul.substring(0,30)}${judul.length>30?'...':''}"`;
    getModal('modalApproveConfirm').show();
}
async function submitApprove() {
    const id=document.getElementById('approve_id_tugas').value;
    getModal('modalApproveConfirm').hide();
    Swal.fire({title:'Memproses...',allowOutsideClick:false,didOpen:()=>Swal.showLoading()});
    try {
        const data=await apiFetch(`/approval-task/${id}/approve`,'POST',{});
        if (data.success){removeCard(id);updateBadgeCount('menunggu',-1);updateBadgeCount('riwayat',1);Swal.fire({icon:'success',title:'Approved!',text:'Task berhasil di-Approve dan masuk ke Riwayat.',confirmButtonColor:'#4F46E5',timer:2500,timerProgressBar:true,showConfirmButton:false});}
        else Swal.fire({icon:'error',title:'Gagal',text:data.message||'Terjadi kesalahan.',confirmButtonColor:'#4F46E5'});
    } catch(e){Swal.fire({icon:'error',title:'Error',text:'Koneksi gagal, coba lagi.',confirmButtonColor:'#4F46E5'});}
}

/* ── INIT ── */
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.session-alert').forEach(a => {
        setTimeout(()=>{a.style.transition='opacity .4s,transform .4s';a.style.opacity='0';a.style.transform='translateY(-8px)';setTimeout(()=>a.remove(),400);},3500);
    });
    document.getElementById('editBriefZone')?.addEventListener('click',()=>document.getElementById('inputEditBrief').click());
    document.addEventListener('keydown', e => {
        if ((e.ctrlKey||e.metaKey)&&e.key==='f') {
            const si=document.getElementById('searchInput');
            if(si){e.preventDefault();si.focus();si.select();}
        }
        if (e.key==='Escape') {
            if (document.getElementById('customSelectWrap').classList.contains('open')) { closeCustomSelect(); return; }
            const si=document.getElementById('searchInput');
            if(document.activeElement===si&&si.value) clearSearch();
        }
    });
});
window.getModal = id => bootstrap.Modal.getOrCreateInstance(document.getElementById(id));
</script>
@endpush