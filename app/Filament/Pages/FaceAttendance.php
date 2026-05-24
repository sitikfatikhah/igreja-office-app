<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\Attendances\AttendancesCluster;
use Filament\Pages\Page;

class FaceAttendance extends Page
{
    protected string $view = 'filament.pages.face-attendance';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $cluster = AttendancesCluster::class;
}
