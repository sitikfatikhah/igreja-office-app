<?php

namespace App\Filament\Clusters\Payrolls;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class PayrollsCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;
}
