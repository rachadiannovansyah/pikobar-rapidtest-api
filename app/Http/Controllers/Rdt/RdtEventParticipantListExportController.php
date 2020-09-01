<?php

namespace App\Http\Controllers\Rdt;

use App\Entities\RdtEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class RdtEventParticipantListExportController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param RdtEvent $rdtEvent
     */
    public function __invoke(Request $request, RdtEvent $rdtEvent)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Expose-Headers: *');

        try {
            $serviceUrl = config('services.internal.participant_export');

            $response = Http::get("{$serviceUrl}/export", [
                'rdt_event_id' => $rdtEvent->id,
            ]);
        } catch (ConnectionException $exception) {
            return response()->json([
                'error'   => "CONNECTION_FAILED",
                'message' => "Cannot connect to service. Message: {$exception->getMessage()}",
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($response->failed()) {
            return response()->json([
                'error'   => "FETCH_FAILED",
                'message' => "Error fetch data from service.",
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $fileName           = $this->getFileName($rdtEvent);
        $contentDisposition = $response->header('Content-Disposition');

        return response($response->body(), 200, [
            'Content-Length'      => $response->header('Content-Length'),
            'Content-Type'        => $response->header('Content-Type'),
            'Content-Disposition' => $contentDisposition,
        ]);
    }

    protected function getFileName(RdtEvent $rdtEvent)
    {
        $now         = now()->format('YmdHis');
        $eventDate   = $rdtEvent->start_at->format('Ymd');
        $eventNumber = str_pad($rdtEvent->id, 5, '0', STR_PAD_LEFT);
        $eventName   = strtoupper(Str::slug($rdtEvent->event_name, '_'));

        return sprintf('PIKOBAR_TESMASIF_PESERTA_%s_%s_%s_%s.xlsx', $eventNumber, $eventDate, $now, $eventName);
    }
}
