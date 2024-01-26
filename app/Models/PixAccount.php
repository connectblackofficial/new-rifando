<?php

namespace App\Models;

use App\Traits\ModelAcessControllTrait;
use App\Traits\ModelSearchTrait;
use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class PixAccount extends Model
{
    use ModelSearchTrait;
    use ModelAcessControllTrait;
    use ModelSiteOwnerTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pix_accounts';

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
    protected $fillable = ['name', 'beneficiary_name', 'key_type', 'key_value', 'user_id'];


    public static function getEnumFields()
    {
        return [
            'key_type' => [
                'email' => 'email',
                'cpf' => 'cpf',
                'phone' => 'phone',
                'cnpj' => 'cnpj',
                'random' => 'random'
            ]
        ];
    }

    public static function getAllAsSelect()
    {
        $rows = [];
        $accounts = self::siteOwner()->get();
        foreach ($accounts as $account) {
            $rows[$account->id] = $account->key_value;
        }

        return $rows;
    }
}
