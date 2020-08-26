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
            'name'                   => 'JAWA BARAT',
            'parent_code_kemendagri' => null,
            'depth'                  => 1,
            'code_kemendagri'        => '32',
        ]);

        Area::create([
            'name'                   => 'KOTA BANDUNG',
            'parent_code_kemendagri' => null,
            'depth'                  => 2,
            'code_kemendagri'        => '32.73',
        ]);

        Area::create([
            'name'                   => 'SUKAJADI',
            'parent_code_kemendagri' => null,
            'depth'                  => 3,
            'code_kemendagri'        => '32.73.07',
        ]);

        Area::create([
            'name'                   => 'CIPEDES',
            'parent_code_kemendagri' => null,
            'depth'                  => 4,
            'code_kemendagri'        => '32.73.07.1002',
        ]);
    }
}
