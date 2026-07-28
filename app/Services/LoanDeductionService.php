<?php

namespace App\Services;

use App\Models\EmployeeLoan;
use App\Models\LoanInstallments;
use Illuminate\Support\Collection;


class LoanDeductionService
{
    public function calculateDueInstallments(int $userId): float
    {
        return EmployeeLoan::where('user_id', $userId)
            ->where('status', 'active')
            ->get()
            ->sum(fn ($loan) => min($loan->installment_amount, $loan->remaining_balance));
    }

    public function getPendingInstallments(int $userId): Collection
    {
        return LoanInstallments::query()
            ->with('employeeLoan')
            ->where('status', 'pending')
            ->whereHas('employeeLoan', fn ($query) => $query->where('user_id', $userId))
            ->get();
    }

    public function recordDeductions(int $userId, int $payrollId): void
    {
        $loans = EmployeeLoan::where('user_id', $userId)
            ->where('status', 'active')
            ->get();

        foreach ($loans as $loan) {
            $amount = min($loan->installment_amount, $loan->remaining_balance);

            if ($amount <= 0) {
                continue;
            }

            LoanInstallments::create([
                'employee_loan_id' => $loan->id,
                'payroll_id' => $payrollId,
                'amount' => $amount,
                'status' => 'pending',
                'deducted_at' => now(),
            ]);

            $loan->decrement('remaining_balance', $amount);

            if ($loan->remaining_balance <= 0) {
                $loan->update(['status' => 'paid_off']);
            }
        }
    }
}