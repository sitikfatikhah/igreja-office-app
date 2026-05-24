<?php

namespace App\Filament\Resources\AttendanceReports\Tables;

use App\Models\Attendance;
use App\Models\User;
use App\Services\AttendanceReportService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
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
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable(),
                TextColumn::make('position')
                    ->searchable(),
                TextColumn::make('check_in')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('check_out')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('total_hours')
                    ->numeric()
                    ->label('Working Hours')
                    ->state(function ($record) {
                        if (!$record->check_in || !$record->check_out) {
                            return '-';
                        }

                        $start = Carbon::parse($record->check_in);
                        $end = Carbon::parse($record->check_out);

                        return round($start->diffInMinutes($end) / 60, 2) . ' hrs';
                    })
                    ->sortable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                IconColumn::make('face_verified')
                    ->label('Face Verified')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->paginated(false)
            ->query(Attendance::query()->with('user'))

            ->filters([
                SelectFilter::make('user_id')
                    ->label('Karyawan')
                    ->options(
                        User::query()
                            ->get()
                            ->mapWithKeys(fn ($user) => [
                                $user->id => "{$user->nip} - {$user->name}"
                            ])
                    )
                    ->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $query, $value) =>$query->where('user_id', $value)
                        );
                    }),
                Filter::make('date')
                    ->label('Tanggal')
                    ->form([
                        DatePicker::make('from')
                        ->label('from'),
                        DatePicker::make('until')
                        ->label('to')
                    ])
                    ->query(function(Builder $query, array $data){
                        return $query
                        ->when(
                            $data['from'] ?? null,
                            fn (Builder $query, $date) =>$query->whereDate('date', '>=', $date)
                        )
                        ->when(
                            $data['until'] ?? null,
                            fn (Builder $query, $date) =>$query->whereDate('date', '<=', $date)
                        );
                    }
                    ),


                TrashedFilter::make(),
            ])
            // layout: FiltersLayout::AboveContent)

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
                Action::make('generateReport')
                    ->label('Generate Report')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function(){
                        app(AttendanceReportService::class)->generateAll();
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Success')
                            ->body('Attendance reports generated successfully')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
            ]);
            
    }
}
