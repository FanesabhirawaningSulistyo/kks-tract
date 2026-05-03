<?php

use App\Http\Controllers\ApprovalTaskController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobRoleController;
use App\Http\Controllers\KategoriProjectController;
use App\Http\Controllers\MetodePembayaranController;
use App\Http\Controllers\PembayaranProjekController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjekController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route untuk guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Route untuk user yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/dashboard/pm', [DashboardController::class, 'index2'])->name('dashboard.pm');
    
    // Dashboard untuk pegawai
    Route::get('/dashboard/pegawai', [DashboardController::class, 'index3'])
        ->name('dashboard.pegawai');
    
    // Dashboard untuk admin
    Route::get('/dashboard/klien', [DashboardController::class, 'index4'])
        ->name('dashboard.klien');

    Route::get('/kelolatask', [TaskController::class, 'index'])
        ->name('dashboard.kelolatask');

    Route::get('/taskkaryawan', [TaskController::class, 'index2'])
        ->name('dashboard.taskkaryawan');

    Route::get('/kelolaproject', [TaskController::class, 'kelolaproject'])
        ->name('dashboard.kelolaproject');
    
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::prefix('performa-karyawan')->name('performa-karyawan.')->middleware('auth')->group(function () {
        Route::get('/',            [\App\Http\Controllers\PerformaKaryawanController::class, 'index'])->name('index');
        Route::get('/{id}/detail', [\App\Http\Controllers\PerformaKaryawanController::class, 'detail'])->name('detail');
    });
});

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('password.store');

// Master Data Users
Route::prefix('master-data-users')->name('master-data-users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
});

// Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::delete('/profile/deactivate', [ProfileController::class, 'deactivate'])->name('profile.deactivate');
});

// Master Data Perusahaan
Route::middleware(['auth'])->group(function () {
    Route::resource('master-data-perusahaan', PerusahaanController::class)->names([
        'index' => 'master-data-perusahaan.index',
        'store' => 'master-data-perusahaan.store',
        'update' => 'master-data-perusahaan.update',
        'destroy' => 'master-data-perusahaan.destroy',
    ]);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/master-data-projek', [ProjekController::class, 'index'])->name('master-data-projek.index');
    Route::post('/master-data-projek', [ProjekController::class, 'store'])->name('master-data-projek.store');
    Route::put('/master-data-projek/{projek}', [ProjekController::class, 'update'])->name('master-data-projek.update');
    Route::delete('/master-data-projek/{projek}', [ProjekController::class, 'destroy'])->name('master-data-projek.destroy');
    Route::get('/master-data-projek/{projek}/laporan', [ProjekController::class, 'laporan'])->name('master-data-projek.laporan');
    Route::patch('/master-data-projek/{id}/status', [ProjekController::class, 'updateStatus'])->name('master-data-projek.updateStatus');

    // ← Tambah ini agar GET /master-data-projek/1 tidak 405
    Route::get('/master-data-projek/{projek}', function () {
        return redirect()->route('master-data-projek.index');
    });
});

// Master Data Tugas
Route::prefix('master-data-tugas')->name('master-data-tugas.')->group(function () {
    Route::get('/', [TugasController::class, 'index'])->name('index');
    Route::post('/', [TugasController::class, 'store'])->name('store');
    Route::put('/{id}', [TugasController::class, 'update'])->name('update');
    Route::delete('/{id}', [TugasController::class, 'destroy'])->name('destroy');
});

// Master Data Job Role - TAMBAHKAN ROUTE UPDATE-STATUS
Route::prefix('master-data-jobrole')->name('master-data-jobrole.')->group(function () {
    Route::get('/', [JobRoleController::class, 'index'])->name('index');
    Route::post('/', [JobRoleController::class, 'store'])->name('store');
    Route::put('/{id}', [JobRoleController::class, 'update'])->name('update');
    Route::put('/{id}/status', [JobRoleController::class, 'updateStatus'])->name('update-status');
    Route::delete('/{id}', [JobRoleController::class, 'destroy'])->name('destroy');
    
});

Route::prefix('master-data-kategori-projek')->name('master-data-kategori-projek.')->group(function () {
    Route::get('/', [KategoriProjectController::class, 'index'])->name('index');
    Route::post('/', [KategoriProjectController::class, 'store'])->name('store');
    Route::put('/{id}', [KategoriProjectController::class, 'update'])->name('update');
    Route::put('/{id}/status', [KategoriProjectController::class, 'updateStatus'])->name('update-status');
    Route::delete('/{id}', [KategoriProjectController::class, 'destroy'])->name('destroy');
});


Route::prefix('projek/{id_projek}/task')->name('task.')->middleware('auth')->group(function () {
    // Halaman utama kelola task
    Route::get('/', [TaskController::class, 'index'])->name('index');

    // ─── Route statis (tanpa wildcard) — HARUS di atas route dengan {parameter} ───
    Route::get('/data',       [TaskController::class, 'getTasks'])->name('data');
    Route::get('/user-stats', [TaskController::class, 'getUserStats'])->name('user-stats'); // ← INI YANG HILANG
    Route::post('/',          [TaskController::class, 'storeTask'])->name('store');

    // Tim — statis dulu sebelum /{id_tugas}
    Route::post('/tim/invite',       [TaskController::class, 'inviteTim'])->name('tim.invite');
    Route::delete('/tim/{id_tim}',   [TaskController::class, 'removeTim'])->name('tim.remove');
    // Di dalam group route 'projek/{id_projek}/task' (sudah ada)
    Route::post('/{id_tugas}/catatan', [TaskController::class, 'storeCatatan'])->name('catatan.store');

    // ─── Route dengan wildcard {id_tugas} — HARUS di bawah route statis ───
    Route::put('/{id_tugas}',                        [TaskController::class, 'updateTask'])->name('update');
    Route::patch('/{id_tugas}/status-akhir',         [TaskController::class, 'updateStatusAkhir'])->name('status-akhir');
    Route::delete('/{id_tugas}',                     [TaskController::class, 'destroyTask'])->name('destroy');
    Route::post('/{id_tugas}/foto',                  [TaskController::class, 'uploadFoto'])->name('foto.upload');
    Route::delete('/{id_tugas}/foto/{id_foto}',      [TaskController::class, 'destroyFoto'])->name('foto.destroy');
});

Route::patch('projek/{id}/tanggal', [ProjekController::class, 'updateTanggal'])->name('projek.updateTanggal');

Route::prefix('pembayaran-projek')->name('pembayaran-projek.')->middleware(['auth'])->group(function () {

    // ── Index & Store (resource standard) ──
    Route::get('/',    [PembayaranProjekController::class, 'index'])->name('index');
    Route::post('/',   [PembayaranProjekController::class, 'store'])->name('store');

    // ── Detail per projek ──
    Route::get('/{id_projek}/detail',  [PembayaranProjekController::class, 'show'])->name('show');
    Route::get('/{id_projek}/riwayat', [PembayaranProjekController::class, 'getRiwayat'])->name('riwayat');

    // ── Cetak ──
    Route::get('/{id_projek}/cetak-riwayat', [PembayaranProjekController::class, 'cetakRiwayat'])->name('cetak-riwayat');
    Route::get('/{id_pembayaran}/struk',      [PembayaranProjekController::class, 'cetakStruk'])->name('struk');

    // ── Upload bukti per baris (✅ WAJIB ADA — ini yang 404) ──
    Route::post('/{id_pembayaran}/bukti',  [PembayaranProjekController::class, 'uploadBukti'])->name('upload-bukti');

    // ── Update status ──
    Route::patch('/{id_pembayaran}/status', [PembayaranProjekController::class, 'updateStatus'])->name('update-status');
});
Route::prefix('pembayaran-projek')
    ->name('pembayaran-projek.')
    ->controller(PembayaranProjekController::class)
    ->group(function () {
        Route::get('/',                       'index')->name('index');
        Route::post('/',                      'store')->name('store');
        Route::get('/{id_projek}/detail',     'show')->name('show');        // ← BARU: halaman detail
        Route::get('/{id_projek}/riwayat',    'getRiwayat')->name('riwayat');
        Route::patch('/{id_pembayaran}/status', 'updateStatus')->name('updateStatus');
        Route::get('/{id_pembayaran}/struk',  'cetakStruk')->name('cetakStruk');
        Route::get('/{id_projek}/cetak-riwayat', 'cetakRiwayat')->name('cetakRiwayat');
    });

Route::prefix('approval-task')->name('approval-task.')->group(function () {
    Route::get('/',          [ApprovalTaskController::class, 'index'])->name('index');
    Route::post('/{id}/approve', [ApprovalTaskController::class, 'approve'])->name('approve');
    Route::post('/{id}/revisi',  [ApprovalTaskController::class, 'revisi'])->name('revisi');
});

Route::prefix('master-data-metode-pembayaran')->name('master-data-metode-pembayaran.')->middleware('auth')->group(function () {
    Route::get('/', [MetodePembayaranController::class, 'index'])->name('index');
    Route::post('/', [MetodePembayaranController::class, 'store'])->name('store');
    Route::put('/{id}', [MetodePembayaranController::class, 'update'])->name('update');
    Route::delete('/{id}', [MetodePembayaranController::class, 'destroy'])->name('destroy');
});
// Redirect root ke dashboard jika sudah login, atau ke login jika belum
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});
