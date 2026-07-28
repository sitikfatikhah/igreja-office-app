<?php

namespace App\Filament\Resources\LoanInstallments;

use App\Filament\Clusters\EmployeeLoans\EmployeeLoansCluster;
use App\Filament\Resources\LoanInstallments\Pages\CreateLoanInstallment;
use App\Filament\Resources\LoanInstallments\Pages\EditLoanInstallment;
use App\Filament\Resources\LoanInstallments\Pages\ListLoanInstallments;
use App\Filament\Resources\LoanInstallments\Schemas\LoanInstallmentForm;
use App\Filament\Resources\LoanInstallments\Tables\LoanInstallmentsTable;
use App\Models\LoanInstallments;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LoanInstallmentResource extends Resource
{
    protected static ?string $model = LoanInstallments::class;

    protected static ?string $cluster = EmployeeLoansCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return LoanInstallmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoanInstallmentsTable::configure($table);
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
            'index' => ListLoanInstallments::route('/'),
            'create' => CreateLoanInstallment::route('/create'),
            'edit' => EditLoanInstallment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('employeeLoan.user');
    }
}
