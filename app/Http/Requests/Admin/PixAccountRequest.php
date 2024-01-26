<?php

namespace App\Http\Requests\Admin;

use App\Traits\TranslateAttrTrait;
use Illuminate\Foundation\Http\FormRequest;

class PixAccountRequest extends FormRequest
{
    use TranslateAttrTrait;

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
			'name' => 'required|max:191',
			'beneficiary_name' => 'required|max:191',
			'key_type' => 'required|in:email,cpf,cnpj,phone,random',
			'key_value' => 'required|max:191|unique:pix_accounts'
        ];
    }
}
