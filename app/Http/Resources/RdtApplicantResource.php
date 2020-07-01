<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RdtApplicantResource extends JsonResource
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
                'id' => $this->id,
            ]),
            'rdt_applicant_id'  => $this->rdt_applicant_id,
            'registration_code' => $this->registration_code,
            'name'              => $this->name,
            'qrcode'            => $this->qrCodeUrl,
            'approved_at'       => $this->approved_at,
            'invitations'       => RdtInvitationResource::collection($this->whenLoaded('invitations')),
            'status'            => $this->status,
            $this->mergeWhen($request->user(), [
                'gender'               => $this->gender,
                'birth_date'           => optional($this->birth_date)->toDateString(),
                'age'                  => optional($this->birth_date)->age,
                'city'                 => new AreaResource($this->whenLoaded('city')),
                'district'             => new AreaResource($this->whenLoaded('district')),
                'village'              => new AreaResource($this->whenLoaded('village')),
                'symptoms'             => $this->symptoms,
                'symptoms_interaction' => $this->symptoms_interaction,
                'symptoms_notes'       => $this->symptoms_notes,
                'symptoms_activity'    => $this->symptoms_activity,
                'person_status'        => $this->person_status,
                'created_at'           => $this->created_at,
                'updated_at'           => $this->updated_at,
            ]),
        ];
    }

    protected function stringToSecret(string $string)
    {
        if (strlen($string) <= 1) {
            return $string;
        }

        $explodeWords = explode(' ', $string);

        $nameMasking = '';
        foreach ($explodeWords as $key => $word) {
            if (strlen($word) <= 2) {
                $nameMasking .= $word.' ';
            } else {
                $nameMasking .= substr($word, 0, 3).str_repeat('*', strlen($word) - 3).' ';
            }
        }

        return rtrim($nameMasking);
    }
}
