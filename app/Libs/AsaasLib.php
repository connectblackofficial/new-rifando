<?php

namespace App\Libs;

use App\Exceptions\UserErrorException;
use App\Models\Product;

class AsaasLib
{
    private $token;
    private $baseUrl = 'https://www.asaas.com/api/v3';
    private $client;

    public function __construct($token)
    {
        if (empty($token)) {
            throw new UserErrorException("Token de configuração asaas inválido.");
        }
        $this->token = $token;
        $this->client = new \GuzzleHttp\Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'access_token' => $this->token
            ]
        ]);

    }

    public function getOrCreateClienteAsaas($nome, $email, $cpf, $telefone)
    {
        if (!validarCPF($cpf)) {
            throw new UserErrorException("CPF inválido.");
        }
        $clientURL = $this->baseUrl . '/customers';

        $params = [
            'query' => [
                'cpfCnpj' => $cpf,
            ]
        ];
        $request = $this->client->get($clientURL, $params);

        $response = json_decode($request->getBody()->getContents());

        if (count($response->data) > 0) {
            $idCliente = $response->data[0]->id;
        } else {
            $requestClient = $this->client->post($clientURL, [
                'form_params' => [
                    "name" => $nome,
                    "email" => $email,
                    "cpfCnpj" => $cpf,
                    "mobilePhone" => $telefone
                ]
            ]);

            $responseCliente = json_decode($requestClient->getBody()->getContents());
            $idCliente = $responseCliente->id;
        }
        return $idCliente;

    }

    public function createPayment(Product $product, $idCliente, $resultPricePIX, $desc, $externalReferencee)
    {
        $pixURL = $this->baseUrl . '/payments';
        $minutosExpiracao = $product->expiracao;
        $dataDeExpiracao = date('Y-m-d H:i:s', strtotime("+" . $minutosExpiracao . " minutes"));

        $requestPIX = $this->client->post($pixURL, [
            'form_params' => [
                "externalReference" => $externalReferencee,
                "description" => $desc,
                "customer" => $idCliente,
                "billingType" => "PIX",
                'dueDate' => date('Y-m-d', strtotime($dataDeExpiracao)),
                "value" => $resultPricePIX,
            ]
        ]);

        return json_decode($requestPIX->getBody()->getContents());

    }

    function getPix(Product $product, $idCliente, $resultPricePIX, $desc, $externalReferencee)
    {
        $responsePix = $this->createPayment($product, $idCliente, $resultPricePIX, $desc, $externalReferencee);
        return $this->getQrcodeUrl($responsePix->id);
    }

    public function getQrcodeUrl($id)
    {
        $QRURL = $this->baseUrl . '/payments/' . $id . '/pixQrCode';
        $reqQR = $this->client->get($QRURL);
        $respQR = json_decode($reqQR->getBody()->getContents());
        $response['codePIXID'] = $id;
        $response['codePIX'] = $respQR->payload;
        $response['qrCode'] = $respQR->encodedImage;
        return $response;
    }
}