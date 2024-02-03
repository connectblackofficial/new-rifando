<?php

namespace App\Models;

use App\Enums\PaymentReceiptStatusEnum;
use App\Traits\ModelAcessControllTrait;
use App\Traits\ModelSearchTrait;
use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentReceipt extends Model
{
    use ModelSearchTrait;
    use ModelAcessControllTrait;
    use ModelSiteOwnerTrait;
    use SoftDeletes;

    protected $fillable = ["id", 'user_id', 'participant_id', 'document', 'status', 'comments'];


    public static function getEnumFields()
    {
        return [
            'status' => PaymentReceiptStatusEnum::getValueAsSelectedNew()
        ];
    }

    public function participant()
    {
        return $this->hasOne(Participant::class, 'id', 'participant_id');
    }

}
