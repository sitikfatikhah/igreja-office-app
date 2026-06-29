<?php

namespace Tests\Unit;

use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_persists_scalar_values_as_json_documents(): void
    {
        $service = new SettingsService();

        $service->put([
            'general' => [
                'company_name' => 'IGreja Office',
            ],
            'attendance' => [
                'radius' => 200,
            ],
            'leave' => [
                'require_approval' => true,
            ],
        ]);

        $radiusSetting = Setting::query()->where('key', 'radius')->where('group', 'attendance')->first();
        $this->assertNotNull($radiusSetting);
        $this->assertSame(200, json_decode($radiusSetting->value, true));

        $approvalSetting = Setting::query()->where('key', 'require_approval')->where('group', 'leave')->first();
        $this->assertNotNull($approvalSetting);
        $this->assertTrue(json_decode($approvalSetting->value, true));
    }
}
