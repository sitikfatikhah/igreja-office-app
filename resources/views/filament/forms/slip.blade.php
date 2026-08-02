<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }

        .page {
            padding: 24px 32px;
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
            width: 70px;
        }

        .kop .logo-box {
            width: 60px;
            height: 60px;
            border: 1px solid #1f2d3d;
            border-radius: 4px;
            text-align: center;
            line-height: 60px;
            font-size: 10px;
            color: #888;
        }

        .kop .instansi-name {
            font-size: 18px;
            font-weight: bold;
            color: #1f2d3d;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .kop .instansi-detail {
            font-size: 10.5px;
            color: #555;
            margin: 2px 0 0 0;
            line-height: 1.5;
        }

        /* ===== DOCUMENT TITLE ===== */
        .doc-title-wrap {
            text-align: center;
            margin-bottom: 14px;
        }

        .doc-title {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0;
            color: #1f2d3d;
        }

        .doc-subtitle {
            font-size: 11px;
            color: #555;
            margin-top: 2px;
        }

        /* ===== META INFO (Slip number, date) ===== */
        .meta-table {
            width: 100%;
            border: none;
            margin-bottom: 14px;
            font-size: 11.5px;
        }

        .meta-table td {
            border: none;
            padding: 1px 0;
        }

        .meta-table .meta-label {
            width: 110px;
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

        /* ===== EMPLOYEE INFO ===== */
        .section-heading {
            background-color: #1f2d3d;
            color: #ffffff;
            font-size: 11.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 10px;
            margin: 18px 0 0 0;
            letter-spacing: 0.3px;
        }

        table.info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        table.info-table td {
            border: 1px solid #cfd4da;
            padding: 6px 10px;
            font-size: 11.5px;
        }

        table.info-table .label-col {
            width: 28%;
            color: #555;
            background-color: #f7f8fa;
        }

        table.info-table .value-col {
            width: 22%;
            font-weight: 600;
        }

        /* ===== SALARY BREAKDOWN TABLE ===== */
        table.salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }

        table.salary-table th {
            background-color: #1f2d3d;
            color: #ffffff;
            border: 1px solid #1f2d3d;
            padding: 7px 10px;
            font-size: 11.5px;
            text-align: left;
            text-transform: uppercase;
        }

        table.salary-table td {
            border: 1px solid #cfd4da;
            padding: 7px 10px;
            font-size: 11.5px;
        }

        table.salary-table .text-right {
            text-align: right;
        }

        table.salary-table .col-label {
            width: 70%;
        }

        table.salary-table .col-amount {
            width: 30%;
        }

        table.salary-table .subtotal-row td {
            font-weight: bold;
            background-color: #f7f8fa;
        }

        table.salary-table .grand-total-row td {
            font-weight: bold;
            font-size: 13px;
            background-color: #1f2d3d;
            color: #ffffff;
            border: 1px solid #1f2d3d;
        }

        .text-success {
            color: #1a7a3d;
        }

        .text-danger {
            color: #b8312f;
        }

        /* ===== AMOUNT IN WORDS ===== */
        .terbilang-box {
            margin-top: 10px;
            padding: 8px 10px;
            border: 1px dashed #888;
            background-color: #f7f8fa;
            font-size: 11px;
            font-style: italic;
        }

        .terbilang-label {
            font-weight: bold;
            font-style: normal;
        }

        /* ===== NOTES ===== */
        .notes-box {
            margin-top: 14px;
            font-size: 10.5px;
            color: #555;
            line-height: 1.6;
        }

        .notes-box ol {
            margin: 4px 0 0 0;
            padding-left: 16px;
        }

        /* ===== SIGNATURE ===== */
        .signature-section {
            width: 100%;
            margin-top: 36px;
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
            font-size: 11.5px;
            padding: 0 20px;
        }

        .signature-role {
            margin-bottom: 2px;
        }

        .signature-place-date {
            margin-bottom: 70px;
            color: #333;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 4px;
        }

        .signature-position {
            color: #555;
            font-size: 10.5px;
            margin-top: 2px;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 28px;
            border-top: 1px solid #cfd4da;
            padding-top: 8px;
            font-size: 9.5px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="page">
    @php
        use Carbon\Carbon;

        $settings = app(\App\Services\SettingsService::class)->all(auth()->user());

        $currency = data_get($settings, 'payroll.currency', 'IDR');

        $companyName = app(\App\Services\SettingsService::class)
            ->getCompanyName(auth()->user());

        $companyLogo = app(\App\Services\SettingsService::class)
            ->getCompanyLogo(auth()->user());

        $companyAddress = app(\App\Services\SettingsService::class)
            ->get('general.company_address', '-', auth()->user());

        $companyPhone = app(\App\Services\SettingsService::class)
            ->get('general.company_phone', '-', auth()->user());

        $companyEmail = app(\App\Services\SettingsService::class)
            ->get('general.company_email', '-', auth()->user());

        $companyWebsite = app(\App\Services\SettingsService::class)
            ->get('general.company_website', '-', auth()->user());

        $basicSalary     = $payroll->basic_salary ?? $payroll->basic_pay ?? 0;
        $additionTotal   = $payroll->addition_total ?? $payroll->additions ?? 0;
        $overtimePay     = $payroll->overtime_pay ?? 0;

        $bpjsDeduction   = $payroll->bpjs_deduction ?? 0;
        $taxDeduction    = $payroll->tax_deduction ?? 0;
        $otherDeduction  = $payroll->deduction_total ?? $payroll->deductions ?? 0;

        $grossTotal = $basicSalary + $additionTotal + $overtimePay;
        $totalDeduction = $bpjsDeduction + $taxDeduction + $otherDeduction;

        $preparedBy = app(\App\Services\SettingsService::class)->get(
        'payroll.prepared_by',
        'HR/Payroll Staff',
        auth()->user()
        );

        $approvedBy = app(\App\Services\SettingsService::class)->get(
            'payroll.approved_by',
            'Manager',
            auth()->user()
        );

        $receivedBy = app(\App\Services\SettingsService::class)->get(
            'payroll.received_by',
            $payroll->user?->name,
            auth()->user()
        );

        $city = app(\App\Services\SettingsService::class)->get(
            'general.company_city',
            'Tangerang',
            auth()->user()
        );

        $instansi = $instansi ?? (object) [
            'name' => $companyName,
            'address' => $companyAddress,
            'phone' => $companyPhone,
            'email' => $companyEmail,
            'website' => $companyWebsite,
            'city' => $city,
        ];
    @endphp

    {{-- ===================== COMPANY LETTERHEAD ===================== --}}
    <div class="kop">
        <table>
            <tr>
                <td width="90" style="text-align:center; vertical-align:middle;">
                    <img
                        src="{{ $companyLogo }}"
                        alt="{{ $instansi->name ?? $companyName }}"
                        width="75"
                        height="75"
                    >
                </td>
                <td>
                    <p class="instansi-name">{{ $instansi->name ?? $companyName }}</p>
                    <p class="instansi-detail">
                        {{ $instansi->address ?? $companyAddress }}<br>
                        Phone: {{ $instansi->phone ?? $companyPhone }} &nbsp;|&nbsp;
                        Email: {{ $instansi->email ?? $companyEmail }} &nbsp;|&nbsp;
                        {{ $instansi->website ?? $companyWebsite }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- ===================== DOCUMENT TITLE ===================== --}}
    <div class="doc-title-wrap">
        <p class="doc-title">Slip Gaji Pegawai</p>
        <p class="doc-subtitle">Slip Gaji Pegawai</p>
    </div>

    {{-- ===================== META: SLIP NUMBER & DATE ===================== --}}
    <table class="meta-table">
        <tr>
            <td class="meta-label">No. Slip</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $payroll->slip_number ?? ('SG/' . now()->format('Y/m') . '/' . str_pad($payroll->id ?? 0, 5, '0', STR_PAD_LEFT)) }}</td>
            <td class="meta-right">
                Tanggal Cetak: <strong>{{ now()->translatedFormat('d F Y') }}</strong>
            </td>
        </tr>
        <tr>
            <td class="meta-label">Periode Pembayaran</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">
                {{ \Carbon\Carbon::parse($payroll->attendanceReport?->start_date)->translatedFormat('d F Y') }}
                &ndash;
                {{ \Carbon\Carbon::parse($payroll->attendanceReport?->end_date)->translatedFormat('d F Y') }}
            </td>
            <td class="meta-right">
                Status: <strong>{{ $payroll->status_label ?? 'Dibayar' }}</strong>
            </td>
        </tr>
    </table>

    {{-- ===================== EMPLOYEE INFORMATION ===================== --}}
    <div class="section-heading">Informasi Pegawai</div>
    <table class="info-table">
        <tr>
            <td class="label-col">Nama Pegawai</td>
            <td class="value-col">{{ $payroll->user?->name ?? '-' }}</td>
            <td class="label-col">NIP / ID Pegawai</td>
            <td class="value-col">{{ $payroll->user?->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col">Position</td>
            <td class="value-col">{{ $payroll->user?->position ?? $payroll->position ?? '-' }}</td>
            <td class="label-col">Department</td>
            <td class="value-col">{{ $payroll->user?->department ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col">Status Kepegawaian</td>
            <td class="value-col">{{ $payroll->user?->employment_status ?? '-' }}</td>
            <td class="label-col">No. Rekening</td>
            <td class="value-col">{{ $payroll->user?->bank_account ?? '-' }}</td>
        </tr>
    </table>

    {{-- ===================== ATTENDANCE SUMMARY ===================== --}}
    <div class="section-heading">Ringkasan Kehadiran</div>
    <table class="info-table">
        <tr>
            <td class="label-col">Total Jam Kerja</td>
            <td class="value-col">{{ $payroll->attendanceReport?->total_hours ?? 0 }} hours</td>
            <td class="label-col">Total Jam Lembur</td>
            <td class="value-col">{{ $payroll->attendanceReport?->total_overtime ?? 0 }} hours</td>
        </tr>
        <tr>
            <td class="label-col">Total Jam Terlambat</td>
            <td class="value-col">{{ $payroll->attendanceReport?->total_late ?? 0 }} hours</td>
            <td class="label-col">Status Kehadiran</td>
            <td class="value-col">{{ ucfirst($payroll->attendanceReport?->status ?? '-') }}</td>
        </tr>
    </table>

    {{-- ===================== SALARY BREAKDOWN ===================== --}}
    <div class="section-heading">Rincian Gaji</div>
    
    @php
    $currency = data_get($settings,'payroll.currency', 'IDR');
    @endphp

    <table class="salary-table">
        <thead>
            <tr>
                <th class="col-label">Komponen</th>
                <th class="col-amount text-right">Nominal ({{ data_get($settings,'payroll.currency', 'IDR') }})</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gaji Pokok</td>
                <td class="text-right"> {{ $currency }} {{ number_format($payroll->basic_pay ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan &amp; Insentif Lainnya</td>
                <td class="text-right"> {{ $currency }} {{ number_format($payroll->addition_total ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan Lembur ({{ $payroll->attendanceReport?->total_overtime ?? 0 }} jam)</td>
                <td class="text-right"> {{ $currency }} {{ number_format($payroll->overtime_pay ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr class="subtotal-row">
                <td>Total Pendapatan (Kotor)</td>
                <td class="text-right text-success">
                    {{ $currency }} {{ number_format(
                        ($payroll->basic_salary ?? 0)
                        + ($payroll->addition_total ?? 0)
                        + ($payroll->overtime_pay ?? 0),
                        0,
                        ',',
                        '.'
                    ) }}
                </td>
            </tr>

            
                
            <tr>
                <td>Jaminan Kesehatan &amp; Ketenagakerjaan (BPJS)</td>
                <td class="text-right text-danger">- {{ $currency }} {{ number_format($payroll->bpjs_deduction ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pajak Penghasilan (PPh 21)</td>
                <td class="text-right text-danger">
                    - {{ $currency }}
                    {{ number_format(data_get($settings, 'payroll.tax_rate', 0), 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td>Potongan Lainnya</td>
                <td class="text-right text-danger">- {{ $currency }} {{ number_format($payroll->deduction_total ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr class="subtotal-row">
                <td>Total Potongan</td>
                <td class="text-right text-danger">
                    - {{ $currency }} {{ number_format(
                        ($payroll->bpjs_deduction ?? 0)
                        + ($payroll->tax_deduction ?? 0)
                        + ($payroll->deduction_total ?? 0),
                        0,
                        ',',
                        '.'
                    ) }}
                </td>
            </tr>

            <tr class="grand-total-row">
                <td>GAJI DITERIMA (THP)</td>
                <td class="text-right">{{ $currency }} {{ number_format($payroll->net_pay ?? 0, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- ===================== AMOUNT IN WORDS ===================== --}}
    {{-- <div class="terbilang-box">
        <span class="terbilang-label">Amount in words:</span>
        {{ ucwords(\App\Helpers\Terbilang::make($payroll->net_pay ?? 0) ?? '-') }} Rupiah
    </div> --}}

    {{-- ===================== NOTES ===================== --}}
    <div class="notes-box">
        <strong>Catatan:</strong>
        <ol>
            <li>Slip gaji ini dibuat secara otomatis oleh sistem dan berlaku tanpa cap basah setelah ditandatangani secara digital.</li>
            <li>Segera hubungi bagian HR/Payroll jika terdapat data pada slip gaji ini yang tampak tidak sesuai.</li>
            <li>Dokumen ini bersifat rahasia dan hanya ditujukan untuk pegawai yang namanya tercantum di atas.</li>
        </ol>
    </div>

    {{-- ===================== SIGNATURE ===================== --}}
    <div class="signature-section">
        <table class="signature-table">
            <tr>

                <td style="width:33%">
                    <div class="signature-place-date">
                        {{ $city }}, {{ now()->translatedFormat('d F Y') }}
                    </div>

                    <div class="signature-role">
                        Disiapkan Oleh
                    </div>

                    <br><br><br><br>

                    <div class="signature-name">
                        {{ $preparedBy }}
                    </div>

                    <div class="signature-position">
                        HR / Payroll
                    </div>
                </td>

                <td style="width:33%">
                    <div class="signature-place-date">&nbsp;</div>

                    <div class="signature-role">
                        Disetujui Oleh
                    </div>

                    <br><br><br><br>

                    <div class="signature-name">
                        {{ $approvedBy }}
                    </div>

                    <div class="signature-position">
                        Approver
                    </div>
                </td>

                <td style="width:33%">
                    <div class="signature-place-date">&nbsp;</div>

                    <div class="signature-role">
                        Diterima Oleh
                    </div>

                    <br><br><br><br>

                    <div class="signature-name">
                        {{ $receivedBy }}
                    </div>

                    <div class="signature-position">
                        {{ $payroll->user?->position ?? 'Karyawan' }}
                    </div>
                </td>

            </tr>
        </table>
    </div>

    {{-- ===================== FOOTER ===================== --}}
    <div class="footer">
        Dokumen ini dibuat otomatis oleh sistem penggajian {{ $instansi->name ?? config('app.name') }} &mdash;
        Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB
    </div>

</div>

</body>
</html>