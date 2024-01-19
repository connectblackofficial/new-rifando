<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteProductUpdateRequest extends FormRequest
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
        $rules = (new SiteProductStoreRequest())->rules();
        $rules['images'] = 'nullable|max:3';
        return $rules;
    }
}
