<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingsService
{
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
            'overtime_rate' => 1.5,
            'tax_rate' => 0.05,
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
        return array_replace_recursive($this->defaults, $this->loadFromDatabase($user));
    }

    public function get(string $key, mixed $default = null, ?User $user = null): mixed
    {
        return data_get($this->all($user), $key, $default);
    }

    public function put(array $data): void
    {
        $this->storeToDatabase($this->sanitizePayload($data));
        $this->apply($this->sanitizePayload($data));
    }

    public function putForRole(string $role, array $data): void
    {
        $this->storeToDatabase($this->sanitizePayload($data), role: $role);
        $this->apply($this->sanitizePayload($data));
    }

    public function putForPermission(string $permission, array $data): void
    {
        $this->storeToDatabase($this->sanitizePayload($data), permission: $permission);
        $this->apply($this->sanitizePayload($data));
    }

    public function getCompanyName(?User $user = null): string
    {
        return (string) $this->get('general.company_name', 'IGreja Office', $user);
    }

    public function getCompanyLogo(?User $user = null): string
    {
        $logo = $this->get('general.logo', null, $user);

        if (blank($logo)) {
            return asset('images/logo_gki.jpeg');
        }

        return str_starts_with((string) $logo, ['http://', 'https://']) ? (string) $logo : asset((string) $logo);
    }

    public function saveLogo(mixed $file): ?string
    {
        if (blank($file)) {
            return null;
        }

        if (is_array($file) && isset($file['tmp_name'])) {
            $file = $file['tmp_name'];
        }

        if ($file instanceof UploadedFile) {
            if (! $file->isValid()) {
                return null;
            }

            $path = $file->store('company-logos', 'public');

            return $path ? Storage::disk('public')->url($path) : null;
        }

        if (is_string($file) && file_exists($file) && is_file($file)) {
            $filename = Str::slug(pathinfo($file, PATHINFO_FILENAME)) . '.' . pathinfo($file, PATHINFO_EXTENSION);
            $path = 'company-logos/' . Str::uuid() . '-' . $filename;
            Storage::disk('public')->put($path, file_get_contents($file));

            return Storage::disk('public')->url($path);
        }

        return null;
    }

    public function apply(?array $data = null): void
    {
        $settings = $data ?? $this->all();

        foreach (Arr::dot($settings) as $key => $value) {
            config(['settings.' . $key => $value]);
        }

        config([
            'app.name' => $settings['general']['company_name'] ?? config('app.name'),
            'attendance.office_latitude' => $settings['attendance']['office_latitude'] ?? config('attendance.office_latitude'),
            'attendance.office_longitude' => $settings['attendance']['office_longitude'] ?? config('attendance.office_longitude'),
            'attendance.radius' => $settings['attendance']['radius'] ?? config('attendance.radius'),
        ]);
    }

    protected function loadFromDatabase(?User $user = null): array
    {
        $settings = Setting::query()->where('is_active', true)->get();
        $globalPayload = [];
        $rolePayload = [];
        $permissionPayload = [];

        foreach ($settings as $item) {
            $group = $item->group ?? 'general';
            $key = $item->key;

            if (str_contains($key, '.')) {
                [$group, $key] = explode('.', $key, 2);
            }

            $value = is_string($item->value) ? json_decode($item->value, true) : $item->value;
            $value = is_array($value) ? $value : $value;
            $payload = match ($item->scope) {
                'role' => $rolePayload,
                'permission' => $permissionPayload,
                default => $globalPayload,
            };

            if ($item->scope === 'role' && $user && filled($item->role) && $user->hasRole($item->role)) {
                $payload[$group][$key] = $value;
            } elseif ($item->scope === 'permission' && $user && filled($item->permission) && $user->can($item->permission)) {
                $payload[$group][$key] = $value;
            } elseif ($item->scope !== 'role' && $item->scope !== 'permission') {
                $payload[$group][$key] = $value;
            }
        }

        return $this->normalizePayload(array_replace_recursive($this->defaults, $globalPayload, $rolePayload, $permissionPayload));
    }

    protected function storeToDatabase(array $data, ?string $role = null, ?string $permission = null): void
    {
        $grouped = $this->normalizePayload($data);

        foreach ($grouped as $group => $values) {
            foreach ($values as $key => $value) {
                Setting::updateOrCreate(
                    [
                        'key' => $key,
                        'group' => $group,
                        'role' => $role,
                        'permission' => $permission,
                    ],
                    [
                        'value' => $this->encodeValue($value),
                        'type' => is_bool($value) ? 'boolean' : (is_numeric($value) ? 'number' : 'string'),
                        'scope' => $role ? 'role' : ($permission ? 'permission' : 'global'),
                        'role' => $role,
                        'permission' => $permission,
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

    protected function encodeValue(mixed $value): mixed
    {
        if ($value instanceof UploadedFile) {
            return json_encode((string) $value);
        }

        $encoded = json_encode($value, JSON_UNESCAPED_SLASHES);

        return $encoded === false ? json_encode((string) $value, JSON_UNESCAPED_SLASHES) : $encoded;
    }
}
