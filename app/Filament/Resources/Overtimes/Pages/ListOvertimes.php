<?php

namespace App\Filament\Resources\Overtimes\Pages;

use App\Filament\Resources\Overtimes\OvertimeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOvertimes extends ListRecords
{
    protected static string $resource = OvertimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getHeading(): string
    {
        return 'Lembur Karyawan';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola seluruh data lembur karyawan beserta status persetujuan.';
    }
}
