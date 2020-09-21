<?php

use Illuminate\Database\Seeder;

class OccupationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return vooccupation_type_value
     */
    public function run()
    {
        $occupationType = [
            ['occupation_type_value'=> 99,'sequence'=>99 ,'name' => 'Belum bekerja'],
            ['occupation_type_value'=> 14,'sequence'=>14, 'name' => 'Pegawai Pemprov Jawa Barat'],
            ['occupation_type_value'=> 7,'sequence'=>7, 'name' => 'Petugas Pelayanan Publik (Kasir/Customer Service Bank, Petugas Keamanan, Loket Layanan Publik)'],
            ['occupation_type_value' => 8,'sequence'=>8, 'name' => 'Petugas Transportasi (Terminal, Airport, Stasiun, Ojol)'],
            ['occupation_type_value' => 9,'sequence'=>9, 'name' => 'Petugas Kebersihan'],
            ['occupation_type_value' => 10,'sequence'=>10, 'name' => 'Wartawan'],
            ['occupation_type_value' => 11,'sequence'=>11, 'name' => 'Pedagang Pasar'],
            ['occupation_type_value' =>12,'sequence'=>12, 'name' => 'Pemuka Agama'],
            ['occupation_type_value' =>13,'sequence'=>13, 'name' => 'Lainnya'],
            ['occupation_type_value' =>15,'sequence'=>15, 'name' => 'Hukum'],
            ['occupation_type_value' =>16,'sequence'=>16, 'name' => 'Ibu rumah tangga'],
            ['occupation_type_value' =>17,'sequence'=>17, 'name' => 'Tenaga Kesehatan'],
            ['occupation_type_value' =>18,'sequence'=>18, 'name' => 'Keuangan'],
            ['occupation_type_value' =>19,'sequence'=>19, 'name' => 'Mesin'],
            ['occupation_type_value' =>20,'sequence'=>20, 'name' => 'Nelayan'],
            ['occupation_type_value' => 21,'sequence'=>21, 'name' => 'Pegawai Negeri'],
            ['occupation_type_value' => 22,'sequence'=>22, 'name' => 'Pegawai Swasta'],
            ['occupation_type_value' => 23,'sequence'=>23, 'name' => 'Pelajar/mahasiswa'],
            ['occupation_type_value' => 24,'sequence'=>24, 'name' => 'Pendoccupation_type_valueikan'],
            ['occupation_type_value' => 25 ,'sequence'=>25, 'name' => 'Pemerintahan'],
            ['occupation_type_value' => 26 ,'sequence'=>26, 'name' => 'Pengobatan'],
            ['occupation_type_value' => 27 ,'sequence'=>27, 'name' => 'Petani'],
            ['occupation_type_value' => 28 ,'sequence'=>28, 'name' => 'Peternak'],
            ['occupation_type_value' => 29 ,'sequence'=> 29,'name' => 'TNI/Polisi'],
            ['occupation_type_value' => 30 ,'sequence'=> 30, 'name' => 'Tukang Bangunan'],
            ['occupation_type_value' => 31,'sequence'=> 31, 'name' => 'Wiraswasta'],
        ];

        \DB::table('occupation_types')->insert($occupationType);
    }
}
