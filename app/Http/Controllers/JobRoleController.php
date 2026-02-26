<?php

namespace App\Http\Controllers;

use App\Models\JobRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class JobRoleController extends Controller
{
    /**
     * Display a listing of the job roles.
     */
    public function index(Request $request)
    {
        $query = JobRole::withCount('users'); // Add user count relationship

        // Apply filters
        $this->applyFilters($query, $request);

        // Get counts
        $totalCount = JobRole::count();
        $activeCount = JobRole::where('status', true)->count();
        $inactiveCount = JobRole::where('status', false)->count();

        // Sort by recent activity
        $query->latest('diperbarui_pada');

        // Paginate
        $perPage = $request->get('per_page', 10);
        $jobRoles = $query->paginate($perPage);

        return view('dashboard.master-data-jobrole', compact(
            'jobRoles',
            'totalCount',
            'activeCount',
            'inactiveCount'
        ));
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters($query, Request $request)
    {
        // Status filter
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_job_role', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
    }

    /**
     * Store a newly created job role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_job_role' => 'required|string|max:100|unique:job_roles,nama_job_role',
            'deskripsi' => 'nullable|string',
            'status' => 'required|boolean'
        ], [
            'nama_job_role.required' => 'Nama job role wajib diisi',
            'nama_job_role.max' => 'Nama job role maksimal 100 karakter',
            'nama_job_role.unique' => 'Nama job role sudah terdaftar',
            'status.required' => 'Status wajib dipilih'
        ]);

        try {
            DB::beginTransaction();

            JobRole::create([
                'nama_job_role' => $validated['nama_job_role'],
                'deskripsi' => $validated['deskripsi'],
                'status' => $validated['status'],
                'dibuat_pada' => now(),
                'diperbarui_pada' => now()
            ]);

            DB::commit();

            return redirect()
                ->route('master-data-jobrole.index')
                ->with('success', 'Data job role berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified job role.
     */
    public function update(Request $request, $id)
    {
        $jobRole = JobRole::withCount('users')->findOrFail($id);

        $validated = $request->validate([
            'nama_job_role' => 'required|string|max:100|unique:job_roles,nama_job_role,' . $id . ',id_job_role',
            'deskripsi' => 'nullable|string',
            'status' => 'required|boolean'
        ], [
            'nama_job_role.required' => 'Nama job role wajib diisi',
            'nama_job_role.max' => 'Nama job role maksimal 100 karakter',
            'nama_job_role.unique' => 'Nama job role sudah terdaftar',
            'status.required' => 'Status wajib dipilih'
        ]);

        try {
            DB::beginTransaction();

            // CEK VALIDASI: Jika job role memiliki karyawan dan ingin dinonaktifkan
            if ($jobRole->users_count > 0 && $validated['status'] == 0) {
                throw ValidationException::withMessages([
                    'status' => "Job role tidak dapat dinonaktifkan karena masih memiliki {$jobRole->users_count} karyawan yang terikat!"
                ]);
            }

            $jobRole->update([
                'nama_job_role' => $validated['nama_job_role'],
                'deskripsi' => $validated['deskripsi'],
                'status' => $validated['status'],
                'diperbarui_pada' => now()
            ]);

            DB::commit();

            return redirect()
                ->route('master-data-jobrole.index')
                ->with('success', 'Data job role berhasil diperbarui!');
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified job role.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $jobRole = JobRole::withCount('users')->findOrFail($id);

            // CEK VALIDASI: Jika job role memiliki karyawan, tidak bisa dihapus
            if ($jobRole->users_count > 0) {
                return redirect()
                    ->back()
                    ->with('error', "Job role tidak dapat dihapus karena masih memiliki {$jobRole->users_count} karyawan yang terikat! Silahkan pindahkan atau hapus karyawan tersebut terlebih dahulu.");
            }

            $jobRole->delete();

            DB::commit();

            return redirect()
                ->route('master-data-jobrole.index')
                ->with('success', 'Data job role berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get job roles for dropdown (API endpoint)
     */
    public function getForDropdown(Request $request)
    {
        $query = JobRole::where('status', true);

        if ($request->filled('search')) {
            $query->where('nama_job_role', 'like', '%' . $request->search . '%');
        }

        $jobRoles = $query->orderBy('nama_job_role')
            ->get(['id_job_role', 'nama_job_role']);

        return response()->json($jobRoles);
    }

    /**
     * Check if job role has employees
     */
    public function checkEmployees($id)
    {
        $jobRole = JobRole::withCount('users')->findOrFail($id);

        return response()->json([
            'has_employees' => $jobRole->users_count > 0,
            'count' => $jobRole->users_count
        ]);
    }
}
