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
        $query = User::with(['jobRole', 'perusahaan']);

        if ($request->filled('role'))     $query->where('role', $request->role);
        if ($request->filled('job_role')) $query->where('id_job_role', $request->job_role);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama',  'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_hp',  'like', "%{$search}%")
                    ->orWhereHas('jobRole', fn($q) => $q->where('nama_job_role', 'like', "%{$search}%"));
            });
        }

        $jobRoles   = JobRole::where('status', true)->orderBy('nama_job_role')->get();
        $totalCount = User::count();
        $allCounts  = $this->getJobRoleCounts($jobRoles);

        $query->latest('updated_at');
        $users = $query->get();

        foreach ($users as $user) {
            $user->jobRoleColor = $user->jobRole
                ? $this->getJobRoleColor($user->jobRole->nama_job_role)
                : 'purple-1';
        }

        return view('dashboard.master-data-users', compact('users', 'jobRoles', 'totalCount', 'allCounts'));
    }

    /* ─────────────────────────────────────────────────────────────────
     | Tentukan id_job_role otomatis berdasarkan role:
     |   klien    → null           (klien tidak punya job role)
     |   admin    → job role 'Admin'
     |   PM       → job role 'Project Manager'
     |   karyawan → pakai pilihan user dari form (wajib diisi)
     ───────────────────────────────────────────────────────────────── */
    private function resolveJobRole(string $role, $formJobRoleId): ?int
    {
        if ($role === 'klien') return null;

        if ($role === 'karyawan') return $formJobRoleId ?: null;

        $namaCari = match ($role) {
            'admin' => 'Admin',
            'PM'    => 'Project Manager',
            default => null,
        };

        if (!$namaCari) return null;

        // Cari job role, buat baru jika belum ada
        $jr = JobRole::whereRaw('LOWER(nama_job_role) = ?', [strtolower($namaCari)])->first();
        if (!$jr) {
            $jr = JobRole::create(['nama_job_role' => $namaCari, 'status' => true]);
        }

        return $jr->id_job_role;
    }

    private function getJobRoleCounts($jobRoles)
    {
        $counts = User::select('id_job_role', DB::raw('count(*) as count'))
            ->whereNotNull('id_job_role')
            ->groupBy('id_job_role')
            ->pluck('count', 'id_job_role');

        return $jobRoles->map(fn($jr) => [
            'id'    => $jr->id_job_role,
            'name'  => $jr->nama_job_role,
            'count' => $counts[$jr->id_job_role] ?? 0,
            'color' => $this->getJobRoleColor($jr->nama_job_role),
        ])->values()->toArray();
    }

    private function getJobRoleColor(string $jobRoleName): string
    {
        $colors = ['purple-1', 'purple-2', 'purple-3', 'purple-4'];
        return $colors[abs(crc32($jobRoleName)) % count($colors)];
    }

    /* ─────────────────────────────────────────────────────────────────
     | STORE
     ───────────────────────────────────────────────────────────────── */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:8',
            'role'        => 'required|in:admin,PM,karyawan,klien',  // ✅ PM uppercase
            'id_job_role' => 'nullable|exists:job_roles,id_job_role',
            'no_hp'       => 'nullable|string|max:20',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'      => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')->store('users', 'public');
            }

            $validated['password']    = Hash::make($validated['password']);
            $validated['id_job_role'] = $this->resolveJobRole(
                $validated['role'],
                $validated['id_job_role'] ?? null
            );

            User::create($validated);
            DB::commit();

            return redirect()->route('master-data-users.index')
                ->with('success', 'Data user berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($validated['foto'])) Storage::disk('public')->delete($validated['foto']);
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /* ─────────────────────────────────────────────────────────────────
     | UPDATE
     ───────────────────────────────────────────────────────────────── */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Klien tidak bisa ganti role
        if ($user->role === 'klien') {
            $request->merge(['role' => 'klien']);
        }

        $validated = $request->validate([
            'nama'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email,' . $id . ',id_user',
            'password'    => 'nullable|min:8',
            'role'        => 'required|in:admin,PM,karyawan,klien',  // ✅ PM uppercase
            'id_job_role' => 'nullable|exists:job_roles,id_job_role',
            'no_hp'       => 'nullable|string|max:20',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'      => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            $oldPhoto   = $user->foto;
            $statusLama = $user->status;
            $statusBaru = (bool) $validated['status'];

            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')->store('users', 'public');
                if ($oldPhoto) Storage::disk('public')->delete($oldPhoto);
            }

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            // Klien: jangan ubah id_job_role sama sekali (biarkan nilai DB apa adanya)
            // Admin/PM: auto-assign job role sesuai role
            // Karyawan: pakai pilihan dari form
            if ($validated['role'] === 'klien') {
                unset($validated['id_job_role']); // skip kolom ini, tidak di-update
            } else {
                $validated['id_job_role'] = $this->resolveJobRole(
                    $validated['role'],
                    $validated['id_job_role'] ?? null
                );
            }

            $user->update($validated);

            // Sinkronisasi status ke perusahaan terkait (klien)
            if ($user->role === 'klien' && $statusLama !== $statusBaru) {
                $perusahaan = Perusahaan::where('id_user_perusahaan', $user->id_user)->first();
                if ($perusahaan) {
                    Perusahaan::withoutEvents(fn() => $perusahaan->update(['status' => $statusBaru]));
                }
            }

            DB::commit();

            $pesanStatus = '';
            if ($statusLama !== $statusBaru) {
                $label       = $statusBaru ? 'Aktif' : 'Non-Aktif';
                $pesanStatus = " Status diubah menjadi {$label}.";
                if ($user->role === 'klien') $pesanStatus .= ' Status perusahaan terkait juga diperbarui.';
            }

            return redirect()->route('master-data-users.index')
                ->with('success', 'Data user berhasil diperbarui!' . $pesanStatus);
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($validated['foto']) && $validated['foto'] !== ($oldPhoto ?? null)) {
                Storage::disk('public')->delete($validated['foto']);
            }
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /* ─────────────────────────────────────────────────────────────────
     | DESTROY
     ───────────────────────────────────────────────────────────────── */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);

            if ($user->role === 'klien') {
                $perusahaan = Perusahaan::where('id_user_perusahaan', $user->id_user)->first();
                if ($perusahaan) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'User tidak dapat dihapus karena masih terkait perusahaan. ' .
                            'Hapus melalui Master Data Perusahaan.');
                }
            }

            if ($user->foto) Storage::disk('public')->delete($user->foto);
            $user->delete();
            DB::commit();

            return redirect()->route('master-data-users.index')
                ->with('success', 'Data user berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
