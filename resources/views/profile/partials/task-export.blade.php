{{--
|--------------------------------------------------------------------------
| partials/task-export.blade.php
|--------------------------------------------------------------------------
| Partial export PDF & Excel untuk halaman Kelola Task.
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
/* ── Export Dropdown hover ── */
#exportDropdown button:hover { background: var(--gray-50); }
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
.pdf-toolbar-btn.print-btn {
    background: white; color: #1E2A3A; border-color: white;
}
.pdf-toolbar-btn.print-btn:hover { background: #F3F4F6; }
#pdfPreviewContent { flex: 1; overflow-y: auto; padding: 24px; background: #F3F4F6; }
/* ══════════════════════════════════════
   PDF PAGE STYLES — FORMAL / MINIMAL
══════════════════════════════════════ */
.pdf-wrap {
    font-family: 'Georgia', 'Times New Roman', serif;
    max-width: 794px; margin: 0 auto;
    color: #1F2937; background: white;
    border: 1px solid #D1D5DB;
    display: flex; flex-direction: column;
    min-height: 297mm;
}
.pdf-letterhead {
    background: #1E2A3A;
    padding: 20px 28px 18px;
    display: flex; justify-content: space-between; align-items: flex-start;
}
.pdf-letterhead-left .doc-type {
    font-size: 9px; font-weight: 400; text-transform: uppercase;
    letter-spacing: .15em; color: #9CA3AF; margin-bottom: 5px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.pdf-letterhead-left .doc-title {
    font-size: 18px; font-weight: 700; color: white; line-height: 1.25;
    font-family: 'Georgia', serif;
}
.pdf-letterhead-left .doc-sub {
    font-size: 11px; color: #9CA3AF; margin-top: 4px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.pdf-letterhead-right { text-align: right; flex-shrink: 0; }
.pdf-letterhead-right .doc-num {
    font-size: 10px; color: #9CA3AF; font-family: 'Courier New', monospace; margin-bottom: 4px;
}
.pdf-letterhead-right .doc-date {
    font-size: 11px; color: #D1D5DB; font-family: 'Segoe UI', Arial, sans-serif; font-weight: 500;
}
.pdf-rule { border: none; border-top: 2px solid #374151; margin: 0; }
.pdf-project-info {
    padding: 16px 28px; background: #F9FAFB; border-bottom: 1px solid #E5E7EB;
    display: grid; grid-template-columns: 1fr 1fr; gap: 0;
}
.pdf-info-col { padding: 0 12px; }
.pdf-info-col:first-child { padding-left: 0; border-right: 1px solid #E5E7EB; }
.pdf-info-col:last-child  { padding-left: 20px; border-left: none; }
.pdf-info-row {
    display: flex; gap: 8px; margin-bottom: 7px; font-size: 11px;
    align-items: flex-start; font-family: 'Segoe UI', Arial, sans-serif;
}
.pdf-info-row:last-child { margin-bottom: 0; }
.pdf-info-lbl { min-width: 108px; color: #6B7280; font-weight: 500; flex-shrink: 0; }
.pdf-info-val { color: #111827; font-weight: 600; line-height: 1.5; }
.pdf-section-header {
    padding: 8px 28px 6px; background: white; border-bottom: 1px solid #E5E7EB;
    display: flex; align-items: center; gap: 10px;
}
.pdf-section-header span {
    font-size: 9px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .12em; color: #6B7280; font-family: 'Segoe UI', Arial, sans-serif;
}
.pdf-section-header::before {
    content: ''; width: 3px; height: 11px; background: #1E2A3A; border-radius: 1px; flex-shrink: 0;
}
.pdf-section-header::after { content: ''; flex: 1; height: 1px; background: #E5E7EB; }
.pdf-stats-wrapper {
    padding: 16px 28px; background: white; border-bottom: 1px solid #E5E7EB;
}
.pdf-stats-table-wrap { flex: 1; }
.pdf-stats-table { width: 100%; border-collapse: collapse; font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; }
.pdf-stats-table th {
    background: #1E2A3A; color: white; padding: 7px 10px; text-align: left;
    font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: .06em;
}
.pdf-stats-table td { padding: 7px 10px; border-bottom: 1px solid #F3F4F6; color: #374151; }
.pdf-stats-table tr:last-child td { border-bottom: none; }
.pdf-stats-table tr:nth-child(even) td { background: #F9FAFB; }
.pdf-stats-count { font-weight: 700; color: #111827; }
.pdf-stats-total-row td { background: #F3F4F6 !important; font-weight: 700; color: #1F2937; border-top: 1px solid #D1D5DB; }
.pdf-completion-block {
    margin-top: 10px; padding: 10px 12px; background: #F9FAFB;
    border: 1px solid #E5E7EB; border-radius: 4px; font-family: 'Segoe UI', Arial, sans-serif;
}
.pdf-completion-label { font-size: 9px; color: #6B7280; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 5px; }
.pdf-completion-nums  { font-size: 13px; font-weight: 700; color: #1E2A3A; margin-bottom: 6px; }
.pdf-bar-bg  { background: #E5E7EB; height: 6px; border-radius: 3px; overflow: hidden; }
.pdf-bar-fill{ height: 100%; background: #1E2A3A; border-radius: 3px; }
.pdf-chart-wrap { width: 180px; flex-shrink: 0; display: flex; flex-direction: column; align-items: center; }
.pdf-chart-title {
    font-size: 9px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .08em; color: #6B7280; margin-bottom: 8px;
    font-family: 'Segoe UI', Arial, sans-serif; text-align: center;
}
#pdfPieChart { display: block; }
.pdf-chart-legend { margin-top: 10px; width: 100%; font-family: 'Segoe UI', Arial, sans-serif; }
.pdf-legend-item { display: flex; align-items: center; gap: 6px; font-size: 9px; color: #374151; margin-bottom: 4px; }
.pdf-legend-dot { width: 10px; height: 10px; border-radius: 2px; flex-shrink: 0; }
.pdf-tasks-wrap { padding: 0 28px 24px; background: white; }
.pdf-task-card { border: 1px solid #D1D5DB; border-radius: 4px; margin-bottom: 14px; overflow: hidden; page-break-inside: avoid; }
.pdf-task-head {
    padding: 8px 12px; background: #F9FAFB; border-bottom: 1px solid #E5E7EB;
    display: flex; align-items: flex-start; gap: 10px;
}
.pdf-task-no {
    width: 22px; height: 22px; border-radius: 3px; background: #1E2A3A; color: white;
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 700; flex-shrink: 0; font-family: 'Segoe UI', Arial, sans-serif;
}
.pdf-task-no.approved { background: #374151; }
.pdf-task-title-block { flex: 1; min-width: 0; }
.pdf-task-title { font-size: 12px; font-weight: 700; color: #111827; line-height: 1.3; font-family: 'Segoe UI', Arial, sans-serif; }
.pdf-task-desc { font-size: 10px; color: #6B7280; margin-top: 2px; line-height: 1.5; font-family: 'Segoe UI', Arial, sans-serif; }
.pdf-task-badges { display: flex; gap: 5px; flex-wrap: wrap; margin-left: auto; flex-shrink: 0; }
.pdf-badge {
    display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 3px;
    font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em;
    white-space: nowrap; font-family: 'Segoe UI', Arial, sans-serif;
}
.badge-draft       { background: #F3F4F6; color: #6B7280; border: 1px solid #D1D5DB; }
.badge-todo        { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }
.badge-inprogress  { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }
.badge-done        { background: #F0FDF4; color: #166534; border: 1px solid #BBF7D0; }
.badge-review      { background: #F5F3FF; color: #5B21B6; border: 1px solid #DDD6FE; }
.badge-revisi      { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }
.badge-approved    { background: #F0FDF4; color: #166534; border: 1px solid #BBF7D0; }
.pdf-task-body { padding: 10px 12px; }
.pdf-task-meta-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 10px; }
.pdf-meta-item .lbl {
    font-size: 8px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .06em; color: #9CA3AF; margin-bottom: 2px; font-family: 'Segoe UI', Arial, sans-serif;
}
.pdf-meta-item .val { font-size: 11px; font-weight: 600; color: #1F2937; line-height: 1.4; font-family: 'Segoe UI', Arial, sans-serif; }
.pdf-hasil-section { margin-top: 8px; }
.pdf-hasil-label {
    font-size: 9px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .08em; color: #374151; margin-bottom: 7px;
    display: flex; align-items: center; gap: 5px; font-family: 'Segoe UI', Arial, sans-serif;
    border-top: 1px solid #E5E7EB; padding-top: 8px;
}
.pdf-hasil-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 8px; }
.pdf-hasil-img-wrap { border-radius: 3px; overflow: hidden; border: 1px solid #D1D5DB; aspect-ratio: 16/10; background: #F9FAFB; }
.pdf-hasil-img-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
.pdf-hasil-doc {
    display: flex; align-items: center; gap: 8px; background: #F9FAFB;
    border: 1px solid #D1D5DB; border-radius: 3px; padding: 9px 11px;
}
.pdf-hasil-doc .icon { font-size: 20px; }
.pdf-hasil-doc .name { font-size: 10px; font-weight: 700; color: #374151; word-break: break-all; font-family: 'Segoe UI', Arial, sans-serif; }
.pdf-hasil-doc .type { font-size: 9px; color: #9CA3AF; margin-top: 2px; text-transform: uppercase; font-family: 'Segoe UI', Arial, sans-serif; }
.pdf-empty-foto {
    background: #F9FAFB; border: 1px dashed #D1D5DB; border-radius: 3px;
    padding: 10px; text-align: center; font-size: 10px; color: #9CA3AF;
    font-style: italic; font-family: 'Segoe UI', Arial, sans-serif;
}
.pdf-doc-footer {
    background: #1E2A3A; padding: 9px 28px;
    display: flex; justify-content: space-between; align-items: center; margin-top: auto;
}
.pdf-doc-footer span { font-size: 9px; color: #9CA3AF; font-family: 'Segoe UI', Arial, sans-serif; }
.pdf-footer-spacer { display: none; }
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
   LABEL STATUS
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
const PIE_COLORS = {
    'done':        '#3B7DD8',
    'In Progress': '#E8A838',
    'To Do':       '#9CA3AF',
};

/* ═══════════════════════════════════════════
   HELPER UMUM
═══════════════════════════════════════════ */
function fmtDateLong(s) {
    if (!s) return '—';
    let clean = String(s).trim();
    if (clean.includes('T')) clean = clean.split('T')[0];
    if (!/^\d{4}-\d{2}-\d{2}$/.test(clean)) return '—';
    const parts = clean.split('-');
    const year  = parseInt(parts[0]);
    const month = parseInt(parts[1]) - 1;
    const day   = parseInt(parts[2]);
    const d     = new Date(year, month, day);
    if (isNaN(d.getTime())) return '—';
    const mn = ['Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember'];
    return `${day} ${mn[d.getMonth()]} ${year}`;
}

function escHtml(s) {
    if (!s) return '';
    return String(s)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}

function isImageFile(n) {
    return /\.(jpg|jpeg|png|gif|webp|bmp|svg)$/i.test(n || '');
}

function _docExt(filename) {
    return ((filename || '').split('.').pop() || '').toLowerCase();
}

function _docEmoji(filename) {
    const ext = _docExt(filename);
    const map = { pdf:'📄', doc:'📝', docx:'📝', xls:'📊', xlsx:'📊', ppt:'📋', pptx:'📋' };
    return map[ext] || '📎';
}

/* ═══════════════════════════════════════════════════════════════
   _calcStats — LOGIKA SAMA DENGAN MASTER DATA PROJECT
   ─────────────────────────────────────────────────────────────
   - Exclude draft dari SEMUA perhitungan
   - Selesai (done) = status_progress "done" DAN status_akhir "approved"
   - Persentase = weight(done+approved) / weight(total non-draft) × 100
═══════════════════════════════════════════════════════════════ */
function _calcStats() {
    // Exclude draft dari semua perhitungan
    const nonDraftTasks = tasks.filter(t => t.status_progress !== 'draft');
    const W = t => (t.weight > 0 ? t.weight : 1);

    // ── COUNT per status_progress (non-draft) ──
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

    // ── Status akhir breakdown (dari semua non-draft) ──
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
        .reduce((s,t) => s + W(t), 0);

    // Persentase = weight(done+approved) / weight(total non-draft) × 100
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
   PDF BUILDERS
═══════════════════════════════════════════ */
function _buildLetterhead() {
    const now    = new Date();
    const nowFmt = fmtDateLong(now.toISOString().split('T')[0]);
    const docNum = `DOC-${EXPORT_PROJEK.id}-${now.getFullYear()}${String(now.getMonth()+1).padStart(2,'0')}`;
    return `
    <div class="pdf-letterhead">
        <div class="pdf-letterhead-left">
            <div class="doc-type">Laporan Manajemen Task</div>
            <div class="doc-title">${escHtml(EXPORT_PROJEK.nama)}</div>
            <div class="doc-sub">${escHtml(EXPORT_PROJEK.perusahaan !== '—' ? EXPORT_PROJEK.perusahaan : EXPORT_PROJEK.pembuat)}</div>
        </div>
        <div class="pdf-letterhead-right">
            <div class="doc-num">${docNum}</div>
            <div class="doc-date">Diterbitkan: ${nowFmt}</div>
        </div>
    </div>
    <hr class="pdf-rule">`;
}

function _buildProjectInfo() {
    return `
    <div class="pdf-project-info">
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
}

function _buildStatsSection() {
    const s = _calcStats();

    const SA_COLORS = { approved:'#22C55E', review:'#8B5CF6', revisi:'#F59E0B', null:'#9CA3AF' };

    // Progress rows (status_progress non-draft)
    const progressRows = [
        { label:'Selesai (Done)',    n:s.done, w:s.wDone, key:'done'        },
        { label:'Proses Pengerjaan', n:s.prog, w:s.wProg, key:'In Progress' },
        { label:'Belum Pengerjaan',  n:s.todo, w:s.wTodo, key:'To Do'       },
    ];

    // Status akhir rows
    const wNull = Math.max(0, s.totalWeight - (s.wSaApproved + s.wSaRevisi + s.wSaReview));
    const saRows = [
        { label:'Disetujui (Approved)', n:s.saApproved, w:s.wSaApproved, key:'approved', color:'#166534', bg:'#F0FDF4', border:'#BBF7D0' },
        { label:'Review PM',            n:s.saReview,   w:s.wSaReview,   key:'review',   color:'#5B21B6', bg:'#F5F3FF', border:'#DDD6FE' },
        { label:'Revisi PM',            n:s.saRevisi,   w:s.wSaRevisi,   key:'revisi',   color:'#92400E', bg:'#FFFBEB', border:'#FDE68A' },
        { label:'Belum Dinilai',        n:s.saNull,     w:wNull,         key:'null',     color:'#6B7280', bg:'#F9FAFB', border:'#E5E7EB' },
    ].filter(r => r.n > 0);

    // Legend untuk pie status progress
    const legendPie1 = progressRows.filter(r => r.n > 0).map(r => {
        const pct = s.tot > 0 ? Math.round((r.n/s.tot)*100) : 0;
        return `<div class="pdf-legend-item"><div class="pdf-legend-dot" style="background:${PIE_COLORS[r.key]};"></div><span>${r.label}: <strong>${r.n}</strong> task (${pct}%)</span></div>`;
    }).join('');

    // Legend untuk pie status akhir
    const legendPie2 = saRows.map(r => {
        const pct = s.tot > 0 ? Math.round((r.n/s.tot)*100) : 0;
        return `<div class="pdf-legend-item"><div class="pdf-legend-dot" style="background:${SA_COLORS[r.key]};"></div><span>${r.label}: <strong>${r.n}</strong> task (${pct}%)</span></div>`;
    }).join('');

    return `
    <div class="pdf-section-header"><span>Statistik &amp; Distribusi Status (Tidak Termasuk Draft)</span></div>
    <div class="pdf-stats-wrapper">
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
                        const dot  = PIE_COLORS[r.key] || '#D1D5DB';
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
        <!-- Progress bar penyelesaian -->
        <div class="pdf-completion-block" style="margin-top:14px;">
            <div class="pdf-completion-label">Tingkat Penyelesaian Proyek (Done + Approved PM / Total Non-Draft)</div>
            <div class="pdf-completion-nums">${s.pct}% &mdash; ${s.appr} dari ${s.tot} task done &amp; disetujui PM (Weight: ${s.approvedWeight}/${s.totalWeight})</div>
            <div class="pdf-bar-bg"><div class="pdf-bar-fill" style="width:${s.pct}%;"></div></div>
        </div>
    </div>`;
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
    let startAngle = -Math.PI / 2;
    const cx=65, cy=65, r=58;
    filtered.forEach(d => {
        const slice = (d.n / total) * Math.PI * 2;
        ctx.beginPath(); ctx.moveTo(cx, cy);
        ctx.arc(cx, cy, r, startAngle, startAngle + slice);
        ctx.closePath();
        ctx.fillStyle = colorMap[d.key] || '#9CA3AF';
        ctx.fill(); ctx.strokeStyle = 'white'; ctx.lineWidth = 2; ctx.stroke();
        if (d.n / total >= 0.07) {
            const mid = startAngle + slice / 2;
            const tx  = cx + (r * 0.62) * Math.cos(mid);
            const ty  = cy + (r * 0.62) * Math.sin(mid);
            ctx.fillStyle = d.key === 'To Do' ? '#374151' : 'white';
            ctx.font = 'bold 9px Segoe UI, Arial, sans-serif';
            ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
            ctx.fillText(Math.round((d.n/total)*100)+'%', tx, ty);
        }
        startAngle += slice;
    });
    ctx.beginPath(); ctx.arc(cx,cy,r*.36,0,Math.PI*2); ctx.fillStyle='white'; ctx.fill();
    ctx.fillStyle='#1E2A3A'; ctx.font='bold 14px Georgia,serif';
    ctx.textAlign='center'; ctx.textBaseline='middle'; ctx.fillText(total,cx,cy-5);
    ctx.font='8px Segoe UI,Arial,sans-serif'; ctx.fillStyle='#9CA3AF'; ctx.fillText('task',cx,cy+9);
}

function _drawPieChart() {
    const s = _calcStats();
    const SA_C = { approved:'#22C55E', review:'#8B5CF6', revisi:'#F59E0B', null:'#9CA3AF' };

    _drawPieDonut('pdfPieChart', [
        { key:'done',        n:s.done },
        { key:'In Progress', n:s.prog },
        { key:'To Do',       n:s.todo },
    ], PIE_COLORS, s.tot);

    _drawPieDonut('pdfPieChartSA', [
        { key:'approved', n:s.saApproved },
        { key:'review',   n:s.saReview   },
        { key:'revisi',   n:s.saRevisi   },
        { key:'null',     n:s.saNull     },
    ], SA_C, s.tot);
}

function _buildTaskCards() {
    // Exclude draft dari laporan PDF
    const filteredTasks = tasks.filter(t => t.status_progress !== 'draft');

    if (!filteredTasks.length) return `<div style="padding:20px;text-align:center;color:#9CA3AF;font-size:12px;font-family:'Segoe UI',Arial,sans-serif;">Belum ada task aktif dalam proyek ini.</div>`;

    return filteredTasks.map((t, i) => {
        const member      = TIM_LIST.find(m => m.id_tim === t.id_tim);
        const assignee    = member ? (member.jabatan ? `${member.nama} (${member.jabatan})` : member.nama) : '—';
        const tglMulai    = t.tanggal_mulai   ? fmtDateLong(t.tanggal_mulai)   : '—';
        const tglDeadline = t.tenggat_waktu   ? fmtDateLong(t.tenggat_waktu)   : '—';
        const tglSelesai  = t.tanggal_selesai ? fmtDateLong(t.tanggal_selesai) : '—';
        const spLabel = SP_LABEL_PDF[t.status_progress] || t.status_progress || '—';
        const spClass = SP_BADGE_CLASS[t.status_progress] || 'badge-draft';
        const saLabel = t.status_akhir ? (SA_LABEL_PDF[t.status_akhir] || t.status_akhir) : null;
        const saClass = t.status_akhir ? (SA_BADGE_CLASS[t.status_akhir] || 'badge-draft') : '';
        const hasilFotos = (t.foto || []).filter(f => f.tipe === 'hasil');
        let hasilHtml = '';
        if (hasilFotos.length) {
            const items = hasilFotos.map(f => {
                if (isImageFile(f.nama_file || f.url)) {
                    return `<div class="pdf-hasil-img-wrap"><img src="${escHtml(f.url)}" alt="${escHtml(f.nama_file||'Hasil')}" onerror="this.style.display='none';this.parentElement.style.display='none'"></div>`;
                }
                const ext = _docExt(f.nama_file || f.url);
                return `<div class="pdf-hasil-doc"><span class="icon">${_docEmoji(f.nama_file||f.url)}</span><div><div class="name">${escHtml((f.nama_file||'Dokumen').split('/').pop())}</div><div class="type">${ext||'file'}</div></div></div>`;
            }).join('');
            hasilHtml = `<div class="pdf-hasil-section"><div class="pdf-hasil-label">Laporan Hasil (${hasilFotos.length} file)</div><div class="pdf-hasil-grid">${items}</div></div>`;
        } else {
            hasilHtml = `<div class="pdf-hasil-section"><div class="pdf-hasil-label">Laporan Hasil</div><div class="pdf-empty-foto">Belum ada foto/dokumen laporan hasil untuk task ini.</div></div>`;
        }
        const isDoneApproved = t.status_progress === 'done' && t.status_akhir === 'approved';
        return `
        <div class="pdf-task-card">
            <div class="pdf-task-head">
                <div class="pdf-task-no ${isDoneApproved ? 'approved' : ''}">${i+1}</div>
                <div class="pdf-task-title-block">
                    <div class="pdf-task-title">${escHtml(t.judul_tugas||'—')}</div>
                    ${t.deskripsi_tugas ? `<div class="pdf-task-desc">${escHtml(t.deskripsi_tugas.substring(0,220))}${t.deskripsi_tugas.length>220?'…':''}</div>` : ''}
                </div>
                <div class="pdf-task-badges">
                    <span class="pdf-badge ${spClass}">${spLabel}</span>
                    ${saLabel ? `<span class="pdf-badge ${saClass}">${saLabel}</span>` : ''}
                </div>
            </div>
            <div class="pdf-task-body">
                <div class="pdf-task-meta-row">
                    <div class="pdf-meta-item"><div class="lbl">Penanggung Jawab</div><div class="val">${escHtml(assignee)}</div></div>
                    <div class="pdf-meta-item"><div class="lbl">Tanggal Mulai</div><div class="val">${tglMulai}</div></div>
                    <div class="pdf-meta-item"><div class="lbl">Tenggat Waktu</div><div class="val">${tglDeadline}</div></div>
                    <div class="pdf-meta-item"><div class="lbl">Tanggal Selesai</div><div class="val" style="${isDoneApproved ? 'color:#166534;font-weight:700;' : 'color:#9CA3AF;'}">${tglSelesai}</div></div>
                </div>
                ${hasilHtml}
            </div>
        </div>`;
    }).join('');
}

function _buildPdfPageHtml() {
    const filteredTasks = tasks.filter(t => t.status_progress !== 'draft');
    return `
    <div class="pdf-wrap">
        ${_buildLetterhead()}
        ${_buildProjectInfo()}
        ${_buildStatsSection()}
        <div class="pdf-section-header"><span>Detail Task (${filteredTasks.length} task, tidak termasuk draft)</span></div>
        <div class="pdf-tasks-wrap">${_buildTaskCards()}</div>
        <div class="pdf-footer-spacer"></div>
        <div class="pdf-doc-footer">
            <span>${escHtml(EXPORT_PROJEK.pembuat)}</span>
            <span>Sistem Manajemen Task &mdash; ${new Date().toLocaleString('id-ID')}</span>
        </div>
    </div>`;
}

/* ═══════════════════════════════════════════
   CSS UNTUK WINDOW PRINT
═══════════════════════════════════════════ */
const PDF_PRINT_CSS = `
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Georgia','Times New Roman',serif;background:#F3F4F6;padding:20px;}
.pdf-wrap{max-width:794px;margin:0 auto;color:#1F2937;background:white;border:1px solid #D1D5DB;}
.pdf-letterhead{background:#1E2A3A;padding:20px 28px 18px;display:flex;justify-content:space-between;align-items:flex-start;}
.pdf-letterhead-left .doc-type{font-size:9px;font-weight:400;text-transform:uppercase;letter-spacing:.15em;color:#9CA3AF;margin-bottom:5px;font-family:'Segoe UI',Arial,sans-serif;}
.pdf-letterhead-left .doc-title{font-size:18px;font-weight:700;color:white;line-height:1.25;font-family:'Georgia',serif;}
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
.pdf-stats-wrapper{padding:16px 28px;background:white;border-bottom:1px solid #E5E7EB;}
.pdf-stats-table{width:100%;border-collapse:collapse;font-family:'Segoe UI',Arial,sans-serif;font-size:11px;}
.pdf-stats-table th{background:#1E2A3A;color:white;padding:7px 10px;text-align:left;font-size:9px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;}
.pdf-stats-table td{padding:7px 10px;border-bottom:1px solid #F3F4F6;color:#374151;}
.pdf-stats-count{font-weight:700;color:#111827;}
.pdf-stats-total-row td{background:#F3F4F6!important;font-weight:700;color:#1F2937;border-top:1px solid #D1D5DB;}
.pdf-completion-block{margin-top:10px;padding:10px 12px;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:4px;font-family:'Segoe UI',Arial,sans-serif;}
.pdf-completion-label{font-size:9px;color:#6B7280;text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px;}
.pdf-completion-nums{font-size:13px;font-weight:700;color:#1E2A3A;margin-bottom:6px;}
.pdf-bar-bg{background:#E5E7EB;height:6px;border-radius:3px;overflow:hidden;}
.pdf-bar-fill{height:100%;background:#1E2A3A;border-radius:3px;}
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
.pdf-hasil-label{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#374151;margin-bottom:7px;display:flex;align-items:center;gap:5px;border-top:1px solid #E5E7EB;padding-top:8px;font-family:'Segoe UI',Arial,sans-serif;}
.pdf-hasil-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:8px;}
.pdf-hasil-img-wrap{border-radius:3px;overflow:hidden;border:1px solid #D1D5DB;aspect-ratio:16/10;background:#F9FAFB;}
.pdf-hasil-img-wrap img{width:100%;height:100%;object-fit:cover;display:block;}
.pdf-hasil-doc{display:flex;align-items:center;gap:8px;background:#F9FAFB;border:1px solid #D1D5DB;border-radius:3px;padding:9px 11px;}
.pdf-hasil-doc .name{font-size:10px;font-weight:700;color:#374151;word-break:break-all;font-family:'Segoe UI',Arial,sans-serif;}
.pdf-hasil-doc .type{font-size:9px;color:#9CA3AF;margin-top:2px;text-transform:uppercase;}
.pdf-empty-foto{background:#F9FAFB;border:1px dashed #D1D5DB;border-radius:3px;padding:10px;text-align:center;font-size:10px;color:#9CA3AF;font-style:italic;font-family:'Segoe UI',Arial,sans-serif;}
.pdf-doc-footer{background:#1E2A3A;padding:10px 28px;display:flex;justify-content:space-between;align-items:center;}
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
   DROPDOWN TOGGLE
═══════════════════════════════════════════ */
function toggleExportMenu(e) {
    e.stopPropagation();
    const dd = document.getElementById('exportDropdown');
    dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
}
document.addEventListener('click', () => {
    const dd = document.getElementById('exportDropdown');
    if (dd) dd.style.display = 'none';
});

/* ═══════════════════════════════════════════
   EXPORT PDF
═══════════════════════════════════════════ */
function exportPDF() {
    document.getElementById('exportDropdown').style.display = 'none';
    document.getElementById('pdfPreviewContent').innerHTML = _buildPdfPageHtml();
    document.getElementById('pdfPreviewModal').classList.add('open');
    requestAnimationFrame(() => _drawPieChart());
}

function closePdfPreview() {
    document.getElementById('pdfPreviewModal').classList.remove('open');
}

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
    win.document.write(`<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><title>Laporan Task — ${escHtml(EXPORT_PROJEK.nama)}</title><style>${PDF_PRINT_CSS}</style></head><body>${content}</body></html>`);
    win.document.close();
    setTimeout(() => { win.focus(); win.print(); }, 700);
}

/* ═══════════════════════════════════════════════════════════════
   EXPORT EXCEL — menggunakan ExcelJS (styled, Times New Roman 12)
   ─────────────────────────────────────────────────────────────
   Sheet 1 — Sampul & Ringkasan Proyek
   Sheet 2 — Daftar Task (lengkap + berwarna, tanpa draft)
   ─────────────────────────────────────────────────────────────
   LOGIKA PERHITUNGAN:
   - Draft DIKELUARKAN dari semua perhitungan
   - Done = status_progress "done" DAN status_akhir "approved"
   - % = weight(done+approved) / weight(total non-draft) × 100
═══════════════════════════════════════════════════════════════ */
async function exportExcel() {
    document.getElementById('exportDropdown').style.display = 'none';
    showToast('Membuat file Excel...', 'saving', 'Export Excel', 0);

    /* ── Load ExcelJS dari CDN ── */
    if (typeof ExcelJS === 'undefined') {
        await new Promise((res, rej) => {
            const s  = document.createElement('script');
            s.src    = 'https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js';
            s.onload = res; s.onerror = rej;
            document.head.appendChild(s);
        });
    }

    // Exclude draft dari laporan Excel
    const filteredTasks = tasks.filter(t => t.status_progress !== 'draft');

    const wb  = new ExcelJS.Workbook();
    wb.creator  = EXPORT_PROJEK.pembuat;
    wb.company  = EXPORT_PROJEK.perusahaan;
    wb.created  = new Date();
    wb.modified = new Date();

    const now     = new Date();
    const s       = _calcStats(); // sudah exclude draft
    const dateStr = now.toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' });
    const perus   = EXPORT_PROJEK.perusahaan !== '—' ? EXPORT_PROJEK.perusahaan : EXPORT_PROJEK.pembuat;

    /* ════════════════════════════════════════════════════════════
       KONSTANTA STYLE
    ════════════════════════════════════════════════════════════ */
    const FONT_BASE  = { name: 'Times New Roman', size: 12 };
    const FONT_BOLD  = { name: 'Times New Roman', size: 12, bold: true };
    const FONT_SM    = { name: 'Times New Roman', size: 11 };
    const FONT_SM_B  = { name: 'Times New Roman', size: 11, bold: true };
    const FONT_HDR   = { name: 'Times New Roman', size: 12, bold: true, color: { argb: 'FFFFFFFF' } };
    const FONT_HDR_SM= { name: 'Times New Roman', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
    const FONT_GREEN = { name: 'Times New Roman', size: 11, bold: true, color: { argb: 'FF166534' } };
    const FONT_AMBER = { name: 'Times New Roman', size: 11, bold: true, color: { argb: 'FF92400E' } };
    const FONT_BLUE  = { name: 'Times New Roman', size: 11, bold: true, color: { argb: 'FF1D4ED8' } };
    const FONT_PURP  = { name: 'Times New Roman', size: 11, bold: true, color: { argb: 'FF5B21B6' } };
    const FONT_GRAY  = { name: 'Times New Roman', size: 11, bold: true, color: { argb: 'FF6B7280' } };

    const FILL_NAVY   = { type:'pattern', pattern:'solid', fgColor:{ argb:'FF1E2A3A' } };
    const FILL_NAVY2  = { type:'pattern', pattern:'solid', fgColor:{ argb:'FF2D3F52' } };
    const FILL_WHITE  = { type:'pattern', pattern:'solid', fgColor:{ argb:'FFFFFFFF' } };
    const FILL_ALT    = { type:'pattern', pattern:'solid', fgColor:{ argb:'FFEEF2F7' } };
    const FILL_GREEN  = { type:'pattern', pattern:'solid', fgColor:{ argb:'FFDCFCE7' } };
    const FILL_AMBER  = { type:'pattern', pattern:'solid', fgColor:{ argb:'FFFEF3C7' } };
    const FILL_BLUE   = { type:'pattern', pattern:'solid', fgColor:{ argb:'FFDBEAFE' } };
    const FILL_PURP   = { type:'pattern', pattern:'solid', fgColor:{ argb:'FFEDE9FE' } };
    const FILL_GRAY   = { type:'pattern', pattern:'solid', fgColor:{ argb:'FFF3F4F6' } };
    const FILL_TOTAL  = { type:'pattern', pattern:'solid', fgColor:{ argb:'FFE2E8F0' } };

    const BORDER_THIN = {
        top:    { style:'thin',   color:{ argb:'FFD1D5DB' } },
        left:   { style:'thin',   color:{ argb:'FFD1D5DB' } },
        bottom: { style:'thin',   color:{ argb:'FFD1D5DB' } },
        right:  { style:'thin',   color:{ argb:'FFD1D5DB' } },
    };
    const BORDER_MED = {
        top:    { style:'medium', color:{ argb:'FF9CA3AF' } },
        left:   { style:'medium', color:{ argb:'FF9CA3AF' } },
        bottom: { style:'medium', color:{ argb:'FF9CA3AF' } },
        right:  { style:'medium', color:{ argb:'FF9CA3AF' } },
    };
    const BORDER_HDR = {
        top:    { style:'medium', color:{ argb:'FF1E2A3A' } },
        left:   { style:'medium', color:{ argb:'FF1E2A3A' } },
        bottom: { style:'medium', color:{ argb:'FF1E2A3A' } },
        right:  { style:'medium', color:{ argb:'FF1E2A3A' } },
    };

    const ALIGN_CC  = { horizontal:'center',  vertical:'middle' };
    const ALIGN_LC  = { horizontal:'left',    vertical:'middle' };
    const ALIGN_RC  = { horizontal:'right',   vertical:'middle' };
    const ALIGN_LT  = { horizontal:'left',    vertical:'top',    wrapText:true };

    /* Helper: terapkan style ke satu sel */
    function stl(cell, opts) {
        if (opts.font)      cell.font      = opts.font;
        if (opts.fill)      cell.fill      = opts.fill;
        if (opts.border)    cell.border    = opts.border;
        if (opts.alignment) cell.alignment = opts.alignment;
        if (opts.numFmt)    cell.numFmt    = opts.numFmt;
    }

    /* Helper: merge + tulis + style */
    function mergeWrite(sheet, r1, c1, r2, c2, value, opts) {
        sheet.mergeCells(r1, c1, r2, c2);
        const cell = sheet.getCell(r1, c1);
        cell.value = value;
        stl(cell, opts);
    }

    /* Helper: tulis 1 tabel statistik */
    function xlTable(ws, startR, c1, items, totalVal, mode, titleText) {
        ws.mergeCells(startR, c1, startR, c1+2);
        const titleCell = ws.getCell(startR, c1);
        titleCell.value = titleText;
        stl(titleCell, {
            font: { name:'Times New Roman', size:10, bold:true, color:{ argb:'FFFFFFFF' } },
            fill: FILL_NAVY, alignment: ALIGN_CC,
            border: { bottom:{ style:'thin', color:{ argb:'FF3B7DD8' } } },
        });
        ws.getRow(startR).height = 20;

        const colLabel = mode === 'task' ? 'Jumlah Task' : 'Jumlah Weight';
        const hdrR = startR + 1;
        ws.getRow(hdrR).height = 18;
        [{ v:'Status', c:c1 }, { v:colLabel, c:c1+1 }, { v:'Persentase', c:c1+2 }].forEach(h => {
            ws.getCell(hdrR, h.c).value = h.v;
            stl(ws.getCell(hdrR, h.c), { font:FONT_HDR, fill:FILL_NAVY2, alignment:ALIGN_CC, border:BORDER_HDR });
        });

        items.forEach((item, i) => {
            const r   = hdrR + 1 + i;
            const val = mode === 'task' ? item.n : item.w;
            const pct = totalVal > 0 ? Math.round((val / totalVal) * 100) : 0;
            ws.getRow(r).height = 20;
            ws.getCell(r, c1).value = item.label;
            stl(ws.getCell(r, c1), { font:item.font, fill:item.fill, alignment:ALIGN_LC, border:BORDER_THIN });
            ws.getCell(r, c1+1).value = val;
            stl(ws.getCell(r, c1+1), { font:{ ...item.font, size:12 }, fill:item.fill, alignment:ALIGN_CC, border:BORDER_THIN });
            ws.getCell(r, c1+2).value = totalVal > 0 ? pct/100 : 0;
            stl(ws.getCell(r, c1+2), { font:item.font, fill:item.fill, alignment:ALIGN_CC, border:BORDER_THIN, numFmt:'0%' });
        });

        const totR = hdrR + 1 + items.length;
        ws.getRow(totR).height = 20;
        ws.getCell(totR, c1).value = 'TOTAL';
        stl(ws.getCell(totR, c1), { font:{ name:'Times New Roman', size:11, bold:true, color:{ argb:'FF1E2A3A' } }, fill:FILL_TOTAL, alignment:ALIGN_LC, border:BORDER_MED });
        ws.getCell(totR, c1+1).value = totalVal;
        stl(ws.getCell(totR, c1+1), { font:{ name:'Times New Roman', size:12, bold:true, color:{ argb:'FF1E2A3A' } }, fill:FILL_TOTAL, alignment:ALIGN_CC, border:BORDER_MED });
        ws.getCell(totR, c1+2).value = 1;
        stl(ws.getCell(totR, c1+2), { font:{ name:'Times New Roman', size:11, bold:true, color:{ argb:'FF1E2A3A' } }, fill:FILL_TOTAL, alignment:ALIGN_CC, border:BORDER_MED, numFmt:'0%' });

        return totR;
    }

    /* ════════════════════════════════════════════════════
       SHEET 1 — SAMPUL & RINGKASAN PROYEK
    ════════════════════════════════════════════════════ */
    const ws1 = wb.addWorksheet('Sampul & Ringkasan', {
        properties: { tabColor: { argb: 'FF1E2A3A' } },
        views: [{ showGridLines: false }],
    });
    ws1.columns = [
        { width: 3  }, /* A – gutter kiri */
        { width: 28 }, /* B */
        { width: 18 }, /* C */
        { width: 14 }, /* D */
        { width: 3  }, /* E – gutter tengah */
        { width: 28 }, /* F */
        { width: 18 }, /* G */
        { width: 14 }, /* H */
        { width: 3  }, /* I – gutter kanan */
    ];

    /* ── Accent stripe atas ── */
    ws1.getRow(1).height = 6; ws1.getRow(2).height = 6;
    for (let c = 1; c <= 9; c++) {
        ws1.getCell(1, c).fill = FILL_NAVY;
        ws1.getCell(2, c).fill = { type:'pattern', pattern:'solid', fgColor:{ argb:'FF3B7DD8' } };
    }

    /* ── Judul ── */
    for (let r = 3; r <= 8; r++) for (let c = 1; c <= 9; c++) ws1.getCell(r, c).fill = FILL_NAVY;
    ws1.getRow(3).height = 10; ws1.getRow(4).height = 40;
    ws1.getRow(5).height = 22; ws1.getRow(6).height = 18;
    ws1.getRow(7).height = 18; ws1.getRow(8).height = 12;

    mergeWrite(ws1, 4, 2, 4, 8,
        'LAPORAN MANAJEMEN TASK',
        { font: { name:'Times New Roman', size:20, bold:true, color:{ argb:'FFFFFFFF' } }, fill: FILL_NAVY, alignment: ALIGN_LC }
    );
    mergeWrite(ws1, 5, 2, 5, 8, EXPORT_PROJEK.nama,
        { font:{ name:'Times New Roman', size:14, color:{ argb:'FF9CA3AF' } }, fill:FILL_NAVY, alignment:ALIGN_LC }
    );
    mergeWrite(ws1, 6, 2, 6, 4, perus,
        { font:{ name:'Times New Roman', size:11, color:{ argb:'FFD1D5DB' } }, fill:FILL_NAVY, alignment:ALIGN_LC }
    );
    const docNum = `DOC-${EXPORT_PROJEK.id}-${now.getFullYear()}${String(now.getMonth()+1).padStart(2,'0')}`;
    ws1.getCell(6, 6).value = `No. Dokumen: ${docNum}`;
    stl(ws1.getCell(6, 6), { font:{ name:'Times New Roman', size:10, color:{ argb:'FF9CA3AF' }, italic:true }, fill:FILL_NAVY, alignment:ALIGN_RC });
    ws1.getCell(7, 2).value = `Diterbitkan: ${dateStr}`;
    stl(ws1.getCell(7, 2), { font:{ name:'Times New Roman', size:11, color:{ argb:'FFD1D5DB' } }, fill:FILL_NAVY, alignment:ALIGN_LC });
    ws1.getCell(7, 6).value = `PM: ${EXPORT_PROJEK.pm}`;
    stl(ws1.getCell(7, 6), { font:{ name:'Times New Roman', size:10, color:{ argb:'FF9CA3AF' } }, fill:FILL_NAVY, alignment:ALIGN_RC });

    /* ── Separator ── */
    ws1.getRow(9).height = 8;
    for (let c = 1; c <= 9; c++) ws1.getCell(9, c).fill = { type:'pattern', pattern:'solid', fgColor:{ argb:'FF3B7DD8' } };
    ws1.getRow(10).height = 14;

    /* ── Header INFORMASI PROYEK ── */
    ws1.getRow(11).height = 22;
    mergeWrite(ws1, 11, 2, 11, 8, '▌  INFORMASI PROYEK', {
        font:{ name:'Times New Roman', size:12, bold:true, color:{ argb:'FF1E2A3A' } },
        fill:{ type:'pattern', pattern:'solid', fgColor:{ argb:'FFE2E8F0' } },
        alignment:ALIGN_LC,
        border:{ bottom:{ style:'medium', color:{ argb:'FF1E2A3A' } } },
    });

    /* ── Data Proyek ── */
    const infoData = [
        ['Project Manager',  EXPORT_PROJEK.pm,         'Tanggal Mulai',   EXPORT_PROJEK.mulai],
        ['Perusahaan',       EXPORT_PROJEK.perusahaan, 'Target Selesai',  EXPORT_PROJEK.akhir],
        ['Kategori',         EXPORT_PROJEK.kategori,   'Pembuat Sistem',  EXPORT_PROJEK.pembuat],
        ['Deskripsi',        EXPORT_PROJEK.deskripsi || 'Tidak ada deskripsi.', '', ''],
    ];
    let infoRow = 12;
    infoData.forEach((row, idx) => {
        ws1.getRow(infoRow + idx).height = idx === 3 ? 32 : 20;
        const fillRow = idx % 2 === 1 ? FILL_ALT : FILL_WHITE;
        ws1.getCell(infoRow + idx, 2).value = row[0];
        stl(ws1.getCell(infoRow + idx, 2), { font: FONT_SM_B, fill: fillRow, alignment: ALIGN_LC, border: BORDER_THIN });
        ws1.getCell(infoRow + idx, 3).value = row[1];
        stl(ws1.getCell(infoRow + idx, 3), { font: FONT_SM, fill: fillRow, alignment: ALIGN_LT, border: BORDER_THIN });
        if (row[2]) {
            ws1.getCell(infoRow + idx, 6).value = row[2];
            stl(ws1.getCell(infoRow + idx, 6), { font: FONT_SM_B, fill: fillRow, alignment: ALIGN_LC, border: BORDER_THIN });
            ws1.getCell(infoRow + idx, 7).value = row[3];
            stl(ws1.getCell(infoRow + idx, 7), { font: FONT_SM, fill: fillRow, alignment: ALIGN_LC, border: BORDER_THIN });
        } else {
            ws1.mergeCells(infoRow + idx, 3, infoRow + idx, 8);
            stl(ws1.getCell(infoRow + idx, 3), { font: FONT_SM, fill: fillRow, alignment: { horizontal:'left', vertical:'top', wrapText:true }, border: BORDER_THIN });
        }
    });

    ws1.getRow(17).height = 14;
    ws1.getRow(18).height = 22;
    mergeWrite(ws1, 18, 2, 18, 8, '▌  STATISTIK TASK (TIDAK TERMASUK DRAFT)', {
        font:{ name:'Times New Roman', size:12, bold:true, color:{ argb:'FF1E2A3A' } },
        fill:{ type:'pattern', pattern:'solid', fgColor:{ argb:'FFE2E8F0' } },
        alignment:ALIGN_LC,
        border:{ bottom:{ style:'medium', color:{ argb:'FF1E2A3A' } } },
    });

    /* ── Data statistik ── */
    const spItems = [
        { label:'Selesai (Done)',    n:s.done, w:s.wDone, fill:FILL_GREEN, font:FONT_GREEN },
        { label:'Proses Pengerjaan', n:s.prog, w:s.wProg, fill:FILL_AMBER, font:FONT_AMBER },
        { label:'Belum Pengerjaan',  n:s.todo, w:s.wTodo, fill:FILL_BLUE,  font:FONT_BLUE  },
    ];
    const wNull2 = Math.max(0, s.totalWeight - (s.wSaApproved + s.wSaRevisi + s.wSaReview));
    const saItemsXL = [
        { label:'Disetujui (Approved)', n:s.saApproved, w:s.wSaApproved, fill:FILL_GREEN, font:FONT_GREEN },
        { label:'Review PM',            n:s.saReview,   w:s.wSaReview,   fill:FILL_PURP,  font:FONT_PURP  },
        { label:'Revisi PM',            n:s.saRevisi,   w:s.wSaRevisi,   fill:FILL_AMBER, font:FONT_AMBER  },
        { label:'Belum Dinilai',        n:s.saNull,     w:wNull2,        fill:FILL_GRAY,  font:FONT_GRAY  },
    ];

    /* ── 4 Tabel: kiri (sp task + sp weight), kanan (sa task + sa weight) ── */
    const T1_START = 19;
    const T1_END = xlTable(ws1, T1_START, 2, spItems, s.tot,         'task',   '📊 Status Progress — Jumlah Task');
    const T2_START = T1_END + 2;
    const T2_END = xlTable(ws1, T2_START, 2, spItems, s.totalWeight, 'weight', '⚖️ Status Progress — Jumlah Weight');
    const T3_START = 19;
    const T3_END = xlTable(ws1, T3_START, 6, saItemsXL, s.tot,         'task',   '📊 Status Penilaian PM — Jumlah Task');
    const T4_START = T3_END + 2;
    const T4_END = xlTable(ws1, T4_START, 6, saItemsXL, s.totalWeight, 'weight', '⚖️ Status Penilaian PM — Jumlah Weight');

    /* ── Progress bar ── */
    const lastDataRow = Math.max(T2_END, T4_END);
    const barRowNum   = lastDataRow + 2;
    ws1.getRow(barRowNum).height = 28;
    const barFill  = '█'.repeat(Math.round(s.pct / 5));
    const barEmpty = '░'.repeat(20 - Math.round(s.pct / 5));
    ws1.mergeCells(barRowNum, 2, barRowNum, 8);
    const barCell = ws1.getCell(barRowNum, 2);
    barCell.value = `PENYELESAIAN PROYEK   ${barFill}${barEmpty}   ${s.pct}%  (${s.appr} dari ${s.tot} task done & approved PM · Weight: ${s.approvedWeight}/${s.totalWeight})`;
    stl(barCell, {
        font:{ name:'Courier New', size:10, bold:true, color:{ argb:'FF1E2A3A' } },
        fill:{ type:'pattern', pattern:'solid', fgColor:{ argb:'FFE2E8F0' } },
        alignment: ALIGN_CC,
        border:{ top:{ style:'medium', color:{ argb:'FF1E2A3A' } }, bottom:{ style:'medium', color:{ argb:'FF1E2A3A' } } },
    });

    /* ── Footer accent ── */
    const footR1 = barRowNum + 2; const footR2 = barRowNum + 3;
    ws1.getRow(footR1).height = 6; ws1.getRow(footR2).height = 6;
    for (let c = 1; c <= 9; c++) {
        ws1.getCell(footR1, c).fill = { type:'pattern', pattern:'solid', fgColor:{ argb:'FF3B7DD8' } };
        ws1.getCell(footR2, c).fill = FILL_NAVY;
    }

    /* ════════════════════════════════════════════════════
       SHEET 2 — DAFTAR TASK (non-draft)
    ════════════════════════════════════════════════════ */
    const ws2 = wb.addWorksheet('Daftar Task', {
        properties: { tabColor: { argb: 'FF3B7DD8' } },
        views: [{ showGridLines: false, state:'frozen', ySplit:5 }],
    });
    ws2.columns = [
        { width: 5  }, /* A No */
        { width: 32 }, /* B Judul Task */
        { width: 36 }, /* C Deskripsi */
        { width: 22 }, /* D Penanggung Jawab */
        { width: 18 }, /* E Jabatan */
        { width: 18 }, /* F Status Progress */
        { width: 16 }, /* G Status PM */
        { width: 15 }, /* H Tgl Mulai */
        { width: 15 }, /* I Tenggat */
        { width: 15 }, /* J Tgl Selesai */
        { width: 22 }, /* K Ketepatan Waktu */
        { width: 10 }, /* L Jml Hasil */
        { width: 52 }, /* M URL Hasil */
    ];

    /* Stripe header */
    ws2.getRow(1).height = 8; ws2.getRow(2).height = 8;
    for (let c = 1; c <= 13; c++) {
        ws2.getCell(1, c).fill = FILL_NAVY;
        ws2.getCell(2, c).fill = { type:'pattern', pattern:'solid', fgColor:{ argb:'FF3B7DD8' } };
    }

    /* Judul */
    ws2.getRow(3).height = 32;
    mergeWrite(ws2, 3, 1, 3, 13,
        `DAFTAR TASK PROYEK  ·  ${EXPORT_PROJEK.nama}  ·  ${filteredTasks.length} task (tidak termasuk draft)`,
        { font:{ name:'Times New Roman', size:14, bold:true, color:{ argb:'FFFFFFFF' } }, fill: FILL_NAVY, alignment: ALIGN_LC }
    );

    /* Sub-header grup kolom */
    ws2.getRow(4).height = 18;
    const grupData = [
        { c1:1,  c2:5,  label:'IDENTITAS TASK',   fill:{ type:'pattern', pattern:'solid', fgColor:{ argb:'FF2D3F52' } } },
        { c1:6,  c2:7,  label:'STATUS',            fill:{ type:'pattern', pattern:'solid', fgColor:{ argb:'FF374151' } } },
        { c1:8,  c2:11, label:'WAKTU & KETEPATAN', fill:{ type:'pattern', pattern:'solid', fgColor:{ argb:'FF2D3F52' } } },
        { c1:12, c2:13, label:'LAPORAN HASIL',      fill:{ type:'pattern', pattern:'solid', fgColor:{ argb:'FF374151' } } },
    ];
    grupData.forEach(({ c1, c2, label, fill }) => {
        ws2.mergeCells(4, c1, 4, c2);
        const cell = ws2.getCell(4, c1);
        cell.value = label;
        stl(cell, { font:{ name:'Times New Roman', size:10, bold:true, color:{ argb:'FFADB5C0' } }, fill, alignment: ALIGN_CC });
    });

    /* Header kolom detail */
    ws2.getRow(5).height = 26;
    ['No','Judul Task','Deskripsi','Penanggung Jawab','Jabatan',
     'Status Progress','Status PM',
     'Tgl. Mulai','Tenggat Waktu','Tgl. Selesai','Ketepatan Waktu',
     'Jml Hasil','URL Laporan Hasil'
    ].forEach((h, i) => {
        const c = ws2.getCell(5, i + 1);
        c.value = h;
        stl(c, { font: FONT_HDR, fill: FILL_NAVY, alignment: ALIGN_CC, border: BORDER_HDR });
    });

    /* Warna per status */
    const SP_EXCEL = {
        'done':        { fill: FILL_GREEN, font: FONT_GREEN },
        'In Progress': { fill: FILL_AMBER, font: FONT_AMBER },
        'To Do':       { fill: FILL_BLUE,  font: FONT_BLUE  },
    };
    const SA_EXCEL = {
        'review':  { fill: FILL_PURP,  font: FONT_PURP  },
        'revisi':  { fill: FILL_AMBER, font: FONT_AMBER },
        'approved':{ fill: FILL_GREEN, font: FONT_GREEN },
    };

    function calcTlSimple(t) {
        const today = new Date(); today.setHours(0,0,0,0);
        const end   = t.tenggat_waktu   ? new Date(t.tenggat_waktu+'T00:00:00')   : null;
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

    const TL_LABEL_XL = {
        early:'Selesai Lebih Awal', ontime:'Tepat Waktu', late:'Terlambat',
        inprogress:'Proses Pengerjaan', overdue:'Melewati Deadline',
        upcoming:'Deadline Dekat', todo:'Segera Dikerjakan',
        todo_overdue:'Lewat Deadline Belum Mulai', todo_upcoming:'Segera Dikerjakan', pending:'—',
    };

    /* Data task (sudah filtered, tanpa draft) */
    filteredTasks.forEach((t, i) => {
        const r       = 6 + i;
        const isAlt   = i % 2 === 1;
        const fillRow = isAlt ? FILL_ALT : FILL_WHITE;
        const member  = TIM_LIST.find(m => m.id_tim === t.id_tim);
        const tlSts   = calcTlSimple(t);
        const hasilF  = (t.foto || []).filter(f => f.tipe === 'hasil');
        const spSty   = SP_EXCEL[t.status_progress] || { fill: fillRow, font: FONT_BASE };
        const saSty   = t.status_akhir ? (SA_EXCEL[t.status_akhir] || { fill: fillRow, font: FONT_BASE }) : null;

        ws2.getRow(r).height = 22;

        ws2.getCell(r, 1).value = i + 1;
        stl(ws2.getCell(r, 1), { font: FONT_BASE, fill: fillRow, alignment: ALIGN_CC, border: BORDER_THIN });

        ws2.getCell(r, 2).value = t.judul_tugas || '—';
        stl(ws2.getCell(r, 2), { font: FONT_BOLD, fill: fillRow, alignment: ALIGN_LT, border: BORDER_THIN });

        ws2.getCell(r, 3).value = (t.deskripsi_tugas || '—').substring(0, 300);
        stl(ws2.getCell(r, 3), { font: FONT_SM, fill: fillRow, alignment: { horizontal:'left', vertical:'top', wrapText:true }, border: BORDER_THIN });

        ws2.getCell(r, 4).value = member?.nama || '—';
        stl(ws2.getCell(r, 4), { font: FONT_BASE, fill: fillRow, alignment: ALIGN_LC, border: BORDER_THIN });

        ws2.getCell(r, 5).value = member?.jabatan || '—';
        stl(ws2.getCell(r, 5), { font: FONT_SM, fill: fillRow, alignment: ALIGN_LC, border: BORDER_THIN });

        ws2.getCell(r, 6).value = SP_LABEL_PDF[t.status_progress] || t.status_progress || '—';
        stl(ws2.getCell(r, 6), { font: spSty.font, fill: spSty.fill, alignment: ALIGN_CC, border: BORDER_THIN });

        ws2.getCell(r, 7).value = t.status_akhir ? (SA_LABEL_PDF[t.status_akhir] || t.status_akhir) : '—';
        stl(ws2.getCell(r, 7), {
            font: saSty ? saSty.font : { name:'Times New Roman', size:11, color:{ argb:'FF9CA3AF' } },
            fill: saSty ? saSty.fill : fillRow,
            alignment: ALIGN_CC, border: BORDER_THIN,
        });

        ws2.getCell(r, 8).value  = fmtDateLong(t.tanggal_mulai)   || '—';
        ws2.getCell(r, 9).value  = fmtDateLong(t.tenggat_waktu)   || '—';
        ws2.getCell(r, 10).value = fmtDateLong(t.tanggal_selesai) || '—';
        [8, 9, 10].forEach(c => stl(ws2.getCell(r, c), { font: FONT_SM, fill: fillRow, alignment: ALIGN_CC, border: BORDER_THIN }));

        const tlLabel = TL_LABEL_XL[tlSts] || '—';
        ws2.getCell(r, 11).value = tlLabel;
        let tlFill = fillRow, tlFont = FONT_SM;
        if (tlSts === 'early' || tlSts === 'ontime') {
            tlFill = FILL_GREEN; tlFont = FONT_GREEN;
        } else if (tlSts === 'late' || tlSts === 'overdue' || tlSts === 'todo_overdue') {
            tlFill = { type:'pattern', pattern:'solid', fgColor:{ argb:'FFFEE2E2' } };
            tlFont = { name:'Times New Roman', size:11, bold:true, color:{ argb:'FF991B1B' } };
        } else if (tlSts === 'upcoming' || tlSts === 'todo_upcoming') {
            tlFill = FILL_AMBER; tlFont = FONT_AMBER;
        }
        stl(ws2.getCell(r, 11), { font: tlFont, fill: tlFill, alignment: ALIGN_CC, border: BORDER_THIN });

        ws2.getCell(r, 12).value = hasilF.length;
        stl(ws2.getCell(r, 12), {
            font: hasilF.length > 0 ? FONT_GREEN : { name:'Times New Roman', size:11, color:{ argb:'FF9CA3AF' } },
            fill: hasilF.length > 0 ? FILL_GREEN : fillRow,
            alignment: ALIGN_CC, border: BORDER_THIN,
        });

        ws2.getCell(r, 13).value = hasilF.map(f => f.url).join('\n') || '—';
        stl(ws2.getCell(r, 13), { font:{ name:'Times New Roman', size:10, color:{ argb:'FF1D4ED8' } }, fill: fillRow, alignment: { horizontal:'left', vertical:'top', wrapText:true }, border: BORDER_THIN });
    });

    /* Baris total */
    const taskTotRow = 6 + filteredTasks.length;
    ws2.getRow(taskTotRow).height = 26;
    mergeWrite(ws2, taskTotRow, 1, taskTotRow, 5,
        `TOTAL: ${filteredTasks.length} TASK  |  Selesai: ${s.done}  |  Proses: ${s.prog}  |  Belum: ${s.todo}  |  Done+Approved: ${s.appr}`,
        { font: FONT_HDR, fill: FILL_NAVY, alignment: ALIGN_LC, border: BORDER_HDR }
    );
    ws2.getCell(taskTotRow, 6).value  = `${s.done} selesai`;
    ws2.getCell(taskTotRow, 7).value  = `${s.appr} disetujui`;
    ws2.getCell(taskTotRow, 11).value = `${s.pct}% (done+approved)`;
    ws2.getCell(taskTotRow, 12).value = filteredTasks.reduce((a,t) => a + (t.foto||[]).filter(f => f.tipe==='hasil').length, 0);
    [6, 7, 11, 12].forEach(c => {
        stl(ws2.getCell(taskTotRow, c), { font: FONT_HDR, fill: FILL_NAVY, alignment: ALIGN_CC, border: BORDER_HDR });
    });
    [8, 9, 10, 13].forEach(c => {
        ws2.getCell(taskTotRow, c).fill = FILL_NAVY;
    });

    /* ── Download ── */
    const filename = `laporan-task-${EXPORT_PROJEK.id}-${now.toISOString().split('T')[0]}.xlsx`;
    const buffer   = await wb.xlsx.writeBuffer();
    const blob     = new Blob([buffer], { type:'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    const url      = URL.createObjectURL(blob);
    const a        = document.createElement('a');
    a.href = url; a.download = filename;
    document.body.appendChild(a); a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    showToast(`"${filename}" berhasil diunduh.`, 'success', '⬇ Excel Selesai', 3500);
}
</script>
@endpush