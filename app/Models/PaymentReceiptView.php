<?php

namespace App\Models;

use App\Enums\PaymentReceiptStatusEnum;
use App\Traits\ModelAcessControllTrait;
use App\Traits\ModelSearchTrait;
use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentReceiptView extends PaymentReceipt
{

    protected $table = 'payment_receipts_with_participants_view';

    protected $fillable = ['id', 'user_id', 'participant_id', 'status', 'comments', 'document', 'customer_id', 'customer_name', 'customer_email', 'customer_cpf', 'customer_ddi', 'customer_phone', 'valor', 'product_name'];


}
