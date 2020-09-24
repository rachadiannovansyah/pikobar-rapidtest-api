<?php

use App\Entities\Fasyankes;
use Illuminate\Database\Seeder;

class FasyankesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fasyankes = [
            [
                "name" => "RSUP Dr. Hasan Sadikin",
                "type" => "rumah_sakit",
            ],[
                "name" => "RSUD Kota Bogor",
                "type" => "rumah_sakit",
                
            ],[
                "name" => "RSP Dr. H.A. Rotinsulu",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSP Dr. Goenawan P",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Dr. Slamet",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD R. Syamsudin, SH",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Indramayu",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Gunungjati",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "Rumkit Tk. ll Dustira",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Cibinong",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Ciawi",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Cibabat",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Al Ihsan",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RS Bhayangkara Sartika Asih",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD dr. Soekardjo",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD SMC Kab. Tasik",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RS Paru Prov. Jabar Sidawangi",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Bayu Asih",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Karawang",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Sekarwangi",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Subang",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Waled",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Arjawinangun",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD 45 Kuningan",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Kab Bekasi",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Sumedang",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Banjar",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Ciamis",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Cideres",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Majalaya",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RS Lanud dr. M. Salamun",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Kota Depok",
                "type" => "rumah_sakit",
               
            ],[
                "name" => "RSUD Sayang",
                "type" => "rumah_sakit",
               
            ]
        ];

        Fasyankes::insert($fasyankes);
    }
}
