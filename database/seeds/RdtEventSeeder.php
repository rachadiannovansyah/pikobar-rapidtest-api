<?php

use App\RdtEvent;
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
        });
    }
}
