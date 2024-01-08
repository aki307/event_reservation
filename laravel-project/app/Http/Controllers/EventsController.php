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

class EventsController extends Controller
{
    protected $eventService;
    protected $groupService;

    public function __construct(EventService $eventService, GroupService $groupService)
    {
        $this->eventService = $eventService;
        $this->groupService = $groupService;
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

    public function show($id)
    {
        $event = $this->eventService->getEventById($id);
        $groups = $this->groupService->getAllGroups();

        return view('events.show', compact('event', 'groups'));
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
