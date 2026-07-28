<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingsService
{
    protected array $cache = [];

    // public function __construct(
    //     protected SettingsService $settings,
    // ) {}
    protected array $defaults = [
        'general' => [
            'company_name' => 'IGreja Office',
            'company_email' => 'info@igreja.local',
            'company_phone' => '+62 812-0000-0000',
            'company_address' => 'Jl. Contoh No. 1',
            'timezone' => 'Asia/Jakarta',
            'logo' => null,
        ],
        'attendance' => [
            'office_latitude' => -6.211197166367241,
            'office_longitude' => 106.56546102232565,
            'radius' => 200,
            'check_in_time' => '08:00',
            'check_out_time' => '17:00',
            'grace_period_minutes' => 15,
            'working_hours_per_day' => 8,
        ],
        'leave' => [
            'default_leave_days' => 12,
            'max_leave_days_per_request' => 7,
            'require_approval' => true,
        ],
        'payroll' => [
            'currency' => 'IDR',
            'payroll_cycle' => 'monthly',

            'monthly_working_hours' => 173,
            'free_overtime_hours' => 1,
            'max_paid_overtime_hours' => 4,
            'overtime_rate' => 1.5,

            'tax_rate' => 0,

            'prepared_by' => '',
            'approved_by' => '',
            'received_by' => '',
        ],
        'notifications' => [
            'reminder_enabled' => true,
            'reminder_before_minutes' => 15,
        ],
        'security' => [
            'face_attendance_enabled' => false,
        ],
        'permissions' => [
            'manage_settings' => 'super_admin',
            'manage_company_profile' => 'super_admin',
            'manage_attendance_settings' => 'admin',
        ],
    ];

    public function all(?User $user = null): array
    {
        $cacheKey = $user
            ? 'user_' . $user->getAuthIdentifier()
            : 'global';

        if (! isset($this->cache[$cacheKey])) {
            $this->cache[$cacheKey] = array_replace_recursive(
                $this->defaults,
                $this->loadFromDatabase($user)
            );
        }

        return $this->cache[$cacheKey];
    }

    public function get(string $key, mixed $default = null, ?User $user = null): mixed
    {
        return data_get($this->all($user), $key, $default);
    }

    public function put(array $data): void
    {
        $data = $this->sanitizePayload($data);

        $this->storeToDatabase($data);

        $this->clearCache();

        $this->apply($data, Auth::user());
    }

    public function putForRole(string $role, array $data): void
    {
        $data = $this->sanitizePayload($data);

        $this->storeToDatabase($data);

        $this->clearCache();

        $this->apply($data, Auth::user());
    }

    public function putForPermission(string $permission, array $data): void
    {
        $data = $this->sanitizePayload($data);

        $this->storeToDatabase($data);

        $this->clearCache();

        $this->apply($data, Auth::user());
    }

    public function getCompanyName(?User $user = null): string
    {
        return (string) $this->get('general.company_name', 'IGreja Office', $user);
    }

    public function getCompanyLogo(?User $user = null): string
    {
        $logo = $this->get('general.logo', null, $user);

        // Tidak ada logo
        if (blank($logo)) {
            return asset('images/logo_gki.jpeg');
        }

        // Jika array (data rusak), gunakan logo default
        if (is_array($logo)) {
            return asset('images/logo_gki.jpeg');
        }

        // URL eksternal
        if (filter_var($logo, FILTER_VALIDATE_URL)) {
            return $logo;
        }

        // File tidak ada
        if (! Storage::disk('public')->exists($logo)) {
            return asset('images/logo_gki.jpeg');
        }

        return Storage::disk('public')->url($logo);

        
    }

    public function saveLogo(mixed $file): ?string
    {
        if (blank($file)) {
            return null;
        }

        if ($file instanceof UploadedFile) {
            return $file->store('company-logos', 'public');
        }

        if (is_array($file)) {
            foreach ($file as $value) {
                if ($value instanceof UploadedFile) {
                    return $value->store('company-logos', 'public');
                }

                if (is_string($value)) {
                    return $value;
                }
            }

            return null;
        }

        return is_string($file) ? $file : null;
    }

    public function apply(?array $data, ?User $user = null): void
    {
        $settings = array_replace_recursive(
            $this->all($user),
            $data ?? []
        );

        foreach (Arr::dot($settings) as $key => $value) {
            config(["settings.{$key}" => $value]);
        }

        $map = [
            'general.company_name'                     => 'app.name',

            'attendance.office_latitude'              => 'attendance.office_latitude',
            'attendance.office_longitude'             => 'attendance.office_longitude',
            'attendance.radius'                       => 'attendance.radius',
            'attendance.check_in_time'                => 'attendance.check_in_time',
            'attendance.check_out_time'               => 'attendance.check_out_time',
            'attendance.grace_period_minutes'         => 'attendance.grace_period_minutes',
            'attendance.working_hours_per_day'        => 'attendance.working_hours_per_day',

            'leave.default_leave_days'                => 'leave.default_leave_days',
            'leave.max_leave_days_per_request'        => 'leave.max_leave_days_per_request',
            'leave.require_approval'                  => 'leave.require_approval',

            'payroll.currency'                        => 'payroll.currency',
            'payroll.payroll_cycle'                   => 'payroll.payroll_cycle',
            'payroll.monthly_working_hours'           => 'payroll.monthly_working_hours',
            'payroll.free_overtime_hours'             => 'payroll.free_overtime_hours',
            'payroll.max_paid_overtime_hours'         => 'payroll.max_paid_overtime_hours',
            'payroll.overtime_rate'                   => 'payroll.overtime_rate',
            'payroll.tax_rate'                        => 'payroll.tax_rate',
            'payroll.prepared_by'                     => 'payroll.prepared_by',
            'payroll.approved_by'                     => 'payroll.approved_by',
            'payroll.received_by'                     => 'payroll.received_by',

            'notifications.reminder_enabled'          => 'notifications.reminder_enabled',
            'notifications.reminder_before_minutes'   => 'notifications.reminder_before_minutes',

            'security.face_attendance_enabled'        => 'security.face_attendance_enabled',
        ];

        foreach ($map as $settingKey => $configKey) {
            $value = data_get($settings, $settingKey);

            if ($value !== null) {
                config([$configKey => $value]);
            }
        }
    }

    public function clearCache(): void
    {
        $this->cache = [];
    }

    protected function loadFromDatabase(?User $user): array
    {
        $query = Setting::query()
            ->where('is_active', true)
            ->where(function ($query) use ($user) {
                $query->where('scope', 'global');

                if ($user) {
                    $roles = method_exists($user, 'getRoleNames')
                        ? $user->getRoleNames()->toArray()
                        : [];

                    if (! empty($roles)) {
                        $query->orWhere(function ($q) use ($roles) {
                            $q->where('scope', 'role')
                                ->whereIn('role', $roles);
                        });
                    }

                    $permissions = method_exists($user, 'getAllPermissions')
                        ? $user->getAllPermissions()->pluck('name')->toArray()
                        : [];

                    if (! empty($permissions)) {
                        $query->orWhere(function ($q) use ($permissions) {
                            $q->where('scope', 'permission')
                                ->whereIn('permission', $permissions);
                        });
                    }
                }
            });

        $settings = $query->get();

        $result = [];

        foreach ($settings as $setting) {
            data_set(
                $result,
                "{$setting->group}.{$setting->key}",
                json_decode($setting->value, true)
            );
        }

        return $result;
    }

    protected function storeToDatabase(
        array $data,
        ?string $role = null,
        ?string $permission = null
    ): void {

        $scope = match (true) {
            $role !== null => 'role',
            $permission !== null => 'permission',
            default => 'global',
        };

        foreach ($this->normalizePayload($data) as $group => $values) {

            foreach ($values as $key => $value) {

                Setting::updateOrCreate(

                    [
                        'key'        => $key,
                        'group'      => $group,
                        'scope'      => $scope,
                        'role'       => $role,
                        'permission' => $permission,
                    ],

                    [
                        'value'      => $this->encodeValue($value),
                        'type'       => match (true) {
                            is_bool($value) => 'boolean',
                            is_numeric($value) => 'number',
                            is_array($value) => 'array',
                            default => 'string',
                        },

                        'is_active' => true,
                    ]

                );
            }
        }
    }

    protected function normalizePayload(array $payload): array
    {
        $normalized = [];

        foreach ($payload as $group => $value) {
            if (is_array($value)) {
                $normalized[$group] = $value;
            }
        }

        return $normalized;
    }

    protected function sanitizePayload(array $data): array
    {
        return collect($data)
            ->except(['settings_scope', 'settings_role', 'settings_permission'])
            ->toArray();
    }

    protected function encodeValue(mixed $value): string
    {
        return json_encode($value, JSON_UNESCAPED_SLASHES);
    }
}
