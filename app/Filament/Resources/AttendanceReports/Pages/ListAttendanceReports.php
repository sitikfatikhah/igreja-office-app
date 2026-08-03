<?php

namespace App\Filament\Resources\AttendanceReports\Pages;

use App\Filament\Resources\AttendanceReports\AttendanceReportResource;
use App\Services\AttendanceReportService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListAttendanceReports extends ListRecords
{
    protected static string $resource = AttendanceReportResource::class;

    public function getHeading(): string
    {
        return 'Daftar Laporan Kehadiran';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola laporan kehadiran per karyawan dan periode.';
    }

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
            Action::make('generateReport')
                ->label('Buat Laporan')
                ->icon('heroicon-o-arrow-path')
                ->form([
                    Select::make('user_id')
                        ->label('Karyawan')
                        ->relationship('user', 'name')
                        ->required(),

                    DatePicker::make('start_date')->required(),
                    DatePicker::make('end_date')->required(),
                ])
                ->action(function (array $data) {
                    app(AttendanceReportService::class)
                        ->generate(
                            $data['user_id'],
                            $data['start_date'],
                            $data['end_date']
                        );
                            Notification::make()
                                ->title('Laporan berhasil dibuat')
                                ->success()
                                ->send();
                }),
        ];
    }
}
