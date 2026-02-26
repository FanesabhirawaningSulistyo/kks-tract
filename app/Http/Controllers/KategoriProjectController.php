<?php

namespace App\Http\Controllers;

use App\Models\KategoriProjek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = KategoriProjek::withCount('projek');

        $this->applyFilters($query, $request);

        $totalCount    = KategoriProjek::count();
        $activeCount   = KategoriProjek::where('status', true)->count();
        $inactiveCount = KategoriProjek::where('status', false)->count();

        $query->latest('diperbarui_pada');

        $perPage        = $request->get('per_page', 10);
        $kategoriProjek = $query->paginate($perPage);

        return view('dashboard.master-data-kategori-projek', compact(
            'kategoriProjek',
            'totalCount',
            'activeCount',
            'inactiveCount'
        ));
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kategori', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_projek,nama_kategori',
            'deskripsi'     => 'nullable|string',
            'status'        => 'required|boolean',
        ], [
            'nama_kategori.required' => 'Nama kategori projek wajib diisi',
            'nama_kategori.max'      => 'Nama kategori projek maksimal 100 karakter',
            'nama_kategori.unique'   => 'Nama kategori projek sudah terdaftar',
            'status.required'        => 'Status wajib dipilih',
        ]);

        try {
            DB::beginTransaction();

            KategoriProjek::create([
                'nama_kategori' => $validated['nama_kategori'],
                'deskripsi'     => $validated['deskripsi'] ?? null,
                'status'        => $validated['status'],
            ]);

            DB::commit();

            return redirect()
                ->route('master-data-kategori-projek.index')
                ->with('success', 'Data kategori projek berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $kategoriProjek = KategoriProjek::findOrFail($id);

        $validated = $request->validate([
            // ✅ kolom PK yang benar: id_kategori_projek
            'nama_kategori' => 'required|string|max:100|unique:kategori_projek,nama_kategori,' . $id . ',id_kategori_projek',
            'deskripsi'     => 'nullable|string',
            'status'        => 'required|boolean',
        ], [
            'nama_kategori.required' => 'Nama kategori projek wajib diisi',
            'nama_kategori.max'      => 'Nama kategori projek maksimal 100 karakter',
            'nama_kategori.unique'   => 'Nama kategori projek sudah terdaftar',
            'status.required'        => 'Status wajib dipilih',
        ]);

        try {
            DB::beginTransaction();

            $kategoriProjek->update([
                'nama_kategori'   => $validated['nama_kategori'],
                'deskripsi'       => $validated['deskripsi'] ?? null,
                'status'          => $validated['status'],
                'diperbarui_pada' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('master-data-kategori-projek.index')
                ->with('success', 'Data kategori projek berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $kategoriProjek = KategoriProjek::withCount('projek')->findOrFail($id);

            if ($kategoriProjek->projek_count > 0) {
                return redirect()->back()
                    ->with('error', "Kategori projek tidak dapat dihapus karena masih memiliki {$kategoriProjek->projek_count} projek!");
            }

            $kategoriProjek->delete();
            DB::commit();

            return redirect()
                ->route('master-data-kategori-projek.index')
                ->with('success', 'Data kategori projek berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
