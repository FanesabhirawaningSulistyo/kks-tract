<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JobRole;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('jobRole');

        // Apply filters
        $this->applyFilters($query, $request);

        // Get job roles data
        $jobRoles = JobRole::where('status', true)
            ->orderBy('nama_job_role')
            ->get();

        // Get counts
        $totalCount = User::count();
        $allCounts = $this->getJobRoleCounts($jobRoles);

        // Sort by recent activity
        $query->latest('updated_at');

        // Paginate
        $perPage = $request->get('per_page', 10);
        $users = $query->paginate($perPage);

        // Add color to each user's job role
        foreach ($users as $user) {
            if ($user->jobRole) {
                $user->jobRoleColor = $this->getJobRoleColor($user->jobRole->nama_job_role);
            }
            // PENTING: Pastikan relasi jobRole di-load untuk JSON
            $user->load('jobRole');
        }

        return view('dashboard.master-data-users', compact(
            'users',
            'jobRoles',
            'totalCount',
            'allCounts'
        ));
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('job_role')) {
            $query->where('id_job_role', $request->job_role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhereHas('jobRole', function ($q) use ($search) {
                        $q->where('nama_job_role', 'like', "%{$search}%");
                    });
            });
        }
    }

    private function getJobRoleCounts($jobRoles)
    {
        $counts = User::select('id_job_role', DB::raw('count(*) as count'))
            ->whereNotNull('id_job_role')
            ->groupBy('id_job_role')
            ->pluck('count', 'id_job_role');

        return $jobRoles->map(function ($jobRole) use ($counts) {
            return [
                'id' => $jobRole->id_job_role,
                'name' => $jobRole->nama_job_role,
                'count' => $counts[$jobRole->id_job_role] ?? 0,
                'color' => $this->getJobRoleColor($jobRole->nama_job_role)
            ];
        })->toArray();
    }

    private function getJobRoleColor($jobRoleName)
    {
        $jobRoleLower = strtolower($jobRoleName);

        $colorMap = [
            'info' => ['web', 'developer', 'frontend', 'backend'],
            'warning' => ['seo', 'marketing', 'social media', 'content'],
            'primary' => ['design', 'ui', 'ux', 'graphic'],
            'success' => ['project', 'manager', 'pm'],
            'secondary' => ['admin', 'support', 'helpdesk']
        ];

        foreach ($colorMap as $color => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($jobRoleLower, $keyword)) {
                    return $color;
                }
            }
        }

        $colors = ['primary', 'success', 'info', 'warning', 'danger'];
        return $colors[crc32($jobRoleName) % count($colors)];
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,karyawan,PM,klien',
            'id_job_role' => 'nullable|exists:job_roles,id_job_role',
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|boolean'
        ], [
            'nama.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'role.required' => 'Role wajib dipilih',
            'status.required' => 'Status wajib dipilih',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'foto.max' => 'Ukuran gambar maksimal 2MB'
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')->store('users', 'public');
            }

            $validated['password'] = Hash::make($validated['password']);

            if ($validated['role'] === 'klien') {
                $validated['id_job_role'] = null;
            }

            User::create($validated);

            DB::commit();

            return redirect()
                ->route('master-data-users.index')
                ->with('success', 'Data user berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($validated['foto'])) {
                Storage::disk('public')->delete($validated['foto']);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id . ',id_user',
            'password' => 'nullable|min:8',
            'role' => 'required|in:admin,karyawan,PM,klien',
            'id_job_role' => 'nullable|exists:job_roles,id_job_role',
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|boolean'
        ], [
            'nama.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 8 karakter',
            'role.required' => 'Role wajib dipilih',
            'status.required' => 'Status wajib dipilih',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'foto.max' => 'Ukuran gambar maksimal 2MB'
        ]);

        try {
            DB::beginTransaction();

            $oldPhoto = $user->foto;

            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')->store('users', 'public');
                if ($oldPhoto) {
                    Storage::disk('public')->delete($oldPhoto);
                }
            }

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            if ($validated['role'] === 'klien') {
                $validated['id_job_role'] = null;
            }

            $user->update($validated);

            DB::commit();

            return redirect()
                ->route('master-data-users.index')
                ->with('success', 'Data user berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($validated['foto']) && $validated['foto'] !== $oldPhoto) {
                Storage::disk('public')->delete($validated['foto']);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            // PENGECEKAN BARU: Jika role klien dan memiliki relasi perusahaan
            if ($user->role === 'klien') {
                // Cek apakah user ini adalah perwakilan perusahaan
                $perusahaan = Perusahaan::where('id_user_perwakilan', $user->id_user)->first();

                if ($perusahaan) {
                    return redirect()
                        ->back()
                        ->with('error', 'User dengan role klien yang terkait dengan perusahaan tidak dapat dihapus secara langsung. Harap hapus melalui Master Data Perusahaan.');
                }
            }

            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }

            $user->delete();

            DB::commit();

            return redirect()
                ->route('master-data-users.index')
                ->with('success', 'Data user berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
