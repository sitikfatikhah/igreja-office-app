<?php

namespace App\Filament\Resources\LoanInstallments\Pages;

use App\Filament\Resources\LoanInstallments\LoanInstallmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLoanInstallments extends ListRecords
{
    protected static string $resource = LoanInstallmentResource::class;

    public function getHeading(): string
    {
        return 'Daftar Angsuran Pinjaman';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola pelunasan pinjaman karyawan.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
