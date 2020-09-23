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
        foreach ($this->items() as $item) {
            Fasyankes::query()->updateOrCreate(
                \Illuminate\Support\Arr::only($item, ['name', 'type']),
                $item
            );
        }
    }

    public function items(): array
    {
        return [
            [
                "name"=> "RSUP Dr. Hasan Sadikin",
                "type"=> "rumah_sakit",
            ],[
                "name"=> "RSUD Kota Bogor",
                "type"=> "rumah_sakit",
                
            ],[
                "name"=> "RSP Dr. H.A. Rotinsulu",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSP Dr. Goenawan P",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Dr. Slamet",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD R. Syamsudin, SH",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Indramayu",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Gunungjati",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "Rumkit Tk. ll Dustira",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Cibinong",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Ciawi",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Cibabat",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Al Ihsan",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RS Bhayangkara Sartika Asih",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD dr. Soekardjo",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD SMC Kab. Tasik",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RS Paru Prov. Jabar Sidawangi",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Bayu Asih",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Karawang",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Sekarwangi",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Subang",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Waled",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Arjawinangun",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD 45 Kuningan",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Kab Bekasi",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Sumedang",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Banjar",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Ciamis",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Cideres",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Majalaya",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RS Lanud dr. M. Salamun",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Kota Depok",
                "type"=> "rumah_sakit",
               
            ],[
                "name"=> "RSUD Sayang",
                "type"=> "rumah_sakit",
               
            ],
            [
                "name"=> "Kota Bandung",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Bandung",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Bandung Barat",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kota Banjar",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kota Cimahi",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Bekasi",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kota Bekasi",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Bogor",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kota Bogor",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Ciamis",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Cianjur",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Cirebon",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kota Cirebon",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kota Depok",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Garut",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Indramayu",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Karawang",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Kuningan",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Majalengka",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Pangandaran",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Purwakarta",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Subang",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Sukabumi",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kota Sukabumi",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten SUmedang",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kota Tasikmalaya",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Tasikmalaya",
                "type"=> "dinkes",
               
            ],
            [
                "name"=> "Kabupaten Tasikmalaya",
                "type"=> "dinkes",
               
            ],
        ];
    }
}
