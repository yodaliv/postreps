<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePost extends FormRequest
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
            "post_name"     =>  "required | string " ,
            "quantity"      =>  "nullable | numeric" ,
            "price"         =>  "required | numeric" ,
            "renewal_fee"   =>  "required | numeric" ,
            "loss_damage"   =>  "required | numeric" ,
            "time_days"     =>  "required | numeric" ,
            "point_value"   =>  "nullable | numeric" ,
            "listing_order" =>  "nullable | numeric" ,
            "office_id"     =>  "nullable | numeric" ,
            "agent_id"      =>  "nullable | numeric" ,
            "default"       =>  "required | numeric" ,
            "status"        =>  "required | numeric" ,
            "image"         =>  "nullable | file | image"
        ];
    }
}
