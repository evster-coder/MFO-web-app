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
            'passportSeries' => 'required|string|regex:/[0-9]{2} [0-9]{2}/',
            'passportNumber' => 'required|string|size:6',
            'passportDateIssue' => 'required|date',
            'passportIssuedBy' => 'required|string',
            'passportDepartamentCode' => 'present|string|regex:/[0-9]{3}-[0-9]{3}/|nullable',
            'snils' => 'required|string|size:11',
            'actualResidenceAddress' => 'required|string',
            'passportResidenceAddress' => 'required|string',
            'numberOfDependents' => 'present|integer|min:0|nullable',
            'workPlaceName' => 'required|string',
            'workPlacePhone' => 'present|string|nullable',
            'mobilePhone' => 'present|string|nullable',
            'homePhone' => 'present|string|nullable',
            'constainIncome' => 'required|numeric|min:0',
            'additionalIncome' => 'required|numeric|min:0',
            'loanCost' => 'required|numeric|min:0',
            'loanTerm' => 'required|integer|min:0',
            'interestRate' => 'required|numeric|min:0',
            'loanDate' => 'required|date',
            'hasCredits' => 'required|boolean',
            'cashierComment' => 'present|string|nullable'
        ];
    }
}
