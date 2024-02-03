<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DatabaseView
{
    private function recreate($viewName, $sqlView)
    {
        $viewName .= "_view";
        DB::statement("DROP VIEW IF EXISTS $viewName;");
        DB::statement("CREATE VIEW $viewName as $sqlView;");
    }

    public function paymentReceiptsWithParticipants()
    {
        $viewName = "payment_receipts_with_participants";
        $sqlView = "SELECT 
    payment_receipts.*, 
    participant.customer_id,
    participant.name as customer_name, 
    participant.email as customer_email, 
    participant.cpf as customer_cpf, 
    participant.ddi as customer_ddi, 
    participant.telephone as customer_phone, 
    participant.valor, 
    products.name as product_name 
FROM 
    `payment_receipts` 
INNER JOIN 
    participant ON participant.id = payment_receipts.participant_id 
INNER JOIN 
    products ON products.id = participant.product_id 
WHERE 
    products.user_id = participant.user_id and products.deleted_at is  null";
        $this->recreate($viewName, $sqlView);
    }
}