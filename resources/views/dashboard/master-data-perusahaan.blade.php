@extends('layouts.master')
@section('title', 'Master Data Perusahaan')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-semibold mb-1">Master Data Perusahaan</h4>
                <p class="text-muted mb-0 small">Kelola data perusahaan mitra</p>
            </div>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPerusahaanModal">
                <i class='bx bx-plus'></i> Tambah Perusahaan
            </button>
        </div>
        
        <!-- Table Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <form method="GET" action="{{ route('master-data-perusahaan.index') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <!-- Left: Pagination -->
                                <div class="d-flex align-items-center">
                                    <label class="me-2 text-nowrap small">Tampilkan:</label>
                                    <select name="per_page" id="perPageSelect" class="form-select form-select-sm" style="width: 80px;">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                                
                                <!-- Right: Search + Column settings -->
                                <div class="d-flex align-items-center gap-2">
                                    <div class="search-box">
                                        <i class='bx bx-search'></i>
                                        <input type="text" name="search" id="searchInput" 
                                               placeholder="Cari perusahaan..." 
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
                </form>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="column-no" style="width: 60px;">NO</th>
                            <th class="column-nama sortable" data-column="nama_perusahaan" style="min-width: 280px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>PERUSAHAAN & PERWAKILAN</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-email" style="min-width: 220px;">EMAIL</th>
                            <th class="column-telepon" style="min-width: 150px;">TELEPON</th>
                            <th class="column-alamat" style="min-width: 200px;">ALAMAT</th>
                            <th class="column-dibuat sortable" data-column="dibuat_pada" style="min-width: 140px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>DIBUAT</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-diubah sortable" data-column="diperbarui_pada" style="min-width: 140px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>DIUBAH</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-actions text-center" style="width: 140px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="perusahaanTableBody">
                        @forelse($perusahaans as $index => $perusahaan)
                        <tr class="perusahaan-row" 
                            data-original-index="{{ $index + 1 }}"
                            data-nama_perusahaan="{{ strtolower($perusahaan->nama_perusahaan ?? '') }}"
                            data-dibuat_pada="{{ $perusahaan->dibuat_pada ? $perusahaan->dibuat_pada->format('Y-m-d H:i:s') : '' }}"
                            data-diperbarui_pada="{{ $perusahaan->diperbarui_pada ? $perusahaan->diperbarui_pada->format('Y-m-d H:i:s') : '' }}">
                            <td class="column-no">
                                <span class="row-number">{{ $perusahaans->firstItem() + $index }}</span>
                            </td>
                            
                            <!-- KOLOM PERUSAHAAN & PERWAKILAN -->
                            <td class="column-nama">
                                <div class="d-flex align-items-start">
                                    @if($perusahaan->logo_perusahaan)
                                    <img src="{{ asset('storage/' . $perusahaan->logo_perusahaan) }}" 
                                         alt="{{ $perusahaan->nama_perusahaan }}" 
                                         class="company-logo" 
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="company-avatar" style="display: none;">
                                        <i class='bx bx-buildings'></i>
                                    </div>
                                    @else
                                    <div class="company-avatar">
                                        <i class='bx bx-buildings'></i>
                                    </div>
                                    @endif
                                    
                                    <div class="ms-3">
                                        <!-- Nama Perusahaan (dari users.nama) -->
                                        <div class="company-name mb-1">
                                           {{ $perusahaan->nama_perusahaan ?? '-' }}
                                        </div>
                                        
                                        <!-- Nama Perwakilan (dari perusahaan.nama_perwakilan) -->
                                        <div class="representative-name">
                                            <i class='bx bxs-user me-1'></i>{{ $perusahaan->nama_perwakilan ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- KOLOM EMAIL -->
                            <td class="column-email">
                                <div class="email-container">
                                    <!-- Email Perusahaan (dari users.email) -->
                                    <div class="email-item">
                                        <i class='bx bxs-envelope email-icon-company'></i>
                                        <span class="email-text">{{ $perusahaan->email_perusahaan ?? '-' }}</span>
                                    </div>
                                    
                                    <!-- Email Perwakilan (dari perusahaan.email_perwakilan) -->
                                    <div class="email-item-secondary">
                                        <i class='bx bxs-user-voice email-icon-person'></i>
                                        <span class="email-text-secondary">{{ $perusahaan->email_perwakilan ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- KOLOM TELEPON -->
                            <td class="column-telepon">
                                <div class="phone-container">
                                    <!-- Telepon Perusahaan (dari users.no_hp) -->
                                    @if($perusahaan->telepon_perusahaan)
                                    <div class="phone-item">
                                        <i class='bx bxs-phone phone-icon-company'></i>
                                        <span class="phone-text">{{ $perusahaan->telepon_perusahaan }}</span>
                                    </div>
                                    @else
                                    <div class="phone-item text-muted">
                                        <i class='bx bx-phone me-1'></i>
                                        <span>-</span>
                                    </div>
                                    @endif
                                    
                                    <!-- Telepon Perwakilan (dari perusahaan.telepon_perwakilan) -->
                                    @if($perusahaan->telepon_perwakilan)
                                    <div class="phone-item-secondary">
                                        <i class='bx bxs-mobile phone-icon-person'></i>
                                        <span class="phone-text-secondary">{{ $perusahaan->telepon_perwakilan }}</span>
                                    </div>
                                    @else
                                    <div class="phone-item-secondary text-muted">
                                        <i class='bx bx-mobile me-1'></i>
                                        <span>-</span>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- KOLOM ALAMAT -->
                            <td class="column-alamat">
                                <div class="address-container">
                                    <span class="address-text">{{ $perusahaan->alamat_perusahaan ?? '-' }}</span>
                                </div>
                            </td>
                            
                            <!-- KOLOM DIBUAT -->
                            <td class="column-dibuat">
                                <span class="date-text">{{ $perusahaan->dibuat_pada ? $perusahaan->dibuat_pada->format('d/m/Y H:i') : '-' }}</span>
                            </td>
                            
                            <!-- KOLOM DIUBAH -->
                            <td class="column-diubah">
                                <span class="date-text">{{ $perusahaan->diperbarui_pada ? $perusahaan->diperbarui_pada->format('d/m/Y H:i') : '-' }}</span>
                            </td>
                            
                            <!-- KOLOM AKSI -->
<td class="column-actions text-center">
    <div class="action-buttons">
        <button type="button" 
                class="action-btn view-btn" 
                onclick="viewPerusahaan({{ $perusahaan->id_perusahaan }})"
                data-id="{{ $perusahaan->id_perusahaan }}"
                data-nama-perusahaan="{{ $perusahaan->nama_perusahaan }}"
                data-email-perusahaan="{{ $perusahaan->email_perusahaan }}"
                data-telepon-perusahaan="{{ $perusahaan->telepon_perusahaan }}"
                data-nama-perwakilan="{{ $perusahaan->nama_perwakilan }}"
                data-email-perwakilan="{{ $perusahaan->email_perwakilan }}"
                data-telepon-perwakilan="{{ $perusahaan->telepon_perwakilan }}"
                data-alamat="{{ $perusahaan->alamat_perusahaan }}"
                data-logo="{{ $perusahaan->logo_perusahaan }}"
                data-dibuat="{{ $perusahaan->dibuat_pada ? $perusahaan->dibuat_pada->format('d/m/Y H:i') : '-' }}"
                data-diubah="{{ $perusahaan->diperbarui_pada ? $perusahaan->diperbarui_pada->format('d/m/Y H:i') : '-' }}"
                title="Lihat Detail">
            <i class='bx bx-show'></i>
        </button>
        <button type="button" 
                class="action-btn edit-btn" 
                onclick="editPerusahaan({{ $perusahaan->id_perusahaan }})"
                data-id="{{ $perusahaan->id_perusahaan }}"
                data-nama-perusahaan="{{ $perusahaan->nama_perusahaan }}"
                data-email-perusahaan="{{ $perusahaan->email_perusahaan }}"
                data-telepon-perusahaan="{{ $perusahaan->telepon_perusahaan }}"
                data-nama-perwakilan="{{ $perusahaan->nama_perwakilan }}"
                data-email-perwakilan="{{ $perusahaan->email_perwakilan }}"
                data-telepon-perwakilan="{{ $perusahaan->telepon_perwakilan }}"
                data-alamat="{{ $perusahaan->alamat_perusahaan }}"
                data-logo="{{ $perusahaan->logo_perusahaan }}"
                title="Edit">
            <i class='bx bx-edit'></i>
        </button>
        <button type="button" 
                class="action-btn delete-btn" 
                onclick="deletePerusahaan({{ $perusahaan->id_perusahaan }}, '{{ $perusahaan->nama_perusahaan }}')"
                title="Hapus">
            <i class='bx bx-trash'></i>
        </button>
    </div>
</td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="8" class="text-center py-5">
                                <i class='bx bx-buildings empty-icon'></i>
                                <p class="empty-text">Belum ada data perusahaan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer bg-white border-0 py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0 text-muted small">
                            Menampilkan {{ $perusahaans->firstItem() ?? 0 }} - {{ $perusahaans->lastItem() ?? 0 }} dari {{ $perusahaans->total() }} data
                        </p>
                    </div>
                    <div class="col-md-6">
                        <nav>
                            <ul class="pagination pagination-sm justify-content-md-end mb-0">
                                {{ $perusahaans->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Perusahaan Modal -->
<div class="modal fade" id="viewPerusahaanModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">Detail Perusahaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="row g-4">
                    <!-- Logo & Nama Perusahaan -->
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <div id="view_logo_container" class="me-3"></div>
                            <div>
                                <h5 class="mb-1" id="view_nama_perusahaan"></h5>
                                <p class="text-muted mb-0 small"><i class='bx bxs-user me-1'></i><span id="view_nama_perwakilan"></span></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12"><hr class="my-2"></div>
                    
                    <!-- Informasi Perusahaan -->
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <i class='bx bx-buildings me-2'></i>Informasi Perusahaan
                        </h6>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="small text-muted mb-1">Email Perusahaan</label>
                        <p class="mb-0" id="view_email_perusahaan"></p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="small text-muted mb-1">Telepon Perusahaan</label>
                        <p class="mb-0" id="view_telepon_perusahaan"></p>
                    </div>
                    
                    <div class="col-12">
                        <label class="small text-muted mb-1">Alamat Perusahaan</label>
                        <p class="mb-0" id="view_alamat_perusahaan"></p>
                    </div>
                    
                    <div class="col-12"><hr class="my-2"></div>
                    
                    <!-- Informasi Perwakilan -->
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3 text-success">
                            <i class='bx bx-user me-2'></i>Informasi Perwakilan (PIC)
                        </h6>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="small text-muted mb-1">Nama Perwakilan</label>
                        <p class="mb-0" id="view_nama_perwakilan_full"></p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="small text-muted mb-1">Email Perwakilan</label>
                        <p class="mb-0" id="view_email_perwakilan"></p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="small text-muted mb-1">Telepon Perwakilan</label>
                        <p class="mb-0" id="view_telepon_perwakilan"></p>
                    </div>
                    
                    <div class="col-12"><hr class="my-2"></div>
                    
                    <!-- Timestamp -->
                    <div class="col-md-6">
                        <label class="small text-muted mb-1">Dibuat Pada</label>
                        <p class="mb-0" id="view_dibuat_pada"></p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="small text-muted mb-1">Terakhir Diubah</label>
                        <p class="mb-0" id="view_diperbarui_pada"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Perusahaan Modal -->
<div class="modal fade" id="addPerusahaanModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">Tambah Perusahaan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('master-data-perusahaan.store') }}" method="POST" enctype="multipart/form-data" id="addPerusahaanForm">
                @csrf
                <div class="modal-body pt-3">
                    <div class="row g-3">
                        <!-- Data Perusahaan (masuk ke tabel users) -->
                        <div class="col-12">
                            <h6 class="fw-semibold mb-3">
                                <i class='bx bx-buildings me-2 text-primary'></i>Data Perusahaan
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small">Nama Perusahaan *</label>
                            <input type="text" name="nama_perusahaan" class="form-control" required maxlength="100" placeholder="PT ABC Indonesia">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small">Email Perusahaan *</label>
                            <input type="email" name="email_perusahaan" class="form-control" required maxlength="100" placeholder="info@abc.co.id">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small">Telepon Perusahaan</label>
                            <input type="text" name="telepon_perusahaan" class="form-control" maxlength="20" placeholder="021-1234567">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small">Password *</label>
                            <div class="input-group">
                                <input type="password" name="password_perusahaan" id="add_password" class="form-control" required minlength="8">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('add_password', 'add_password_icon')">
                                    <i class='bx bx-hide' id="add_password_icon"></i>
                                </button>
                            </div>
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label small">Alamat Perusahaan *</label>
                            <textarea name="alamat_perusahaan" class="form-control" rows="2" required placeholder="Jl. Sudirman No. 123, Jakarta"></textarea>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label small">Logo Perusahaan</label>
                            <div class="position-relative">
                                <input type="file" name="logo_perusahaan" id="add_logo" class="form-control" accept="image/jpeg,image/png,image/jpg" onchange="previewAddLogo(event)">
                                <button type="button" class="btn btn-sm btn-danger position-absolute" id="clearAddLogo" style="display: none; top: 4px; right: 4px;" onclick="clearAddLogoPreview()">
                                    <i class='bx bx-x'></i>
                                </button>
                            </div>
                            <small class="text-muted">Format: JPG, JPEG, PNG | Maksimal 2MB</small>
                            <div id="addLogoPreview" class="mt-2" style="display: none;">
                                <img id="addLogoImg" src="" alt="Preview" class="preview-image">
                            </div>
                        </div>
                        
                        <!-- Data Perwakilan (masuk ke tabel perusahaan) -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-semibold mb-3">
                                <i class='bx bx-user me-2 text-success'></i>Data Perwakilan (PIC)
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small">Nama Perwakilan *</label>
                            <input type="text" name="nama_perwakilan" class="form-control" required maxlength="100" placeholder="Ahmad Wijaya">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small">Email Perwakilan *</label>
                            <input type="email" name="email_perwakilan" class="form-control" required maxlength="100" placeholder="ahmad@abc.co.id">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small">Telepon Perwakilan</label>
                            <input type="text" name="telepon_perwakilan" class="form-control" maxlength="20" placeholder="08123456789">
                        </div>
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

<!-- Edit Perusahaan Modal -->
<div class="modal fade" id="editPerusahaanModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">Edit Perusahaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPerusahaanForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body pt-3">
                    <div class="row g-3">
                        <!-- Data Perusahaan (di tabel users) -->
                        <div class="col-12">
                            <h6 class="fw-semibold mb-3">
                                <i class='bx bx-buildings me-2 text-primary'></i>Data Perusahaan
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small">Nama Perusahaan *</label>
                            <input type="text" name="nama_perusahaan" id="edit_nama_perusahaan" class="form-control" required maxlength="100">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small">Email Perusahaan *</label>
                            <input type="email" name="email_perusahaan" id="edit_email_perusahaan" class="form-control" required maxlength="100">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small">Telepon Perusahaan</label>
                            <input type="text" name="telepon_perusahaan" id="edit_telepon_perusahaan" class="form-control" maxlength="20">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small">Password (kosongkan jika tidak diubah)</label>
                            <div class="input-group">
                                <input type="password" name="password_perusahaan" id="edit_password" class="form-control" minlength="8">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('edit_password', 'edit_password_icon')">
                                    <i class='bx bx-hide' id="edit_password_icon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label small">Alamat Perusahaan *</label>
                            <textarea name="alamat_perusahaan" id="edit_alamat_perusahaan" class="form-control" rows="2" required></textarea>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label small">Logo Perusahaan Saat Ini</label>
                            <div id="currentLogoPreview" class="mb-2"></div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label small">Ganti Logo Perusahaan</label>
                            <div class="position-relative">
                                <input type="file" name="logo_perusahaan" id="edit_logo" class="form-control" accept="image/jpeg,image/png,image/jpg" onchange="previewNewLogo(event)">
                                <button type="button" class="btn btn-sm btn-danger position-absolute" id="clearEditLogo" style="display: none; top: 4px; right: 4px;" onclick="clearEditLogoPreview()">
                                    <i class='bx bx-x'></i>
                                </button>
                            </div>
                            <small class="text-muted">Format: JPG, JPEG, PNG | Maksimal 2MB</small>
                            <div id="newLogoPreview" class="mt-2" style="display: none;">
                                <img id="newLogoImg" src="" alt="Preview" class="preview-image">
                            </div>
                        </div>
                        
                        <!-- Data Perwakilan (di tabel perusahaan) -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-semibold mb-3">
                                <i class='bx bx-user me-2 text-success'></i>Data Perwakilan (PIC)
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small">Nama Perwakilan *</label>
                            <input type="text" name="nama_perwakilan" id="edit_nama_perwakilan" class="form-control" required maxlength="100">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small">Email Perwakilan *</label>
                            <input type="email" name="email_perwakilan" id="edit_email_perwakilan" class="form-control" required maxlength="100">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small">Telepon Perwakilan</label>
                            <input type="text" name="telepon_perwakilan" id="edit_telepon_perwakilan" class="form-control" maxlength="20">
                        </div>
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
                    <label class="form-check-label small" for="col-nama">Perusahaan & Perwakilan</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="email" id="col-email" checked>
                    <label class="form-check-label small" for="col-email">Email</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="telepon" id="col-telepon" checked>
                    <label class="form-check-label small" for="col-telepon">Telepon</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="alamat" id="col-alamat" checked>
                    <label class="form-check-label small" for="col-alamat">Alamat</label>
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
/* ===== MODERN TABLE DESIGN ===== */
/* Company Logo & Avatar */
.company-logo,
.company-avatar {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    flex-shrink: 0;
}

.company-logo {
    object-fit: cover;
    border: 2px solid #e7e7ff;
}

.company-avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

/* Company & Representative Names */
.company-name {
    font-weight: 600;
    color: #2d3748;
    font-size: 0.9rem;
    line-height: 1.4;
}

.representative-name {
    font-weight: 500;
    color: #805ad5;
    font-size: 0.8125rem;
    line-height: 1.4;
}

/* Email Container */
.email-container {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.email-item,
.email-item-secondary {
    display: flex;
    align-items: center;
    gap: 6px;
}

/* EMAIL ICONS - PERUSAHAAN (HIJAU) */
.email-icon-company {
    color: #10b981;
    font-size: 16px;
}

/* EMAIL ICONS - PERWAKILAN (PRIMER) */
.email-icon-person {
    color: #667eea;
    font-size: 16px;
}

.email-text {
    font-size: 0.8125rem;
    color: #2d3748;
    font-weight: 500;
}

.email-text-secondary {
    font-size: 0.8125rem;
    color: #718096;
    font-weight: 500;
}

/* Phone Container */
.phone-container {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.phone-item,
.phone-item-secondary {
    display: flex;
    align-items: center;
    gap: 6px;
}

/* PHONE ICONS - PERUSAHAAN (HIJAU) */
.phone-icon-company {
    color: #10b981;
    font-size: 16px;
}

/* PHONE ICONS - PERWAKILAN (PRIMER) */
.phone-icon-person {
    color: #667eea;
    font-size: 16px;
}

.phone-text {
    font-size: 0.8125rem;
    color: #2d3748;
    font-weight: 500;
}

.phone-text-secondary {
    font-size: 0.8125rem;
    color: #718096;
    font-weight: 500;
}

/* Address */
.address-container {
    display: flex;
    align-items: start;
    gap: 6px;
}

.address-text {
    font-size: 0.8125rem;
    color: #4a5568;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
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
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
    color: #667eea;
}

.sortable.asc .sort-icon {
    transform: rotate(180deg);
}

/* Action Buttons */
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

/* Card */
.card {
    border-radius: 12px;
    overflow: hidden;
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
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Buttons */
.btn-sm {
    font-size: 0.8125rem;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 500;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5145cd 0%, #6a3d8f 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
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

/* Preview Image */
.preview-image {
    max-width: 160px;
    max-height: 160px;
    border-radius: 10px;
    object-fit: cover;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border: 2px solid #e2e8f0;
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    
    .company-logo,
    .company-avatar {
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
    confirmButtonColor: '#667eea',
    timer: 3000,
    timerProgressBar: true
});
@endif

@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '{{ session('error') }}',
    confirmButtonColor: '#667eea'
});
@endif

// ===== CONSTANTS & CONFIG =====
const STORAGE_KEY = 'perusahaan_column_settings';

// ===== SEARCH & FILTER =====
let searchTimeout = null;
const searchInput = document.getElementById('searchInput');
const perPageSelect = document.getElementById('perPageSelect');
const clearSearchBtn = document.getElementById('clearSearch');
const filterForm = document.getElementById('filterForm');

searchInput.addEventListener('input', function() {
    const searchValue = this.value.trim();
    
    clearSearchBtn.style.display = searchValue.length > 0 ? 'block' : 'none';
    
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        if (searchValue.length >= 2 || searchValue.length === 0) {
            filterForm.submit();
        }
    }, 400);
});

clearSearchBtn.addEventListener('click', function() {
    searchInput.value = '';
    this.style.display = 'none';
    filterForm.submit();
});

perPageSelect.addEventListener('change', () => filterForm.submit());

// ===== COLUMN SETTINGS FUNCTIONS =====
function loadColumnSettings() {
    try {
        const savedSettings = localStorage.getItem(STORAGE_KEY);
        
        const defaultSettings = {
            'email': true,
            'telepon': true,
            'alamat': true,
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
        'email': true,
        'telepon': true,
        'alamat': true,
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

document.querySelectorAll('.column-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        toggleColumn(this.value, this.checked);
    });
});

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
        'email': true,
        'telepon': true,
        'alamat': true,
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

// ===== ROW NUMBERS =====
function updateRowNumbers() {
    const visibleRows = Array.from(document.querySelectorAll('.perusahaan-row'))
        .filter(row => row.style.display !== 'none');
    visibleRows.forEach((row, index) => {
        const numberCell = row.querySelector('.row-number');
        if (numberCell) numberCell.textContent = index + 1;
    });
}

// ===== SORTING =====
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
    const tbody = document.getElementById('perusahaanTableBody');
    const rows = Array.from(tbody.querySelectorAll('.perusahaan-row'));
    
    rows.sort((a, b) => {
        let aVal = a.dataset[column] || '';
        let bVal = b.dataset[column] || '';
        
        if (column === 'dibuat_pada' || column === 'diperbarui_pada') {
            aVal = new Date(aVal).getTime() || 0;
            bVal = new Date(bVal).getTime() || 0;
            return direction === 'asc' ? aVal - bVal : bVal - aVal;
        }
        
        return direction === 'asc' ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
    });
    
    rows.forEach(row => tbody.appendChild(row));
    updateRowNumbers();
}

// ===== VIEW PERUSAHAAN =====
function viewPerusahaan(id) {
    const button = event.target.closest('button');
    
    // Ambil data dari data attributes
    const namaPerusahaan = button.getAttribute('data-nama-perusahaan');
    const emailPerusahaan = button.getAttribute('data-email-perusahaan');
    const teleponPerusahaan = button.getAttribute('data-telepon-perusahaan');
    const namaPerwakilan = button.getAttribute('data-nama-perwakilan');
    const emailPerwakilan = button.getAttribute('data-email-perwakilan');
    const teleponPerwakilan = button.getAttribute('data-telepon-perwakilan');
    const alamat = button.getAttribute('data-alamat');
    const logo = button.getAttribute('data-logo');
    const dibuat = button.getAttribute('data-dibuat');
    const diubah = button.getAttribute('data-diubah');
    
    console.log('View Data:', {
        namaPerusahaan,
        emailPerusahaan,
        teleponPerusahaan,
        namaPerwakilan,
        emailPerwakilan,
        teleponPerwakilan
    });
    
    // Set nama perusahaan (dari users.nama)
    document.getElementById('view_nama_perusahaan').textContent = namaPerusahaan || '-';
    document.getElementById('view_nama_perwakilan').textContent = namaPerwakilan || '-';
    
    // Set logo
    const logoContainer = document.getElementById('view_logo_container');
    if (logo && logo !== 'null' && logo !== '') {
        logoContainer.innerHTML = `<img src="/storage/${logo}" alt="${namaPerusahaan}" class="company-logo" style="width: 60px; height: 60px;">`;
    } else {
        logoContainer.innerHTML = `<div class="company-avatar" style="width: 60px; height: 60px; font-size: 24px;"><i class='bx bx-buildings'></i></div>`;
    }
    
    // Set data perusahaan (dari users)
    document.getElementById('view_email_perusahaan').textContent = emailPerusahaan || '-';
    document.getElementById('view_telepon_perusahaan').textContent = teleponPerusahaan || '-';
    document.getElementById('view_alamat_perusahaan').textContent = alamat || '-';
    
    // Set data perwakilan (dari perusahaan)
    document.getElementById('view_nama_perwakilan_full').textContent = namaPerwakilan || '-';
    document.getElementById('view_email_perwakilan').textContent = emailPerwakilan || '-';
    document.getElementById('view_telepon_perwakilan').textContent = teleponPerwakilan || '-';
    
    // Set timestamps
    document.getElementById('view_dibuat_pada').textContent = dibuat || '-';
    document.getElementById('view_diperbarui_pada').textContent = diubah || '-';
    
    new bootstrap.Modal(document.getElementById('viewPerusahaanModal')).show();
}

// ===== EDIT PERUSAHAAN =====
function editPerusahaan(id) {
    const button = event.target.closest('button');
    
    // Ambil data dari data attributes
    const namaPerusahaan = button.getAttribute('data-nama-perusahaan');
    const emailPerusahaan = button.getAttribute('data-email-perusahaan');
    const teleponPerusahaan = button.getAttribute('data-telepon-perusahaan');
    const namaPerwakilan = button.getAttribute('data-nama-perwakilan');
    const emailPerwakilan = button.getAttribute('data-email-perwakilan');
    const teleponPerwakilan = button.getAttribute('data-telepon-perwakilan');
    const alamat = button.getAttribute('data-alamat');
    const logo = button.getAttribute('data-logo');
    
    console.log('Edit Data:', {
        namaPerusahaan,
        emailPerusahaan,
        teleponPerusahaan,
        namaPerwakilan,
        emailPerwakilan,
        teleponPerwakilan
    });
    
    // Set data perusahaan (dari users)
    document.getElementById('edit_nama_perusahaan').value = namaPerusahaan || '';
    document.getElementById('edit_email_perusahaan').value = emailPerusahaan || '';
    document.getElementById('edit_telepon_perusahaan').value = teleponPerusahaan || '';
    document.getElementById('edit_alamat_perusahaan').value = alamat || '';
    
    // Set data perwakilan (dari perusahaan)
    document.getElementById('edit_nama_perwakilan').value = namaPerwakilan || '';
    document.getElementById('edit_email_perwakilan').value = emailPerwakilan || '';
    document.getElementById('edit_telepon_perwakilan').value = teleponPerwakilan || '';
    
    document.getElementById('editPerusahaanForm').action = `/master-data-perusahaan/${id}`;
    
    // Reset logo preview
    document.getElementById('newLogoPreview').style.display = 'none';
    document.getElementById('edit_logo').value = '';
    document.getElementById('clearEditLogo').style.display = 'none';
    document.getElementById('edit_password').value = '';
    
    // Show current logo
    const currentLogoPreview = document.getElementById('currentLogoPreview');
    if (logo && logo !== 'null' && logo !== '') {
        currentLogoPreview.innerHTML = `<img src="/storage/${logo}" alt="${namaPerusahaan}" class="preview-image">`;
    } else {
        currentLogoPreview.innerHTML = `<div class="company-avatar" style="width: 60px; height: 60px;"><i class='bx bx-buildings'></i></div>`;
    }
    
    new bootstrap.Modal(document.getElementById('editPerusahaanModal')).show();
}

// ===== LOGO VALIDATION & PREVIEW =====
function validateLogo(file) {
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    const maxSize = 2 * 1024 * 1024;
    
    if (!validTypes.includes(file.type)) {
        Swal.fire({
            icon: 'error',
            title: 'Format Tidak Valid',
            text: 'Gunakan format JPG, JPEG, atau PNG',
            confirmButtonColor: '#667eea'
        });
        return false;
    }
    
    if (file.size > maxSize) {
        Swal.fire({
            icon: 'error',
            title: 'Ukuran Terlalu Besar',
            text: 'Ukuran logo maksimal 2MB',
            confirmButtonColor: '#667eea'
        });
        return false;
    }
    
    return true;
}

function previewAddLogo(event) {
    const file = event.target.files[0];
    if (file) {
        if (!validateLogo(file)) {
            event.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('addLogoImg').src = e.target.result;
            document.getElementById('addLogoPreview').style.display = 'block';
            document.getElementById('clearAddLogo').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function previewNewLogo(event) {
    const file = event.target.files[0];
    if (file) {
        if (!validateLogo(file)) {
            event.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('newLogoImg').src = e.target.result;
            document.getElementById('newLogoPreview').style.display = 'block';
            document.getElementById('clearEditLogo').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function clearAddLogoPreview() {
    document.getElementById('add_logo').value = '';
    document.getElementById('addLogoPreview').style.display = 'none';
    document.getElementById('clearAddLogo').style.display = 'none';
}

function clearEditLogoPreview() {
    document.getElementById('edit_logo').value = '';
    document.getElementById('newLogoPreview').style.display = 'none';
    document.getElementById('clearEditLogo').style.display = 'none';
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

// ===== DELETE PERUSAHAAN =====
function deletePerusahaan(id, nama) {
    Swal.fire({
        title: 'Hapus Perusahaan?',
        html: `Anda akan menghapus <strong>${nama}</strong> beserta akses login-nya`,
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
            form.action = `/master-data-perusahaan/${id}`;
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
['addPerusahaanForm', 'editPerusahaanForm'].forEach(formId => {
    const form = document.getElementById(formId);
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const modalId = formId === 'addPerusahaanForm' ? 'addPerusahaanModal' : 'editPerusahaanModal';
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

// ===== INITIALIZATION =====
document.addEventListener('DOMContentLoaded', function() {
    loadColumnSettings();
    
    if (searchInput && searchInput.value.trim().length > 0) {
        clearSearchBtn.style.display = 'block';
    }
    
    updateRowNumbers();
});

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

const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
            updateRowNumbers();
        }
    });
});

document.querySelectorAll('.perusahaan-row').forEach(row => {
    observer.observe(row, { attributes: true, attributeFilter: ['style'] });
});
</script>
@endsection