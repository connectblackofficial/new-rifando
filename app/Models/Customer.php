<?php

namespace App\Models;

use App\Rules\CpfValidation;
use App\Traits\ModelAcessControllTrait;
use App\Traits\ModelSearchTrait;
use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Customer extends Model
{
    use ModelSiteOwnerTrait;
    use ModelSearchTrait;
    use ModelAcessControllTrait;

    protected $fillable = [
        'id',
        'nome',
        'telephone',
        'email',
        'cpf',
        'user_id',
        'uuid',
        'ddi'
    ];

    public function scopePhoneFromRequest($query, $requestData)
    {
        if (is_array($requestData)) {
            $where = ['ddi' => $requestData['ddi'], 'telephone' => removePhoneMask($requestData['phone'])];
        } else {
            $where = ['ddi' => $requestData->ddi, 'telephone' => removePhoneMask($requestData->phone)];
        }
        return $query->where($where);
    }

    public static function getRules($config)
    {
        $rules['nome'] = 'required|min:10|max:255';
        if (isset($config['email_required']) && $config['email_required'] == 1) {
            $rules['email'] = "email|required|max:255";
        }
        if (isset($config['cpf_required']) && $config['cpf_required'] == 1) {
            $rules['cpf'] = ["required", new CpfValidation()];
        }
        return $rules;

    }


    public static function getEnumFields()
    {
        return [
            'ddi' => getCountriesDdi()
        ];
    }
}
