<?php

namespace App\Http\Requests\Rdt;

use App\Enums\RdtEventStatus;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumValueRule;

class RdtEventUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event_name'     => ['sometimes', 'required'],
            'event_location' => ['sometimes', 'required'],
            'start_at'       => ['sometimes', 'required', 'date'],
            'end_at'         => ['sometimes', 'required', 'date'],
            'schedules'      => ['sometimes', 'required', 'array'],
            'status'         => ['required', new EnumValueRule(RdtEventStatus::class)],
        ];
    }
}
