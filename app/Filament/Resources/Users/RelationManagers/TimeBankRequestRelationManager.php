<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\TimeBankRequests\TimeBankRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TimeBankRequestRelationManager extends RelationManager
{
    protected static string $relationship = 'TimeBankRequest';

    protected static ?string $relatedResource = TimeBankRequestResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
