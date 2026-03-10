{{--
|--------------------------------------------------------------------------
| partials/task-export.blade.php
|--------------------------------------------------------------------------
| Partial export PDF untuk halaman Kelola Task.
| Di-include dari kelola-task.blade.php:
|
|   @include('partials.task-export', [
|       'projek'      => $projek,
|       'pmNama'      => $pmNama,
|       'tglMulai'    => $tglMulai,
|       'tglAkhir'    => $tglAkhir,
|       'stats'       => $stats,
|       'perusahaan'  => optional($projek->perusahaan)->nama_perusahaan ?? '—',
|   ])
--}}
@push('styles')
<style>
/* ══ PDF PREVIEW MODAL ══ */
#pdfPreviewModal {
    position: fixed; inset: 0; z-index: 10000;
    display: none; align-items: center; justify-content: center;
}
#pdfPreviewModal.open { display: flex; }
#pdfPreviewBackdrop {
    position: absolute; inset: 0;
    background: rgba(0,0,0,.45);
    backdrop-filter: blur(2px);
}
#pdfPreviewBox {
    position: relative; z-index: 1; background: white; border-radius: 8px;
    width: min(98vw, 960px); max-height: 94vh;
    display: flex; flex-direction: column;
    box-shadow: 0 16px 48px rgba(0,0,0,.2); overflow: hidden;
    border: 1px solid #D1D5DB;
}
#pdfPreviewToolbar {
    background: #1E2A3A;
    padding: 12px 20px; display: flex; align-items: center; gap: 10px; flex-shrink: 0;
}
#pdfPreviewToolbar h6 { color: #F9FAFB; font-size: 14px; font-weight: 600; margin: 0; flex: 1; }
.pdf-toolbar-btn {
    padding: 6px 14px; border-radius: 5px; border: 1px solid rgba(255,255,255,.25);
    background: transparent; color: #D1D5DB; font-size: 12px; font-weight: 600;
    cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all .15s;
}
.pdf-toolbar-btn:hover { background: rgba(255,255,255,.1); color: white; }
.pdf-toolbar-btn.print-btn { background: white; color: #1E2A3A; border-color: white; }
.pdf-toolbar-btn.print-btn:hover { background: #F3F4F6; }
#pdfPreviewContent { flex: 1; overflow-y: auto; padding: 24px; background: #F3F4F6; }

/* ══════════════════════════════════════
   PDF PAGE STYLES
══════════════════════════════════════ */
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

{{-- ════ HTML: PDF Preview Modal ════ --}}
<div id="pdfPreviewModal">
    <div id="pdfPreviewBackdrop" onclick="closePdfPreview()"></div>
    <div id="pdfPreviewBox">
        <div id="pdfPreviewToolbar">
            <h6>&#128196; Preview Laporan — {{ $projek->nama_projek }}</h6>
            <button class="pdf-toolbar-btn print-btn" onclick="printPDF()">
                &#128424; Cetak / Simpan PDF
            </button>
            <button class="pdf-toolbar-btn" onclick="closePdfPreview()">
                &#10005; Tutup
            </button>
        </div>
        <div id="pdfPreviewContent">
            {{-- Diisi oleh exportPDF() --}}
        </div>
    </div>
</div>

@push('scripts')
<script>
'use strict';

/* ═══════════════════════════════════════════
   DATA PROYEK (dari Blade → JS)
═══════════════════════════════════════════ */
const EXPORT_PROJEK = {
    id:         {{ $projek->id_projek }},
    nama:       @json($projek->nama_projek),
    deskripsi:  @json($projek->deskripsi ?? ''),
    pm:         @json($pmNama),
    mulai:      @json($tglMulai ?? '—'),
    akhir:      @json($tglAkhir ?? '—'),
    kategori:   @json(optional($projek->kategoriProjek)->nama_kategori ?? '—'),
    perusahaan: @json(optional($projek->perusahaan)->nama_perusahaan ?? '—'),
    pembuat:    'PT Kawan Kita Solusindo',
};

/* ═══════════════════════════════════════════
   LABEL & CLASS STATUS
═══════════════════════════════════════════ */
const SP_LABEL_PDF = {
    'draft':       'Draft',
    'To Do':       'Belum Pengerjaan',
    'In Progress': 'Proses Pengerjaan',
    'done':        'Selesai',
};
const SA_LABEL_PDF = {
    'review':   'Review PM',
    'revisi':   'Revisi PM',
    'approved': 'Disetujui',
};
const SP_BADGE_CLASS = {
    'draft':       'badge-draft',
    'To Do':       'badge-todo',
    'In Progress': 'badge-inprogress',
    'done':        'badge-done',
};
const SA_BADGE_CLASS = {
    'review':   'badge-review',
    'revisi':   'badge-revisi',
    'approved': 'badge-approved',
};
const PIE_COLORS_P = {
    'done':        '#3B7DD8',
    'In Progress': '#E8A838',
    'To Do':       '#9CA3AF',
};
const SA_PIE_COLORS = {
    'approved': '#22C55E',
    'review':   '#8B5CF6',
    'revisi':   '#F59E0B',
    'null':     '#9CA3AF',
};

/* ═══════════════════════════════════════════
   HELPER UMUM
═══════════════════════════════════════════ */
function escHtml(s) {
    if (!s) return '';
    return String(s)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}
function isImageFile(n) { return /\.(jpg|jpeg|png|gif|webp|bmp|svg)$/i.test(n || ''); }
function _docExt(f)      { return ((f||'').split('.').pop()||'').toLowerCase(); }
function _docEmoji(f) {
    const e = _docExt(f);
    return { pdf:'📄', doc:'📝', docx:'📝', xls:'📊', xlsx:'📊', ppt:'📋', pptx:'📋' }[e] || '📎';
}
function _fmtDateLong(s) {
    if (!s) return '—';
    let clean = String(s).trim();
    if (clean.includes('T')) clean = clean.split('T')[0];
    if (!/^\d{4}-\d{2}-\d{2}$/.test(clean)) return '—';
    const p = clean.split('-');
    const d = new Date(parseInt(p[0]), parseInt(p[1])-1, parseInt(p[2]));
    if (isNaN(d.getTime())) return '—';
    const mn = ['Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember'];
    return `${parseInt(p[2])} ${mn[d.getMonth()]} ${parseInt(p[0])}`;
}

/* ═══════════════════════════════════════════════════════════════
   _calcStats
   - Draft DIKELUARKAN dari semua perhitungan
   - Done + Approved = status_progress "done" DAN status_akhir "approved"
   - Persentase = weight(done+approved) / weight(total non-draft) × 100
═══════════════════════════════════════════════════════════════ */
function _calcStats() {
    const nonDraftTasks = tasks.filter(t => t.status_progress !== 'draft');
    const W = t => (t.weight > 0 ? t.weight : 1);
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
    const appr = nonDraftTasks.filter(
        t => t.status_progress === 'done' && t.status_akhir === 'approved'
    ).length;
    const approvedWeight = nonDraftTasks
        .filter(t => t.status_progress === 'done' && t.status_akhir === 'approved')
        .reduce((s,t) => s + W(t), 0);
    const pct = totalWeight > 0 ? Math.round((approvedWeight / totalWeight) * 100) : 0;
    return {
        tot, done, prog, todo,
        wDone, wProg, wTodo, totalWeight,
        appr, approvedWeight, pct,
        saApproved, saRevisi, saReview, saNull,
        wSaApproved, wSaRevisi, wSaReview,
    };
}

/* ═══════════════════════════════════════════
   PDF BUILDER
═══════════════════════════════════════════ */
function _buildPdfForTask() {
    const filteredTasks = tasks.filter(t => t.status_progress !== 'draft');
    const s   = _calcStats();
    const now = new Date();
    const nowFmt = _fmtDateLong(now.toISOString().split('T')[0]);
    const docNum = `DOC-${EXPORT_PROJEK.id}-${now.getFullYear()}${String(now.getMonth()+1).padStart(2,'0')}`;
    const perus  = EXPORT_PROJEK.perusahaan !== '—' ? EXPORT_PROJEK.perusahaan : EXPORT_PROJEK.pembuat;

    /* ── Letterhead ── */
    let html = `<div class="pdf-wrap">
    <div class="pdf-letterhead">
        <div class="pdf-letterhead-left">
            <div class="doc-type">Laporan Manajemen Task</div>
            <div class="doc-title">${escHtml(EXPORT_PROJEK.nama)}</div>
            <div class="doc-sub">${escHtml(perus)}</div>
        </div>
        <div class="pdf-letterhead-right">
            <div class="doc-num">${docNum}</div>
            <div class="doc-date">Diterbitkan: ${nowFmt}</div>
        </div>
    </div>
    <hr class="pdf-rule">`;

    /* ── Info Proyek ── */
    html += `<div class="pdf-project-info">
        <div class="pdf-info-col">
            <div class="pdf-info-row"><span class="pdf-info-lbl">Project Manager</span><span class="pdf-info-val">${escHtml(EXPORT_PROJEK.pm)}</span></div>
            <div class="pdf-info-row"><span class="pdf-info-lbl">Tanggal Mulai</span><span class="pdf-info-val">${escHtml(EXPORT_PROJEK.mulai)}</span></div>
            <div class="pdf-info-row"><span class="pdf-info-lbl">Target Selesai</span><span class="pdf-info-val">${escHtml(EXPORT_PROJEK.akhir)}</span></div>
            <div class="pdf-info-row"><span class="pdf-info-lbl">Kategori</span><span class="pdf-info-val">${escHtml(EXPORT_PROJEK.kategori)}</span></div>
        </div>
        <div class="pdf-info-col">
            <div class="pdf-info-row"><span class="pdf-info-lbl">Perusahaan</span><span class="pdf-info-val">${escHtml(EXPORT_PROJEK.perusahaan !== '—' ? EXPORT_PROJEK.perusahaan : '—')}</span></div>
            <div class="pdf-info-row"><span class="pdf-info-lbl">Pembuat Sistem</span><span class="pdf-info-val">${escHtml(EXPORT_PROJEK.pembuat)}</span></div>
            <div class="pdf-info-row"><span class="pdf-info-lbl">Deskripsi</span><span class="pdf-info-val">${escHtml(EXPORT_PROJEK.deskripsi || 'Tidak ada deskripsi.')}</span></div>
        </div>
    </div>`;

    /* ── Statistik ── */
    const progressRows = [
        { label:'Selesai (Done)',    n:s.done, w:s.wDone, key:'done'        },
        { label:'Proses Pengerjaan', n:s.prog, w:s.wProg, key:'In Progress' },
        { label:'Belum Pengerjaan',  n:s.todo, w:s.wTodo, key:'To Do'       },
    ];
    const wNull = Math.max(0, s.totalWeight - (s.wSaApproved + s.wSaRevisi + s.wSaReview));
    const saRows = [
        { label:'Disetujui (Approved)', n:s.saApproved, w:s.wSaApproved, key:'approved', color:'#166534', bg:'#F0FDF4', border:'#BBF7D0' },
        { label:'Review PM',            n:s.saReview,   w:s.wSaReview,   key:'review',   color:'#5B21B6', bg:'#F5F3FF', border:'#DDD6FE' },
        { label:'Revisi PM',            n:s.saRevisi,   w:s.wSaRevisi,   key:'revisi',   color:'#92400E', bg:'#FFFBEB', border:'#FDE68A' },
        { label:'Belum Dinilai',        n:s.saNull,     w:wNull,         key:'null',     color:'#6B7280', bg:'#F9FAFB', border:'#E5E7EB' },
    ].filter(r => r.n > 0);

    const legendPie1 = progressRows.filter(r => r.n > 0).map(r => {
        const pct = s.tot > 0 ? Math.round((r.n/s.tot)*100) : 0;
        return `<div class="pdf-legend-item"><div class="pdf-legend-dot" style="background:${PIE_COLORS_P[r.key]};"></div><span>${r.label}: <strong>${r.n}</strong> (${pct}%)</span></div>`;
    }).join('');

    const legendPie2 = saRows.map(r => {
        const pct = s.tot > 0 ? Math.round((r.n/s.tot)*100) : 0;
        return `<div class="pdf-legend-item"><div class="pdf-legend-dot" style="background:${SA_PIE_COLORS[r.key]};"></div><span>${r.label}: <strong>${r.n}</strong> (${pct}%)</span></div>`;
    }).join('');

    html += `<div class="pdf-section-header"><span>Statistik &amp; Distribusi Status</span></div>
    <div style="padding:16px 28px;background:white;border-bottom:1px solid #E5E7EB;">
        <div style="display:flex;gap:16px;margin-bottom:16px;">
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
                        return `<tr>
                            <td style="display:flex;align-items:center;gap:6px;">
                                <span style="display:inline-block;width:8px;height:8px;border-radius:2px;background:${PIE_COLORS_P[r.key]};flex-shrink:0;"></span>${r.label}
                            </td>
                            <td style="text-align:center;font-weight:700;color:#1E2A3A;">${r.w}</td>
                            <td style="text-align:center;font-weight:700;color:#374151;">${s.totalWeight > 0 ? wpct+'%' : '—'}</td>
                        </tr>`;
                    }).join('')}
                    <tr class="pdf-stats-total-row"><td>Total</td><td style="text-align:center;">${s.totalWeight}</td><td style="text-align:center;">100%</td></tr>
                    </tbody>
                </table>
            </div>
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
        <div style="display:flex;gap:16px;align-items:flex-start;">
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;margin-bottom:6px;font-family:'Segoe UI',Arial,sans-serif;text-align:center;">Distribusi Status Progress</div>
                <canvas id="pdfPieChart" width="130" height="130"></canvas>
                <div style="margin-top:8px;width:100%;font-family:'Segoe UI',Arial,sans-serif;">${legendPie1}</div>
            </div>
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6B7280;margin-bottom:6px;font-family:'Segoe UI',Arial,sans-serif;text-align:center;">Distribusi Status Penilaian PM</div>
                <canvas id="pdfPieChartSA" width="130" height="130"></canvas>
                <div style="margin-top:8px;width:100%;font-family:'Segoe UI',Arial,sans-serif;">${legendPie2}</div>
            </div>
        </div>
        <div class="pdf-completion-block" style="margin-top:14px;">
            <div class="pdf-completion-label">Tingkat Penyelesaian Proyek (Done + Approved PM / Total)</div>
            <div class="pdf-completion-nums">${s.pct}% &mdash; ${s.appr} dari ${s.tot} task done &amp; disetujui PM (Weight: ${s.approvedWeight}/${s.totalWeight})</div>
            <div class="pdf-bar-bg"><div class="pdf-bar-fill" style="width:${s.pct}%;"></div></div>
        </div>
    </div>`;

    /* ── Detail Task ── */
    html += `<div class="pdf-section-header"><span>Detail Task (${filteredTasks.length} task)</span></div>
    <div class="pdf-tasks-wrap">`;

    if (!filteredTasks.length) {
        html += `<div style="padding:20px;text-align:center;color:#9CA3AF;font-size:12px;font-family:'Segoe UI',Arial,sans-serif;">Belum ada task aktif dalam proyek ini.</div>`;
    } else {
        filteredTasks.forEach((t, i) => {
            const member   = TIM_LIST.find(m => m.id_tim === t.id_tim);
            const assignee = member ? (member.jabatan ? `${member.nama} (${member.jabatan})` : member.nama) : '—';
            const spLabel  = SP_LABEL_PDF[t.status_progress] || t.status_progress || '—';
            const spClass  = SP_BADGE_CLASS[t.status_progress] || 'badge-draft';
            const saLabel  = t.status_akhir ? (SA_LABEL_PDF[t.status_akhir] || t.status_akhir) : null;
            const saClass  = t.status_akhir ? (SA_BADGE_CLASS[t.status_akhir] || 'badge-draft') : '';
            const hasilF   = (t.foto || []).filter(f => f.tipe === 'hasil');
            const isDoneApproved = t.status_progress === 'done' && t.status_akhir === 'approved';

            let hasilHtml = '';
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
                    <div class="pdf-task-no ${isDoneApproved ? 'approved' : ''}">${i+1}</div>
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
                        <div class="pdf-meta-item"><div class="lbl">Tanggal Selesai</div><div class="val" style="${isDoneApproved ? 'color:#166534;font-weight:700;' : 'color:#9CA3AF;'}">${t.tanggal_selesai ? _fmtDateLong(t.tanggal_selesai) : '—'}</div></div>
                    </div>
                    ${hasilHtml}
                </div>
            </div>`;
        });
    }

    html += `</div>
    <div class="pdf-doc-footer">
        <span>${escHtml(EXPORT_PROJEK.pembuat)}</span>
        <span>Sistem Manajemen Task &mdash; ${new Date().toLocaleString('id-ID')}</span>
    </div>
    </div>`;

    return html;
}

/* ═══════════════════════════════════════════
   PIE CHART (Canvas)
═══════════════════════════════════════════ */
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
    let startAngle = -Math.PI / 2;
    const cx=65, cy=65, r=58;
    filtered.forEach(d => {
        const slice = (d.n / total) * Math.PI * 2;
        ctx.beginPath(); ctx.moveTo(cx,cy);
        ctx.arc(cx,cy,r,startAngle,startAngle+slice);
        ctx.closePath();
        ctx.fillStyle = colorMap[d.key] || '#9CA3AF';
        ctx.fill(); ctx.strokeStyle='white'; ctx.lineWidth=2; ctx.stroke();
        if (d.n / total >= 0.07) {
            const mid = startAngle + slice / 2;
            ctx.fillStyle = d.key === 'To Do' ? '#374151' : 'white';
            ctx.font = 'bold 9px Segoe UI,Arial,sans-serif';
            ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
            ctx.fillText(Math.round((d.n/total)*100)+'%', cx+(r*.62)*Math.cos(mid), cy+(r*.62)*Math.sin(mid));
        }
        startAngle += slice;
    });
    ctx.beginPath(); ctx.arc(cx,cy,r*.36,0,Math.PI*2); ctx.fillStyle='white'; ctx.fill();
    ctx.fillStyle='#1E2A3A'; ctx.font='bold 15px Georgia,serif';
    ctx.textAlign='center'; ctx.textBaseline='middle'; ctx.fillText(total,cx,cy-5);
    ctx.font='8px Segoe UI,Arial,sans-serif'; ctx.fillStyle='#9CA3AF'; ctx.fillText('task',cx,cy+9);
}

function _drawAllPieCharts() {
    const s = _calcStats();
    _drawPieDonut('pdfPieChart', [
        { key:'done',        n:s.done },
        { key:'In Progress', n:s.prog },
        { key:'To Do',       n:s.todo },
    ], PIE_COLORS_P, s.tot);
    _drawPieDonut('pdfPieChartSA', [
        { key:'approved', n:s.saApproved },
        { key:'review',   n:s.saReview   },
        { key:'revisi',   n:s.saRevisi   },
        { key:'null',     n:s.saNull     },
    ], SA_PIE_COLORS, s.tot);
}

/* ═══════════════════════════════════════════
   CSS PRINT (digunakan oleh printPDF)
═══════════════════════════════════════════ */
const PDF_PRINT_CSS = `
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
.pdf-doc-footer{background:#1E2A3A;padding:9px 28px;display:flex;justify-content:space-between;align-items:center;}
.pdf-doc-footer span{font-size:9px;color:#9CA3AF;font-family:'Segoe UI',Arial,sans-serif;}
#pdfPieChart,#pdfPieChartSA{display:block;}
@media print{
    body{background:white;padding:0;}
    @page{margin:10mm 8mm;size:A4;}
    .pdf-wrap{max-width:100%;border:none;display:flex;flex-direction:column;min-height:277mm;}
    .pdf-doc-footer{margin-top:auto;}
    .pdf-task-card{page-break-inside:avoid;}
    .pdf-letterhead,.pdf-doc-footer,.pdf-stats-table th{-webkit-print-color-adjust:exact;print-color-adjust:exact;}
}`;

/* ═══════════════════════════════════════════
   EXPORT PDF — buka modal preview
═══════════════════════════════════════════ */
function exportPDF() {
    const content = _buildPdfForTask();
    document.getElementById('pdfPreviewToolbar').querySelector('h6').textContent =
        `📄 Preview Laporan Task — ${EXPORT_PROJEK.nama}`;
    document.getElementById('pdfPreviewContent').innerHTML = content;
    document.getElementById('pdfPreviewModal').classList.add('open');
    requestAnimationFrame(() => _drawAllPieCharts());
}

function closePdfPreview() {
    document.getElementById('pdfPreviewModal').classList.remove('open');
}

/* ═══════════════════════════════════════════
   PRINT PDF — konversi canvas → img lalu buka window cetak
═══════════════════════════════════════════ */
function printPDF() {
    const canvas1 = document.getElementById('pdfPieChart');
    const canvas2 = document.getElementById('pdfPieChartSA');
    let content   = document.getElementById('pdfPreviewContent').innerHTML;

    if (canvas1) {
        const img1 = canvas1.toDataURL('image/png');
        content = content.replace(
            /<canvas id="pdfPieChart"[^>]*><\/canvas>/,
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
    win.document.write(`<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Task — ${escHtml(EXPORT_PROJEK.nama)}</title>
    <style>${PDF_PRINT_CSS}</style>
</head>
<body>${content}</body>
</html>`);
    win.document.close();
    setTimeout(() => { win.focus(); win.print(); }, 700);
}
</script>
@endpush