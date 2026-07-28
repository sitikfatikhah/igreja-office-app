<?php

namespace App\Filament\Resources\AttendanceReports\Pages;

use App\Filament\Resources\AttendanceReports\AttendanceReportResource;
use App\Models\Attendance;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateAttendanceReport extends CreateRecord
{
    protected static string $resource = AttendanceReportResource::class;

    public function getReportData()
    {
        $data = $this->form->getState();

        $query = Attendance::query()
            ->with('user')
            ->orderBy('date');

        if (! empty($data['user_id'])) {
            $query->where('user_id', $data['user_id']);
        }

        if (! empty($data['start_date'])) {
            $query->whereDate('date', '>=', $data['start_date']);
        }

        if (! empty($data['end_date'])) {
            $query->whereDate('date', '<=', $data['end_date']);
        }

        if (! empty($data['month'])) {
            $month = Carbon::parse($data['month']);
            $query->whereYear('date', $month->year)
                ->whereMonth('date', $month->month);
        }

        return $query->get();
    }
}
