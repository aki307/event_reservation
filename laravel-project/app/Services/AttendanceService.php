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

    public function checkExistingGoogleCalendarEventId($userId, $eventId)
    {
        return Attendance::where('user_id', $userId)->where('event_id', $eventId)->first();
    }

    public function createAttendance($userId, $eventId, $createdGoogleCalendarEventId)
    {
        $attendance = new Attendance();
        $attendance->user_id = $userId;
        $attendance->event_id = $eventId;
        if ($createdGoogleCalendarEventId !== null) {
            $attendance->created_google_calendar_event_id = $createdGoogleCalendarEventId;
        }
        $attendance->save();
    }

    public function attendEvent($eventId, $createdGoogleCalendarEventId)
    {
        $userId = Auth::id();
        if ($this->checkExistingAttendance($userId, $eventId)) {
            throw new \Exception("既にイベント参加予定になっています");
        }
        $this->createAttendance($userId, $eventId, $createdGoogleCalendarEventId);
    }

    public function unattendEvent($eventId)
    {
        $userId = Auth::id();
        $attendance = $this->checkExistingAttendance($userId, $eventId);
        if (!$attendance) {
            throw new \Exception("既にイベント不参加になっています");
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
