<?php

namespace App\Filament\Resources\Allowances\Pages;

use App\Filament\Resources\Allowances\AllowanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAllowances extends ListRecords
{
    protected static string $resource = AllowanceResource::class;

    public function getHeading(): string
    {
        return 'Daftar Tunjangan';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola data tunjangan karyawan.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
