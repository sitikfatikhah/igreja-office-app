<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Clusters\Payrolls\PayrollsCluster;
use App\Filament\Resources\Payrolls\PayrollResource;
use App\Models\AttendanceReport;
use App\Services\PayrollService;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPayrolls extends ListRecords
{
    protected static string $resource = PayrollResource::class;

    protected static ?string $cluster = PayrollsCluster::class;

    public function getHeading(): string
    {
        return 'Daftar Penggajian';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola daftar penggajian karyawan berdasarkan laporan kehadiran.';
    }

    protected function getHeaderActions(): array
    {
        return [

            CreateAction::make('generatePayroll')
                ->label('Buat Penggajian')
                ->icon('heroicon-o-banknotes')

                ->form([

                    Select::make('attendance_report_id')
                        ->label('Periode Kehadiran')
                        ->relationship(
                            'attendanceReport',
                            'id',
                            fn ($query) => $query->with('user')
                        )
                        ->getOptionLabelFromRecordUsing(
                            fn (AttendanceReport $record) =>
                                "{$record->user->name} | {$record->start_date} - {$record->end_date}"
                        )
                        ->searchable()
                        ->required(),

                ])

                ->action(function (array $data) {

                    $report = AttendanceReport::findOrFail(
                        $data['attendance_report_id']
                    );

                    app(PayrollService::class)
                        ->generate($report);

                    Notification::make()
                        ->success()
                        ->title('Penggajian berhasil dibuat.')
                        ->send();
                }),

        ];
    }
}