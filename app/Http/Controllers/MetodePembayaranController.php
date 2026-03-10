<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MetodePembayaranController extends Controller
{
    /**
     * Display a listing of metode pembayaran.
     */
    public function index(Request $request)
    {
        $query = MetodePembayaran::withCount('pembayaranProjek');

        $this->applyFilters($query, $request);

        $totalCount    = MetodePembayaran::count();
        $activeCount   = MetodePembayaran::where('status', 'aktif')->count();
        $inactiveCount = MetodePembayaran::where('status', 'nonaktif')->count();

        $query->latest('diperbarui_pada');

        $perPage           = $request->get('per_page', 10);
        $metodePembayarans = $query->paginate($perPage);

        return view('dashboard.master-data-metode-pembayaran', compact(
            'metodePembayarans',
            'totalCount',
            'activeCount',
            'inactiveCount'
        ));
    }

    /**
     * Apply filters to the query.
     */
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('status') && $request->status !== '') {
            $statusMap = ['1' => 'aktif', '0' => 'nonaktif'];
            $statusVal = $statusMap[$request->status] ?? $request->status;
            $query->where('status', $statusVal);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_metode', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
    }

    /**
     * Store a newly created metode pembayaran.
     */
    public function store(Request $request)
    {
        // FIX: validasi status pakai 'in:aktif,nonaktif' bukan 'boolean'
        $validated = $request->validate([
            'nama_metode' => 'required|string|max:100|unique:metode_pembayaran,nama_metode',
            'deskripsi'   => 'nullable|string',
            'status'      => 'required|in:aktif,nonaktif',
        ], [
            'nama_metode.required' => 'Nama metode pembayaran wajib diisi',
            'nama_metode.max'      => 'Nama metode pembayaran maksimal 100 karakter',
            'nama_metode.unique'   => 'Nama metode pembayaran sudah terdaftar',
            'status.required'      => 'Status wajib dipilih',
            'status.in'            => 'Status tidak valid',
        ]);

        try {
            DB::beginTransaction();

            MetodePembayaran::create([
                'nama_metode'     => $validated['nama_metode'],
                'deskripsi'       => $validated['deskripsi'] ?? null,
                'status'          => $validated['status'],   // langsung simpan 'aktif'/'nonaktif'
                'dibuat_pada'     => now(),
                'diperbarui_pada' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('master-data-metode-pembayaran.index')
                ->with('success', 'Data metode pembayaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified metode pembayaran.
     */
    public function update(Request $request, $id)
    {
        $metodePembayaran = MetodePembayaran::withCount('pembayaranProjek')->findOrFail($id);

        // FIX: validasi status pakai 'in:aktif,nonaktif' bukan 'boolean'
        $validated = $request->validate([
            'nama_metode' => 'required|string|max:100|unique:metode_pembayaran,nama_metode,' . $id . ',id_metode_pembayaran',
            'deskripsi'   => 'nullable|string',
            'status'      => 'required|in:aktif,nonaktif',
        ], [
            'nama_metode.required' => 'Nama metode pembayaran wajib diisi',
            'nama_metode.max'      => 'Nama metode pembayaran maksimal 100 karakter',
            'nama_metode.unique'   => 'Nama metode pembayaran sudah terdaftar',
            'status.required'      => 'Status wajib dipilih',
            'status.in'            => 'Status tidak valid',
        ]);

        try {
            DB::beginTransaction();

            // FIX: bandingkan dengan string 'nonaktif', bukan == 0
            if ($metodePembayaran->pembayaranProjek_count > 0 && $validated['status'] === 'nonaktif') {
                throw ValidationException::withMessages([
                    'status' => "Metode pembayaran tidak dapat dinonaktifkan karena masih memiliki {$metodePembayaran->pembayaranProjek_count} projek yang terikat!",
                ]);
            }

            $metodePembayaran->update([
                'nama_metode'     => $validated['nama_metode'],
                'deskripsi'       => $validated['deskripsi'] ?? null,
                'status'          => $validated['status'],   // langsung simpan 'aktif'/'nonaktif'
                'diperbarui_pada' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('master-data-metode-pembayaran.index')
                ->with('success', 'Data metode pembayaran berhasil diperbarui!');
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
     * Remove the specified metode pembayaran.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $metodePembayaran = MetodePembayaran::withCount('pembayaranProjek')->findOrFail($id);

            if ($metodePembayaran->pembayaranProjek_count > 0) {
                return redirect()
                    ->back()
                    ->with('error', "Metode pembayaran tidak dapat dihapus karena masih memiliki {$metodePembayaran->pembayaranProjek_count} projek yang terikat! Silahkan pindahkan atau hapus projek tersebut terlebih dahulu.");
            }

            $metodePembayaran->delete();

            DB::commit();

            return redirect()
                ->route('master-data-metode-pembayaran.index')
                ->with('success', 'Data metode pembayaran berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get metode pembayaran for dropdown (API endpoint).
     */
    public function getForDropdown(Request $request)
    {
        // FIX: gunakan string 'aktif' bukan boolean true
        $query = MetodePembayaran::where('status', 'aktif');

        if ($request->filled('search')) {
            $query->where('nama_metode', 'like', '%' . $request->search . '%');
        }

        $metodePembayaran = $query->orderBy('nama_metode')
            ->get(['id_metode_pembayaran', 'nama_metode']);

        return response()->json($metodePembayaran);
    }

    /**
     * Check if metode pembayaran has projek.
     */
    public function checkProjek($id)
    {
        $metodePembayaran = MetodePembayaran::withCount('pembayaranProjek')->findOrFail($id);

        return response()->json([
            'has_projek' => $metodePembayaran->pembayaranProjek_count > 0,
            'count'      => $metodePembayaran->pembayaranProjek_count,
        ]);
    }
}
