<?php

namespace App\Filament\Resources\Overtimes\Pages;

use App\Filament\Clusters\Attendances\AttendancesCluster;
use App\Filament\Resources\Overtimes\OvertimeResource;
use App\Models\Compensations;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateOvertime extends CreateRecord
{
    protected static string $resource = OvertimeResource::class;

    protected static ?string $cluster = AttendancesCluster::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        
        // $data['position'] = $user->position;

        if (empty($data['start_time']) || empty($data['end_time'])) {
            $data['total_hours'] = 0;
            return $data;
        }

        $startTime = Carbon::parse($data['start_time']);
        $endTime = Carbon::parse($data['end_time']);
            


        if ($endTime->lessThan($startTime)) {
            $endTime->addDay();          
        }

        $totalHours = $startTime->diffInMinutes($endTime)/60;
        
        $data['total_hours'] = round($totalHours, 2);

        //set approval status
        $data['approval_status'] = 'Pending';

        //kompensasi lembur

        $compensation = Compensations::where('user_id', Auth::id())->first();

        $basicSalary = $compensation?->basic_salary ?? 0;

        $basePay = $basicSalary / 173; 

        $actualHours = $data['total_hours'];

        $paidHours = max(0, $actualHours -1);

        $paidHours = min($paidHours, 4);

        $data['base_pay'] = round($basePay, 2);
        $data['paid_hours'] = round($paidHours, 2);

        $data['total_pay'] = round($basePay * $paidHours, 2);

        $data['leave_deposit'] = $actualHours > 8 ? 1 : 0;

        $data['approved_by'] = Auth::user()->name;

        return $data;
    }
    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Overtime request created successfully.')
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return OvertimeResource::getUrl('index');
    }
}
