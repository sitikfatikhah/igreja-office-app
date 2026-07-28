<?php

namespace App\Filament\Resources\LeaveRequests\Pages;

use App\Filament\Resources\LeaveRequests\LeaveRequestResource;
use App\Services\LeaveDeductionService;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateLeaveRequest extends CreateRecord
{
    protected static string $resource = LeaveRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $service = app(LeaveDeductionService::class);

        $cukup = $service->hasSufficientBalance(
            userId: $data['user_id'],
            totalDays: (int) $data['total_days'],
            source: $data['source'],
            leaveDepositBalanceId: $data['leave_deposit_balance_id'] ?? null,
            startDate: Carbon::parse($data['start_date']), // <-- parse dulu jadi Carbon
        );

        if (! $cukup) {
            Notification::make()
                ->title('Saldo cuti tidak mencukupi')
                ->body("Sisa saldo tidak cukup untuk pengajuan {$data['total_days']} hari.")
                ->danger()
                ->send();

            $this->halt();
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->record->approval_status === 'Approved') {
            app(LeaveDeductionService::class)->deduct($this->record);
        }
    }

    protected function getRedirectUrl(): string
    {
        return LeaveRequestResource::getUrl('index');
    }
}