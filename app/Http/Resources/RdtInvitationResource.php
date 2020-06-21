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
            'event'           => new RdtEventResource($this->whenLoaded('event')),
            'test_type'       => $this->test_type,
            'lab_result_type' => $this->lab_result_type,
        ];
    }
}
