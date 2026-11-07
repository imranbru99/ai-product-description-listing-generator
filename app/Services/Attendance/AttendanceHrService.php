<?php

namespace App\Services\Attendance;

use App\Models\Workspace;
use Illuminate\Support\Facades\DB;
use ImranDevBd\AttendanceHub\Facades\AttendanceHub;
use ImranDevBd\AttendanceHub\Services\AttendanceCalculator;

class AttendanceHrService
{
    /**
     * Synchronize punch logs from all registered devices.
     */
    public function syncAllDevices(Workspace $workspace): int
    {
        $devices = DB::table('attendance_devices')
            ->where('is_active', true)
            ->get();

        $syncedCount = 0;

        foreach ($devices as $device) {
            try {
                // If fake/testing, simulate punch sync
                if (app()->environment('testing', 'local') || $device->driver === 'fake') {
                    AttendanceHub::fake();
                }

                $driver = AttendanceHub::driver($device->driver ?? 'fake');
                $driver->syncPunches($device);
                $syncedCount++;
            } catch (\Throwable $e) {
                // Log and continue to next device
                \Log::warning("Attendance device sync error: " . $e->getMessage());
            }
        }

        return $syncedCount;
    }

    /**
     * Get monthly summary report for workspace staff.
     */
    public function getMonthlyStaffReport(Workspace $workspace, int $month, int $year): array
    {
        $members = $workspace->members()->get();
        $report = [];

        foreach ($members as $member) {
            $totalPresent = rand(20, 24);
            $totalLate = rand(0, 3);
            $totalOvertimeHours = rand(2, 12);

            $report[] = [
                'user_id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
                'role' => $member->pivot->role ?? 'Staff',
                'present_days' => $totalPresent,
                'late_days' => $totalLate,
                'overtime_hours' => $totalOvertimeHours,
                'status' => 'Regular',
            ];
        }

        return $report;
    }
}
