<?php

namespace App\Filament\Resources\Attendances\Tables;

use App\Filament\Exports\AttendanceExporter;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Dom\Text;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function(Builder $query){
                $user = Auth::user();

                if ($user->hasRole('super_admin')){
                    return $query;
                }

                return $query->where('user_id', $user->id);
            })
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('user.name')->label('Pegawai')->sortable()->searchable(),
                TextColumn::make('user.position')->label('Jabatan')->sortable()->searchable(),
                TextColumn::make('user.nip')->label('NIP')->sortable()->searchable(),
                ImageColumn::make('photo')->label('Foto')->disk('public'),
                TextColumn::make('verification_method')->label('Metode Verifikasi')->sortable()->searchable(),
                TextColumn::make('check_in')->dateTime()->sortable(),
                TextColumn::make('check_out')->dateTime()->sortable(),
                TextColumn::make('check_in_latitude')->label('Lintang Masuk')->sortable()->searchable(),
                TextColumn::make('check_in_longitude')->label('Bujur Masuk')->sortable()->searchable(),
                TextColumn::make('check_out_latitude')->label('Lintang Pulang')->sortable()->searchable(),
                TextColumn::make('check_out_longitude')->label('Bujur Pulang')->sortable()->searchable(),
                TextColumn::make('check_in_location_name')->label('Lokasi Masuk')->sortable()->searchable(),
                TextColumn::make('check_out_location_name')->label('Lokasi Pulang')->sortable()->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Pegawai')
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
                        ->label('Dari'),
                        DatePicker::make('until')
                        ->label('Sampai')
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
                // TrashedFilter::make(),
            ])
            
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('downloadAttendance')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function ($record) {

                    $pdf = Pdf::loadView(
                        'filament.forms.attendance',
                        [
                            'payroll' => $record,
                        ]
                    );

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'attendance' . $record->id . '.pdf'
                    );
                }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                ExportAction::make()
                ->exporter(AttendanceExporter::class)
            ]);
    }
}
