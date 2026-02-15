@extends('layouts.master')
@section('title', 'Master Data Tugas')
@section('styles')
{{-- Ganti dengan tema Bootstrap 4 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-1">Master Data Tugas</h4>
                <p class="text-muted mb-0">Kelola data tugas proyek</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTugasModal">
                <i class='bx bx-plus me-1'></i> Tambah Tugas
            </button>
        </div>
        
        <!-- Table Card -->
        <div class="card">
            <div class="card-header border-bottom">
                <form method="GET" action="{{ route('master-data-tugas.index') }}" id="filterForm">
                    <div class="row g-3 align-items-center">
                        <div class="col-lg-3 col-md-4">
                            <div class="d-flex align-items-center">
                                <label class="me-2 text-nowrap small fs-6">Show</label>
                                <select name="per_page" class="form-select fs-6" style="width: 80px;" onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <span class="ms-2 text-nowrap small fs-6">entries</span>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8 ms-auto d-flex justify-content-end gap-2">
                            <div class="input-group w-auto">
                                <span class="input-group-text fs-6"><i class='bx bx-search'></i></span>
                                <input type="text" name="search" id="searchInput" 
                                       class="form-control fs-6" 
                                       placeholder="Search..." 
                                       value="{{ request('search') }}" autocomplete="off">
                            </div>
                            <button type="button" class="btn btn-outline-secondary fs-6" 
                                    data-bs-toggle="modal" data-bs-target="#columnSettingsModal">
                                <i class='bx bx-cog me-1'></i> Kolom
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover table-nowrap">
                   <thead class="table-light">
    <tr>
        <th style="width: 60px;" class="column-no text-center">NO</th>
        <th class="column-projek sortable" data-column="id_projek" style="min-width: 200px;">
            <div class="d-flex align-items-center justify-content-between">
                <span>PROYEK</span>
                <i class='bx bx-sort sort-icon'></i>
            </div>
        </th>
        <th class="column-judul sortable" data-column="judul_tugas" style="min-width: 300px;">
            <div class="d-flex align-items-center justify-content-between">
                <span>JUDUL TUGAS</span>
                <i class='bx bx-sort sort-icon'></i>
            </div>
        </th>
        <!-- HAPUS kolom DESKRIPSI dari sini -->
        <th class="column-level" style="min-width: 120px;">LEVEL</th>
        <th class="column-weight" style="min-width: 100px;">WEIGHT</th>
        <th class="column-penanggung_jawab sortable" data-column="penanggung_jawab" style="min-width: 200px;">
            <div class="d-flex align-items-center justify-content-between">
                <span>PENANGGUNG JAWAB</span>
                <i class='bx bx-sort sort-icon'></i>
            </div>
        </th>
        <th class="column-status" style="min-width: 120px;">STATUS</th>
        <th class="column-tenggat sortable" data-column="tenggat_waktu" style="min-width: 180px;">
            <div class="d-flex align-items-center justify-content-between">
                <span>TENGGAT WAKTU</span>
                <i class='bx bx-sort sort-icon'></i>
            </div>
        </th>
        <th class="column-dibuat sortable" data-column="dibuat_pada" style="min-width: 180px;">
            <div class="d-flex align-items-center justify-content-between">
                <span>DIBUAT</span>
                <i class='bx bx-sort sort-icon'></i>
            </div>
        </th>
        <th class="column-diubah sortable" data-column="diubah_pada" style="min-width: 180px;">
            <div class="d-flex align-items-center justify-content-between">
                <span>DIUBAH</span>
                <i class='bx bx-sort sort-icon'></i>
            </div>
        </th>
        <th class="text-end column-actions text-center" style="min-width: 120px;">AKSI</th>
    </tr>
</thead>
                    <tbody id="tugasTableBody">
                        @forelse($tugas as $index => $item)
                        <tr class="tugas-row" 
                            data-original-index="{{ $index + 1 }}"
                            data-id_projek="{{ strtolower($item->projek->nama_projek ?? '') }}" 
                            data-judul_tugas="{{ strtolower($item->judul_tugas) }}"
                            data-penanggung_jawab="{{ strtolower($item->penanggungJawab->nama ?? '') }}"
                            data-tenggat_waktu="{{ $item->tenggat_waktu ?? '' }}"
                            data-dibuat_pada="{{ $item->dibuat_pada }}"
                            data-diubah_pada="{{ $item->diubah_pada }}">
                            <td class="column-no text-center">
                                <span class="row-number fw-semibold text-muted">{{ $index + 1 }}</span>
                            </td>
                            <td class="column-projek">
                                <div class="d-flex align-items-center">
                                    <div class="project-avatar me-2">
                                        <i class='bx bx-folder'></i>
                                    </div>
                                    <span class="text-dark">{{ $item->projek->nama_projek ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="column-judul">
    <div>
        <div class="text-dark fw-medium mb-1">{{ $item->judul_tugas }}</div>
        <div class="column-deskripsi">
            <small class="text-muted" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ $item->deskripsi_tugas ?: 'Tidak ada deskripsi' }}
            </small>
        </div>
    </div>
</td>
                           
                            <td class="column-level">
                                @php
                                    $levelClass = [
                                        'mudah' => 'success',
                                        'medium' => 'warning',
                                        'susah' => 'danger'
                                    ][$item->level] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $levelClass }}">{{ ucfirst($item->level) }}</span>
                            </td>
                            <td class="column-weight">
                                <span class="text-dark">{{ $item->weight }}</span>
                            </td>
                            <td class="column-penanggung_jawab">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-2">
                                        @if($item->penanggungJawab && $item->penanggungJawab->foto_profil)
                                            <img src="{{ asset('storage/' . $item->penanggungJawab->foto_profil) }}" alt="{{ $item->penanggungJawab->nama }}">
                                        @else
                                            <i class='bx bx-user'></i>
                                        @endif
                                    </div>
                                    <span class="text-dark">{{ $item->penanggungJawab->nama ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="column-status">
                                @php
                                    $statusClass = [
                                        'draft' => 'secondary',
                                        'publis' => 'info',
                                        'progres' => 'primary',
                                        'done' => 'success'
                                    ][$item->status] ?? 'secondary';
                                    $statusLabel = [
                                        'draft' => 'Draft',
                                        'publis' => 'Publis',
                                        'progres' => 'Progres',
                                        'done' => 'Done'
                                    ][$item->status] ?? $item->status;
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td class="column-tenggat">
                                @if($item->tenggat_waktu)
                                    @php
                                        $today = \Carbon\Carbon::now()->startOfDay();
                                        $deadline = \Carbon\Carbon::parse($item->tenggat_waktu)->startOfDay();
                                        $isOverdue = $deadline->lt($today);
                                        $colorClass = $isOverdue ? 'text-danger' : 'text-primary';
                                    @endphp
                                    <div class="{{ $colorClass }} small fw-medium">
                                        <i class='bx bx-calendar me-1'></i>
                                        {{ \Carbon\Carbon::parse($item->tenggat_waktu)->format('d/m/Y') }}
                                        @if($isOverdue)
                                            <span class="badge bg-danger ms-1">Terlambat</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="column-dibuat">
                                <div class="text-dark small">
                                    {{ \Carbon\Carbon::parse($item->dibuat_pada)->format('d/m/Y H:i') }}
                                </div>
                            </td>
                            <td class="column-diubah">
                                <div class="text-dark small">
                                    {{ \Carbon\Carbon::parse($item->diubah_pada)->format('d/m/Y H:i') }}
                                </div>
                            </td>
                            <td class="text-end column-actions text-center">
                                <div class="d-inline-flex gap-1">
                                    <button type="button" 
                                            class="btn btn-sm btn-text-secondary rounded-pill btn-icon" 
                                            onclick="editTugas({{ $item->id_tugas }})"
                                            data-tugas='@json($item)'
                                            title="Edit">
                                        <i class='bx bx-edit'></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-text-danger rounded-pill btn-icon" 
                                            onclick="deleteTugas({{ $item->id_tugas }}, '{{ $item->judul_tugas }}')"
                                            title="Delete">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <i class='bx bx-task' style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="text-muted mt-3 mb-0">Belum ada data tugas</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer border-top">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0 text-muted fs-6" id="tableInfo">
                            Showing 1 to {{ $tugas->count() }} of {{ $tugas->total() }} entries
                        </p>
                    </div>
                    <div class="col-md-6">
                        <nav>
                            <ul class="pagination pagination-sm justify-content-md-end mb-0 fs-6">
                                {{ $tugas->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Tugas Modal -->
<div class="modal fade" id="addTugasModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Tugas Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('master-data-tugas.store') }}" method="POST" id="addTugasForm">
                @csrf
                <div class="modal-body">
                   <div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Proyek *</label>
        <select name="id_projek" id="add_id_projek" class="form-select select2-projek" required>
            <option value="">-- Pilih Proyek --</option>
            @foreach($projeks as $projek)
                <option value="{{ $projek->id_projek }}">{{ $projek->nama_projek }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Penanggung Jawab *</label>
        <select name="penanggung_jawab" id="add_penanggung_jawab" class="form-select select2-user" required>
            <option value="">-- Pilih Penanggung Jawab --</option>
            @foreach($users as $user)
                <option value="{{ $user->id_user }}">{{ $user->nama }}</option>
            @endforeach
        </select>
    </div>
</div>
                    <div class="mb-3">
                        <label class="form-label">Judul Tugas *</label>
                        <input type="text" name="judul_tugas" class="form-control" required maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Tugas</label>
                        <textarea name="deskripsi_tugas" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Level *</label>
                            <select name="level" class="form-select" required>
                                <option value="mudah">Mudah</option>
                                <option value="medium" selected>Medium</option>
                                <option value="susah">Susah</option>
                            </select>
                        </div>
                       <div class="col-md-4 mb-3">
    <label class="form-label">Weight *</label>
    <input type="number" name="weight" id="add_weight" class="form-control" value="2" min="1" required readonly style="background-color: #f0f0f0;">
</div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-select" required>
                                <option value="draft" selected>Draft</option>
                                <option value="publis">Publis</option>
                                <option value="progres">Progres</option>
                                <option value="done">Done</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tenggat Waktu</label>
                        <input type="date" name="tenggat_waktu" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Edit Tugas Modal -->
<div class="modal fade" id="editTugasModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Tugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editTugasForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Proyek *</label>
        <select name="id_projek" id="edit_id_projek" class="form-select select2-projek" required>
            <option value="">-- Pilih Proyek --</option>
            @foreach($projeks as $projek)
                <option value="{{ $projek->id_projek }}">{{ $projek->nama_projek }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Penanggung Jawab *</label>
        <select name="penanggung_jawab" id="edit_penanggung_jawab" class="form-select select2-user" required>
            <option value="">-- Pilih Penanggung Jawab --</option>
            @foreach($users as $user)
                <option value="{{ $user->id_user }}">{{ $user->nama }}</option>
            @endforeach
        </select>
    </div>
</div>
                    <div class="mb-3">
                        <label class="form-label">Judul Tugas *</label>
                        <input type="text" name="judul_tugas" id="edit_judul_tugas" class="form-control" required maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Tugas</label>
                        <textarea name="deskripsi_tugas" id="edit_deskripsi_tugas" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Level *</label>
                            <select name="level" id="edit_level" class="form-select" required>
                                <option value="mudah">Mudah</option>
                                <option value="medium">Medium</option>
                                <option value="susah">Susah</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
    <label class="form-label">Weight *</label>
    <input type="number" name="weight" id="edit_weight" class="form-control" min="1" required readonly style="background-color: #f0f0f0;">
</div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" id="edit_status" class="form-select" required>
                                <option value="draft">Draft</option>
                                <option value="publis">Publis</option>
                                <option value="progres">Progres</option>
                                <option value="done">Done</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tenggat Waktu</label>
                        <input type="date" name="tenggat_waktu" id="edit_tenggat_waktu" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Column Settings Modal -->
<div class="modal fade" id="columnSettingsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pengaturan Kolom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="no" id="col-no" checked disabled>
                    <label class="form-check-label" for="col-no">No Urut</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="projek" id="col-projek" checked disabled>
                    <label class="form-check-label" for="col-projek">Proyek</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="judul" id="col-judul" checked disabled>
                    <label class="form-check-label" for="col-judul">Judul Tugas</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="deskripsi" id="col-deskripsi" checked>
                    <label class="form-check-label" for="col-deskripsi">Deskripsi</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="level" id="col-level" checked>
                    <label class="form-check-label" for="col-level">Level</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="weight" id="col-weight">
                    <label class="form-check-label" for="col-weight">Weight</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="penanggung_jawab" id="col-penanggung_jawab" checked>
                    <label class="form-check-label" for="col-penanggung_jawab">Penanggung Jawab</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="status" id="col-status" checked>
                    <label class="form-check-label" for="col-status">Status</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="tenggat" id="col-tenggat" checked>
                    <label class="form-check-label" for="col-tenggat">Tenggat Waktu</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="dibuat" id="col-dibuat">
                    <label class="form-check-label" for="col-dibuat">Dibuat</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="diubah" id="col-diubah">
                    <label class="form-check-label" for="col-diubah">Diubah</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="actions" id="col-actions" checked disabled>
                    <label class="form-check-label" for="col-actions">Aksi</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" onclick="resetColumns()">Reset</button>
                <button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Simpan</button>
            </div>
        </div>
    </div>
</div>

<style>
.project-avatar {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
    flex-shrink: 0;
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    flex-shrink: 0;
    overflow: hidden;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table > :not(caption) > * > * {
    padding: 0.875rem 0.75rem;
    vertical-align: middle;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(67, 89, 113, 0.04);
}

.table thead th {
    font-weight: 600;
    font-size: 0.8125rem;
    letter-spacing: 0.3px;
    color: #566a7f;
    text-transform: none !important;
}

.table tbody td {
    color: #566a7f !important;
}

.table tbody td .text-dark {
    color: #566a7f !important;
}

.sortable {
    cursor: pointer;
    user-select: none;
    transition: background-color 0.2s ease;
}

.sortable:hover {
    background-color: rgba(67, 89, 113, 0.08);
}

.sort-icon {
    font-size: 16px;
    color: #a8b1bb;
    transition: all 0.2s ease;
}

.sortable.asc .sort-icon {
    color: #696cff;
    transform: rotate(180deg);
}

.sortable.desc .sort-icon {
    color: #696cff;
}

.btn-icon {
    transition: all 0.2s ease;
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-icon:hover {
    transform: scale(1.1);
}

.btn-text-secondary:hover {
    background-color: rgba(105, 108, 255, 0.08);
    color: #696cff !important;
}

.btn-text-danger:hover {
    background-color: rgba(255, 62, 29, 0.08);
    color: #ff3e1d !important;
}

.form-control:focus, .form-select:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.1);
}

th.column-hidden, td.column-hidden {
    display: none !important;
}

.column-judul .text-muted {
    line-height: 1.4;
    max-width: 100%;
}

@media (max-width: 768px) {
    .card-header .row > div {
        margin-bottom: 0.5rem;
    }
    
    .table > :not(caption) > * > * {
        padding: 0.75rem 0.5rem;
        font-size: 0.875rem;
    }
}

.table-responsive::-webkit-scrollbar {
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.autocomplete-wrapper {
    position: relative;
}

.autocomplete-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #d9dee3;
    border-top: none;
    border-radius: 0 0 0.375rem 0.375rem;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1050;
    display: none;
    box-shadow: 0 0.25rem 1rem rgba(161, 172, 184, 0.45);
}

.autocomplete-suggestion-item {
    padding: 0.5rem 0.75rem;
    cursor: pointer;
    transition: background-color 0.2s;
}

.autocomplete-suggestion-item:hover {
    background-color: #f8f9fa;
}

.autocomplete-suggestion-item.active {
    background-color: #696cff;
    color: white;
}

.autocomplete-suggestions-empty {
    padding: 0.75rem;
    text-align: center;
    color: #6c757d;
    font-size: 0.875rem;
}


/* Select2 Custom Styling */
.select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #d9dee3;
    border-radius: 0.375rem;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px;
    padding-left: 12px;
    color: #566a7f;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}

.select2-container--default.select2-container--open .select2-selection--single {
    border-color: #696cff;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #696cff;
}

.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #d9dee3;
    border-radius: 0.375rem;
    padding: 8px 12px;
}

.select2-container--default .select2-search--dropdown .select2-search__field:focus {
    border-color: #696cff;
    outline: none;
}

.select2-dropdown {
    border: 1px solid #d9dee3;
    border-radius: 0.375rem;
    box-shadow: 0 0.25rem 1rem rgba(161, 172, 184, 0.45);
}

.select2-results__option {
    padding: 8px 12px;
}
/* Tambahkan di <style> */
.select2-search--dropdown {
    display: block !important;
    padding: 4px;
}

.select2-search__field {
    width: 100% !important;
    padding: 4px 8px !important;
    box-sizing: border-box !important;
}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    console.log('jQuery version:', $.fn.jquery);
console.log('Select2 loaded:', typeof $.fn.select2);
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonText: 'OK',
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
        confirmButtonText: 'OK',
        confirmButtonColor: '#696cff'
    });
@endif

// Global data
let projekData = @json($projeks);
let userData = @json($users);

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
    initializeAutocomplete();
    initializeColumnSettings();
    initializeSearchFunctionality();
    setupWeightAutoFill('level', 'add_weight');
    initializeSelect2();
});

function initializeSelect2() {
    // Initialize Select2 untuk Proyek
    $('.select2-projek').select2({
        theme: 'bootstrap4',  // TAMBAH INI
        placeholder: 'Ketik untuk mencari proyek...',
        allowClear: true,
        width: '100%',
        minimumResultsForSearch: 0,
        dropdownParent: $(this).closest('.modal')
    });

    // Initialize Select2 untuk User
    $('.select2-user').select2({
        theme: 'bootstrap4',  // TAMBAH INI
        placeholder: 'Ketik untuk mencari penanggung jawab...',
        allowClear: true,
        width: '100%',
        minimumResultsForSearch: 0,
        dropdownParent: $(this).closest('.modal')
    });
}

// Autocomplete Initialization
function initializeAutocomplete() {
    // Setup untuk modal tambah
    setupAutocomplete('add_projek_search', 'add_id_projek', 'add_projek_suggestions', projekData, 'nama_projek', 'id_projek');
    setupAutocomplete('add_user_search', 'add_penanggung_jawab', 'add_user_suggestions', userData, 'nama', 'id_user');
    
    // Setup untuk modal edit
    setupAutocomplete('edit_projek_search', 'edit_id_projek', 'edit_projek_suggestions', projekData, 'nama_projek', 'id_projek');
    setupAutocomplete('edit_user_search', 'edit_penanggung_jawab', 'edit_user_suggestions', userData, 'nama', 'id_user');
}

function setupAutocomplete(searchInputId, hiddenInputId, suggestionsId, data, nameKey, idKey) {
    const searchInput = document.getElementById(searchInputId);
    const hiddenInput = document.getElementById(hiddenInputId);
    const suggestionsDiv = document.getElementById(suggestionsId);
    
    if (!searchInput || !hiddenInput || !suggestionsDiv) return;
    
    let selectedIndex = -1;
    let filteredData = [];
    
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        if (query.length < 2) {
            suggestionsDiv.style.display = 'none';
            hiddenInput.value = '';
            return;
        }
        
        // Filter data
        filteredData = data.filter(item => 
            item[nameKey].toLowerCase().includes(query.toLowerCase())
        );
        
        displaySuggestions(filteredData, suggestionsDiv, searchInput, hiddenInput, nameKey, idKey);
        selectedIndex = -1;
    });
    
    // Keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        const items = suggestionsDiv.querySelectorAll('.autocomplete-suggestion-item');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
            updateSelectedItem(items, selectedIndex);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = Math.max(selectedIndex - 1, -1);
            updateSelectedItem(items, selectedIndex);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (selectedIndex >= 0 && items[selectedIndex]) {
                items[selectedIndex].click();
            }
        } else if (e.key === 'Escape') {
            suggestionsDiv.style.display = 'none';
            selectedIndex = -1;
        }
    });
    
    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
            suggestionsDiv.style.display = 'none';
            selectedIndex = -1;
        }
    });
}

function displaySuggestions(data, suggestionsDiv, searchInput, hiddenInput, nameKey, idKey) {
    if (data.length === 0) {
        suggestionsDiv.innerHTML = '<div class="autocomplete-suggestions-empty">Tidak ada data ditemukan</div>';
        suggestionsDiv.style.display = 'block';
        return;
    }
    
    let html = '';
    data.forEach(item => {
        html += `<div class="autocomplete-suggestion-item" 
                      data-id="${item[idKey]}" 
                      data-name="${item[nameKey]}">
                    ${item[nameKey]}
                 </div>`;
    });
    
    suggestionsDiv.innerHTML = html;
    suggestionsDiv.style.display = 'block';
    
    // Add click handlers
    suggestionsDiv.querySelectorAll('.autocomplete-suggestion-item').forEach(item => {
        item.addEventListener('click', function() {
            searchInput.value = this.dataset.name;
            hiddenInput.value = this.dataset.id;
            suggestionsDiv.style.display = 'none';
        });
    });
}

function updateSelectedItem(items, index) {
    items.forEach((item, i) => {
        if (i === index) {
            item.classList.add('active');
            item.scrollIntoView({ block: 'nearest' });
        } else {
            item.classList.remove('active');
        }
    });
}

// Column Toggle Functions
function initializeColumnSettings() {
    toggleColumn('weight', false);
    document.getElementById('col-weight').checked = false;
    
    toggleColumn('penanggung_jawab', false);
    document.getElementById('col-penanggung_jawab').checked = false;
    
    toggleColumn('dibuat', false);
    document.getElementById('col-dibuat').checked = false;
    
    toggleColumn('diubah', false);
    document.getElementById('col-diubah').checked = false;
}

function toggleColumn(columnValue, show) {
    const headers = document.querySelectorAll(`th.column-${columnValue}`);
    const cells = document.querySelectorAll(`td.column-${columnValue}`);
    
    headers.forEach(el => {
        if (show) {
            el.classList.remove('column-hidden');
        } else {
            el.classList.add('column-hidden');
        }
    });
    
    cells.forEach(el => {
        if (show) {
            el.classList.remove('column-hidden');
        } else {
            el.classList.add('column-hidden');
        }
    });
}

document.querySelectorAll('.column-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        toggleColumn(this.value, this.checked);
    });
});

function resetColumns() {
    document.querySelectorAll('.column-toggle').forEach(toggle => {
        if (!toggle.disabled) {
            toggle.checked = true;
            toggleColumn(toggle.value, true);
        }
    });
}

// Update Row Numbers
function updateRowNumbers() {
    const visibleRows = Array.from(document.querySelectorAll('.tugas-row')).filter(row => row.style.display !== 'none');
    visibleRows.forEach((row, index) => {
        const numberCell = row.querySelector('.row-number');
        if (numberCell) numberCell.textContent = index + 1;
    });
}

// Sorting
document.querySelectorAll('.sortable').forEach(header => {
    header.addEventListener('click', function() {
        const column = this.dataset.column;
        const isAsc = this.classList.contains('asc');
        
        document.querySelectorAll('.sortable').forEach(h => h.classList.remove('asc', 'desc'));
        this.classList.add(isAsc ? 'desc' : 'asc');
        
        sortTable(column, isAsc ? 'desc' : 'asc');
    });
});

function sortTable(column, direction) {
    const tbody = document.getElementById('tugasTableBody');
    const rows = Array.from(tbody.querySelectorAll('.tugas-row'));
    
    rows.sort((a, b) => {
        let aVal = a.dataset[column] || '';
        let bVal = b.dataset[column] || '';
        
        if (column === 'dibuat_pada' || column === 'diubah_pada' || column === 'tenggat_waktu') {
            aVal = new Date(aVal).getTime() || 0;
            bVal = new Date(bVal).getTime() || 0;
            return direction === 'asc' ? aVal - bVal : bVal - aVal;
        }
        
        return direction === 'asc' ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
    });
    
    rows.forEach(row => tbody.appendChild(row));
    updateRowNumbers();
}

// Edit Tugas

// Update fungsi editTugas
function editTugas(id) {
    const button = event.target.closest('[data-tugas]');
    const tugas = JSON.parse(button.dataset.tugas);
    
    // Set nilai untuk Select2
    $('#edit_id_projek').val(tugas.id_projek).trigger('change');
    $('#edit_penanggung_jawab').val(tugas.penanggung_jawab).trigger('change');
    
    document.getElementById('edit_judul_tugas').value = tugas.judul_tugas;
    document.getElementById('edit_deskripsi_tugas').value = tugas.deskripsi_tugas || '';
    document.getElementById('edit_level').value = tugas.level;
    document.getElementById('edit_status').value = tugas.status;
    
    if (tugas.tenggat_waktu) {
        const date = new Date(tugas.tenggat_waktu);
        const formattedDate = date.toISOString().split('T')[0];
        document.getElementById('edit_tenggat_waktu').value = formattedDate;
    } else {
        document.getElementById('edit_tenggat_waktu').value = '';
    }
    
    document.getElementById('editTugasForm').action = `/master-data-tugas/${tugas.id_tugas}`;
    
    new bootstrap.Modal(document.getElementById('editTugasModal')).show();
}


// Delete Tugas
function deleteTugas(id, judul) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        html: `Anda akan menghapus tugas <strong>${judul}</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Menghapus...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/master-data-tugas/${id}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Weight Auto-fill
function setupWeightAutoFill(levelSelectId, weightInputId) {
    const levelSelect = document.querySelector(`select[name="${levelSelectId}"], #${levelSelectId}`);
    const weightInput = document.querySelector(`input[name="${weightInputId}"], #${weightInputId}`);
    
    if (levelSelect && weightInput) {
        levelSelect.addEventListener('change', function() {
            const weightMap = {
                'mudah': 1,
                'medium': 2,
                'susah': 3
            };
            weightInput.value = weightMap[this.value] || 2;
        });
        
        const weightMap = {
            'mudah': 1,
            'medium': 2,
            'susah': 3
        };
        weightInput.value = weightMap[levelSelect.value] || 2;
    }
}
['addTugasForm', 'editTugasForm'].forEach(formId => {
    document.getElementById(formId).addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validasi projek
        const projekSelectId = formId === 'addTugasForm' ? '#add_id_projek' : '#edit_id_projek';
        if (!$(projekSelectId).val()) {
            Swal.fire({
                icon: 'error',
                title: 'Proyek Belum Dipilih',
                text: 'Silakan pilih proyek terlebih dahulu',
                confirmButtonText: 'OK',
                confirmButtonColor: '#696cff'
            });
            $(projekSelectId).select2('open');
            return;
        }
        
        // Validasi penanggung jawab
        const userSelectId = formId === 'addTugasForm' ? '#add_penanggung_jawab' : '#edit_penanggung_jawab';
        if (!$(userSelectId).val()) {
            Swal.fire({
                icon: 'error',
                title: 'Penanggung Jawab Belum Dipilih',
                text: 'Silakan pilih penanggung jawab terlebih dahulu',
                confirmButtonText: 'OK',
                confirmButtonColor: '#696cff'
            });
            $(userSelectId).select2('open');
            return;
        }
        
        const modalId = formId === 'addTugasForm' ? 'addTugasModal' : 'editTugasModal';
        const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
        
        if (modal) modal.hide();
        
        setTimeout(() => {
            Swal.fire({
                title: 'Memproses...',
                html: formId === 'addTugasForm' ? 'Menyimpan data tugas...' : 'Memperbarui data tugas...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });
            
            this.submit();
        }, 300);
    });
});

// Auto Search
function initializeSearchFunctionality() {
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const searchValue = this.value.toLowerCase().trim();
            
            searchTimeout = setTimeout(function() {
                const rows = document.querySelectorAll('.tugas-row');
                let visibleCount = 0;
                
                rows.forEach(row => {
                    const projek = row.dataset.id_projek || '';
                    const judul = row.dataset.judul_tugas || '';
                    const penanggungJawab = row.dataset.penanggung_jawab || '';
                    const deskripsi = row.querySelector('.column-deskripsi .text-muted')?.textContent.toLowerCase() || '';
                    
                    const matches = projek.includes(searchValue) || 
                                   judul.includes(searchValue) || 
                                   penanggungJawab.includes(searchValue) ||
                                   deskripsi.includes(searchValue);
                    
                    if (matches || searchValue === '') {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                updateRowNumbers();
                
                const totalEntries = rows.length;
                document.getElementById('tableInfo').textContent = 
                    `Showing ${visibleCount} of ${totalEntries} entries` + 
                    (searchValue ? ` (filtered)` : '');
                
                const tbody = document.getElementById('tugasTableBody');
                const emptyRow = tbody.querySelector('td[colspan]')?.parentElement;
                
                if (visibleCount === 0 && !emptyRow) {
                    const newEmptyRow = document.createElement('tr');
                    newEmptyRow.className = 'empty-search-row';
                    newEmptyRow.innerHTML = `
                        <td colspan="11" class="text-center py-5">
                            <i class='bx bx-search-alt' style="font-size: 48px; opacity: 0.3;"></i>
                            <p class="text-muted mt-3 mb-0">Tidak ada data yang sesuai dengan pencarian "${searchValue}"</p>
                        </td>
                    `;
                    tbody.appendChild(newEmptyRow);
                } else if (visibleCount > 0) {
                    const emptySearchRow = tbody.querySelector('.empty-search-row');
                    if (emptySearchRow) {
                        emptySearchRow.remove();
                    }
                }
            }, 300);
        });
    }
}

// Modal Reset Handlers - Update untuk Select2
document.getElementById('addTugasModal')?.addEventListener('hidden.bs.modal', function() {
    document.getElementById('addTugasForm').reset();
    $('#add_id_projek').val(null).trigger('change');
    $('#add_penanggung_jawab').val(null).trigger('change');
});

document.getElementById('editTugasModal')?.addEventListener('hidden.bs.modal', function() {
    $('#edit_id_projek').val(null).trigger('change');
    $('#edit_penanggung_jawab').val(null).trigger('change');
});

document.getElementById('editTugasModal').addEventListener('show.bs.modal', function() {
    setupWeightAutoFill('edit_level', 'edit_weight');
    
    const editLevel = document.getElementById('edit_level');
    if (editLevel) {
        const event = new Event('change');
        editLevel.dispatchEvent(event);
    }
});
</script>
@endsection