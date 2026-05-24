<?php

namespace App\Services;


use App\Models\Attendance;
use App\Models\AttendanceReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceReportService
{
    public function generate(Attendance $attendance):void
    {
        $hours = 0;

        if ($attendance->check_in && $attendance->check_out) {
            $hours = Carbon::parse($attendance->check_in)
                ->diffInMinutes($attendance->check_out) / 60;
        }

        AttendanceReport::updateOrCreate(
            [
                'user_id' => $attendance->user_id,
                'date' => $attendance->date,
            ],
            [
                'nip' => $attendance->nip,
                'position' => $attendance->position,
                'check_in' => $attendance->check_in,
                'check_out' => $attendance->check_out,
                'total_hours' => $hours,
                'is_face_verified' => $attendance->face_verified,
                'status' => $attendance->check_out ? 'present' : 'incomplete',
            ]
        );
    }

    public function generateAll():void
    {        
        Attendance::query()
            ->chunk(100, function($attendances){
                foreach ($attendances as $attendance){
                $this->generate($attendance);
            }
        });
        
        // Log::info('Attendance reports generated successfully');
    }

}