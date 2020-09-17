<?php

use Illuminate\Database\Seeder;

class OccupationType extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $occupationType = [
            ['id'=> 99 ,'name' => 'Belum bekerja'],
            ['id'=> 14 ,'name' => 'Pegawai Pemprov Jawa Barat'],
            ['id'=> 7 ,'name' => 'Petugas Pelayanan Publik (Kasir/Customer Service Bank, Petugas Keamanan, Loket Layanan Publik)'],
            ['id' => 8 ,'name' => 'Petugas Transportasi (Terminal, Airport, Stasiun, Ojol)'],
            ['id' => 9 ,'name' => 'Petugas Kebersihan'],
            ['id' => 10,'name' => 'Wartawan'],
            ['id' => 11 ,'name' => 'Pedagang Pasar'],
            ['id' =>12 ,'name' => 'Pemuka Agama'],
            ['id' =>13 ,'name' => 'Lainnya'],
            ['id' =>15 ,'name' => 'Hukum'],
            ['id' =>16 ,'name' => 'Ibu rumah tangga'],
            ['id' =>17 ,'name' => 'Tenaga Kesehatan'],
            ['id' =>18 ,'name' => 'Keuangan'],
            ['id' =>19 ,'name' => 'Mesin'],
            ['id' =>20 ,'name' => 'Nelayan'],
            ['id' => 21,'name' => 'Pegawai Negeri'],
            ['id' => 22,'name' => 'Pegawai Swasta'],
            ['id' => 23,'name' => 'Pelajar/mahasiswa'],
            ['id' => 24,'name' => 'Pendidikan'],
            ['id' => 25 ,'name' => 'Pemerintahan'],
            ['id' => 26 ,'name' => 'Pengobatan'],
            ['id' => 27 ,'name' => 'Petani'],
            ['id' => 28 ,'name' => 'Peternak'],
            ['id' => 29 ,'name' => 'TNI/Polisi'],
            ['id' => 30 ,'name' => 'Tukang Bangunan'],
            ['id' => 31,'name' => 'Wiraswasta'],
        ];

        \DB::table('occupation_types')->insert($occupationType);
    }
}
