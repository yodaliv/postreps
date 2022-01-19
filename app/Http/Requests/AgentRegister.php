<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgentRegister extends FormRequest
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
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'email' => 'required|email|unique:users',
            'phone' => 'string|required',
            'city' => 'string|required',
            'state' => 'string|required',
            'zipcode' => 'string|required',
            're_license' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'agent_office' => 'nullable|numeric',
        ];
    }
}
