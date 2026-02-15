<?php

namespace App\Http\Controllers;

use App\Models\Projek;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjekController extends Controller
{
    /**
     * Display a listing of projects.
     */
    public function index(Request $request)
    {
        $query = Projek::with(['perusahaan', 'pembuat']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_projek', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('perusahaan', function($q) use ($search) {
                        $q->where('nama_perusahaan', 'like', "%{$search}%");
                    });
            });
        }

        // Sorting - prioritize recently updated OR created records
        $query->orderByRaw('GREATEST(dibuat_pada, diperbarui_pada) DESC');

        // Pagination
        $perPage = $request->get('per_page', 10);
        $projeks = $query->paginate($perPage);

        // Get perusahaan list for dropdown
        $perusahaans = Perusahaan::orderBy('nama_perusahaan')->get();

        return view('dashboard.master-data-project', compact('projeks', 'perusahaans'));
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id_perusahaan',
            'nama_projek' => 'required|string|max:150',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'tanggal_pesan' => 'required|date',
            'status' => 'required|in:pending,disetujui,berjalan,selesai,batal',
            'nominal_projek' => 'required|numeric|min:0',
            'sisa_tanggungan' => 'required|numeric|min:0',
            'dokumen_perjanjian' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        if ($request->hasFile('dokumen_perjanjian')) {
            $file = $request->file('dokumen_perjanjian');
            $originalName = $file->getClientOriginalName();
            $timestamp = time();
            $fileName = $timestamp . '_' . $originalName;
            $path = $file->storeAs('dokumen_projek', $fileName, 'public');
            $validated['dokumen_perjanjian'] = $path;  // ✅ BENAR: $validated
        }
        $validated['dibuat_oleh'] = Auth::id();

        Projek::create($validated);

        return redirect()->route('master-data-projek.index')
            ->with('success', 'Project berhasil ditambahkan!');
    }

    /**
     * Update the specified project.
     */
    public function update(Request $request, Projek $projek)
    {
        $validated = $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id_perusahaan',
            'nama_projek' => 'required|string|max:150',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'tanggal_pesan' => 'required|date',
            'status' => 'required|in:pending,disetujui,berjalan,selesai,batal',
            'nominal_projek' => 'required|numeric|min:0',
            'sisa_tanggungan' => 'required|numeric|min:0',
            'dokumen_perjanjian' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        if ($request->hasFile('dokumen_perjanjian')) {
            // Hapus file lama jika ada
            if ($projek->dokumen_perjanjian && Storage::disk('public')->exists($projek->dokumen_perjanjian)) {
                Storage::disk('public')->delete($projek->dokumen_perjanjian);
            }

            $file = $request->file('dokumen_perjanjian');
            $originalName = $file->getClientOriginalName();
            $timestamp = time();
            $fileName = $timestamp . '_' . $originalName;
            $path = $file->storeAs('dokumen_projek', $fileName, 'public');
            $validated['dokumen_perjanjian'] = $path;  // ✅ BENAR: $validated
        }

        $projek->update($validated);

        return redirect()->route('master-data-projek.index')
            ->with('success', 'Project berhasil diupdate!');
    }

    /**
     * Remove the specified project.
     */
    public function destroy(Projek $projek)
    {
        if ($projek->dokumen_perjanjian) {
            Storage::disk('public')->delete($projek->dokumen_perjanjian);
        }

        $projek->delete();

        return redirect()->route('master-data-projek.index')
            ->with('success', 'Project berhasil dihapus!');
    }
}