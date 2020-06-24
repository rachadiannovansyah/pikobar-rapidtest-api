<?php

namespace App\Http\Resources\RdtApplicants;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ShowRdtApplicantCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
