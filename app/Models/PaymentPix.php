<?php

namespace App\Models;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class PaymentPix extends Model
{
    use ModelSiteOwnerTrait;

    protected $table = 'payment_pix';

    protected $fillable = [
        'key_pix', 'participant_id', 'user_id', 'status','full_pix'
    ];

    /** @return Participant */
    public function participant(): Participant
    {
        return $this->hasOne(Participant::class, 'id', 'participant_id')->where('user_id', getSiteOwnerId())->first();
    }

}
