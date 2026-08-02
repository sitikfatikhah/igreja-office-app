<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\RelationManagers\AllowanceRelationManager;
use App\Filament\Resources\Users\RelationManagers\AnnualLeaveBalanceRelationManager;
use App\Filament\Resources\Users\RelationManagers\AttendanceRelationManager;
use App\Filament\Resources\Users\RelationManagers\CompensationRelationManager;
use App\Filament\Resources\Users\RelationManagers\EmployeeLoanRelationManager;
use App\Filament\Resources\Users\RelationManagers\PayrollRelationManager;
use App\Filament\Resources\Users\RelationManagers\TimeBankRequestRelationManager;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserCircle;

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getNavigationLabel(): string
    {
        return __('Pegawai');
    }

    public static function getModelLabel(): string
    {
        return __('Pegawai');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Pegawai');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Manajemen SDM');
    }

    public static function getBreadcrumb(): string
    {
        return __('Pegawai');
    }

    public static function getRelations(): array
    {
        return [
            // CompensationRelationManager::class,

            // AllowanceRelationManager::class,

            EmployeeLoanRelationManager::class,

            AnnualLeaveBalanceRelationManager::class,

            TimeBankRequestRelationManager::class,

            AttendanceRelationManager::class,

            PayrollRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['compensation', 'allowance']);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
