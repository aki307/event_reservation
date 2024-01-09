<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AttendanceService;

class AttendanceController extends Controller
{   
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService )
    {
        $this->attendanceService = $attendanceService;
    }

    public function store(Request $request, $eventId)
    {
        $this->attendanceService->attendEvent($eventId);
        return back();
    }

    public function destroy($eventId)
    {
        $this->attendanceService->unattendEvent($eventId);
        return back();
    }

    
}
