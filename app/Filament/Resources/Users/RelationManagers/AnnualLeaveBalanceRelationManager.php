<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\AnnualLeaveBalances\AnnualLeaveBalanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class AnnualLeaveBalanceRelationManager extends RelationManager
{
    protected static string $relationship = 'AnnualLeaveBalance';

    protected static ?string $relatedResource = AnnualLeaveBalanceResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
