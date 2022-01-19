<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePanel extends FormRequest
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
            "panel_name"   => "required|string" ,
            "quantity"   => "required|numeric" ,
            "price"   => "nullable|numeric" ,
            "free_storage"   => "required|numeric" ,
            "cost_per_unit"   => "required|numeric" ,
            "frequency"   => "required|numeric" ,
            "listing_order" => "nullable|numeric",
            "office_id"   => "required|numeric" ,
            "agent_id"   => "nullable|array" ,
            "status"   => "required|numeric" ,
            "image_path"   => "nullable|numeric" ,
        ];
    }
}
