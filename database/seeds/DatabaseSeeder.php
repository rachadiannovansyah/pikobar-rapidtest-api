<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AreasSeeder::class);
        $this->call(OccupationTypeSeeder::class);
        $this->call(RdtEventSeeder::class);
        $this->call(RdtApplicantSeeder::class);
        $this->call(FasyankesSeeder::class);
    }
}
