<?php

namespace App\Filament\Resources\SalarySlips;

use App\Filament\Clusters\Payrolls\PayrollsCluster;
use App\Filament\Resources\SalarySlips\Pages\CreateSalarySlip;
use App\Filament\Resources\SalarySlips\Pages\EditSalarySlip;
use App\Filament\Resources\SalarySlips\Pages\ListSalarySlips;
use App\Filament\Resources\SalarySlips\Schemas\SalarySlipForm;
use App\Filament\Resources\SalarySlips\Tables\SalarySlipsTable;
use App\Models\Salary_slips;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalarySlipResource extends Resource
{
    protected static ?string $model = Salary_slips::class;

    protected static ?string $cluster = PayrollsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return SalarySlipForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalarySlipsTable::configure($table);
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
            'index' => ListSalarySlips::route('/'),
            'create' => CreateSalarySlip::route('/create'),
            'edit' => EditSalarySlip::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
