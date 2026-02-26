@extends('layouts.master')
@section('title', 'Master Data Kategori Projek')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/master-data.css') }}">
@endpush
@section('content')

{{-- ── Page Header ── --}}
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
    <div>
        <h4>Master Data Kategori Projek</h4>
        <p>Kelola data kategori projek</p>
    </div>
    <button class="btn-main" data-bs-toggle="modal" data-bs-target="#addKategoriModal">
        <i class='bx bx-plus'></i> Tambah Kategori Projek
    </button>
</div>

@if(session('success') || session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
        Swal.fire({ icon:'success', title:'Berhasil', text:'{{ session('success') }}', confirmButtonColor:'#5145cd', timer:3000, timerProgressBar:true, showConfirmButton:false });
        @endif
        @if(session('error'))
        Swal.fire({ icon:'error', title:'Gagal', text:'{{ session('error') }}', confirmButtonColor:'#5145cd' });
        @endif
    });
</script>
@endif

<div class="data-card">
    <div class="card-top">
        {{-- Counter pills --}}
        <div class="counter-group">
            @foreach([
                ['' ,  'Semua',    $totalCount    ?? 0],
                ['1',  'Aktif',    $activeCount   ?? 0],
                ['0',  'Nonaktif', $inactiveCount ?? 0],
            ] as [$val, $label, $count])
            <a href="javascript:void(0)"
               onclick="filterByStatus('{{ $val }}')"
               class="counter-pill {{ (request('status') === $val || ($val === '' && !request()->has('status'))) ? 'active' : '' }}"
               data-status="{{ $val }}">
                <span class="pill-count">{{ $count }}</span>
                <span>{{ $label }}</span>
            </a>
            @endforeach
        </div>

        {{-- Toolbar --}}
        <div class="toolbar">
            <div class="toolbar-left">
                <span class="label-sm">Tampilkan</span>
                <select id="perPageSelect" class="ctrl" style="width:70px;">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="label-sm">entri</span>
            </div>
            <div class="toolbar-right">
                <div class="search-wrap">
                    <i class='bx bx-search ico'></i>
                    <input type="text" id="searchInput" class="ctrl" placeholder="Cari kategori projek..." autocomplete="off">
                    <button type="button" id="clearSearch" class="search-clear"><i class='bx bx-x'></i></button>
                </div>
                <button type="button" class="btn-ghost" data-bs-toggle="modal" data-bs-target="#columnSettingsModal" title="Pengaturan kolom">
                    <i class='bx bx-columns'></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="col-no" style="width:52px; text-align:center;">No</th>
                    <th class="col-nama sortable" data-column="nama_kategori">Nama Kategori Projek <span class="sort-icon"></span></th>
                    <th class="col-projek sortable" data-column="projek_count" style="min-width:120px;">Projek <span class="sort-icon"></span></th>
                    <th class="col-status sortable" data-column="status" style="min-width:110px;">Status <span class="sort-icon"></span></th>
                    <th class="col-dibuat sortable" data-column="dibuat_pada" style="min-width:130px;">Dibuat <span class="sort-icon"></span></th>
                    <th class="col-diubah sortable" data-column="diperbarui_pada" style="min-width:130px;">Diubah <span class="sort-icon"></span></th>
                    <th class="col-aksi" style="width:110px; text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody id="kategoriTableBody">
                @forelse($kategoriProjek as $index => $item)
                <tr class="kat-row"
                    data-nama_kategori="{{ strtolower($item->nama_kategori) }}"
                    data-projek_count="{{ $item->projek_count }}"
                    data-status="{{ $item->status ? '1' : '0' }}"
                    data-dibuat_pada="{{ $item->dibuat_pada }}"
                    data-diperbarui_pada="{{ $item->diperbarui_pada }}"
                    data-item='@json($item)'>
                    <td class="col-no"><span class="row-no">{{ $index + 1 }}</span></td>
                    <td class="col-nama">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div class="role-icon"><i class='bx bx-category'></i></div>
                            <div>
                                <div class="role-name">{{ $item->nama_kategori }}</div>
                                <div class="role-desc">{{ $item->deskripsi ?: 'Tidak ada deskripsi' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="col-projek">
                        @if($item->projek_count > 0)
                            <span class="emp-count has-emp"><i class='bx bx-folder' style="font-size:13px;"></i> {{ $item->projek_count }} projek</span>
                        @else
                            <span class="emp-count no-emp"><i class='bx bx-folder' style="font-size:13px;"></i> 0 projek</span>
                        @endif
                    </td>
                    <td class="col-status">
                        @if($item->status)
                            <span class="status-pill pill-active"><span class="dot"></span>Aktif</span>
                        @else
                            <span class="status-pill pill-inactive"><span class="dot"></span>Nonaktif</span>
                        @endif
                    </td>
                    <td class="col-dibuat"><span class="date-val">{{ $item->dibuat_pada ? \Carbon\Carbon::parse($item->dibuat_pada)->format('d/m/Y H:i') : '—' }}</span></td>
                    <td class="col-diubah"><span class="date-val">{{ $item->diperbarui_pada ? \Carbon\Carbon::parse($item->diperbarui_pada)->format('d/m/Y H:i') : '—' }}</span></td>
                    <td class="col-aksi" style="text-align:right;">
                        <div class="act-group">
                            <button type="button" class="act-btn view" onclick="viewKategori(this)" data-item='@json($item)' title="Lihat Detail"><i class='bx bx-show'></i></button>
                            <button type="button" class="act-btn edit" onclick="editKategori(this)" data-item='@json($item)' title="Edit"><i class='bx bx-edit'></i></button>
                            <button type="button" class="act-btn delete" onclick="deleteKategori({{ $item->id_kategori_projek }},'{{ addslashes($item->nama_kategori) }}',{{ $item->projek_count }})" title="Hapus"><i class='bx bx-trash'></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7">
                    <div class="empty-state">
                        <i class='bx bx-category'></i>
                        <p>Belum ada data kategori projek.<br>Tambahkan kategori projek pertama Anda.</p>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <span class="footer-info">
            Menampilkan <strong id="showingStart">1</strong>–<strong id="showingEnd">{{ $kategoriProjek->count() }}</strong>
            dari <strong id="totalEntries">{{ $kategoriProjek->count() }}</strong> data
        </span>
        <nav><ul class="page-list" id="paginationControls"></ul></nav>
    </div>
</div>

{{-- ══ MODAL VIEW ══ --}}
<div class="modal fade" id="viewKategoriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-hdr">
                <div class="modal-hdr-title">
                    <div class="hdr-icon"><i class='bx bx-show'></i></div>
                    Detail Kategori Projek
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-bdy">
                <div style="display:flex; align-items:center; gap:14px; margin-bottom:20px;">
                    <div>
                        <div class="role-name" id="view_nama" style="font-size:15px;"></div>
                        <div class="role-desc" style="max-width:none;" id="view_desc"></div>
                    </div>
                </div>
                <hr class="divider">
                <div class="detail-section-title">Informasi</div>
                <div class="detail-row"><span class="detail-label">Projek</span><span class="detail-value" id="view_projek"></span></div>
                <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value" id="view_status"></span></div>
                <hr class="divider">
                <div class="detail-section-title">Waktu</div>
                <div class="detail-row"><span class="detail-label">Dibuat</span><span class="detail-value" style="font-weight:400; color:var(--ink-500);" id="view_dibuat"></span></div>
                <div class="detail-row"><span class="detail-label">Diperbarui</span><span class="detail-value" style="font-weight:400; color:var(--ink-500);" id="view_diubah"></span></div>
            </div>
            <div class="modal-ftr">
                <button type="button" class="btn-outline" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn-main" id="view_edit_btn"><i class='bx bx-edit'></i> Edit</button>
            </div>
        </div>
    </div>
</div>

{{-- ══ MODAL ADD ══ --}}
<div class="modal fade" id="addKategoriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-hdr">
                <div class="modal-hdr-title">
                    <div class="hdr-icon"><i class='bx bx-plus'></i></div>
                    Tambah Kategori Projek
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('master-data-kategori-projek.store') }}" method="POST" id="addKategoriForm">
                @csrf
                <div class="modal-bdy">
                    <div class="mb-3">
                        <label class="form-label-sm">Nama Kategori Projek <span style="color:#DC2626;">*</span></label>
                        <input type="text" name="nama_kategori" class="form-ctrl" required maxlength="100" placeholder="Contoh: Website, Mobile App, UI/UX">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-sm">Deskripsi</label>
                        <textarea name="deskripsi" class="form-ctrl" rows="3" placeholder="Deskripsi singkat kategori projek"></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label-sm">Status <span style="color:#DC2626;">*</span></label>
                        <select name="status" class="form-ctrl" required>
                            <option value="1" selected>Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-ftr">
                    <button type="button" class="btn-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-main"><i class='bx bx-save'></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══ MODAL EDIT ══ --}}
<div class="modal fade" id="editKategoriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-hdr">
                <div class="modal-hdr-title">
                    <div class="hdr-icon"><i class='bx bx-edit'></i></div>
                    Edit Kategori Projek
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editKategoriForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-bdy">
                    <div id="editKategoriAlert" style="display:none;"></div>
                    <div class="mb-3">
                        <label class="form-label-sm">Nama Kategori Projek <span style="color:#DC2626;">*</span></label>
                        <input type="text" name="nama_kategori" id="edit_nama_kategori" class="form-ctrl" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-sm">Deskripsi</label>
                        <textarea name="deskripsi" id="edit_deskripsi" class="form-ctrl" rows="3"></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label-sm">Status <span style="color:#DC2626;">*</span></label>
                        <select name="status" id="edit_status" class="form-ctrl" required>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                        <small id="editStatusHelp" class="fhint" style="display:none; color:#DC2626;">
                            <i class='bx bx-info-circle'></i> Kategori tidak dapat dinonaktifkan karena masih memiliki projek terikat.
                        </small>
                    </div>
                </div>
                <div class="modal-ftr">
                    <button type="button" class="btn-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-main"><i class='bx bx-save'></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══ MODAL COLUMN SETTINGS ══ --}}
<div class="modal fade" id="columnSettingsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-hdr">
                <div class="modal-hdr-title">
                    <div class="hdr-icon"><i class='bx bx-columns'></i></div>
                    Pengaturan Kolom
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-bdy" style="padding-top:14px;">
                @foreach([
                    ['no',     'No Urut',               true,  true ],
                    ['nama',   'Nama Kategori Projek',   true,  true ],
                    ['projek', 'Jumlah Projek',          true,  false],
                    ['status', 'Status',                 true,  false],
                    ['dibuat', 'Dibuat',                 false, false],
                    ['diubah', 'Diubah',                 false, false],
                    ['aksi',   'Aksi',                   true,  true ],
                ] as [$val, $label, $checked, $disabled])
                <div class="col-check-item">
                    <input class="column-toggle" type="checkbox" value="{{ $val }}"
                           id="chk_{{ $val }}" {{ $checked ? 'checked' : '' }} {{ $disabled ? 'disabled' : '' }}>
                    <label for="chk_{{ $val }}">{{ $label }}</label>
                    @if($disabled)<span class="col-lock"><i class='bx bx-lock-alt'></i></span>@endif
                </div>
                @endforeach
            </div>
            <div class="modal-ftr" style="justify-content:space-between;">
                <button type="button" class="btn-outline" onclick="resetColumns()">Reset</button>
                <button type="button" class="btn-main" onclick="saveColumnSettings()"><i class='bx bx-save'></i> Simpan</button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
'use strict';
const STORAGE_KEY = 'kategori_projek_col_v1';
let allRows = [], filteredRows = [];
let currentPage = 1, perPage = 10, currentStatus = '', currentSearch = '';
let searchTimeout = null, currentViewId = null;

document.addEventListener('DOMContentLoaded', function () {
    initData(); loadColumnSettings(); initEvents(); renderTable();
});

function initData() {
    allRows = Array.from(document.querySelectorAll('.kat-row')).map(row => ({
        el:              row,
        nama_kategori:   row.dataset.nama_kategori,
        projek_count:    parseInt(row.dataset.projek_count) || 0,
        status:          row.dataset.status,
        dibuat_pada:     row.dataset.dibuat_pada,
        diperbarui_pada: row.dataset.diperbarui_pada,
        item:            JSON.parse(row.dataset.item),
    }));
    filteredRows = [...allRows];
}

function initEvents() {
    const pp = document.getElementById('perPageSelect');
    pp.value = perPage;
    pp.addEventListener('change', () => { perPage = parseInt(pp.value); currentPage = 1; renderTable(); });

    const si = document.getElementById('searchInput');
    const sc = document.getElementById('clearSearch');
    si.addEventListener('input', function () {
        sc.style.display = this.value.trim() ? 'block' : 'none';
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => { currentSearch = this.value.trim().toLowerCase(); applyFilters(); }, 280);
    });
    sc.addEventListener('click', () => { si.value = ''; sc.style.display = 'none'; currentSearch = ''; applyFilters(); });

    document.querySelectorAll('.sortable').forEach(th => {
        th.addEventListener('click', function () {
            const col = this.dataset.column, wasAsc = this.classList.contains('asc');
            document.querySelectorAll('.sortable').forEach(h => h.classList.remove('asc','desc'));
            this.classList.add(wasAsc ? 'desc' : 'asc');
            sortRows(col, wasAsc ? 'desc' : 'asc');
        });
    });

    document.querySelectorAll('.column-toggle').forEach(c => {
        c.addEventListener('change', function () { toggleColumn(this.value, this.checked); });
    });

    document.getElementById('columnSettingsModal')?.addEventListener('show.bs.modal', () => {
        document.querySelectorAll('.column-toggle:not([disabled])').forEach(c => {
            const el = document.querySelector(`.col-${c.value}`);
            if (el) c.checked = !el.classList.contains('col-hidden');
        });
    });
}

/* ── Column Settings ── */
function loadColumnSettings() {
    const defaults = { projek: true, status: true, dibuat: false, diubah: false };
    let settings = { ...defaults };
    try { const s = localStorage.getItem(STORAGE_KEY); if (s) settings = { ...defaults, ...JSON.parse(s) }; } catch(e) {}
    document.querySelectorAll('.column-toggle:not([disabled])').forEach(c => {
        c.checked = settings.hasOwnProperty(c.value) ? settings[c.value] : (defaults[c.value] ?? true);
        toggleColumn(c.value, c.checked);
    });
}
function toggleColumn(col, show) {
    document.querySelectorAll(`.col-${col}`).forEach(el => el.classList.toggle('col-hidden', !show));
}
function saveColumnSettings() {
    const s = {};
    document.querySelectorAll('.column-toggle:not([disabled])').forEach(c => { s[c.value] = c.checked; });
    localStorage.setItem(STORAGE_KEY, JSON.stringify(s));
    bootstrap.Modal.getInstance(document.getElementById('columnSettingsModal'))?.hide();
    Swal.fire({ icon:'success', title:'Tersimpan', showConfirmButton:false, timer:1200, confirmButtonColor:'#5145cd' });
}
function resetColumns() {
    const defaults = { projek: true, status: true, dibuat: false, diubah: false };
    document.querySelectorAll('.column-toggle:not([disabled])').forEach(c => {
        c.checked = defaults[c.value] ?? true; toggleColumn(c.value, c.checked);
    });
}

/* ── Filter / Sort ── */
function filterByStatus(status) {
    currentStatus = status; currentPage = 1;
    document.querySelectorAll('.counter-pill').forEach(p => p.classList.toggle('active', p.dataset.status === status));
    applyFilters();
}
function applyFilters() {
    filteredRows = allRows.filter(r => {
        if (currentStatus !== '' && r.status !== currentStatus) return false;
        if (currentSearch  !== '' && !r.nama_kategori.includes(currentSearch)) return false;
        return true;
    });
    currentPage = 1; renderTable();
}
function sortRows(col, dir) {
    filteredRows.sort((a, b) => {
        if (['dibuat_pada','diperbarui_pada'].includes(col)) {
            return dir === 'asc' ? new Date(a[col]) - new Date(b[col]) : new Date(b[col]) - new Date(a[col]);
        }
        if (['projek_count','status'].includes(col))
            return dir === 'asc' ? parseInt(a[col]) - parseInt(b[col]) : parseInt(b[col]) - parseInt(a[col]);
        const av = String(a[col]||''), bv = String(b[col]||'');
        return dir === 'asc' ? av.localeCompare(bv) : bv.localeCompare(av);
    });
    renderTable();
}

/* ── Render ── */
function renderTable() {
    const tbody = document.getElementById('kategoriTableBody');
    tbody.innerHTML = '';
    const start = (currentPage - 1) * perPage;
    const page  = filteredRows.slice(start, start + perPage);

    if (page.length === 0) {
        let msg = 'Belum ada data kategori projek.';
        if (currentSearch) msg = `Tidak ada hasil untuk "<strong>${currentSearch}</strong>"`;
        else if (currentStatus !== '') msg = `Tidak ada data dengan status ${currentStatus==='1'?'Aktif':'Nonaktif'}.`;
        tbody.innerHTML = `<tr><td colspan="7"><div class="empty-state"><i class='bx bx-category'></i><p>${msg}</p></div></td></tr>`;
    } else {
        page.forEach((row, idx) => {
            const clone = row.el.cloneNode(true);
            const num = clone.querySelector('.row-no');
            if (num) num.textContent = start + idx + 1;
            tbody.appendChild(clone);
        });
    }

    const end = Math.min(start + perPage, filteredRows.length);
    document.getElementById('showingStart').textContent = filteredRows.length === 0 ? 0 : start + 1;
    document.getElementById('showingEnd').textContent   = end;
    document.getElementById('totalEntries').textContent = filteredRows.length;
    renderPagination();
}
function renderPagination() {
    const ctrl = document.getElementById('paginationControls');
    const total = Math.ceil(filteredRows.length / perPage);
    if (total <= 1) { ctrl.innerHTML = ''; return; }
    let html = `<li class="page-item ${currentPage===1?'disabled':''}"><a class="page-link" href="javascript:void(0)" onclick="goToPage(${currentPage-1})"><i class='bx bx-chevron-left'></i></a></li>`;
    let sp = Math.max(1, currentPage-2), ep = Math.min(total, sp+4);
    if (ep-sp<4) sp = Math.max(1, ep-4);
    if (sp>1) { html+=`<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="goToPage(1)">1</a></li>`; if(sp>2) html+=`<li class="page-item disabled"><span class="page-link">…</span></li>`; }
    for (let i=sp;i<=ep;i++) html+=`<li class="page-item ${i===currentPage?'active':''}"><a class="page-link" href="javascript:void(0)" onclick="goToPage(${i})">${i}</a></li>`;
    if (ep<total) { if(ep<total-1) html+=`<li class="page-item disabled"><span class="page-link">…</span></li>`; html+=`<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="goToPage(${total})">${total}</a></li>`; }
    html+=`<li class="page-item ${currentPage===total?'disabled':''}"><a class="page-link" href="javascript:void(0)" onclick="goToPage(${currentPage+1})"><i class='bx bx-chevron-right'></i></a></li>`;
    ctrl.innerHTML = html;
}
function goToPage(p) {
    const total = Math.ceil(filteredRows.length / perPage);
    if (p < 1 || p > total) return;
    currentPage = p; renderTable();
}

/* ── Helpers ── */
function fmtDate(s) {
    if (!s) return '—';
    const d = new Date(s);
    return d.toLocaleDateString('id-ID',{day:'2-digit',month:'2-digit',year:'numeric'}) + ' ' +
           d.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
}

/* ── CRUD ── */
function viewKategori(btn) {
    const d = JSON.parse(btn.dataset.item);
    currentViewId = d.id_kategori_projek;
    document.getElementById('view_nama').textContent = d.nama_kategori || '—';
    document.getElementById('view_desc').textContent = d.deskripsi || 'Tidak ada deskripsi';
    document.getElementById('view_projek').innerHTML = `<span class="emp-count ${d.projek_count>0?'has-emp':'no-emp'}"><i class='bx bx-folder' style="font-size:13px;"></i> ${d.projek_count??0} projek</span>`;
    document.getElementById('view_status').innerHTML = d.status
        ? '<span class="status-pill pill-active"><span class="dot"></span>Aktif</span>'
        : '<span class="status-pill pill-inactive"><span class="dot"></span>Nonaktif</span>';
    document.getElementById('view_dibuat').textContent = fmtDate(d.dibuat_pada);
    document.getElementById('view_diubah').textContent = fmtDate(d.diperbarui_pada);
    document.getElementById('view_edit_btn').onclick = function() {
        bootstrap.Modal.getInstance(document.getElementById('viewKategoriModal'))?.hide();
        setTimeout(() => {
            const row = allRows.find(r => r.item.id_kategori_projek == currentViewId);
            if (row) editKategori({ dataset: { item: JSON.stringify(row.item) } });
        }, 300);
    };
    new bootstrap.Modal(document.getElementById('viewKategoriModal')).show();
}

function editKategori(btn) {
    const d = JSON.parse(btn.dataset.item);
    document.getElementById('edit_nama_kategori').value = d.nama_kategori || '';
    document.getElementById('edit_deskripsi').value     = d.deskripsi    || '';
    document.getElementById('edit_status').value        = d.status ? '1' : '0';
    document.getElementById('editKategoriForm').action  = `/master-data-kategori-projek/${d.id_kategori_projek}`;

    const alertDiv   = document.getElementById('editKategoriAlert');
    const statusHelp = document.getElementById('editStatusHelp');
    const statusSelect = document.getElementById('edit_status');

    if (d.projek_count > 0) {
        alertDiv.innerHTML = `
            <div class="alert alert-warning mb-3">
                <i class='bx bx-info-circle'></i>
                Kategori ini memiliki <strong>${d.projek_count} projek</strong> yang terikat.
                ${d.status ? '<br><small>Tidak dapat dinonaktifkan selama masih ada projek.</small>' : ''}
            </div>`;
        alertDiv.style.display = 'block';
        if (d.status) {
            statusHelp.style.display = 'block';
            Array.from(statusSelect.options).forEach(o => { if (o.value === '0') o.disabled = true; });
        }
    } else {
        alertDiv.style.display = 'none';
        statusHelp.style.display = 'none';
        Array.from(statusSelect.options).forEach(o => o.disabled = false);
    }

    new bootstrap.Modal(document.getElementById('editKategoriModal')).show();
}

function deleteKategori(id, nama, projekCount) {
    if (projekCount > 0) {
        Swal.fire({
            title: 'Tidak Dapat Dihapus',
            html: `<strong>${nama}</strong> masih memiliki <strong>${projekCount} projek</strong> yang terikat.<br><br>Silahkan pindahkan atau hapus projek tersebut terlebih dahulu.`,
            icon: 'warning', confirmButtonColor: '#5145cd', confirmButtonText: 'Mengerti'
        });
        return;
    }
    Swal.fire({
        title: 'Hapus Kategori Projek?',
        html: `Tindakan ini akan menghapus <strong>${nama}</strong> secara permanen.`,
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#DC2626', cancelButtonColor: '#6B7280',
        confirmButtonText: 'Hapus', cancelButtonText: 'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            const f = document.createElement('form');
            f.method = 'POST';
            f.action = `/master-data-kategori-projek/${id}`;
            f.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
            document.body.appendChild(f);
            f.submit();
        }
    });
}

['addKategoriForm','editKategoriForm'].forEach(fid => {
    const f = document.getElementById(fid);
    if (!f) return;
    f.addEventListener('submit', function(e) {
        e.preventDefault();

        if (fid === 'editKategoriForm') {
            const statusSelect = document.getElementById('edit_status');
            const row = allRows.find(r => r.item.id_kategori_projek == currentViewId);
            if (row && row.projek_count > 0 && statusSelect.value === '0') {
                Swal.fire({
                    icon: 'error', title: 'Tidak Dapat Menonaktifkan',
                    text: 'Kategori tidak dapat dinonaktifkan karena masih memiliki projek terikat!',
                    confirmButtonColor: '#5145cd'
                });
                return;
            }
        }

        const mid = fid === 'addKategoriForm' ? 'addKategoriModal' : 'editKategoriModal';
        bootstrap.Modal.getInstance(document.getElementById(mid))?.hide();
        setTimeout(() => {
            Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            this.submit();
        }, 280);
    });
});
</script>
@endpush