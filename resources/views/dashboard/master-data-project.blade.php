@extends('layouts.master')
@section('title', 'Master Data Project')
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')

<div class="row">
    <div class="col-12">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-1">Master Data Project</h4>
                <p class="text-muted mb-0">Kelola data project perusahaan</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjekModal">
                <i class='bx bx-plus me-1'></i> Tambah Project
            </button>
        </div>
        
        <!-- Table Card -->
        <div class="card">
            <div class="card-header border-bottom">
                <form method="GET" action="{{ route('master-data-projek.index') }}" id="filterForm">
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
                            <select name="status" class="form-select fs-6 w-auto" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                            </select>
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
                            <th class="column-nama_projek sortable" data-column="nama_projek" style="min-width: 200px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>NAMA PROJECT</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-perusahaan sortable" data-column="perusahaan" style="min-width: 180px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>PERUSAHAAN</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-kategori sortable" data-column="kategori" style="min-width: 150px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>KATEGORI</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                           
                            <th class="column-tanggal_pesan sortable" data-column="tanggal_pesan" style="min-width: 130px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>TGL PESAN</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-status sortable" data-column="status" style="min-width: 130px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>STATUS</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-nominal_projek sortable" data-column="nominal_projek" style="min-width: 150px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>NOMINAL PROJECT</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                          
                            <th class="column-dokumen_perjanjian" style="min-width: 150px;">DOKUMEN</th>
                            <th class="column-tanggal_mulai sortable" data-column="tanggal_mulai" style="min-width: 130px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>TGL MULAI</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-tanggal_selesai sortable" data-column="tanggal_selesai" style="min-width: 130px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>TGL SELESAI</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-dibuat_oleh" style="min-width: 150px;">DIBUAT OLEH</th>
                            <th class="column-created_at sortable" data-column="created_at" style="min-width: 150px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>DIBUAT PADA</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="column-updated_at sortable" data-column="updated_at" style="min-width: 150px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>DIPERBARUI PADA</span>
                                    <i class='bx bx-sort sort-icon'></i>
                                </div>
                            </th>
                            <th class="text-end column-actions text-center" style="min-width: 120px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="projekTableBody">
                        @forelse($projeks as $index => $projek)
                        <tr class="projek-row" 
                            data-original-index="{{ $index + 1 }}"
                            data-status="{{ $projek->status }}" 
                            data-nama_projek="{{ strtolower($projek->nama_projek) }}" 
                            data-perusahaan="{{ strtolower($projek->perusahaan->nama_perusahaan ?? '') }}"
                            data-kategori="{{ strtolower($projek->kategori) }}"
                            data-nominal_projek="{{ $projek->nominal_projek }}"
                            data-sisa_tanggungan="{{ $projek->sisa_tanggungan }}"
                            data-tanggal_pesan="{{ $projek->tanggal_pesan }}"
                            data-tanggal_mulai="{{ $projek->tanggal_mulai }}"
                            data-tanggal_selesai="{{ $projek->tanggal_selesai }}"
                            data-created_at="{{ $projek->created_at }}"
                            data-updated_at="{{ $projek->updated_at }}">
                            <td class="column-no text-center">
                                <span class="row-number fw-semibold text-muted">{{ $index + 1 }}</span>
                            </td>
                          <td class="column-nama_projek">
                                <div class="fw-semibold text-dark">{{ $projek->nama_projek }}</div>
                                @if($projek->deskripsi)
                                <small class="text-muted">{{ Str::limit($projek->deskripsi, 50) }}</small>
                                @endif
                            </td>
                            <td class="column-perusahaan">
                                <span class="text-dark">{{ $projek->perusahaan->nama_perusahaan ?? '-' }}</span>
                            </td>
                            <td class="column-kategori">
                                @php
                                    $colors = ['text-primary','text-success','text-info','text-warning','text-danger','text-dark'];
                                    $index_color = crc32(strtolower($projek->kategori)) % count($colors);
                                    $kategoriColor = $colors[$index_color];
                                @endphp
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-circle me-2 {{ $kategoriColor }}' style="font-size: 8px;"></i>
                                    <span class="{{ $kategoriColor }} fw-semibold small">{{ $projek->kategori }}</span>
                                </div>
                            </td>
                            
                            <td class="column-tanggal_pesan">
                                <span class="text-dark small">{{ \Carbon\Carbon::parse($projek->tanggal_pesan)->format('d/m/Y') }}</span>
                            </td>
                             <td class="column-status">
                                @php
                                    $statusConfig = [
                                        'pending' => ['badge' => 'bg-label-warning', 'icon' => 'bx-time', 'label' => 'Pending'],
                                        'disetujui' => ['badge' => 'bg-label-info', 'icon' => 'bx-check', 'label' => 'Disetujui'],
                                        'berjalan' => ['badge' => 'bg-label-primary', 'icon' => 'bx-play', 'label' => 'Berjalan'],
                                        'selesai' => ['badge' => 'bg-label-success', 'icon' => 'bx-check-circle', 'label' => 'Selesai'],
                                        'batal' => ['badge' => 'bg-label-danger', 'icon' => 'bx-x', 'label' => 'Batal']
                                    ];
                                    $config = $statusConfig[$projek->status] ?? $statusConfig['pending'];
                                @endphp
                                <span class="badge rounded-pill {{ $config['badge'] }}" style="font-weight: 500; padding: 6px 12px;">
                                    <i class='bx {{ $config['icon'] }} me-1' style="font-size: 14px;"></i>
                                    {{ $config['label'] }}
                                </span>
                            </td>
                             <td class="column-nominal">
                                <div class="text-dark fw-semibold">Rp {{ number_format($projek->nominal_projek, 0, ',', '.') }}</div>
                                @if($projek->sisa_tanggungan > 0)
                                <small class="text-warning">Sisa: Rp {{ number_format($projek->sisa_tanggungan, 0, ',', '.') }}</small>
                                @endif
                            </td>
                            <td class="column-dokumen_perjanjian">
                                @if($projek->dokumen_perjanjian)
                                <a href="{{ asset('storage/' . $projek->dokumen_perjanjian) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class='bx bx-file me-1'></i> Lihat
                                </a>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="column-tanggal_mulai">
                                @if($projek->tanggal_mulai)
                                <span class="text-dark small">{{ \Carbon\Carbon::parse($projek->tanggal_mulai)->format('d/m/Y') }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="column-tanggal_selesai">
                                @if($projek->tanggal_selesai)
                                <span class="text-dark small">{{ \Carbon\Carbon::parse($projek->tanggal_selesai)->format('d/m/Y') }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="column-dibuat_oleh">
                                <span class="text-dark small">{{ $projek->pembuat->nama ?? '-' }}</span>
                            </td>
                            <td class="column-created_at">
                                <span class="text-dark small">{{ \Carbon\Carbon::parse($projek->created_at)->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="column-updated_at">
                                <span class="text-dark small">{{ \Carbon\Carbon::parse($projek->updated_at)->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="text-end column-actions text-center">
                                <div class="d-inline-flex gap-1">
                                    <button type="button" 
                                            class="btn btn-sm btn-text-secondary rounded-pill btn-icon" 
                                            onclick="editProjek({{ $projek->id_projek }})"
                                            data-projek='@json($projek)'
                                            title="Edit">
                                        <i class='bx bx-edit'></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-text-danger rounded-pill btn-icon" 
                                            onclick="deleteProjek({{ $projek->id_projek }}, '{{ $projek->nama_projek }}')"
                                            title="Delete">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="16" class="text-center py-5">
                                <i class='bx bx-folder-open' style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="text-muted mt-3 mb-0">Belum ada data project</p>
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
                            Showing 1 to {{ $projeks->count() }} of {{ $projeks->total() }} entries
                        </p>
                    </div>
                    <div class="col-md-6">
                        <nav>
                            <ul class="pagination pagination-sm justify-content-md-end mb-0 fs-6">
                                {{ $projeks->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="addProjekModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Project Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('master-data-projek.store') }}" method="POST" enctype="multipart/form-data" id="addProjekForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Project *</label>
                                <input type="text" name="nama_projek" class="form-control" required maxlength="150">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Perusahaan *</label>
                                <div class="perusahaan-autocomplete">
                                    <input type="text" 
                                        id="add_perusahaan_search" 
                                        class="form-control perusahaan-search" 
                                        placeholder="Ketik minimal 3 huruf untuk mencari..." 
                                        autocomplete="off">
                                    <input type="hidden" name="id_perusahaan" id="add_id_perusahaan" required>
                                    <div class="perusahaan-suggestions" id="add_perusahaan_suggestions"></div>
                                </div>
                                <small class="text-muted">Ketik minimal 3 karakter untuk melihat daftar perusahaan</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori *</label>
                                <input type="text" name="kategori" class="form-control" required maxlength="100" placeholder="e.g. Web Development, Mobile App">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-select" required>
                                    <option value="pending" selected>Pending</option>
                                    <option value="disetujui">Disetujui</option>
                                    <option value="berjalan">Berjalan</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="batal">Batal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nominal Project *</label>
                                <input type="number" name="nominal_projek" class="form-control" required min="0" step="0.01" placeholder="0.00">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sisa Tanggungan *</label>
                                <input type="number" name="sisa_tanggungan" class="form-control" required min="0" step="0.01" placeholder="0.00">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Pesan *</label>
                                <input type="date" name="tanggal_pesan" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control">
                            </div>
                            <div class="mb-3">
    <label class="form-label">Dokumen Perjanjian</label>
    <input type="file" name="dokumen_perjanjian" id="add_dokumen" class="form-control" accept=".pdf,.doc,.docx" style="display: none;">
    
    <!-- File upload display -->
    <div class="file-input-display" id="add_file_display" style="display: block;">
        <div class="d-grid">
            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('add_dokumen').click()">
                <i class='bx bx-upload me-2'></i>Pilih File
            </button>
        </div>
    </div>
    
    <!-- Selected file display -->
    <div class="file-selected-display" id="add_file_selected" style="display: none;">
        <div class="alert alert-info d-flex align-items-center justify-content-between mb-0">
            <div class="d-flex align-items-center">
                <i class='bx bx-file me-2' style="font-size: 20px;"></i>
                <span class="file-selected-name" id="add_file_name"></span>
            </div>
            <button type="button" class="btn btn-sm btn-text-danger" onclick="cancelAddFile()">
                <i class='bx bx-x' style="font-size: 20px;"></i>
            </button>
        </div>
    </div>
    
    <small class="text-muted d-block mt-1">Max 5MB (PDF, DOC, DOCX)</small>
</div>
                        </div>
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

<!-- Edit Projek Modal -->
<div class="modal fade" id="editProjekModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProjekForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Project *</label>
                                <input type="text" name="nama_projek" id="edit_nama_projek" class="form-control" required maxlength="150">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Perusahaan *</label>
                                <div class="perusahaan-autocomplete">
                                    <input type="text" 
                                        id="edit_perusahaan_search" 
                                        class="form-control perusahaan-search" 
                                        placeholder="Ketik minimal 3 huruf untuk mencari..." 
                                        autocomplete="off">
                                    <input type="hidden" name="id_perusahaan" id="edit_id_perusahaan" required>
                                    <div class="perusahaan-suggestions" id="edit_perusahaan_suggestions"></div>
                                </div>
                                <small class="text-muted">Ketik minimal 3 karakter untuk melihat daftar perusahaan</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori *</label>
                                <input type="text" name="kategori" id="edit_kategori" class="form-control" required maxlength="100">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status *</label>
                                <select name="status" id="edit_status" class="form-select" required>
                                    <option value="pending">Pending</option>
                                    <option value="disetujui">Disetujui</option>
                                    <option value="berjalan">Berjalan</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="batal">Batal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nominal Project *</label>
                                <input type="number" name="nominal_projek" id="edit_nominal_projek" class="form-control" required min="0" step="0.01">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sisa Tanggungan *</label>
                                <input type="number" name="sisa_tanggungan" id="edit_sisa_tanggungan" class="form-control" required min="0" step="0.01">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Pesan *</label>
                                <input type="date" name="tanggal_pesan" id="edit_tanggal_pesan" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" id="edit_tanggal_mulai" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" id="edit_tanggal_selesai" class="form-control">
                            </div>
<div class="mb-3">
    <label class="form-label fw-semibold mb-2">Dokumen Perjanjian</label>
    
    <!-- File Saat Ini -->
    <a href="#" id="edit_view_file" target="_blank" class="text-decoration-none" style="display: none;">
        <div id="edit_current_file_display" class="card border-0 shadow-sm p-3 mb-3 hover-shadow" style="cursor: pointer; transition: all 0.2s;">
            <div class="d-flex align-items-center">
                <div class="bg-light p-2 rounded me-3">
                    <i class='bx bx-file text-primary fs-4'></i>
                </div>
                <div>
                    <small class="text-muted d-block">File saat ini (klik untuk melihat)</small>
                    <span id="edit_current_file_name" class="fw-semibold text-dark"></span>
                </div>
            </div>
        </div>
    </a>
    
    <!-- Input File -->
    <input type="file" name="dokumen_perjanjian" id="edit_dokumen" class="form-control d-none" accept=".pdf,.doc,.docx">
    
    <!-- Tombol Pilih File -->
    <div id="edit_file_display">
        <button type="button" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center py-2"
                onclick="document.getElementById('edit_dokumen').click()">
            <i class='bx bx-upload me-2 fs-5'></i> Pilih File Baru
        </button>
    </div>
    
    <!-- File Terpilih -->
    <div id="edit_file_selected" class="card border-0 bg-light shadow-sm p-3 mt-3" style="display: none;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="bg-white p-2 rounded me-3">
                    <i class='bx bx-file text-info fs-4'></i>
                </div>
                <span id="edit_file_name" class="fw-semibold text-dark"></span>
            </div>
            <button type="button" class="btn btn-sm text-danger" onclick="cancelEditFile()" title="Batalkan file ini">
                <i class='bx bx-x fs-4'></i>
            </button>
        </div>
    </div>
    
    <small class="text-muted d-block mt-2">Ukuran maks. 5MB (format: PDF, DOC, DOCX)</small>
</div>
                           
                        </div>
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
                    <input class="form-check-input column-toggle" type="checkbox" value="nama_projek" id="col-nama_projek" checked disabled>
                    <label class="form-check-label" for="col-nama_projek">Nama Project</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="perusahaan" id="col-perusahaan">
                    <label class="form-check-label" for="col-perusahaan">Perusahaan</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="kategori" id="col-kategori" checked>
                    <label class="form-check-label" for="col-kategori">Kategori</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="deskripsi" id="col-deskripsi" checked>
                    <label class="form-check-label" for="col-deskripsi">Deskripsi</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="tanggal_pesan" id="col-tanggal_pesan">
                    <label class="form-check-label" for="col-tanggal_pesan">Tgl Pesan</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="status" id="col-status" checked>
                    <label class="form-check-label" for="col-status">Status</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="nominal_projek" id="col-nominal_projek" checked>
                    <label class="form-check-label" for="col-nominal_projek">Nominal Project</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="sisa_tanggungan" id="col-sisa_tanggungan" checked>
                    <label class="form-check-label" for="col-sisa_tanggungan">Sisa Tanggungan</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="dokumen_perjanjian" id="col-dokumen_perjanjian" checked>
                    <label class="form-check-label" for="col-dokumen_perjanjian">Dokumen</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="tanggal_mulai" id="col-tanggal_mulai">
                    <label class="form-check-label" for="col-tanggal_mulai">Tgl Mulai</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="tanggal_selesai" id="col-tanggal_selesai">
                    <label class="form-check-label" for="col-tanggal_selesai">Tgl Selesai</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="dibuat_oleh" id="col-dibuat_oleh">
                    <label class="form-check-label" for="col-dibuat_oleh">Dibuat Oleh</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="created_at" id="col-created_at">
                    <label class="form-check-label" for="col-created_at">Dibuat Pada</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input column-toggle" type="checkbox" value="updated_at" id="col-updated_at">
                    <label class="form-check-label" for="col-updated_at">Diperbarui Pada</label>
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

.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    transform: translateY(-2px);
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
.bg-label-success {
    background-color: rgba(113, 221, 55, 0.16) !important;
    color: #71dd37 !important;
}
.bg-label-warning {
    background-color: rgba(255, 171, 0, 0.16) !important;
    color: #ffab00 !important;
}
.bg-label-info {
    background-color: rgba(3, 195, 236, 0.16) !important;
    color: #03c3ec !important;
}
.bg-label-primary {
    background-color: rgba(105, 108, 255, 0.16) !important;
    color: #696cff !important;
}
.bg-label-danger {
    background-color: rgba(255, 62, 29, 0.16) !important;
    color: #ff3e1d !important;
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
}.perusahaan-autocomplete {
    position: relative;
}
.perusahaan-suggestions {
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
.perusahaan-suggestion-item {
    padding: 0.5rem 0.75rem;
    cursor: pointer;
    transition: background-color 0.2s;
}
.perusahaan-suggestion-item:hover {
    background-color: #f8f9fa;
}
.perusahaan-suggestion-item.active {
    background-color: #696cff;
    color: white;
}
.perusahaan-suggestions-empty {
    padding: 0.75rem;
    text-align: center;
    color: #6c757d;
    font-size: 0.875rem;
}
.perusahaan-suggestions-loading {
    padding: 0.75rem;
    text-align: center;
    color: #6c757d;
    font-size: 0.875rem;
}

/* File Upload Styling */
.file-upload-wrapper {
    border: 1px dashed #d9dee3;
    border-radius: 0.375rem;
    padding: 1rem;
    background-color: #fafafa;
    transition: all 0.3s ease;
}

.file-upload-wrapper:hover {
    border-color: #696cff;
    background-color: #f8f9fa;
}

.file-input-display, .file-selected-display {
    transition: all 0.3s ease;
}

.btn-choose-file, .btn-cancel-file, .btn-remove-current-file {
    transition: all 0.2s ease;
}

.btn-choose-file:hover, .btn-cancel-file:hover, .btn-remove-current-file:hover {
    transform: translateY(-1px);
}

/* Modal Responsive */
@media (max-width: 768px) {
    .file-upload-wrapper {
        padding: 0.75rem;
    }
    
    .d-flex.align-items-center.justify-content-between {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 0.5rem;
    }
    
    .d-flex.gap-1 {
        align-self: flex-end;
    }
}
</style><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Alert untuk Success
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#696cff'
        });
    @endif

    // Alert untuk Error
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#696cff'
        });
    @endif

    // Alert untuk Validation Errors
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#696cff'
        });
    @endif
</script>
<script>
// SweetAlert Notification
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

// Global Variables
let searchTimeout;
let currentDocumentUrl = '';
let currentDocumentName = '';
let perusahaanData = @json($perusahaans);

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeColumnSettings();
    initializeSearchFunctionality();
    initializeFileUploads();
    initializePerusahaanAutocomplete();
    initializeDateValidation();
});

// Perusahaan Autocomplete Functions
function initializePerusahaanAutocomplete() {
    // Setup untuk modal tambah
    setupAutocomplete('add_perusahaan_search', 'add_id_perusahaan', 'add_perusahaan_suggestions');
    
    // Setup untuk modal edit
    setupAutocomplete('edit_perusahaan_search', 'edit_id_perusahaan', 'edit_perusahaan_suggestions');
}

function setupAutocomplete(searchInputId, hiddenInputId, suggestionsId) {
    const searchInput = document.getElementById(searchInputId);
    const hiddenInput = document.getElementById(hiddenInputId);
    const suggestionsDiv = document.getElementById(suggestionsId);
    
    if (!searchInput || !hiddenInput || !suggestionsDiv) return;
    
    let selectedIndex = -1;
    let filteredData = [];
    
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        if (query.length < 3) {
            suggestionsDiv.style.display = 'none';
            hiddenInput.value = '';
            return;
        }
        
        // Filter perusahaan
        filteredData = perusahaanData.filter(p => 
            p.nama_perusahaan.toLowerCase().includes(query.toLowerCase())
        );
        
        displaySuggestions(filteredData, suggestionsDiv, searchInput, hiddenInput);
        selectedIndex = -1;
    });
    
    // Keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        const items = suggestionsDiv.querySelectorAll('.perusahaan-suggestion-item');
        
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

function displaySuggestions(data, suggestionsDiv, searchInput, hiddenInput) {
    if (data.length === 0) {
        suggestionsDiv.innerHTML = '<div class="perusahaan-suggestions-empty">Tidak ada perusahaan ditemukan</div>';
        suggestionsDiv.style.display = 'block';
        return;
    }
    
    let html = '';
    data.forEach(perusahaan => {
        html += `<div class="perusahaan-suggestion-item" 
                      data-id="${perusahaan.id_perusahaan}" 
                      data-name="${perusahaan.nama_perusahaan}">
                    ${perusahaan.nama_perusahaan}
                 </div>`;
    });
    
    suggestionsDiv.innerHTML = html;
    suggestionsDiv.style.display = 'block';
    
    // Add click handlers
    suggestionsDiv.querySelectorAll('.perusahaan-suggestion-item').forEach(item => {
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

// Column Management Functions
function initializeColumnSettings() {
    document.querySelectorAll('.column-toggle').forEach(toggle => {
        if (!toggle.disabled) {
            toggleColumn(toggle.value, toggle.checked);
        }
    });
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
    
    updateRowNumbers();
}

function resetColumns() {
    const defaultSettings = {
        'no': true,
        'nama_projek': true,
        'perusahaan': false,
        'kategori': true,
        'deskripsi': true,
        'tanggal_pesan': false,
        'status': true,
        'nominal_projek': true,
        'sisa_tanggungan': true,
        'dokumen_perjanjian': true,
        'tanggal_mulai': false,
        'tanggal_selesai': false,
        'dibuat_oleh': false,
        'created_at': false,
        'updated_at': false,
        'actions': true
    };
    
    document.querySelectorAll('.column-toggle').forEach(toggle => {
        if (!toggle.disabled) {
            toggle.checked = defaultSettings[toggle.value] || false;
            toggleColumn(toggle.value, toggle.checked);
        }
    });
}

// Event Listeners for Column Toggles
document.querySelectorAll('.column-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        toggleColumn(this.value, this.checked);
    });
});

// Row Number Management
function updateRowNumbers() {
    const visibleRows = Array.from(document.querySelectorAll('.projek-row')).filter(row => 
        !row.classList.contains('column-hidden') && row.style.display !== 'none'
    );
    visibleRows.forEach((row, index) => {
        const numberCell = row.querySelector('.row-number');
        if (numberCell) numberCell.textContent = index + 1;
    });
}

// Table Sorting Functions
function sortTable(column, direction) {
    const tbody = document.getElementById('projekTableBody');
    const rows = Array.from(tbody.querySelectorAll('.projek-row'));
    
    rows.sort((a, b) => {
        let aVal = a.dataset[column] || '';
        let bVal = b.dataset[column] || '';
        
        if (column === 'nominal_projek' || column === 'sisa_tanggungan') {
            aVal = parseFloat(aVal) || 0;
            bVal = parseFloat(bVal) || 0;
            return direction === 'asc' ? aVal - bVal : bVal - aVal;
        }
        
        if (column.includes('tanggal') || column === 'created_at' || column === 'updated_at') {
            aVal = new Date(aVal).getTime() || 0;
            bVal = new Date(bVal).getTime() || 0;
            return direction === 'asc' ? aVal - bVal : bVal - aVal;
        }
        
        return direction === 'asc' ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
    });
    
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }
    rows.forEach(row => tbody.appendChild(row));
    
    updateRowNumbers();
}

// Sortable Header Click Events
document.querySelectorAll('.sortable').forEach(header => {
    header.addEventListener('click', function() {
        const column = this.dataset.column;
        const isAsc = this.classList.contains('asc');
        const newDirection = isAsc ? 'desc' : 'asc';
        
        document.querySelectorAll('.sortable').forEach(h => {
            h.classList.remove('asc', 'desc');
        });
        
        this.classList.add(newDirection);
        
        sortTable(column, newDirection);
    });
});

// Search Functionality
function initializeSearchFunctionality() {
    const searchInput = document.getElementById('searchInput');
    const filterForm = document.getElementById('filterForm');
    
    if (searchInput && filterForm) {
        let lastCursorPosition = 0;
        
        searchInput.addEventListener('input', function(e) {
            lastCursorPosition = this.selectionStart;
            clearTimeout(searchTimeout);
            
            searchTimeout = setTimeout(function() {
                sessionStorage.setItem('searchValue', searchInput.value);
                sessionStorage.setItem('cursorPosition', lastCursorPosition);
                filterForm.submit();
            }, 500);
        });
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                sessionStorage.removeItem('searchValue');
                sessionStorage.removeItem('cursorPosition');
                filterForm.submit();
            }
        });
        
        const savedSearch = sessionStorage.getItem('searchValue');
        const savedCursor = sessionStorage.getItem('cursorPosition');
        
        if (savedSearch && searchInput.value === savedSearch && savedCursor) {
            searchInput.focus();
            searchInput.setSelectionRange(savedCursor, savedCursor);
        }
    }
}

// File Upload Management
function initializeFileUploads() {
    // Modal Tambah
    const addFileInput = document.getElementById('add_dokumen');
    if (addFileInput) {
        addFileInput.addEventListener('change', function() {
            handleAddFileSelection(this);
        });
    }
    
    // Modal Edit
    const editFileInput = document.getElementById('edit_dokumen');
    if (editFileInput) {
        editFileInput.addEventListener('change', function() {
            handleEditFileSelection(this);
        });
    }
}

function handleAddFileSelection(fileInput) {
    const file = fileInput.files[0];
    if (!file) return;
    
    if (!validateFile(file, file.name)) {
        fileInput.value = '';
        return;
    }
    
    const fileName = file.name;
    const fileSize = (file.size / (1024 * 1024)).toFixed(2);
    
    document.getElementById('add_file_name').textContent = `${fileName} (${fileSize} MB)`;
    document.getElementById('add_file_display').style.display = 'none';
    document.getElementById('add_file_selected').style.display = 'block';
}


function cancelAddFile() {
    const fileInput = document.getElementById('add_dokumen');
    fileInput.value = '';
    document.getElementById('add_file_display').style.display = 'block';
    document.getElementById('add_file_selected').style.display = 'none';
}



function handleEditFileSelection(fileInput) {
    const file = fileInput.files[0];
    if (!file) return;
    
    if (!validateFile(file, file.name)) {
        fileInput.value = '';
        return;
    }
    
    const fileName = file.name;
    const fileSize = (file.size / (1024 * 1024)).toFixed(2);
    
    document.getElementById('edit_file_name').textContent = `${fileName} (${fileSize} MB)`;
    document.getElementById('edit_file_display').style.display = 'none';
    document.getElementById('edit_file_selected').style.display = 'block';
}

function cancelEditFile() {
    const fileInput = document.getElementById('edit_dokumen');
    fileInput.value = '';
    document.getElementById('edit_file_display').style.display = 'block';
    document.getElementById('edit_file_selected').style.display = 'none';
}

function validateFile(file, fileName) {
    if (file.size > 5 * 1024 * 1024) {
        Swal.fire({
            icon: 'error',
            title: 'File Terlalu Besar',
            text: 'Ukuran dokumen maksimal 5MB',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        return false;
    }
    
    const allowedExtensions = ['.pdf', '.doc', '.docx'];
    const fileExtension = '.' + fileName.split('.').pop().toLowerCase();
    if (!allowedExtensions.includes(fileExtension)) {
        Swal.fire({
            icon: 'error',
            title: 'Format File Tidak Didukung',
            text: 'Hanya file PDF, DOC, dan DOCX yang diizinkan',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        return false;
    }
    
    return true;
}

function resetFileInput(fileInput, fileDisplay, fileSelectedDisplay, fileNameSpan) {
    if (fileInput && fileDisplay) {
        fileInput.value = '';
        fileDisplay.style.display = 'flex';
        if (fileSelectedDisplay) fileSelectedDisplay.style.display = 'none';
        if (fileNameSpan) fileNameSpan.textContent = 'Belum ada file dipilih';
    }
}

// Date Validation Functions
function showDateValidationError(message) {
    Swal.fire({
        icon: 'warning',
        title: 'Validasi Tanggal',
        text: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
}

function validateAndSetMinDate(tanggalMulaiInput, tanggalSelesaiInput) {
    if (tanggalMulaiInput.value) {
        const minDate = new Date(tanggalMulaiInput.value);
        minDate.setDate(minDate.getDate() + 1);
        
        const minDateString = minDate.toISOString().split('T')[0];
        tanggalSelesaiInput.min = minDateString;
        tanggalSelesaiInput.disabled = false;
        
        if (tanggalSelesaiInput.value && tanggalSelesaiInput.value <= tanggalMulaiInput.value) {
            tanggalSelesaiInput.value = '';
            showDateValidationError('Tanggal selesai harus setelah tanggal mulai');
        }
    } else {
        tanggalSelesaiInput.disabled = true;
        tanggalSelesaiInput.min = '';
    }
}

function validateDateRange(tanggalMulaiInput, tanggalSelesaiInput) {
    if (tanggalMulaiInput.value && tanggalSelesaiInput.value) {
        if (tanggalSelesaiInput.value <= tanggalMulaiInput.value) {
            showDateValidationError('Tanggal selesai harus setelah tanggal mulai');
            tanggalSelesaiInput.value = '';
            tanggalSelesaiInput.focus();
        }
    }
}

function initializeDateValidation() {
    const addTanggalMulai = document.querySelector('#addProjekModal input[name="tanggal_mulai"]');
    const addTanggalSelesai = document.querySelector('#addProjekModal input[name="tanggal_selesai"]');
    
    if (addTanggalMulai && addTanggalSelesai) {
        addTanggalMulai.addEventListener('change', function() {
            validateAndSetMinDate(this, addTanggalSelesai);
        });
        
        addTanggalSelesai.addEventListener('change', function() {
            validateDateRange(addTanggalMulai, this);
        });
    }
    
    const editTanggalMulai = document.querySelector('#editProjekModal input[name="tanggal_mulai"]');
    const editTanggalSelesai = document.querySelector('#editProjekModal input[name="tanggal_selesai"]');
    
    if (editTanggalMulai && editTanggalSelesai) {
        editTanggalMulai.addEventListener('change', function() {
            validateAndSetMinDate(this, editTanggalSelesai);
        });
        
        editTanggalSelesai.addEventListener('change', function() {
            validateDateRange(editTanggalMulai, this);
        });
    }
}

// Project CRUD Functions
function editProjek(projekId) {
    const button = event.target.closest('[data-projek]');
    if (!button) return;
    
    const projek = JSON.parse(button.dataset.projek);
    
    document.getElementById('edit_nama_projek').value = projek.nama_projek;
    document.getElementById('edit_kategori').value = projek.kategori;
    document.getElementById('edit_deskripsi').value = projek.deskripsi || '';
    document.getElementById('edit_status').value = projek.status;
    document.getElementById('edit_nominal_projek').value = projek.nominal_projek;
    document.getElementById('edit_sisa_tanggungan').value = projek.sisa_tanggungan;
    document.getElementById('edit_tanggal_pesan').value = projek.tanggal_pesan;
    document.getElementById('edit_tanggal_mulai').value = projek.tanggal_mulai || '';
    document.getElementById('edit_tanggal_selesai').value = projek.tanggal_selesai || '';
    
    document.getElementById('editProjekForm').action = `/master-data-projek/${projek.id_projek}`;
    
    // Set perusahaan value
const perusahaan = perusahaanData.find(p => p.id_perusahaan == projek.id_perusahaan);
if (perusahaan) {
    document.getElementById('edit_perusahaan_search').value = perusahaan.nama_perusahaan;
    document.getElementById('edit_id_perusahaan').value = projek.id_perusahaan;
}
    const currentDokumenDisplay = document.getElementById('currentDokumenDisplay');
    const noDocumentDisplay = document.getElementById('noDocumentDisplay');
    const fileUploadSection = document.querySelector('#editProjekModal .file-upload-section');
    const keepCurrentFileCheckbox = document.getElementById('keep_current_file');
    const removeCurrentDocumentInput = document.getElementById('remove_current_document');
    
    const editFileInput = document.getElementById('edit_dokumen');
    const editFileDisplay = document.querySelector('#editProjekModal .file-input-display');
    const editFileSelectedDisplay = document.querySelector('#editProjekModal .file-selected-display');
    const editFileNameSpan = document.querySelector('#editProjekModal .file-name');
    
    resetFileInput(editFileInput, editFileDisplay, editFileSelectedDisplay, editFileNameSpan);
    

document.getElementById('edit_file_display').style.display = 'block';
document.getElementById('edit_file_selected').style.display = 'none';
document.getElementById('edit_dokumen').value = '';

if (projek.dokumen_perjanjian) {
    const documentUrl = `/storage/${projek.dokumen_perjanjian}`;
    const documentName = projek.dokumen_perjanjian.split('/').pop();
    
    document.getElementById('edit_current_file_name').textContent = documentName;
    document.getElementById('edit_view_file').href = documentUrl;
    document.getElementById('edit_view_file').style.display = 'block';
} else {
    document.getElementById('edit_view_file').style.display = 'none';
}
    
    const editTanggalMulai = document.querySelector('#editProjekModal input[name="tanggal_mulai"]');
    const editTanggalSelesai = document.querySelector('#editProjekModal input[name="tanggal_selesai"]');
    
    if (editTanggalMulai && editTanggalSelesai) {
        if (editTanggalMulai.value) {
            const minDate = new Date(editTanggalMulai.value);
            minDate.setDate(minDate.getDate() + 1);
            editTanggalSelesai.min = minDate.toISOString().split('T')[0];
            editTanggalSelesai.disabled = false;
        } else {
            editTanggalSelesai.disabled = true;
        }
    }
    
    const editModal = new bootstrap.Modal(document.getElementById('editProjekModal'));
    editModal.show();
}

// Modal Add Reset
document.getElementById('addProjekModal')?.addEventListener('hidden.bs.modal', function() {
    const form = document.getElementById('addProjekForm');
    if (form) form.reset();
    
    // Reset autocomplete
    document.getElementById('add_perusahaan_search').value = '';
    document.getElementById('add_id_perusahaan').value = '';
    document.getElementById('add_perusahaan_suggestions').style.display = 'none';
    
    // Reset file upload
    cancelAddFile();
    
    const addTanggalSelesai = document.querySelector('#addProjekModal input[name="tanggal_selesai"]');
    if (addTanggalSelesai) {
        addTanggalSelesai.disabled = true;
        addTanggalSelesai.min = '';
    }
});

// Modal Edit Reset
document.getElementById('editProjekModal')?.addEventListener('hidden.bs.modal', function() {
    // Reset autocomplete
    document.getElementById('edit_perusahaan_search').value = '';
    document.getElementById('edit_id_perusahaan').value = '';
    document.getElementById('edit_perusahaan_suggestions').style.display = 'none';
    
    // Reset file upload
    cancelEditFile();
    document.getElementById('edit_view_file').style.display = 'none';
    
    const editTanggalSelesai = document.querySelector('#editProjekModal input[name="tanggal_selesai"]');
    if (editTanggalSelesai) {
        editTanggalSelesai.disabled = true;
        editTanggalSelesai.min = '';
    }
});

function deleteProjek(projekId, projekName) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        html: `Anda akan menghapus project <strong>${projekName}</strong>`,
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
            form.action = `/master-data-projek/${projekId}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
// Export functions to global scope
window.editProjek = editProjek;
window.deleteProjek = deleteProjek;
window.resetColumns = resetColumns;
</script>
@endsection