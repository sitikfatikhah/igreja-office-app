<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report</title>
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

        /* ===== LETTERHEAD ===== */
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

        /* ===== DOCUMENT TITLE ===== */
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
            text-align: center;
            vertical-align: top;
        }

        .summary-table.cols-6 td { width: 16.66%; }
        .summary-table.cols-4 td { width: 25%; }

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
        .summary-card.purple .summary-value { color: #6f42c1; }
        .summary-card.gray .summary-value { color: #555; }

        /* ===== DAILY DETAIL TABLE ===== */
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

        table.detail-table tr.offday-row td {
            background-color: #f5f5f7;
        }

        table.detail-table tr.holiday-row td {
            background-color: #eef2fb;
        }

        table.detail-table tr.leave-row td {
            background-color: #f3eefc;
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

        .badge-success  { background-color: #1a7a3d; }
        .badge-warning  { background-color: #b8860b; }
        .badge-info     { background-color: #2563a8; }
        .badge-danger   { background-color: #b8312f; }
        .badge-gray     { background-color: #888; }
        .badge-purple   { background-color: #6f42c1; }
        .badge-secondary{ background-color: #6c757d; }

        .holiday-note {
            font-size: 8.5px;
            color: #2563a8;
            font-style: italic;
            display: block;
            margin-top: 1px;
        }

        .leave-note {
            font-size: 8.5px;
            color: #6f42c1;
            font-style: italic;
            display: block;
            margin-top: 1px;
        }

        /* ===== LEGEND ===== */
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

        /* ===== SIGNATURE ===== */
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
        VARIABLES EXPECTED TO BE PASSED TO THIS VIEW:

        $instansi        : object/array (optional) -> name, address, phone, email, city
        $user            : User model -> name, nip, position/department, etc.
        $startDate       : Carbon|string -> period start date
        $endDate         : Carbon|string -> period end date
        $attendances     : Collection of Attendance, keyed by date (Y-m-d)
                            e.g. $attendances->keyBy(fn($a) => $a->date->format('Y-m-d'))
        $holidays        : associative array ['Y-m-d' => 'Holiday Name', ...] (optional, default [])
        $leaveRequests   : Collection of LeaveRequest with approval_status = 'Approved',
                            overlapping the period (optional, default empty collection).
                            Each item is expected to expose: start_date, end_date,
                            leave_type, reason.

        Rules implemented in this revision:
        - isWeekend() is NOT used anywhere. Non-working days are derived instead from:
            1) $holidays (explicit national/company holidays), and
            2) a weekly "1 day off per 6 working days" allowance: for every ISO week
               (Mon-Sun) inside the period, the FIRST day that has no attendance and
               no approved leave is treated as an "Off Day" rather than "Absent".
               Any further day(s) in the same week with no attendance/leave are
               still marked as "Absent".
        - Approved leave request days are shown as "Leave" (with leave type and
          reason), never as "Absent".
        - Holiday takes priority over Leave/Off Day/Absent if they happen to
          overlap on the same date.

        The summary variables below are ideally computed in the Controller/Service,
        but as a safeguard this view also has a fallback calculation from
        $attendances / $holidays / $leaveRequests when $summaryProvided is not set.
        ====================================================================
    --}}

    @php
        use Carbon\Carbon;

        $startDate     = isset($startDate) ? Carbon::parse($startDate) : null;
        $endDate       = isset($endDate) ? Carbon::parse($endDate) : null;
        $holidays      = $holidays ?? [];
        $attendances   = $attendances ?? collect();
        $leaveRequests = $leaveRequests ?? collect();

        
            $offDays = collect(optional($workSchedule)->off_days ?? [])
                ->map(fn ($day) => strtolower($day))
                ->toArray();
        

        // Build the full date range from start to end (including days with no record)
        $period = collect();
        if ($startDate && $endDate) {
            $cursor = $startDate->copy();
            while ($cursor->lte($endDate)) {
                $period->push($cursor->copy());
                $cursor->addDay();
            }
        }

        // Expand approved leave requests into a date => LeaveRequest map
        $leaveDays = collect();
        foreach ($leaveRequests as $leave) {
            if (($leave->approval_status ?? 'Approved') !== 'Approved') {
                continue;
            }

            $leaveCursor = Carbon::parse($leave->start_date);
            $leaveEnd    = Carbon::parse($leave->end_date);

            while ($leaveCursor->lte($leaveEnd)) {
                $leaveDays->put($leaveCursor->format('Y-m-d'), $leave);
                $leaveCursor->addDay();
            }
        }

        // ---- Pass 1: classify each day as Holiday / Leave / Present / (candidate Off-or-Absent)
        $dayData = collect();
        foreach ($period as $day) {
            $key           = $day->format('Y-m-d');
            $isHoliday     = array_key_exists($key, $holidays);
            $leave         = $leaveDays->get($key);
            $att           = $attendances->get($key);
            $hasAttendance = (bool) ($att && $att->check_in);

            $dayData->put($key, [
                'date'          => $day,
                'isHoliday'     => $isHoliday,
                'leave'         => $leave,
                'attendance'    => $att,
                'hasAttendance' => $hasAttendance,
            ]);
        }

        // ---- Pass 2: Off Day quota is CALENDAR-BASED — every calendar week (Mon-Sun)
        // only requires 6 working days, full stop. The quota itself never looks at
        // whether/which day the employee actually skipped; it only depends on how
        // many non-holiday days fall inside that week (so a week with a holiday
        // already in it doesn't also get an extra off day on top of the holiday).
        $weekGroups = $dayData->groupBy(fn ($d) => $d['date']->format('o-\WW'));

        $offDayQuotaByWeek = collect(); // weekKey => quota (int)
        foreach ($weekGroups as $weekKey => $daysInWeek) {
            $nonHolidayInWeek = $daysInWeek->filter(fn ($d) => ! $d['isHoliday'])->count();
            $workingQuota     = min(6, $nonHolidayInWeek);
            $offDayQuotaByWeek->put($weekKey, $nonHolidayInWeek - $workingQuota);
        }
        $totalOffDayQuota = (int) $offDayQuotaByWeek->sum();

        // For the DAILY TABLE ONLY: best-effort pin each week's quota onto an actual
        // "no attendance, no leave" day (chronologically first), purely so the report
        // can show a concrete date. If the employee happened to have attendance on
        // every single day of a week, the quota still reduces Working Days below
        // (that's the whole point — we don't need a specific day to "spend" it on),
        // it just won't have a row visibly tagged "OFF DAY" that week.
        $offDayKeys = collect();
        foreach ($weekGroups as $weekKey => $daysInWeek) {
            $quota = $offDayQuotaByWeek->get($weekKey, 0);
            if ($quota <= 0) {
                continue;
            }

            $picked = 0;
            foreach ($daysInWeek as $key => $d) {
                if ($picked >= $quota) {
                    break;
                }
                if ($d['isHoliday'] || $d['leave'] || $d['hasAttendance']) {
                    continue;
                }
                $offDayKeys->push($key);
                $picked++;
            }
        }

        // ---- Fallback summary calculation (only runs if the controller/service didn't already provide one)
        $totalHariKerja  = $totalHariKerja  ?? 0; // Working Days = Present + Leave + Absent
        $totalHadir      = $totalHadir      ?? 0; // Present
        $totalCuti       = $totalCuti       ?? 0; // Leave
        $totalTidakHadir = $totalTidakHadir ?? 0; // Absent
        $totalOffDay     = $totalOffDay     ?? 0; // Off Day (weekly non-working day)
        $totalLiburNasional = $totalLiburNasional ?? 0; // National / company holidays
        $totalJamKerja   = $totalJamKerja   ?? 0;
        $totalLembur     = $totalLembur     ?? 0;
        $totalTelat      = $totalTelat      ?? 0;

        if (!isset($summaryProvided)) {
            $totalHadir      = 0;
            $totalCuti       = 0;
            $totalTidakHadir = 0;
            $totalLiburNasional = 0;
            $totalJamKerja   = 0;
            $totalLembur     = 0;
            $totalTelat      = 0;

            foreach ($dayData as $key => $d) {
                if ($d['isHoliday']) {
                    $totalLiburNasional++;
                    continue;
                }

                if ($offDayKeys->contains($key)) {
                    continue; // spent on this week's calendar off-day quota
                }

                if ($d['leave']) {
                    $totalCuti++;
                    continue;
                }

                if ($d['hasAttendance']) {
                    $totalHadir++;
                    $att = $d['attendance'];
                    $totalJamKerja += (float) ($att->total_hours ?? 0);
                    $totalLembur   += (float) ($att->overtime_hours ?? 0);
                    if ($att->is_late) {
                        $totalTelat += (float) ($att->late_hours ?? 0);
                    }
                } else {
                    $totalTidakHadir++;
                }
            }

            // Working Days and Off Day are CALENDAR totals (6 working days / week),
            // not a count of per-day rows — this is what guarantees the 6-day/week
            // rule holds even in the edge case where an employee has attendance on
            // every single day of a week (see Pass 2 above).
            $totalOffDay = $period->filter(function ($day) use ($offDays, $holidays) {

                if (isset($holidays[$day->format('Y-m-d')])) {
                    return false;
                }

                return in_array(
                    strtolower($day->englishDayOfWeek),
                    $offDays
                );
            })->count();
            $totalNonHoliday = $period->count() - $totalLiburNasional;
            $totalHariKerja  = max(0, $totalNonHoliday - $totalOffDay);
        }
    @endphp

    {{-- ===================== COMPANY LETTERHEAD ===================== --}}
    {{--
        NOTE: to avoid CORS / blocked-remote-resource issues when this view is
        rendered to PDF (dompdf / wkhtmltopdf run server-side, no browser CORS
        context, but remote HTTP(S) image fetches can still fail, hang, or be
        blocked depending on server/network config), make sure
        SettingsService::getCompanyLogo() returns either:
          - a local filesystem path (e.g. public_path('storage/logo.png')), or
          - a base64 data URI (e.g. 'data:image/png;base64,....')
        rather than a remote URL. Do not fetch the logo over HTTP(S) at render time.
    --}}
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
                        Phone: {{ $instansi->phone ?? app(\App\Services\SettingsService::class)->get('general.company_phone', '(021) 55 237 55', auth()->user()) }} &nbsp;|&nbsp;
                        Email: {{ $instansi->email ?? app(\App\Services\SettingsService::class)->get('general.company_email', 'https://gkisutopo.org', auth()->user()) }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- ===================== DOCUMENT TITLE ===================== --}}
    <div class="doc-title-wrap">
        <p class="doc-title">Laporan Kehadiran Pegawai</p>
    </div>

    {{-- ===================== META ===================== --}}
    <table class="meta-table">
        <tr>
            <td class="meta-label">No. Laporan</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">
                {{ $reportNumber ?? ('ATT/' . now()->format('Y/m') . '/' . str_pad($user->id ?? 0, 5, '0', STR_PAD_LEFT)) }}
            </td>
            <td class="meta-right">Tanggal Cetak: <strong>{{ now()->translatedFormat('d F Y') }}</strong></td>
        </tr>
        <tr>
            <td class="meta-label">Periode</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">
                {{ $startDate?->translatedFormat('d F Y') ?? '-' }} &ndash; {{ $endDate?->translatedFormat('d F Y') ?? '-' }}
            </td>
            <td class="meta-right">Dicetak oleh: <strong>{{ auth()->user()->name ?? 'Sistem' }}</strong></td>
        </tr>
    </table>

    {{-- ===================== EMPLOYEE INFORMATION ===================== --}}
    <div class="section-heading">Informasi Pegawai</div>
    <table class="info-table">
        <tr>
            <td class="label-col">Nama Pegawai</td>
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

    {{-- ===================== ATTENDANCE SUMMARY ===================== --}}
    <div class="section-heading">Ringkasan Kehadiran</div>
    <table class="summary-table cols-6">
        <tr>
            <td>
                <div class="summary-card info">
                    <span class="summary-value">{{ $totalHariKerja }}</span>
                    <span class="summary-unit">days</span>
                    <span class="summary-label">Hari Kerja</span>
                </div>
            </td>
            <td>
                <div class="summary-card success">
                    <span class="summary-value">{{ $totalHadir }}</span>
                    <span class="summary-unit">days</span>
                    <span class="summary-label">Hadir</span>
                </div>
            </td>
            <td>
                <div class="summary-card purple">
                    <span class="summary-value">{{ $totalCuti }}</span>
                    <span class="summary-unit">days</span>
                    <span class="summary-label">Cuti</span>
                </div>
            </td>
            <td>
                <div class="summary-card danger">
                    <span class="summary-value">{{ $totalTidakHadir }}</span>
                    <span class="summary-unit">days</span>
                    <span class="summary-label">Tidak Hadir</span>
                </div>
            </td>
            <td>
                <div class="summary-card gray">
                    <span class="summary-value">{{ $totalOffDay }}</span>
                    <span class="summary-unit">days</span>
                    <span class="summary-label">Hari Libur</span>
                </div>
            </td>
            <td>
                <div class="summary-card">
                    <span class="summary-value">{{ $totalLiburNasional }}</span>
                    <span class="summary-unit">days</span>
                    <span class="summary-label">Hari Libur Nasional</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="summary-table cols-4" style="margin-top: 8px;">
        <tr>
            <td>
                <div class="summary-card">
                    <span class="summary-value">{{ number_format($totalJamKerja, 1) }}</span>
                    <span class="summary-unit">hours</span>
                    <span class="summary-label">Total Jam Kerja</span>
                </div>
            </td>
            <td>
                <div class="summary-card warning">
                    <span class="summary-value">{{ number_format($totalLembur, 1) }}</span>
                    <span class="summary-unit">hours</span>
                    <span class="summary-label">Total Lembur</span>
                </div>
            </td>
            <td>
                <div class="summary-card danger">
                    <span class="summary-value">{{ number_format($totalTelat, 1) }}</span>
                    <span class="summary-unit">hours</span>
                    <span class="summary-label">Total Jam Terlambat</span>
                </div>
            </td>
            <td>
                <div class="summary-card">
                    <span class="summary-value">
                        {{ $totalHariKerja > 0 ? number_format(min(100, ($totalHadir / $totalHariKerja) * 100), 1) : 0 }}%
                    </span>
                    <span class="summary-unit">&nbsp;</span>
                    <span class="summary-label">Tingkat Kehadiran</span>
                </div>
            </td>
        </tr>
    </table>

    {{-- ===================== DAILY DETAILS ===================== --}}
    <div class="section-heading">Detail Kehadiran Harian</div>
    <table class="detail-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 9%;">Tanggal</th>
                <th style="width: 7%;">Hari</th>
                <th style="width: 8%;">Masuk</th>
                <th style="width: 8%;">Pulang</th>
                <th style="width: 8%;">Jam Kerja</th>
                <th style="width: 8%;">Lembur</th>
                <th style="width: 7%;">Terlambat (jam)</th>
                <th style="width: 9%;">Status</th>
                <th style="width: 32%;">Catatan</th>
            </tr>
        </thead>
        <tbody>
            
            @forelse ($period as $i => $day)
                @php
                    $key = $day->format('Y-m-d');
                    $d   = $dayData->get($key);

                    $isHoliday = $d['isHoliday'];
                    $leave     = $d['leave'];
                    $att       = $d['attendance'];
                    $isOffDay = in_array(
                        strtolower($day->englishDayOfWeek),
                        $offDays
                    );
                    $isAbsent  = !$isHoliday && !$leave && !$d['hasAttendance'] && !$isOffDay;

                    $rowClass = '';
                    if ($isHoliday) {
                        $rowClass = 'holiday-row';
                    } elseif ($leave) {
                        $rowClass = 'leave-row';
                    } elseif ($isOffDay) {
                        $rowClass = 'offday-row';
                    } elseif ($isAbsent) {
                        $rowClass = 'absent-row';
                    }
                @endphp
                <tr class="{{ $rowClass }}">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $day->format('d-m-Y') }}</td>
                    <td>{{ $day->translatedFormat('l') }}</td>

                    @if ($isHoliday)
                        <td colspan="5" class="text-left">
                            <span class="badge badge-info">LIBUR NASIONAL</span>
                            <span class="holiday-note">{{ $holidays[$key] }}</span>
                        </td>
                    @elseif ($leave)
                        <td colspan="5" class="text-left">
                            <span class="badge badge-purple">CUTI &ndash; {{ $leave->leave_type }}</span>
                            @if (!empty($leave->reason))
                                <span class="leave-note">{{ $leave->reason }}</span>
                            @endif
                        </td>
                    @elseif ($d['hasAttendance'])
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
                    @elseif ($isOffDay)
                        <td colspan="5" class="text-left">
                            <span class="badge badge-secondary">HARI LIBUR</span>
                        </td>
                        <td class="text-left">
                            Hari libur terjadwal ({{ $day->englishDayOfWeek }})
                        </td>
                    @else
                        <td colspan="5" class="text-left">
                            <span class="badge badge-danger">TIDAK HADIR</span>
                        </td>
                        <td class="text-left">No remarks</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 14px;">No data available for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ===================== LEGEND ===================== --}}
    <div class="legend-box">
        <span><span class="legend-dot" style="background:#eef2fb;"></span>Libur Nasional</span>
        <span><span class="legend-dot" style="background:#f3eefc;"></span>Cuti</span>
        <span><span class="legend-dot" style="background:#f5f5f7;"></span>Hari Libur</span>
        <span><span class="legend-dot" style="background:#fdecea;"></span>Tidak Hadir</span>
        <span><span class="badge badge-warning" style="margin-right:3px;">&nbsp;</span>Terlambat</span>
        <span><span class="badge badge-success" style="margin-right:3px;">&nbsp;</span>Tepat Waktu</span>
    </div>

    {{-- ===================== NOTE ===================== --}}
    <div class="legend-box" style="margin-top: 10px;">
        <strong>Catatan:</strong> Laporan ini dibuat otomatis oleh sistem berdasarkan catatan kehadiran
        dan data cuti yang telah disetujui. Penerapan minggu kerja 6 hari berlaku: setiap minggu kerja hanya membutuhkan
        6 hari kerja terlepas dari hari tertentu yang menjadi hari libur (minggu yang sudah memuat hari libur nasional
        tidak mendapatkan hari libur tambahan di atasnya). Jam kerja, lembur, dan keterlambatan dihitung sesuai kebijakan perusahaan yang berlaku.
    </div>

    {{-- ===================== SIGNATURE ===================== --}}
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-place-date">
                        {{ $instansi->city ?? 'Tangerang' }}, {{ now()->translatedFormat('d F Y') }}
                    </div>
                    <div>Ditinjau oleh,</div>
                    <div class="signature-name">{{ $reviewedBy ?? 'HR Staff' }}</div>
                    <div class="signature-position">Departemen Sumber Daya Manusia</div>
                </td>
                <td>
                    <div class="signature-place-date">&nbsp;</div>
                    <div>Disetujui oleh,</div>
                    <div class="signature-name">{{ $user->name ?? '-' }}</div>
                    <div class="signature-position">{{ $user->position ?? 'Employee' }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ===================== FOOTER ===================== --}}
    <div class="footer">
        Dokumen ini dibuat otomatis oleh sistem kehadiran {{ $instansi->name ?? config('app.name') }} &mdash;
        Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB
    </div>

</div>

</body>
</html>