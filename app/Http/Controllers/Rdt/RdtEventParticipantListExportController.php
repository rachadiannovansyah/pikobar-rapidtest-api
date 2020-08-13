<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Http\Controllers\Controller;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Http\Request;

class RdtEventParticipantListExportController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Entities\RdtEvent $rdtEvent
     * @return void
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function __invoke(Request $request, RdtEvent $rdtEvent)
    {
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser('test.xlsx');

        $cells = [
            WriterEntityFactory::createCell('Nomor Pendaftaran'),
            WriterEntityFactory::createCell('NIK'),
            WriterEntityFactory::createCell('Nama Peserta'),
            WriterEntityFactory::createCell('Nomor Telepon'),
            WriterEntityFactory::createCell('Jenis Kelamin'),
            WriterEntityFactory::createCell('Tanggal Lahir'),
            WriterEntityFactory::createCell('Umur (Tahun)'),
            WriterEntityFactory::createCell('Alamat Domisili'),
            WriterEntityFactory::createCell('Kab/Kota Domisili'),
            WriterEntityFactory::createCell('Kode Kab/Kota Domisili'),
            WriterEntityFactory::createCell('Kecamatan Domisili'),
            WriterEntityFactory::createCell('Kode Kecamatan Domisili'),
            WriterEntityFactory::createCell('Kelurahan/Desa Domisili'),
            WriterEntityFactory::createCell('Kode Kelurahan/Desa Domisili'),
            WriterEntityFactory::createCell('Jenis Pekerjaan / Profesi'),
            WriterEntityFactory::createCell('Nama Pekerjaan / Profesi'),
            WriterEntityFactory::createCell('Nama Tempat Bekerja'),
            WriterEntityFactory::createCell('Gejala'),
            WriterEntityFactory::createCell('Catatan Gejala'),
            WriterEntityFactory::createCell('Riwayat Kontak'),
            WriterEntityFactory::createCell('Riwayat Kegiatan'),
            WriterEntityFactory::createCell('Status Kesehatan'),
            WriterEntityFactory::createCell('Tanggal Pendaftaran'),
            WriterEntityFactory::createCell('Checkin Kehadiran'),
            WriterEntityFactory::createCell('Tanggal Hasil Lab'),
            WriterEntityFactory::createCell('Kode Sampel Lab'),
            WriterEntityFactory::createCell('Hasil Test'),
        ];

        $singleRow = WriterEntityFactory::createRow($cells);
        $writer->addRow($singleRow);

        $rdtEvent
            ->invitations()
            ->chunk(100, function ($invitations) use ($writer) {

            /**
             * @var \App\Entities\RdtInvitation[] $invitations
             */
            foreach ($invitations as $invitation) {
                $age = $invitation->applicant->birth_date ? $invitation->applicant->birth_date->age : null;

                $address = strtoupper($invitation->applicant->address);
                $address = preg_replace("/[\r\n]*/","", $address);

                $symptoms = implode(', ', $invitation->applicant->symptoms);
                $symptomsActivity= implode(', ', $invitation->applicant->symptoms_activity);

                $symptomsNotes = preg_replace("/[\r\n]*/","", $invitation->applicant->symptoms_notes);

                $cells = [
                    WriterEntityFactory::createCell($invitation->applicant->registration_code),
                    WriterEntityFactory::createCell($invitation->applicant->nik),
                    WriterEntityFactory::createCell($invitation->applicant->name),
                    WriterEntityFactory::createCell($invitation->applicant->phone_number),
                    WriterEntityFactory::createCell($invitation->applicant->gender),
                    WriterEntityFactory::createCell($invitation->applicant->birth_date ? $invitation->applicant->birth_date->toDateString() : null),
                    WriterEntityFactory::createCell($age),
                    WriterEntityFactory::createCell($address),
                    WriterEntityFactory::createCell($invitation->applicant->city ? $invitation->applicant->city->name : null),
                    WriterEntityFactory::createCell($invitation->applicant->city_code),
                    WriterEntityFactory::createCell($invitation->applicant->district ? $invitation->applicant->district->name : null),
                    WriterEntityFactory::createCell($invitation->applicant->district_code),
                    WriterEntityFactory::createCell($invitation->applicant->village ? $invitation->applicant->village->name : null),
                    WriterEntityFactory::createCell($invitation->applicant->village_code),
                    WriterEntityFactory::createCell($invitation->applicant->occupation_type),
                    WriterEntityFactory::createCell(strtoupper($invitation->applicant->occupation_name)),
                    WriterEntityFactory::createCell(strtoupper($invitation->applicant->workplace_name)),
                    WriterEntityFactory::createCell($symptoms),
                    WriterEntityFactory::createCell($symptomsNotes),
                    WriterEntityFactory::createCell($invitation->applicant->symptoms_interaction ? $invitation->applicant->symptoms_interaction->getName() : null),
                    WriterEntityFactory::createCell($symptomsActivity),
                    WriterEntityFactory::createCell($invitation->applicant->person_status ? $invitation->applicant->person_status->getName() : null),
                    WriterEntityFactory::createCell($invitation->applicant->created_at->setTimezone('Asia/Jakarta')->toDateTimeString()),
                    WriterEntityFactory::createCell($invitation->attended_at ? $invitation->attended_at->setTimezone('Asia/Jakarta')->toDateTimeString() : null),
                    WriterEntityFactory::createCell($invitation->result_at ? $invitation->result_at->setTimezone('Asia/Jakarta')->toDateTimeString() : null),
                    WriterEntityFactory::createCell($invitation->lab_code_sample),
                    WriterEntityFactory::createCell($invitation->lab_result_type ? $invitation->lab_result_type->getName() : null),
                ];

                $singleRow = WriterEntityFactory::createRow($cells);
                $writer->addRow($singleRow);
            }
        });

        $writer->close();
    }
}
