<?php

namespace App\Http\Requests\SuperAdmin;

use App\Traits\TranslateAttrTrait;
use Illuminate\Foundation\Http\FormRequest;

class SiteRequest extends FormRequest
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


            'id' => 'required',
            'uuid' => 'required|max:196',
            'subdomain' => 'required|unique|alpha_num|max:196',
            'name' => 'required|max:196',
            'tema' => 'required|max:196',
            'facebook' => 'required|max:196',
            'instagram' => 'required|max:196',
            'token_api_wpp' => 'required|max:196',
            'key_pix' => 'required|max:196',
            'key_pix_public' => 'required',
            'paggue_client_secret' => 'required|max:196',
            'paggue_client_key' => 'required|max:196',
            'token_asaas' => 'required|max:196',
            'pixel' => 'required',
            'verify_domain_fb' => 'required',
            'group_whats' => 'required|max:196',
            'logo' => 'required|max:196',
            'footer' => 'required',
            'user_id' => 'required',
            'active' => 'required|in:0,1',
            'regulation' => 'required',
            'user_term' => 'required',
            'policy_privay' => 'required',
            'scripts_footer' => 'required',
            'scripts_top' => 'required',
            'hide_winners' => 'required|in:0,1',
            'enable_affiliates' => 'required|in:0,1',
            'cpf_required' => 'required|in:0,1',
            'email_required' => 'required|in:0,1',
            'show_faqs' => 'required|in:0,1',
            'email' => 'required|max:196',
            'whatsapp' => 'required|max:196',
            'description' => 'required|max:196',
            'og_image' => 'required|max:196',
            'banner' => 'required|max:196',
            'require_user_terms_acept' => 'required|in:0,1',
            'show_purchase_notifications' => 'required|in:0,1',
            'created_at' => 'required',
            'updated_at' => 'required'

        ];
    }
}
