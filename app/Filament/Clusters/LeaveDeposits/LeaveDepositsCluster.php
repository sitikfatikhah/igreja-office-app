<?php

namespace App\Filament\Clusters\LeaveDeposits;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class LeaveDepositsCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::InboxArrowDown;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;
}
