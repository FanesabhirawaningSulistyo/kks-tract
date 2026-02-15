<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Projek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tugas::with(['projek', 'penanggungJawab']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_tugas', 'like', "%{$search}%")
                    ->orWhere('deskripsi_tugas', 'like', "%{$search}%")
                    ->orWhereHas('projek', function ($q) use ($search) {
                        $q->where('nama_projek', 'like', "%{$search}%");
                    })
                    ->orWhereHas('penanggungJawab', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        // Sorting - default by updated time descending (newest first)
        $sortBy = $request->get('sort_by', 'diubah_pada');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $tugas = $query->paginate($perPage);

        // Get data for dropdowns
        $projeks = Projek::orderBy('nama_projek')->get();
        $users = User::orderBy('nama')->get();

        return view('dashboard.master-data-tugas', compact('tugas', 'projeks', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_projek' => 'required|exists:projek,id_projek',
            'judul_tugas' => 'required|string|max:150',
            'deskripsi_tugas' => 'nullable|string',
            'level' => 'required|in:mudah,medium,susah',
            'weight' => 'required|integer|min:1',
            'penanggung_jawab' => 'required|exists:users,id_user',
            'status' => 'required|in:draft,publis,progres,done',
            'tenggat_waktu' => 'nullable|date'
        ], [
            'id_projek.required' => 'Proyek wajib dipilih',
            'id_projek.exists' => 'Proyek tidak valid',
            'judul_tugas.required' => 'Judul tugas wajib diisi',
            'judul_tugas.max' => 'Judul tugas maksimal 150 karakter',
            'level.required' => 'Level tugas wajib dipilih',
            'level.in' => 'Level tugas tidak valid',
            'weight.required' => 'Weight wajib diisi',
            'weight.integer' => 'Weight harus berupa angka',
            'weight.min' => 'Weight minimal 1',
            'penanggung_jawab.required' => 'Penanggung jawab wajib dipilih',
            'penanggung_jawab.exists' => 'Penanggung jawab tidak valid',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status tidak valid',
            'tenggat_waktu.date' => 'Format tanggal tidak valid'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        try {
            Tugas::create($request->all());

            return redirect()->route('master-data-tugas.index')
                ->with('success', 'Data tugas berhasil ditambahkan!');
        } catch (\Exception $e) {
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
        $tugas = Tugas::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'id_projek' => 'required|exists:projek,id_projek',
            'judul_tugas' => 'required|string|max:150',
            'deskripsi_tugas' => 'nullable|string',
            'level' => 'required|in:mudah,medium,susah',
            'weight' => 'required|integer|min:1',
            'penanggung_jawab' => 'required|exists:users,id_user',
            'status' => 'required|in:draft,publis,progres,done',
            'tenggat_waktu' => 'nullable|date'
        ], [
            'id_projek.required' => 'Proyek wajib dipilih',
            'id_projek.exists' => 'Proyek tidak valid',
            'judul_tugas.required' => 'Judul tugas wajib diisi',
            'judul_tugas.max' => 'Judul tugas maksimal 150 karakter',
            'level.required' => 'Level tugas wajib dipilih',
            'level.in' => 'Level tugas tidak valid',
            'weight.required' => 'Weight wajib diisi',
            'weight.integer' => 'Weight harus berupa angka',
            'weight.min' => 'Weight minimal 1',
            'penanggung_jawab.required' => 'Penanggung jawab wajib dipilih',
            'penanggung_jawab.exists' => 'Penanggung jawab tidak valid',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status tidak valid',
            'tenggat_waktu.date' => 'Format tanggal tidak valid'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        try {
            $tugas->update($request->all());

            return redirect()->route('master-data-tugas.index')
                ->with('success', 'Data tugas berhasil diperbarui!');
        } catch (\Exception $e) {
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
            $tugas = Tugas::findOrFail($id);
            $tugas->delete();

            return redirect()->route('master-data-tugas.index')
                ->with('success', 'Data tugas berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
