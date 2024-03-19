<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AttendanceService;
use Illuminate\Support\Facades\Log;
use App\Services\EventService;
use App\Models\Event;

class AttendanceController extends Controller
{
    protected $attendanceService;
    protected $eventService;

    public function __construct(AttendanceService $attendanceService, EventService $eventService)
    {
        $this->attendanceService = $attendanceService;
        $this->eventService = $eventService;
    }

    public function store(Request $request, $eventId)
    {
        try {
            $isGoogleUser = $request->session()->get('is_google_login', 'false');
            if ($isGoogleUser === true) {
                $event = $this->eventService->getEventById($eventId);
                $user_id = Auth::id();
                $createdGoogleCalendarEventId = $this->eventService->createGoogleCalendar($event, $user_id);
            }
            if($createdGoogleCalendarEventId) {
                $this->attendanceService->attendEvent($eventId,$createdGoogleCalendarEventId);
            }else {
                $createdGoogleCalendarEventId=null;
                $this->attendanceService->attendEvent($eventId,$createdGoogleCalendarEventId);
            }
            return back();
        } catch (\Exception $e) {
            Log::error("Attendance store failed: " . $e->getMessage(), ['event_id' => $eventId, 'user_id' => Auth::id()]);
            return back()->withErrors(['custom_error' => $e->getMessage()]);
        }
    }

    public function destroy($eventId)
    {
        try {
            $isGoogleUser = session()->get('is_google_login', 'false');
            if ($isGoogleUser === true) {
                $event = $this->eventService->getEventById($eventId);
                $user_id = Auth::id();
                $createdGoogleCalendarEventId = $this->eventService->createGoogleCalendar($event, $user_id);
            }
            if($createdGoogleCalendarEventId) {
                $this->attendanceService->attendEvent($eventId,$createdGoogleCalendarEventId);
            }else {
                $createdGoogleCalendarEventId=null;
                $this->attendanceService->attendEvent($eventId,$createdGoogleCalendarEventId);
            }
            $this->attendanceService->unattendEvent($eventId);
            return back();
        } catch (\Exception $e) {
            Log::error("Attendance destroy failed: " . $e->getMessage(), ['event_id' => $eventId, 'user_id' => Auth::id()]);
            return back()->withErrors(['custom_error' => $e->getMessage()]);
        }
    }
}
