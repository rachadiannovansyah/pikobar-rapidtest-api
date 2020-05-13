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
        if ($request->user()) {
            return parent::toArray($request);
        }

        return [
            'registration_code' => $this->registration_code,
            'name'              => $this->stringToSecret($this->name),
            'status'            => $this->status,
        ];
    }

    protected function stringToSecret(string $string)
    {
        $length       = strlen($string);
        $visibleCount = (int) round($length / 3);
        $hiddenCount  = $length - ($visibleCount * 2);

        return substr($string, 0, $visibleCount).str_repeat('*', $hiddenCount).substr($string, ($visibleCount * -1),
                $visibleCount);
    }
}
