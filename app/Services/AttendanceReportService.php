<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceReport;
use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AttendanceReportService
{
    public function generate(
        int $userId,
        string $startDate,
        string $endDate
    ): AttendanceReport {

        $user = User::findOrFail($userId);

        $attendances = Attendance::query()
            ->where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->keyBy(fn ($attendance) => Carbon::parse($attendance->date)->toDateString());

        $leaveRequests = LeaveRequest::query()
            ->where('user_id', $userId)
            ->where('approval_status', 'Approved')
            ->whereDate('start_date', '<=', $endDate)
            ->whereDate('end_date', '>=', $startDate)
            ->get();

        $leaveDays = collect();

        foreach ($leaveRequests as $leave) {

            $date = Carbon::parse($leave->start_date);

            while ($date->lte(Carbon::parse($leave->end_date))) {
                $leaveDays->put($date->toDateString(), true);
                $date->addDay();
            }
        }

        $totalHours = 0;
        $totalOvertime = 0;
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalLeave = 0;

        $scheduleService = app(EmployeeWorkScheduleService::class);

        $period = Carbon::parse($startDate);

        while ($period->lte(Carbon::parse($endDate))) {

            // Lewati hari libur sesuai jadwal libur karyawan
            if (! $scheduleService->isWorkingDay($user, $period)) {
                $period->addDay();
                continue;
            }

            $key = $period->toDateString();

            $attendance = $attendances->get($key);

            if ($attendance) {

                $totalPresent++;

                $hours = $this->calculateTotalHours(
                    $attendance->check_in,
                    $attendance->check_out
                );

                $totalHours += $hours;
            
                $totalOvertime += $this->calculateOvertimeForDate(
                    $user,
                    Carbon::parse($attendance->date)
                );
                

            } elseif ($leaveDays->has($key)) {

                $totalLeave++;

            } else {

                $totalAbsent++;
            }

            $period->addDay();
        }

        return AttendanceReport::updateOrCreate(
            [
                'user_id' => $userId,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            [
                'total_present' => $totalPresent,
                'total_absent' => $totalAbsent,
                'total_leave' => $totalLeave,
                'total_hours' => round($totalHours, 2),
                'total_overtime' => round($totalOvertime, 2),
                'status' => 'generated',
                'report_date' => now(),
            ]
        );
    }

    public function getApprovedLeaveRequests(AttendanceReport $report): Collection
    {
        return LeaveRequest::query()
            ->where('user_id', $report->user_id)
            ->where('approval_status', 'Approved')
            ->whereDate('start_date', '<=', $report->end_date)
            ->whereDate('end_date', '>=', $report->start_date)
            ->get();
    }

    public function getAttendancesInPeriod(AttendanceReport $report): Collection
    {
        $attendances = Attendance::query()
            ->where('user_id', $report->user_id)
            ->whereBetween('date', [$report->start_date, $report->end_date])
            ->orderBy('date')
            ->get()
            ->keyBy(fn ($attendance) => Carbon::parse($attendance->date)->toDateString());

        $approvedLeaves = $this->getApprovedLeaveRequests($report);
        $result = collect();
        $date = Carbon::parse($report->start_date);

        $scheduleService = app(EmployeeWorkScheduleService::class);
        $user = $report->user;

        while ($date->lte(Carbon::parse($report->end_date))) {
            if (! $scheduleService->isWorkingDay($user, $date)) {
                $date->addDay();
                continue;
            }

            $key = $date->toDateString();
            $attendance = $attendances->get($key);

            if ($attendance) {
                $attendance->status = $attendance->status ?: 'present';
                $result->push($attendance);
            } else {
                $isLeave = $approvedLeaves->contains(function ($leave) use ($date) {
                    return $date->between(
                        Carbon::parse($leave->start_date),
                        Carbon::parse($leave->end_date)
                    );
                });

                $result->push((object) [
                    'date' => $key,
                    'status' => $isLeave ? 'leave' : 'absent',
                    'check_in' => null,
                    'check_out' => null,
                    'total_overtime' => 0,
                ]);
            }

            $date->addDay();
        }

        return $result;
    }

    public function getTotalLeaveDays(AttendanceReport $report): int
    {
        return $this->getApprovedLeaveRequests($report)
            ->sum(function (LeaveRequest $leave) use ($report) {
                $leaveStart = Carbon::parse($leave->start_date)->max(Carbon::parse($report->start_date));
                $leaveEnd = Carbon::parse($leave->end_date)->min(Carbon::parse($report->end_date));

                return $leaveStart->diffInDays($leaveEnd) + 1;
            });
    }

    public function __construct(
        protected SettingsService $settings,
    ) {}

    protected function calculateTotalHours($checkIn, $checkOut): float
    {
        if (!$checkIn || !$checkOut) {
            return 0;
        }

        return round(
            Carbon::parse($checkIn)
                ->diffInMinutes(Carbon::parse($checkOut)) / 60,
            2
        );
    }

    protected function calculateLate($checkIn): float
    {
        if (!$checkIn) {
            return 0;
        }

        $checkIn = Carbon::parse($checkIn);

        $startTime = $this->settings->get('attendance.check_in_time');

        if (strlen($startTime) <= 5) {
            $startTime = $startTime . ':00';
        }

        $officeStart = Carbon::parse(
            $checkIn->format('Y-m-d') . ' ' . $startTime
        );

        if ($checkIn->lte($officeStart)) {
            return 0;
        }

        return round(
            $officeStart->diffInMinutes($checkIn) / 60,
            2
        );
    }

    public function calculateOvertimeForDate(User $user, Carbon $date): float
    {
        $attendance = Attendance::query()
            ->where('user_id', $user->id)
            ->whereDate('date', $date)
            ->first();

        if (! $attendance) {
            return 0;
        }

        if (! $attendance->check_in || ! $attendance->check_out) {
            return 0;
        }

        $workedHours = $this->calculateTotalHours(
            $attendance->check_in,
            $attendance->check_out
        );

        $workingHoursPerDay = (float) $this->settings->get(
            'attendance.working_hours_per_day'
        );

        return max(
            round($workedHours - $workingHoursPerDay, 2),
            0
        );
    }
}