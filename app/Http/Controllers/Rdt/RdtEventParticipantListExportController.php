<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Http\Controllers\Controller;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Expose-Headers: *');

        $writer = WriterEntityFactory::createXLSXWriter();

        $now = now()->format('YmdHis');
        $eventDate = $rdtEvent->start_at->format('Ymd');
        $eventNumber = str_pad($rdtEvent->id, 5, '0', STR_PAD_LEFT);
        $eventName = strtoupper(Str::slug($rdtEvent->event_name, '_'));

        $fileName = sprintf('PIKOBAR_TESMASIF_PESERTA_%s_%s_%s_%s.xlsx', $eventNumber, $eventDate, $now, $eventName);
        $writer->openToBrowser($fileName);

        $cells = [
            WriterEntityFactory::createCell('NOMOR PENDAFTARAN'),
            WriterEntityFactory::createCell('ID EVENT'),
            WriterEntityFactory::createCell('ID KLOTER'),
            WriterEntityFactory::createCell('NAMA KEGIATAN'),
            WriterEntityFactory::createCell('PENYELENGGARA KEGIATAN'),
            WriterEntityFactory::createCell('NIK'),
            WriterEntityFactory::createCell('NAMA PESERTA'),
            WriterEntityFactory::createCell('NOMOR TELEPON'),
            WriterEntityFactory::createCell('JENIS KELAMIN'),
            WriterEntityFactory::createCell('TANGGAL LAHIR'),
            WriterEntityFactory::createCell('UMUR (TAHUN)'),
            WriterEntityFactory::createCell('ALAMAT DOMISILI'),
            WriterEntityFactory::createCell('KAB/KOTA DOMISILI'),
            WriterEntityFactory::createCell('KODE KAB/KOTA DOMISILI'),
            WriterEntityFactory::createCell('KECAMATAN DOMISILI'),
            WriterEntityFactory::createCell('KODE KECAMATAN DOMISILI'),
            WriterEntityFactory::createCell('KELURAHAN/DESA DOMISILI'),
            WriterEntityFactory::createCell('KODE KELURAHAN/DESA DOMISILI'),
            WriterEntityFactory::createCell('PNS'),
            WriterEntityFactory::createCell('JENIS PEKERJAAN/PROFESI'),
            WriterEntityFactory::createCell('NAMA PEKERJAAN/PROFESI'),
            WriterEntityFactory::createCell('NAMA TEMPAT BEKERJA'),
            WriterEntityFactory::createCell('GEJALA'),
            WriterEntityFactory::createCell('CATATAN GEJALA'),
            WriterEntityFactory::createCell('RIWAYAT KONTAK'),
            WriterEntityFactory::createCell('RIWAYAT KEGIATAN'),
            WriterEntityFactory::createCell('STATUS KESEHATAN'),
            WriterEntityFactory::createCell('TANGGAL PENDAFTARAN'),
            WriterEntityFactory::createCell('KIRIM UNDANGAN'),
            WriterEntityFactory::createCell('LOKASI CHECKIN'),
            WriterEntityFactory::createCell('CHECKIN KEHADIRAN'),
            WriterEntityFactory::createCell('TANGGAL HASIL LAB'),
            WriterEntityFactory::createCell('KODE SAMPEL LAB'),
            WriterEntityFactory::createCell('HASIL TEST'),
            WriterEntityFactory::createCell('KIRIM HASIL TEST'),
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

                $symptoms = null;
                if ($invitation->applicant->symptoms) {
                    $symptoms = implode(', ', $invitation->applicant->symptoms);
                }

                $symptomsActivity = null;
                if ($invitation->applicant->symptoms_activity) {
                    $symptomsActivity= implode(', ', $invitation->applicant->symptoms_activity);
                }

                $symptomsNotes = preg_replace("/[\r\n]*/","", $invitation->applicant->symptoms_notes);

                $cells = [
                    WriterEntityFactory::createCell($invitation->applicant->registration_code),
                    WriterEntityFactory::createCell($invitation->rdt_event_id),
                    WriterEntityFactory::createCell($invitation->rdt_event_schedule_id),
                    WriterEntityFactory::createCell(strtoupper($invitation->event->event_name)),
                    WriterEntityFactory::createCell(strtoupper($invitation->event->host_name)),
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
                    WriterEntityFactory::createCell((int) $invitation->applicant->is_pns),
                    WriterEntityFactory::createCell($invitation->applicant->occupation_type),
                    WriterEntityFactory::createCell(strtoupper($invitation->applicant->occupation_name)),
                    WriterEntityFactory::createCell(strtoupper($invitation->applicant->workplace_name)),
                    WriterEntityFactory::createCell($symptoms),
                    WriterEntityFactory::createCell($symptomsNotes),
                    WriterEntityFactory::createCell($invitation->applicant->symptoms_interaction ? $invitation->applicant->symptoms_interaction->getName() : null),
                    WriterEntityFactory::createCell($symptomsActivity),
                    WriterEntityFactory::createCell($invitation->applicant->person_status ? $invitation->applicant->person_status->getName() : null),
                    WriterEntityFactory::createCell($invitation->applicant->created_at->setTimezone('Asia/Jakarta')->toDateTimeString()),
                    WriterEntityFactory::createCell($invitation->notified_at ? $invitation->notified_at->setTimezone('Asia/Jakarta')->toDateTimeString() : null),
                    WriterEntityFactory::createCell($invitation->attend_location),
                    WriterEntityFactory::createCell($invitation->attended_at ? $invitation->attended_at->setTimezone('Asia/Jakarta')->toDateTimeString() : null),
                    WriterEntityFactory::createCell($invitation->result_at ? $invitation->result_at->setTimezone('Asia/Jakarta')->toDateTimeString() : null),
                    WriterEntityFactory::createCell($invitation->lab_code_sample),
                    WriterEntityFactory::createCell($invitation->lab_result_type ? $invitation->lab_result_type->getName() : null),
                    WriterEntityFactory::createCell($invitation->notified_result_at ? $invitation->notified_result_at->setTimezone('Asia/Jakarta')->toDateTimeString() : null),
                ];

                $singleRow = WriterEntityFactory::createRow($cells);
                $writer->addRow($singleRow);
            }
        });

        $writer->close();
    }
}
