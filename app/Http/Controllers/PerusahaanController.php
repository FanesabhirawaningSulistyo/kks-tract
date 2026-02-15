<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\JobRole;
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
                // Search di data perusahaan
                $q->where('nama_perwakilan', 'like', "%{$search}%")
                    ->orWhere('email_perwakilan', 'like', "%{$search}%")
                    ->orWhere('telepon_perwakilan', 'like', "%{$search}%")
                    ->orWhere('alamat_perusahaan', 'like', "%{$search}%")
                    // Search di user (data perusahaan)
                    ->orWhereHas('userPerusahaan', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%") // nama perusahaan
                            ->orWhere('email', 'like', "%{$search}%") // email perusahaan
                            ->orWhere('no_hp', 'like', "%{$search}%"); // telepon perusahaan
                    });
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'diperbarui_pada');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $perusahaans = $query->paginate($perPage);

        return view('dashboard.master-data-perusahaan', compact('perusahaans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Data Perusahaan (yang akan masuk ke tabel users)
            'nama_perusahaan' => 'required|string|max:100',
            'email_perusahaan' => 'required|email|max:100|unique:users,email',
            'telepon_perusahaan' => 'nullable|string|max:20',
            'password_perusahaan' => 'required|string|min:8',

            // Data Perwakilan (yang akan masuk ke tabel perusahaan)
            'nama_perwakilan' => 'required|string|max:100',
            'email_perwakilan' => 'required|email|max:100|unique:perusahaan,email_perwakilan',
            'telepon_perwakilan' => 'nullable|string|max:20',

            // Data lainnya
            'alamat_perusahaan' => 'required|string',
            'logo_perusahaan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi',
            'email_perusahaan.required' => 'Email perusahaan wajib diisi',
            'email_perusahaan.email' => 'Format email perusahaan tidak valid',
            'email_perusahaan.unique' => 'Email perusahaan sudah terdaftar',
            'password_perusahaan.required' => 'Password wajib diisi',
            'password_perusahaan.min' => 'Password minimal 8 karakter',
            'nama_perwakilan.required' => 'Nama perwakilan wajib diisi',
            'email_perwakilan.required' => 'Email perwakilan wajib diisi',
            'email_perwakilan.email' => 'Format email perwakilan tidak valid',
            'email_perwakilan.unique' => 'Email perwakilan sudah terdaftar',
            'alamat_perusahaan.required' => 'Alamat perusahaan wajib diisi',
            'logo_perusahaan.image' => 'File harus berupa gambar',
            'logo_perusahaan.mimes' => 'Logo harus berformat jpeg, png, atau jpg',
            'logo_perusahaan.max' => 'Ukuran logo maksimal 2MB'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Get Job Role for Klien
            $klienJobRole = JobRole::where('nama_job_role', 'Klien')->first();

            // Handle logo upload
            $logoPath = null;
            if ($request->hasFile('logo_perusahaan')) {
                $logo = $request->file('logo_perusahaan');
                $logoName = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
                $logoPath = $logo->storeAs('logos', $logoName, 'public');
            }

            // 1. Create User (berisi data perusahaan)
            $userPerusahaan = User::create([
                'nama' => $request->nama_perusahaan,           // NAMA PERUSAHAAN
                'email' => $request->email_perusahaan,         // EMAIL PERUSAHAAN
                'password' => Hash::make($request->password_perusahaan),
                'role' => 'klien',
                'id_job_role' => $klienJobRole ? $klienJobRole->id_job_role : null,
                'no_hp' => $request->telepon_perusahaan,       // TELEPON PERUSAHAAN
                'foto' => null,
                'status' => true
            ]);

            // 2. Create Perusahaan (berisi data perwakilan dan alamat)
            $perusahaan = Perusahaan::create([
                'id_user_perusahaan' => $userPerusahaan->id_user,  // Relasi ke user
                'nama_perwakilan' => $request->nama_perwakilan,
                'email_perwakilan' => $request->email_perwakilan,
                'telepon_perwakilan' => $request->telepon_perwakilan,
                'logo_perusahaan' => $logoPath,
                'alamat_perusahaan' => $request->alamat_perusahaan
            ]);

            DB::commit();

            return redirect()->route('master-data-perusahaan.index')
                ->with('success', 'Data perusahaan dan akun berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded logo if exists
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
     */
    public function update(Request $request, $id)
    {
        $perusahaan = Perusahaan::with('userPerusahaan')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            // Data Perusahaan (di tabel users)
            'nama_perusahaan' => 'required|string|max:100',
            'email_perusahaan' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($perusahaan->id_user_perusahaan, 'id_user')
            ],
            'telepon_perusahaan' => 'nullable|string|max:20',
            'password_perusahaan' => 'nullable|string|min:8',

            // Data Perwakilan (di tabel perusahaan)
            'nama_perwakilan' => 'required|string|max:100',
            'email_perwakilan' => [
                'required',
                'email',
                'max:100',
                Rule::unique('perusahaan', 'email_perwakilan')->ignore($perusahaan->id_perusahaan, 'id_perusahaan')
            ],
            'telepon_perwakilan' => 'nullable|string|max:20',

            // Data lainnya
            'alamat_perusahaan' => 'required|string',
            'logo_perusahaan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi',
            'email_perusahaan.required' => 'Email perusahaan wajib diisi',
            'email_perusahaan.email' => 'Format email perusahaan tidak valid',
            'email_perusahaan.unique' => 'Email perusahaan sudah terdaftar',
            'password_perusahaan.min' => 'Password minimal 8 karakter',
            'nama_perwakilan.required' => 'Nama perwakilan wajib diisi',
            'email_perwakilan.required' => 'Email perwakilan wajib diisi',
            'email_perwakilan.email' => 'Format email perwakilan tidak valid',
            'email_perwakilan.unique' => 'Email perwakilan sudah terdaftar',
            'alamat_perusahaan.required' => 'Alamat perusahaan wajib diisi',
            'logo_perusahaan.image' => 'File harus berupa gambar',
            'logo_perusahaan.mimes' => 'Logo harus berformat jpeg, png, atau jpg',
            'logo_perusahaan.max' => 'Ukuran logo maksimal 2MB'
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
            $logoPath = $perusahaan->logo_perusahaan;
            if ($request->hasFile('logo_perusahaan')) {
                // Delete old logo if exists
                if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                    Storage::disk('public')->delete($logoPath);
                }

                $logo = $request->file('logo_perusahaan');
                $logoName = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
                $logoPath = $logo->storeAs('logos', $logoName, 'public');
            }

            // 1. Update User (data perusahaan)
            if ($perusahaan->userPerusahaan) {
                $userData = [
                    'nama' => $request->nama_perusahaan,           // NAMA PERUSAHAAN
                    'email' => $request->email_perusahaan,         // EMAIL PERUSAHAAN
                    'no_hp' => $request->telepon_perusahaan        // TELEPON PERUSAHAAN
                ];

                // Update password only if provided
                if ($request->filled('password_perusahaan')) {
                    $userData['password'] = Hash::make($request->password_perusahaan);
                }

                $perusahaan->userPerusahaan->update($userData);
            }

            // 2. Update Perusahaan (data perwakilan dan alamat)
            $perusahaan->update([
                'nama_perwakilan' => $request->nama_perwakilan,
                'email_perwakilan' => $request->email_perwakilan,
                'telepon_perwakilan' => $request->telepon_perwakilan,
                'logo_perusahaan' => $logoPath,
                'alamat_perusahaan' => $request->alamat_perusahaan
            ]);

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
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $perusahaan = Perusahaan::with('userPerusahaan')->findOrFail($id);

            // PENGECEKAN: Apakah perusahaan memiliki proyek terkait
            if ($perusahaan->projek()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Perusahaan tidak dapat dihapus karena masih memiliki proyek terkait!');
            }

            // Delete logo if exists
            if ($perusahaan->logo_perusahaan && Storage::disk('public')->exists($perusahaan->logo_perusahaan)) {
                Storage::disk('public')->delete($perusahaan->logo_perusahaan);
            }

            // Store user ID for deletion
            $userId = $perusahaan->id_user_perusahaan;

            // Delete perusahaan first (karena ada foreign key)
            $perusahaan->delete();

            // Delete user (data perusahaan) if exists
            if ($userId) {
                $user = User::find($userId);
                if ($user) {
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
