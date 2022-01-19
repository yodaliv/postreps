<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAccessory extends FormRequest
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
            "accessory_name"                => "required | string",
            "quantity"                      => "nullable | numeric",
            "price"                         => "required | numeric",
            "loss_damage"                   => "required | numeric",
            "point_value"                   => "nullable | numeric",
            "listing_order"                 => "nullable | numeric",
            "prompt"                        => "nullable",
            "message"                       => "nullable",
            "inventory"                     => "nullable",
            "pull_list"                     => "nullable",
            "default"                       => "required | numeric",
            "status"                        => "required | numeric",
            "accessory_image"               => "nullable | file | image"
        ];
    }
}
