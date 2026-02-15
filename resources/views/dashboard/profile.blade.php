@extends('layouts.master')
@section('title', 'Profile Settings')
@section('content')
<div class="container-xxl flex-grow-1" style="padding: 0.5rem 1.625rem 1.625rem;">
    <h4 class="fw-bold mb-3" style="margin-top: 0;"><span class="text-muted fw-light">Pengaturan Akun /</span> Profil</h4>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <!-- Profile Details Card -->
                <div class="card mb-4">
                    <h5 class="card-header">Detail Profil</h5>
                    <!-- Account -->
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
<div class="d-flex align-items-start align-items-sm-center gap-4 mb-4">
    <img src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('assets/img/avatars/1.png') }}"
        alt="user-avatar" 
        class="d-block" 
        id="uploadedAvatar"
        style="width:100px; height:100px; object-fit:cover; border-radius:15px; border:2px solid #ddd;" />

    <div class="button-wrapper">
        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
            <span class="d-none d-sm-block">Upload Foto Profil</span>
            <i class="bx bx-upload d-block d-sm-none"></i>
            <input type="file" id="upload" name="foto" class="account-file-input" hidden
                accept="image/png, image/jpeg, image/jpg" onchange="previewImage(event)" />
        </label>

  <p class="text-muted mb-0">Format diperbolehkan: JPG atau PNG. Ukuran maksimal 2000KB</p>

    </div>
</div>


                            <hr class="my-0 mb-4" />

                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="nama" class="form-label">Nama Lengkap</label>
                                    <input class="form-control" type="text" id="nama" name="nama"
                                        value="{{ old('nama', $user->nama) }}" required />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input class="form-control" type="email" id="email" name="email"
                                        value="{{ $user->email }}" readonly disabled />
                                    <small class="text-muted">Email tidak dapat diubah</small>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="role" class="form-label">Role</label>
                                    <input class="form-control text-capitalize" type="text" id="role"
                                        value="{{ $user->role }}" readonly disabled />
                                    <small class="text-muted">Role tidak dapat diubah</small>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="job_role" class="form-label">Jabatan</label>
                                    <input type="text" class="form-control" id="job_role" name="job_role"
                                        value="{{ old('job_role', $user->job_role) }}" placeholder="Masukkan jabatan" />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="no_hp">No. HP</label>
                                    <input type="text" id="no_hp" name="no_hp" class="form-control"
                                        value="{{ old('no_hp', $user->no_hp) }}" placeholder="08xx xxxx xxxx" />
                                </div>
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Simpan perubahan</button>
                                <button type="reset" class="btn btn-outline-secondary">Batal</button>
                            </div>
                        </form>
                    </div>
                    <!-- /Account -->
                </div>

                <!-- Change Password Card -->
                <div class="card mb-4">
                    <h5 class="card-header">Ubah Password</h5>
                    <div class="card-body">
                        <form action="{{ route('profile.update-password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label for="current_password" class="form-label">Password Saat Ini</label>
                                    <input class="form-control" type="password" id="current_password"
                                        name="current_password" placeholder="Masukkan password saat ini" />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="new_password" class="form-label">Password Baru</label>
                                    <input class="form-control" type="password" id="new_password" name="new_password"
                                        placeholder="Masukkan password baru" />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="new_password_confirmation" class="form-label">Konfirmasi Password
                                        Baru</label>
                                    <input class="form-control" type="password" id="new_password_confirmation"
                                        name="new_password_confirmation" placeholder="Konfirmasi password baru" />
                                </div>
                            </div>

                            <div class="mt-2"><button type="submit" class="btn btn-primary me-2">Perbarui Password</button>
                            <button type="reset" class="btn btn-outline-secondary">Batal</button>

                            </div>
                        </form>
                    </div>
                </div>
<!-- Hapus Akun Card -->
<div class="card">
    <h5 class="card-header">Hapus Akun</h5>
    <div class="card-body">
        <div class="mb-4col-12 mb-0">
            <div class="alert alert-warning">
                <h6 class="alert-heading fw-bold mb-1">Apakah Anda yakin ingin menghapus akun?</h6>
                <p class="mb-0">Setelah akun dihapus, tindakan ini tidak bisa dibatalkan. Mohon pastikan keputusan Anda.</p>
            </div>
        </div>
<form id="formAccountDeactivation">
    @csrf
    @method('DELETE')
    
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="accountActivation"
            id="accountActivation" value="1" />
        <label class="form-check-label" for="accountActivation">
            Saya mengonfirmasi untuk menghapus akun saya
        </label>
    </div>
    <button type="button" class="btn btn-danger deactivate-account" onclick="confirmDeleteAccount()">Hapus Akun</button>
</form>
    </div>
</div>

            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('uploadedAvatar').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }

    function resetImage() {
        document.getElementById('uploadedAvatar').src = "{{ $user->foto ? asset('storage/' . $user->foto) : asset('assets/img/avatars/1.png') }}";
        document.getElementById('upload').value = '';
    }

function confirmDeleteAccount() {
    const checkbox = document.getElementById('accountActivation');
    
    if (!checkbox.checked) {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: 'Silakan centang konfirmasi terlebih dahulu',
            confirmButtonColor: '#696cff'
        });
        return;
    }
    
    Swal.fire({
        title: 'Apakah Anda yakin?',
        html: 'Setelah akun dihapus, tindakan ini <strong>tidak bisa dibatalkan</strong>.<br>Semua data Anda akan hilang permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus Akun!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Menghapus akun...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('profile.deactivate') }}";
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection