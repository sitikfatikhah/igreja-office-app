<?php

namespace App\Filament\Resources\Allowances;

use App\Filament\Clusters\Payrolls\PayrollsCluster;
use App\Filament\Resources\Allowances\Pages\CreateAllowance;
use App\Filament\Resources\Allowances\Pages\EditAllowance;
use App\Filament\Resources\Allowances\Pages\ListAllowances;
use App\Filament\Resources\Allowances\Schemas\AllowanceForm;
use App\Filament\Resources\Allowances\Tables\AllowancesTable;
use App\Models\Allowance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AllowanceResource extends Resource
{
    protected static ?string $model = Allowance::class;

    protected static ?string $cluster = PayrollsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    public static function form(Schema $schema): Schema
    {
        return AllowanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AllowancesTable::configure($table);
    }

    public static function getNavigationLabel(): string
    {
        return __('Tunjangan');
    }

    public static function getModelLabel(): string
    {
        return __('Tunjangan');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Tunjangan');
    }

    public static function getBreadcrumb(): string
    {
        return __('Tunjangan');
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
            'index' => ListAllowances::route('/'),
            'create' => CreateAllowance::route('/create'),
            'edit' => EditAllowance::route('/{record}/edit'),
        ];
    }
}
