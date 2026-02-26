@extends('layouts.master')
@section('title', 'Master Data Perusahaan')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/master-data.css') }}">
@endpush
@section('content')

<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
    <div>
        <h4>Master Data Perusahaan</h4>
        <p>Kelola data perusahaan mitra</p>
    </div>
    <button class="btn-main" data-bs-toggle="modal" data-bs-target="#addPerusahaanModal">
        <i class='bx bx-plus'></i> Tambah Perusahaan
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
                <div class="search-wrap">
                    <i class='bx bx-search ico'></i>
                    <input type="text" id="searchInput" class="ctrl" placeholder="Cari perusahaan..." autocomplete="off">
                    <button type="button" id="clearSearch" class="search-clear"><i class='bx bx-x'></i></button>
                </div>
                <button type="button" class="btn-ghost" data-bs-toggle="modal" data-bs-target="#columnSettingsModal">
                    <i class='bx bx-columns'></i>
                </button>
            </div>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="column-no" style="width:52px;text-align:center;">No</th>
                    <th class="column-nama sortable" data-column="nama_perusahaan" style="min-width:260px;">Perusahaan & Perwakilan <span class="sort-icon"></span></th>
                    <th class="column-email" style="min-width:210px;">Email</th>
                    <th class="column-telepon" style="min-width:160px;">Telepon</th>
                    <th class="column-alamat" style="min-width:190px;">Alamat</th>
                    <th class="column-jumlah_projek" style="min-width:120px;text-align:center;">Projek</th>
                    <th class="column-dibuat sortable" data-column="dibuat_pada" style="min-width:130px;">Dibuat <span class="sort-icon"></span></th>
                    <th class="column-diubah sortable" data-column="diperbarui_pada" style="min-width:130px;">Diubah <span class="sort-icon"></span></th>
                    <th class="column-actions" style="width:110px;text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody id="perusahaanTableBody">
                @forelse($perusahaans as $index => $item)
                @php
                    $namaPerusahaan    = $item->nama_perusahaan    ?? ($item->userPerusahaan?->nama ?? '-');
                    $emailPerusahaan   = $item->email_perusahaan   ?? ($item->userPerusahaan?->email ?? '-');
                    $teleponPerusahaan = $item->telepon_perusahaan ?? ($item->userPerusahaan?->no_hp ?? null);
                    $jumlahProjek      = $item->jumlah_projek ?? 0;
                    $logoRaw           = $item->getRawOriginal('logo_perusahaan') ?? $item->logo_perusahaan;
                    $itemData = [
                        'id_perusahaan'      => $item->id_perusahaan,
                        'id_user_perusahaan' => $item->id_user_perusahaan,
                        'nama_perusahaan'    => $namaPerusahaan,
                        'email_perusahaan'   => $emailPerusahaan,
                        'telepon_perusahaan' => $teleponPerusahaan,
                        'nama_perwakilan'    => $item->nama_perwakilan,
                        'email_perwakilan'   => $item->email_perwakilan,
                        'telepon_perwakilan' => $item->telepon_perwakilan,
                        'alamat_perusahaan'  => $item->alamat_perusahaan,
                        'logo_perusahaan'    => $logoRaw,
                        'dibuat_pada'        => $item->dibuat_pada,
                        'diperbarui_pada'    => $item->diperbarui_pada,
                        'jumlah_projek'      => $jumlahProjek,
                        'status_akun'        => $item->userPerusahaan?->status ? 'aktif' : ($item->userPerusahaan ? 'nonaktif' : null),
                    ];
                @endphp
                <tr class="perusahaan-row"
                    data-nama_perusahaan="{{ strtolower($namaPerusahaan) }}"
                    data-email_perusahaan="{{ strtolower($emailPerusahaan) }}"
                    data-nama_perwakilan="{{ strtolower($item->nama_perwakilan ?? '') }}"
                    data-dibuat_pada="{{ $item->dibuat_pada }}"
                    data-diperbarui_pada="{{ $item->diperbarui_pada }}"
                    data-item='@json($itemData)'>

                    <td class="column-no" style="text-align:center;"><span class="row-no">{{ $perusahaans->firstItem() + $index }}</span></td>

                    <td class="column-nama">
                        <div style="display:flex;align-items:center;gap:10px;">
                            @if($logoRaw)
                                <img src="{{ asset('storage/' . $logoRaw) }}" alt="{{ $namaPerusahaan }}" class="company-logo"
                                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                <div class="company-av" style="display:none;"><i class='bx bx-buildings'></i></div>
                            @else
                                <div class="company-av"><i class='bx bx-buildings'></i></div>
                            @endif
                            <div>
                                <div class="company-name">{{ $namaPerusahaan }}</div>
                                <div class="company-sub"><i class='bx bxs-user' style="font-size:11px;"></i> {{ $item->nama_perwakilan ?? '-' }}</div>
                                @if($item->userPerusahaan)
                                    @if($item->userPerusahaan->status)
                                        <span class="acc-badge acc-active"><i class='bx bx-check-circle'></i> Aktif</span>
                                    @else
                                        <span class="acc-badge acc-inactive"><i class='bx bx-x-circle'></i> Non-Aktif</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </td>

                    <td class="column-email">
    <div class="dual-cell">
        <div class="dual-main" title="Email Perusahaan (akun login)">
            <i class='bx bxs-buildings'></i>
            <span>{{ $emailPerusahaan }}</span>
        </div>
        <div class="dual-sec" title="Email Perwakilan (PIC)">
            <i class='bx bxs-user-circle'></i>
            <span>{{ $item->email_perwakilan ?? '-' }}</span>
        </div>
    </div>
</td>

                  <td class="column-telepon">
    <div class="dual-cell">
        <div class="dual-main {{ !$teleponPerusahaan ? 'dual-sec' : '' }}" title="Telepon Perusahaan">
            <i class='bx {{ $teleponPerusahaan ? "bxs-phone-call" : "bx-phone-call" }}'></i>
            <span>{{ $teleponPerusahaan ?? '-' }}</span>
        </div>
        <div class="dual-sec" title="Telepon Perwakilan">
            <i class='bx {{ $item->telepon_perwakilan ? "bxs-mobile-vibration" : "bx-mobile" }}'></i>
            <span>{{ $item->telepon_perwakilan ?? '-' }}</span>
        </div>
    </div>
</td>

                    <td class="column-alamat">
                        <span class="addr-text">{{ $item->alamat_perusahaan ?? '-' }}</span>
                    </td>

                    <td class="column-jumlah_projek" style="text-align:center;">
                        @if($jumlahProjek > 0)
                            <span class="proj-pill"><i class='bx bx-folder'></i> {{ $jumlahProjek }} projek</span>
                        @else
                            <span class="proj-pill empty"><i class='bx bx-folder'></i> 0 projek</span>
                        @endif
                    </td>

                    <td class="column-dibuat">
                        <span class="date-val">{{ $item->dibuat_pada ? \Carbon\Carbon::parse($item->dibuat_pada)->format('d/m/Y H:i') : '—' }}</span>
                    </td>

                    <td class="column-diubah">
                        <span class="date-val">{{ $item->diperbarui_pada ? \Carbon\Carbon::parse($item->diperbarui_pada)->format('d/m/Y H:i') : '—' }}</span>
                    </td>

                    <td class="column-actions" style="text-align:right;">
                        <div class="act-group">
                            <button type="button" class="act-btn view" onclick="viewPerusahaan(this)"
                                    data-item='@json($itemData)' title="Lihat Detail"><i class='bx bx-show'></i></button>
                            <button type="button" class="act-btn edit" onclick="editPerusahaan(this)"
                                    data-item='@json($itemData)' title="Edit"><i class='bx bx-edit'></i></button>
                            <button type="button" class="act-btn delete"
                                    onclick="deletePerusahaan({{ $item->id_perusahaan }}, '{{ addslashes($namaPerusahaan) }}')"
                                    title="Hapus"><i class='bx bx-trash'></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9">
                    <div class="empty-state">
                        <i class='bx bx-buildings'></i>
                        <p>Belum ada data perusahaan.<br>Tambahkan perusahaan pertama Anda.</p>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <span class="footer-info">Menampilkan <strong id="showingStart">{{ $perusahaans->firstItem() ?? 0 }}</strong>–<strong id="showingEnd">{{ $perusahaans->lastItem() ?? 0 }}</strong> dari <strong id="totalEntries">{{ $perusahaans->total() }}</strong> data</span>
        <nav><ul class="page-list" id="paginationControls"></ul></nav>
    </div>
</div>

{{-- ══════════════════════════════════════
     MODAL VIEW
══════════════════════════════════════ --}}
<div class="modal fade" id="viewPerusahaanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-hdr">
                <div class="modal-hdr-title">
                    <div class="hdr-icon"><i class='bx bx-buildings'></i></div>
                    Detail Perusahaan
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-bdy">
                <div class="view-logo-container" id="view_logo_container"></div>

                <!-- Akun Login -->
                <div class="sec-row">Informasi Perusahaan <small style="font-weight:400;text-transform:none;letter-spacing:0;">(Akun Login)</small></div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="drow"><span class="dlabel">Nama Perusahaan</span><span class="dval" id="view_nama_perusahaan"></span></div>
                    </div>
                    <div class="col-md-6">
                        <div class="drow"><span class="dlabel">Email Login</span><span class="dval" style="color:var(--p1);font-weight:500;" id="view_email_perusahaan"></span></div>
                    </div>
                    <div class="col-md-6">
                        <div class="drow"><span class="dlabel">Telepon</span><span class="dval" id="view_telepon_perusahaan"></span></div>
                    </div>
                    <div class="col-md-6">
                        <div class="drow"><span class="dlabel">Alamat</span><span class="dval" id="view_alamat_perusahaan"></span></div>
                    </div>
                </div>

                <!-- Perwakilan -->
                <div class="sec-row green">Informasi Perwakilan (PIC)</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="drow"><span class="dlabel">Nama</span><span class="dval" id="view_nama_perwakilan"></span></div>
                    </div>
                    <div class="col-md-6">
                        <div class="drow"><span class="dlabel">Email</span><span class="dval" id="view_email_perwakilan"></span></div>
                    </div>
                    <div class="col-md-6">
                        <div class="drow"><span class="dlabel">Telepon</span><span class="dval" id="view_telepon_perwakilan"></span></div>
                    </div>
                </div>

                <!-- Statistik & Waktu -->
                <div class="sec-row teal">Statistik & Waktu</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="drow"><span class="dlabel">Jumlah Projek</span><span class="dval" id="view_jumlah_projek"></span></div>
                    </div>
                    <div class="col-md-4">
                        <div class="drow"><span class="dlabel">Dibuat</span><span class="dval" style="color:var(--ink-500);font-weight:400;" id="view_dibuat_pada"></span></div>
                    </div>
                    <div class="col-md-4">
                        <div class="drow"><span class="dlabel">Diperbarui</span><span class="dval" style="color:var(--ink-500);font-weight:400;" id="view_diperbarui_pada"></span></div>
                    </div>
                </div>
            </div>
            <div class="modal-ftr">
                <button type="button" class="btn-outline" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn-main" id="view_edit_btn"><i class='bx bx-edit'></i> Edit</button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     MODAL ADD
══════════════════════════════════════ --}}
<div class="modal fade" id="addPerusahaanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-hdr">
                <div class="modal-hdr-title">
                    <div class="hdr-icon"><i class='bx bx-plus'></i></div>
                    Tambah Perusahaan Baru
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('master-data-perusahaan.store') }}" method="POST" enctype="multipart/form-data" id="addPerusahaanForm">
                @csrf
                <div class="modal-bdy">
                    <div style="background:var(--p-light);border:1px solid var(--p-soft);border-radius:var(--radius-sm);padding:10px 14px;font-size:12px;color:var(--p2);margin-bottom:16px;">
                        <i class='bx bx-info-circle'></i>
                        Data perusahaan (nama, email, telepon) akan menjadi <strong>akun login</strong> perusahaan secara otomatis.
                    </div>

                    <div class="dsec" style="margin-bottom:14px;"><i class='bx bx-buildings me-1'></i>Data Perusahaan <span style="font-weight:400;text-transform:none;letter-spacing:0;">(Akun Login)</span></div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="flabel">Nama Perusahaan <span style="color:#DC2626;">*</span></label>
                            <input type="text" name="nama_perusahaan" class="fctrl" required maxlength="100" placeholder="PT ABC Indonesia">
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Email Perusahaan <span style="color:#DC2626;">*</span> <span class="fhint" style="margin:0;display:inline;">(untuk login)</span></label>
                            <input type="email" name="email_perusahaan" class="fctrl" required maxlength="100" placeholder="info@abc.co.id">
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Telepon Perusahaan</label>
                            <input type="text" name="telepon_perusahaan" class="fctrl" maxlength="20" placeholder="021-1234567">
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Password <span style="color:#DC2626;">*</span> <span class="fhint" style="margin:0;display:inline;">(untuk login)</span></label>
                            <div class="pw-wrap">
                                <input type="password" name="password_perusahaan" id="add_password" class="fctrl" required minlength="8" placeholder="Min. 8 karakter">
                                <button class="btn-pw" type="button" onclick="togglePw('add_password','add_pw_icon')"><i class='bx bx-hide' id="add_pw_icon"></i></button>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="flabel">Alamat Perusahaan <span style="color:#DC2626;">*</span></label>
                            <textarea name="alamat_perusahaan" class="fctrl" rows="2" required placeholder="Jl. Sudirman No. 123, Jakarta"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="flabel">Logo Perusahaan</label>
                            <input type="file" name="logo_perusahaan" id="add_logo" class="fctrl" accept="image/jpeg,image/png,image/jpg"
                                   onchange="previewLogo(event,'addLogoImg','addLogoPreview')">
                            <div class="fhint">Format: JPG, JPEG, PNG — Maks. 2MB</div>
                            <div class="photo-preview" id="addLogoPreview">
                                <img id="addLogoImg" src="" alt="Preview">
                                <button type="button" class="photo-clear" onclick="clearLogo('add_logo','addLogoImg','addLogoPreview')">
                                    <i class='bx bx-x'></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr class="divider">
                    <div class="dsec" style="margin-bottom:14px;"><i class='bx bx-user me-1'></i>Data Perwakilan (PIC)</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="flabel">Nama Perwakilan <span style="color:#DC2626;">*</span></label>
                            <input type="text" name="nama_perwakilan" class="fctrl" required maxlength="100" placeholder="Ahmad Wijaya">
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Email Perwakilan <span style="color:#DC2626;">*</span></label>
                            <input type="email" name="email_perwakilan" class="fctrl" required maxlength="100" placeholder="ahmad@abc.co.id">
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Telepon Perwakilan</label>
                            <input type="text" name="telepon_perwakilan" class="fctrl" maxlength="20" placeholder="08123456789">
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

{{-- ══════════════════════════════════════
     MODAL EDIT
══════════════════════════════════════ --}}
<div class="modal fade" id="editPerusahaanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-hdr">
                <div class="modal-hdr-title">
                    <div class="hdr-icon"><i class='bx bx-edit'></i></div>
                    Edit Perusahaan
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPerusahaanForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-bdy">
                    <div style="background:rgba(14,116,144,.07);border:1px solid rgba(14,116,144,.2);border-radius:var(--radius-sm);padding:10px 14px;font-size:12px;color:#0e7490;margin-bottom:16px;">
                        <i class='bx bx-sync'></i>
                        Perubahan nama, email, dan telepon perusahaan akan <strong>otomatis tersinkronisasi</strong> ke akun login perusahaan.
                    </div>

                    <div class="dsec" style="margin-bottom:14px;"><i class='bx bx-buildings me-1'></i>Data Perusahaan <span style="font-weight:400;text-transform:none;letter-spacing:0;">(Akun Login)</span></div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="flabel">Nama Perusahaan <span style="color:#DC2626;">*</span></label>
                            <input type="text" name="nama_perusahaan" id="edit_nama_perusahaan" class="fctrl" required maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Email Perusahaan <span style="color:#DC2626;">*</span> <span class="fhint" style="margin:0;display:inline;">(akun login)</span></label>
                            <input type="email" name="email_perusahaan" id="edit_email_perusahaan" class="fctrl" required maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Telepon Perusahaan</label>
                            <input type="text" name="telepon_perusahaan" id="edit_telepon_perusahaan" class="fctrl" maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Password <span class="fhint" style="margin:0;display:inline;">(kosongkan jika tidak diubah)</span></label>
                            <div class="pw-wrap">
                                <input type="password" name="password_perusahaan" id="edit_password" class="fctrl" minlength="8" placeholder="Isi jika ingin mengubah">
                                <button class="btn-pw" type="button" onclick="togglePw('edit_password','edit_pw_icon')"><i class='bx bx-hide' id="edit_pw_icon"></i></button>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="flabel">Alamat Perusahaan <span style="color:#DC2626;">*</span></label>
                            <textarea name="alamat_perusahaan" id="edit_alamat_perusahaan" class="fctrl" rows="2" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="flabel">Logo Saat Ini</label>
                            <div class="photo-current" id="currentLogoPreview"></div>
                            <label class="flabel">Ganti Logo</label>
                            <input type="file" name="logo_perusahaan" id="edit_logo" class="fctrl" accept="image/jpeg,image/png,image/jpg"
                                   onchange="previewLogo(event,'editLogoImg','editLogoPreview')">
                            <div class="fhint">Format: JPG, JPEG, PNG — Maks. 2MB</div>
                            <div class="photo-preview" id="editLogoPreview">
                                <img id="editLogoImg" src="" alt="Preview">
                                <button type="button" class="photo-clear" onclick="clearLogo('edit_logo','editLogoImg','editLogoPreview')">
                                    <i class='bx bx-x'></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr class="divider">
                    <div class="dsec" style="margin-bottom:14px;"><i class='bx bx-user me-1'></i>Data Perwakilan (PIC)</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="flabel">Nama Perwakilan <span style="color:#DC2626;">*</span></label>
                            <input type="text" name="nama_perwakilan" id="edit_nama_perwakilan" class="fctrl" required maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Email Perwakilan <span style="color:#DC2626;">*</span></label>
                            <input type="email" name="email_perwakilan" id="edit_email_perwakilan" class="fctrl" required maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Telepon Perwakilan</label>
                            <input type="text" name="telepon_perwakilan" id="edit_telepon_perwakilan" class="fctrl" maxlength="20">
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

{{-- ══════════════════════════════════════
     MODAL COLUMN SETTINGS
══════════════════════════════════════ --}}
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
                    ['no','No Urut',true,true],
                    ['nama','Perusahaan & Perwakilan',true,true],
                    ['email','Email',true,false],
                    ['telepon','Telepon',true,false],
                    ['alamat','Alamat',true,false],
                    ['jumlah_projek','Jumlah Projek',true,false],
                    ['dibuat','Dibuat',false,false],
                    ['diubah','Diubah',false,false],
                    ['actions','Aksi',true,true],
                ] as [$val, $label, $checked, $disabled])
                <div class="col-check-item">
                    <input class="column-toggle" type="checkbox" value="{{ $val }}"
                           id="col-{{ $val }}" {{ $checked?'checked':'' }} {{ $disabled?'disabled':'' }}>
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
const STORAGE_KEY = 'perusahaan_column_settings';
let allRows = [], filteredRows = [];
let currentPage = 1, perPage = 10, currentSearch = '';
let searchTimeout = null, currentViewBtn = null;

document.addEventListener('DOMContentLoaded', function () {
    initializeData();
    loadColumnSettings();
    initializeEventListeners();
    renderTable();
});

function initializeData() {
    allRows = Array.from(document.querySelectorAll('#perusahaanTableBody .perusahaan-row')).map(row => ({
        element:         row,
        nama_perusahaan: row.dataset.nama_perusahaan,
        email_perusahaan:row.dataset.email_perusahaan,
        nama_perwakilan: row.dataset.nama_perwakilan,
        dibuat_pada:     row.dataset.dibuat_pada,
        diperbarui_pada: row.dataset.diperbarui_pada,
    }));
    filteredRows = [...allRows];
}

function initializeEventListeners() {
    const pp = document.getElementById('perPageSelect');
    pp.value = perPage;
    pp.addEventListener('change', () => { perPage = parseInt(pp.value); currentPage = 1; renderTable(); });

    const si = document.getElementById('searchInput'), sc = document.getElementById('clearSearch');
    si.addEventListener('input', function () {
        sc.style.display = this.value.trim() ? 'block' : 'none';
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => { currentSearch = this.value.trim().toLowerCase(); applyFilters(); }, 300);
    });
    sc.addEventListener('click', () => { si.value = ''; sc.style.display = 'none'; currentSearch = ''; applyFilters(); });

    document.querySelectorAll('.column-toggle').forEach(t =>
        t.addEventListener('change', function () { toggleColumn(this.value, this.checked); })
    );

    document.querySelectorAll('.sortable').forEach(h => {
        h.addEventListener('click', function () {
            const col = this.dataset.column, isAsc = this.classList.contains('asc');
            document.querySelectorAll('.sortable').forEach(x => x.classList.remove('asc','desc'));
            this.classList.add(isAsc ? 'desc' : 'asc');
            sortRows(col, isAsc ? 'desc' : 'asc');
        });
    });

    document.getElementById('columnSettingsModal')?.addEventListener('show.bs.modal', function () {
        document.querySelectorAll('.column-toggle:not([disabled])').forEach(t => {
            const el = document.querySelector(`.column-${t.value}`);
            if (el) t.checked = !el.classList.contains('column-hidden');
        });
    });
}

// ── Column Settings ──
function loadColumnSettings() {
    const defaults = { email:true, telepon:true, alamat:true, jumlah_projek:true, dibuat:false, diubah:false };
    let s = { ...defaults };
    try { const saved = localStorage.getItem(STORAGE_KEY); if (saved) s = { ...defaults, ...JSON.parse(saved) }; } catch(e) {}
    document.querySelectorAll('.column-toggle:not([disabled])').forEach(t => {
        t.checked = s.hasOwnProperty(t.value) ? s[t.value] : (defaults[t.value] ?? true);
        toggleColumn(t.value, t.checked);
    });
}
function toggleColumn(col, show) {
    document.querySelectorAll(`.column-${col}`).forEach(el => el.classList.toggle('column-hidden', !show));
}
function saveColumnSettings() {
    const s = {};
    document.querySelectorAll('.column-toggle:not([disabled])').forEach(t => { s[t.value] = t.checked; });
    localStorage.setItem(STORAGE_KEY, JSON.stringify(s));
    bootstrap.Modal.getInstance(document.getElementById('columnSettingsModal'))?.hide();
    Swal.fire({ icon:'success', title:'Tersimpan', showConfirmButton:false, timer:1200, confirmButtonColor:'#5145cd' });
}
function resetColumns() {
    const defaults = { email:true, telepon:true, alamat:true, jumlah_projek:true, dibuat:false, diubah:false };
    document.querySelectorAll('.column-toggle:not([disabled])').forEach(t => { t.checked = defaults[t.value] ?? true; toggleColumn(t.value, t.checked); });
}

// ── Filters & Sort ──
function applyFilters() {
    filteredRows = allRows.filter(row => {
        if (!currentSearch) return true;
        return row.nama_perusahaan.includes(currentSearch)
            || row.email_perusahaan.includes(currentSearch)
            || row.nama_perwakilan.includes(currentSearch);
    });
    currentPage = 1; renderTable();
}
function sortRows(column, direction) {
    filteredRows.sort((a, b) => {
        if (['dibuat_pada','diperbarui_pada'].includes(column))
            return direction === 'asc' ? new Date(a[column]) - new Date(b[column]) : new Date(b[column]) - new Date(a[column]);
        return direction === 'asc'
            ? String(a[column]||'').localeCompare(String(b[column]||''))
            : String(b[column]||'').localeCompare(String(a[column]||''));
    });
    renderTable();
}

// ── Render ──
function renderTable() {
    const tbody = document.getElementById('perusahaanTableBody');
    tbody.innerHTML = '';
    const start = (currentPage - 1) * perPage;
    const page  = filteredRows.slice(start, Math.min(start + perPage, filteredRows.length));
    if (page.length === 0) {
        const msg = currentSearch
            ? `Tidak ada data perusahaan dengan kata kunci "<strong>${currentSearch}</strong>"`
            : 'Belum ada data perusahaan.';
        tbody.innerHTML = `<tr><td colspan="9"><div class="empty-state"><i class='bx bx-buildings'></i><p>${msg}</p></div></td></tr>`;
    } else {
        page.forEach((row, idx) => {
            const clone = row.element.cloneNode(true);
            const n = clone.querySelector('.row-no');
            if (n) n.textContent = start + idx + 1;
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
    if (ep-sp < 4) sp = Math.max(1, ep-4);
    if (sp > 1) { html+=`<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="goToPage(1)">1</a></li>`; if(sp>2) html+=`<li class="page-item disabled"><span class="page-link">…</span></li>`; }
    for (let i=sp; i<=ep; i++) html+=`<li class="page-item ${i===currentPage?'active':''}"><a class="page-link" href="javascript:void(0)" onclick="goToPage(${i})">${i}</a></li>`;
    if (ep < total) { if(ep<total-1) html+=`<li class="page-item disabled"><span class="page-link">…</span></li>`; html+=`<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="goToPage(${total})">${total}</a></li>`; }
    html+=`<li class="page-item ${currentPage===total?'disabled':''}"><a class="page-link" href="javascript:void(0)" onclick="goToPage(${currentPage+1})"><i class='bx bx-chevron-right'></i></a></li>`;
    ctrl.innerHTML = html;
}
function goToPage(page) { const t=Math.ceil(filteredRows.length/perPage); if(page<1||page>t)return; currentPage=page; renderTable(); }

// ── Helpers ──
function formatDate(str) {
    if (!str) return '—';
    return new Date(str).toLocaleDateString('id-ID',{day:'2-digit',month:'2-digit',year:'numeric'})
         + ' ' + new Date(str).toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
}
function logoHTML(logo, nama, size = 60, square = false) {
    const radius = square ? 'var(--radius-md)' : '50%';
    if (logo && logo !== 'null' && logo !== '') {
        return `<img src="/storage/${logo}" alt="${nama}" style="width:${size}px;height:${size}px;border-radius:${radius};object-fit:cover;border:3px solid var(--p-soft);">`;
    }
    const letter = nama ? nama.charAt(0).toUpperCase() : '?';
    return `<div style="width:${size}px;height:${size}px;border-radius:${radius};background:linear-gradient(135deg,var(--p1),var(--p4));display:flex;align-items:center;justify-content:center;color:white;font-size:${Math.round(size*.45)}px;font-weight:700;margin:0 auto;box-shadow:0 4px 16px rgba(105,108,255,.3);"><i class='bx bx-buildings'></i></div>`;
}

// ── CRUD ──
function viewPerusahaan(btn) {
    const d = JSON.parse(btn.dataset.item);
    currentViewBtn = btn;
    document.getElementById('view_logo_container').innerHTML = logoHTML(d.logo_perusahaan, d.nama_perusahaan, 88, true);
    document.getElementById('view_nama_perusahaan').textContent    = d.nama_perusahaan || '—';
    document.getElementById('view_email_perusahaan').textContent   = d.email_perusahaan || '—';
    document.getElementById('view_telepon_perusahaan').textContent = d.telepon_perusahaan || '—';
    document.getElementById('view_alamat_perusahaan').textContent  = d.alamat_perusahaan || '—';
    document.getElementById('view_nama_perwakilan').textContent    = d.nama_perwakilan || '—';
    document.getElementById('view_email_perwakilan').textContent   = d.email_perwakilan || '—';
    document.getElementById('view_telepon_perwakilan').textContent = d.telepon_perwakilan || '—';
    const jp = d.jumlah_projek ?? 0;
    document.getElementById('view_jumlah_projek').innerHTML =
        jp > 0
            ? `<span class="proj-pill"><i class='bx bx-folder'></i> ${jp} projek</span>`
            : `<span class="proj-pill empty"><i class='bx bx-folder'></i> 0 projek</span>`;
    document.getElementById('view_dibuat_pada').textContent    = formatDate(d.dibuat_pada);
    document.getElementById('view_diperbarui_pada').textContent = formatDate(d.diperbarui_pada);
    document.getElementById('view_edit_btn').onclick = function () {
        bootstrap.Modal.getInstance(document.getElementById('viewPerusahaanModal'))?.hide();
        setTimeout(() => { if (currentViewBtn) editPerusahaan(currentViewBtn); }, 300);
    };
    new bootstrap.Modal(document.getElementById('viewPerusahaanModal')).show();
}

function editPerusahaan(btn) {
    const d = JSON.parse(btn.dataset.item);
    document.getElementById('edit_nama_perusahaan').value    = d.nama_perusahaan || '';
    document.getElementById('edit_email_perusahaan').value   = d.email_perusahaan || '';
    document.getElementById('edit_telepon_perusahaan').value = d.telepon_perusahaan || '';
    document.getElementById('edit_alamat_perusahaan').value  = d.alamat_perusahaan || '';
    document.getElementById('edit_nama_perwakilan').value    = d.nama_perwakilan || '';
    document.getElementById('edit_email_perwakilan').value   = d.email_perwakilan || '';
    document.getElementById('edit_telepon_perwakilan').value = d.telepon_perwakilan || '';
    document.getElementById('edit_password').value           = '';
    document.getElementById('editPerusahaanForm').action     = `/master-data-perusahaan/${d.id_perusahaan}`;
    document.getElementById('currentLogoPreview').innerHTML  = logoHTML(d.logo_perusahaan, d.nama_perusahaan, 60, true);
    document.getElementById('editLogoPreview').style.display = 'none';
    document.getElementById('edit_logo').value = '';
    new bootstrap.Modal(document.getElementById('editPerusahaanModal')).show();
}

function deletePerusahaan(id, nama) {
    Swal.fire({
        title:'Hapus Perusahaan?',
        html:`Tindakan ini akan menghapus <strong>${nama}</strong> beserta akses login-nya secara permanen.`,
        icon:'warning', showCancelButton:true,
        confirmButtonColor:'#DC2626', cancelButtonColor:'#6B7280',
        confirmButtonText:'Hapus', cancelButtonText:'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            const f = document.createElement('form');
            f.method = 'POST'; f.action = `/master-data-perusahaan/${id}`;
            f.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
            document.body.appendChild(f); f.submit();
        }
    });
}

// ── Logo ──
function previewLogo(event, imgId, previewId) {
    const file = event.target.files[0]; if (!file) return;
    if (!['image/jpeg','image/jpg','image/png'].includes(file.type)) {
        Swal.fire({ icon:'error', title:'Format Tidak Valid', text:'Gunakan JPG, JPEG, atau PNG', confirmButtonColor:'#5145cd' });
        event.target.value = ''; return;
    }
    if (file.size > 2*1024*1024) {
        Swal.fire({ icon:'error', title:'Terlalu Besar', text:'Ukuran logo maksimal 2MB', confirmButtonColor:'#5145cd' });
        event.target.value = ''; return;
    }
    const reader = new FileReader();
    reader.onload = e => { document.getElementById(imgId).src = e.target.result; document.getElementById(previewId).style.display = 'flex'; };
    reader.readAsDataURL(file);
}
function clearLogo(inputId, imgId, previewId) {
    document.getElementById(inputId).value = '';
    document.getElementById(imgId).src = '';
    document.getElementById(previewId).style.display = 'none';
}

// ── Password ──
function togglePw(inputId, iconId) {
    const inp = document.getElementById(inputId), ic = document.getElementById(iconId);
    const show = inp.type === 'password';
    inp.type = show ? 'text' : 'password';
    ic.className = show ? 'bx bx-show' : 'bx bx-hide';
}

// ── Form Submit ──
['addPerusahaanForm','editPerusahaanForm'].forEach(formId => {
    const form = document.getElementById(formId); if (!form) return;
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const mid = formId === 'addPerusahaanForm' ? 'addPerusahaanModal' : 'editPerusahaanModal';
        bootstrap.Modal.getInstance(document.getElementById(mid))?.hide();
        setTimeout(() => { Swal.fire({ title:'Memproses...', allowOutsideClick:false, didOpen:() => Swal.showLoading() }); this.submit(); }, 300);
    });
});
</script>
@endpush