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
use App\Http\Controllers\PerformaKaryawanController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjekController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== ROUTE GUEST (BELUM LOGIN) ====================
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// ==================== ROUTE PASSWORD RESET (TANPA PROTEKSI ROLE) ====================
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('password.store');

// ==================== ROUTE YANG SUDAH LOGIN (AUTH) ====================
Route::middleware('auth')->group(function () {

    // ---------- DASHBOARD ----------
    // Dashboard utama - semua role yang login
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('role:admin,PM,karyawan,klien');

    // Dashboard PM - khusus PM dan Admin
    Route::get('/dashboard/pm', [DashboardController::class, 'index2'])
        ->name('dashboard.pm')
        ->middleware('role:PM,admin');

    // Dashboard Pegawai - khusus karyawan dan Admin
    Route::get('/dashboard/pegawai', [DashboardController::class, 'index3'])
        ->name('dashboard.pegawai')
        ->middleware('role:karyawan,admin');

    // Dashboard Klien - khusus klien dan Admin
    Route::get('/dashboard/klien', [DashboardController::class, 'index4'])
        ->name('dashboard.klien')
        ->middleware('role:klien,admin');

    // ---------- TASK MANAGEMENT ----------
    // Kelola Task - khusus Admin dan PM
    Route::get('/kelolatask', [TaskController::class, 'index'])
        ->name('dashboard.kelolatask')
        ->middleware('role:admin,PM');

    // Task Karyawan - khusus karyawan
    Route::get('/taskkaryawan', [TaskController::class, 'index2'])
        ->name('dashboard.taskkaryawan')
        ->middleware('role:karyawan');

    // Kelola Project - khusus Admin dan PM
    Route::get('/kelolaproject', [TaskController::class, 'kelolaproject'])
        ->name('dashboard.kelolaproject')
        ->middleware('role:admin,PM');

    // ---------- LOGOUT ----------
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // ---------- PERFORMA KARYAWAN ----------
    // Khusus Admin dan PM
    Route::prefix('performa-karyawan')->name('performa-karyawan.')
        ->middleware('role:admin,PM,karyawan')
        ->group(function () {
            Route::get('/', [PerformaKaryawanController::class, 'index'])->name('index');
            Route::get('/{id}/detail', [PerformaKaryawanController::class, 'detail'])->name('detail');
        });
});

// ==================== MASTER DATA USERS ====================
// Hanya ADMIN yang bisa mengakses
Route::prefix('master-data-users')->name('master-data-users.')
    ->middleware('auth', 'role:admin')
    ->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

// ==================== PROFILE ROUTES ====================
// Semua user yang login bisa akses
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::delete('/profile/deactivate', [ProfileController::class, 'deactivate'])->name('profile.deactivate');
});

// ==================== MASTER DATA PERUSAHAAN ====================
// Hanya ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('master-data-perusahaan', PerusahaanController::class)->names([
        'index' => 'master-data-perusahaan.index',
        'store' => 'master-data-perusahaan.store',
        'update' => 'master-data-perusahaan.update',
        'destroy' => 'master-data-perusahaan.destroy',
    ]);
});

// ==================== MASTER DATA PROJEK ====================
Route::middleware(['auth'])->group(function () {
    // INDEX - semua role bisa lihat
    Route::get('/master-data-projek', [ProjekController::class, 'index'])
        ->name('master-data-projek.index')
        ->middleware('role:admin,PM,karyawan,klien');

    // STORE - hanya admin dan PM
    Route::post('/master-data-projek', [ProjekController::class, 'store'])
        ->name('master-data-projek.store')
        ->middleware('role:admin,PM');

    // UPDATE - hanya admin dan PM
    Route::put('/master-data-projek/{projek}', [ProjekController::class, 'update'])
        ->name('master-data-projek.update')
        ->middleware('role:admin,PM');

    // DESTROY - hanya admin
    Route::delete('/master-data-projek/{projek}', [ProjekController::class, 'destroy'])
        ->name('master-data-projek.destroy')
        ->middleware('role:admin');

    // LAPORAN - admin, PM, dan klien
    Route::get('/master-data-projek/{projek}/laporan', [ProjekController::class, 'laporan'])
        ->name('master-data-projek.laporan')
        ->middleware('role:admin,PM,klien');

    // UPDATE STATUS - admin dan PM
    Route::patch('/master-data-projek/{id}/status', [ProjekController::class, 'updateStatus'])
        ->name('master-data-projek.updateStatus')
        ->middleware('role:admin,PM');

    // Redirect untuk GET detail (menghindari error 405)
    Route::get('/master-data-projek/{projek}', function () {
        return redirect()->route('master-data-projek.index');
    })->middleware('role:admin,PM,karyawan,klien');
});

// ==================== MASTER DATA TUGAS ====================
// Hanya admin dan PM
Route::prefix('master-data-tugas')->name('master-data-tugas.')
    ->middleware('auth', 'role:admin,PM')
    ->group(function () {
        Route::get('/', [TugasController::class, 'index'])->name('index');
        Route::post('/', [TugasController::class, 'store'])->name('store');
        Route::put('/{id}', [TugasController::class, 'update'])->name('update');
        Route::delete('/{id}', [TugasController::class, 'destroy'])->name('destroy');
    });

// ==================== MASTER DATA JOB ROLE ====================
// Hanya ADMIN
Route::prefix('master-data-jobrole')->name('master-data-jobrole.')
    ->middleware('auth', 'role:admin')
    ->group(function () {
        Route::get('/', [JobRoleController::class, 'index'])->name('index');
        Route::post('/', [JobRoleController::class, 'store'])->name('store');
        Route::put('/{id}', [JobRoleController::class, 'update'])->name('update');
        Route::put('/{id}/status', [JobRoleController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{id}', [JobRoleController::class, 'destroy'])->name('destroy');
    });

// ==================== MASTER DATA KATEGORI PROJEK ====================
// Admin dan PM
Route::prefix('master-data-kategori-projek')->name('master-data-kategori-projek.')
    ->middleware('auth', 'role:admin,PM')
    ->group(function () {
        Route::get('/', [KategoriProjectController::class, 'index'])->name('index');
        Route::post('/', [KategoriProjectController::class, 'store'])->name('store');
        Route::put('/{id}', [KategoriProjectController::class, 'update'])->name('update');
        Route::put('/{id}/status', [KategoriProjectController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{id}', [KategoriProjectController::class, 'destroy'])->name('destroy');
    });

// ==================== TASK MANAGEMENT (DETAIL PER PROJEK) ====================
Route::prefix('projek/{id_projek}/task')->name('task.')
    ->middleware('auth')
    ->group(function () {

        // INDEX - semua role (admin, PM, karyawan)
        Route::get('/', [TaskController::class, 'index'])
            ->name('index')
            ->middleware('role:admin,PM,karyawan');

        // GET DATA TASK - semua role
        Route::get('/data', [TaskController::class, 'getTasks'])
            ->name('data')
            ->middleware('role:admin,PM,karyawan');

        // USER STATS - hanya admin dan PM
        Route::get('/user-stats', [TaskController::class, 'getUserStats'])
            ->name('user-stats')
            ->middleware('role:admin,PM');

        // STORE TASK - hanya admin dan PM
        Route::post('/', [TaskController::class, 'storeTask'])
            ->name('store')
            ->middleware('role:admin,PM');

        // INVITE TIM - hanya admin dan PM
        Route::post('/tim/invite', [TaskController::class, 'inviteTim'])
            ->name('tim.invite')
            ->middleware('role:admin,PM');

        // REMOVE TIM - hanya admin dan PM
        Route::delete('/tim/{id_tim}', [TaskController::class, 'removeTim'])
            ->name('tim.remove')
            ->middleware('role:admin,PM');

        // STORE CATATAN - semua role
        Route::post('/{id_tugas}/catatan', [TaskController::class, 'storeCatatan'])
            ->name('catatan.store')
            ->middleware('role:admin,PM,karyawan');

        // UPDATE TASK - semua role (karyawan bisa update status)
        Route::put('/{id_tugas}', [TaskController::class, 'updateTask'])
            ->name('update')
            ->middleware('role:admin,PM,karyawan');

        // UPDATE STATUS AKHIR - semua role
        Route::patch('/{id_tugas}/status-akhir', [TaskController::class, 'updateStatusAkhir'])
            ->name('status-akhir')
            ->middleware('role:admin,PM,karyawan');

        // DESTROY TASK - hanya admin dan PM
        Route::delete('/{id_tugas}', [TaskController::class, 'destroyTask'])
            ->name('destroy')
            ->middleware('role:admin,PM');

        // UPLOAD FOTO - semua role
        Route::post('/{id_tugas}/foto', [TaskController::class, 'uploadFoto'])
            ->name('foto.upload')
            ->middleware('role:admin,PM,karyawan');

        // DESTROY FOTO - hanya admin dan PM
        Route::delete('/{id_tugas}/foto/{id_foto}', [TaskController::class, 'destroyFoto'])
            ->name('foto.destroy')
            ->middleware('role:admin,PM');
    });

// ==================== UPDATE TANGGAL PROJEK ====================
Route::patch('projek/{id}/tanggal', [ProjekController::class, 'updateTanggal'])
    ->name('projek.updateTanggal')
    ->middleware('auth', 'role:admin,PM');

// ==================== PEMBAYARAN PROJEK ====================
Route::prefix('pembayaran-projek')->name('pembayaran-projek.')
    ->middleware(['auth'])
    ->group(function () {

        // INDEX - admin dan klien
        Route::get('/', [PembayaranProjekController::class, 'index'])
            ->name('index')
            ->middleware('role:admin,klien');

        // STORE - hanya admin
        Route::post('/', [PembayaranProjekController::class, 'store'])
            ->name('store')
            ->middleware('role:admin');

        // DETAIL PER PROJEK - admin dan klien
        Route::get('/{id_projek}/detail', [PembayaranProjekController::class, 'show'])
            ->name('show')
            ->middleware('role:admin,klien');

        // RIWAYAT - admin dan klien
        Route::get('/{id_projek}/riwayat', [PembayaranProjekController::class, 'getRiwayat'])
            ->name('riwayat')
            ->middleware('role:admin,klien');

        // CETAK RIWAYAT - admin dan klien
        Route::get('/{id_projek}/cetak-riwayat', [PembayaranProjekController::class, 'cetakRiwayat'])
            ->name('cetak-riwayat')
            ->middleware('role:admin,klien');

        // CETAK STRUK - admin dan klien
        Route::get('/{id_pembayaran}/struk', [PembayaranProjekController::class, 'cetakStruk'])
            ->name('struk')
            ->middleware('role:admin,klien');

        // UPLOAD BUKTI - klien dan admin
        Route::post('/{id_pembayaran}/bukti', [PembayaranProjekController::class, 'uploadBukti'])
            ->name('upload-bukti')
            ->middleware('role:klien,admin');

        // UPDATE STATUS - hanya admin
        Route::patch('/{id_pembayaran}/status', [PembayaranProjekController::class, 'updateStatus'])
            ->name('update-status')
            ->middleware('role:admin');
    });

// ==================== APPROVAL TASK ====================
// Hanya admin dan PM
Route::prefix('approval-task')->name('approval-task.')
    ->middleware('auth', 'role:admin,PM')
    ->group(function () {
        Route::get('/', [ApprovalTaskController::class, 'index'])->name('index');
        Route::post('/{id}/approve', [ApprovalTaskController::class, 'approve'])->name('approve');
        Route::post('/{id}/revisi', [ApprovalTaskController::class, 'revisi'])->name('revisi');
    });

// ==================== MASTER DATA METODE PEMBAYARAN ====================
// Hanya admin
Route::prefix('master-data-metode-pembayaran')->name('master-data-metode-pembayaran.')
    ->middleware('auth', 'role:admin')
    ->group(function () {
        Route::get('/', [MetodePembayaranController::class, 'index'])->name('index');
        Route::post('/', [MetodePembayaranController::class, 'store'])->name('store');
        Route::put('/{id}', [MetodePembayaranController::class, 'update'])->name('update');
        Route::delete('/{id}', [MetodePembayaranController::class, 'destroy'])->name('destroy');
    });

// ==================== REDIRECT ROOT ====================
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});
