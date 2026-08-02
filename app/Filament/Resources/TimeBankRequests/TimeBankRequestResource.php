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
use Illuminate\Database\Eloquent\Builder;

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

    public static function getNavigationLabel(): string
    {
        return __('Permohonan Time Bank');
    }

    public static function getModelLabel(): string
    {
        return __('Permohonan Time Bank');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Permohonan Time Bank');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Cuti');
    }

    public static function getBreadcrumb(): string
    {
        return __('Permohonan Time Bank');
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
