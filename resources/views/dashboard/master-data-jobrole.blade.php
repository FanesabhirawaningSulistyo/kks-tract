@extends('layouts.master')
@section('title', 'Master Data Job Role')
@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-semibold mb-1">Master Data Job Role</h4>
                <p class="text-muted mb-0 small">Kelola data posisi pekerjaan</p>
            </div>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addJobRoleModal">
                <i class='bx bx-plus'></i> Tambah Job Role
            </button>
        </div>
        
        <!-- Table Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <div class="row g-3">
                    <!-- Row 1: Status Counters -->
                    <div class="col-12">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <!-- Total Counter -->
                            <a href="javascript:void(0)" onclick="filterByStatus('')" class="text-decoration-none">
                                <span class="badge-counter {{ !request()->has('status') || request('status') === '' ? 'active' : '' }}" data-status="">
                                    <span class="count">{{ $totalCount ?? 0 }}</span>
                                    <span class="label">Semua Data</span>
                                </span>
                            </a>
                            
                            <!-- Active Counter -->
                            <a href="javascript:void(0)" onclick="filterByStatus('1')" class="text-decoration-none">
                                <span class="badge-counter {{ request('status') == '1' ? 'active' : '' }}" data-status="1">
                                    <span class="count">{{ $activeCount ?? 0 }}</span>
                                    <span class="label">Aktif</span>
                                </span>
                            </a>
                            
                            <!-- Inactive Counter -->
                            <a href="javascript:void(0)" onclick="filterByStatus('0')" class="text-decoration-none">
                                <span class="badge-counter {{ request('status') == '0' ? 'active' : '' }}" data-status="0">
                                    <span class="count">{{ $inactiveCount ?? 0 }}</span>
                                    <span class="label">Nonaktif</span>
                                </span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Row 2: Filters -->
                    <div class="col-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <!-- Left side: Pagination -->
                            <div class="d-flex align-items-center">
                                <label class="me-2 text-nowrap small fs-6">Tampilkan:</label>
                                <select id="perPageSelect" class="form-select fs-6" style="width: 80px;">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            
                            <!-- Right: Search & Settings -->
                            <div class="d-flex align-items-center gap-2">
                                <div class="search-box">
                                    <i class='bx bx-search'></i>
                                    <input type="text" id="searchInput" 
                                           placeholder="Cari job role..." 
                                           autocomplete="off">
                                    <button type="button" id="clearSearch" class="btn-clear-search" style="display: none;">
                                        <i class='bx bx-x'></i>
                                    </button>
                                </div>
                                
                                <button type="button" class="btn btn-sm btn-light" 
                                        data-bs-toggle="modal" data-bs-target="#columnSettingsModal">
                                    <i class='bx bx-cog'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="column-no" style="width: 60px;">NO</th>
                            <th class="column-nama sortable" data-column="nama_job_role" style="min-width: 250px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>NAMA JOB ROLE</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-karyawan sortable" data-column="users_count" style="min-width: 120px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>KARYAWAN</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-status sortable" data-column="status" style="min-width: 120px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>STATUS</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-dibuat sortable" data-column="dibuat_pada" style="min-width: 140px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>DIBUAT</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-diubah sortable" data-column="diubah_pada" style="min-width: 140px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>DIUBAH</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-actions text-center" style="width: 140px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="jobRoleTableBody">
                        @forelse($jobRoles as $index => $item)
                       <tr class="jobrole-row" 
    data-original-index="{{ $index + 1 }}"
    data-nama_job_role="{{ strtolower($item->nama_job_role) }}"
    data-users_count="{{ $item->users_count }}"
    data-status="{{ $item->status ? '1' : '0' }}"
    data-dibuat_pada="{{ $item->dibuat_pada }}"
    data-diubah_pada="{{ $item->diubah_pada }}">
                            <td class="column-no">
                                <span class="row-number">{{ $index + 1 }}</span>
                            </td>
                            <td class="column-nama">
                                <div class="d-flex align-items-center">
                                    <div class="role-icon">
                                        <i class='bx bx-briefcase'></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="role-name">{{ $item->nama_job_role }}</div>
                                        <div class="role-desc">{{ $item->deskripsi ?: 'Tidak ada deskripsi' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="column-karyawan">
                                @if($item->users_count > 0)
                                    <a href="{{ route('master-data-users.index', ['job_role' => $item->id_job_role]) }}" 
                                       class="text-decoration-none employee-count-link">
                                        <span class="employee-count">
                                            <i class='bx bx-user'></i>
                                            <span class="count-number">{{ $item->users_count }}</span>
                                            <span class="count-label">orang</span>
                                        </span>
                                    </a>
                                @else
                                    <span class="employee-count employee-count-empty">
                                        <i class='bx bx-user'></i>
                                        <span class="count-number">0</span>
                                        <span class="count-label">orang</span>
                                    </span>
                                @endif
                            </td>
                            <td class="column-status">
                                @if($item->status)
                                    <span class="status-badge status-active">
                                        <i class='bx bx-check-circle'></i>
                                        Aktif
                                    </span>
                                @else
                                    <span class="status-badge status-inactive">
                                        <i class='bx bx-x-circle'></i>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="column-dibuat">
                                <span class="date-text">{{ \Carbon\Carbon::parse($item->dibuat_pada)->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="column-diubah">
                                <span class="date-text">{{ \Carbon\Carbon::parse($item->diubah_pada)->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="column-actions text-center">
                                <div class="action-buttons">
                                    <button type="button" 
                                            class="action-btn view-btn" 
                                            onclick="viewJobRole({{ $item->id_job_role }})"
                                            data-jobrole='@json($item)'
                                            title="Lihat Detail">
                                        <i class='bx bx-show'></i>
                                    </button>
                                    <button type="button" 
                                            class="action-btn edit-btn" 
                                            onclick="editJobRole({{ $item->id_job_role }})"
                                            data-jobrole='@json($item)'
                                            title="Edit">
                                        <i class='bx bx-edit'></i>
                                    </button>
                                    <button type="button" 
                                            class="action-btn delete-btn" 
                                            onclick="deleteJobRole({{ $item->id_job_role }}, '{{ $item->nama_job_role }}', {{ $item->users_count }})"
                                            title="Hapus">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="7" class="text-center py-5">
                                <i class='bx bx-briefcase empty-icon'></i>
                                <p class="empty-text">Belum ada data job role</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer bg-white border-0 py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0 text-muted small" id="tableInfo">
                            Menampilkan <span id="showingStart">1</span> - <span id="showingEnd">10</span> dari <span id="totalEntries">{{ $jobRoles->count() }}</span> data
                        </p>
                    </div>
                    <div class="col-md-6">
                        <nav>
                            <ul class="pagination pagination-sm justify-content-md-end mb-0" id="paginationControls">
                                <!-- Pagination will be generated by JavaScript -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Job Role Modal -->
<div class="modal fade" id="viewJobRoleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">Detail Job Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="row g-4">
                    <!-- Icon & Nama Job Role -->
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <div class="role-icon me-3" style="width: 60px; height: 60px; font-size: 28px;">
                                <i class='bx bx-briefcase'></i>
                            </div>
                            <div>
                                <h5 class="mb-1" id="view_nama_job_role"></h5>
                                <p class="text-muted mb-0 small" id="view_deskripsi"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12"><hr class="my-2"></div>
                    
                    <!-- Informasi Job Role -->
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <i class='bx bx-info-circle me-2'></i>Informasi Job Role
                        </h6>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="small text-muted mb-1">Jumlah Karyawan</label>
                        <p class="mb-0" id="view_users_count"></p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="small text-muted mb-1">Status</label>
                        <p class="mb-0" id="view_status"></p>
                    </div>
                    
                    <div class="col-12"><hr class="my-2"></div>
                    
                    <!-- Timestamp -->
                    <div class="col-md-6">
                        <label class="small text-muted mb-1">Dibuat Pada</label>
                        <p class="mb-0" id="view_dibuat_pada"></p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="small text-muted mb-1">Terakhir Diubah</label>
                        <p class="mb-0" id="view_diubah_pada"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Job Role Modal -->
<div class="modal fade" id="addJobRoleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">Tambah Job Role Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('master-data-jobrole.store') }}" method="POST" id="addJobRoleForm">
                @csrf
                <div class="modal-body pt-3">
                    <div class="mb-3">
                        <label class="form-label small">Nama Job Role *</label>
                        <input type="text" name="nama_job_role" class="form-control" required maxlength="100" placeholder="Contoh: Web Developer, UI/UX Designer">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi tugas dan tanggung jawab"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="1" selected>Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class='bx bx-save me-1'></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Job Role Modal -->
<div class="modal fade" id="editJobRoleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">Edit Job Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editJobRoleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body pt-3">
                    <div class="mb-3">
                        <label class="form-label small">Nama Job Role *</label>
                        <input type="text" name="nama_job_role" id="edit_nama_job_role" class="form-control" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Deskripsi</label>
                        <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Status *</label>
                        <select name="status" id="edit_status" class="form-select" required>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class='bx bx-save me-1'></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Column Settings Modal -->
<div class="modal fade" id="columnSettingsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">Pengaturan Kolom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="no" id="col-no" checked disabled>
                    <label class="form-check-label small" for="col-no">No Urut</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="nama" id="col-nama" checked disabled>
                    <label class="form-check-label small" for="col-nama">Nama Job Role</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="karyawan" id="col-karyawan" checked>
                    <label class="form-check-label small" for="col-karyawan">Jumlah Karyawan</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="status" id="col-status" checked>
                    <label class="form-check-label small" for="col-status">Status</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="dibuat" id="col-dibuat">
                    <label class="form-check-label small" for="col-dibuat">Dibuat</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="diubah" id="col-diubah">
                    <label class="form-check-label small" for="col-diubah">Diubah</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="actions" id="col-actions" checked disabled>
                    <label class="form-check-label small" for="col-actions">Aksi</label>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" onclick="resetColumns()">Reset</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="saveColumnSettings()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== MODERN TABLE DESIGN (SAME AS PERUSAHAAN) ===== */

/* Role Icon */
.role-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: linear-gradient(135deg, #696cff 0%, #5145cd 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
}

/* Role Info */
.role-name {
    font-weight: 600;
    color: #2d3748;
    font-size: 0.9rem;
    line-height: 1.4;
}

.role-desc {
    font-weight: 500;
    color: #805ad5;
    font-size: 0.8125rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Employee Count */
.employee-count {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    background: #e7f3ff;
    border-radius: 6px;
    font-size: 0.8125rem;
    transition: all 0.2s ease;
}

.employee-count-link:hover .employee-count {
    background: #696cff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(105, 108, 255, 0.3);
}

.employee-count-link:hover .employee-count i,
.employee-count-link:hover .employee-count .count-number,
.employee-count-link:hover .employee-count .count-label {
    color: white !important;
}

.employee-count-empty {
    background: #f8f9fa;
    cursor: default;
}

.employee-count i {
    color: #696cff;
    font-size: 14px;
}

.employee-count .count-number {
    font-weight: 600;
    color: #696cff;
}

.employee-count .count-label {
    color: #6c757d;
    font-weight: 500;
}

.employee-count-empty i,
.employee-count-empty .count-number {
    color: #adb5bd;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.8125rem;
    font-weight: 500;
}

.status-active {
    background: #d4edda;
    color: #28a745;
}

.status-inactive {
    background: #f8d7da;
    color: #dc3545;
}

.status-badge i {
    font-size: 14px;
}

/* Card */
.card {
    border-radius: 12px;
    overflow: hidden;
}

/* Badge Counters */
.badge-counter {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: #f8f9fa;
    border: 1.5px solid #e9ecef;
    border-radius: 20px;
    transition: all 0.2s ease;
    cursor: pointer;
}

.badge-counter:hover {
    background: #e9ecef;
    border-color: #dee2e6;
    transform: translateY(-1px);
}

.badge-counter.active {
    background: #696cff;
    border-color: #696cff;
    box-shadow: 0 2px 8px rgba(105, 108, 255, 0.25);
}

.badge-counter .count {
    font-weight: 600;
    font-size: 0.875rem;
    color: #495057;
}

.badge-counter.active .count {
    color: white;
}

.badge-counter .label {
    font-size: 0.8125rem;
    color: #6c757d;
    font-weight: 500;
}

.badge-counter.active .label {
    color: white;
}

/* Search Box */
.search-box {
    position: relative;
    display: flex;
    align-items: center;
}

.search-box i.bx-search {
    position: absolute;
    left: 12px;
    color: #a0aec0;
    font-size: 18px;
    pointer-events: none;
    z-index: 1;
}

.search-box input {
    padding: 8px 40px 8px 40px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.875rem;
    width: 220px;
    transition: all 0.3s ease;
}

.search-box input:focus {
    outline: none;
    border-color: #696cff;
    box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.1);
    width: 280px;
}

.btn-clear-search {
    position: absolute;
    right: 6px;
    background: none;
    border: none;
    padding: 4px;
    cursor: pointer;
    color: #a0aec0;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    z-index: 2;
}

.btn-clear-search:hover {
    background: #fed7d7;
    color: #e53e3e;
}

/* Table */
.table {
    font-size: 0.875rem;
}

.table thead th {
    font-weight: 600;
    color: #4a5568;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 14px 16px;
    background: #f7fafc;
    border: none;
    font-size: 0.75rem;
}

.table tbody tr {
    border-bottom: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background: #f7fafc;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.table tbody td {
    padding: 16px;
    vertical-align: middle;
    border: none;
}

/* Sortable Headers */
.sortable {
    cursor: pointer;
    user-select: none;
    transition: background-color 0.2s ease;
}

.sortable:hover {
    background: #edf2f7;
}

.sort-icon {
    font-size: 16px;
    color: #cbd5e0;
    transition: all 0.3s ease;
}

.sortable.asc .sort-icon,
.sortable.desc .sort-icon {
    color: #696cff;
}

.sortable.asc .sort-icon {
    transform: rotate(180deg);
}

/* Action Buttons - SAME AS PERUSAHAAN */
.action-buttons {
    display: inline-flex;
    gap: 6px;
}

.action-btn {
    width: 34px;
    height: 34px;
    border: none;
    background: #f7fafc;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    color: #718096;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.view-btn:hover {
    background: #bee3f8;
    color: #2c5282;
}

.edit-btn:hover {
    background: #e7e9fd;
    color: #5145cd;
}

.delete-btn:hover {
    background: #fed7d7;
    color: #c53030;
}

.action-btn i {
    font-size: 18px;
}

/* Date Text */
.date-text {
    font-size: 0.8125rem;
    color: #718096;
}

/* Row Number */
.row-number {
    font-weight: 600;
    color: #cbd5e0;
    font-size: 0.875rem;
}

/* Form Controls */
.form-control,
.form-select {
    font-size: 0.875rem;
    border-color: #e2e8f0;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.1);
}

/* Buttons */
.btn-sm {
    font-size: 0.8125rem;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 500;
}

.btn-primary {
    background: linear-gradient(135deg, #696cff 0%, #5145cd 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5145cd 0%, #3a2d9f 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(105, 108, 255, 0.4);
}

.btn-light {
    background: #f7fafc;
    border-color: #e2e8f0;
    color: #4a5568;
}

.btn-light:hover {
    background: #edf2f7;
    border-color: #cbd5e0;
}

/* Empty State */
.empty-icon {
    font-size: 56px;
    color: #e2e8f0;
}

.empty-text {
    color: #a0aec0;
    margin-top: 16px;
    font-size: 0.9rem;
}

/* Hidden Column */
.column-hidden {
    display: none !important;
}

/* Pagination */
.pagination {
    gap: 4px;
}

.page-link {
    border-radius: 8px;
    border-color: #e2e8f0;
    color: #4a5568;
    font-size: 0.875rem;
    padding: 6px 12px;
}

.page-link:hover {
    background: #f7fafc;
    border-color: #cbd5e0;
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #696cff 0%, #5145cd 100%);
    border: none;
}

/* Modal Improvements */
.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-header h5 {
    color: #2d3748;
}

/* Responsive */
@media (max-width: 768px) {
    .search-box input {
        width: 160px;
    }
    
    .search-box input:focus {
        width: 200px;
    }
    
    .role-icon {
        width: 36px;
        height: 36px;
        font-size: 18px;
    }
    
    .table {
        font-size: 0.8125rem;
    }
    
    .action-btn {
        width: 30px;
        height: 30px;
    }
}

/* Scrollbar */
.table-responsive::-webkit-scrollbar {
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f7fafc;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ===== SESSION ALERTS =====
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session('success') }}',
    confirmButtonColor: '#696cff',
    timer: 3000,
    timerProgressBar: true
});
@endif

@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '{{ session('error') }}',
    confirmButtonColor: '#696cff'
});
@endif

// ===== CONSTANTS & CONFIG =====
const STORAGE_KEY = 'jobrole_column_settings';

// Global Variables
let allRows = [];
let filteredRows = [];
let currentPage = 1;
let perPage = 10;
let currentStatus = '';
let currentSearch = '';
let searchTimeout = null;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    initializeData();
    loadColumnSettings();
    initializeEventListeners();
    renderTable();
    
    // Show clear search button if search has value
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearch');
    if (searchInput && searchInput.value.trim().length > 0) {
        clearSearchBtn.style.display = 'block';
    }
});

// Initialize Data
function initializeData() {
    const tbody = document.getElementById('jobRoleTableBody');
    const rows = tbody.querySelectorAll('.jobrole-row');
    
    allRows = Array.from(rows).map(row => {
        return {
            element: row,
            nama_job_role: row.dataset.nama_job_role,
            users_count: parseInt(row.dataset.users_count) || 0,
            status: row.dataset.status,
            dibuat_pada: row.dataset.dibuat_pada,
            diubah_pada: row.dataset.diubah_pada,
            originalIndex: parseInt(row.dataset.original_index)
        };
    });
    
    filteredRows = [...allRows];
}

// Initialize Event Listeners
function initializeEventListeners() {
    // Per Page Select
    const perPageSelect = document.getElementById('perPageSelect');
    perPageSelect.value = perPage;
    perPageSelect.addEventListener('change', function() {
        perPage = parseInt(this.value);
        currentPage = 1;
        renderTable();
    });
    
    // Search Input
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearch');
    
    searchInput.addEventListener('input', function() {
        const searchValue = this.value.trim();
        clearSearchBtn.style.display = searchValue.length > 0 ? 'block' : 'none';
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentSearch = searchValue.toLowerCase();
            applyFilters();
        }, 300);
    });
    
    // Clear Search
    clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        this.style.display = 'none';
        currentSearch = '';
        applyFilters();
    });
    
    // Column Toggles
    document.querySelectorAll('.column-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            toggleColumn(this.value, this.checked);
        });
    });
    
    // Sortable Headers
    document.querySelectorAll('.sortable').forEach(header => {
        header.addEventListener('click', function() {
            const column = this.dataset.column;
            const isAsc = this.classList.contains('asc');
            
            document.querySelectorAll('.sortable').forEach(h => h.classList.remove('asc', 'desc'));
            this.classList.add(isAsc ? 'desc' : 'asc');
            
            sortTable(column, isAsc ? 'desc' : 'asc');
        });
    });
}

// ===== COLUMN SETTINGS FUNCTIONS =====
function loadColumnSettings() {
    try {
        const savedSettings = localStorage.getItem(STORAGE_KEY);
        
        const defaultSettings = {
            'karyawan': true,
            'status': true,
            'dibuat': false,
            'diubah': false
        };
        
        if (savedSettings) {
            const settings = JSON.parse(savedSettings);
            
            document.querySelectorAll('.column-toggle:not([disabled])').forEach(toggle => {
                const columnName = toggle.value;
                if (settings.hasOwnProperty(columnName)) {
                    toggle.checked = settings[columnName];
                    toggleColumn(columnName, settings[columnName]);
                } else {
                    if (defaultSettings.hasOwnProperty(columnName)) {
                        toggle.checked = defaultSettings[columnName];
                        toggleColumn(columnName, defaultSettings[columnName]);
                    }
                }
            });
        } else {
            document.querySelectorAll('.column-toggle:not([disabled])').forEach(toggle => {
                const columnName = toggle.value;
                if (defaultSettings.hasOwnProperty(columnName)) {
                    toggle.checked = defaultSettings[columnName];
                    toggleColumn(columnName, defaultSettings[columnName]);
                } else {
                    toggleColumn(columnName, toggle.checked);
                }
            });
            
            saveColumnSettingsToStorage();
        }
    } catch (e) {
        console.error('Error loading column settings:', e);
        initializeDefaultColumns();
    }
}

function initializeDefaultColumns() {
    const defaultSettings = {
        'karyawan': true,
        'status': true,
        'dibuat': false,
        'diubah': false
    };
    
    document.querySelectorAll('.column-toggle:not([disabled])').forEach(toggle => {
        const columnName = toggle.value;
        if (defaultSettings.hasOwnProperty(columnName)) {
            toggle.checked = defaultSettings[columnName];
            toggleColumn(columnName, defaultSettings[columnName]);
        } else {
            toggleColumn(columnName, toggle.checked);
        }
    });
}

function saveColumnSettingsToStorage() {
    const settings = {};
    document.querySelectorAll('.column-toggle:not([disabled])').forEach(toggle => {
        settings[toggle.value] = toggle.checked;
    });
    localStorage.setItem(STORAGE_KEY, JSON.stringify(settings));
}

function toggleColumn(columnValue, show) {
    const elements = document.querySelectorAll(`.column-${columnValue}`);
    elements.forEach(el => {
        if (show) {
            el.classList.remove('column-hidden');
        } else {
            el.classList.add('column-hidden');
        }
    });
}

function saveColumnSettings() {
    saveColumnSettingsToStorage();
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('columnSettingsModal'));
    if (modal) modal.hide();
    
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Pengaturan kolom berhasil disimpan',
        showConfirmButton: false,
        timer: 1500
    });
}

function resetColumns() {
    const defaultSettings = {
        'karyawan': true,
        'status': true,
        'dibuat': false,
        'diubah': false
    };
    
    document.querySelectorAll('.column-toggle:not([disabled])').forEach(toggle => {
        const columnName = toggle.value;
        if (defaultSettings.hasOwnProperty(columnName)) {
            toggle.checked = defaultSettings[columnName];
            toggleColumn(columnName, defaultSettings[columnName]);
        } else {
            toggle.checked = true;
            toggleColumn(columnName, true);
        }
    });
}

// Sync checkboxes with column visibility when modal opens
document.getElementById('columnSettingsModal')?.addEventListener('show.bs.modal', function() {
    document.querySelectorAll('.column-toggle:not([disabled])').forEach(toggle => {
        const columnName = toggle.value;
        const element = document.querySelector(`.column-${columnName}`);
        if (element) {
            const isVisible = !element.classList.contains('column-hidden');
            toggle.checked = isVisible;
        }
    });
});

// ===== FILTER FUNCTIONS =====
function filterByStatus(status) {
    currentStatus = status;
    currentPage = 1;
    
    document.querySelectorAll('.badge-counter').forEach(badge => {
        if (badge.dataset.status === status) {
            badge.classList.add('active');
        } else {
            badge.classList.remove('active');
        }
    });
    
    applyFilters();
}

function applyFilters() {
    filteredRows = allRows.filter(row => {
        if (currentStatus !== '') {
            if (row.status !== currentStatus) {
                return false;
            }
        }
        
        if (currentSearch !== '') {
            if (!row.nama_job_role.includes(currentSearch)) {
                return false;
            }
        }
        
        return true;
    });
    
    currentPage = 1;
    renderTable();
}

// ===== SORT FUNCTION =====
function sortTable(column, direction) {
    filteredRows.sort((a, b) => {
        let aVal = a[column];
        let bVal = b[column];
        
        if (column === 'dibuat_pada' || column === 'diubah_pada') {
            aVal = new Date(aVal).getTime() || 0;
            bVal = new Date(bVal).getTime() || 0;
            return direction === 'asc' ? aVal - bVal : bVal - aVal;
        }
        
        if (column === 'users_count') {
            return direction === 'asc' ? aVal - bVal : bVal - aVal;
        }
        
        if (column === 'status') {
            aVal = parseInt(aVal) || 0;
            bVal = parseInt(bVal) || 0;
            return direction === 'asc' ? aVal - bVal : bVal - aVal;
        }
        
        aVal = String(aVal || '');
        bVal = String(bVal || '');
        return direction === 'asc' ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
    });
    
    renderTable();
}

// ===== RENDER FUNCTIONS =====
function renderTable() {
    const tbody = document.getElementById('jobRoleTableBody');
    tbody.innerHTML = '';
    
    const startIndex = (currentPage - 1) * perPage;
    const endIndex = Math.min(startIndex + perPage, filteredRows.length);
    const paginatedRows = filteredRows.slice(startIndex, endIndex);
    
    if (paginatedRows.length === 0) {
        let message = 'Belum ada data job role';
        
        if (currentStatus !== '' && currentSearch !== '') {
            const statusLabel = currentStatus === '1' ? 'Aktif' : 'Nonaktif';
            message = `Tidak ada data job role dengan status ${statusLabel} dan kata kunci "${currentSearch}"`;
        } else if (currentStatus !== '') {
            const statusLabel = currentStatus === '1' ? 'Aktif' : 'Nonaktif';
            message = `Tidak ada data job role dengan status ${statusLabel}`;
        } else if (currentSearch !== '') {
            message = `Tidak ada data job role dengan kata kunci "${currentSearch}"`;
        }
        
        tbody.innerHTML = `
            <tr id="emptyRow">
                <td colspan="7" class="text-center py-5">
                    <i class='bx bx-briefcase empty-icon'></i>
                    <p class="empty-text">${message}</p>
                </td>
            </tr>
        `;
    } else {
        paginatedRows.forEach((row, index) => {
            const rowClone = row.element.cloneNode(true);
            const rowNumber = rowClone.querySelector('.row-number');
            if (rowNumber) {
                rowNumber.textContent = startIndex + index + 1;
            }
            tbody.appendChild(rowClone);
        });
    }
    
    updateTableInfo(startIndex + 1, endIndex, filteredRows.length);
    renderPagination();
}

function updateTableInfo(start, end, total) {
    document.getElementById('showingStart').textContent = total === 0 ? 0 : start;
    document.getElementById('showingEnd').textContent = end;
    document.getElementById('totalEntries').textContent = total;
}

function renderPagination() {
    const paginationControls = document.getElementById('paginationControls');
    const totalPages = Math.ceil(filteredRows.length / perPage);
    
    if (totalPages <= 1) {
        paginationControls.innerHTML = '';
        return;
    }
    
    let html = '';
    
    html += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" onclick="goToPage(${currentPage - 1})">
                <i class='bx bx-chevron-left'></i>
            </a>
        </li>
    `;
    
    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
    
    if (endPage - startPage < maxVisiblePages - 1) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    if (startPage > 1) {
        html += `<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="goToPage(1)">1</a></li>`;
        if (startPage > 2) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
    }
    
    for (let i = startPage; i <= endPage; i++) {
        html += `
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="javascript:void(0)" onclick="goToPage(${i})">${i}</a>
            </li>
        `;
    }
    
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
        html += `<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="goToPage(${totalPages})">${totalPages}</a></li>`;
    }
    
    html += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" onclick="goToPage(${currentPage + 1})">
                <i class='bx bx-chevron-right'></i>
            </a>
        </li>
    `;
    
    paginationControls.innerHTML = html;
}

function goToPage(page) {
    const totalPages = Math.ceil(filteredRows.length / perPage);
    if (page < 1 || page > totalPages) return;
    
    currentPage = page;
    renderTable();
}

// ===== VIEW JOB ROLE =====
function viewJobRole(id) {
    const button = event.target.closest('[data-jobrole]');
    const jobRole = JSON.parse(button.dataset.jobrole);
    
    document.getElementById('view_nama_job_role').textContent = jobRole.nama_job_role || '-';
    document.getElementById('view_deskripsi').textContent = jobRole.deskripsi || 'Tidak ada deskripsi';
    document.getElementById('view_users_count').innerHTML = `<span class="employee-count"><i class='bx bx-user'></i> ${jobRole.users_count} orang</span>`;
    
    const statusElement = document.getElementById('view_status');
    if (jobRole.status) {
        statusElement.innerHTML = '<span class="status-badge status-active"><i class="bx bx-check-circle"></i> Aktif</span>';
    } else {
        statusElement.innerHTML = '<span class="status-badge status-inactive"><i class="bx bx-x-circle"></i> Nonaktif</span>';
    }
    
    document.getElementById('view_dibuat_pada').textContent = jobRole.dibuat_pada ? new Date(jobRole.dibuat_pada).toLocaleString('id-ID', { 
        day: '2-digit', 
        month: '2-digit', 
        year: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit' 
    }) : '-';
    
    document.getElementById('view_diubah_pada').textContent = jobRole.diubah_pada ? new Date(jobRole.diubah_pada).toLocaleString('id-ID', { 
        day: '2-digit', 
        month: '2-digit', 
        year: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit' 
    }) : '-';
    
    new bootstrap.Modal(document.getElementById('viewJobRoleModal')).show();
}

// ===== EDIT JOB ROLE =====
function editJobRole(id) {
    const button = event.target.closest('[data-jobrole]');
    const jobRole = JSON.parse(button.dataset.jobrole);
    
    document.getElementById('edit_nama_job_role').value = jobRole.nama_job_role;
    document.getElementById('edit_deskripsi').value = jobRole.deskripsi || '';
    document.getElementById('edit_status').value = jobRole.status ? '1' : '0';
    document.getElementById('editJobRoleForm').action = `/master-data-jobrole/${jobRole.id_job_role}`;
    
    new bootstrap.Modal(document.getElementById('editJobRoleModal')).show();
}

// ===== DELETE JOB ROLE =====
function deleteJobRole(id, nama, usersCount) {
    if (usersCount > 0) {
        Swal.fire({
            title: 'Tidak Dapat Dihapus!',
            html: `Job role <strong>${nama}</strong> masih memiliki <strong>${usersCount} karyawan</strong>.<br>Pindahkan atau hapus karyawan terlebih dahulu.`,
            icon: 'warning',
            confirmButtonColor: '#696cff',
            confirmButtonText: 'Mengerti'
        });
        return;
    }
    
    Swal.fire({
        title: 'Hapus Job Role?',
        html: `Anda akan menghapus <strong>${nama}</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e53e3e',
        cancelButtonColor: '#718096',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/master-data-jobrole/${id}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// ===== FORM SUBMISSIONS =====
['addJobRoleForm', 'editJobRoleForm'].forEach(formId => {
    const form = document.getElementById(formId);
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const modalId = formId === 'addJobRoleForm' ? 'addJobRoleModal' : 'editJobRoleModal';
            const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
            if (modal) modal.hide();
            
            setTimeout(() => {
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                this.submit();
            }, 300);
        });
    }
});

// ===== MUTATION OBSERVER FOR ROW NUMBERS =====
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
            updateRowNumbers();
        }
    });
});

// Observe all jobrole rows for style changes
document.querySelectorAll('.jobrole-row').forEach(row => {
    observer.observe(row, { attributes: true, attributeFilter: ['style'] });
});

function updateRowNumbers() {
    const visibleRows = Array.from(document.querySelectorAll('.jobrole-row'))
        .filter(row => row.style.display !== 'none');
    visibleRows.forEach((row, index) => {
        const numberCell = row.querySelector('.row-number');
        if (numberCell) numberCell.textContent = index + 1;
    });
}
</script>
@endsection