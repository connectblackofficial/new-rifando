<?php

namespace App\Http\Requests;

use App\Models\Customer;
use App\Models\Site;
use App\Rules\CpfValidation;
use Illuminate\Foundation\Http\FormRequest;

class CompleteCheckoutRequest extends FormRequest
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
    public function rules(Site $config, $customerId)
    {
        $rules = [
            'cart_uuid' => config("constants.cart_uuid"),
        ];
        if (is_null($customerId)) {
            $rules['name'] = 'required|min:10|max:255';
            $rules['phone'] = config("constants.phone_rule");
            if (isset($config['email_required']) && $config['email_required'] == 1) {
                $rules['email'] = "email|required|max:255";
            }
            if (isset($config['cpf_required']) && $config['cpf_required'] == 1) {
                $rules['cpf'] = ["required", new CpfValidation()];
            }
        }


        return $rules;
    }

}