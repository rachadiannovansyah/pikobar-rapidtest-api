<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RdtEventResource extends JsonResource
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
            $this->mergeWhen($request->user(), [
                'id'         => $this->id,
                'event_code' => $this->event_code,
            ]),
            'event_name'     => $this->event_name,
            'event_location' => $this->event_location,
            'start_at'       => $this->start_at,
            'end_at'         => $this->end_at,
            'status'         => $this->status,
            $this->mergeWhen($this->invitations_count !== null, [
                'invitations_count' => $this->invitations_count,
            ]),
            $this->mergeWhen($this->schedules_count !== null, [
                'schedules_count' => $this->schedules_count,
            ]),
            $this->mergeWhen($request->user(), [
                'invitations' => RdtInvitationResource::collection($this->whenLoaded('invitations')),
                'schedules'   => RdtEventScheduleResource::collection($this->whenLoaded('schedules')),
                'created_at'  => $this->created_at,
                'updated_at'  => $this->updated_at,
            ]),
        ];
    }
}
