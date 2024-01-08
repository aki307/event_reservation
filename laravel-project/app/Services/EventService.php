<?php

namespace App\Services;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
        $events = Event::whereDate('start_date_and_time', $today)->paginate(5);
        return $events;
    }

    public function getAllEvents()
    {
        $events = Event::orderBy('id', 'desc')->paginate(5);
        return $events;
    }
    public function getEventById($id){
        return Event::find($id);
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
}
