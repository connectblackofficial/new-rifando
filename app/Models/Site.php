<?php

namespace App\Models;

use App\Traits\ModelAcessControllTrait;
use App\Traits\ModelSearchTrait;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use ModelSearchTrait;
    use ModelAcessControllTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sites';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uuid', 'subdomain', 'name', 'tema', 'facebook', 'instagram', 'token_api_wpp', 'key_pix', 'key_pix_public', 'paggue_client_secret', 'paggue_client_key', 'token_asaas', 'pixel', 'verify_domain_fb', 'group_whats', 'logo', 'footer', 'user_id', 'active', 'regulation', 'user_term', 'policy_privay', 'scripts_footer', 'scripts_top', 'hide_winners', 'enable_affiliates', 'cpf_required', 'email_required', 'show_faqs', 'email', 'whatsapp', 'description', 'og_image', 'banner', 'require_user_terms_acept', 'show_purchase_notifications', 'created_at', 'updated_at'];


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function jsKeys()
    {
        return ['show_purchase_notifications' => $this->show_purchase_notifications];
    }
    public static function getEnumFields()
    {
        return [
            'active' => [
                '0' => 'no',
                '1' => 'yes'
            ],
            'hide_winners' => [
                '0' => 'no',
                '1' => 'yes'
            ],
            'enable_affiliates' => [
                '0' => 'no',
                '1' => 'yes'
            ],
            'cpf_required' => [
                '0' => 'no',
                '1' => 'yes'
            ],
            'email_required' => [
                '0' => 'no',
                '1' => 'yes'
            ],
            'show_faqs' => [
                '0' => 'no',
                '1' => 'yes'
            ],
            'require_user_terms_acept' => [
                '0' => 'no',
                '1' => 'yes'
            ],
            'show_purchase_notifications' => [
                '0' => 'no',
                '1' => 'yes'
            ]
        ];
    }
}
