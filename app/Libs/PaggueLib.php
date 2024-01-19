<?php

namespace App\Libs;

use Exception;

class PaggueLib
{
    private const AUTH_URL = 'https://ms.paggue.io/payments/api/auth/login';
    private const PAYMENT_URL = 'https://ms.paggue.io/payments/api/billing_order';

    private $clientKey;
    private $clientSecret;

    public function __construct($clientKey, $clientSecret)
    {
        $this->clientKey = $clientKey;
        $this->clientSecret = $clientSecret;
    }

    public function getPix($name, $resultPricePIX, $desc, $externalId)
    {
        include(app_path() . '/ThirdParty/phpqrcode/qrlib.php');

        try {
            $authResponse = $this->authenticate();

            $paymentResponse = $this->createPaymentOrder($name, $resultPricePIX, $desc, $externalId, $authResponse);

            $qrCode = $this->generateQrCode($paymentResponse->payment);

            $response = [
                'codePIXID' => $paymentResponse->hash,
                'codePIX' => $paymentResponse->payment,
                'qrCode' => $qrCode
            ];

            file_put_contents('create_paggue.json', json_encode($response));

            return $response;
        } catch (Exception $e) {
            // Tratamento de erro adequado
            return ['error' => $e->getMessage()];
        }
    }

    private function authenticate()
    {
        $payload = [
            "client_key" => $this->clientKey,
            "client_secret" => $this->clientSecret
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => self::AUTH_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($payload),
        ]);

        $response = curl_exec($curl);
        if (!$response) {
            throw new Exception('Falha na autenticação: ' . curl_error($curl));
        }

        curl_close($curl);

        return json_decode($response);
    }

    private function createPaymentOrder($name, $resultPricePIX, $desc, $externalId, $authResponse)
    {
        $payload = [
            "payer_name" => $name,
            "amount" => $resultPricePIX * 100,
            "external_id" => $externalId,
            "description" => $desc,
        ];

        $headers = [
            'Accept: application/json',
            'Authorization: Bearer ' . $authResponse->access_token,
            'Content-Type: application/json',
            'X-Company-ID: ' . $authResponse->user->companies[0]->id
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => self::PAYMENT_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => $headers
        ]);

        $response = curl_exec($curl);
        if (!$response) {
            throw new Exception('Falha ao criar pedido de pagamento: ' . curl_error($curl));
        }

        curl_close($curl);

        return json_decode($response);
    }

    private function generateQrCode($paymentString)
    {
        ob_start();
        \QRCode::png($paymentString);
        $imageString = base64_encode(ob_get_contents());
        ob_end_clean();

        return $imageString;
    }
}
