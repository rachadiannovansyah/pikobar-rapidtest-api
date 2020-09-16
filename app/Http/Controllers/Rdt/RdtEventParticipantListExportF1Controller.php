<?php

namespace App\Http\Controllers\Rdt;

use App\Http\Controllers\Controller;
use App\Entities\RdtEvent;
use File;
use DB;
use Carbon\Carbon;
use App\Enums\Gender;
use Illuminate\Support\Str;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

class RdtEventParticipantListExportF1Controller extends Controller
{
    public function __invoke($id)
    {
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Expose-Headers: *');

        $writer = WriterEntityFactory::createXLSXWriter();

        $rdtEvent = RdtEvent::findOrFail($id);
        $fileName = Str::slug($rdtEvent->event_name, '-') . '.xlsx';
        $writer->openToBrowser($fileName);

        $header =  [
            'NO',
            'INSTANSI_PENGIRIM',
            'FASYANKES/DINKES',
            'KODE_SAMPLE',
            'KODE_REGISTRASI',
            'STATUS_SASARAN',
            'PEKERJAAN/ KATEGORI',
            'NAMA_PASIEN',
            'NIK',
            'NOMOR_TELEPON' ,
            'JENIS_KELAMIN',
            'TEMPAT_LAHIR',
            'TANGGAL_LAHIR',
            'KOTA',
            'KECAMATAN',
            'KELURAHAN',
            'ALAMAT',
            'KEWARGANEGARAAN',
            'KUNJUNGAN',
            'GEJALA',
            'TANGGAL_MUNCUL_GEJALA',
            'PENYAKIT PENYERTA',
            'RIWAYAT PERJALANAN',
            'APAKAH_PERNAH_KONTAK',
            'JIKA_IYA_TANGGAL_KONTAK',
            'TANGGAL_ACARA',
            'JAM_ACARA',
            'TEMPAT_ACARA',
            'KETERANGAN',
            'HASIL_TEST',
            'NILAI_CT'
        ];

        $rowFromValues = WriterEntityFactory::createRowFromArray($header);
        $writer->addRow($rowFromValues);
        DB::statement(DB::raw('set @number=0'));

        $data = \DB::table('rdt_invitations')
                ->select(
                    DB::raw('@number:=@number+1 as number'),
                    'rdt_invitations.lab_code_sample',
                    'rdt_invitations.lab_result_type',
                    'rdt_invitations.registration_code',
                    'rdt_applicants.person_status',
                    'rdt_applicants.occupation_type',
                    'rdt_applicants.name',
                    'rdt_applicants.nik',
                    'rdt_applicants.phone_number',
                    'rdt_applicants.gender',
                    'rdt_applicants.birth_date',
                    'rdt_applicants.address',
                    'rdt_applicants.symptoms',
                    'rdt_applicants.city_visited',
                    'rdt_applicants.have_interacted',
                    'rdt_applicants.congenital_disease',
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

        return $data;

        foreach ($data as $row) {
            if (Gender::MALE()->getValue() === $row->gender) {
                $gender = "Laki Laki";
            } elseif (Gender::FEMALE()->getValue() === $row->gender) {
                $gender = "perempuan";
            } else {
                $gender = "";
            }

            $row =  [
                $row->number,
                '',
                '',
                $row->lab_code_sample ,
                $row->registration_code,
                $row->person_status,
                $row->occupation_type,
                $row->name,
                $row->nik,
                $row->phone_number ,
                $gender,
                '',
                $row->birth_date,
                $row->city,
                $row->district,
                '',
                $row->address,
                'WNI',
                '',
                $row->symptoms,
                '',
                $row->congenital_disease,
                $row->city_visited,
                $row->have_interacted,
                '',
                Carbon::parse($row->start_at)->format('Y-m-d'),
                Carbon::parse($row->start_at)->format('H:i:s') . ' - ' . Carbon::parse($row->end_at)->format('H:i:s'),
                $row->event_location,
                '',
                $row->lab_result_type,
                ''
            ];

            $rowFromValues = WriterEntityFactory::createRowFromArray($row);
            $writer->addRow($rowFromValues);
        }

        $writer->close();
    }
}
