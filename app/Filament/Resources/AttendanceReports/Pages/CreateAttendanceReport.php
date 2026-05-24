<?php

namespace App\Filament\Resources\AttendanceReports\Pages;

use App\Filament\Resources\AttendanceReports\AttendanceReportResource;
use App\Models\Attendance;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateAttendanceReport extends CreateRecord
{
    protected static string $resource = AttendanceReportResource::class;

    public function getReportData()
    {
        $data= $this->form->getState();
        
        $query = Attendance::query()
            ->select([
                'user_id',
                DB::raw('DATE(date) as date'),
                DB::raw('TIME(check_in) as start_time'),
                DB::raw('TIME(check_out) as end_time'),
            ]);
            
            if(!empty($data['user-id'])){
                $query->where('user_id', $data['user_id']);
            }

            if(!empty($data['month'])){
                $query->whereRaw(
                    "DATE-FORMAT(date, '%Y-%m')= ?",
                    [$data['month']]
                );
            }
            return $query->with('user')->get();
            }
}
