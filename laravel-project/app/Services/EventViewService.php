<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventViewService
{
    
    public function findOrCreate($event)
    {
        $eventView = $event->views()->firstOrCreate([
            'event_id' => $event->id,
        ]);
        return $eventView;
    }
    
    public function countView($eventView){
        $eventView->increment('views_count');
    }
}
