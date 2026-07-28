<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\EmployeeLoans\EmployeeLoanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class EmployeeLoanRelationManager extends RelationManager
{
    protected static string $relationship = 'EmployeeLoan';

    protected static ?string $relatedResource = EmployeeLoanResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
