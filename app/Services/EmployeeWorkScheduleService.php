<?php

namespace App\Services;

use App\Models\EmployeeWorkSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class EmployeeWorkScheduleService
{
    protected Collection $scheduleCache;

    public function __construct()
    {
        $this->scheduleCache = collect();
    }

    /**
     * Load seluruh jadwal libur user sekali saja.
     */
    public function loadSchedules(User $user): void
    {
        $this->scheduleCache = EmployeeWorkSchedule::query()
            ->where('user_id', $user->id)
            ->orderBy('effective_from')
            ->get();
    }

    /**
     * Mengambil jadwal libur yang berlaku pada tanggal tertentu.
     */
    public function getSchedule(User $user, Carbon $date): ?EmployeeWorkSchedule
    {
        if ($this->scheduleCache->isEmpty()) {
            $this->loadSchedules($user);
        }

        return $this->scheduleCache
            ->filter(function (EmployeeWorkSchedule $schedule) use ($date) {

                if (Carbon::parse($schedule->effective_from)->gt($date)) {
                    return false;
                }

                if (
                    $schedule->effective_until &&
                    Carbon::parse($schedule->effective_until)->lt($date)
                ) {
                    return false;
                }

                return true;
            })
            ->sortByDesc('effective_from')
            ->first();
    }

    /**
     * Mengecek apakah tanggal tersebut merupakan hari kerja.
     */
    public function isWorkingDay(User $user, Carbon $date): bool
    {
        $schedule = $this->getSchedule($user, $date);

        if (! $schedule) {
            // Jika belum ada jadwal libur, anggap semua hari adalah hari kerja
            return true;
        }

        $offDays = collect($schedule->off_days ?? [])
            ->map(fn ($day) => strtolower($day));

        return ! $offDays->contains(
            strtolower($date->englishDayOfWeek)
        );
    }

    /**
     * Mengambil daftar hari libur (off days) dari jadwal.
     */
    public function getOffDays(User $user, Carbon $date): Collection
    {
        $schedule = $this->getSchedule($user, $date);

        if (! $schedule) {
            return collect();
        }

        return collect($schedule->off_days ?? [])
            ->map(fn ($day) => strtolower($day));
    }
}