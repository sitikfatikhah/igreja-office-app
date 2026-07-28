<?php

namespace App\Services;

use App\Models\AnnualLeaveBalance;
use App\Models\LeaveDepositBalance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class LeaveDeductionService
{
    public function hasSufficientBalance(
        int $userId,
        int $totalDays,
        string $source,
        ?int $leaveDepositBalanceId,
        \Carbon\Carbon $startDate
    ): bool {
        if ($source === 'annual_leave') {
            return AnnualLeaveBalance::currentBalance($userId, $startDate->year) >= $totalDays;
        }

        if ($source === 'time_bank') {
            return LeaveDepositBalance::where('id', $leaveDepositBalanceId)
                ->where('user_id', $userId)
                ->where('balanced', '>=', $totalDays)
                ->exists();
        }

        return false;
    }

    private function latestTimeBankBalance(int $userId): ?LeaveDepositBalance
    {
        return LeaveDepositBalance::where('user_id', $userId)
            ->latest('id')
            ->first();
    }
    private function latestAnnualLeaveBalance(int $userId, int $year): int
    {
        return AnnualLeaveBalance::currentBalance($userId, $year);
    }

    public function remainingBalance(
        int $userId,
        string $source,
            Carbon $startDate
    ): int {

            if ($source === 'annual_leave') {
                return $this->latestAnnualLeaveBalance(
                    $userId,
                    $startDate->year
                );
            }

            $balance = $this->latestTimeBankBalance($userId);

            return $balance?->balanced ?? 0;
    }

    public function deductAnnualLeaveBalance(LeaveRequest $request): void
    {
        $year = $request->start_date->year;

        $lastBalance = AnnualLeaveBalance::where('user_id', $request->user_id)
            ->where('year', $year)
            ->latest('id')
            ->value('balanced') ?? 0;

        AnnualLeaveBalance::create([
            'user_id'          => $request->user_id,
            'leave_request_id' => $request->id,
            'year'             => $year,
            'leave_type'       => $request->leave_type,
            'days'             => -$request->total_days,
            'type'             => 'debit',
            'balanced'         => $lastBalance - $request->total_days,
            'description'      => "Leave deduction #{$request->id}",
        ]);
    }
    public function deductTimeBankBalance(LeaveRequest $request): void
    {
        $lastBalance = LeaveDepositBalance::where('id', $request->leave_deposit_balance_id)
            ->where('user_id', $request->user_id)
            ->latest('id')
            ->value('balanced') ?? 0;

        LeaveDepositBalance::create([
            'user_id'          => $request->user_id,
            'leave_request_id' => $request->id,
            'days'             => -$request->total_days,
            'type'             => 'debit',
            'balanced'         => $lastBalance - $request->total_days,
            'description'      => "Leave deduction #{$request->id}",
        ]);
    }

    public function deduct(LeaveRequest $request): void
    {
        switch ($request->source) {
            case 'annual_leave':
                $this->deductAnnualLeaveBalance($request);
                return;

            case 'time_bank':
                $this->deductTimeBankBalance($request);
                return;
        }

        throw new \InvalidArgumentException('INVALID_SOURCE');
    }
}