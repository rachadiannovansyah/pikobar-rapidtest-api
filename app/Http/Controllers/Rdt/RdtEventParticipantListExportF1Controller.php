<?php

namespace App\Http\Controllers\Rdt;

use App\Http\Controllers\Controller;
use App\Entities\RdtEvent;
use File;
use DB;
use Carbon\Carbon;
use App\Enums\PersonCaseStatusEnum;
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

        $header = [
            'NO',
            'NOMOR SAMPEL',
            'TANGGAL KUNJUNGAN',
            'KEWARGANEGARAAN',
            'KATEGORI',
            'STATUS',
            'NAMA PASIEN',
            'NIK',
            'USIA TAHUN',
            'USIA BULAN',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'JENIS KELAMIN',
            'ALAMAT',
            'RT',
            'RW',
            'KELURAHAN',
            'KECAMATAN',
            'KOTA',
            'KUNJUNGAN',
            'HASI RDT',
            'SUHU',
            'INSTANSI PENGIRIM',
            'FASYANKES/ DINKES',
            'DOKTER',
            'TELP DOKTER'
        ];

        $rowFromValues = WriterEntityFactory::createRowFromArray($header);
        $writer->addRow($rowFromValues);

        DB::statement(DB::raw('set @number=0'));

        $data = \DB::table('rdt_invitations')
                ->select(
                    DB::raw('@number:=@number+1 as number'),
                    'rdt_invitations.lab_code_sample',
                    'rdt_invitations.attended_at',
                    'rdt_invitations.lab_result_type',
                    'rdt_invitations.registration_code',
                    'rdt_applicants.person_status',
                    'rdt_applicants.occupation_type',
                    'rdt_applicants.name',
                    'rdt_applicants.nik',
                    'rdt_applicants.phone_number',
                    'rdt_applicants.gender',
                    'rdt_applicants.birth_date',
                    'rdt_applicants.birth_place',
                    'rdt_applicants.address',
                    'rdt_applicants.symptoms',
                    'rdt_applicants.city_visited',
                    'rdt_applicants.have_interacted',
                    'rdt_applicants.congenital_disease',
                    'rdt_events.host_name',
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
                ->where('rdt_invitations.rdt_event_id', $id)
                ->get();

        $personStatusValue=[
            'CONFIRMED' => 'Terkonfirmasi',
            'SUSPECT' => 'Kasus Suspek',
            'PROBABLE' => 'Kasus Probable',
            'CLOSE_CONTACT' => 'Kontak Erat',
            'NOT_ALL' => 'Bukan Semuanya',
            'UNKNOWN' => 'Tidak Tahu',
            'ODP' => 'Orang Dalam pengawasan',
            'OTG' => 'Orang Tanpa Gejala',
            'PDP' => 'Pasien Dalam Pengawasan'
        ];

        foreach ($data as $row) {
            if ($row->birth_date) {
                $age = Carbon::parse($row->birth_date)->diff(Carbon::now());
                $ageYear = $age->format('%y');
                $ageMonth = $age->format('%m');
            } else {
                $ageYear = '';
                $ageMonth = '';
            }
            
            $row =  [
                        $row->number,
                        $row->lab_code_sample ,
                        $row->attended_at,
                        'WNI',
                        '',
                        $personStatusValue[$row->person_status],
                        $row->name,
                        $row->nik,
                        $ageYear,
                        $ageMonth,
                        $row->birth_place,
                        $row->birth_date,
                        $row->gender,
                        $row->address,
                        '',
                        '',
                        $row->village,
                        $row->district,
                        $row->city,
                        1,
                        $row->lab_result_type,
                        '',
                        '',
                        $row->host_name,
                        '',
                        ''
                    ];
        
            $rowFromValues = WriterEntityFactory::createRowFromArray($row);
            $writer->addRow($rowFromValues);
        }
        
        $writer->close();
    }
}
