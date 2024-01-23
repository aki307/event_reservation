<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AttendanceService;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function store(Request $request, $eventId)
    {
        try {
            $this->attendanceService->attendEvent($eventId);
            return back();
        } catch (\Exception $e) {
            Log::error("Attendance store failed: " . $e->getMessage(), ['event_id' => $eventId, 'user_id' => Auth::id()]);
            return back()->withErrors(['custom_error' => $e->getMessage()]);
        }
    }

    public function destroy($eventId)
    {
        try {
            $this->attendanceService->unattendEvent($eventId);
            return back();
        } catch (\Exception $e) {
            Log::error("Attendance destroy failed: " . $e->getMessage(), ['event_id' => $eventId, 'user_id' => Auth::id()]);
            return back()->withErrors(['custom_error' => $e->getMessage()]);
        }
    }
}
