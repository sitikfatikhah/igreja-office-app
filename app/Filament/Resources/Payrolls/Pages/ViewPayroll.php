<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\PayrollResource;
use App\Models\Payrolls;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ViewPayroll extends ViewRecord implements HasTable, HasSchemas, HasActions
{
    use InteractsWithTable;
    use InteractsWithSchemas;
    use InteractsWithActions;

    protected static string $resource = PayrollResource::class;

    protected string $view = 'filament.pages.view-payroll';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payrolls::query()
                    ->where('user_id', $this->record->user_id)
                    ->whereBetween('date', [
                        $this->record->getRawOriginal('period')
                    ])
                    ->orderBy('date')
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('Karyawan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pay_period')
                    ->label('Periode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('base_salary')
                    ->label('Gaji Pokok')
                    ->money('IDR', true)
                    ->sortable(),
                TextColumn::make('gross_pay')
                    ->label(Gross)
                    ->money('IDR', true)
                    ->sortable(),
                TextColumn::make('net_pay')
                    ->label('Net')
                    ->money('IDR', true)
                    ->sortable(),
                TextColumn::make('deductions')
                    ->label('Pengurangan')
                    ->money('IDR', true)
                    ->sortable(),
                TextColumn::make('additions')
                    ->label('Penambahan')
                    ->money('IDR', true)
                    ->sortable(),
                TextColumn::make('generated_at')
                    ->label('Generated_at')
                    ->dateTime()
                    ->sortable(),
                SelectColumn::make('status')
                    ->label('Status')
                    ->searchable(),
            ])
            ->paginated(false)
            ->striped();
    }

    protected function getTableHeaderActions(): array
    {
        return [
            EditAction::make(),

            ExportAction::make('exportDetail')
                ->label('ExportDetail')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(PayrollExporter::class)
                ->formats([ExportFormat::csv, ExportFormat::Xlsx])
                ->modifyQueryUsing(function ($query){
                    $record = $this->getRecord();

                    return $query
                        ->where('user_id', $record->user_id)
                        ->where('pay_period')
                        ->orderBy('date');
                }),

        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->record($this->getRecord())
            ->schema([
                Section::make('Payroll Summary')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('user.name')->label('Karyawan'),
                        TextEntry::make(user.nip)->label('NIP'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn(string $state) => match ($state){
                                'present'  => 'success',
                                'late'     => 'warning',
                                'overtime' => 'info',
                                'absent'   => 'gray',
                                default    => 'gray',
                            }),
                        TextEntry::make('pay_period')->label('Periode'),
                        TextEntry::make('base_salary')->label('Gaji Pokok'),
                        TextEntry::make('gross_pay')->label(Gross),
                        TextEntry::make('net_pay')->label('Net'),
                        TextEntry::make('additions')->label('Penambahan'),
                        TextEntry::make('overtime_hours')->label('pengurangan'),
                        TextEntry::make('overtime_pay')->label('Lembur'),
                        TextEntry::make('allowance')->label('allowance'),
                        TextEntry::make('status'),
                        TextEntry::make('generated_at'),

                        
                    ])
            ]);
    }
}
