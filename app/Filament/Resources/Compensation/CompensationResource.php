<?php

namespace App\Filament\Resources\Compensation;

use App\Filament\Clusters\Payrolls\PayrollsCluster;
use App\Filament\Resources\Compensation\Pages\CreateCompensation;
use App\Filament\Resources\Compensation\Pages\EditCompensation;
use App\Filament\Resources\Compensation\Pages\ListCompensation;
use App\Filament\Resources\Compensation\Pages\ViewCompensation;
use App\Filament\Resources\Compensation\Schemas\CompensationForm;
use App\Filament\Resources\Compensation\Schemas\CompensationInfolist;
use App\Filament\Resources\Compensation\Tables\CompensationTable;
use App\Models\Compensation;
use App\Models\Compensations;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompensationResource extends Resource
{
    protected static ?string $model = Compensations::class;

    protected static ?string $cluster = PayrollsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CurrencyDollar;

    public static function form(Schema $schema): Schema
    {
        return CompensationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CompensationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompensationTable::configure($table);
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
            'index' => ListCompensation::route('/'),
            'create' => CreateCompensation::route('/create'),
            'view' => ViewCompensation::route('/{record}'),
            'edit' => EditCompensation::route('/{record}/edit'),
        ];
    }
}
