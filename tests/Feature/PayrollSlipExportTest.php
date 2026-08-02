<?php

namespace Tests\Feature;

use App\Exports\PayrollSlipExport;
use App\Models\Payrolls;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class PayrollSlipExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_payroll_slip_pdf_and_excel_exports_can_be_generated(): void
    {
        $user = \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test-user@example.com',
            'password' => bcrypt('password'),
            'position' => 'Staff',
            'nip' => '1234567890',
            'department' => 'HR',
            'allowance_id' => 0,
            'compensation_id' => 0,
        ]);

        $payroll = new Payrolls();
        $attendanceReport = \App\Models\AttendanceReport::create([
            'user_id' => $user->id,
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-31',
            'total_present' => 20,
            'total_absent' => 0,
            'total_overtime' => 3,
            'total_leave' => 0,
            'total_hours' => 160,
            'attendances_in_period' => 20,
            'status' => 'present',
            'report_date' => '2026-07-31',
            'description' => 'Test report',
        ]);

        $payroll->forceFill([
            'id' => 999,
            'user_id' => $user->id,
            'attendance_report_id' => $attendanceReport->id,
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-31',
            'gross_pay' => 1500000,
            'net_pay' => 1300000,
            'deduction_total' => 75000,
            'addition_total' => 250000,
            'loan_total' => 0,
            'overtime_total' => 0,
            'generated_at' => now(),
            'status' => 'paid',
        ]);
        $payroll->save();

        $pdf = Pdf::loadView('filament.forms.slip', ['payroll' => $payroll]);
        $pdfOutput = $pdf->output();

        $this->assertNotEmpty($pdfOutput);
        $this->assertStringStartsWith('%PDF', $pdfOutput);

        $excel = Excel::raw(new PayrollSlipExport($payroll), \Maatwebsite\Excel\Excel::XLSX);

        $this->assertNotEmpty($excel);
        $this->assertStringStartsWith('PK', $excel);
    }
}
