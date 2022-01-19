<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreateOrder extends FormRequest
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
            "install_post_address" => "required | string",
            "install_post_property_type" => "required | numeric",
            "install_post_desired_date" => "nullable | string",
            "install_post_custom_desired_date" => "nullable | date ",
            "install_post_office" => "required | numeric",
            "install_post_agent" => "nullable",
            "install_post_select_post" => "required | numeric",
            "install_post_select_sign" => "nullable ",
            "install_post_select_accessories" => "nullable ",
            "install_post_comment" => "required|min:2",
            "install_post_signage" => "required | numeric",
            "install_post_zone_fee" => "required | numeric",
            "install_post_files" => "nullable | array",
        ];
    }

    public function messages()
    {
        return [
            'install_post_office.required' => 'Please select office.',
            'install_post_address.required' => 'Please enter property address.',
            'install_post_property_type.required' => 'Please select type of property.',
            'install_post_select_post.required' => 'Please select a post.',
            'install_post_comment.required' => 'Please enter comments.',
            'install_post_comment.min' => 'Comments must have at least 2 characters.',
        ];
    }
    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $request = $_POST;
            if ($request['install_post_desired_date'] == "custom_date") {
                if (empty(trim($request['install_post_custom_desired_date']))) {
                    $validator->errors()->add('install_post_desired_date', "Please select a date.");
                }
            }
        });
    }
}
