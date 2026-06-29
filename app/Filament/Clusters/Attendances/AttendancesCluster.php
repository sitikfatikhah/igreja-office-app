<?php

namespace App\Filament\Clusters\Attendances;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class AttendancesCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    protected static ?string $slug = 'attendance-management';
}
