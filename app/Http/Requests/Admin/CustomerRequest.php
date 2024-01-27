<?php

namespace App\Http\Requests\Admin;

use App\Rules\CpfValidation;
use App\Traits\TranslateAttrTrait;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'nome' => 'required|max:191',
            'ddi' => 'required|in:' . allowedDdiAsList(),
            'telephone' => 'required|numeric',
            'cpf' => ['nullable','max:191',new CpfValidation()],
            'email' => 'nullable|max:191'

        ];
    }
}
