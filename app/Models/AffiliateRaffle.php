<?php

namespace App\Models;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class AffiliateRaffle extends Model
{
    use ModelSiteOwnerTrait;

    protected $fillable = [
        'product_id',
        'afiliado_id',
        'token',
        'user_id'
    ];

    public static function getRowFromRequest($siteOwner, $requestData)
    {
        $where = [];
        $where['user_id'] = $siteOwner;
        if (is_array($requestData) && !empty($requestData['tokenAfiliado'])) {
            $where['token'] = $requestData['tokenAfiliado'];
        } else if (!empty($requestData->tokenAfiliado)) {
            $where['token'] = $requestData['tokenAfiliado'];
        }
        if (isset($where['token'])) {
            return self::where($where)->first();
        }
        return null;

    }
}
