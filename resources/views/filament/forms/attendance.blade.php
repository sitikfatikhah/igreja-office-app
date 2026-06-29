<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kehadiran Karyawan</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }

        .page {
            padding: 22px 30px;
        }

        /* ===== KOP SURAT ===== */
        .kop {
            width: 100%;
            border-bottom: 3px solid #1f2d3d;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .kop table {
            width: 100%;
            border: none;
        }

        .kop td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .kop .logo-cell {
            width: 65px;
        }

        .kop .logo-box {
            width: 60px;
            height: 60px;
            text-align: center;
            border: none;
            line-height: normal;
        }

        .kop .instansi-name {
            font-size: 16px;
            font-weight: bold;
            color: #1f2d3d;
            letter-spacing: 0.4px;
            margin: 0;
        }

        .kop .instansi-detail {
            font-size: 9.5px;
            color: #555;
            margin: 2px 0 0 0;
            line-height: 1.5;
        }

        /* ===== JUDUL DOKUMEN ===== */
        .doc-title-wrap {
            text-align: center;
            margin-bottom: 12px;
        }

        .doc-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0;
            color: #1f2d3d;
        }

        .doc-subtitle {
            font-size: 10px;
            color: #555;
            margin-top: 2px;
        }

        /* ===== META INFO ===== */
        .meta-table {
            width: 100%;
            border: none;
            margin-bottom: 12px;
            font-size: 10.5px;
        }

        .meta-table td {
            border: none;
            padding: 1px 0;
            vertical-align: top;
        }

        .meta-table .meta-label {
            width: 100px;
            color: #555;
        }

        .meta-table .meta-colon {
            width: 12px;
        }

        .meta-table .meta-value {
            font-weight: bold;
        }

        .meta-right {
            text-align: right;
        }

        /* ===== SECTION HEADING ===== */
        .section-heading {
            background-color: #1f2d3d;
            color: #ffffff;
            font-size: 10.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 10px;
            margin: 16px 0 0 0;
            letter-spacing: 0.3px;
        }

        table.info-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.info-table td {
            border: 1px solid #cfd4da;
            padding: 5px 10px;
            font-size: 10.5px;
        }

        table.info-table .label-col {
            width: 22%;
            color: #555;
            background-color: #f7f8fa;
        }

        table.info-table .value-col {
            width: 28%;
            font-weight: 600;
        }

        /* ===== SUMMARY CARDS ===== */
        table.summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }

        table.summary-table td {
            border: 1px solid #cfd4da;
            padding: 0;
            width: 16.66%;
            text-align: center;
            vertical-align: top;
        }

        .summary-card {
            padding: 10px 4px 8px 4px;
        }

        .summary-card .summary-value {
            font-size: 17px;
            font-weight: bold;
            color: #1f2d3d;
            display: block;
        }

        .summary-card .summary-unit {
            font-size: 9px;
            color: #888;
            display: block;
            margin-bottom: 3px;
        }

        .summary-card .summary-label {
            font-size: 9px;
            text-transform: uppercase;
            color: #555;
            letter-spacing: 0.2px;
            display: block;
            border-top: 1px solid #e3e6ea;
            padding-top: 4px;
            margin-top: 4px;
        }

        .summary-card.success .summary-value { color: #1a7a3d; }
        .summary-card.danger .summary-value { color: #b8312f; }
        .summary-card.warning .summary-value { color: #b8860b; }
        .summary-card.info .summary-value { color: #2563a8; }

        /* ===== TABEL DETAIL HARIAN ===== */
        table.detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }

        table.detail-table th {
            background-color: #1f2d3d;
            color: #ffffff;
            border: 1px solid #1f2d3d;
            padding: 6px 6px;
            font-size: 9.5px;
            text-align: center;
            text-transform: uppercase;
        }

        table.detail-table td {
            border: 1px solid #cfd4da;
            padding: 5px 6px;
            font-size: 9.5px;
            text-align: center;
        }

        table.detail-table td.text-left {
            text-align: left;
        }

        table.detail-table tr.weekend-row td {
            background-color: #f5f5f7;
        }

        table.detail-table tr.holiday-row td {
            background-color: #fdecea;
        }

        table.detail-table tr.absent-row td {
            background-color: #fdecea;
        }

        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 3px;
            font-size: 8.5px;
            font-weight: bold;
            color: #fff;
            text-transform: uppercase;
        }

        .badge-success { background-color: #1a7a3d; }
        .badge-warning { background-color: #b8860b; }
        .badge-info    { background-color: #2563a8; }
        .badge-danger  { background-color: #b8312f; }
        .badge-gray    { background-color: #888; }

        .holiday-note {
            font-size: 8.5px;
            color: #b8312f;
            font-style: italic;
            display: block;
            margin-top: 1px;
        }

        /* ===== LEGENDA ===== */
        .legend-box {
            margin-top: 10px;
            font-size: 9px;
            color: #555;
        }

        .legend-box span {
            margin-right: 14px;
            white-space: nowrap;
        }

        .legend-dot {
            display: inline-block;
            width: 9px;
            height: 9px;
            border-radius: 2px;
            margin-right: 3px;
            vertical-align: middle;
        }

        /* ===== TANDA TANGAN ===== */
        .signature-section {
            width: 100%;
            margin-top: 30px;
        }

        .signature-table {
            width: 100%;
            border: none;
        }

        .signature-table td {
            border: none;
            text-align: center;
            width: 50%;
            vertical-align: top;
            font-size: 10.5px;
            padding: 0 20px;
        }

        .signature-place-date {
            margin-bottom: 60px;
            color: #333;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 4px;
        }

        .signature-position {
            color: #555;
            font-size: 9.5px;
            margin-top: 2px;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 22px;
            border-top: 1px solid #cfd4da;
            padding-top: 7px;
            font-size: 8.5px;
            color: #888;
            text-align: center;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>

<div class="page">

    {{--
        ====================================================================
        VARIABEL YANG DIHARAPKAN DIKIRIM KE VIEW INI:

        $instansi          : object/array (opsional) -> name, address, phone, email, city
        $user               : Model User -> name, nip, position/department dst
        $startDate          : Carbon|string -> awal periode
        $endDate            : Carbon|string -> akhir periode
        $attendances        : Collection of Attendance, sudah di-keyBy tanggal
                              format Y-m-d, contoh: $attendances->keyBy(fn($a) => $a->date->format('Y-m-d'))
        $holidays           : array asosiatif ['Y-m-d' => 'Nama Hari Libur', ...]   (opsional, default [])

        Variabel summary di bawah ini idealnya dihitung di Controller/Page,
        namun untuk amannya view ini juga punya fallback penghitungan ulang
        dari $attendances bila variabel summary tidak dikirim.
        ====================================================================
    --}}

    @php
        use Carbon\Carbon;

        $startDate = isset($startDate) ? Carbon::parse($startDate) : null;
        $endDate   = isset($endDate) ? Carbon::parse($endDate) : null;
        $holidays  = $holidays ?? [];
        $attendances = $attendances ?? collect();

        // Bangun rentang tanggal penuh dari start s/d end (termasuk yang tidak ada record-nya)
        $period = collect();
        if ($startDate && $endDate) {
            $cursor = $startDate->copy();
            while ($cursor->lte($endDate)) {
                $period->push($cursor->copy());
                $cursor->addDay();
            }
        }

        // Hitung ulang summary sebagai fallback jika tidak dikirim dari controller
        $totalHadir       = $totalHadir       ?? 0;
        $totalTidakHadir  = $totalTidakHadir  ?? 0;
        $totalJamKerja    = $totalJamKerja    ?? 0;
        $totalLembur      = $totalLembur      ?? 0;
        $totalTelat       = $totalTelat       ?? 0;
        $totalHariKerja   = $totalHariKerja   ?? 0;
        $totalLibur       = $totalLibur       ?? 0;

        if (!isset($summaryProvided)) {
            $totalHadir = 0;
            $totalTidakHadir = 0;
            $totalJamKerja = 0;
            $totalLembur = 0;
            $totalTelat = 0;
            $totalHariKerja = 0;
            $totalLibur = 0;

            foreach ($period as $day) {
                $key = $day->format('Y-m-d');
                $isWeekend = $day->isWeekend();
                $isHoliday = array_key_exists($key, $holidays);

                if ($isWeekend || $isHoliday) {
                    $totalLibur++;
                    continue;
                }

                $totalHariKerja++;
                $att = $attendances->get($key);

                if ($att && $att->check_in) {
                    $totalHadir++;
                    $totalJamKerja += (float) ($att->total_hours ?? 0);
                    $totalLembur   += (float) ($att->overtime_hours ?? 0);
                    if ($att->is_late) {
                        $totalTelat += (float) ($att->late_hours ?? 0);
                    }
                } else {
                    $totalTidakHadir++;
                }
            }
        }
    @endphp

    {{-- ===================== KOP INSTANSI ===================== --}}
    <div class="kop">
        <table>
            <tr>
                <td width="90" style="text-align:center; vertical-align:middle;">
                    <img
                        src="{{ app(\App\Services\SettingsService::class)->getCompanyLogo(auth()->user()) }}"
                        alt="{{ app(\App\Services\SettingsService::class)->getCompanyName(auth()->user()) }}"
                        width="75"
                        height="75"
                    >
                </td>
                <td>
                    <p class="instansi-name">{{ $instansi->name ?? app(\App\Services\SettingsService::class)->getCompanyName(auth()->user()) }}</p>
                    <p class="instansi-detail">
                        {{ $instansi->address ?? app(\App\Services\SettingsService::class)->get('general.company_address', 'Jl. Sutopo No. 9 Tangerang', auth()->user()) }}<br>
                        Telp: {{ $instansi->phone ?? app(\App\Services\SettingsService::class)->get('general.company_phone', '(021) 55 237 55', auth()->user()) }} &nbsp;|&nbsp;
                        Email: {{ $instansi->email ?? app(\App\Services\SettingsService::class)->get('general.company_email', 'https://gkisutopo.org', auth()->user()) }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- ===================== JUDUL DOKUMEN ===================== --}}
    <div class="doc-title-wrap">
        <p class="doc-title">Laporan Rekapitulasi Kehadiran Karyawan</p>
        <p class="doc-subtitle">Employee Attendance Report</p>
    </div>

    {{-- ===================== META ===================== --}}
    <table class="meta-table">
        <tr>
            <td class="meta-label">No. Laporan</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">
                {{ $reportNumber ?? ('ATT/' . now()->format('Y/m') . '/' . str_pad($user->id ?? 0, 5, '0', STR_PAD_LEFT)) }}
            </td>
            <td class="meta-right">Tanggal Cetak : <strong>{{ now()->translatedFormat('d F Y') }}</strong></td>
        </tr>
        <tr>
            <td class="meta-label">Periode</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">
                {{ $startDate?->translatedFormat('d F Y') ?? '-' }} &ndash; {{ $endDate?->translatedFormat('d F Y') ?? '-' }}
            </td>
            <td class="meta-right">Dicetak oleh : <strong>{{ auth()->user()->name ?? 'System' }}</strong></td>
        </tr>
    </table>

    {{-- ===================== INFORMASI KARYAWAN ===================== --}}
    <div class="section-heading">Informasi Karyawan</div>
    <table class="info-table">
        <tr>
            <td class="label-col">Nama Karyawan</td>
            <td class="value-col">{{ $user->name ?? '-' }}</td>
            <td class="label-col">NIP</td>
            <td class="value-col">{{ $user->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col">Jabatan</td>
            <td class="value-col">{{ $user->position ?? '-' }}</td>
            <td class="label-col">Departemen</td>
            <td class="value-col">{{ $user->department ?? '-' }}</td>
        </tr>
    </table>

    {{-- ===================== RINGKASAN KEHADIRAN ===================== --}}
    <div class="section-heading">Ringkasan Kehadiran</div>
    <table class="summary-table">
        <tr>
            <td>
                <div class="summary-card info">
                    <span class="summary-value">{{ $totalHariKerja }}</span>
                    <span class="summary-unit">hari</span>
                    <span class="summary-label">Hari Kerja</span>
                </div>
            </td>
            <td>
                <div class="summary-card success">
                    <span class="summary-value">{{ $totalHadir }}</span>
                    <span class="summary-unit">hari</span>
                    <span class="summary-label">Hadir</span>
                </div>
            </td>
            <td>
                <div class="summary-card danger">
                    <span class="summary-value">{{ $totalTidakHadir }}</span>
                    <span class="summary-unit">hari</span>
                    <span class="summary-label">Tidak Hadir</span>
                </div>
            </td>
            <td>
                <div class="summary-card">
                    <span class="summary-value">{{ number_format($totalJamKerja, 1) }}</span>
                    <span class="summary-unit">jam</span>
                    <span class="summary-label">Total Jam Kerja</span>
                </div>
            </td>
            <td>
                <div class="summary-card warning">
                    <span class="summary-value">{{ number_format($totalLembur, 1) }}</span>
                    <span class="summary-unit">jam</span>
                    <span class="summary-label">Total Lembur</span>
                </div>
            </td>
            <td>
                <div class="summary-card danger">
                    <span class="summary-value">{{ number_format($totalTelat, 1) }}</span>
                    <span class="summary-unit">jam</span>
                    <span class="summary-label">Total Terlambat</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="summary-table" style="margin-top: 8px;">
        <tr>
            <td style="width: 50%;">
                <div class="summary-card">
                    <span class="summary-value">{{ $totalLibur }}</span>
                    <span class="summary-unit">hari</span>
                    <span class="summary-label">Libur / Akhir Pekan / Tanggal Merah</span>
                </div>
            </td>
            <td style="width: 50%;">
                <div class="summary-card">
                    <span class="summary-value">
                        {{ $totalHariKerja > 0 ? number_format(($totalHadir / $totalHariKerja) * 100, 1) : 0 }}%
                    </span>
                    <span class="summary-unit">&nbsp;</span>
                    <span class="summary-label">Tingkat Kehadiran</span>
                </div>
            </td>
        </tr>
    </table>

    {{-- ===================== DETAIL HARIAN ===================== --}}
    <div class="section-heading">Detail Kehadiran Harian</div>
    <table class="detail-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 9%;">Tanggal</th>
                <th style="width: 8%;">Hari</th>
                <th style="width: 8%;">Check In</th>
                <th style="width: 8%;">Check Out</th>
                <th style="width: 8%;">Jam Kerja</th>
                <th style="width: 8%;">Lembur</th>
                <th style="width: 7%;">Telat (jam)</th>
                <th style="width: 9%;">Status</th>
                <th style="width: 31%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($period as $i => $day)
                @php
                    $key = $day->format('Y-m-d');
                    $isWeekend = $day->isWeekend();
                    $isHoliday = array_key_exists($key, $holidays);
                    $att = $attendances->get($key);

                    $rowClass = '';
                    if ($isHoliday) {
                        $rowClass = 'holiday-row';
                    } elseif ($isWeekend) {
                        $rowClass = 'weekend-row';
                    } elseif (!$att || !$att->check_in) {
                        $rowClass = 'absent-row';
                    }
                @endphp
                <tr class="{{ $rowClass }}">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $day->format('d-m-Y') }}</td>
                    <td>{{ $day->translatedFormat('l') }}</td>

                    @if ($isHoliday || $isWeekend)
                        <td colspan="5" class="text-left">
                            <span class="badge badge-gray">{{ $isHoliday ? 'LIBUR NASIONAL' : 'LIBUR AKHIR PEKAN' }}</span>
                            @if ($isHoliday)
                                <span class="holiday-note">{{ $holidays[$key] }}</span>
                            @endif
                        </td>
                    @elseif ($att && $att->check_in)
                        <td>{{ $att->check_in->format('H:i') }}</td>
                        <td>{{ $att->check_out?->format('H:i') ?? '-' }}</td>
                        <td>{{ number_format($att->total_hours ?? 0, 1) }}</td>
                        <td>{{ number_format($att->overtime_hours ?? 0, 1) }}</td>
                        <td>{{ $att->is_late ? number_format($att->late_hours ?? 0, 1) : '-' }}</td>
                        <td>
                            @if ($att->is_late)
                                <span class="badge badge-warning">Terlambat</span>
                            @else
                                <span class="badge badge-success">Tepat Waktu</span>
                            @endif
                        </td>
                        <td class="text-left">
                            {{ $att->check_in_location_name ?? '-' }}
                        </td>
                    @else
                        <td colspan="5" class="text-left">
                            <span class="badge badge-danger">TIDAK HADIR</span>
                        </td>
                        <td class="text-left">Tanpa keterangan</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 14px;">Tidak ada data pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ===================== LEGENDA ===================== --}}
    <div class="legend-box">
        <span><span class="legend-dot" style="background:#fdecea;"></span>Tidak Hadir / Tanpa Keterangan</span>
        <span><span class="legend-dot" style="background:#f5f5f7;"></span>Akhir Pekan</span>
        <span><span class="legend-dot" style="background:#fdecea; border:1px solid #b8312f;"></span>Tanggal Merah / Libur Nasional</span>
        <span><span class="badge badge-warning" style="margin-right:3px;">&nbsp;</span>Terlambat</span>
        <span><span class="badge badge-success" style="margin-right:3px;">&nbsp;</span>Tepat Waktu</span>
    </div>

    {{-- ===================== CATATAN ===================== --}}
    <div class="legend-box" style="margin-top: 10px;">
        <strong>Catatan:</strong> Laporan ini dihasilkan secara otomatis oleh sistem berdasarkan data presensi yang tercatat. Jam kerja, lembur, dan keterlambatan dihitung sesuai kebijakan perusahaan yang berlaku.
    </div>

    {{-- ===================== TANDA TANGAN ===================== --}}
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-place-date">
                        {{ $instansi->city ?? 'Tangerang' }}, {{ now()->translatedFormat('d F Y') }}
                    </div>
                    <div>Diperiksa oleh,</div>
                    <div class="signature-name">{{ $reviewedBy ?? 'Staff HRD' }}</div>
                    <div class="signature-position">Bagian Sumber Daya Manusia</div>
                </td>
                <td>
                    <div class="signature-place-date">&nbsp;</div>
                    <div>Mengetahui,</div>
                    <div class="signature-name">{{ $user->name ?? '-' }}</div>
                    <div class="signature-position">{{ $user->position ?? 'Karyawan' }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ===================== FOOTER ===================== --}}
    <div class="footer">
        Dokumen ini dibuat secara otomatis oleh sistem kehadiran {{ $instansi->name ?? config('app.name') }} &mdash;
        Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB
    </div>

</div>

</body>
</html>