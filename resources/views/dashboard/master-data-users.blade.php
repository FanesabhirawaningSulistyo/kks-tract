@extends('layouts.master')
@section('title', 'Master Data Users')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/master-data.css') }}">
@endpush
@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
    <div><h4>Master Data Users</h4><p>Kelola data pengguna sistem</p></div>
    <button class="btn-main" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class='bx bx-plus'></i> Tambah User</button>
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
        <div class="counter-group">
            <a href="javascript:void(0)" onclick="filterByJobRole('')" class="counter-pill active" data-jobrole="">
                <span class="pill-count">{{ $totalCount ?? 0 }}</span><span>Semua User</span>
            </a>
            @foreach($allCounts as $c)
                @if($c['count'] > 0)
                <a href="javascript:void(0)" onclick="filterByJobRole('{{ $c['id'] }}')" class="counter-pill" data-jobrole="{{ $c['id'] }}">
                    <span class="pill-count">{{ $c['count'] }}</span><span>{{ $c['name'] }}</span>
                </a>
                @endif
            @endforeach
        </div>
        <div class="toolbar">
            <div class="toolbar-left">
                <span class="label-sm">Tampilkan</span>
                <select id="perPageSelect" class="ctrl" style="width:70px;">
                    <option value="10">10</option><option value="25">25</option>
                    <option value="50">50</option><option value="100">100</option>
                </select>
                <span class="label-sm">entri</span>
            </div>
            <div class="toolbar-right">
                <select id="roleSelect" class="ctrl">
                    <option value="">Semua Role</option>
                    <option value="klien">Klien</option>
                    <option value="karyawan">Karyawan</option>
                    <option value="PM">Project Manager</option>
                    <option value="admin">Admin</option>
                </select>
                <div class="search-wrap">
                    <i class='bx bx-search ico'></i>
                    <input type="text" id="searchInput" class="ctrl" placeholder="Cari user..." autocomplete="off">
                    <button type="button" id="clearSearch" class="search-clear"><i class='bx bx-x'></i></button>
                </div>
                <button type="button" class="btn-ghost" data-bs-toggle="modal" data-bs-target="#columnSettingsModal"><i class='bx bx-columns'></i></button>
            </div>
        </div>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="column-no" style="width:52px;text-align:center;">No</th>
                    <th class="column-nama sortable" data-column="nama" style="min-width:240px;">Nama <span class="sort-icon"></span></th>
                    <th class="column-job_role sortable" data-column="job_role" style="min-width:140px;">Jabatan <span class="sort-icon"></span></th>
                    <th class="column-no_hp sortable" data-column="no_hp" style="min-width:120px;">No. HP <span class="sort-icon"></span></th>
                    <th class="column-role sortable" data-column="role" style="min-width:110px;">Role <span class="sort-icon"></span></th>
                    <th class="column-status sortable" data-column="status" style="min-width:100px;">Status <span class="sort-icon"></span></th>
                    <th class="column-created_at sortable" data-column="created_at" style="min-width:130px;">Dibuat <span class="sort-icon"></span></th>
                    <th class="column-updated_at sortable" data-column="updated_at" style="min-width:130px;">Diupdate <span class="sort-icon"></span></th>
                    <th class="column-actions" style="width:110px;text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                @forelse($users as $index => $user)
                @php
                    $jobRole      = $user->jobRole;
                    $jobRoleName  = $jobRole ? $jobRole->nama_job_role : '-';
                    $jobRoleId    = $jobRole ? $jobRole->id_job_role : '';
                    $jobRoleColor = $user->jobRoleColor ?? 'purple-1';
                    $roleConfig   = [
                   'admin'    => ['icon'=>'bx-key',        'label'=>'Admin'],
'PM'       => ['icon'=>'bx-task',         'label'=>'Project Manager'],
'karyawan' => ['icon'=>'bx-user-circle',  'label'=>'Karyawan'],
'klien'    => ['icon'=>'bx-buildings',    'label'=>'Klien'],
                    ];
                    $rc      = $roleConfig[$user->role] ?? $roleConfig['karyawan'];
                    $roleCls = 'role-' . ($user->role === 'PM' ? 'pm' : $user->role);
                @endphp
                <tr class="user-row"
                    data-nama="{{ strtolower($user->nama) }}"
                    data-email="{{ strtolower($user->email) }}"
                    data-job_role="{{ strtolower($jobRoleName) }}"
                    data-job_role_id="{{ $jobRoleId }}"
                    data-no_hp="{{ $user->no_hp ?? '' }}"
                    data-role="{{ $user->role }}"
                    data-status="{{ $user->status ? '1' : '0' }}"
                    data-created_at="{{ $user->created_at }}"
                    data-updated_at="{{ $user->updated_at }}"
                    data-item='@json($user)'>
                    <td class="column-no" style="text-align:center;"><span class="row-no">{{ $index + 1 }}</span></td>
                    <td class="column-nama">
                        <div style="display:flex;align-items:center;gap:10px;">
                            @if($user->foto)
                                <img src="{{ asset('storage/'.$user->foto) }}" alt="{{ $user->nama }}"
                                     style="width:36px;height:36px;border-radius:50%;object-fit:cover;flex-shrink:0;"
                                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                <div class="av" style="display:none;">{{ strtoupper(substr($user->nama,0,1)) }}</div>
                            @else
                                <div class="av">{{ strtoupper(substr($user->nama,0,1)) }}</div>
                            @endif
                            <div>
                                <div class="user-name">{{ $user->nama }}</div>
                                <div class="user-email">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="column-job_role">
                        @if($jobRole)
                            <span class="jr-pill {{ $jobRoleColor }}">{{ $jobRoleName }}</span>
                        @else
                            <span style="color:var(--ink-400);font-size:12px;">—</span>
                        @endif
                    </td>
                    <td class="column-no_hp"><span class="date-val">{{ $user->no_hp ?? '—' }}</span></td>
                    <td class="column-role">
                        <span class="role-pill {{ $roleCls }}">
                            <i class='bx {{ $rc['icon'] }}' style="font-size:13px;"></i> {{ $rc['label'] }}
                        </span>
                    </td>
                    <td class="column-status">
                        @if($user->status)
                            <span class="status-pill pill-active"><span class="dot"></span>Aktif</span>
                        @else
                            <span class="status-pill pill-inactive"><span class="dot"></span>Non-Aktif</span>
                        @endif
                    </td>
                    <td class="column-created_at"><span class="date-val">{{ $user->created_at?->format('d/m/Y H:i') ?? '—' }}</span></td>
                    <td class="column-updated_at"><span class="date-val">{{ $user->updated_at?->format('d/m/Y H:i') ?? '—' }}</span></td>
                    <td class="column-actions" style="text-align:right;">
                        <div class="act-group">
                            <button type="button" class="act-btn view" onclick="viewUser(this)"
                                    data-item='@json($user)' data-job-role-name="{{ $jobRoleName }}" data-job-role-color="{{ $jobRoleColor }}"
                                    title="Lihat Detail"><i class='bx bx-show'></i></button>
                            <button type="button" class="act-btn edit" onclick="editUser(this)"
                                    data-item='@json($user)' data-job-role-id="{{ $jobRoleId }}"
                                    title="Edit"><i class='bx bx-edit'></i></button>
                            <button type="button" class="act-btn delete"
                                    onclick="deleteUser({{ $user->id_user }},'{{ addslashes($user->nama) }}')"
                                    title="Hapus"><i class='bx bx-trash'></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9">
                    <div class="empty-state">
                        <i class='bx bx-user-x'></i>
                        <p>Belum ada data user.<br>Tambahkan user pertama Anda.</p>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">
        <span class="footer-info">Menampilkan <strong id="showingStart">1</strong>–<strong id="showingEnd">{{ $users->count() }}</strong> dari <strong id="totalEntries">{{ $users->count() }}</strong> data</span>
        <nav><ul class="page-list" id="paginationControls"></ul></nav>
    </div>
</div>

{{-- ══ MODAL VIEW ══ --}}
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-hdr">
                <div class="modal-hdr-title"><div class="hdr-icon"><i class='bx bx-show'></i></div>Detail User</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-bdy">
                <div style="text-align:center;margin-bottom:20px;" id="view_foto_container"></div>
                <hr class="divider">
                <div class="dsec">Informasi Akun</div>
                <div class="drow"><span class="dlabel">Nama</span><span class="dval" id="view_nama"></span></div>
                <div class="drow"><span class="dlabel">Email</span><span class="dval" id="view_email" style="color:var(--p1);font-weight:500;"></span></div>
                <div class="drow"><span class="dlabel">No. HP</span><span class="dval" id="view_no_hp"></span></div>
                <div class="drow"><span class="dlabel">Role</span><span class="dval" id="view_role_badge"></span></div>
                <div class="drow"><span class="dlabel">Jabatan</span><span class="dval" id="view_job_role"></span></div>
                <div class="drow"><span class="dlabel">Status</span><span class="dval" id="view_status_badge"></span></div>
                <hr class="divider">
                <div class="dsec">Waktu</div>
                <div class="drow"><span class="dlabel">Dibuat</span><span class="dval" style="font-weight:400;color:var(--ink-500);" id="view_created_at"></span></div>
                <div class="drow"><span class="dlabel">Diperbarui</span><span class="dval" style="font-weight:400;color:var(--ink-500);" id="view_updated_at"></span></div>
            </div>
            <div class="modal-ftr">
                <button type="button" class="btn-outline" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn-main" id="view_edit_btn"><i class='bx bx-edit'></i> Edit</button>
            </div>
        </div>
    </div>
</div>

{{-- ══ MODAL ADD ══ --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-hdr">
                <div class="modal-hdr-title"><div class="hdr-icon"><i class='bx bx-plus'></i></div>Tambah User Baru</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('master-data-users.store') }}" method="POST" enctype="multipart/form-data" id="addUserForm">
                @csrf
                <div class="modal-bdy">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="flabel">Nama Lengkap <span style="color:#DC2626;">*</span></label>
                            <input type="text" name="nama" class="fctrl" required maxlength="100" placeholder="Nama lengkap">
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Email <span style="color:#DC2626;">*</span></label>
                            <input type="email" name="email" class="fctrl" required placeholder="email@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Password <span style="color:#DC2626;">*</span></label>
                            <div class="pw-wrap">
                                <input type="password" name="password" id="add_password" class="fctrl" required minlength="8" placeholder="Min. 8 karakter">
                                <button class="btn-pw" type="button" onclick="togglePw('add_password','add_pw_icon')"><i class='bx bx-hide' id="add_pw_icon"></i></button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Role <span style="color:#DC2626;">*</span></label>
                            <select name="role" id="add_role" class="fctrl" required onchange="handleAddRoleChange(this.value)">
                                <option value="">— Pilih Role —</option>
                                <option value="admin">Admin</option>
                                <option value="karyawan">Karyawan</option>
                                <option value="PM">Project Manager</option>
                                <option value="klien">Klien</option>
                            </select>
                        </div>
                        {{-- Hanya muncul jika role = karyawan --}}
                        <div class="col-md-6" id="addJobRoleField" style="display:none;">
                            <label class="flabel">Job Role <span style="color:#DC2626;">*</span></label>
                            <div class="cdd">
                                <div class="cdd-display" onclick="cddToggle('add')">
                                    <span class="cdd-txt" id="addCddTxt">— Pilih Job Role —</span>
                                    <i class='bx bx-chevron-down'></i>
                                </div>
                                <div class="cdd-menu" id="addCddMenu">
                                    <div class="cdd-search"><i class='bx bx-search'></i>
                                        <input type="text" placeholder="Cari..." oninput="cddFilter('add',this.value)" onclick="event.stopPropagation()">
                                    </div>
                                    <div class="cdd-opts" id="addCddOpts">
                                        <div class="cdd-opt" data-value="" onclick="cddSelect('add','','— Pilih Job Role —')">— Pilih Job Role —</div>
                                        @foreach($jobRoles as $jr)
                                        <div class="cdd-opt" data-value="{{ $jr->id_job_role }}" data-text="{{ $jr->nama_job_role }}"
                                             onclick="cddSelect('add','{{ $jr->id_job_role }}','{{ $jr->nama_job_role }}')">{{ $jr->nama_job_role }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" name="id_job_role" id="add_id_job_role">
                            </div>
                        </div>
                        {{-- Info otomatis untuk admin/PM/klien --}}
                        <div class="col-md-6" id="addJobRoleInfo" style="display:none;">
                            <label class="flabel">Job Role</label>
                            <div class="fctrl" style="background:var(--ink-100);color:var(--ink-500);cursor:default;" id="addJobRoleInfoText">— otomatis —</div>
                            <div class="fhint"><i class='bx bx-info-circle'></i> Ditetapkan otomatis sesuai role</div>
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">No. HP</label>
                            <input type="text" name="no_hp" class="fctrl" maxlength="20" placeholder="08xx-xxxx-xxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Status <span style="color:#DC2626;">*</span></label>
                            <select name="status" class="fctrl" required>
                                <option value="1" selected>Aktif</option>
                                <option value="0">Non-Aktif</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="flabel">Foto Profil</label>
                            <input type="file" name="foto" id="add_foto" class="fctrl" accept="image/jpeg,image/png,image/jpg" onchange="previewPhoto(event,'addPhotoImg','addPhotoPreview')">
                            <div class="fhint">Format: JPG, JPEG, PNG — Maks. 2MB</div>
                            <div class="photo-preview" id="addPhotoPreview">
                                <img id="addPhotoImg" src="" alt="Preview">
                                <button type="button" class="photo-clear" onclick="clearPhoto('add_foto','addPhotoImg','addPhotoPreview')"><i class='bx bx-x'></i> Hapus</button>
                            </div>
                        </div>
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
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-hdr">
                <div class="modal-hdr-title"><div class="hdr-icon"><i class='bx bx-edit'></i></div>Edit User</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-bdy">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="flabel">Nama Lengkap <span style="color:#DC2626;">*</span></label>
                            <input type="text" name="nama" id="edit_nama" class="fctrl" required maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Email <span style="color:#DC2626;">*</span></label>
                            <input type="email" name="email" id="edit_email" class="fctrl" required>
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Password <span class="fhint" style="margin:0;display:inline;">(kosongkan jika tidak diubah)</span></label>
                            <div class="pw-wrap">
                                <input type="password" name="password" id="edit_password" class="fctrl" minlength="8" placeholder="Isi jika ingin mengubah">
                                <button class="btn-pw" type="button" onclick="togglePw('edit_password','edit_pw_icon')"><i class='bx bx-hide' id="edit_pw_icon"></i></button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">
                                Role <span style="color:#DC2626;">*</span>
                                <span id="edit_role_klien_badge" class="lock-badge" style="display:none;"><i class='bx bx-lock-alt'></i> Tidak dapat diubah</span>
                            </label>
                            <select name="role" id="edit_role" class="fctrl" required onchange="handleEditRoleChange(this.value)">
                                <option value="admin">Admin</option>
                                <option value="karyawan">Karyawan</option>
                                <option value="PM">Project Manager</option>
                                <option value="klien">Klien</option>
                            </select>
                            <input type="hidden" name="role" id="edit_role_hidden">
                        </div>
                        {{-- Hanya muncul jika role = karyawan --}}
                        <div class="col-md-6" id="editJobRoleField" style="display:none;">
                            <label class="flabel">Job Role <span style="color:#DC2626;">*</span></label>
                            <div class="cdd">
                                <div class="cdd-display" id="editDropdownDisplay" onclick="cddToggle('edit')">
                                    <span class="cdd-txt" id="editSelectedText">— Pilih Job Role —</span>
                                    <i class='bx bx-chevron-down'></i>
                                </div>
                                <div class="cdd-menu" id="editCddMenu">
                                    <div class="cdd-search"><i class='bx bx-search'></i>
                                        <input type="text" placeholder="Cari..." oninput="cddFilter('edit',this.value)" onclick="event.stopPropagation()">
                                    </div>
                                    <div class="cdd-opts" id="editCddOpts">
                                        <div class="cdd-opt" data-value="" onclick="cddSelect('edit','','— Pilih Job Role —')">— Pilih Job Role —</div>
                                        @foreach($jobRoles as $jr)
                                        <div class="cdd-opt" data-value="{{ $jr->id_job_role }}" data-text="{{ $jr->nama_job_role }}"
                                             onclick="cddSelect('edit','{{ $jr->id_job_role }}','{{ $jr->nama_job_role }}')">{{ $jr->nama_job_role }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" name="id_job_role" id="edit_id_job_role">
                            </div>
                        </div>
                        {{-- Info otomatis untuk admin/PM/klien --}}
                        <div class="col-md-6" id="editJobRoleInfo" style="display:none;">
                            <label class="flabel">Job Role</label>
                            <div class="fctrl" style="background:var(--ink-100);color:var(--ink-500);cursor:default;" id="editJobRoleInfoText">— otomatis —</div>
                            <div class="fhint"><i class='bx bx-info-circle'></i> Ditetapkan otomatis sesuai role</div>
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">No. HP</label>
                            <input type="text" name="no_hp" id="edit_no_hp" class="fctrl" maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Status <span style="color:#DC2626;">*</span></label>
                            <select name="status" id="edit_status" class="fctrl" required>
                                <option value="1">Aktif</option>
                                <option value="0">Non-Aktif</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="flabel">Foto Profil Saat Ini</label>
                            <div class="photo-current" id="currentPhotoPreview"></div>
                            <label class="flabel">Ganti Foto Profil</label>
                            <input type="file" name="foto" id="edit_foto" class="fctrl" accept="image/jpeg,image/png,image/jpg" onchange="previewPhoto(event,'editPhotoImg','editPhotoPreview')">
                            <div class="fhint">Format: JPG, JPEG, PNG — Maks. 2MB</div>
                            <div class="photo-preview" id="editPhotoPreview">
                                <img id="editPhotoImg" src="" alt="Preview">
                                <button type="button" class="photo-clear" onclick="clearPhoto('edit_foto','editPhotoImg','editPhotoPreview')"><i class='bx bx-x'></i> Hapus</button>
                            </div>
                        </div>
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
                <div class="modal-hdr-title"><div class="modal-hdr-icon"><i class='bx bx-columns'></i></div>Pengaturan Kolom</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-bdy" style="padding-top:14px;">
                @foreach([
                    ['no','No Urut',true,true],['nama','Nama',true,true],['job_role','Jabatan',true,false],
                    ['no_hp','No. HP',true,false],['role','Role',true,false],['status','Status',true,false],
                    ['created_at','Dibuat',false,false],['updated_at','Diupdate',false,false],['actions','Aksi',true,true],
                ] as [$val, $label, $checked, $disabled])
                <div class="col-check-item">
                    <input class="column-toggle" type="checkbox" value="{{ $val }}" id="col-{{ $val }}" {{ $checked?'checked':'' }} {{ $disabled?'disabled':'' }}>
                    <label for="col-{{ $val }}">{{ $label }}</label>
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
const STORAGE_KEY = 'user_column_settings';
let allRows = [], filteredRows = [];
let currentPage = 1, perPage = 10;
let currentJobRole = '', currentRole = '', currentSearch = '';
let searchTimeout = null, currentViewBtn = null;

document.addEventListener('DOMContentLoaded', function () {
    initializeData(); loadColumnSettings(); initializeEventListeners(); renderTable();
});

function initializeData() {
    allRows = Array.from(document.querySelectorAll('#userTableBody .user-row')).map(row => ({
        element: row, nama: row.dataset.nama, email: row.dataset.email,
        job_role: row.dataset.job_role, job_role_id: row.dataset.job_role_id,
        no_hp: row.dataset.no_hp, role: row.dataset.role, status: row.dataset.status,
        created_at: row.dataset.created_at, updated_at: row.dataset.updated_at,
    }));
    filteredRows = [...allRows];
}
function initializeEventListeners() {
    const pp = document.getElementById('perPageSelect');
    pp.value = perPage;
    pp.addEventListener('change', () => { perPage = parseInt(pp.value); currentPage = 1; renderTable(); });
    document.getElementById('roleSelect').addEventListener('change', function() { currentRole = this.value; applyFilters(); });
    const si = document.getElementById('searchInput'), sc = document.getElementById('clearSearch');
    si.addEventListener('input', function() {
        sc.style.display = this.value.trim() ? 'block' : 'none';
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => { currentSearch = this.value.trim().toLowerCase(); applyFilters(); }, 300);
    });
    sc.addEventListener('click', () => { si.value=''; sc.style.display='none'; currentSearch=''; applyFilters(); });
    document.querySelectorAll('.column-toggle').forEach(t => t.addEventListener('change', function() { toggleColumn(this.value, this.checked); }));
    document.querySelectorAll('.sortable').forEach(h => {
        h.addEventListener('click', function() {
            const col = this.dataset.column, isAsc = this.classList.contains('asc');
            document.querySelectorAll('.sortable').forEach(x => x.classList.remove('asc','desc'));
            this.classList.add(isAsc ? 'desc' : 'asc');
            sortRows(col, isAsc ? 'desc' : 'asc');
        });
    });
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.cdd')) document.querySelectorAll('.cdd-menu.open').forEach(m => { m.classList.remove('open'); m.previousElementSibling.classList.remove('open'); });
    });
    document.getElementById('columnSettingsModal')?.addEventListener('show.bs.modal', function() {
        document.querySelectorAll('.column-toggle:not([disabled])').forEach(t => { const el = document.querySelector(`.column-${t.value}`); if(el) t.checked = !el.classList.contains('column-hidden'); });
    });
}

function loadColumnSettings() {
    const defaults = { job_role:true, no_hp:true, role:true, status:true, created_at:false, updated_at:false };
    let s = { ...defaults };
    try { const saved = localStorage.getItem(STORAGE_KEY); if(saved) s = { ...defaults, ...JSON.parse(saved) }; } catch(e) {}
    document.querySelectorAll('.column-toggle:not([disabled])').forEach(t => { t.checked = s.hasOwnProperty(t.value) ? s[t.value] : (defaults[t.value] ?? true); toggleColumn(t.value, t.checked); });
}
function toggleColumn(col, show) { document.querySelectorAll(`.column-${col}`).forEach(el => el.classList.toggle('column-hidden', !show)); }
function saveColumnSettings() {
    const s = {};
    document.querySelectorAll('.column-toggle:not([disabled])').forEach(t => { s[t.value] = t.checked; });
    localStorage.setItem(STORAGE_KEY, JSON.stringify(s));
    bootstrap.Modal.getInstance(document.getElementById('columnSettingsModal'))?.hide();
    Swal.fire({ icon:'success', title:'Tersimpan', showConfirmButton:false, timer:1200, confirmButtonColor:'#5145cd' });
}
function resetColumns() {
    const defaults = { job_role:true, no_hp:true, role:true, status:true, created_at:false, updated_at:false };
    document.querySelectorAll('.column-toggle:not([disabled])').forEach(t => { t.checked = defaults[t.value]??true; toggleColumn(t.value, t.checked); });
}
function filterByJobRole(id) {
    currentJobRole = String(id); currentPage = 1;
    document.querySelectorAll('.counter-pill').forEach(p => p.classList.toggle('active', p.dataset.jobrole === currentJobRole));
    applyFilters();
}
function applyFilters() {
    filteredRows = allRows.filter(row => {
        if (currentJobRole !== '' && String(row.job_role_id) !== currentJobRole) return false;
        if (currentRole    !== '' && row.role !== currentRole) return false;
        if (currentSearch  !== '' && !row.nama.includes(currentSearch) && !row.email.includes(currentSearch) && !row.job_role.includes(currentSearch)) return false;
        return true;
    });
    currentPage = 1; renderTable();
}
function sortRows(column, direction) {
    filteredRows.sort((a, b) => {
        if (['created_at','updated_at'].includes(column)) return direction==='asc' ? new Date(a[column])-new Date(b[column]) : new Date(b[column])-new Date(a[column]);
        if (column === 'status') return direction==='asc' ? parseInt(a[column])-parseInt(b[column]) : parseInt(b[column])-parseInt(a[column]);
        return direction==='asc' ? String(a[column]||'').localeCompare(String(b[column]||'')) : String(b[column]||'').localeCompare(String(a[column]||''));
    });
    renderTable();
}
function renderTable() {
    const tbody = document.getElementById('userTableBody');
    tbody.innerHTML = '';
    const start = (currentPage - 1) * perPage;
    const page  = filteredRows.slice(start, Math.min(start + perPage, filteredRows.length));
    if (page.length === 0) {
        tbody.innerHTML = `<tr><td colspan="9"><div class="empty-state"><i class='bx bx-user-x'></i><p>Tidak ada data user yang ditemukan.</p></div></td></tr>`;
    } else {
        page.forEach((row, idx) => { const clone = row.element.cloneNode(true); const n = clone.querySelector('.row-no'); if(n) n.textContent = start + idx + 1; tbody.appendChild(clone); });
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
    let sp = Math.max(1,currentPage-2), ep = Math.min(total,sp+4);
    if (ep-sp<4) sp = Math.max(1,ep-4);
    if (sp>1) { html+=`<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="goToPage(1)">1</a></li>`; if(sp>2) html+=`<li class="page-item disabled"><span class="page-link">…</span></li>`; }
    for (let i=sp;i<=ep;i++) html+=`<li class="page-item ${i===currentPage?'active':''}"><a class="page-link" href="javascript:void(0)" onclick="goToPage(${i})">${i}</a></li>`;
    if (ep<total) { if(ep<total-1) html+=`<li class="page-item disabled"><span class="page-link">…</span></li>`; html+=`<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="goToPage(${total})">${total}</a></li>`; }
    html+=`<li class="page-item ${currentPage===total?'disabled':''}"><a class="page-link" href="javascript:void(0)" onclick="goToPage(${currentPage+1})"><i class='bx bx-chevron-right'></i></a></li>`;
    ctrl.innerHTML = html;
}
function goToPage(page) { const t=Math.ceil(filteredRows.length/perPage); if(page<1||page>t)return; currentPage=page; renderTable(); }
function formatDate(str) { if(!str)return'—'; const d=new Date(str); return d.toLocaleDateString('id-ID',{day:'2-digit',month:'2-digit',year:'numeric'})+' '+d.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'}); }

const ROLE_CONFIG = {
    admin:    { icon:'bx-key',        label:'Admin' },
    PM:       { icon:'bx-task',         label:'Project Manager' },
    karyawan: { icon:'bx-user-circle',  label:'Karyawan' },
    klien:    { icon:'bx-shie   ld-alt-2', label:'Klien' }

};

/* ── Job Role UI: karyawan=dropdown, lainnya=info otomatis ── */
function applyJobRoleUI(prefix, role) {
    const fieldEl  = document.getElementById(prefix + 'JobRoleField');
    const infoEl   = document.getElementById(prefix + 'JobRoleInfo');
    const infoText = document.getElementById(prefix + 'JobRoleInfoText');
    if (!fieldEl || !infoEl) return;
    if (role === 'karyawan') {
        fieldEl.style.display = 'block'; infoEl.style.display = 'none';
    } else if (role === '') {
        fieldEl.style.display = 'none';  infoEl.style.display = 'none';
    } else {
        fieldEl.style.display = 'none';  infoEl.style.display = 'block';
        const labels = { admin:'Admin', PM:'Project Manager', klien:'Klien' };
        if (infoText) infoText.textContent = labels[role] || '— otomatis —';
    }
}
function handleAddRoleChange(role) {
    applyJobRoleUI('add', role);
    if (role !== 'karyawan') { document.getElementById('add_id_job_role').value = ''; const t = document.getElementById('addCddTxt'); if(t) t.textContent = '— Pilih Job Role —'; }
}
function handleEditRoleChange(role) {
    applyJobRoleUI('edit', role);
    if (role !== 'karyawan') { document.getElementById('edit_id_job_role').value = ''; const t = document.getElementById('editSelectedText'); if(t) t.textContent = '— Pilih Job Role —'; }
}

/* ── VIEW ── */
function viewUser(btn) {
    const d = JSON.parse(btn.dataset.item);
    const jobRoleName = btn.dataset.jobRoleName || '—', jobRoleColor = btn.dataset.jobRoleColor || 'purple-1';
    currentViewBtn = btn;
    const fc = document.getElementById('view_foto_container');
    fc.innerHTML = d.foto
        ? `<img src="/storage/${d.foto}" alt="${d.nama}" style="width:88px;height:88px;border-radius:50%;object-fit:cover;border:3px solid var(--p-soft);">`
        : `<div class="av-lg">${d.nama.charAt(0).toUpperCase()}</div>`;
    document.getElementById('view_nama').textContent       = d.nama;
    document.getElementById('view_email').textContent      = d.email;
    document.getElementById('view_no_hp').textContent      = d.no_hp || '—';
    document.getElementById('view_created_at').textContent = formatDate(d.created_at);
    document.getElementById('view_updated_at').textContent = formatDate(d.updated_at);
    const rc = ROLE_CONFIG[d.role] || ROLE_CONFIG.karyawan;
    const roleClass = 'role-' + (d.role === 'PM' ? 'pm' : d.role);
    document.getElementById('view_role_badge').innerHTML = `<span class="role-pill ${roleClass}"><i class='bx ${rc.icon}' style="font-size:13px;"></i> ${rc.label}</span>`;
    document.getElementById('view_job_role').innerHTML = (jobRoleName && jobRoleName !== '-')
        ? `<span class="jr-pill ${jobRoleColor}">${jobRoleName}</span>`
        : '<span style="color:var(--ink-400);font-size:13px;">—</span>';
    document.getElementById('view_status_badge').innerHTML = d.status
        ? '<span class="status-pill pill-active"><span class="dot"></span>Aktif</span>'
        : '<span class="status-pill pill-inactive"><span class="dot"></span>Non-Aktif</span>';
    document.getElementById('view_edit_btn').onclick = function() {
        bootstrap.Modal.getInstance(document.getElementById('viewUserModal'))?.hide();
        setTimeout(() => { if(currentViewBtn) editUser(currentViewBtn); }, 300);
    };
    new bootstrap.Modal(document.getElementById('viewUserModal')).show();
}

/* ── EDIT ── */
function editUser(btn) {
    const d = JSON.parse(btn.dataset.item), jobRoleId = btn.dataset.jobRoleId || '', isKlien = (d.role === 'klien');
    document.getElementById('edit_nama').value     = d.nama;
    document.getElementById('edit_email').value    = d.email;
    document.getElementById('edit_no_hp').value    = d.no_hp || '';
    document.getElementById('edit_status').value   = d.status ? '1' : '0';
    document.getElementById('edit_password').value = '';
    document.getElementById('editUserForm').action = `/master-data-users/${d.id_user}`;
    const roleSelect = document.getElementById('edit_role'), roleHidden = document.getElementById('edit_role_hidden'), roleBadge = document.getElementById('edit_role_klien_badge');
    roleSelect.value = d.role;
    if (isKlien) {
        roleSelect.disabled = true; roleSelect.removeAttribute('name');
        roleHidden.value = 'klien'; roleHidden.name = 'role';
        roleBadge.style.display = 'inline-flex';
    } else {
        roleSelect.disabled = false; roleSelect.name = 'role';
        roleHidden.value = ''; roleHidden.removeAttribute('name');
        roleBadge.style.display = 'none';
    }
    applyJobRoleUI('edit', d.role);
    if (d.role === 'karyawan' && jobRoleId) {
        const opt = document.querySelector(`#editCddOpts .cdd-opt[data-value="${jobRoleId}"]`);
        cddSelect('edit', jobRoleId, opt ? opt.dataset.text : '— Pilih Job Role —');
    } else {
        cddSelect('edit','','— Pilih Job Role —');
    }
    const cp = document.getElementById('currentPhotoPreview');
    cp.innerHTML = d.foto ? `<img src="/storage/${d.foto}" alt="${d.nama}">` : `<div class="av" style="width:60px;height:60px;font-size:22px;">${d.nama.charAt(0).toUpperCase()}</div>`;
    document.getElementById('editPhotoPreview').style.display = 'none';
    document.getElementById('edit_foto').value = '';
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}

/* ── DELETE ── */
function deleteUser(id, nama) {
    Swal.fire({ title:'Hapus User?', html:`Tindakan ini akan menghapus <strong>${nama}</strong> secara permanen.`, icon:'warning', showCancelButton:true, confirmButtonColor:'#DC2626', cancelButtonColor:'#6B7280', confirmButtonText:'Hapus', cancelButtonText:'Batal' })
    .then(r => { if(r.isConfirmed) { const f=document.createElement('form'); f.method='POST'; f.action=`/master-data-users/${id}`; f.innerHTML=`<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`; document.body.appendChild(f); f.submit(); } });
}

/* ── Custom Dropdown ── */
function cddToggle(type) { const menu=document.getElementById(type+'CddMenu'), disp=menu.previousElementSibling; document.querySelectorAll('.cdd-menu.open').forEach(m => { if(m!==menu){m.classList.remove('open');m.previousElementSibling.classList.remove('open');} }); menu.classList.toggle('open'); disp.classList.toggle('open'); }
function cddFilter(type, val) { document.querySelectorAll(`#${type}CddOpts .cdd-opt`).forEach(o => { const txt=(o.dataset.text||o.textContent).toLowerCase(); o.classList.toggle('hidden',!txt.includes(val.toLowerCase())); }); }
function cddSelect(type, value, text) {
    document.getElementById(type+'_id_job_role').value = value;
    const txt = type==='edit' ? document.getElementById('editSelectedText') : document.getElementById(type+'CddTxt');
    if(txt) txt.textContent = text;
    document.querySelectorAll(`#${type}CddOpts .cdd-opt`).forEach(o => o.classList.toggle('selected', o.dataset.value===value));
    const menu=document.getElementById(type+'CddMenu');
    if(menu) { menu.classList.remove('open'); menu.previousElementSibling.classList.remove('open'); }
    const si=menu?.querySelector('.cdd-search input'); if(si) { si.value=''; cddFilter(type,''); }
}
function previewPhoto(event, imgId, previewId) {
    const file=event.target.files[0]; if(!file)return;
    if(!['image/jpeg','image/jpg','image/png'].includes(file.type)) { Swal.fire({icon:'error',title:'Format Tidak Valid',text:'Gunakan JPG, JPEG, atau PNG',confirmButtonColor:'#5145cd'}); event.target.value=''; return; }
    if(file.size>2*1024*1024) { Swal.fire({icon:'error',title:'Terlalu Besar',text:'Ukuran foto maksimal 2MB',confirmButtonColor:'#5145cd'}); event.target.value=''; return; }
    const reader=new FileReader(); reader.onload=e => { document.getElementById(imgId).src=e.target.result; document.getElementById(previewId).style.display='flex'; }; reader.readAsDataURL(file);
}
function clearPhoto(inputId, imgId, previewId) { document.getElementById(inputId).value=''; document.getElementById(imgId).src=''; document.getElementById(previewId).style.display='none'; }
function togglePw(inputId, iconId) { const inp=document.getElementById(inputId), ic=document.getElementById(iconId), show=inp.type==='password'; inp.type=show?'text':'password'; ic.className=show?'bx bx-show':'bx bx-hide'; }

['addUserForm','editUserForm'].forEach(formId => {
    const form=document.getElementById(formId); if(!form)return;
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const isEdit = formId==='editUserForm';
        const roleVal = isEdit ? document.getElementById('edit_role').value : document.getElementById('add_role').value;
        const jrVal   = document.getElementById(isEdit ? 'edit_id_job_role' : 'add_id_job_role').value;
        // Hanya karyawan yg wajib pilih job role manual
        if (roleVal === 'karyawan' && !jrVal) {
            Swal.fire({icon:'warning',title:'Job Role Wajib',text:'Karyawan harus memilih job role.',confirmButtonColor:'#5145cd'});
            return;
        }
        const mid = isEdit?'editUserModal':'addUserModal';
        bootstrap.Modal.getInstance(document.getElementById(mid))?.hide();
        setTimeout(() => { Swal.fire({title:'Menyimpan...',allowOutsideClick:false,didOpen:()=>Swal.showLoading()}); this.submit(); }, 300);
    });
});
</script>
@endpush