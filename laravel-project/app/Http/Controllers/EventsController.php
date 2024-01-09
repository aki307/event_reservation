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

class EventsController extends Controller
{
    protected $eventService;
    protected $groupService;
    protected $attendanceService;

    public function __construct(EventService $eventService, GroupService $groupService,AttendanceService $attendanceService )
    {
        $this->eventService = $eventService;
        $this->groupService = $groupService;
        $this->attendanceService = $attendanceService;
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
            return view('events.registerEventComplete');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['custom_error' => $e->getMessage()])->withInput();
        }
    }

    public function todaysEvents()
    {
        $events = $this->eventService->getTodaysEvents();
        $groups = $this->groupService->getAllGroups();

        return view('events.todays_event', ['events' => $events, 'groups' => $groups]);
    }

    public function index()
    {
        $events = $this->eventService->getAllEvents();
        $groups = $this->groupService->getAllGroups();
        return view('events.index', compact('events', 'groups'));
    }

    public function show($id, AttendanceService $attendanceService)
    {
        $event = $this->eventService->getEventById($id);
        $groups = $this->groupService->getAllGroups();
        $userAttendance = $attendanceService->checkExistingAttendance(Auth::id(), $id);
        $attendees = $event->attendances()->with('user')->get()->map(function ($attendance) {
            return $attendance->user;
        });
       
        return view('events.show', compact('event', 'groups', 'userAttendance', 'attendees'));
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
            return redirect()->back()->withErrors(['custom_error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $this->eventService->deleteEvent($id);

        return redirect()->route('events.index');
    }
}
