<?php

namespace App\Filament\Resources\Attendances;

use App\Filament\Clusters\Attendances\AttendancesCluster;
use App\Filament\Resources\Attendances\Pages\CreateAttendance;
use App\Filament\Resources\Attendances\Pages\EditAttendance;
use App\Filament\Resources\Attendances\Pages\ListAttendances;
use App\Filament\Resources\Attendances\Pages\ViewAttendance;
use App\Filament\Resources\Attendances\Schemas\AttendanceForm;
use App\Filament\Resources\Attendances\Schemas\AttendanceInfolist;
use App\Filament\Resources\Attendances\Tables\AttendancesTable;
use App\Models\Attendance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $cluster = AttendancesCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::FingerPrint;

    public static function form(Schema $schema): Schema
    {
        return AttendanceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AttendanceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendancesTable::configure($table);
    }

    public static function getNavigationLabel(): string
    {
        return __('Kehadiran');
    }

    public static function getModelLabel(): string
    {
        return __('Kehadiran');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Kehadiran');
    }

    public static function getBreadcrumb(): string
    {
        return __('Kehadiran');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function canAccess(): bool
    {
        return true;
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListAttendances::route('/'),
            'create' => CreateAttendance::route('/create'),
            'view'   => ViewAttendance::route('/{record}'),
            'edit'   => EditAttendance::route('/{record}/edit'),
        ];
    }

    /**
     * Batasi data yang dapat dilihat berdasarkan role.
     */
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