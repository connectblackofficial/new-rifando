<?php

namespace App;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    protected $table = 'consulting_environments';

    protected $fillable = [
        'id', 'subdomain', 'name', 'tema', 'facebook', 'instagram', 'token_api_wpp', 'key_pix', 'key_pix_public', 'paggue_client_secret', 'paggue_client_key', 'token_asaas', 'pixel', 'verify_domain_fb', 'group_whats', 'logo', 'footer', 'user_id', 'active', 'regulation', 'user_term', 'policy_privay', 'scripts_footer', 'scripts_top', 'hide_winners', 'enable_affiliates', 'cpf_required', 'email_required', 'show_faqs', 'email', 'whatsapp', 'description', 'og_image', 'banner',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
