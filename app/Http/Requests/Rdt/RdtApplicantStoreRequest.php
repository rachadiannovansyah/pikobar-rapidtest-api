<?php

namespace App\Http\Requests\Rdt;

use App\Enums\Gender;
use App\Enums\RdtApplicantStatus;
use App\Rules\NikRule;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumValueRule;

class RdtApplicantStoreRequest extends FormRequest
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
            'name'                 => ['required', 'min:3'],
            'nik'                  => ['required', new NikRule()],
            'address'              => 'required',
            'city_code'            => ['required', 'exists:areas,code_kemendagri'],
//            'district_code'        => ['required', 'exists:areas,code_kemendagri'],
//            'village_code'         => ['required', 'exists:areas,code_kemendagri'],
//            'email'                => ['required', 'email'],
            'phone_number'         => 'required',
            'gender'               => ['required', new EnumValueRule(Gender::class)],
//            'birth_date'           => ['required', 'date'],
//            'occupation_type'      => ['required', 'integer'],
//            'workplace_name'       => 'required',
//            'symptoms'             => 'required',
//            'symptoms_notes'       => 'required',
//            'symptoms_interaction' => 'required',
//            'symptoms_activity'    => 'required',
            'status'               => ['required', new EnumValueRule(RdtApplicantStatus::class)],
        ];
    }
}
