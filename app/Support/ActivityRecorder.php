<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\DataChange;
use Illuminate\Database\Eloquent\Model;

class ActivityRecorder
{
    public static function activity(string $role, string $activity, ?string $userName = null): void
    {
        ActivityLog::create([
            'role' => $role,
            'user_name' => $userName ?? session('auth_name'),
            'activity' => $activity,
        ]);
    }

    public static function dataChange(
        string $action,
        string $dataType,
        string $dataName,
        ?array $beforeData = null,
        ?array $afterData = null,
        ?Model $target = null,
    ): void {
        DataChange::create([
            'action' => $action,
            'data_type' => $dataType,
            'data_name' => $dataName,
            'actor_role' => self::roleName(),
            'actor_name' => session('auth_name'),
            'target_table' => $target?->getTable(),
            'target_id' => $target?->getKey(),
            'before_data' => $beforeData,
            'after_data' => $afterData,
        ]);
    }

    private static function roleName(): string
    {
        return match ((int) session('auth_level')) {
            5 => 'Owner',
            4 => 'Manager',
            3 => 'Cashier',
            2 => 'Baker',
            1 => 'Waiter',
            default => 'Customer',
        };
    }
}
