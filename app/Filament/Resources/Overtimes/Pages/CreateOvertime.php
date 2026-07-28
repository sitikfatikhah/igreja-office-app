<?php

namespace App\Filament\Resources\Overtimes\Pages;

use App\Filament\Clusters\Attendances\AttendancesCluster;
use App\Filament\Resources\Overtimes\OvertimeResource;
use App\Models\Attendance;
use App\Models\User;
use App\Services\AttendanceReportService;
use App\Services\SettingsService;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CreateOvertime extends CreateRecord
{
    protected static string $resource = OvertimeResource::class;

    protected static ?string $cluster = AttendancesCluster::class;

    protected SettingsService $settings;

    public function mount(): void
    {
        parent::mount();
        $this->settings = app(SettingsService::class);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        /*
        |--------------------------------------------------------------------------
        | Hitung Jam Overtime Yang Diajukan
        |--------------------------------------------------------------------------
        */

        if (empty($data['start_time']) || empty($data['end_time'])) {
            $data['total_hours'] = 0;

            return $data;
        }

        $start = Carbon::parse($data['start_time']);
        $end = Carbon::parse($data['end_time']);

        if ($end->lt($start)) {
            $end->addDay();
        }

        $requestedHours = round(
            $start->diffInMinutes($end) / 60,
            2
        );

        $data['total_hours'] = $requestedHours;

        $workingHoursPerDay = (float) $this->settings->get(
            'attendance.working_hours_per_day'
        );

        /*
        |--------------------------------------------------------------------------
        | Validasi Dengan Attendance
        |--------------------------------------------------------------------------
        */

        $user = User::with('compensation')->findOrFail($data['user_id']);

        $attendanceService = app(AttendanceReportService::class);

        $actualHours = $attendanceService->calculateOvertimeForDate(
            $user,
            Carbon::parse($data['overtime_date'])
        );

        if ($requestedHours > $actualHours) {
            throw ValidationException::withMessages([
                'total_hours' =>
                    "Permintaan lembur ({$requestedHours} jam) melebihi lembur aktual ({$actualHours} jam).",
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Default Status
        |--------------------------------------------------------------------------
        */

        $data['approval_status'] = 'Pending';

        $data['approved_by'] = Auth::user()->name;

        /*
        |--------------------------------------------------------------------------
        | Payroll Calculation
        |--------------------------------------------------------------------------
        */

        $basicSalary = $user->compensation?->basic_salary ?? 0;

        $monthlyHours = (float) $this->settings->get('payroll.monthly_working_hours');

        $freeHours = (float) $this->settings->get('payroll.free_overtime_hours');

        $maxHours = (float) $this->settings->get('payroll.max_paid_overtime_hours');

        $hourlyRate = $basicSalary / max($monthlyHours, 1);

        $paidHours = max(0, $requestedHours - $freeHours);
        $paidHours = min($paidHours, $maxHours);

        $data['base_pay'] = round($hourlyRate, 2);
        $data['paid_hours'] = round($paidHours, 2);
        $data['total_pay'] = round($hourlyRate * $paidHours, 2);

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $data['overtime_date'])
            ->first();

        $workedHours = 0;

        if ($attendance && $attendance->check_in && $attendance->check_out) {
            $workedHours = Carbon::parse($attendance->check_in)
                ->diffInMinutes(Carbon::parse($attendance->check_out))
                / 60;
        }

        $data['leave_deposit'] =
            $workedHours >= $workingHoursPerDay ? 1 : 0;

        return $data;
    }

    protected function afterCreate(): void
    {
        Notification::make()
            ->success()
            ->title('Permintaan lembur berhasil dibuat.')
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return OvertimeResource::getUrl('index');
    }
}