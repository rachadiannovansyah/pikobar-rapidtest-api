<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RdtInvitationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'applicant'             => new RdtApplicantResource($this->whenLoaded('applicant')),
            'event'                 => new RdtEventResource($this->whenLoaded('event')),
            'schedule'              => new RdtEventScheduleResource($this->whenLoaded('schedule')),
            'rdt_applicant_id'      => $this->rdt_applicant_id,
            'event_id'              => $this->rdt_event_id,
            'rdt_event_schedule_id' => $this->rdt_event_schedule_id,
            'test_type'             => $this->test_type,
            'lab_result_type'       => $this->lab_result_type,
            'result_at'             => $this->result_at,
            'created_at'            => $this->created_at,
        ];
    }
}
