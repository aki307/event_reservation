<?php

namespace App\Services;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Google\Client;
use Google\Service\Calendar;
use App\Models\User;
use Google\Service\Calendar\Event as GoogleEvent;

class EventService
{
    public function createEvent($data, $user_id)
    {

        $event = Event::create([
            'title' => $data->input('title'),
            'start_date_and_time' => $data->input('start_date_and_time'),
            'end_date_and_time' => $data->input('end_date_and_time'),
            'location' => $data->input('location'),
            'description' => $data->input('description'),
            'group_id' => $data->input('group_id'),
            'user_id' => $user_id,
        ]);

        return $event;
    }

    public function getTodaysEvents()
    {
        $today = Carbon::today();
        $events = Event::where('start_date_and_time', '<=', $today)
            ->where(function ($query) use ($today) {
                $query->where('end_date_and_time', '>=', $today)
                    ->orWhereNull('end_date_and_time');
            })
            ->paginate(5);
        return $events;
    }

    public function getAllEvents($request)
    {

        $events = Event::query();
        $titleTerm = $request->query('title');
        if (!empty($titleTerm)) {
            $events = $events->where('title', 'LIKE', '%' . $titleTerm . '%');
        }
        $descriptionTerm = $request->query('description');
        if (!empty($descriptionTerm)) {
            $events = $events->where('title', 'LIKE', '%' . $descriptionTerm . '%');
        }
        if (empty($titleTerm) && empty($descriptionTerm)) {
            $events = Event::orderBy('id', 'desc')->paginate(5);
        } else {
            $events = $events->paginate(5);
        }
        return $events;
    }
    public function getEventById($id)
    {
        return Event::findOrFail($id);
    }

    public function updateEvent($data, $id)
    {
        $event = Event::findOrFail($id);
        $isSame = $this->matchesAttributes($data, $event);

        if ($isSame) {
            throw new \Exception('入力内容が変わっていません。');
        }
        $event->fill($data);
        $event->save();


        return $event;
    }

    protected function matchesAttributes($attributes, $event)
    {
        foreach ($attributes as $key => $value) {
            if ($key !== 'password' && $event->$key !== $value) {
                return false;
            }
        }
        return true;
    }

    public function deleteEvent($id)
    {
        $userId = Auth::id();
        $event = Event::findOrFail($id);
        if ($event->user_id != $userId) {

            abort(403, '主催者本人でないと削除できません');
        }
        $event->delete();
    }

    public function createGoogleCalendar($data, $userId)
    {
        $user = User::find($userId);
        $googleUser = $user->googleUser;
        $googleToken = $googleUser->token;
        $client = new Client();
        $client->setAccessToken($googleToken);

        $calendarService = new Calendar($client);

        $event = new GoogleEvent();
        $event->setSummary($data->input('title'));
        $event->setLocation($data->input('location'));
        $event->setDescription($data->input('description'));
        $event->setStart(new Calendar\EventDateTime(['dateTime' => $data->input('start_date_and_time'), 'timeZone' => 'Asia/Tokyo']));
        $event->setEnd(new Calendar\EventDateTime(['dateTime' => $data->input('end_date_and_time'), 'timeZone' => 'Asia/Tokyo']));
        $calendarId = 'primary';
        $createdEvent = $calendarService->events->insert($calendarId, $event);

        return redirect()->back()->with('success', 'イベントをカレンダーに追加しました。');
    }
}
