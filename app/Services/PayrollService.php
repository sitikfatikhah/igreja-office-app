<?php

namespace App\Services;

use App\Models\Allowance;
use App\Models\AttendanceReport;
use App\Models\Compensations;
use App\Models\Overtimes;
use App\Models\Payrolls;

class PayrollService
{
    public function generate(
    AttendanceReport $attendanceReport
        ): Payrolls {

            $compensation = Compensations::where('user_id', $attendanceReport->user_id)
                ->first();

            if (! $compensation) {
                throw new \Exception('Compensation not found');
            }

            $overtimeHours = $attendanceReport->total_overtime;

            $overtimePay =$overtimeHours *($compensation->overtime_rate ?? 0);

            $allowanceTotal = Allowance::sum('amount');

            $deductions = ($attendanceReport->total_late ?? 0) * 10000;

            $grossPay =
                $compensation->basic_salary +
                $overtimePay +
                $allowanceTotal;

            $netPay = $grossPay - $deductions;

            return Payrolls::updateOrCreate(
                [
                    'attendance_report_id' => $attendanceReport->id,
                ],
                [
                    'user_id' => $attendanceReport->user_id,

                    'start_date' => $attendanceReport->start_date,
                    'end_date' => $attendanceReport->end_date,

                    'gross_pay' => $grossPay,
                    'net_pay' => $netPay,
                    'additions' => $overtimePay + $allowanceTotal,
                    'deductions' => $deductions,

                    'generated_at' => now(),
                    'status' => 'generated',
                ]
            );
        }
}