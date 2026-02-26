<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Project – {{ $projek->nama_projek }}</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', Arial, sans-serif; background: #f5f7fa; color: #1F2937; padding: 32px; }
    .report-wrapper { max-width: 780px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.12); }
    .report-header { background: linear-gradient(135deg, #4F46E5 0%, #8B5CF6 100%); color: white; padding: 32px 40px; }
    .report-header h1 { font-size: 22px; font-weight: 700; margin-bottom: 4px; }
    .report-header p  { font-size: 13px; opacity: .85; }
    .report-body { padding: 32px 40px; }
    .section { margin-bottom: 28px; }
    .section-title {
        font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em;
        color: #6B7280; border-bottom: 2px solid #E5E7EB; padding-bottom: 8px; margin-bottom: 16px;
    }
    table.info-table { width: 100%; border-collapse: collapse; }
    table.info-table td { padding: 9px 12px; font-size: 13px; border-bottom: 1px solid #F3F4F6; vertical-align: top; }
    table.info-table td:first-child { color: #6B7280; font-weight: 600; width: 40%; }
    table.info-table td:last-child { color: #111827; font-weight: 600; }
    .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
    .badge-aktif    { background: #D1FAE5; color: #065F46; }
    .badge-progress { background: #FEF3C7; color: #92400E; }
    .badge-selesai  { background: #DBEAFE; color: #1E40AF; }
    .badge-pending  { background: #F3F4F6; color: #374151; }
    .report-footer { text-align: center; padding: 20px 40px; font-size: 11px; color: #9CA3AF; border-top: 1px solid #E5E7EB; }
    @media print {
        body { background: white; padding: 0; }
        .report-wrapper { box-shadow: none; border-radius: 0; }
        .no-print { display: none; }
    }
</style>
</head>
<body>
<div class="no-print" style="text-align:center; margin-bottom:20px;">
    <button onclick="window.print()" style="padding:10px 24px; background:#4F46E5; color:white; border:none; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer;">
        🖨 Cetak / Simpan PDF
    </button>
</div>

<div class="report-wrapper">
    <div class="report-header">
        <h1>Laporan Progress Project</h1>
        <p>{{ $projek->nama_projek }} &mdash; Dicetak: {{ now()->format('d M Y, H:i') }}</p>
    </div>
    <div class="report-body">

        <div class="section">
            <div class="section-title">Informasi Umum</div>
            <table class="info-table">
                <tr><td>Nama Project</td><td>{{ $projek->nama_projek }}</td></tr>
                <tr>
                    <td>Perusahaan</td>
                    <td>
                        {{ optional($projek->perusahaan)->nama_perwakilan ?? '—' }}
                        @if(optional($projek->perusahaan)->nama_perusahaan)
                            <br><small style="color:#6B7280;">{{ $projek->perusahaan->nama_perusahaan }}</small>
                        @endif
                    </td>
                </tr>
                <tr><td>Kategori</td><td>{{ optional($projek->kategoriProjek)->nama_kategori ?? '—' }}</td></tr>
                <tr>
                    <td>Status</td>
                    <td>
                        @php
                            $badgeClass = ['aktif'=>'badge-aktif','in_progress'=>'badge-progress','selesai'=>'badge-selesai','pending'=>'badge-pending'][$projek->status] ?? 'badge-pending';
                            $badgeLabel = ['aktif'=>'Aktif','in_progress'=>'In Progress','selesai'=>'Selesai','pending'=>'Pending'][$projek->status] ?? ucfirst($projek->status);
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                    </td>
                </tr>
                <tr><td>Deskripsi</td><td>{{ $projek->deskripsi ?: '—' }}</td></tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Timeline</div>
            <table class="info-table">
                <tr>
                    <td>Tanggal Mulai</td>
                    <td>{{ $projek->tanggal_mulai ? \Carbon\Carbon::parse($projek->tanggal_mulai)->format('d M Y') : '—' }}</td>
                </tr>
                <tr>
                    <td>Target Selesai</td>
                    <td>{{ $projek->tanggal_selesai ? \Carbon\Carbon::parse($projek->tanggal_selesai)->format('d M Y') : '—' }}</td>
                </tr>
                @if($projek->tanggal_mulai && $projek->tanggal_selesai)
                @php
                    $durasi = \Carbon\Carbon::parse($projek->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($projek->tanggal_selesai));
                @endphp
                <tr><td>Durasi (hari)</td><td>{{ $durasi }} hari</td></tr>
                @endif
            </table>
        </div>

        <div class="section">
            <div class="section-title">Keuangan</div>
            <table class="info-table">
                <tr>
                    <td>Nominal Project</td>
                    <td style="color:#4F46E5;">Rp {{ number_format($projek->nominal_projek, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Sisa Tanggungan</td>
                    <td style="color:{{ $projek->sisa_tanggungan > 0 ? '#EF4444' : '#10B981' }};">
                        @if($projek->sisa_tanggungan > 0)
                            Rp {{ number_format($projek->sisa_tanggungan, 0, ',', '.') }}
                        @else
                            ✓ Lunas
                        @endif
                    </td>
                </tr>
                @if($projek->nominal_projek > 0)
                <tr>
                    <td>Sudah Dibayar</td>
                    <td>Rp {{ number_format($projek->nominal_projek - $projek->sisa_tanggungan, 0, ',', '.') }}
                        ({{ number_format((($projek->nominal_projek - $projek->sisa_tanggungan) / $projek->nominal_projek) * 100, 1) }}%)
                    </td>
                </tr>
                @endif
            </table>
        </div>

        @if($projek->dokumen_perjanjian)
        <div class="section">
            <div class="section-title">Dokumen</div>
            <table class="info-table">
                <tr>
                    <td>Dokumen Perjanjian</td>
                    <td><a href="{{ Storage::url($projek->dokumen_perjanjian) }}" target="_blank">Lihat / Download Dokumen</a></td>
                </tr>
            </table>
        </div>
        @endif

    </div>
    <div class="report-footer">
        Laporan ini digenerate otomatis oleh sistem &bull; {{ now()->format('d M Y H:i:s') }}
    </div>
</div>
</body>
</html>