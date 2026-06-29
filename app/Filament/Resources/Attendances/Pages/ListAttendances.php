<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Clusters\Attendances\AttendancesCluster;
use App\Filament\Resources\Attendances\AttendanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Override;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;
    protected static ?string $cluster = AttendancesCluster::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

}
