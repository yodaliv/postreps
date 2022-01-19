<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOffice extends FormRequest
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
            'name' => 'string|required',
            'email' => 'required|email|unique:users',
            'phone' => 'string|required',
            'city' => 'string|required',
            'primary_contact' => 'string|nullable',
            'state' => 'string|required',
            'zipcode' => 'string|required',
            'website' => 'string|nullable',
            'inactive' => 'required|numeric',
            'private' => 'required|numeric',
            'region_id' => 'required|numeric',
            'logo_image' => 'nullable|image',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
        ];
    }
}
