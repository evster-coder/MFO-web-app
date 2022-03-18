<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientFormRequest extends FormRequest
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
            'client_id' => 'required',
            'passport_series' => 'required|string|regex:/[0-9]{2} [0-9]{2}/',
            'passport_number' => 'required|string|size:6',
            'passport_date_issue' => 'required|date',
            'passport_issued_by' => 'required|string',
            'passport_department_code' => 'present|string|regex:/[0-9]{3}-[0-9]{3}/|nullable',
            'actual_residence_address' => 'required|string',
            'passport_residence_address' => 'required|string',
            'number_of_dependents' => 'present|integer|min:0|nullable',
            'work_place_name' => 'required|string',
            'work_place_phone' => 'present|string|nullable',
            'mobile_phone' => 'present|string|nullable',
            'home_phone' => 'present|string|nullable',
            'constain_income' => 'required|numeric|min:0',
            'additional_income' => 'required|numeric|min:0',
            'loan_cost' => 'required|numeric|min:0',
            'loan_term' => 'required|integer|min:0',
            'interest_rate' => 'required|numeric|min:0',
            'loan_date' => 'required|date',
            'has_credits' => 'required|boolean',
            'cashier_comment' => 'present|string|nullable'
        ];
    }
}
