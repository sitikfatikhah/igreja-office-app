<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Allowances\AllowanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class AllowanceRelationManager extends RelationManager
{
    protected static string $relationship = 'Allowance';

    protected static ?string $relatedResource = AllowanceResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
