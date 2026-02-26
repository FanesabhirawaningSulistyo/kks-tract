<?php

namespace App\Http\Controllers;

use App\Models\Projek;
use App\Models\Perusahaan;
use App\Models\KategoriProjek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjekController extends Controller
{
    public function index(Request $request)
    {
        $query = Projek::with(['perusahaan', 'kategoriProjek', 'pembuat', 'tugas']);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_projek', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('perusahaan', function ($q2) use ($search) {
                        $q2->where('nama_perusahaan', 'like', "%{$search}%")
                            ->orWhere('nama_perwakilan', 'like', "%{$search}%");
                    });
            });
        }
        if ($request->filled('id_kategori_projek')) {
            $query->where('id_kategori_projek', $request->id_kategori_projek);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $sortBy    = $request->get('sort_by', 'dibuat_pada');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['nama_projek', 'status', 'tanggal_mulai', 'nominal_projek', 'dibuat_pada', 'dibuat_oleh'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'dibuat_pada';
        }
        if ($sortBy === 'kategori') {
            $query->leftJoin('kategori_projek', 'projek.id_kategori_projek', '=', 'kategori_projek.id_kategori_projek')
                ->orderBy('kategori_projek.nama_kategori', $sortOrder)
                ->select('projek.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
        if ($request->get('export') === '1') {
            return $this->exportExcel($query->get());
        }
        $perPage = in_array((int) $request->get('per_page'), [10, 25, 50, 100])
            ? (int) $request->get('per_page') : 10;
        $projeks = $query->paginate($perPage)->withQueryString();
        $kategoris   = KategoriProjek::orderBy('nama_kategori')->get();
        $perusahaans = Perusahaan::orderBy('nama_perwakilan')->get();
        $stats = [
            'total'       => Projek::count(),
            'pending'     => Projek::where('status', 'pending')->count(),
            'aktif'       => Projek::where('status', 'aktif')->count(),
            'in_progress' => Projek::where('status', 'in_progress')->count(),
            'selesai'     => Projek::where('status', 'selesai')->count(),
        ];
        return view('dashboard.master-data-projek', compact('projeks', 'stats', 'kategoris', 'perusahaans'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_projek'        => 'required|string|max:255',
            'id_perusahaan'      => 'required|exists:perusahaan,id_perusahaan',
            'id_kategori_projek' => 'nullable|exists:kategori_projek,id_kategori_projek',
            'status'             => 'required|in:pending,in_progress,aktif,selesai',
            'tanggal_mulai'      => 'nullable|date',
            'tanggal_selesai'    => 'nullable|date|after_or_equal:tanggal_mulai',
            'nominal_projek'     => 'required|numeric|min:0',
            'dokumen_perjanjian' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp|max:5120',
            'deskripsi'          => 'nullable|string',
        ]);
        $validated['sisa_tanggungan'] = $validated['nominal_projek'];
        $validated['dibuat_oleh']     = Auth::id();
        if ($request->hasFile('dokumen_perjanjian')) {
            $validated['dokumen_perjanjian'] = $request->file('dokumen_perjanjian')
                ->store('dokumen/projek', 'public');
        }
        Projek::create($validated);
        return redirect()->route('master-data-projek.index')
            ->with('success', 'Project berhasil ditambahkan.');
    }
    public function updateStatus(Request $request, $id)
    {
        $projek = Projek::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,in_progress,aktif,selesai',
        ]);
        $projek->update(['status' => $request->status]);
        return response()->json([
            'success' => true,
            'status'  => $projek->status,
            'message' => 'Status berhasil diperbarui.',
        ]);
    }
    public function update(Request $request, $id)
    {
        $projek = Projek::findOrFail($id);
        $validated = $request->validate([
            'nama_projek'        => 'required|string|max:255',
            'id_perusahaan'      => 'required|exists:perusahaan,id_perusahaan',
            'id_kategori_projek' => 'nullable|exists:kategori_projek,id_kategori_projek',
            'status'             => 'required|in:pending,in_progress,aktif,selesai',
            'tanggal_selesai'    => 'nullable|date',
            'nominal_projek'     => 'required|numeric|min:0',
            'dokumen_perjanjian' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp|max:5120',
            'deskripsi'          => 'nullable|string',
        ]);
        unset($validated['tanggal_mulai']);
        $selisih = $validated['nominal_projek'] - $projek->nominal_projek;
        $validated['sisa_tanggungan'] = max(0, $projek->sisa_tanggungan + $selisih);
        if ($request->hasFile('dokumen_perjanjian')) {
            if ($projek->dokumen_perjanjian) {
                Storage::disk('public')->delete($projek->dokumen_perjanjian);
            }
            $validated['dokumen_perjanjian'] = $request->file('dokumen_perjanjian')
                ->store('dokumen/projek', 'public');
        }
        $projek->update($validated);
        return redirect()->route('master-data-projek.index')
            ->with('success', 'Project berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $projek = Projek::findOrFail($id);
        if ($projek->dokumen_perjanjian) {
            Storage::disk('public')->delete($projek->dokumen_perjanjian);
        }
        $projek->delete();
        return redirect()->route('master-data-projek.index')
            ->with('success', 'Project berhasil dihapus.');
    }
    public function laporan($id)
    {
        $projek = Projek::with(['perusahaan', 'kategoriProjek', 'pembuat', 'tugas'])
            ->findOrFail($id);
        return response()->json([
            'message'  => 'Laporan project: ' . $projek->nama_projek,
            'progress' => $projek->progress_projek . '%',
        ]);
    }
    private function exportExcel($projeks)
    {
        $filename = 'projek_' . now()->format('Ymd_His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        $callback = function () use ($projeks) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'No',
                'Nama Project',
                'Perusahaan',
                'Kategori',
                'Status',
                'Tanggal Mulai',
                'Target Selesai',
                'Nominal',
                'Sisa Tanggungan',
                'Dibuat Oleh',
                'Progress (%)',
            ]);
            foreach ($projeks as $i => $p) {
                fputcsv($handle, [
                    $i + 1,
                    $p->nama_projek,
                    optional($p->perusahaan)->nama_perusahaan ?? '—',
                    optional($p->kategoriProjek)->nama_kategori ?? '—',
                    $p->status,
                    $p->tanggal_mulai?->format('d/m/Y') ?? '—',
                    $p->tanggal_selesai?->format('d/m/Y') ?? '—',
                    $p->nominal_projek,
                    $p->sisa_tanggungan,
                    optional($p->pembuat)->nama ?? '—',
                    $p->progress_projek,
                ]);
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }
}
