<?php

namespace App\Filament\Resources\EmployeeLoans\Pages;

use App\Filament\Resources\EmployeeLoans\EmployeeLoanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeLoans extends ListRecords
{
    protected static string $resource = EmployeeLoanResource::class;

    public function getHeading(): string
    {
        return 'Pinjaman Karyawan';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola seluruh data pinjaman karyawan beserta status pembayaran.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
