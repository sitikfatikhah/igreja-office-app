<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Clusters\Payrolls\PayrollsCluster;
use App\Filament\Resources\Payrolls\PayrollResource;
use App\Models\Payrolls;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Override;

class EditPayroll extends EditRecord
{
    protected static string $resource = PayrollResource::class;

    protected static ?string $cluster = PayrollsCluster::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    #[Override]
    protected function getRedirectUrl(): string
    {
        return PayrollResource::getUrl('index');
    }
}
