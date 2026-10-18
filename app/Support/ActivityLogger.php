<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function record(string $event, string $subjectType, ?int $subjectId = null, array $properties = []): void
    {
        DB::table('activity_logs')->insert([
            'user_id' => Auth::id(),
            'event' => $event,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'properties' => json_encode($properties, JSON_THROW_ON_ERROR),
            'ip_address' => Request::ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
