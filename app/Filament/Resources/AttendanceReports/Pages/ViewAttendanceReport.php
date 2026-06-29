<?php

namespace App\Filament\Resources\AttendanceReports\Pages;

use App\Filament\Exports\AttendanceDetailExporter;
use App\Filament\Resources\AttendanceReports\AttendanceReportResource;
use App\Models\Attendance;
use App\Models\AttendanceReport;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Concerns\RestrictsFileUploadsToSchemaComponents;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ViewAttendanceReport extends ViewRecord implements HasTable, HasSchemas, HasActions
{
    use InteractsWithTable;
    use InteractsWithSchemas;
    use InteractsWithActions;
    // use RestrictsFileUploadsToSchemaComponents;

    protected static string $resource = AttendanceReportResource::class;

    protected string $view = 'filament.pages.view-attendance-report';

    public function mount(int | string $record): void
    {
        parent::mount($record);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Attendance::query()
                    ->where('user_id', $this->record->user_id)
                    ->whereBetween('date', [
                        $this->record->getRawOriginal('start_date'),
                        $this->record->getRawOriginal('end_date'),
                    ])
                    ->orderBy('date')
            )
            ->columns([
                TextColumn::make('date')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('check_in')
                    ->label('Check In')
                    ->dateTime('H:i')
                    ->placeholder('-'),
                TextColumn::make('check_out')
                    ->label('Check Out')
                    ->dateTime('H:i')
                    ->placeholder('-'),
                TextColumn::make('total_hours')
                    ->label('Hours')
                    ->suffix(' hrs'),
                TextColumn::make('is_late')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state ? 'Late' : 'On Time')
                    ->badge()
                    ->color(fn ($state) => $state ? 'warning' : 'success'),
                TextColumn::make('overtime_hours')
                    ->label('Overtime')
                    ->suffix(' hrs'),
            ])
            ->paginated(false)
            ->striped();
    }

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make('exportDetail')
                ->label('Export Detail')
                ->icon('heroicon-o-arrow-down-tray')
                // ->color('success')
                ->exporter(AttendanceDetailExporter::class)
                ->formats([ExportFormat::Csv, ExportFormat::Xlsx])
                ->modifyQueryUsing(function ($query) {
                    $record = $this->getRecord();

                    return $query
                        ->where('user_id', $record->user_id)
                        ->whereBetween('date', [
                            $record->getRawOriginal('start_date'),
                            $record->getRawOriginal('end_date'),
                        ])
                        ->orderBy('date');
                }),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->record($this->getRecord())
            ->schema([
                Section::make('Report Summary')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('user.name')->label('Karyawan'),
                        TextEntry::make('user.nip')->label('NIP'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state) => match ($state) {
                                'present'  => 'success',
                                'late'     => 'warning',
                                'overtime' => 'info',
                                'absent'   => 'gray',
                                default    => 'gray',
                            }),
                        TextEntry::make('start_date')->label('Period Start')->date('d M Y'),
                        TextEntry::make('end_date')->label('Period End')->date('d M Y'),
                        TextEntry::make('total_hours')->label('Total Working Hours')->suffix(' hrs'),
                        TextEntry::make('total_overtime')->label('Total Overtime')->suffix(' hrs'),
                        TextEntry::make('total_late')->label('Total Late')->suffix(' hrs'),
                    ]),
            ]);
    }
}