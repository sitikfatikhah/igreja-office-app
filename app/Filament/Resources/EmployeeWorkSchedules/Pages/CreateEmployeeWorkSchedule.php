<?php

namespace App\Filament\Resources\EmployeeWorkSchedules\Pages;

use App\Filament\Resources\EmployeeWorkSchedules\EmployeeWorkScheduleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployeeWorkSchedule extends CreateRecord
{
    protected static string $resource = EmployeeWorkScheduleResource::class;

    protected function getRedirectToUrl():string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }
}
