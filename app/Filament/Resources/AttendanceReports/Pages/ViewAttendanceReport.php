<?php

namespace App\Filament\Resources\AttendanceReports\Pages;

use App\Filament\Exports\AttendanceDetailExporter;
use App\Filament\Resources\AttendanceReports\AttendanceReportResource;
use App\Models\Attendance;
use App\Models\AttendanceReport;
use App\Models\EmployeeWorkSchedule;
use App\Models\LeaveRequest;
use App\Services\AttendanceReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Concerns\RestrictsFileUploadsToSchemaComponents;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class ViewAttendanceReport extends ViewRecord implements HasTable, HasSchemas, HasActions
{
    use InteractsWithTable;
    use InteractsWithSchemas;
    use InteractsWithActions;
    // use RestrictsFileUploadsToSchemaComponents;

    protected static string $resource = AttendanceReportResource::class;

    protected string $view = 'filament.pages.view-attendance-report';

    protected ?Collection $workSchedules = null;
    
    
    public function mount($record): void
    {
        parent::mount($record);

        $this->workSchedules = EmployeeWorkSchedule::query()
            ->where('user_id', $this->record->user_id)
            ->orderByDesc('effective_from')
            ->get();
    }

    /**
     * Approved leave requests overlapping the report's period.
     * Reused by both the infolist (indirectly, via the model accessor)
     * and the PDF export below.
     */
    protected function getApprovedLeaveRequests(AttendanceReport $record)
    {
        return LeaveRequest::query()
            ->where('user_id', $record->user_id)
            ->where('approval_status', 'Approved')
            ->whereDate('start_date', '<=', $record->getRawOriginal('end_date'))
            ->whereDate('end_date', '>=', $record->getRawOriginal('start_date'))
            ->get();
    }
    protected function getOffDays(AttendanceReport $record)
    {
        return EmployeeWorkSchedule::query()
            ->where('user_id', $record->user_id)
            ->latest('effective_from')
            ->get();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Attendance::query()
                    ->where('user_id', $this->record->user_id)
                    ->whereBetween('date', [
                        $this->record->getRawOriginal('start_date'),
                        $this->record->getRawOriginal('end_date'),
                    ])
                    ->orderBy('date')
            )
            ->columns([
                TextColumn::make('date')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('check_in')
                    ->label('Check In')
                    ->dateTime('H:i')
                    ->placeholder('-'),
                TextColumn::make('check_out')
                    ->label('Check Out')
                    ->dateTime('H:i')
                    ->placeholder('-'),
                TextColumn::make('total_days')
                    ->label('Days')
                    ->suffix(' days'),
                TextColumn::make('is_late')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state ? 'Late' : 'On Time')
                    ->badge()
                    ->color(fn ($state) => $state ? 'warning' : 'success'),
                TextColumn::make('overtime_hours')
                    ->label('Overtime')
                    ->suffix(' hrs'),
                TextColumn::make('off_days')
                    ->label('Off Days')
                    ->state(function (Attendance $attendance) {

                        $schedule = $this->getScheduleByDate($attendance->date);

                        if (!$schedule) {
                            return '-';
                        }

                        $today = strtolower(
                            Carbon::parse($attendance->date)
                                ->englishDayOfWeek
                        );

                        $offDays = collect($schedule->off_days)
                            ->map(fn($day)=>strtolower($day))
                            ->toArray();

                        return in_array($today, $offDays)
                            ? ucfirst($today) . ' - OFF'
                            : '-';
                    })
                    ->badge()
                    ->color('danger'),
            ])
            ->paginated(false)
            ->striped();
    }

    public function isWorkingDay($date): bool
    {
        $schedule = $this->getScheduleByDate($date);

        if (!$schedule) {
            return true;
        }

        $offDays = collect($schedule->off_days ?? [])
            ->map(fn ($day) => strtolower($day));

        return ! $offDays->contains(
            strtolower(Carbon::parse($date)->englishDayOfWeek)
        );
    }

    protected function getWorkSchedule(AttendanceReport $record)
    {
        return EmployeeWorkSchedule::query()
            ->where('user_id',$record->user_id)
            ->whereDate('effective_from','<=',$record->end_date)
            ->where(function($query) use ($record){

                $query->whereNull('effective_until')
                    ->orWhereDate(
                        'effective_until',
                        '>=',
                        $record->start_date
                    );

            })
            ->latest('effective_from')
            ->first();
    }

    protected function getScheduleByDate(Carbon|string $date): ?EmployeeWorkSchedule
    {
        $date = Carbon::parse($date);

        // Jika property belum diinisialisasi (Livewire)
        if ($this->workSchedules === null) {
            $this->workSchedules = EmployeeWorkSchedule::query()
                ->where('user_id', $this->record->user_id)
                ->orderByDesc('effective_from')
                ->get();
        }

        return $this->workSchedules
            ->filter(function (EmployeeWorkSchedule $schedule) use ($date) {

                if (Carbon::parse($schedule->effective_from)->gt($date)) {
                    return false;
                }

                if (
                    !empty($schedule->effective_until) &&
                    Carbon::parse($schedule->effective_until)->lt($date)
                ) {
                    return false;
                }

                return true;
            })
            ->first();
    }

    

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make('exportDetail')
                ->label('Export Detail')
                ->icon('heroicon-o-arrow-down-tray')
                // ->color('success')
                ->exporter(AttendanceDetailExporter::class)
                ->formats([ExportFormat::Csv, ExportFormat::Xlsx])
                ->modifyQueryUsing(function ($query) {
                    $record = $this->getRecord();

                    return $query
                        ->where('user_id', $record->user_id)
                        ->whereBetween('date', [
                            $record->getRawOriginal('start_date'),
                            $record->getRawOriginal('end_date'),
                        ])
                        ->orderBy('date');
                }),
             Action::make('downloadSlip')
                ->label('Download Pdf')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () {
                    $record = $this->getRecord();
                    $user = $record->user;
                    $startDate = Carbon::parse($record->getRawOriginal('start_date'));
                    $endDate = Carbon::parse($record->getRawOriginal('end_date'));

                    $attendances = app(AttendanceReportService::class)
                        ->getAttendancesInPeriod($record)
                        ->keyBy(fn ($a) => Carbon::parse($a->date)->format('Y-m-d'));

                    // Approved leave requests overlapping the period — this is what
                    // was missing before, so the PDF never showed Leave rows.
                    $leaveRequests = $this->getApprovedLeaveRequests($record);

                    $holidays = [
                        '2026-01-01' => 'Tahun Baru Masehi',
                        '2026-03-20' => 'Hari Raya Nyepi',
                        // ... isi sesuai kalender libur nasional tahun berjalan
                    ];
                    $workSchedule = $this->getWorkSchedule($record);

                    $pdf = Pdf::loadView(
                        'filament.forms.attendance',
                        [
                            'user' => $user,
                            'startDate' => $startDate,
                            'endDate' => $endDate,
                            'attendances' => $attendances,
                            'holidays' => $holidays,
                            'leaveRequests' => $leaveRequests,
                            'workSchedule' => $workSchedule,                            
                        ]
                    );
                    
                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'Attendance-' . $record->id . '.pdf'
                    );
                }),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->record($this->getRecord())
            ->schema([
                Section::make('Report Summary')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('user.name')->label('Employee'),
                        TextEntry::make('user.nip')->label('NIP'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state) => match ($state) {
                                'present'  => 'success',
                                'late'     => 'warning',
                                'overtime' => 'info',
                                'absent'   => 'gray',
                                default    => 'gray',
                            }),
                        TextEntry::make('start_date')->label('Period Start')->date('d M Y'),
                        TextEntry::make('end_date')->label('Period End')->date('d M Y'),
                        TextEntry::make('total_days')->label('Total Working Days')->suffix(' days'),
                        TextEntry::make('total_overtime')->label('Total Overtime')->suffix(' days'),
                        TextEntry::make('total_late')->label('Total Late')->suffix(' days'),
                        TextEntry::make('off_days')
                            ->label('Off Days')
                            ->state(function () {

                                $schedule = $this->getScheduleByDate(
                                    $this->record->start_date
                                );

                                if (!$schedule) {
                                    return '-';
                                }

                                return collect($schedule->off_days)
                                    ->map(fn($day)=>ucfirst($day))
                                    ->implode(', ');
                            }),
                        TextEntry::make('total_leave_days')->label('Total Leave')->suffix(' days'),
                    ]),
            ]);
    }
}