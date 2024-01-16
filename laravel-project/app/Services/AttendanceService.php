<?php

namespace App\Services;

use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceService
{
    public function checkExistingAttendance($userId, $eventId)
    {
        return Attendance::where('user_id', $userId)->where('event_id', $eventId)->first();
    }

    public function createAttendance($userId, $eventId)
    {
        $attendance = new Attendance();
        $attendance->user_id = $userId;
        $attendance->event_id = $eventId;
        $attendance->save();
    }

    public function attendEvent($eventId)
    {
        $userId = Auth::id();
        if ($this->checkExistingAttendance($userId, $eventId)) {
            return false;
        }
        $this->createAttendance($userId, $eventId);
        
    }

    public function unattendEvent($eventId)
    {
        $userId = Auth::id();
        $attendance = $this->checkExistingAttendance($userId, $eventId);
        if (!$attendance) {
            return false;
        }
        $attendance->delete();
    }

    public function getUserAttendances()
    {
        $userId = Auth::id();
        $userAttendances = Attendance::where('user_id', $userId)->pluck('event_id')->toArray();
        return $userAttendances;
    }
}
