<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Exports\PayrollExporter;
use App\Filament\Resources\Payrolls\PayrollResource;
use App\Models\PayrollDetail;
use App\Models\Payrolls;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
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
                PayrollDetail::query()
                    ->where('payroll_id', $this->record->id)
                    ->with('user')
            )
            ->columns([
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable(),
                TextColumn::make('category')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('qty')
                    ->label('Quantity')
                    ->sortable(),
                TextColumn::make('rate')
                    ->label('Rate')
                    ->money('IDR', true)
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('IDR', true)
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Employee')
                    ->sortable(),
            ])
            ->paginated(false)
            ->striped();
    }

    protected function getTableHeaderActions(): array
    {
        return [
            EditAction::make(),
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
                        TextEntry::make('user.name')->label('Employee Name'),
                        TextEntry::make('user.nip')->label('NIP'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn(string $state) => match ($state){
                                'present'  => 'success',
                                'late'     => 'warning',
                                'overtime' => 'info',
                                'absent'   => 'gray',
                                default    => 'gray',
                            }),
                        TextEntry::make('pay_period')->label('Period'),
                        TextEntry::make('base_salary')->label('Base Salary'),
                        TextEntry::make('gross_pay')->label('Gross Pay'),
                        TextEntry::make('net_pay')->label('Net Pay'),
                        TextEntry::make('additions')->label('Additions'),
                        TextEntry::make('overtime_hours')->label('Overtime Hours'),
                        TextEntry::make('overtime_pay')->label('Overtime Pay'),
                        TextEntry::make('allowance')->label('Allowance'),
                        TextEntry::make('status'),
                        TextEntry::make('generated_at'),

                        
                    ])
            ]);
    }
}
