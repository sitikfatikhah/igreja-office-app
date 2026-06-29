<?php

namespace App\Filament\Resources\AttendanceReports;

use App\Filament\Clusters\Payrolls\PayrollsCluster;
use App\Filament\Resources\AttendanceReports\Pages\CreateAttendanceReport;
use App\Filament\Resources\AttendanceReports\Pages\EditAttendanceReport;
use App\Filament\Resources\AttendanceReports\Pages\ListAttendanceReports;
use App\Filament\Resources\AttendanceReports\Pages\ViewAttendanceReport;
use App\Filament\Resources\AttendanceReports\Schemas\AttendanceReportForm;
use App\Filament\Resources\AttendanceReports\Tables\AttendanceReportsTable;
use App\Models\AttendanceReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceReportResource extends Resource
{
    protected static ?string $model = AttendanceReport::class;

    protected static ?string $cluster = PayrollsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::PaperClip;

    public static function form(Schema $schema): Schema
    {
        return AttendanceReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendanceReportsTable::configure($table);
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
            'index' => ListAttendanceReports::route('/'),
            'create' => CreateAttendanceReport::route('/create'),
            'edit' => EditAttendanceReport::route('/{record}/edit'),
            'view' => ViewAttendanceReport::route('/{record}/view')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
