<?php

namespace App\Http\Controllers;

use App\Models\Projek;
use App\Models\PembayaranProjek;
use App\Models\MetodePembayaran;
use App\Models\KategoriProjek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PembayaranProjekController extends Controller
{
    public function index(Request $request)
    {
        $filterStatus = $request->get('filter_lunas', 'belum_lunas');
        $query = Projek::with(['perusahaan', 'kategoriProjek', 'pembuat', 'tugas']);
        if ($filterStatus === 'lunas') {
            $query->where('sisa_tanggungan', '<=', 0);
        } else {
            $query->where('sisa_tanggungan', '>', 0);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_projek', 'like', "%{$search}%")
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
        $query->orderBy('dibuat_pada', 'desc');
        $perPage   = in_array((int) $request->get('per_page'), [10, 25, 50, 100]) ? (int) $request->get('per_page') : 10;
        $projeks   = $query->paginate($perPage)->withQueryString();
        $projekIds = $projeks->pluck('id_projek')->toArray();
        $progressDb = DB::table('tugas')
            ->select(
                'id_projek',
                DB::raw('SUM(CASE WHEN status_progress != "draft" THEN weight ELSE 0 END) as total_weight'),
                DB::raw('SUM(CASE WHEN status_progress = "done" AND status_akhir = "approved" THEN weight ELSE 0 END) as approved_weight'),
                DB::raw('SUM(CASE WHEN status_progress != "draft" THEN 1 ELSE 0 END) as total_count'),
                DB::raw('SUM(CASE WHEN status_progress = "done" AND status_akhir = "approved" THEN 1 ELSE 0 END) as approved_count')
            )
            ->whereIn('id_projek', $projekIds)->groupBy('id_projek')->get()->keyBy('id_projek');
        $kategoris   = KategoriProjek::orderBy('nama_kategori')->get();
        $stats = [
            'total_projek'  => Projek::count(),
            'belum_lunas'   => Projek::where('sisa_tanggungan', '>', 0)->count(),
            'lunas'         => Projek::where('sisa_tanggungan', '<=', 0)->count(),
            'total_sisa'    => Projek::where('sisa_tanggungan', '>', 0)->sum('sisa_tanggungan'),
            'total_nominal' => Projek::sum('nominal_projek'),
        ];
        $projeksData = $projeks->map(function ($projek) use ($progressDb) {
            $prog = $progressDb->get($projek->id_projek);
            $tw = $prog ? (float) $prog->total_weight : 0;
            $aw = $prog ? (float) $prog->approved_weight : 0;
            $tc = $prog ? (int) $prog->total_count : 0;
            $ac = $prog ? (int) $prog->approved_count : 0;
            $pg = $tw > 0 ? round(($aw / $tw) * 100, 2) : ($tc > 0 ? round(($ac / $tc) * 100, 2) : 0);
            return [
                'id_projek'       => $projek->id_projek,
                'nama_projek'     => $projek->nama_projek,
                'perusahaan_nama' => optional($projek->perusahaan)->nama_perwakilan ?? '—',
                'perusahaan_pt'   => optional($projek->perusahaan)->nama_perusahaan ?? '',
                'kategori_nama'   => optional($projek->kategoriProjek)->nama_kategori ?? '—',
                'status'          => $projek->status,
                'nominal_projek'  => (float) $projek->nominal_projek,
                'sisa_tanggungan' => (float) $projek->sisa_tanggungan,
                'progress'        => $pg,
            ];
        })->values()->all();
        $metodes = MetodePembayaran::orderBy('nama_metode')->get();

        // ✅ ALL projects (tidak paginated) untuk laporan PDF — agar lunas & belum lunas semua muncul
        $allProjeks = Projek::with(['perusahaan', 'kategoriProjek'])->orderBy('dibuat_pada', 'desc')->get();
        $allProjekIds = $allProjeks->pluck('id_projek')->toArray();
        $allProgressDb = DB::table('tugas')
            ->select(
                'id_projek',
                DB::raw('SUM(CASE WHEN status_progress != "draft" THEN weight ELSE 0 END) as total_weight'),
                DB::raw('SUM(CASE WHEN status_progress = "done" AND status_akhir = "approved" THEN weight ELSE 0 END) as approved_weight'),
                DB::raw('SUM(CASE WHEN status_progress != "draft" THEN 1 ELSE 0 END) as total_count'),
                DB::raw('SUM(CASE WHEN status_progress = "done" AND status_akhir = "approved" THEN 1 ELSE 0 END) as approved_count')
            )
            ->whereIn('id_projek', $allProjekIds)->groupBy('id_projek')->get()->keyBy('id_projek');
        $allProjeksData = $allProjeks->map(function ($projek) use ($allProgressDb) {
            $prog = $allProgressDb->get($projek->id_projek);
            $tw = $prog ? (float) $prog->total_weight : 0;
            $aw = $prog ? (float) $prog->approved_weight : 0;
            $tc = $prog ? (int) $prog->total_count : 0;
            $ac = $prog ? (int) $prog->approved_count : 0;
            $pg = $tw > 0 ? round(($aw / $tw) * 100, 2) : ($tc > 0 ? round(($ac / $tc) * 100, 2) : 0);
            return [
                'id_projek'       => $projek->id_projek,
                'nama_projek'     => $projek->nama_projek,
                'perusahaan_nama' => optional($projek->perusahaan)->nama_perwakilan ?? '—',
                'perusahaan_pt'   => optional($projek->perusahaan)->nama_perusahaan ?? '',
                'kategori_nama'   => optional($projek->kategoriProjek)->nama_kategori ?? '—',
                'status'          => $projek->status,
                'nominal_projek'  => (float) $projek->nominal_projek,
                'sisa_tanggungan' => (float) $projek->sisa_tanggungan,
                'progress'        => $pg,
            ];
        })->values()->all();

        $monthlyPayments = DB::table('pembayaran_projek')
            ->select(
                DB::raw('YEAR(tanggal_bayar) as year'),
                DB::raw('MONTH(tanggal_bayar) as month'),
                DB::raw('SUM(jumlah_bayar) as total')
            )
            ->where('status', 'valid')
            ->groupBy(DB::raw('YEAR(tanggal_bayar)'), DB::raw('MONTH(tanggal_bayar)'))
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->toArray();

        // ✅ Detailed payments untuk laporan pendapatan
        $detailedPayments = PembayaranProjek::with(['projek.perusahaan', 'petugas', 'metode'])
            ->where('status', 'valid')
            ->orderBy('tanggal_bayar', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id_pembayaran'   => $item->id_pembayaran,
                    'kode_pembayaran' => $item->kode_pembayaran,
                    'jumlah_bayar'    => (float) $item->jumlah_bayar,
                'tanggal_bayar' => $item->tanggal_bayar ? \Carbon\Carbon::parse($item->tanggal_bayar)->format('Y-m-d') : null,
                    'nama_projek'     => optional($item->projek)->nama_projek ?? '—',
                    'nominal_projek'  => (float) optional($item->projek)->nominal_projek ?? 0,
                    'nama_perusahaan' => optional(optional($item->projek)->perusahaan)->nama_perusahaan ?? '—',
                    'nama_perwakilan' => optional(optional($item->projek)->perusahaan)->nama_perwakilan ?? '—',
                    'nama_petugas'    => optional($item->petugas)->nama ?? '—',
                    'nama_metode'     => optional($item->metode)->nama_metode ?? '—',
                    'sisa_tanggungan' => (float) optional($item->projek)->sisa_tanggungan ?? 0,
                ];
            })->toArray();

        $logoUrl = asset('images/logo1.png');

        return view('dashboard.master-data-pembayaran', compact(
            'projeks',
            'stats',
            'kategoris',
            'projeksData',
            'allProjeksData',
            'metodes',
            'filterStatus',
            'monthlyPayments',
            'detailedPayments',
            'logoUrl'
        ));
    }

    public function show($id_projek)
    {
        $projek = Projek::with(['perusahaan', 'kategoriProjek'])->findOrFail($id_projek);
        $progressDb = DB::table('tugas')->where('id_projek', $id_projek)
            ->selectRaw('SUM(CASE WHEN status_progress != "draft" THEN weight ELSE 0 END) as total_weight,
                SUM(CASE WHEN status_progress = "done" AND status_akhir = "approved" THEN weight ELSE 0 END) as approved_weight')
            ->first();
        $tw = $progressDb ? (float) $progressDb->total_weight : 0;
        $aw = $progressDb ? (float) $progressDb->approved_weight : 0;
        $progress = $tw > 0 ? round(($aw / $tw) * 100, 2) : 0;
        $riwayatChronological = PembayaranProjek::with(['petugas', 'metode'])
            ->where('id_projek', $id_projek)
            ->orderBy('tanggal_bayar', 'asc')->orderBy('id_pembayaran', 'asc')->get();
        $nominalAwal  = (float) $projek->nominal_projek;
        $sisaBerjalan = $nominalAwal;
        $sisaMap      = [];
        foreach ($riwayatChronological as $item) {
            if ($item->status !== 'batal') $sisaBerjalan -= (float) $item->jumlah_bayar;
            $sisaMap[$item->id_pembayaran] = max(0, $sisaBerjalan);
        }
        $riwayat    = $riwayatChronological->sortByDesc('id_pembayaran')->values();
        $totalValid = $riwayatChronological->where('status', 'valid')->sum('jumlah_bayar');
        $totalDraft = $riwayatChronological->where('status', 'draft')->sum('jumlah_bayar');
        $metodes    = MetodePembayaran::orderBy('nama_metode')->get();
        return view('dashboard.detail-pembayaran', compact(
            'projek',
            'riwayat',
            'sisaMap',
            'progress',
            'totalValid',
            'totalDraft',
            'metodes'
        ));
    }

    public function getRiwayat($id_projek)
    {
        $projek = Projek::with(['perusahaan', 'kategoriProjek'])->findOrFail($id_projek);
        $riwayatChronological = PembayaranProjek::with(['petugas', 'metode'])
            ->where('id_projek', $id_projek)
            ->orderBy('tanggal_bayar', 'asc')->orderBy('id_pembayaran', 'asc')->get();
        $nominalAwal  = (float) $projek->nominal_projek;
        $sisaBerjalan = $nominalAwal;
        $sisaMap      = [];
        foreach ($riwayatChronological as $item) {
            if ($item->status !== 'batal') $sisaBerjalan -= (float) $item->jumlah_bayar;
            $sisaMap[$item->id_pembayaran] = max(0, $sisaBerjalan);
        }
        $riwayatDesc = $riwayatChronological->sortByDesc('id_pembayaran')->values();
        $totalValid  = $riwayatChronological->where('status', 'valid')->sum('jumlah_bayar');

        $riwayatFormatted = $riwayatDesc->map(function ($item) use ($sisaMap) {
            $buktiUrl = $item->bukti_bayar ? asset('storage/' . $item->bukti_bayar) : null;
            return [
                'id_pembayaran'   => $item->id_pembayaran,
                'kode_pembayaran' => $item->kode_pembayaran,
                'jumlah_bayar'    => (float) $item->jumlah_bayar,
                'sisa_setelah'    => $sisaMap[$item->id_pembayaran] ?? 0,
                'tanggal_bayar'   => $item->tanggal_bayar,
                'nama_petugas'    => optional($item->petugas)->nama ?? '—',
                'nama_metode'     => optional($item->metode)->nama_metode ?? '—',
                'status'          => $item->status,
                'bukti_url'       => $buktiUrl,
            ];
        });

        $progressDb = DB::table('tugas')->where('id_projek', $id_projek)
            ->selectRaw('SUM(CASE WHEN status_progress != "draft" THEN weight ELSE 0 END) as total_weight,
                SUM(CASE WHEN status_progress = "done" AND status_akhir = "approved" THEN weight ELSE 0 END) as approved_weight')
            ->first();
        $tw = $progressDb ? (float) $progressDb->total_weight : 0;
        $aw = $progressDb ? (float) $progressDb->approved_weight : 0;
        $pg = $tw > 0 ? round(($aw / $tw) * 100, 2) : 0;

        return response()->json([
            'success'        => true,
            'projek'         => [
                'id_projek'       => $projek->id_projek,
                'nama_projek'     => $projek->nama_projek,
                'perusahaan_nama' => optional($projek->perusahaan)->nama_perwakilan ?? '—',
                'perusahaan_pt'   => optional($projek->perusahaan)->nama_perusahaan ?? '',
                'kategori_nama'   => optional($projek->kategoriProjek)->nama_kategori ?? '—',
                'nominal_projek'  => $nominalAwal,
                'sisa_tanggungan' => (float) $projek->sisa_tanggungan,
                'status'          => $projek->status,
                'progress'        => $pg,
            ],
            'riwayat'        => $riwayatFormatted->values(),
            'total_terbayar' => (float) $totalValid,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_projek'            => 'required|exists:projek,id_projek',
            'jumlah_bayar'         => 'required|numeric|min:1',
            'tanggal_bayar'        => 'required|date',
            'id_metode_pembayaran' => 'required|exists:metode_pembayaran,id_metode_pembayaran',
            'bukti_bayar'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);
        $projek = Projek::findOrFail($validated['id_projek']);
        if ((float) $validated['jumlah_bayar'] > (float) $projek->sisa_tanggungan) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah bayar melebihi sisa tanggungan (Rp ' . number_format($projek->sisa_tanggungan, 0, ',', '.') . ').',
            ], 422);
        }
        DB::beginTransaction();
        try {
            $kode = 'PAY-' . str_pad($projek->id_projek, 4, '0', STR_PAD_LEFT) . '-' . strtoupper(substr(uniqid(), -6));
            $buktiPath = null;
            if ($request->hasFile('bukti_bayar') && $request->file('bukti_bayar')->isValid()) {
                $buktiPath = $request->file('bukti_bayar')->store('bukti-pembayaran', 'public');
            }
            $pembayaran = PembayaranProjek::create([
                'kode_pembayaran'      => $kode,
                'id_projek'            => $validated['id_projek'],
                'id_petugas'           => Auth::id(),
                'id_metode_pembayaran' => $validated['id_metode_pembayaran'],
                'jumlah_bayar'         => $validated['jumlah_bayar'],
                'tanggal_bayar'        => $validated['tanggal_bayar'],
                'bukti_bayar'          => $buktiPath,
                'status'               => 'valid',
                'dibuat_pada'          => now(),
                'diperbarui_pada'      => now(),
            ]);
            $sisaBaru = max(0, (float) $projek->sisa_tanggungan - (float) $validated['jumlah_bayar']);
            $projek->update(['sisa_tanggungan' => $sisaBaru]);
            DB::commit();
            return response()->json([
                'success'         => true,
                'message'         => 'Pembayaran berhasil! Kode: ' . $kode,
                'id_pembayaran'   => $pembayaran->id_pembayaran,
                'kode_pembayaran' => $kode,
                'sisa_tanggungan' => $sisaBaru,
                'bukti_url'       => $buktiPath ? asset('storage/' . $buktiPath) : null,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    public function uploadBukti(Request $request, $id_pembayaran)
    {
        $request->validate([
            'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);
        $pembayaran = PembayaranProjek::findOrFail($id_pembayaran);
        if ($pembayaran->bukti_bayar) {
            Storage::disk('public')->delete($pembayaran->bukti_bayar);
        }
        $path = $request->file('bukti_bayar')->store('bukti-pembayaran', 'public');
        $pembayaran->update(['bukti_bayar' => $path, 'diperbarui_pada' => now()]);
        return response()->json([
            'success'   => true,
            'message'   => 'Bukti berhasil diupload.',
            'bukti_url' => asset('storage/' . $path),
        ]);
    }

    public function updateStatus(Request $request, $id_pembayaran)
    {
        $request->validate(['status' => 'required|in:draft,valid,batal']);
        $pembayaran = PembayaranProjek::with('projek')->findOrFail($id_pembayaran);
        $statusLama = $pembayaran->status;
        $statusBaru = $request->status;
        if ($statusLama === $statusBaru) {
            return response()->json(['success' => false, 'message' => 'Status tidak berubah.'], 422);
        }
        DB::beginTransaction();
        try {
            $projek   = $pembayaran->projek;
            $jumlah   = (float) $pembayaran->jumlah_bayar;
            $sisaSkrg = (float) $projek->sisa_tanggungan;
            if ($statusBaru === 'batal' && $statusLama !== 'batal') {
                $projek->update(['sisa_tanggungan' => $sisaSkrg + $jumlah]);
            }
            if ($statusLama === 'batal' && in_array($statusBaru, ['draft', 'valid'])) {
                if ($jumlah > ($sisaSkrg + 0.01)) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Tidak dapat diaktifkan: jumlah melebihi sisa tanggungan.'], 422);
                }
                $projek->update(['sisa_tanggungan' => max(0, $sisaSkrg - $jumlah)]);
            }
            $pembayaran->update(['status' => $statusBaru, 'diperbarui_pada' => now()]);
            DB::commit();
            return response()->json([
                'success'         => true,
                'message'         => 'Status diubah ke ' . ucfirst($statusBaru),
                'status'          => $statusBaru,
                'sisa_tanggungan' => (float) $projek->fresh()->sisa_tanggungan,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function cetakStruk($id_pembayaran)
    {
        $p = PembayaranProjek::with(['projek.perusahaan', 'projek.kategoriProjek', 'petugas', 'metode'])
            ->findOrFail($id_pembayaran);
        $nominalAwal = (float) optional($p->projek)->nominal_projek;
        $totalHingga = PembayaranProjek::where('id_projek', $p->id_projek)->where('status', '!=', 'batal')
            ->where(function ($q) use ($p) {
                $q->where('tanggal_bayar', '<', $p->tanggal_bayar)
                    ->orWhere(function ($q2) use ($p) {
                        $q2->where('tanggal_bayar', '=', $p->tanggal_bayar)
                            ->where('id_pembayaran', '<=', $p->id_pembayaran);
                    });
            })->sum('jumlah_bayar');
        $sisaSetelah = max(0, $nominalAwal - (float) $totalHingga);
        $buktiUrl = $p->bukti_bayar ? asset('storage/' . $p->bukti_bayar) : null;
        return response()->json([
            'success' => true,
            'struk'   => [
                'kode_pembayaran' => $p->kode_pembayaran,
                'nama_projek'     => optional($p->projek)->nama_projek ?? '—',
                'perusahaan'      => optional(optional($p->projek)->perusahaan)->nama_perusahaan ?? '—',
                'perusahaan_nama' => optional(optional($p->projek)->perusahaan)->nama_perwakilan ?? '—',
                'kategori'        => optional(optional($p->projek)->kategoriProjek)->nama_kategori ?? '—',
                'nominal_projek'  => $nominalAwal,
                'jumlah_bayar'    => (float) $p->jumlah_bayar,
                'sisa_setelah'    => $sisaSetelah,
                'tanggal_bayar'   => $p->tanggal_bayar,
                'nama_petugas'    => optional($p->petugas)->nama ?? '—',
                'nama_metode'     => optional($p->metode)->nama_metode ?? '—',
                'status'          => $p->status,
                'bukti_url'       => $buktiUrl,
                'dicetak_pada'    => now()->format('d/m/Y H:i:s'),
            ],
        ]);
    }

    public function cetakRiwayat($id_projek)
    {
        $projek  = Projek::with(['perusahaan', 'kategoriProjek'])->findOrFail($id_projek);
        $riwayat = PembayaranProjek::with(['petugas', 'metode'])->where('id_projek', $id_projek)
            ->orderBy('tanggal_bayar', 'asc')->orderBy('id_pembayaran', 'asc')->get();
        $nominalAwal  = (float) $projek->nominal_projek;
        $sisaBerjalan = $nominalAwal;
        $riwayatFmt   = [];
        $no           = 1;
        foreach ($riwayat as $item) {
            if ($item->status !== 'batal') $sisaBerjalan -= (float) $item->jumlah_bayar;
            $riwayatFmt[] = [
                'no'              => $no++,
                'kode_pembayaran' => $item->kode_pembayaran,
                'jumlah_bayar'    => (float) $item->jumlah_bayar,
                'sisa_setelah'    => max(0, $sisaBerjalan),
                'tanggal_bayar'   => $item->tanggal_bayar,
                'nama_petugas'    => optional($item->petugas)->nama ?? '—',
                'nama_metode'     => optional($item->metode)->nama_metode ?? '—',
                'status'          => $item->status,
            ];
        }
        $totalValid = $riwayat->where('status', 'valid')->sum('jumlah_bayar');
        $totalDraft = $riwayat->where('status', 'draft')->sum('jumlah_bayar');
        $progressDb = DB::table('tugas')->where('id_projek', $id_projek)
            ->selectRaw('SUM(CASE WHEN status_progress != "draft" THEN weight ELSE 0 END) as total_weight,
                SUM(CASE WHEN status_progress = "done" AND status_akhir = "approved" THEN weight ELSE 0 END) as approved_weight')
            ->first();
        $tw = $progressDb ? (float) $progressDb->total_weight : 0;
        $aw = $progressDb ? (float) $progressDb->approved_weight : 0;
        $pg = $tw > 0 ? round(($aw / $tw) * 100, 2) : 0;
        return response()->json([
            'success'      => true,
            'projek'       => [
                'nama_projek'     => $projek->nama_projek,
                'perusahaan'      => optional($projek->perusahaan)->nama_perusahaan ?? '—',
                'perusahaan_nama' => optional($projek->perusahaan)->nama_perwakilan ?? '—',
                'kategori'        => optional($projek->kategoriProjek)->nama_kategori ?? '—',
                'nominal_projek'  => $nominalAwal,
                'sisa_tanggungan' => (float) $projek->sisa_tanggungan,
                'status'          => $projek->status,
                'progress'        => $pg,
                'total_terbayar'  => (float) $totalValid,
                'total_draft'     => (float) $totalDraft,
            ],
            'riwayat'      => $riwayatFmt,
            'dicetak_pada' => now()->format('d/m/Y H:i:s'),
        ]);
    }
}
