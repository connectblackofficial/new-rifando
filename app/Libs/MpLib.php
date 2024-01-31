<?php

namespace App\Libs;

use App\Exceptions\UserErrorException;

class MpLib
{
    private $token;

    public function __construct($token)
    {
        if (empty($token)) {
            throw new UserErrorException("Token de configuração mercado pago inválido.");
        }
        $this->token = $token;
    }

    public function getPix($resultPricePIX, $name, $email, $desc, $externalReferencee)
    {
        $idempotency_key = uniqid();
        $url = 'https://api.mercadopago.com/v1/payments';
        $resultPricePIX = str_replace(",", "", $resultPricePIX);
        $payment_data = [
            "transaction_amount" => floatval($resultPricePIX),
            "description" => $desc,
            "payment_method_id" => "pix",
            "payer" => [
                "email" => $email,
                "first_name" => $name,
                "identification" => [
                    "type" => "hash",
                    "number" => date('YmdHis')
                ]
            ],
            "notification_url" => route('api.notificaoMP'),
            "external_reference" => $externalReferencee,
        ];


        $payment_data = json_encode($payment_data);


        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payment_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json',
            'X-Idempotency-Key: ' . $idempotency_key
        ]);
        $responsex = curl_exec($ch);
        $data = json_decode($responsex, true);
        curl_close($ch);

        $codePIXID = $data['id'];
        $codePIX = $data['point_of_interaction']['transaction_data']['qr_code'];
        $qrCode = $data['point_of_interaction']['transaction_data']['qr_code_base64'];
        $response['codePIXID'] = $codePIXID;
        $response['codePIX'] = $codePIX;
        $response['qrCode'] = $qrCode;
        return $response;
    }
}