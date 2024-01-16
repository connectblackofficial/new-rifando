<?php

namespace App\Http\Requests\SuperAdmin;

use App\Traits\TranslateAttrTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
			'telephone' => 'required|numeric',
			'status' => 'required|in:0,1',
			'afiliado' => 'required|in:0,1',
			'email' => 'required|email',
			'pix' => 'max:191',
			'cpf' => 'required|max:191'
		
        ];
    }
}
