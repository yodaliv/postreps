<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetPanelSetting extends FormRequest
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
            "price"   => "nullable|numeric" ,
            "free_storage"   => "required|numeric" ,
            "cost_per_unit"   => "required|numeric" ,
            "frequency"   => "required|numeric"
        ];
    }
}
