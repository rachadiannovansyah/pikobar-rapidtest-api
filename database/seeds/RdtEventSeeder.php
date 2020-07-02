<?php

use App\Entities\RdtEvent;
use App\Entities\RdtEventSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class RdtEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $start = new Carbon();
        $start->hours(8)->minutes(0)->seconds(0);

        factory(RdtEvent::class, 20)->create()->each(function (RdtEvent $event) use ($start) {
            $event->start_at = $start->addDays(1);
            $event->end_at   = $start->copy()->addHours(4);
            $event->save();

            $schedulesCount = 4;
            $scheduleStart  = $event->start_at;

            for ($n = 1; $n <= $schedulesCount; $n++) {
                $schedule           = new RdtEventSchedule();
                $schedule->start_at = $scheduleStart;
                $schedule->end_at   = $scheduleStart->copy()->addMinutes(60);
                $schedule->event()->associate($event);
                $schedule->save();

                $scheduleStart = $schedule->end_at;
            }
        });
    }
}
