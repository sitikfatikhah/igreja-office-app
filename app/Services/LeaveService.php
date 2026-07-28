<?php

namespace App\Services;

use App\Models\LeaveRequest;
use Illuminate\Support\Facades\DB;

class LeaveService
{
    public function approve(LeaveRequest $record, int $approverId): LeaveRequest
    {
        if ($record->approval_status === 'Approved') {
            throw new \Exception('ALREADY_APPROVED');
        }

        return DB::transaction(function () use ($record, $approverId) {
            switch ($record->source) {
                case 'annual_leave':
                    app(LeaveDeductionService::class)->deductAnnualLeaveBalance($record);
                    break;

                case 'time_bank':
                    app(LeaveDeductionService::class)->deductTimeBankBalance($record);
                    break;

                default:
                    throw new \Exception('INVALID_SOURCE');
            }

            $record->update([
                'approval_status' => 'Approved',
                'approved_by' => $approverId,
            ]);

            return $record->fresh();
        });
    }

    public function reject(LeaveRequest $record, int $approverId): LeaveRequest
    {
        if ($record->approval_status === 'Approved') {
            throw new \Exception('ALREADY_APPROVED');
        }

        $record->update([
            'approval_status' => 'Rejected',
            'approved_by' => $approverId,
        ]);

        return $record->fresh();
    }
}
