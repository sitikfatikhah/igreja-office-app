<?php

namespace App\Filament\Resources\EmployeeWorkSchedules\Pages;

use App\Filament\Resources\EmployeeWorkSchedules\EmployeeWorkScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeWorkSchedules extends ListRecords
{
    protected static string $resource = EmployeeWorkScheduleResource::class;

    public function getHeading(): string
    {
        return 'Daftar Jadwal Libur Karyawan';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola jadwal libur karyawan.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
