<?php

namespace App\Services;

use App\Models\Allowance;
use App\Models\Attendance;
use App\Models\AttendanceReport;
use App\Models\Compensations;
use App\Models\EmployeeWorkSchedule;
use App\Models\LoanInstallments;
use App\Models\Overtimes;
use App\Models\PayrollDetail;
use App\Models\Payrolls;
use App\Services\LoanDeductionService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PayrollService
{
     public function __construct(
        protected SettingsService $settings,
        protected LoanDeductionService $loanDeductionService,
    ) {}

    public function generate(AttendanceReport $attendanceReport): Payrolls
    {
        return DB::transaction(function () use ($attendanceReport) {
            $attendanceReport->loadMissing([
                'user.compensation',
                'user.allowance',
                'user.employeeLoan.installments',
            ]);

            $user = $attendanceReport->user;

            $attendances = Attendance::query()
                ->select(['user_id','date','check_in','check_out'])
                ->where('user_id', $attendanceReport->user_id)
                ->whereBetween('date', [
                    $attendanceReport->start_date,
                    $attendanceReport->end_date,
                ])
                ->get();

            $details = collect();

            /*
            |--------------------------------------------------------------------------
            | Attendance Summary
            |--------------------------------------------------------------------------
            */

            // $workingDays = $attendanceReport->total_present;
            
            // $presentDays = $attendanceReport->total_present;
            // $absentDays  = $attendanceReport->total_absent;
            // $leaveDays = $attendanceReport->total_leave;

            // $workingDays =
            //     $presentDays +
            //     $absentDays +
            //     $leaveDays;
            // $totalHours   = $attendanceReport->total_hours;
            // $overtimeHours = $attendanceReport->total_overtime;

            /*
            |--------------------------------------------------------------------------
            | Basic Salary
            |--------------------------------------------------------------------------
            */

            $compensation = $user->compensation;

            if (!$compensation) {
                throw new \Exception('Employee has no compensation.');
            }

            $basicSalary = $compensation->basic_salary;
           

            $details->push([
                'type'=>'earning',
                'category'=>'basic_salary',
                'description'=>'Basic Salary',
                'qty'=>1,
                'rate'=>$basicSalary,
                'amount'=>$basicSalary,
                'reference_type'=> null,
                'reference_id'=> null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Allowances
            |--------------------------------------------------------------------------
            */

            $allowances = $user->allowance;

            foreach ($allowances as $allowance) {

                $qty = 1;
                $rate = $allowance->amount;

                switch ($allowance->calculation_type) {

                    case 'attendance':
                        $qty = $attendanceReport->total_present;
                        break;

                    case 'overtime':
                        $qty = $attendanceReport->total_overtime ?? 0;
                        break;

                    default:
                        $qty = 1;
                        break;
                }

                $details->push([
                    'type' => 'earning',
                    'category' => 'allowance',
                    'description' => $allowance->type,
                    'qty' => $qty,
                    'rate' => $rate,
                    'amount' => $qty * $rate,
                    'reference_type' => Allowance::class,
                    'reference_id' => $allowance->id,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Overtime
            |--------------------------------------------------------------------------
            */
            $overtimes = Overtimes::query()
                ->where('user_id', $attendanceReport->user_id)
                ->where('approval_status', 'Approved')
                ->whereBetween('overtime_date', [
                    $attendanceReport->start_date,
                    $attendanceReport->end_date,
                ])
                ->get();

            $freeHours = (float) $this->settings->get('payroll.free_overtime_hours');
            $maxHours = (float) $this->settings->get('payroll.max_paid_overtime_hours');
            $overtimeRate = (float) $this->settings->get('payroll.overtime_rate');
            $monthlyHours = (float) $this->settings->get('payroll.monthly_working_hours');

            $paidHours = 0;

            foreach ($overtimes as $overtime) {
                $hours = (float) $overtime->total_hours;

                if ($hours <= $freeHours) {
                    continue;
                }

                $paidHours += min($hours - $freeHours, $maxHours);
            }

            $hourlyRate = $basicSalary / max($monthlyHours, 1);

            $overtimePay = round(
                $paidHours * $hourlyRate * $overtimeRate,
                2
            );

            if ($overtimePay > 0) {
                $details->push([
                    'type' => 'earning',
                    'category' => 'overtime',
                    'description' => 'Overtime',
                    'qty' => $paidHours,
                    'rate' => $hourlyRate,
                    'amount' => $overtimePay,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Attendance Deduction
            |--------------------------------------------------------------------------
            */

            // $workingDays =
            //     $attendanceReport->total_present +
            //     $attendanceReport->total_absent +
            //     $attendanceReport->total_leave_days;

            // $dailyRate = $basicSalary / max($workingDays, 1);

            // if ($absentDeduction > 0) {

            //     $details->push([
            //         'type' => 'deduction',
            //         'category' => 'absence',
            //         'description' => 'Absent Deduction',
            //         'qty' => $absentDays,
            //         'rate' => $dailyRate,
            //         'amount' => $absentDeduction,
            //     ]);
            // }

            /*
            |--------------------------------------------------------------------------
            | Loan Installments
            |--------------------------------------------------------------------------
            */

            $loanInstallments = $this->loanDeductionService->getPendingInstallments($attendanceReport->user_id);

            foreach ($loanInstallments as $loan) {
                $details->push([
                    'type' => 'deduction',
                    'category' => 'loan',
                    'description' => $loan->description ?? 'Loan Installment',
                    'qty' => 1,
                    'rate' => $loan->amount,
                    'amount' => $loan->amount,
                    'reference_type' => LoanInstallments::class,
                    'reference_id' => $loan->id,
                ]);
            }
            
            /*
            |--------------------------------------------------------------------------
            | Summary
            |--------------------------------------------------------------------------
            */

            $grossPay = $details
                ->where('type', 'earning')
                ->sum('amount');

            $additionTotal = $details
                ->whereIn('category', [
                    'allowance',
                    'overtime',
                ])
                ->sum('amount');

            $loanTotal = $details
                ->where('category', 'loan')
                ->sum('amount');

            $deductionTotal = $details
                ->where('type', 'deduction')
                ->sum('amount');

            $taxRate = (float) $this->settings->get('payroll.tax_rate', 0);
            $taxAmount = round($grossPay * ($taxRate / 100), 2);

            if ($taxAmount > 0) {
                $details->push([
                    'type' => 'deduction',
                    'category' => 'tax',
                    'description' => 'Income Tax',
                    'qty' => 1,
                    'rate' => $taxRate,
                    'amount' => $taxAmount,
                ]);

                $deductionTotal += $taxAmount;
            }

            $netPay = $grossPay - $deductionTotal;

            /*
            |--------------------------------------------------------------------------
            | Save Payroll
            |--------------------------------------------------------------------------
            */

            $payroll = Payrolls::updateOrCreate(
                [
                    'attendance_report_id' => $attendanceReport->id,
                ],
                [
                    'user_id' => $attendanceReport->user_id,
                    'start_date' => $attendanceReport->start_date,
                    'end_date' => $attendanceReport->end_date,
                    'gross_pay' => $grossPay,
                    'addition_total' => $additionTotal,
                    'deduction_total' => $deductionTotal,
                    'loan_total' => $loanTotal,
                    'overtime_total' => $overtimePay,
                    'net_pay' => $netPay,
                    'generated_at' => now(),
                    'status' => 'generated',
                ]
            );

            // dd([
            //     'allowances' => $allowances->count(),
            //     'overtimes' => $overtimes->count(),
            //     'loanInstallments' => $loanInstallments->count(),
            //     'payroll' => $payroll,
            //     'gross_pay' => $grossPay,
            //     'net_pay' => $netPay,
            //     'details' => $details->toArray(),
            // ]);

            /*
            |--------------------------------------------------------------------------
            | Payroll Details
            |--------------------------------------------------------------------------
            */

            $payroll->details()->delete();

            $payroll->details()->createMany(
                $details->map(fn ($detail) => [
                    'user_id' => $attendanceReport->user_id,
                    'type' => $detail['type'],
                    'category' => $detail['category'],
                    'description' => $detail['description'],
                    'qty' => $detail['qty'],
                    'rate' => $detail['rate'],
                    'amount' => $detail['amount'],
                    'reference_type' => $detail['reference_type'] ?? null,
                    'reference_id' => $detail['reference_id'] ?? null,
                ])->toArray()
            );

            foreach ($loanInstallments as $loan) {
                $loan->update([
                    'status' => 'paid',
                    'payroll_id' => $payroll->id,
                ]);
            }

            return $payroll;


           
        });
    }
    
}