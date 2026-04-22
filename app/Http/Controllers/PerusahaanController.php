<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\JobRole;
use App\Models\Projek; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PerusahaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Perusahaan::with('userPerusahaan');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%{$search}%")
                    ->orWhere('email_perusahaan', 'like', "%{$search}%")
                    ->orWhere('telepon_perusahaan', 'like', "%{$search}%")
                    ->orWhere('nama_perwakilan', 'like', "%{$search}%")
                    ->orWhere('email_perwakilan', 'like', "%{$search}%")
                    ->orWhere('telepon_perwakilan', 'like', "%{$search}%")
                    ->orWhere('alamat_perusahaan', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy    = $request->get('sort_by', 'diperbarui_pada');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // PAGINATION - Gunakan paginate() bukan get()
        $perPage = $request->get('per_page', 10);
        $perusahaans = $query->paginate($perPage);

        // === TAMBAHKAN INI: Hitung jumlah projek untuk setiap perusahaan ===
        foreach ($perusahaans as $perusahaan) {
            $perusahaan->jumlah_projek = Projek::where('id_perusahaan', $perusahaan->id_perusahaan)->count();
        }

        return view('dashboard.master-data-perusahaan', compact('perusahaans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_perusahaan'     => 'required|string|max:100',
            'email_perusahaan'    => 'required|email|max:100|unique:users,email',
            'telepon_perusahaan'  => 'nullable|string|max:20',
            'password_perusahaan' => 'required|string|min:8',
            'nama_perwakilan'     => 'required|string|max:100',
            'email_perwakilan'    => 'required|email|max:100|unique:perusahaan,email_perwakilan',
            'telepon_perwakilan'  => 'nullable|string|max:20',
            'alamat_perusahaan'   => 'required|string',
            'logo_perusahaan'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama_perusahaan.required'      => 'Nama perusahaan wajib diisi',
            'email_perusahaan.required'     => 'Email perusahaan wajib diisi',
            'email_perusahaan.email'        => 'Format email perusahaan tidak valid',
            'email_perusahaan.unique'       => 'Email perusahaan sudah terdaftar',
            'password_perusahaan.required'  => 'Password wajib diisi',
            'password_perusahaan.min'       => 'Password minimal 8 karakter',
            'nama_perwakilan.required'      => 'Nama perwakilan wajib diisi',
            'email_perwakilan.required'     => 'Email perwakilan wajib diisi',
            'email_perwakilan.email'        => 'Format email perwakilan tidak valid',
            'email_perwakilan.unique'       => 'Email perwakilan sudah terdaftar',
            'alamat_perusahaan.required'    => 'Alamat perusahaan wajib diisi',
            'logo_perusahaan.image'         => 'File harus berupa gambar',
            'logo_perusahaan.mimes'         => 'Logo harus berformat jpeg, png, atau jpg',
            'logo_perusahaan.max'           => 'Ukuran logo maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Ambil Job Role untuk Klien
            $klienJobRole = JobRole::where('nama_job_role', 'Klien')->first();

            // Handle logo upload
            $logoPath = null;
            if ($request->hasFile('logo_perusahaan')) {
                $logo     = $request->file('logo_perusahaan');
                $logoName = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
                $logoPath = $logo->storeAs('logos', $logoName, 'public');
            }

            // 1. Buat User (akun login perusahaan) — status default aktif
            $userPerusahaan = User::create([
                'nama'        => $request->nama_perusahaan,
                'email'       => $request->email_perusahaan,
                'password'    => Hash::make($request->password_perusahaan),
                'role'        => 'klien',
                'id_job_role' => $klienJobRole ? $klienJobRole->id_job_role : null,
                'no_hp'       => $request->telepon_perusahaan,
                'foto'        => null,
                'status'      => true,
            ]);

            // 2. Buat Perusahaan — status ikut dari user (aktif)
            Perusahaan::withoutEvents(function () use ($userPerusahaan, $request, $logoPath) {
                Perusahaan::create([
                    'id_user_perusahaan'  => $userPerusahaan->id_user,
                    'nama_perusahaan'     => $request->nama_perusahaan,
                    'email_perusahaan'    => $request->email_perusahaan,
                    'telepon_perusahaan'  => $request->telepon_perusahaan,
                    'nama_perwakilan'     => $request->nama_perwakilan,
                    'email_perwakilan'    => $request->email_perwakilan,
                    'telepon_perwakilan'  => $request->telepon_perwakilan,
                    'logo_perusahaan'     => $logoPath,
                    'alamat_perusahaan'   => $request->alamat_perusahaan,
                    'status'              => true,
                ]);
            });

            DB::commit();

            return redirect()->route('master-data-perusahaan.index')
                ->with('success', 'Data perusahaan dan akun berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($logoPath) {
                Storage::disk('public')->delete($logoPath);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * Jika status perusahaan diubah di sini, status user (akun klien) juga ikut berubah.
     */
    public function update(Request $request, $id)
    {
        $perusahaan = Perusahaan::with('userPerusahaan')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_perusahaan'     => 'required|string|max:100',
            'email_perusahaan'    => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($perusahaan->id_user_perusahaan, 'id_user'),
                Rule::unique('perusahaan', 'email_perusahaan')->ignore($perusahaan->id_perusahaan, 'id_perusahaan'),
            ],
            'telepon_perusahaan'  => 'nullable|string|max:20',
            'password_perusahaan' => 'nullable|string|min:8',
            'nama_perwakilan'     => 'required|string|max:100',
            'email_perwakilan'    => [
                'required',
                'email',
                'max:100',
                Rule::unique('perusahaan', 'email_perwakilan')->ignore($perusahaan->id_perusahaan, 'id_perusahaan'),
            ],
            'telepon_perwakilan'  => 'nullable|string|max:20',
            'alamat_perusahaan'   => 'required|string',
            'logo_perusahaan'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // status boleh diubah dari form perusahaan juga
            'status'              => 'nullable|boolean',
        ], [
            'nama_perusahaan.required'    => 'Nama perusahaan wajib diisi',
            'email_perusahaan.required'   => 'Email perusahaan wajib diisi',
            'email_perusahaan.email'      => 'Format email perusahaan tidak valid',
            'email_perusahaan.unique'     => 'Email perusahaan sudah terdaftar',
            'password_perusahaan.min'     => 'Password minimal 8 karakter',
            'nama_perwakilan.required'    => 'Nama perwakilan wajib diisi',
            'email_perwakilan.required'   => 'Email perwakilan wajib diisi',
            'email_perwakilan.email'      => 'Format email perwakilan tidak valid',
            'email_perwakilan.unique'     => 'Email perwakilan sudah terdaftar',
            'alamat_perusahaan.required'  => 'Alamat perusahaan wajib diisi',
            'logo_perusahaan.image'       => 'File harus berupa gambar',
            'logo_perusahaan.mimes'       => 'Logo harus berformat jpeg, png, atau jpg',
            'logo_perusahaan.max'         => 'Ukuran logo maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Handle logo upload
            $logoPath = $perusahaan->getRawOriginal('logo_perusahaan') ?? $perusahaan->logo_perusahaan;
            if ($request->hasFile('logo_perusahaan')) {
                if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                    Storage::disk('public')->delete($logoPath);
                }
                $logo     = $request->file('logo_perusahaan');
                $logoName = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
                $logoPath = $logo->storeAs('logos', $logoName, 'public');
            }

            // Status TIDAK bisa diubah dari sini — hanya via Master Data User
            $statusBaru = (bool) $perusahaan->getRawOriginal('status');

            // ============================================================
            // 1. Update User (akun login perusahaan)
            //    - Nama, email, telepon, password (jika diisi), dan STATUS
            //    - updateQuietly agar tidak trigger model event loop
            // ============================================================
            if ($perusahaan->userPerusahaan) {
                $userData = [
                    'nama'   => $request->nama_perusahaan,
                    'email'  => $request->email_perusahaan,
                    'no_hp'  => $request->telepon_perusahaan,
                ];
                if ($request->filled('password_perusahaan')) {
                    $userData['password'] = Hash::make($request->password_perusahaan);
                }
                $perusahaan->userPerusahaan->updateQuietly($userData);
            }

            // ============================================================
            // 2. Update Perusahaan
            //    - Semua field termasuk status
            //    - withoutEvents agar tidak sync balik ke user (cegah loop)
            // ============================================================
            Perusahaan::withoutEvents(function () use ($perusahaan, $request, $logoPath, $statusBaru) {
                $perusahaan->update([
                    'nama_perusahaan'    => $request->nama_perusahaan,
                    'email_perusahaan'   => $request->email_perusahaan,
                    'telepon_perusahaan' => $request->telepon_perusahaan,
                    'nama_perwakilan'    => $request->nama_perwakilan,
                    'email_perwakilan'   => $request->email_perwakilan,
                    'telepon_perwakilan' => $request->telepon_perwakilan,
                    'logo_perusahaan'    => $logoPath,
                    'alamat_perusahaan'  => $request->alamat_perusahaan,
                   
                ]);
            });

            DB::commit();

            return redirect()->route('master-data-perusahaan.index')
                ->with('success', 'Data perusahaan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * Menghapus perusahaan beserta user (akun login) terkait sekaligus.
     * Inilah satu-satunya cara menghapus user klien yang masih punya perusahaan.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $perusahaan = Perusahaan::with('userPerusahaan')->findOrFail($id);

            // Cek apakah perusahaan memiliki proyek terkait
            if (method_exists($perusahaan, 'projek') && $perusahaan->projek()->count() > 0) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Perusahaan tidak dapat dihapus karena masih memiliki proyek terkait!');
            }

            // Hapus logo jika ada
            $rawLogo = $perusahaan->getRawOriginal('logo_perusahaan') ?? $perusahaan->logo_perusahaan;
            if ($rawLogo && Storage::disk('public')->exists($rawLogo)) {
                Storage::disk('public')->delete($rawLogo);
            }

            // Simpan id user sebelum hapus perusahaan
            $userId = $perusahaan->id_user_perusahaan;

            // Hapus perusahaan dahulu (supaya foreign key tidak konflik)
            $perusahaan->delete();

            // Hapus user (akun login perusahaan)
            if ($userId) {
                $user = User::find($userId);
                if ($user) {
                    if ($user->foto) {
                        Storage::disk('public')->delete($user->foto);
                    }
                    $user->delete();
                }
            }

            DB::commit();

            return redirect()->route('master-data-perusahaan.index')
                ->with('success', 'Data perusahaan dan akses login berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting perusahaan: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
