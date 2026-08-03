<?php

namespace App\Filament\Resources\AnnualLeaveBalances;

use App\Filament\Clusters\LeaveDeposits\LeaveDepositsCluster;
use App\Filament\Resources\AnnualLeaveBalances\Pages\CreateAnnualLeaveBalance;
use App\Filament\Resources\AnnualLeaveBalances\Pages\EditAnnualLeaveBalance;
use App\Filament\Resources\AnnualLeaveBalances\Pages\ListAnnualLeaveBalances;
use App\Filament\Resources\AnnualLeaveBalances\Schemas\AnnualLeaveBalanceForm;
use App\Filament\Resources\AnnualLeaveBalances\Tables\AnnualLeaveBalancesTable;
use App\Filament\Resources\LeaveRequests\Pages\CreateLeaveRequest;
use App\Models\AnnualLeaveBalance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AnnualLeaveBalanceResource extends Resource
{
    protected static ?string $model = AnnualLeaveBalance::class;
    protected static ?string $cluster = LeaveDepositsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChatBubbleBottomCenter;

    public static function form(Schema $schema): Schema
    {
        return AnnualLeaveBalanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AnnualLeaveBalancesTable::configure($table);
    }

    public static function getNavigationLabel(): string
    {
        return __('Saldo Cuti Tahunan');
    }

    public static function getModelLabel(): string
    {
        return __('Saldo Cuti Tahunan');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Saldo Cuti Tahunan');
    }

    public static function getBreadcrumb(): string
    {
        return __('Saldo Cuti Tahunan');
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
            'index' => ListAnnualLeaveBalances::route('/'),
            'create' => CreateAnnualLeaveBalance::route('/create'),
            'edit' => EditAnnualLeaveBalance::route('/{record}/edit'),
        ];
    }
}
