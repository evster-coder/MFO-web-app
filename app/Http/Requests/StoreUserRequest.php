<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'username' => 'required|string|unique:users|min:4|max:20',
            'password' => 'required|string|min:4|max:50',
            'full_name' => 'required|string',
            'org_unit_id' => 'required',
            'roles' => 'required',
        ];
    }
}
