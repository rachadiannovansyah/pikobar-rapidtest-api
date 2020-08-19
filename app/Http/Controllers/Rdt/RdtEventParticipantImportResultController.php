<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtInvitation;
use App\Http\Controllers\Controller;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Http\Request;

class RdtEventParticipantImportResultController extends Controller
{
    public function __invoke(Request  $request)
    {
        $reader = ReaderEntityFactory::createXLSXReader();

        $reader->open($request->file->path());

        foreach ($reader->getSheetIterator() as $sheet) {

            foreach ($sheet->getRowIterator() as $key => $row) {

                $rowArray = $row->toArray();

                if ($key > 1) {

                    $registrationCode = $rowArray[0];
                    $result           = $rowArray[1];

                    $invitations = RdtInvitation::where('registration_code', $registrationCode)->first();

                    $invitations->lab_result_type = $result;
                    $invitations->save();

                }
            }
        }

    }
}
