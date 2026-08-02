<?php

namespace App\Filament\Resources\EmployeeLoans;

use App\Filament\Clusters\EmployeeLoans\EmployeeLoansCluster;
use App\Filament\Resources\EmployeeLoans\Pages\CreateEmployeeLoan;
use App\Filament\Resources\EmployeeLoans\Pages\EditEmployeeLoan;
use App\Filament\Resources\EmployeeLoans\Pages\ListEmployeeLoans;
use App\Filament\Resources\EmployeeLoans\Schemas\EmployeeLoanForm;
use App\Filament\Resources\EmployeeLoans\Tables\EmployeeLoansTable;
use App\Models\EmployeeLoan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeLoanResource extends Resource
{
    protected static ?string $model = EmployeeLoan::class;

    protected static ?string $cluster = EmployeeLoansCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BookOpen;

    protected static ?string $navigationLabel = 'Pinjaman Karyawan';

    public static function form(Schema $schema): Schema
    {
        return EmployeeLoanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeLoansTable::configure($table);
    }

    public static function getModelLabel(): string
    {
        return __('Pinjaman Karyawan');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Pinjaman Karyawan');
    }

    public static function getBreadcrumb(): string
    {
        return __('Pinjaman Karyawan');
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
            'index' => ListEmployeeLoans::route('/'),
            'create' => CreateEmployeeLoan::route('/create'),
            'edit' => EditEmployeeLoan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('user');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
