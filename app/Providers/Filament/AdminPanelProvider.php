<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\AttendanceReportStatusWidget;
use App\Filament\Widgets\AttendanceStats;
use App\Filament\Widgets\AttendanceTodayWidget;
use App\Filament\Widgets\AttendanceTrendChart;
use App\Filament\Widgets\LeaveRequestChart;
use App\Filament\Widgets\LeaveStats;
use App\Filament\Widgets\OvertimesStats;
use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\OverviewStats;
use App\Filament\Widgets\PayrollStats;
use App\Filament\Widgets\PayrollStatusWidget;
use App\Filament\Widgets\PendingApprovalWidget;
use App\Filament\Widgets\RecentActivitiesWidget;
use App\Services\SettingsService;
use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Enums\UserMenuPosition;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('app')
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->emailChangeVerification()
            ->profile()
            ->userMenu(position: UserMenuPosition::Sidebar)
            ->brandLogo(fn () => app(SettingsService::class)->getCompanyLogo())
            ->brandLogoHeight('4rem')
            ->colors([
                    'primary' => Color::hex('#3B82F6'), // Blue-500
                    'gray' => Color::Slate,
            ])
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                LeaveStats::class,
                OvertimesStats::class,
                PayrollStatusWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
