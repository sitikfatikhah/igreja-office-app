<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body>

@php
    $settings = app(\App\Services\SettingsService::class)->all(auth()->user());

    $currency = data_get($settings,'payroll.currency','IDR');

    $companyName = app(\App\Services\SettingsService::class)->getCompanyName(auth()->user());
    $companyAddress = app(\App\Services\SettingsService::class)->get('general.company_address','-',auth()->user());
    $companyPhone = app(\App\Services\SettingsService::class)->get('general.company_phone','-',auth()->user());
    $companyEmail = app(\App\Services\SettingsService::class)->get('general.company_email','-',auth()->user());

    $basicSalary    = $payroll->basic_salary ?? $payroll->basic_pay ?? 0;
    $additionTotal  = $payroll->addition_total ?? $payroll->additions ?? 0;
    $overtimePay    = $payroll->overtime_pay ?? 0;

    $bpjsDeduction  = $payroll->bpjs_deduction ?? 0;
    $taxDeduction   = $payroll->tax_deduction ?? 0;
    $otherDeduction = $payroll->deduction_total ?? $payroll->deductions ?? 0;

    $grossTotal = $basicSalary + $additionTotal + $overtimePay;
    $totalDeduction = $bpjsDeduction + $taxDeduction + $otherDeduction;

    $preparedBy = data_get($settings,'payroll.prepared_by','HR/Payroll');
    $approvedBy = data_get($settings,'payroll.approved_by','Manager');
    $receivedBy = data_get($settings,'payroll.received_by',$payroll->user?->name);

    $city = data_get($settings,'general.company_city','Tangerang');
@endphp

<table>
    <tr>
        <td colspan="4"><strong>{{ $companyName }}</strong></td>
    </tr>

    <tr>
        <td colspan="4">{{ $companyAddress }}</td>
    </tr>

    <tr>
        <td colspan="4">
            {{ $companyPhone }}
            |
            {{ $companyEmail }}
        </td>
    </tr>

    <tr></tr>

    <tr>
        <td colspan="4" align="center">
            <strong>SLIP GAJI KARYAWAN</strong>
        </td>
    </tr>

    <tr></tr>

    <tr>
        <td>No Slip</td>
        <td>
            {{ $payroll->slip_number ?? ('SG/'.now()->format('Y/m').'/'.str_pad($payroll->id,5,'0',STR_PAD_LEFT)) }}
        </td>

        <td>Tanggal Cetak</td>
        <td>{{ now()->format('d-m-Y') }}</td>
    </tr>

    <tr>
        <td>Periode</td>
        <td>
            {{ optional($payroll->attendanceReport?->start_date)->format('d-m-Y') }}
            -
            {{ optional($payroll->attendanceReport?->end_date)->format('d-m-Y') }}
        </td>

        <td>Status</td>
        <td>{{ $payroll->status_label ?? 'Paid' }}</td>
    </tr>
</table>

<br>

<table border="1">

    <tr>
        <th colspan="4">DATA KARYAWAN</th>
    </tr>

    <tr>
        <td>Nama</td>
        <td>{{ $payroll->user?->name }}</td>

        <td>NIP</td>
        <td>{{ $payroll->user?->nip }}</td>
    </tr>

    <tr>
        <td>Jabatan</td>
        <td>{{ $payroll->user?->position }}</td>

        <td>Departemen</td>
        <td>{{ $payroll->user?->department }}</td>
    </tr>

    <tr>
        <td>Status</td>
        <td>{{ $payroll->user?->employment_status }}</td>

        <td>Rekening</td>
        <td>{{ $payroll->user?->bank_account }}</td>
    </tr>

</table>

<br>

<table border="1">

    <tr>
        <th colspan="2">RINGKASAN KEHADIRAN</th>
    </tr>

    <tr>
        <td>Total Jam Kerja</td>
        <td>{{ $payroll->attendanceReport?->total_hours }}</td>
    </tr>

    <tr>
        <td>Total Lembur</td>
        <td>{{ $payroll->attendanceReport?->total_overtime }}</td>
    </tr>

    <tr>
        <td>Total Terlambat</td>
        <td>{{ $payroll->attendanceReport?->total_late }}</td>
    </tr>

</table>

<br>

<table border="1">

    <tr>
        <th>Komponen</th>
        <th>Nominal ({{ $currency }})</th>
    </tr>

    <tr>
        <td>Gaji Pokok</td>
        <td>{{ $basicSalary }}</td>
    </tr>

    <tr>
        <td>Tunjangan</td>
        <td>{{ $additionTotal }}</td>
    </tr>

    <tr>
        <td>Upah Lembur</td>
        <td>{{ $overtimePay }}</td>
    </tr>

    <tr>
        <td><strong>Total Pendapatan</strong></td>
        <td><strong>{{ $grossTotal }}</strong></td>
    </tr>

    <tr>
        <td>Potongan BPJS</td>
        <td>{{ $bpjsDeduction }}</td>
    </tr>

    <tr>
        <td>Potongan Pajak</td>
        <td>{{ $taxDeduction }}</td>
    </tr>

    <tr>
        <td>Potongan Lainnya</td>
        <td>{{ $otherDeduction }}</td>
    </tr>

    <tr>
        <td><strong>Total Potongan</strong></td>
        <td><strong>{{ $totalDeduction }}</strong></td>
    </tr>

    <tr>
        <td><strong>TAKE HOME PAY</strong></td>
        <td><strong>{{ $payroll->net_pay }}</strong></td>
    </tr>

</table>

<br><br>

<table>

    <tr>
        <td colspan="3">{{ $city }}, {{ now()->format('d-m-Y') }}</td>
    </tr>

    <tr>
        <td align="center">Disiapkan Oleh</td>
        <td align="center">Disetujui Oleh</td>
        <td align="center">Diterima Oleh</td>
    </tr>

    <tr><td colspan="3"></td></tr>
    <tr><td colspan="3"></td></tr>
    <tr><td colspan="3"></td></tr>
    <tr><td colspan="3"></td></tr>

    <tr>
        <td align="center"><strong>{{ $preparedBy }}</strong></td>
        <td align="center"><strong>{{ $approvedBy }}</strong></td>
        <td align="center"><strong>{{ $receivedBy }}</strong></td>
    </tr>

</table>

</body>

</html>