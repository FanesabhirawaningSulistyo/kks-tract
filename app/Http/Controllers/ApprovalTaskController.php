<?php

namespace App\Http\Controllers;

use App\Models\Projek;
use App\Models\Tugas;
use App\Models\User;
use App\Models\ProjekTim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalTaskController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isPM()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Tab: 'menunggu' | 'revisi' | 'riwayat'
        $tab = $request->get('tab', 'menunggu');

        $projekQuery = Projek::with(['pembuat']);
        if ($user->isPM()) {
            $projekQuery->where('dibuat_oleh', $user->id_user);
        }

        $semuaProjek = $projekQuery->orderBy('nama_projek')->get();
        $projekIds   = $semuaProjek->pluck('id_projek')->toArray();

        $tugasQuery = Tugas::with([
            'projek',
            'projek.perusahaan',
            'tim',
            'tim.user',
            'tim.user.jobRole',
            'foto',  // Pastikan relasi foto ada di model Tugas
        ])->whereIn('id_projek', $projekIds);

        if ($tab === 'menunggu') {
            $tugasQuery->where('status_progress', 'done')
                ->where('status_akhir', 'review');
        } elseif ($tab === 'revisi') {
            $tugasQuery->where('status_akhir', 'revisi');
        } else {
            // Riwayat: semua task yang status_akhir = approved
            $tugasQuery->where('status_akhir', 'approved');
        }

        $filterProjek = $request->get('id_projek');
        if ($filterProjek && $filterProjek !== 'all') {
            $tugasQuery->where('id_projek', $filterProjek);
        }

        $tugasList = $tugasQuery->orderBy('diubah_pada', 'desc')->get();

        // Badge counts
        $countMenunggu = Tugas::whereIn('id_projek', $projekIds)
            ->where('status_progress', 'done')->where('status_akhir', 'review')->count();

        $countRevisi = Tugas::whereIn('id_projek', $projekIds)
            ->where('status_akhir', 'revisi')->count();

        $countRiwayat = Tugas::whereIn('id_projek', $projekIds)
            ->where('status_akhir', 'approved')->count();

        return view('dashboard.approval-task', compact(
            'tugasList',
            'semuaProjek',
            'tab',
            'filterProjek',
            'countMenunggu',
            'countRevisi',
            'countRiwayat'
        ));
    }

    public function approve(Request $request, $id_tugas)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isPM()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }
        $tugas = Tugas::with('projek')->findOrFail($id_tugas);
        if ($user->isPM() && $tugas->projek->dibuat_oleh !== $user->id_user) {
            return response()->json(['success' => false, 'message' => 'Anda hanya bisa approve task dari project milik Anda.'], 403);
        }
        if ($tugas->status_progress !== 'done') {
            return response()->json(['success' => false, 'message' => 'Task harus berstatus Done sebelum di-Approve.'], 422);
        }
        $tugas->update(['status_akhir' => 'approved', 'pernah_approved' => true]);
        return response()->json([
            'success' => true,
            'message' => 'Task berhasil di-Approve.',
            'data'    => ['id_tugas' => $tugas->id_tugas, 'status_akhir' => 'approved', 'pernah_approved' => true],
        ]);
    }

    public function revisi(Request $request, $id_tugas)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isPM()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }
        $tugas = Tugas::with('projek')->findOrFail($id_tugas);
        if ($user->isPM() && $tugas->projek->dibuat_oleh !== $user->id_user) {
            return response()->json(['success' => false, 'message' => 'Anda hanya bisa merevisi task dari project milik Anda.'], 403);
        }
        $tugas->update([
            'status_akhir'    => 'revisi',
            'status_progress' => 'To Do',
            'tanggal_selesai' => null,
            // pernah_approved TIDAK disentuh
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Task dikembalikan untuk Revisi.',
            'data'    => ['id_tugas' => $tugas->id_tugas, 'status_akhir' => 'revisi', 'pernah_approved' => $tugas->pernah_approved],
        ]);
    }
}
