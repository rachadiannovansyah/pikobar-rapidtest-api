<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

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
            'qrcode'            => URL::signedRoute(
                'registration.qrcode',
                ['registration_code' => $this->registration_code]
            ),
            'approved_at'       => $this->approved_at,
            'invited_at'        => $this->invited_at,
            'attended_at'       => $this->attended_at,
            'status'            => $this->status,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
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
