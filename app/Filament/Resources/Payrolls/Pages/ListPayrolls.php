<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Clusters\Payrolls\PayrollsCluster;
use App\Filament\Resources\Payrolls\PayrollResource;
use App\Services\PayrollService;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPayrolls extends ListRecords
{
    protected static string $resource = PayrollResource::class;

    protected static ?string $cluster = PayrollsCluster::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make('generatePayroll')
                ->label('Generate Payroll')
                ->icon('heroicon-o-arrow-path')
                ->form([
                    Select::make('user_id')
                        ->label('Employee')
                        ->relationship('user', 'name')
                        ->required(),
                    DatePicker::make('start_date')
                        ->required(),
                    DatePicker::make('end_date')
                        ->required(),
                ])
                ->action(function (array $data) {
                    app(PayrollService::class)
                        ->generateForUser(
                            $data['user_id'],
                            $data['start_date'],
                            $data['end_date'],
                        );
                     Notification::make()
                        ->title('Report Generated Successfully')
                        ->success()
                        ->send();
                }),
        ];
    }
}
