<?php

namespace App\Http\Requests\Rdt;

use App\Enums\Gender;
use App\Rules\NikRule;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumValueRule;

class RdtApplicantUpdateRequest extends FormRequest
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
            'name'                 => ['sometimes', 'required', 'min:3'],
            'nik'                  => ['sometimes', 'required', new NikRule()],
            'address'              => ['sometimes', 'required'],
            'city_code'            => ['sometimes', 'required', 'exists:areas,code_kemendagri'],
            'district_code'        => ['sometimes', 'required', 'exists:areas,code_kemendagri'],
            'village_code'         => ['sometimes', 'required', 'exists:areas,code_kemendagri'],
            'email'                => ['sometimes', 'required', 'email'],
            'phone_number'         => ['sometimes', 'required'],
            'gender'               => ['sometimes', 'required', new EnumValueRule(Gender::class)],
            'birth_date'           => ['sometimes', 'required', 'date'],
            'occupation_type'      => ['sometimes', 'required', 'integer'],
            'workplace_name'       => ['sometimes', 'required'],
            'symptoms'             => ['sometimes', 'required'],
            'symptoms_notes'       => ['sometimes', 'required'],
            'symptoms_interaction' => ['sometimes', 'required'],
            'symptoms_activity'    => ['sometimes', 'required'],
        ];
    }
}
