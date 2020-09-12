<?php

namespace App\Http\Controllers\Rdt;

use App\Http\Controllers\Controller;
use App\Entities\RdtEvent;
use Rap2hpoutre\FastExcel\FastExcel;
use File;
use DB;
use Carbon\Carbon;
use App\Enums\Gender;
use Illuminate\Support\Str;

class RdtEventParticipantListExportF1Controller extends Controller
{
    public function __invoke($id)
    {
        $rdtEvent = RdtEvent::findOrFail($id);
        $fileName = Str::slug($rdtEvent->event_name, '-') . '.xlsx';

        DB::statement(DB::raw('set @number=0'));

        $data = \DB::table('rdt_invitations')
                ->select(
                    DB::raw('@number:=@number+1 as number'),
                    'rdt_invitations.lab_code_sample',
                    'rdt_invitations.lab_result_type',
                    'rdt_invitations.registration_code',
                    'rdt_applicants.person_status',
                    'rdt_applicants.name',
                    'rdt_applicants.nik',
                    'rdt_applicants.phone_number',
                    'rdt_applicants.gender',
                    'rdt_applicants.birth_date',
                    'rdt_applicants.address',
                    'rdt_applicants.symptoms',
                    'rdt_events.start_at',
                    'rdt_events.end_at',
                    'rdt_events.event_location',
                    'city.name as city',
                    'district.name as district'
                )
                ->leftJoin('rdt_applicants', 'rdt_applicants.id', 'rdt_invitations.rdt_applicant_id')
                ->leftJoin('rdt_events', 'rdt_events.id', 'rdt_invitations.rdt_event_id')
                ->leftJoin('areas as city', 'city.code_kemendagri', 'rdt_applicants.city_code')
                ->leftJoin('areas as district', 'district.code_kemendagri', 'rdt_applicants.district_code')
                ->get();

        return (new FastExcel($data))->download($fileName, function ($row) {
            if (Gender::MALE()->getValue() === $row->gender) {
                $gender = "Laki Laki";
            } elseif (Gender::FEMALE()->getValue() === $row->gender) {
                $gender = "perempuan";
            } else {
                $gender = "";
            }

            return [
                    'NO' => $row->number,
                    'INSTANSI_PENGIRIM' => '',
                    'FASYANKES/DINKES' => '',
                    'KODE_SAMPLE' => $row->lab_code_sample ,
                    'KODE_REGISTRASI' => $row->registration_code,
                    'STATUS_SASARAN' => $row->person_status,
                    'PEKERJAAN/ KATEGORI' => '',
                    'NAMA_PASIEN' => $row->name,
                    'NIK' => $row->nik,
                    'NOMOR_TELEPON' => $row->phone_number,
                    'JENIS_KELAMIN' => $gender,
                    'TEMPAT_LAHIR' => '',
                    'TANGGAL_LAHIR' => $row->birth_date,
                    'KOTA' => $row->city,
                    'KECAMATAN' => $row->district,
                    'KELURAHAN' => '-',
                    'ALAMAT' => $row->address,
                    'KEWARGANEGARAAN' => 'WNI',
                    'KUNJUNGAN' => '',
                    'GEJALA' => $row->symptoms,
                    'TANGGAL_MUNCUL_GEJALA' => '',
                    'PENYAKIT PENYERTA' => '',
                    'RIWAYAT PERJALANAN' => '',
                    'APAKAH_PERNAH_KONTAK' => '',
                    'JIKA_IYA_TANGGAL_KONTAK' => '',
                    'TANGGAL_ACARA' => Carbon::parse($row->start_at)->format('Y-m-d'),
                    'JAM_ACARA' => Carbon::parse($row->start_at)->format('H:i:s') . ' - ' . Carbon::parse($row->end_at)->format('H:i:s'),
                    'TEMPAT_ACARA' => $row->event_location,
                    'KETERANGAN' => '',
                    'HASIL_TEST' => $row->lab_result_type,
                    'NILAI_CT' => ''
                ];
        });
    }
}
