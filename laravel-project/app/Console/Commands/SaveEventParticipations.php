<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\EventParticipation;
use App\Models\Attendance;
use Carbon\Carbon;

class SaveEventParticipations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-event-participations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save event names in participations at the event start time.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $events = Event::where('start_date_and_time', '<=', $now)
            ->where('processed_for_participations', false)
            ->get();

        foreach ($events as $event) {
            $attendances = Attendance::where('event_id', $event->id)->get();
            foreach ($attendances as $attendance) {
                EventParticipation::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'user_id' => $attendance->user_id,
                    ],
                    ['event_title' => $event->title]
                );
            }
            $event->processed_for_participations = true;
            $event->save();
        }
    }
}
