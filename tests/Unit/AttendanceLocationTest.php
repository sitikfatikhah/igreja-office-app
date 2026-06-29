<?php

namespace Tests\Unit;

use App\Models\Attendance;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AttendanceLocationTest extends TestCase
{
    public function test_office_radius_is_200_meters(): void
    {
        Config::set('attendance.radius', 200);

        $this->assertSame(200.0, Attendance::officeRadius());
    }

    public function test_distance_calculation_returns_zero_for_same_coordinates(): void
    {
        $latitude = Attendance::officeLatitude();
        $longitude = Attendance::officeLongitude();

        $distance = Attendance::calculateDistanceMeters($latitude, $longitude, $latitude, $longitude);

        $this->assertSame(0.0, $distance);
    }

    public function test_is_within_office_radius_returns_true_for_nearby_location(): void
    {
        Config::set('attendance.office_latitude', -6.211197166367241);
        Config::set('attendance.office_longitude', 106.56546102232565);
        Config::set('attendance.radius', 200);

        $this->assertTrue(Attendance::isWithinOfficeRadius(-6.211300, 106.565500));
    }

    public function test_is_within_office_radius_returns_false_for_far_location(): void
    {
        Config::set('attendance.office_latitude', -6.211197166367241);
        Config::set('attendance.office_longitude', 106.56546102232565);
        Config::set('attendance.radius', 200);

        $this->assertFalse(Attendance::isWithinOfficeRadius(-6.215000, 106.56546102232565));
    }
}
