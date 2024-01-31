<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Rules\UniqueOnSite;
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
        $rules['slug'] = ['required', 'max:100', 'min:6', new UniqueOnSite(getSiteConfig(), Product::class, 'slug')];
        $rules['images'] = 'nullable|max:3';
        $rules['compra.*'] = 'required|integer|min:0|max:10000';
        unset($rules['numbers']);
        return $rules;
    }
}
