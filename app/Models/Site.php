<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Site extends Model
{
    protected $fillable = [
        'id', 'subdomain', 'show_purchase_notifications', 'uuid', 'require_user_terms_acept', 'name', 'tema', 'facebook', 'instagram', 'token_api_wpp', 'key_pix', 'key_pix_public', 'paggue_client_secret', 'paggue_client_key', 'token_asaas', 'pixel', 'verify_domain_fb', 'group_whats', 'logo', 'footer', 'user_id', 'active', 'regulation', 'user_term', 'policy_privay', 'scripts_footer', 'scripts_top', 'hide_winners', 'enable_affiliates', 'cpf_required', 'email_required', 'show_faqs', 'email', 'whatsapp', 'description', 'og_image', 'banner',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function jsKeys()
    {
        return ['show_purchase_notifications' => $this->show_purchase_notifications];
    }

}
