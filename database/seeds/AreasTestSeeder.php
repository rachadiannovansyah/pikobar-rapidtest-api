<?php

use App\Entities\Area;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AreasTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Area::create([
            'name'            => 'JAWA BARAT',
            'parent_id'       => null,
            'depth'           => 1,
            'code_bps'        => '32',
            'code_kemendagri' => '32',
            'status'          => 1,
        ]);

        Area::create([
            'name'            => 'KOTA BANDUNG',
            'parent_id'       => 1,
            'depth'           => 2,
            'code_bps'        => '3273',
            'code_kemendagri' => '32.73',
            'status'          => 1,
        ]);

        Area::create([
            'name'            => 'SUKAJADI',
            'parent_id'       => 2,
            'depth'           => 3,
            'code_bps'        => '3273240',
            'code_kemendagri' => '32.73.07',
            'status'          => 1,
        ]);

        Area::create([
            'name'            => 'CIPEDES',
            'parent_id'       => 3,
            'depth'           => 4,
            'code_bps'        => '3273240004',
            'code_kemendagri' => '32.73.07.1002',
            'status'          => 1,
        ]);
    }
}
