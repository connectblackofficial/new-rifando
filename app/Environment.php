<?php

namespace App;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    protected $table = 'consulting_environments';

    protected $fillable = [
        'subdomain', 'name', 'tema', 'facebook', 'instagram', 'token_api_wpp', 'key_pix', 'key_pix_public',
        'paggue_client_secret', 'paggue_client_key', 'token_asaas', 'pixel', 'verify_domain_fb', 'group_whats', 'logo',
        'footer', 'user_id', 'active'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
