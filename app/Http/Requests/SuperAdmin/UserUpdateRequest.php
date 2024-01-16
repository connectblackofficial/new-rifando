<?php

namespace App\Http\Requests\SuperAdmin;

use App\Traits\TranslateAttrTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $rules = (new UserStoreRequest())->rules();
        $rules['email'] = 'required|email';
        $rules['password'] = 'nullable|min:8|max:191';

        return $rules;
    }
}
