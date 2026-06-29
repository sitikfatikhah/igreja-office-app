<!DOCTYPE html>
<html>
<head>
    <style>
        body{
            font-family: sans-serif;
            font-size:12px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        td{
            border:1px solid #ddd;
            padding:8px;
        }

        .title{
            text-align:center;
            font-size:18px;
            font-weight:bold;
            margin-bottom:20px;
        }
    </style>
</head>
<body>

<div class="title">
    SLIP GAJI KARYAWAN
</div>

<table>
    <tr>
        <td>Nama</td>
        <td>{{ $payroll->user?->name ?? '-' }}</td>
    </tr>

    <tr>
        <td>Periode</td>
        <td>
            {{ $payroll->attendanceReport?->start_date ?? '-' }}
            -
            {{ $payroll->attendanceReport?->end_date ?? '-' }}
        </td>
    </tr>

    <tr>
        <td>Total Jam Kerja</td>
        <td>{{ $payroll->attendanceReport?->total_hours ?? 0 }}</td>
    </tr>

    <tr>
        <td>Lembur</td>
        <td>{{ $payroll->attendanceReport?->total_overtime ?? 0 }}</td>
    </tr>

    <tr>
        <td>Terlambat</td>
        <td>{{ $payroll->attendanceReport?->total_late ?? 0 }}</td>
    </tr>
</table>

<br>

<table>
    <tr>
        <td>Gaji Pokok</td>
        <td>Rp {{ number_format($payroll->gross_pay ?? 0, 0, ',', '.') }}</td>
    </tr>

    <tr>
        <td>Tambahan</td>
        <td>Rp {{ number_format($payroll->additions ?? 0, 0, ',', '.') }}</td>
    </tr>

    <tr>
        <td>Potongan</td>
        <td>Rp {{ number_format($payroll->deductions ?? 0, 0, ',', '.') }}</td>
    </tr>

    <tr>
        <td><strong>Take Home Pay</strong></td>
        <td>
            <strong>
                Rp {{ number_format($payroll->net_pay ?? 0, 0, ',', '.') }}
            </strong>
        </td>
    </tr>
</table>

</body>
</html>