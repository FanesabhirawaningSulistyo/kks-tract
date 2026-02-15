@extends('layouts.master')
@section('title', 'Master Data Users')
@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-semibold mb-1">Master Data Users</h4>
                <p class="text-muted mb-0 small">Kelola data pengguna sistem</p>
            </div>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class='bx bx-plus'></i> Tambah User
            </button>
        </div>
        
        <!-- Table Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <form method="GET" action="{{ route('master-data-users.index') }}" id="filterForm">
                    <div class="row g-3">
                        <!-- Row 1: Job Role Counters -->
                        <div class="col-12">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <!-- Total User Counter -->
                                <a href="javascript:void(0)" 
                                   class="text-decoration-none job-role-filter"
                                   data-job-role="">
                                    <span class="badge-counter {{ !request()->has('job_role') || request('job_role') === '' ? 'active' : '' }}">
                                        <span class="count">{{ $totalCount ?? 0 }}</span>
                                        <span class="label">Semua User</span>
                                    </span>
                                </a>
                                
                                <!-- Job Role Counters -->
                                @foreach($allCounts as $countData)
                                    @if($countData['count'] > 0)
                                        <a href="javascript:void(0)" 
                                           class="text-decoration-none job-role-filter"
                                           data-job-role="{{ $countData['id'] }}">
                                            <span class="badge-counter {{ request('job_role') == $countData['id'] ? 'active' : '' }}">
                                                <span class="count">{{ $countData['count'] }}</span>
                                                <span class="label">{{ $countData['name'] }}</span>
                                            </span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Row 2: Filters -->
                        <div class="col-12">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <!-- Left side: Pagination -->
                                <div class="d-flex align-items-center">
                                    <label class="me-2 text-nowrap small fs-6">Tampilkan:</label>
                                    <select name="per_page" id="perPageSelect" class="form-select fs-6" style="width: 80px;">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                                
                                <!-- Right side: Role filter + Search + Column settings -->
                                <div class="d-flex align-items-center gap-2">
                                    <select name="role" id="roleSelect" class="form-select form-select-sm" style="width: auto;">
                                        <option value="">Semua Role</option>
                                        <option value="klien" {{ request('role') == 'klien' ? 'selected' : '' }}>Klien</option>
                                        <option value="karyawan" {{ request('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                        <option value="PM" {{ request('role') == 'PM' ? 'selected' : '' }}>Project Manager</option>
                                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    
                                    <div class="search-box">
                                        <i class='bx bx-search'></i>
                                        <input type="text" name="search" id="searchInput" 
                                               placeholder="Cari user..." 
                                               value="{{ request('search') }}" 
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
                    
                    <input type="hidden" name="job_role" id="jobRoleInput" value="{{ request('job_role') }}">
                </form>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="column-no" style="width: 60px;">NO</th>
                            <th class="column-nama sortable" data-column="nama" style="min-width: 250px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>NAMA</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-job_role sortable" data-column="job_role" style="min-width: 150px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>JABATAN</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-no_hp sortable" data-column="no_hp" style="min-width: 130px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>NO. HP</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-role sortable" data-column="role" style="min-width: 120px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>ROLE</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-status sortable" data-column="status" style="min-width: 100px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>STATUS</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-created_at sortable" data-column="created_at" style="min-width: 140px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>DIBUAT</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-updated_at sortable" data-column="updated_at" style="min-width: 140px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>DIUPDATE</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-actions text-center" style="width: 120px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        @forelse($users as $index => $user)
                        @php
                            $jobRole = $user->jobRole;
                            $jobRoleName = $jobRole ? $jobRole->nama_job_role : '-';
                            $jobRoleId = $jobRole ? $jobRole->id_job_role : '';
                            $jobRoleColor = $user->jobRoleColor ?? 'default';
                        @endphp
                        <tr class="user-row" 
                            data-id="{{ $user->id_user }}"
                            data-original-index="{{ $index + 1 }}"
                            data-role="{{ $user->role }}" 
                            data-nama="{{ strtolower($user->nama) }}" 
                            data-email="{{ strtolower($user->email) }}"
                            data-job_role="{{ strtolower($jobRoleName) }}"
                            data-job_role_id="{{ $jobRoleId }}"
                            data-no_hp="{{ strtolower($user->no_hp ?? '') }}"
                            data-status="{{ $user->status ? '1' : '0' }}"
                            data-created_at="{{ $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : '' }}"
                            data-updated_at="{{ $user->updated_at ? $user->updated_at->format('Y-m-d H:i:s') : '' }}">
                            <td class="column-no">
                                <span class="row-number">{{ $index + 1 }}</span>
                            </td>
                            <td class="column-nama">
                                <div class="d-flex align-items-center">
                                    @if($user->foto)
                                    <img src="{{ asset('storage/' . $user->foto) }}" 
                                         alt="{{ $user->nama }}" 
                                         class="avatar-img" 
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="avatar-circle" style="display: none;">
                                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                                    </div>
                                    @else
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                                    </div>
                                    @endif
                                    <div class="ms-3">
                                        <div class="user-name">{{ $user->nama }}</div>
                                        <div class="user-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="column-job_role">
                                @if($jobRole)
                                <span class="job-role-badge job-role-{{ $jobRoleColor }}">
                                    {{ $jobRoleName }}
                                </span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="column-no_hp">{{ $user->no_hp ?? '-' }}</td>
                            <td class="column-role">
                                @php
                                    $roleConfig = [
                                        'admin' => ['icon' => 'bx-crown', 'label' => 'Admin'],
                                        'PM' => ['icon' => 'bx-briefcase', 'label' => 'PM'],
                                        'karyawan' => ['icon' => 'bx-user', 'label' => 'Karyawan'],
                                        'klien' => ['icon' => 'bx-shield-alt-2', 'label' => 'Klien']
                                    ];
                                    $config = $roleConfig[$user->role] ?? $roleConfig['karyawan'];
                                @endphp
                                <span class="role-badge">
                                    <i class='bx {{ $config['icon'] }}'></i>
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td class="column-status">
                                @if($user->status)
                                    <span class="status-badge status-active">
                                        <i class='bx bx-check-circle'></i> Aktif
                                    </span>
                                @else
                                    <span class="status-badge status-inactive">
                                        <i class='bx bx-x-circle'></i> Non-Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="column-created_at">
                                <span class="date-text">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}</span>
                            </td>
                            <td class="column-updated_at">
                                <span class="date-text">{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : '-' }}</span>
                            </td>
                            <td class="column-actions text-center">
                                <div class="action-buttons">
                                    <button type="button" 
                                            class="action-btn view-btn" 
                                            onclick="viewUser({{ $user->id_user }})"
                                            data-user='@json($user)'
                                            data-job-role-name="{{ $jobRoleName }}"
                                            data-job-role-color="{{ $jobRoleColor }}"
                                            title="Lihat Detail">
                                        <i class='bx bx-show'></i>
                                    </button>
                                    <button type="button" 
                                            class="action-btn edit-btn" 
                                            onclick="editUser({{ $user->id_user }})"
                                            data-user='@json($user)'
                                            data-job-role-id="{{ $jobRoleId }}"
                                            title="Edit">
                                        <i class='bx bx-edit'></i>
                                    </button>
                                    <button type="button" 
                                            class="action-btn delete-btn" 
                                            onclick="deleteUser({{ $user->id_user }}, '{{ $user->nama }}')"
                                            title="Hapus">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="9" class="text-center py-5">
                                <i class='bx bx-user-x empty-icon'></i>
                                <p class="empty-text">Belum ada data user</p>
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
                            Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
                        </p>
                    </div>
                    <div class="col-md-6">
                        <nav>
                            <ul class="pagination pagination-sm justify-content-md-end mb-0">
                                {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">Detail User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="row g-4">
                    <!-- Foto Profil -->
                    <div class="col-12 text-center">
                        <div id="view_foto_container"></div>
                    </div>
                    
                    <!-- Informasi Detail -->
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">Nama Lengkap</label>
                        <p class="fw-semibold mb-0" id="view_nama">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">Email</label>
                        <p class="fw-semibold mb-0" id="view_email">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">Role</label>
                        <div id="view_role_badge"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">Job Role</label>
                        <div id="view_job_role"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">No. HP</label>
                        <p class="fw-semibold mb-0" id="view_no_hp">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">Status</label>
                        <div id="view_status_badge"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">ID User</label>
                        <p class="fw-semibold mb-0" id="view_id_user">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">Dibuat Pada</label>
                        <p class="fw-semibold mb-0" id="view_created_at">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">Terakhir Diupdate</label>
                        <p class="fw-semibold mb-0" id="view_updated_at">-</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('master-data-users.store') }}" method="POST" enctype="multipart/form-data" id="addUserForm">
                @csrf
                <div class="modal-body pt-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">Nama Lengkap *</label>
                            <input type="text" name="nama" class="form-control" required maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Password *</label>
                            <div class="input-group">
                                <input type="password" name="password" id="add_password" class="form-control" required minlength="8">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('add_password', 'add_password_icon')">
                                    <i class='bx bx-hide' id="add_password_icon"></i>
                                </button>
                            </div>
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Role *</label>
                            <select name="role" id="add_role" class="form-select" required onchange="toggleJobRoleField(this.value, 'add')">
                                <option value="">Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="karyawan">Karyawan</option>
                                <option value="PM">Project Manager</option>
                                <option value="klien">Klien</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="addJobRoleField">
                            <label class="form-label small">Job Role</label>
                            <div class="custom-search-dropdown">
                                <div class="dropdown-display" onclick="toggleDropdown('add')">
                                    <span class="selected-text">-- Pilih Job Role --</span>
                                    <i class='bx bx-chevron-down'></i>
                                </div>
                                <div class="dropdown-menu-custom" id="addJobRoleDropdown">
                                    <div class="dropdown-search">
                                        <i class='bx bx-search'></i>
                                        <input type="text" placeholder="Cari job role..." onkeyup="filterJobRoles('add', this.value)" onclick="event.stopPropagation()">
                                    </div>
                                    <div class="dropdown-options" id="addJobRoleOptions">
                                        <div class="dropdown-option" data-value="" onclick="selectJobRole('add', '', '-- Pilih Job Role --')">
                                            -- Pilih Job Role --
                                        </div>
                                        @foreach($jobRoles as $jobRole)
                                        <div class="dropdown-option" data-value="{{ $jobRole->id_job_role }}" data-text="{{ $jobRole->nama_job_role }}" onclick="selectJobRole('add', '{{ $jobRole->id_job_role }}', '{{ $jobRole->nama_job_role }}')">
                                            {{ $jobRole->nama_job_role }}
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" name="id_job_role" id="add_id_job_role">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">No. HP</label>
                            <input type="text" name="no_hp" class="form-control" maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Status *</label>
                            <select name="status" class="form-select" required>
                                <option value="1" selected>Aktif</option>
                                <option value="0">Non-Aktif</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Foto Profil</label>
                            <div class="position-relative">
                                <input type="file" name="foto" id="add_foto" class="form-control" accept="image/jpeg,image/png,image/jpg" onchange="previewAddPhoto(event)">
                                <button type="button" class="btn btn-sm btn-danger position-absolute" id="clearAddPhoto" style="display: none; top: 4px; right: 4px;" onclick="clearAddPhotoPreview()">
                                    <i class='bx bx-x'></i>
                                </button>
                            </div>
                            <small class="text-muted">Format: JPG, JPEG, PNG | Maksimal 2MB</small>
                            <div id="addPhotoPreview" class="mt-2" style="display: none;">
                                <img id="addPhotoImg" src="" alt="Preview" class="preview-image">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body pt-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">Nama Lengkap *</label>
                            <input type="text" name="nama" id="edit_nama" class="form-control" required maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Email *</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Password (kosongkan jika tidak diubah)</label>
                            <div class="input-group">
                                <input type="password" name="password" id="edit_password" class="form-control" minlength="8">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('edit_password', 'edit_password_icon')">
                                    <i class='bx bx-hide' id="edit_password_icon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Role *</label>
                            <select name="role" id="edit_role" class="form-select" required onchange="toggleJobRoleField(this.value, 'edit')">
                                <option value="admin">Admin</option>
                                <option value="karyawan">Karyawan</option>
                                <option value="PM">Project Manager</option>
                                <option value="klien">Klien</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="editJobRoleField">
                            <label class="form-label small">Job Role</label>
                            <div class="custom-search-dropdown">
                                <div class="dropdown-display" onclick="toggleDropdown('edit')">
                                    <span class="selected-text" id="editSelectedText">-- Pilih Job Role --</span>
                                    <i class='bx bx-chevron-down'></i>
                                </div>
                                <div class="dropdown-menu-custom" id="editJobRoleDropdown">
                                    <div class="dropdown-search">
                                        <i class='bx bx-search'></i>
                                        <input type="text" placeholder="Cari job role..." onkeyup="filterJobRoles('edit', this.value)" onclick="event.stopPropagation()">
                                    </div>
                                    <div class="dropdown-options" id="editJobRoleOptions">
                                        <div class="dropdown-option" data-value="" onclick="selectJobRole('edit', '', '-- Pilih Job Role --')">
                                            -- Pilih Job Role --
                                        </div>
                                        @foreach($jobRoles as $jobRole)
                                        <div class="dropdown-option" data-value="{{ $jobRole->id_job_role }}" data-text="{{ $jobRole->nama_job_role }}" onclick="selectJobRole('edit', '{{ $jobRole->id_job_role }}', '{{ $jobRole->nama_job_role }}')">
                                            {{ $jobRole->nama_job_role }}
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" name="id_job_role" id="edit_id_job_role">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">No. HP</label>
                            <input type="text" name="no_hp" id="edit_no_hp" class="form-control" maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Status *</label>
                            <select name="status" id="edit_status" class="form-select" required>
                                <option value="1">Aktif</option>
                                <option value="0">Non-Aktif</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Foto Profil Saat Ini</label>
                            <div id="currentPhotoPreview" class="mb-2"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Ganti Foto Profil</label>
                            <div class="position-relative">
                                <input type="file" name="foto" id="edit_foto" class="form-control" accept="image/jpeg,image/png,image/jpg" onchange="previewNewPhoto(event)">
                                <button type="button" class="btn btn-sm btn-danger position-absolute" id="clearEditPhoto" style="display: none; top: 4px; right: 4px;" onclick="clearEditPhotoPreview()">
                                    <i class='bx bx-x'></i>
                                </button>
                            </div>
                            <small class="text-muted">Format: JPG, JPEG, PNG | Maksimal 2MB</small>
                            <div id="newPhotoPreview" class="mt-2" style="display: none;">
                                <img id="newPhotoImg" src="" alt="Preview" class="preview-image">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
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
                    <label class="form-check-label small" for="col-nama">Nama</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="job_role" id="col-job_role" checked>
                    <label class="form-check-label small" for="col-job_role">Jabatan</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="no_hp" id="col-no_hp" checked>
                    <label class="form-check-label small" for="col-no_hp">No. HP</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="role" id="col-role" checked>
                    <label class="form-check-label small" for="col-role">Role</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="status" id="col-status" checked>
                    <label class="form-check-label small" for="col-status">Status</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="created_at" id="col-created_at">
                    <label class="form-check-label small" for="col-created_at">Dibuat</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="updated_at" id="col-updated_at">
                    <label class="form-check-label small" for="col-updated_at">Diupdate</label>
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
/* User Email - Primary Color */
.user-email {
    font-size: 0.75rem;
    color: #696cff !important;
    font-weight: 500;
}

/* Job Role Badge - Colored Variants */
.job-role-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.8125rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

/* Default */
.job-role-badge {
    background: #f8f9fa;
    color: #495057;
}

/* Info - Development (Blue) */
.job-role-info {
    background: #d1e7fd !important;
    color: #0c63e4 !important;
}

/* Warning - SEO/Marketing (Orange) */
.job-role-warning {
    background: #fff3cd !important;
    color: #ff9800 !important;
}

/* Primary - Design (Purple) */
.job-role-primary {
    background: #e7e9fd !important;
    color: #696cff !important;
}

/* Success - Project Management (Green) */
.job-role-success {
    background: #d4edda !important;
    color: #28a745 !important;
}

/* Secondary - Admin/Support (Gray) */
.job-role-secondary {
    background: #e9ecef !important;
    color: #6c757d !important;
}

/* Danger - Other (Red) */
.job-role-danger {
    background: #f8d7da !important;
    color: #dc3545 !important;
}

/* Table Headers - Uppercase */
.table thead th {
    font-weight: 600;
    color: #495057;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    background: #f8f9fa;
    border: none;
    font-size: 0.75rem;
}

/* Card */
.card {
    border-radius: 12px;
    overflow: hidden;
}

/* Badge Counters - Simplified */
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

/* Search Box - Clean Design with Clear Button */
.search-box {
    position: relative;
    display: flex;
    align-items: center;
}

.search-box i.bx-search {
    position: absolute;
    left: 12px;
    color: #adb5bd;
    font-size: 18px;
    pointer-events: none;
    z-index: 1;
}

.search-box input {
    padding: 6px 36px 6px 36px;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    font-size: 0.875rem;
    width: 200px;
    transition: all 0.2s ease;
}

.search-box input:focus {
    outline: none;
    border-color: #696cff;
    box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.1);
    width: 250px;
}

.btn-clear-search {
    position: absolute;
    right: 4px;
    background: none;
    border: none;
    padding: 4px;
    cursor: pointer;
    color: #6c757d;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    z-index: 2;
}

.btn-clear-search:hover {
    background: #f8f9fa;
    color: #dc3545;
}

.btn-clear-search i {
    font-size: 18px;
}

/* Custom Search Dropdown */
.custom-search-dropdown {
    position: relative;
    width: 100%;
}

.dropdown-display {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 12px;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.dropdown-display:hover {
    border-color: #696cff;
}

.dropdown-display.active {
    border-color: #696cff;
    box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.1);
}

.dropdown-display .selected-text {
    color: #495057;
    flex: 1;
}

.dropdown-display i {
    font-size: 20px;
    color: #6c757d;
    transition: transform 0.2s ease;
}

.dropdown-display.active i {
    transform: rotate(180deg);
}

.dropdown-menu-custom {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    margin-top: 4px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    display: none;
    max-height: 280px;
    overflow: hidden;
}

.dropdown-menu-custom.show {
    display: block;
}

.dropdown-search {
    position: relative;
    padding: 8px;
    border-bottom: 1px solid #f1f3f5;
}

.dropdown-search i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #adb5bd;
    font-size: 16px;
}

.dropdown-search input {
    width: 100%;
    padding: 6px 12px 6px 32px;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    font-size: 0.875rem;
    outline: none;
}

.dropdown-search input:focus {
    border-color: #696cff;
}

.dropdown-options {
    max-height: 220px;
    overflow-y: auto;
}

.dropdown-option {
    padding: 8px 12px;
    cursor: pointer;
    font-size: 0.875rem;
    color: #495057;
    transition: background 0.15s ease;
}

.dropdown-option:hover {
    background: #f8f9fa;
}

.dropdown-option.selected {
    background: #e7e9fd;
    color: #696cff;
    font-weight: 500;
}

.dropdown-option.hidden {
    display: none;
}

/* Scrollbar untuk dropdown */
.dropdown-options::-webkit-scrollbar {
    width: 6px;
}

.dropdown-options::-webkit-scrollbar-track {
    background: #f8f9fa;
}

.dropdown-options::-webkit-scrollbar-thumb {
    background: #dee2e6;
    border-radius: 3px;
}

.dropdown-options::-webkit-scrollbar-thumb:hover {
    background: #ced4da;
}

/* Table - Clean & Minimal */
.table {
    font-size: 0.875rem;
}

.table thead tr {
    border-bottom: 2px solid #f1f3f5;
}

.table thead th {
    font-weight: 600;
    color: #495057;
    text-transform: none;
    padding: 12px 16px;
    background: #f8f9fa;
    border: none;
}

.table tbody tr {
    border-bottom: 1px solid #f1f3f5;
    transition: background 0.15s ease;
}

.table tbody tr:hover {
    background: #f8f9fa;
}

.table tbody td {
    padding: 14px 16px;
    color: #495057;
    vertical-align: middle;
    border: none;
}

/* Sortable Headers */
.sortable {
    cursor: pointer;
    user-select: none;
}

.sortable:hover {
    background: #e9ecef;
}

.sort-icon {
    font-size: 16px;
    color: #ced4da;
    transition: all 0.2s ease;
}

.sortable.asc .sort-icon,
.sortable.desc .sort-icon {
    color: #696cff;
}

.sortable.asc .sort-icon {
    transform: rotate(180deg);
}

/* Avatar - Simplified */
.avatar-img,
.avatar-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    flex-shrink: 0;
}

.avatar-img {
    object-fit: cover;
}

.avatar-circle {
    background: linear-gradient(135deg, #696cff 0%, #5145cd 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
}

/* Large Avatar for View Modal */
.avatar-img-large,
.avatar-circle-large {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    margin: 0 auto;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.avatar-img-large {
    object-fit: cover;
}

.avatar-circle-large {
    background: linear-gradient(135deg, #696cff 0%, #5145cd 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 3rem;
}

/* User Info */
.user-name {
    font-weight: 600;
    color: #212529;
    font-size: 0.875rem;
    margin-bottom: 2px;
}

/* Role Badge - Minimal */
.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    background: #e7e9fd;
    color: #696cff;
    border-radius: 6px;
    font-size: 0.8125rem;
    font-weight: 500;
}

.role-badge i {
    font-size: 14px;
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

/* Date Text */
.date-text {
    font-size: 0.8125rem;
    color: #6c757d;
}

/* Row Number */
.row-number {
    font-weight: 600;
    color: #adb5bd;
    font-size: 0.875rem;
}

/* Action Buttons - MODERN STYLE (SAME AS PERUSAHAAN) */
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

/* Form Controls */
.form-control,
.form-select {
    font-size: 0.875rem;
    border-color: #dee2e6;
    border-radius: 6px;
}

.form-control:focus,
.form-select:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.1);
}

.form-control-sm,
.form-select-sm {
    font-size: 0.8125rem;
    padding: 4px 8px;
}

/* Buttons */
.btn-sm {
    font-size: 0.8125rem;
    padding: 6px 16px;
    border-radius: 6px;
}

.btn-primary {
    background: #696cff;
    border-color: #696cff;
}

.btn-primary:hover {
    background: #5145cd;
    border-color: #5145cd;
}

.btn-light {
    background: #f8f9fa;
    border-color: #dee2e6;
    color: #495057;
}

.btn-light:hover {
    background: #e9ecef;
    border-color: #ced4da;
}

/* Empty State */
.empty-icon {
    font-size: 48px;
    color: #dee2e6;
}

.empty-text {
    color: #adb5bd;
    margin-top: 12px;
    font-size: 0.875rem;
}

/* Preview Image */
.preview-image {
    max-width: 150px;
    max-height: 150px;
    border-radius: 8px;
    object-fit: cover;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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
    border-radius: 6px;
    border-color: #dee2e6;
    color: #495057;
    font-size: 0.875rem;
}

.page-link:hover {
    background: #f8f9fa;
    border-color: #dee2e6;
}

.page-item.active .page-link {
    background: #696cff;
    border-color: #696cff;
}

/* Responsive */
@media (max-width: 768px) {
    .badge-counter .label {
        display: none;
    }
    
    .search-box input {
        width: 150px;
    }
    
    .search-box input:focus {
        width: 180px;
    }
    
    .avatar-img,
    .avatar-circle {
        width: 32px;
        height: 32px;
    }
    
    .table {
        font-size: 0.8125rem;
    }
}

/* Modal Error Alert */
.modal-error-alert {
    border-radius: 6px;
    font-size: 0.875rem;
    padding: 12px 16px;
}

.modal-error-alert strong {
    font-weight: 600;
}

.modal-error-alert .btn-close {
    font-size: 0.75rem;
    padding: 8px;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ===== COLUMN SETTINGS - PERSISTENT STORAGE =====
const COLUMN_STORAGE_KEY = 'user_column_settings';

// Default column visibility settings
const DEFAULT_COLUMN_SETTINGS = {
    'job_role': true,
    'no_hp': true,
    'role': true,
    'status': true,
    'created_at': false,
    'updated_at': false
};

// Initialize column settings on page load
document.addEventListener('DOMContentLoaded', function() {
    loadColumnSettings();
    initializeEventListeners();
    
    // Show clear search button if search has value
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearch');
    if (searchInput && searchInput.value.trim().length > 0) {
        clearSearchBtn.style.display = 'block';
    }
});

// Load column settings from localStorage
function loadColumnSettings() {
    try {
        const savedSettings = localStorage.getItem(COLUMN_STORAGE_KEY);
        
        if (savedSettings) {
            const settings = JSON.parse(savedSettings);
            
            // Apply saved settings to checkboxes and columns
            document.querySelectorAll('.column-toggle:not([disabled])').forEach(toggle => {
                const columnName = toggle.value;
                if (settings.hasOwnProperty(columnName)) {
                    toggle.checked = settings[columnName];
                    toggleColumn(columnName, settings[columnName]);
                } else {
                    // If column not in saved settings, use default
                    if (DEFAULT_COLUMN_SETTINGS.hasOwnProperty(columnName)) {
                        toggle.checked = DEFAULT_COLUMN_SETTINGS[columnName];
                        toggleColumn(columnName, DEFAULT_COLUMN_SETTINGS[columnName]);
                    } else {
                        toggleColumn(columnName, toggle.checked);
                    }
                }
            });
        } else {
            // No saved settings, use defaults
            applyDefaultColumnSettings();
        }
    } catch (e) {
        console.error('Error loading column settings:', e);
        applyDefaultColumnSettings();
    }
}

// Apply default column settings
function applyDefaultColumnSettings() {
    document.querySelectorAll('.column-toggle:not([disabled])').forEach(toggle => {
        const columnName = toggle.value;
        if (DEFAULT_COLUMN_SETTINGS.hasOwnProperty(columnName)) {
            toggle.checked = DEFAULT_COLUMN_SETTINGS[columnName];
            toggleColumn(columnName, DEFAULT_COLUMN_SETTINGS[columnName]);
        } else {
            toggleColumn(columnName, toggle.checked);
        }
    });
}

// Save column settings to localStorage
function saveColumnSettingsToStorage() {
    const settings = {};
    document.querySelectorAll('.column-toggle:not([disabled])').forEach(toggle => {
        settings[toggle.value] = toggle.checked;
    });
    localStorage.setItem(COLUMN_STORAGE_KEY, JSON.stringify(settings));
}

// Toggle column visibility
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

// Save column settings (called from modal)
function saveColumnSettings() {
    // Update column visibility based on current checkbox states
    document.querySelectorAll('.column-toggle:not([disabled])').forEach(toggle => {
        toggleColumn(toggle.value, toggle.checked);
    });
    
    // Save to localStorage
    saveColumnSettingsToStorage();
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('columnSettingsModal'));
    if (modal) modal.hide();
    
    // Show success message
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Pengaturan kolom berhasil disimpan',
        showConfirmButton: false,
        timer: 1500
    });
}

// Reset columns to default
function resetColumns() {
    document.querySelectorAll('.column-toggle:not([disabled])').forEach(toggle => {
        const columnName = toggle.value;
        if (DEFAULT_COLUMN_SETTINGS.hasOwnProperty(columnName)) {
            toggle.checked = DEFAULT_COLUMN_SETTINGS[columnName];
            toggleColumn(columnName, DEFAULT_COLUMN_SETTINGS[columnName]);
        } else {
            toggle.checked = true;
            toggleColumn(columnName, true);
        }
    });
    
    saveColumnSettingsToStorage();
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

// ===== INITIALIZE EVENT LISTENERS =====
function initializeEventListeners() {
    // Search Input Handler with Debounce
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearch');
    const filterForm = document.getElementById('filterForm');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchValue = this.value.trim();
            
            if (searchValue.length > 0) {
                clearSearchBtn.style.display = 'block';
            } else {
                clearSearchBtn.style.display = 'none';
            }
            
            clearTimeout(window.searchTimeout);
            
            window.searchTimeout = setTimeout(function() {
                if (searchValue.length >= 2 || searchValue.length === 0) {
                    filterForm.submit();
                }
            }, 300);
        });
    }
    
    // Clear Search Button
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            this.style.display = 'none';
            filterForm.submit();
        });
    }
    
    // Role Filter Change
    const roleSelect = document.getElementById('roleSelect');
    if (roleSelect) {
        roleSelect.addEventListener('change', function() {
            filterForm.submit();
        });
    }
    
    // Per Page Change
    const perPageSelect = document.getElementById('perPageSelect');
    if (perPageSelect) {
        perPageSelect.addEventListener('change', function() {
            filterForm.submit();
        });
    }
    
    // Job Role Filter Click Handlers
    document.querySelectorAll('.job-role-filter').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const jobRoleId = this.dataset.jobRole;
            const jobRoleInput = document.getElementById('jobRoleInput');
            const filterForm = document.getElementById('filterForm');
            
            if (jobRoleInput) {
                jobRoleInput.value = jobRoleId;
            }
            
            document.querySelectorAll('.badge-counter').forEach(badge => {
                badge.classList.remove('active');
            });
            this.querySelector('.badge-counter').classList.add('active');
            
            filterForm.submit();
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

// ===== SORT TABLE =====
function sortTable(column, direction) {
    const tbody = document.getElementById('userTableBody');
    const rows = Array.from(tbody.querySelectorAll('.user-row'));
    
    rows.sort((a, b) => {
        let aVal = a.dataset[column] || '';
        let bVal = b.dataset[column] || '';
        
        if (column === 'created_at' || column === 'updated_at') {
            aVal = new Date(aVal).getTime() || 0;
            bVal = new Date(bVal).getTime() || 0;
            return direction === 'asc' ? aVal - bVal : bVal - aVal;
        }
        
        if (column === 'status') {
            aVal = parseInt(aVal);
            bVal = parseInt(bVal);
            return direction === 'asc' ? aVal - bVal : bVal - aVal;
        }
        
        return direction === 'asc' ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
    });
    
    rows.forEach(row => tbody.appendChild(row));
    updateRowNumbers();
}

// ===== UPDATE ROW NUMBERS =====
function updateRowNumbers() {
    const visibleRows = Array.from(document.querySelectorAll('.user-row')).filter(row => row.style.display !== 'none');
    visibleRows.forEach((row, index) => {
        const numberCell = row.querySelector('.row-number');
        if (numberCell) numberCell.textContent = index + 1;
    });
}

// ===== VIEW USER =====
function viewUser(userId) {
    const button = event.target.closest('[data-user]');
    const user = JSON.parse(button.dataset.user);
    const jobRoleName = button.dataset.jobRoleName || '-';
    const jobRoleColor = button.dataset.jobRoleColor || 'default';
    
    // Set Foto
    const fotoContainer = document.getElementById('view_foto_container');
    if (user.foto) {
        const photoUrl = `/storage/${user.foto}`;
        fotoContainer.innerHTML = `<img src="${photoUrl}" alt="${user.nama}" class="avatar-img-large">`;
    } else {
        fotoContainer.innerHTML = `<div class="avatar-circle-large">${user.nama.charAt(0).toUpperCase()}</div>`;
    }
    
    // Set Data
    document.getElementById('view_nama').textContent = user.nama;
    document.getElementById('view_email').textContent = user.email;
    document.getElementById('view_no_hp').textContent = user.no_hp || '-';
    document.getElementById('view_id_user').textContent = user.id_user;
    
    // Set Role Badge
    const roleConfig = {
        'admin': {icon: 'bx-crown', label: 'Admin'},
        'PM': {icon: 'bx-briefcase', label: 'PM'},
        'karyawan': {icon: 'bx-user', label: 'Karyawan'},
        'klien': {icon: 'bx-shield-alt-2', label: 'Klien'}
    };
    const config = roleConfig[user.role] || roleConfig['karyawan'];
    document.getElementById('view_role_badge').innerHTML = `
        <span class="role-badge">
            <i class='bx ${config.icon}'></i> ${config.label}
        </span>
    `;
    
    // Set Status Badge
    const statusBadge = document.getElementById('view_status_badge');
    if (user.status) {
        statusBadge.innerHTML = `
            <span class="status-badge status-active">
                <i class='bx bx-check-circle'></i> Aktif
            </span>
        `;
    } else {
        statusBadge.innerHTML = `
            <span class="status-badge status-inactive">
                <i class='bx bx-x-circle'></i> Non-Aktif
            </span>
        `;
    }
    
    // Set Job Role
    const jobRoleElement = document.getElementById('view_job_role');
    if (jobRoleName && jobRoleName !== '-') {
        jobRoleElement.innerHTML = `
            <span class="job-role-badge job-role-${jobRoleColor}">
                ${jobRoleName}
            </span>
        `;
    } else {
        jobRoleElement.innerHTML = `<p class="fw-semibold mb-0">-</p>`;
    }
    
    // Set Timestamps
    document.getElementById('view_created_at').textContent = user.created_at 
        ? new Date(user.created_at).toLocaleString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        })
        : '-';
    
    document.getElementById('view_updated_at').textContent = user.updated_at 
        ? new Date(user.updated_at).toLocaleString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        })
        : '-';
    
    new bootstrap.Modal(document.getElementById('viewUserModal')).show();
}

// ===== EDIT USER =====
function editUser(userId) {
    const button = event.target.closest('[data-user]');
    const user = JSON.parse(button.dataset.user);
    const jobRoleId = button.dataset.jobRoleId || '';
    
    // Set basic fields
    document.getElementById('edit_nama').value = user.nama;
    document.getElementById('edit_email').value = user.email;
    document.getElementById('edit_role').value = user.role;
    document.getElementById('edit_no_hp').value = user.no_hp || '';
    document.getElementById('edit_status').value = user.status ? '1' : '0';
    document.getElementById('editUserForm').action = `/master-data-users/${user.id_user}`;
    
    // Toggle job role field berdasarkan role
    toggleJobRoleField(user.role, 'edit');
    
    // Set job role value dengan custom dropdown
    if (jobRoleId) {
        // Find the option text
        const option = document.querySelector(`#editJobRoleOptions .dropdown-option[data-value="${jobRoleId}"]`);
        const optionText = option ? option.getAttribute('data-text') : '-- Pilih Job Role --';
        
        // Set the value and display
        selectJobRole('edit', jobRoleId, optionText);
    } else {
        selectJobRole('edit', '', '-- Pilih Job Role --');
    }
    
    // Reset new photo preview
    document.getElementById('newPhotoPreview').style.display = 'none';
    document.getElementById('edit_foto').value = '';
    document.getElementById('clearEditPhoto').style.display = 'none';
    
    // Set current photo
    const currentPhotoPreview = document.getElementById('currentPhotoPreview');
    if (user.foto) {
        const photoUrl = `/storage/${user.foto}`;
        currentPhotoPreview.innerHTML = `<img src="${photoUrl}" alt="${user.nama}" class="preview-image">`;
    } else {
        currentPhotoPreview.innerHTML = `<div class="avatar-circle">${user.nama.charAt(0).toUpperCase()}</div>`;
    }
    
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}

// ===== DELETE USER =====
function deleteUser(userId, userName) {
    Swal.fire({
        title: 'Hapus User?',
        html: `Anda akan menghapus <strong>${userName}</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e53e3e',
        cancelButtonColor: '#718096',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/master-data-users/${userId}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// ===== TOGGLE JOB ROLE FIELD =====
function toggleJobRoleField(role, formType = 'add') {
    const jobRoleField = formType === 'edit' 
        ? document.getElementById('editJobRoleField') 
        : document.getElementById('addJobRoleField');
    
    const hiddenInput = formType === 'edit' 
        ? document.getElementById('edit_id_job_role') 
        : document.getElementById('add_id_job_role');
    
    if (role === 'klien') {
        jobRoleField.style.display = 'none';
        if (hiddenInput) {
            hiddenInput.value = '';
        }
        // Reset dropdown display
        const display = jobRoleField.querySelector('.dropdown-display .selected-text');
        if (display) {
            display.textContent = '-- Pilih Job Role --';
        }
    } else {
        jobRoleField.style.display = 'block';
    }
}

// ===== CUSTOM SEARCH DROPDOWN FUNCTIONS =====
function toggleDropdown(formType) {
    const dropdown = document.getElementById(formType + 'JobRoleDropdown');
    const display = dropdown.previousElementSibling;
    
    // Close other dropdowns
    document.querySelectorAll('.dropdown-menu-custom').forEach(d => {
        if (d !== dropdown) {
            d.classList.remove('show');
            d.previousElementSibling.classList.remove('active');
        }
    });
    
    dropdown.classList.toggle('show');
    display.classList.toggle('active');
}

function filterJobRoles(formType, searchValue) {
    const options = document.getElementById(formType + 'JobRoleOptions');
    const allOptions = options.querySelectorAll('.dropdown-option');
    const search = searchValue.toLowerCase();
    
    allOptions.forEach(option => {
        const text = option.getAttribute('data-text') || option.textContent;
        if (text.toLowerCase().includes(search)) {
            option.classList.remove('hidden');
        } else {
            option.classList.add('hidden');
        }
    });
}

function selectJobRole(formType, value, text) {
    const hiddenInput = document.getElementById(formType + '_id_job_role');
    const display = document.querySelector(`#${formType}JobRoleDropdown`).previousElementSibling;
    const selectedText = display.querySelector('.selected-text');
    const dropdown = document.getElementById(formType + 'JobRoleDropdown');
    const options = document.getElementById(formType + 'JobRoleOptions');
    
    // Update hidden input
    hiddenInput.value = value;
    
    // Update display text
    selectedText.textContent = text;
    
    // Update selected option styling
    options.querySelectorAll('.dropdown-option').forEach(opt => {
        opt.classList.remove('selected');
        if (opt.getAttribute('data-value') === value) {
            opt.classList.add('selected');
        }
    });
    
    // Close dropdown
    dropdown.classList.remove('show');
    display.classList.remove('active');
    
    // Reset search
    const searchInput = dropdown.querySelector('.dropdown-search input');
    if (searchInput) {
        searchInput.value = '';
        filterJobRoles(formType, '');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.custom-search-dropdown')) {
        document.querySelectorAll('.dropdown-menu-custom').forEach(dropdown => {
            dropdown.classList.remove('show');
            dropdown.previousElementSibling.classList.remove('active');
        });
    }
});

// ===== PHOTO PREVIEW =====
function previewAddPhoto(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('addPhotoPreview');
    const previewImg = document.getElementById('addPhotoImg');
    const clearBtn = document.getElementById('clearAddPhoto');
    
    if (file) {
        if (!validatePhoto(file, 'add')) {
            event.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewContainer.style.display = 'block';
            clearBtn.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function previewNewPhoto(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('newPhotoPreview');
    const previewImg = document.getElementById('newPhotoImg');
    const clearBtn = document.getElementById('clearEditPhoto');
    
    if (file) {
        if (!validatePhoto(file, 'edit')) {
            event.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewContainer.style.display = 'block';
            clearBtn.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function clearAddPhotoPreview() {
    document.getElementById('add_foto').value = '';
    document.getElementById('addPhotoPreview').style.display = 'none';
    document.getElementById('clearAddPhoto').style.display = 'none';
}

function clearEditPhotoPreview() {
    document.getElementById('edit_foto').value = '';
    document.getElementById('newPhotoPreview').style.display = 'none';
    document.getElementById('clearEditPhoto').style.display = 'none';
}

// ===== PHOTO VALIDATION =====
function validatePhoto(file, formType = 'add') {
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    const maxSize = 2 * 1024 * 1024; // 2MB in bytes
    
    const modalId = formType === 'add' ? 'addUserModal' : 'editUserModal';
    const modal = document.getElementById(modalId);
    
    if (!validTypes.includes(file.type)) {
        showModalError(modal, 'Format File Tidak Valid', 'Harap upload foto dengan format JPG, JPEG, atau PNG');
        return false;
    }
    
    if (file.size > maxSize) {
        showModalError(modal, 'Ukuran File Terlalu Besar', 'Ukuran foto maksimal 2MB');
        return false;
    }
    
    return true;
}

function showModalError(modal, title, message) {
    const existingAlert = modal.querySelector('.modal-error-alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show modal-error-alert';
    alertDiv.style.margin = '0 0 15px 0';
    alertDiv.innerHTML = `
        <strong>${title}!</strong> ${message}
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    `;
    
    const modalBody = modal.querySelector('.modal-body');
    modalBody.insertBefore(alertDiv, modalBody.firstChild);
    
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.remove();
        }
    }, 5000);
}

// ===== TOGGLE PASSWORD =====
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bx-hide');
        icon.classList.add('bx-show');
    } else {
        input.type = 'password';
        icon.classList.remove('bx-show');
        icon.classList.add('bx-hide');
    }
}

// ===== FORM SUBMISSIONS =====
['addUserForm', 'editUserForm'].forEach(formId => {
    const form = document.getElementById(formId);
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validasi job role untuk role karyawan dan PM
            const isEditForm = formId === 'editUserForm';
            const roleField = isEditForm ? 'edit_role' : 'add_role';
            const jobRoleField = isEditForm ? 'edit_id_job_role' : 'add_id_job_role';
            
            const role = document.getElementById(roleField).value;
            const jobRoleValue = document.getElementById(jobRoleField).value;
            
            if ((role === 'karyawan' || role === 'PM') && (!jobRoleValue || jobRoleValue === '')) {
                const modalId = isEditForm ? 'editUserModal' : 'addUserModal';
                const modal = document.getElementById(modalId);
                showModalError(modal, 'Job Role Wajib Dipilih', 'Untuk role karyawan atau PM, job role harus dipilih');
                return;
            }
            
            // Tutup modal dan submit
            const modal = bootstrap.Modal.getInstance(document.getElementById(isEditForm ? 'editUserModal' : 'addUserModal'));
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
</script>
@endsection