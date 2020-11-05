<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\RdtEvent;
use DB;
use Carbon\Carbon;
use App\Enums\PersonCaseStatusEnum;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SinkronisasiController extends Controller
{
    public function __invoke(RdtEvent $rdtEvent)
    {
        $labkesUrl = env('LABKES_URL');
        
        $data = DB::table('rdt_invitations')
        ->select(
            DB::raw('@number:=@number+1 as number'),
            'rdt_invitations.lab_code_sample',
            'rdt_invitations.attended_at',
            'rdt_invitations.lab_result_type',
            'rdt_invitations.attend_location',
            'rdt_invitations.registration_code',
            'rdt_applicants.person_status',
            'rdt_applicants.occupation_type',
            'rdt_applicants.name',
            'rdt_applicants.nik',
            'rdt_applicants.phone_number',
            'rdt_applicants.gender',
            'rdt_applicants.province_code',
            'rdt_applicants.city_code',
            'rdt_applicants.district_code',
            'rdt_applicants.village_code',
            'rdt_applicants.birth_date',
            'rdt_applicants.birth_place',
            'rdt_applicants.address',
            'rdt_applicants.symptoms',
            'rdt_applicants.city_visited',
            'rdt_applicants.have_interacted',
            'rdt_applicants.congenital_disease',
            'rdt_events.host_name',
            'fasyankes.id as fasyankes_id',
            'rdt_events.start_at',
            'rdt_events.end_at',
            'rdt_events.event_location',
            'city.name as city',
            'district.name as district',
            'village.name as village'
        )
        ->leftJoin('rdt_applicants', 'rdt_applicants.id', 'rdt_invitations.rdt_applicant_id')
        ->leftJoin('rdt_events', 'rdt_events.id', 'rdt_invitations.rdt_event_id')
        ->leftJoin('areas as city', 'city.code_kemendagri', 'rdt_applicants.city_code')
        ->leftJoin('areas as district', 'district.code_kemendagri', 'rdt_applicants.district_code')
        ->leftJoin('areas as village', 'village.code_kemendagri', 'rdt_applicants.village_code')
        ->leftJoin('fasyankes', 'fasyankes.name', '=', 'rdt_events.host_name')
        ->where('rdt_invitations.rdt_event_id', $rdtEvent->id)
        ->whereNotNull('rdt_invitations.lab_code_sample')
        ->whereNotNull('rdt_invitations.attended_at')
        ->get();

        $personStatusValue = [
            'CONFIRMED' => 'konfirmasi',
            'SUSPECT' => 'suspek',
            'PROBABLE' => 'probable',
            'CLOSE_CONTACT' => 'kontak erat',
            'NOT_ALL' => 'tanpa kriteria',
            'UNKNOWN' => 'tanpa kriteria',
            'ODP' => 'tanpa kriteria',
            'OTG' => 'tanpa kriteria',
            'PDP' => 'tanpa kriteria'
        ];

        $codeSamplesuccessSyn = [];

        foreach ($data as $row) {
            if ($row->birth_date) {
                $age = Carbon::parse($row->birth_date)->diff(Carbon::now());
                $ageYear = $age->format('%y');
                $ageMonth = $age->format('%m');
            } else {
                $ageYear = '';
                $ageMonth = '';
            }

            if ($row->gender == 'F') {
                $gender = 'P';
            } elseif ($row->gender == 'M') {
                $gender = "L";
            } else {
                $gender = "";
            }

            $payload = [
                'kewarganegaraan'       =>  'WNI',
                'kategori'              =>  $rdtEvent->event_name . ' ' . Carbon::parse($row->attended_at)->format('dmY'),
                'kriteria'              =>  $personStatusValue[$row->person_status],
                'nama_pasien'           =>  $row->name,
                'nik'                   =>  $row->nik,
                'registration_code'     =>  $row->registration_code,
                'tempat_lahir'          =>  $row->birth_place,
                'tanggal_lahir'         =>  Carbon::parse($row->birth_date)->format('d-m-Y'),
                'jenis_kelamin'         =>  $row->gender=='M'?'L':'P',
                'provinsi_id'           =>  str_replace('.', '', $row->province_code),
                'kota_id'               =>  str_replace('.', '', $row->city_code),
                'kecamatan_id'          =>  str_replace('.', '', $row->district_code),
                'kelurahan_id'          =>  str_replace('.', '', $row->village_code),
                'alamat'                =>  $row->address,
                'rt'                    =>  null,
                'rw'                    =>  null,
                'no_hp'                 =>  $row->phone_number,
                'suhu'                  =>  null,
                'nomor_sampel'          =>  $row->lab_code_sample,
                'keterangan'            =>  null,
                'hasil_rdt'             =>  $row->lab_result_type,
                'usia_tahun'            =>  $ageYear,
                'usia_bulan'            =>  $ageMonth,
                'kunjungan'             =>  1,
                'fasyankes_id'          =>  $row->fasyankes_id,
                'tanggal_kunjungan'     =>  Carbon::parse($row->attended_at)->format('Y-m-d'),
                'rs_kunjungan'          =>  $row->attend_location
            ];

            $response = Http::post($labkesUrl, $payload);

            $result = json_decode($response->getBody()->getContents());
            if (isset($result->status)) {
                $codeSamplesuccessSyn[] = $row->lab_code_sample;
            }
        }

        DB::table('rdt_invitations')->whereIn('lab_code_sample', array_values($codeSamplesuccessSyn))->update(['synchronization_at'=>now()]);

        return response()->json(
            ['message' => count($codeSamplesuccessSyn) . ' Data Berhasil Dikirim' ]
        );
    }
}
