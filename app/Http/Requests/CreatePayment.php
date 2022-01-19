<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePayment extends FormRequest
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
        if($_POST['payment_type'] == 'use_card'){
            return [
                'card_profile' => "required|integer",
                'order_id' => 'integer|required',
                'payment_type' => 'string|required',
            ];
        }
        return [
            'card_number' => 'string|required',
            'card_code' => 'integer|required',
            'expire_date_month' => 'required',
            'expire_date_year' => 'required',
            'order_id' => 'integer|required',
        ];
    }


}
