<?php

namespace App\Filament\Resources\AttendanceReports\Tables;

use App\Filament\Exports\AttendanceReportExporter;
use App\Filament\Resources\AttendanceReports\AttendanceReportResource;
use App\Filament\Resources\AttendanceReports\Pages\ViewAttendanceReport;
use App\Filament\Resources\Attendances\AttendanceResource;
use App\Filament\Resources\Attendances\Pages\ListAttendances;
use App\Filament\Resources\Attendances\Pages\ViewAttendance;
use App\Models\Attendance;
use App\Models\AttendanceReport;
use App\Models\User;
use App\Services\AttendanceReportService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\Contracts\ExportFormat;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttendanceReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->paginationMode(PaginationMode::Simple)
        ->striped()
        ->query(AttendanceReport::query()->with('user'))
            ->columns([
            TextColumn::make('user.name')
                ->label('Employee')
                ->searchable()
                ->sortable(),

            TextColumn::make('user.nip')
                ->label('NIP')
                ->searchable(),

            TextColumn::make('total_present')
                ->label('Total Present')
                ->suffix(' days')
                ->sortable(),

            TextColumn::make('total_absent')
                ->label('Total Absent')
                ->suffix(' hrs'),

            TextColumn::make('total_overtime')
                ->label('Total Overtime')
                ->suffix(' hrs'),
            TextColumn::make('total_hours')
                ->label('Total Hours')
                ->suffix(' hrs'),

            TextColumn::make('status')
                ->badge()
                ->color(fn (string $state) => match ($state) {
                    'present' => 'success',
                    'late' => 'warning',
                    'overtime' => 'info',
                    'absent' => 'danger',
                    default => 'gray',
                }),
            TextColumn::make('periode')
                ->label('Periode')
                ->state(function($record){
                return Carbon::parse($record->start_date)->translatedFormat('d M Y')
                    . ' to ' .
                    Carbon::parse($record->end_date)->translatedFormat('d M Y');
                }),

            TextColumn::make('report_date')
                ->date()
                ->sortable(),
        ])
            ->paginated(true)
            ->defaultSort('report_date', 'desc')
            ->deferFilters(false)
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Employee')
                    ->relationship('user', 'name')
                    ->preload(),
                    // ->options(
                    //     User::query()
                    //         ->get()
                    //         ->mapWithKeys(fn ($user) => [
                    //             $user->id => "{$user->nip} - {$user->name}"
                    //         ])
                    // )
                    // ->query(function (Builder $query, array $data) {
                    //     return $query->when(
                    //         $data['value'] ?? null,
                    //         fn (Builder $query, $value) =>$query->where('user_id', $value)
                    //     );
                    // }),
                Filter::make('period')
                    ->label('Period')
                    ->form([
                        DatePicker::make('from')
                        ->label('from'),
                        DatePicker::make('until')
                        ->label('to')
                    ])
                    ->query(function(Builder $query, array $data){
                        return $query
                        ->when(
                            $data['from'],
                            fn (Builder $query, $date): Builder =>$query->whereDate('start_date', '>=', $date)
                        )
                        ->when(
                            $data['until'],
                            fn (Builder $query, $date): Builder =>$query->whereDate('end_date', '<=', $date)
                        );
                    }
                    ),


                // TrashedFilter::make(),
            ])
            // layout: FiltersLayout::AboveContent)

            ->recordActions([
            Action::make('View details')
                ->Url(
                    fn (AttendanceReport $record): string => AttendanceReportResource::getUrl('view', ['record'=> $record])
                    )
                ->openUrlInNewTab()
            ])

            ->filtersFormColumns(2)
            
            // ->recordActions([
            //     EditAction::make(),
            // ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            
                // Action::make('generateReport')
                //     ->label('Generate Report')
                //     ->icon('heroicon-o-arrow-path')
                //     ->action(function(){
                //         app(AttendanceReportService::class)->generateAll();
                        
                //         \Filament\Notifications\Notification::make()
                //             ->title('Success')
                //             ->body('Attendance reports generated successfully')
                //             ->success()
                //             ->send();
                //     })
                //     ->requiresConfirmation(),
            ])

            ->headerActions([
                ExportAction::make()
                    ->exporter(AttendanceReportExporter::class)
            ]);
            
            
    }
}
