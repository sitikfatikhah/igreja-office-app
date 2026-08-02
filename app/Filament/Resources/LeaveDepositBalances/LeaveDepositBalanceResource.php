<?php

namespace App\Filament\Resources\LeaveDepositBalances;

use App\Filament\Clusters\LeaveDeposits\LeaveDepositsCluster;
use App\Filament\Resources\LeaveDepositBalances\Pages\CreateLeaveDepositBalance;
use App\Filament\Resources\LeaveDepositBalances\Pages\EditLeaveDepositBalance;
use App\Filament\Resources\LeaveDepositBalances\Pages\ListLeaveDepositBalances;
use App\Filament\Resources\LeaveDepositBalances\Schemas\LeaveDepositBalanceForm;
use App\Filament\Resources\LeaveDepositBalances\Tables\LeaveDepositBalancesTable;
use App\Models\LeaveDepositBalance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LeaveDepositBalanceResource extends Resource
{
    protected static ?string $model = LeaveDepositBalance::class;

    protected static ?string $cluster = LeaveDepositsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Scale;

    public static function form(Schema $schema): Schema
    {
        return LeaveDepositBalanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaveDepositBalancesTable::configure($table);
    }

    public static function getNavigationLabel(): string
    {
        return __('Saldo Deposit Cuti');
    }

    public static function getModelLabel(): string
    {
        return __('Saldo Deposit Cuti');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Saldo Deposit Cuti');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Cuti');
    }

    public static function getBreadcrumb(): string
    {
        return __('Saldo Deposit Cuti');
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
            'index' => ListLeaveDepositBalances::route('/'),
            'create' => CreateLeaveDepositBalance::route('/create'),
            'edit' => EditLeaveDepositBalance::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        // Super Admin dan Admin dapat melihat semua data
        if ($user->hasRole(['super_admin', 'admin'])) {
            return $query;
        }

        // User hanya melihat data miliknya sendiri
        return $query->where('user_id', $user->id);
    }
}
