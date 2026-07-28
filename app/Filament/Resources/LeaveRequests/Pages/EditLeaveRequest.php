<?php

namespace App\Filament\Resources\LeaveRequests\Pages;

use App\Filament\Resources\LeaveRequests\LeaveRequestResource;
use App\Services\LeaveDeductionService;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLeaveRequest extends EditRecord
{
    protected static string $resource = LeaveRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    private bool $wasApproved = false;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->wasApproved = $this->record->approval_status !== 'Approved'
            && ($data['approval_status'] ?? null) === 'Approved';

        if ($this->wasApproved) {
            $service = app(LeaveDeductionService::class);

            $cukup = $service->hasSufficientBalance(
                userId: $data['user_id'],
                totalDays: $data['total_days'],
                source: $data['source'],
                leaveDepositBalanceId: $data['leave_deposit_balance_id'] ?? null,
                startDate: Carbon::parse($data['start_date'])
            );

            if (! $cukup) {
                $sisa = $service->remainingBalance(
                    userId: $data['user_id'],
                    source: $data['source'],
                    startDate: Carbon::parse($data['start_date'])
                );

                throw new \InvalidArgumentException("Insufficient leave balance. Remaining: {$sisa} days.");
            }
        }

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->wasApproved) {
            app(LeaveDeductionService::class)->deduct($this->record);
        }
    }
    protected function getRedirectUrl(): string
    {
        return LeaveRequestResource::getUrl('index');
    }
}
