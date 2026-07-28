<?php

namespace App\Filament\Pages;

use App\Services\SettingsService;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use UnitEnum;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    use HasPageShield;

    protected string $view = 'filament.pages.settings';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string | UnitEnum | null $navigationGroup = 'System';

    protected static ?int $navigationSort = 99;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            app(SettingsService::class)->all()
        );
    }

    public function form(Schema $schema): Schema
    {
        $user = auth()->user();
        $canManageRoleSettings = $user?->hasRole('super_admin') || $user?->can('manage_settings');

        return $schema
            ->components([
                Section::make('General Information')
                    ->description('Profil dasar perusahaan')
                    ->schema([
                        TextInput::make('general.company_name')
                            ->label('Company Name')
                            ->required()
                            ->helperText('Nama lembaga.'),
                        TextInput::make('general.company_email')
                            ->label('Company Email')
                            ->email()
                            ->helperText('Alamat email resmi.'),
                        TextInput::make('general.company_phone')
                            ->label('Company Phone')
                            ->helperText('Nomor telepon resmi.'),
                        FileUpload::make('general.logo')
                            ->label('Company Logo')
                            ->disk('public')
                            ->directory('company-logos')
                            ->image()
                            ->visibility('public')
                            ->moveFiles()
                            ->helperText('Unggah logo lembaga.'),
                        Textarea::make('general.company_address')
                            ->label('Company Address')
                            ->rows(3)
                            ->helperText('Alamat kantor.'),
                        Select::make('general.timezone')
                            ->label('Timezone')
                            ->options([
                                'Asia/Jakarta' => 'Asia/Jakarta',
                                'Asia/Singapore' => 'Asia/Singapore',
                                'Asia/Kuala_Lumpur' => 'Asia/Kuala_Lumpur',
                                'UTC' => 'UTC',
                            ])
                            ->required()
                            ->helperText('Zona waktu kerja.'),
                ]),

                Section::make('Attendance Configuration')
                    ->description('Aturan absensi')
                    ->schema([
                        TextInput::make('attendance.office_latitude')
                            ->label('Office Latitude')
                            ->numeric()
                            ->step('0.0000001')
                            ->helperText('Lintang kantor.'),
                        TextInput::make('attendance.office_longitude')
                            ->label('Office Longitude')
                            ->numeric()
                            ->step('0.0000001')
                            ->helperText('Bujur kantor.'),
                        TextInput::make('attendance.radius')
                            ->label('Attendance Radius (meters)')
                            ->numeric()
                            ->minValue(50)
                            ->helperText('Jarak absensi.'),
                        TimePicker::make('attendance.check_in_time')
                            ->label('Default Check In Time')
                            ->helperText('Jam masuk default.'),
                        TimePicker::make('attendance.check_out_time')
                            ->label('Default Check Out Time')
                            ->helperText('Jam pulang default.'),
                        TextInput::make('attendance.grace_period_minutes')
                            ->label('Grace Period (minutes)')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Toleransi keterlambatan.'),
                        TextInput::make('attendance.working_hours_per_day')
                            ->label('Working Hours Per Day')
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Jam kerja harian.'),
                ]),

                Section::make('Leave Management')
                    ->description('Kebijakan cuti')
                    ->schema([
                        TextInput::make('leave.default_leave_days')
                            ->label('Default Leave Days')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Cuti default.'),
                        TextInput::make('leave.max_leave_days_per_request')
                            ->label('Max Leave Days Per Request')
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Maksimal hari per permohonan.'),
                        Checkbox::make('leave.require_approval')
                            ->label('Require Approval For Leave Requests')
                            ->helperText('Wajib persetujuan.'),
                ]),
                Section::make('Overtime Management')
                    ->description('Overtime Setting')
                    ->schema([
                        TextInput::make('payroll.free_overtime_hours')
                            ->label('Free Overtime Hours')
                            ->minValue(0)
                            ->helperText('Free Overtime Hours'),
                        TextInput::make('payroll.max_paid_overtime_hours')
                            ->label('Max paid overtime Per Request')
                            ->numeric()
                            ->minValue(1)
                            ->helperText('MMax paid overtime Per Request.'),
                ]),

                Section::make('Payroll Settings')
                    ->description('Default penggajian')
                    ->schema([
                        TextInput::make('payroll.currency')
                            ->label('Currency')
                            ->required()
                            ->helperText('Mata uang utama.'),
                        Select::make('payroll.payroll_cycle')
                            ->label('Payroll Cycle')
                            ->options([
                                'monthly' => 'Monthly',
                                'biweekly' => 'Biweekly',
                                'weekly' => 'Weekly',
                            ])
                            ->required()
                            ->helperText('Siklus pembayaran.'),
                        TextInput::make('payroll.overtime_rate')
                            ->label('Overtime Rate')
                            ->numeric()
                            ->step('0.1')
                            ->helperText('Nilai lembur.'),
                        TextInput::make('payroll.tax_rate')
                            ->label('Tax Rate')
                            ->numeric()
                            ->step('0.01')
                            ->helperText('Tarif pajak.'),
                ]),
                Section::make('Payroll Approval')
                    ->description('Default Approval')
                    ->schema([
                        TextInput::make('payroll.prepared_by'),
                        TextInput::make('payroll.approved_by'),
                        TextInput::make('payroll.received_by'),
                ]),

                Section::make('Notifications')
                    ->description('Pengingat otomatis')
                    ->schema([
                        Checkbox::make('notifications.reminder_enabled')
                            ->label('Enable Attendance Reminder')
                            ->helperText('Aktifkan pengingat.'),
                        TextInput::make('notifications.reminder_before_minutes')
                            ->label('Reminder Before (minutes)')
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Jeda pengingat.'),
                ]),

                Section::make('Security & Access')
                    ->description('Kontrol keamanan')
                    ->schema([
                        Checkbox::make('security.face_attendance_enabled')
                            ->label('Enable Face Attendance')
                            ->helperText('Aktifkan absensi wajah.'),
                ]),

                ...($canManageRoleSettings ? [
                    Section::make('Role & Permission Settings')
                        ->description('Aturan akses khusus')
                        ->schema([
                            Select::make('settings_scope')
                                ->label('Apply Settings For')
                                ->options([
                                    'global' => 'Global',
                                    'role' => 'Role',
                                    'permission' => 'Permission',
                                ])
                                ->default('global')
                                ->live()
                                ->helperText('Terapkan untuk global, role, atau permission.'),
                            Select::make('settings_role')
                                ->label('Role')
                                ->options(
                                    Role::query()->pluck('name', 'name')->mapWithKeys(fn ($name) => [$name => ucfirst($name)])->toArray()
                                )
                                ->visible(fn (callable $get) => $get('settings_scope') === 'role')
                                ->helperText('Pilih role target.'),
                            Select::make('settings_permission')
                                ->label('Permission')
                                ->options(
                                    Permission::query()->pluck('name', 'name')->mapWithKeys(fn ($name) => [$name => ucfirst($name)])->toArray()
                                )
                                ->visible(fn (callable $get) => $get('settings_scope') === 'permission')
                                ->helperText('Pilih permission target.'),
                        ]),
                ] : []),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan')
                ->action(fn () => $this->save())
                ->icon('heroicon-o-check')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $scope = $data['settings_scope'] ?? 'global';
        $role = $data['settings_role'] ?? null;
        $permission = $data['settings_permission'] ?? null;

        if (isset($data['general']['logo'])) {
            $logoInput = $data['general']['logo'];

            $logoPath = app(SettingsService::class)->saveLogo($logoInput);

            if ($logoPath) {
                $data['general']['logo'] = $logoPath;
            }
        }

        if ($scope === 'role' && $role) {
            app(SettingsService::class)->putForRole($role, $data);
        } elseif ($scope === 'permission' && $permission) {
            app(SettingsService::class)->putForPermission($permission, $data);
        } else {
            app(SettingsService::class)->put($data);
        }

        // <-- isi ulang form
        $this->form->fill(
            app(SettingsService::class)->all(auth()->user())
        );

        Notification::make()
            ->title('Pengaturan berhasil diperbarui')
            ->success()
            ->send();
    }

    public function getTitle(): string
    {
        return 'Pengaturan Sistem';
    }

    public function getHeading(): string
    {
        return 'Pengaturan Sistem';
    }
}