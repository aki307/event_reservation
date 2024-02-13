<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Services\GroupService;
use App\Services\AttendanceService;
use Illuminate\Support\Facades\Auth;


class FavoriteController extends Controller
{   
    protected $groupService;
    protected $attendanceService;

    public function __construct( GroupService $groupService, AttendanceService $attendanceService)
    {
        
        $this->groupService = $groupService;
        $this->attendanceService = $attendanceService;
        
    }

    public function store(Request $request, Event $event)
    {   
        $user = $request->user();
        $request->user()->favoriteEvents()->toggle($event->id);
        $favorited = $user->favoriteEvents()->where('event_id', $event->id)->exists();
        $favoritesCount = $event->favoritedByUsers()->count();
        return response()->json(['favorited' => $favorited, 'favoritesCount' => $favoritesCount,]);
    }

    public function index(Request $request)
{
    $user = $request->user();
    $events = $user->favoriteEvents; 
    $groups = $this->groupService->getAllGroups();
    $userAttendances = $this->attendanceService->getUserAttendances();

    return view('favorites.index', ['events' => $events, 'groups' => $groups, 'userAttendance' => $userAttendances]);
}
}
