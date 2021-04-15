<?php

namespace App\Exports;

use App\Entities\RdtInvitation;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParticipantListExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct($event)
    {
        $this->event = $event;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = DB::table('rdt_invitations')
        ->select(
            DB::raw('@number:=@number+1 as number'),
            'rdt_applicants.name',
            'rdt_applicants.birth_date',
            'rdt_applicants.workplace_name',
        )
        ->leftJoin('rdt_applicants', 'rdt_applicants.id', 'rdt_invitations.rdt_applicant_id')
        ->where('rdt_invitations.rdt_event_id', $this->event->id)
        ->get();

        return $data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pasien',
            'Tanggal Lahir / Usia',
            'Jenis Spesimen',
            'Institusi Pengirim Spesimen',
            'Nomor Spesimen (Label Barcode)',
        ];
    }

    public function map($event):array
    {
        return [
            $event->name,
            $event->birth_date,
            $event->workplace_name,
        ];
    }
}
