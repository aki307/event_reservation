<?php

namespace App\Services;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Google\Client;
use Google\Service\Calendar;
use App\Models\User;
use Google\Service\Calendar\Event as GoogleEvent;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

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

    public function createGoogleCalendar($event, $userId)
    {
        $user = User::find($userId);
        $userAccessToken = $user->googleUser->token;
        $client = new Google_Client();
        $client->setAccessToken($userAccessToken);
        $service = new Google_Service_Calendar($client);

        $googleEvent = new Google_Service_Calendar_Event();
        $googleEvent->setSummary($event->title);
        $googleEvent->setLocation($event->location);

        // [memo]終了時刻が任意入力で未入力の場合、Google側は開始時刻を反映したいときに
        // 終了時刻も必須とするので開始時刻の日付部分のみを抽出して反映して終了時刻を終日に設定する
        // 開始日時の設定
        $start = new \Google_Service_Calendar_EventDateTime();
        if (empty($event->end_date_and_time)) {
            $startDate = explode(' ', $event->start_date_and_time)[0];
            $start->setDate($startDate);
        } else {
            $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $event->start_date_and_time)
                ->setTimezone('Asia/Tokyo')
                ->toRfc3339String();
            $start->setDateTime($startDateTime);
            $start->setTimeZone('Asia/Tokyo');
        }
        $googleEvent->setStart($start);

        // 終了日時の設定
        $end = new \Google_Service_Calendar_EventDateTime();
        if (empty($event->end_date_and_time)) {
            // 終了日が設定されていない場合、終日イベントとして開始日を終了日と設定する
            $end->setDate($startDate);
        } else {
            $endDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $event->end_date_and_time)
                ->setTimezone('Asia/Tokyo')
                ->toRfc3339String();
            $end->setDateTime($endDateTime);
            $end->setTimeZone('Asia/Tokyo');
        }
        $googleEvent->setEnd($end);



        if (!is_null($event->description)) {
            $googleEvent->setDescription($event->description);
        }


        $createdEvent = $service->events->insert('primary', $googleEvent);
        $createdGoogleCalendarEventId = $createdEvent->getId();

        return $createdGoogleCalendarEventId;
    }

    public function deleteGoogleCalendar(){
        
    }
}
