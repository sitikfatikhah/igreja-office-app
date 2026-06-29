<?php

namespace App\Filament\Resources\TimeBankRequests;

use App\Filament\Clusters\LeaveDeposits\LeaveDepositsCluster;
use App\Filament\Resources\TimeBankRequests\Pages\CreateTimeBankRequest;
use App\Filament\Resources\TimeBankRequests\Pages\EditTimeBankRequest;
use App\Filament\Resources\TimeBankRequests\Pages\ListTimeBankRequests;
use App\Filament\Resources\TimeBankRequests\Schemas\TimeBankRequestForm;
use App\Filament\Resources\TimeBankRequests\Tables\TimeBankRequestsTable;
use App\Models\TimeBankRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TimeBankRequestResource extends Resource
{
    protected static ?string $model = TimeBankRequest::class;

    protected static ?string $cluster = LeaveDepositsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    public static function form(Schema $schema): Schema
    {
        return TimeBankRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TimeBankRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTimeBankRequests::route('/'),
            'create' => CreateTimeBankRequest::route('/create'),
            'edit' => EditTimeBankRequest::route('/{record}/edit'),
        ];
    }
}
