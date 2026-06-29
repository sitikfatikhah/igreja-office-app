<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceReport;
use Carbon\Carbon;

class AttendanceReportService
{
    public function generate(
        int $userId,
        string $startDate,
        string $endDate
    ): AttendanceReport {

        $attendances = Attendance::query()
            ->where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $totalHours = 0;
        $totalOvertime = 0;
        $totalLate = 0;

        foreach ($attendances as $attendance) {

            $hours = $this->calculateTotalHours(
                $attendance->check_in,
                $attendance->check_out
            );

            $totalHours += $hours;

            if ($hours > 8) {
                $totalOvertime += ($hours - 8);
            }

            $totalLate += $this->calculateLate(
                $attendance->check_in
            );
        }

        return AttendanceReport::updateOrCreate(
            [
                'user_id' => $userId,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            [
                'total_hours' => round($totalHours, 2),
                'total_overtime' => round($totalOvertime, 2),
                'total_late' => round($totalLate, 2),

                'status' => 'generated',

                'report_date' => now(),
            ]
        );
    }

    protected function calculateTotalHours($checkIn, $checkOut): float
    {
        if (!$checkIn || !$checkOut) {
            return 0;
        }

        $start = Carbon::parse($checkIn);
        $end = Carbon::parse($checkOut);

        return round(
            $start->diffInMinutes($end) / 60,
            2
        );
    }

    protected function calculateLate($checkIn): float
    {
        if (!$checkIn) {
            return 0;
        }

        $checkInTime = Carbon::parse($checkIn);

        $officeStart = Carbon::parse(
            $checkInTime->format('Y-m-d') . ' 08:00:00'
        );

        if ($checkInTime->lessThanOrEqualTo($officeStart)) {
            return 0;
        }

        return round(
            $officeStart->diffInMinutes($checkInTime) / 60,
            2
        );
    }

    public function generateForUser(int $userId, string $startDate, string $endDate): void
{
    $attendances = Attendance::query()
        ->where('user_id', $userId)
        ->whereBetween('date', [$startDate, $endDate])
        ->get();

        // dd([
        //     'user_id' => $userId,
        //     'start' => $startDate,
        //     'end' => $endDate,
        //     'count' => $attendances->count(),
        // ]);

        // dd(
        //     Attendance::where('user_id', 1)
        //         ->select('id', 'date', 'check_in', 'check_out')
        //         ->get()
        //         ->toArray()
        // );

    $totalHours = 0;
    $totalLate = 0;
    $totalOvertime = 0;

    foreach ($attendances as $attendance) {

            if ($attendance->check_in && $attendance->check_out) {
                $hours = Carbon::parse($attendance->check_in)
                    ->diffInMinutes($attendance->check_out) / 60;

                $totalHours += $hours;
            }

            // contoh logic sederhana (sesuaikan aturan kamu)
            if ($attendance->check_in && Carbon::parse($attendance->check_in)->format('H:i') > '08:00') {
                $totalLate++;
            }

            if ($attendance->check_out && Carbon::parse($attendance->check_out)->format('H:i') > '17:00') {
                $totalOvertime++;
            }
        }

        AttendanceReport::updateOrCreate(
            [
                'user_id' => $userId,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            [
                'total_hours' => $totalHours,
                'total_late' => $totalLate,
                'total_overtime' => $totalOvertime,
                'status' => 'present',
                'report_date' => now(),
            ]
        );
    }
}