<?php

namespace App\Filament\Resources\EmployeeWorkSchedules;

use App\Filament\Resources\EmployeeWorkSchedules\Pages\CreateEmployeeWorkSchedule;
use App\Filament\Resources\EmployeeWorkSchedules\Pages\EditEmployeeWorkSchedule;
use App\Filament\Resources\EmployeeWorkSchedules\Pages\ListEmployeeWorkSchedules;
use App\Filament\Resources\EmployeeWorkSchedules\Schemas\EmployeeWorkScheduleForm;
use App\Filament\Resources\EmployeeWorkSchedules\Tables\EmployeeWorkSchedulesTable;
use App\Models\EmployeeWorkSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmployeeWorkScheduleResource extends Resource
{
    protected static ?string $model = EmployeeWorkSchedule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Calendar;

    public static function form(Schema $schema): Schema
    {
        return EmployeeWorkScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeWorkSchedulesTable::configure($table);
    }

    public static function getNavigationLabel(): string
    {
        return __('Jadwal Kerja');
    }

    public static function getModelLabel(): string
    {
        return __('Jadwal Kerja');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Jadwal Kerja');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Manajemen SDM');
    }

    public static function getBreadcrumb(): string
    {
        return __('Jadwal Kerja');
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
            'index' => ListEmployeeWorkSchedules::route('/'),
            'create' => CreateEmployeeWorkSchedule::route('/create'),
            'edit' => EditEmployeeWorkSchedule::route('/{record}/edit'),
        ];
    }
}
