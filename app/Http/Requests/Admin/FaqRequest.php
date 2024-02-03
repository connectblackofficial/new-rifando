<?php

namespace App\Http\Requests\Admin;

use App\Traits\TranslateAttrTrait;
use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
            'title' => 'required|max:191',
            'description' => 'required|max:999|min:3',
            'order' => 'required|integer|min:0|max:127',
            'show' => 'required|in:0,1'
        ];
    }
}
