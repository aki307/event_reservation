<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\CreateEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Services\EventService;
use App\Services\GroupService;
use Illuminate\Support\Facades\Auth;
use App\Services\AttendanceService;
use Illuminate\Support\Facades\Log;
use App\Services\EventViewService;
use App\Services\CommentService;
use App\Models\EventParticipation;

class EventsController extends Controller
{
    protected $eventService;
    protected $groupService;
    protected $attendanceService;
    protected $eventViewService;
    protected $commentService;

    public function __construct(EventService $eventService, GroupService $groupService, AttendanceService $attendanceService, EventViewService $eventViewService, CommentService $commentService)
    {
        $this->eventService = $eventService;
        $this->groupService = $groupService;
        $this->attendanceService = $attendanceService;
        $this->eventViewService = $eventViewService;
        $this->commentService = $commentService;
    }

    public function create()
    {
        $groups = $this->groupService->getAllGroups();
        return view('events.create', ['groups' => $groups]);
    }

    public function store(CreateEventRequest $request)
    {
        $user_id = Auth::id();
        try {
            $event = $this->eventService->createEvent($request, $user_id);
            $isGoogleUser = $request->session()->get('is_google_login', 'false');

            if ($isGoogleUser === true) {
                $createdGoogleCalendarEventId = $this->eventService->createGoogleCalendar($event, $user_id);
            }
            $eventId = $event->id;
            if($createdGoogleCalendarEventId) {
                $this->attendanceService->attendEvent($eventId,$createdGoogleCalendarEventId);
            }else {
                $createdGoogleCalendarEventId=null;
                $this->attendanceService->attendEvent($eventId,$createdGoogleCalendarEventId);
            }
            return view('events.registerEventComplete');
        } catch (\Exception $e) {
            Log::error("Event store failed: " . $e->getMessage(), ['user_id' => $user_id]);
            return redirect()->back()->withErrors(['custom_error' => $e->getMessage()])->withInput();
        }
    }

    public function todaysEvents()
    {
        $events = $this->eventService->getTodaysEvents();
        $groups = $this->groupService->getAllGroups();
        $userAttendances = $this->attendanceService->getUserAttendances();

        return view('events.todays_event', ['events' => $events, 'groups' => $groups, 'userAttendance' => $userAttendances]);
    }

    public function index(Request $request)
    {
        $events = $this->eventService->getAllEvents($request);
        $groups = $this->groupService->getAllGroups();
        $userAttendance = $this->attendanceService->getUserAttendances();
        return view('events.index', compact('events', 'groups', 'userAttendance'));
    }

    public function show($id, AttendanceService $attendanceService)
    {
        $event = $this->eventService->getEventById($id);
        $groups = $this->groupService->getAllGroups();
        $userAttendance = $attendanceService->checkExistingAttendance(Auth::id(), $id);
        $attendees = $event->attendances()->with('user')->get()->map(function ($attendance) {
            return $attendance->user;
        });
        $eventView = $this->eventViewService->findOrCreate($event);
        $this->eventViewService->countView($eventView);

        $comments = $event->comments()->get();

        return view('events.show', compact('event', 'groups', 'userAttendance', 'attendees', 'comments'));
    }

    public function edit($id)
    {
        $event = $this->eventService->getEventById($id);
        $groups = $this->groupService->getAllGroups();
        return view('events.edit', compact('event', 'groups'));
    }

    public function update(UpdateEventRequest $request,  $id)
    {
        try {
            $event = $this->eventService->updateEvent($request->validated(), $id);
            return redirect()->route('events.show', ['event' => $event->id]);
        } catch (\Exception $e) {
            Log::error("Event update failed: " . $e->getMessage(), ['event_id' => $id]);
            return redirect()->back()->withErrors(['custom_error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->eventService->deleteEvent($id);
            return redirect()->route('events.index');
        } catch (\Exception $e) {
            Log::error("Event deletion failed: " . $e->getMessage(), ['event_id' => $id]);
            return redirect()->back()->withErrors(['custom_error' => $e->getMessage()]);
        }
    }

    public function myAttendanceHistory()
    {
        $userId = Auth::id();
        $participations = EventParticipation::with('event')
            ->where('user_id', $userId)
            ->get();
        return view('events.attendance-history', ['events' => $participations]);
    }
}
