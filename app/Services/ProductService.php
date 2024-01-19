<?php

namespace App\Services;

use App\Enums\FileUploadTypeEnum;
use App\Enums\PaymentGatewayEnum;
use App\Environment;
use App\Exceptions\UserErrorException;
use App\Helpers\FileUploadHelper;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Raffle;
use Illuminate\Http\Request;

class ProductService
{
    /** @var Environment $siteConfig */
    private $siteConfig;


    public function __construct()
    {
        $this->siteConfig = getSiteConfig();
    }

    public function validateGateways($gatewayName)
    {
        $siteConfig = $this->siteConfig;
        if ($gatewayName == PaymentGatewayEnum::MP && empty($siteConfig->key_pix)) {
            $msg = 'Para utilizar o gateway de pagamento Mercado Pago é necessário informar o token na sessão "Meu Perfil".';
            throw  new UserErrorException($msg);
        } elseif ($gatewayName == PaymentGatewayEnum::ASAAS && empty($siteConfig->token_asaas)) {
            $msg = 'Para utilizar o gateway de pagamento ASAAS é necessário informar o token na sessão "Meu Perfil".';
            throw  new UserErrorException($msg);
        } elseif ($gatewayName == PaymentGatewayEnum::PAGGUE && (empty($siteConfig->paggue_client_key) || empty($siteConfig->paggue_client_secret))) {
            $msg = 'Para utilizar o gateway de pagamento Paggue é necessário informar o CLIENT KEY e CLIENT SECRET na sessão "Meu Perfil".';
            throw  new UserErrorException($msg);
        } else {
            throw  new UserErrorException("Gateway de pagamento inválido.");
        }
    }

    public function processAddProduct(Request $request)
    {
        $requestData = $request->all();
        $gatewayName = $request->gateway;
        $gameMode = $request->modo_de_jogo;
        $qtdNumbers = $request->numbers;
        $zerosQtd = $request->qtd_zeros;
        $this->validateGateways($gatewayName);/** @noinspection PhpUnreachableStatementInspection */;
        $product = $this->create($requestData);
        $product->createPromos();
        $product->createDefaultPremiums($requestData);
        $this->processImages($product, $request);

        $this->saveNumbers($product, $gameMode, $qtdNumbers, $zerosQtd);
        $product->createOrUpdateDescription($request->description);
    }

    function processImages(Product $product, $request, $fileName = 'images')
    {
        if ($request->hasFile($fileName)) {
            $files = $request->file($fileName);
            try {
                \DB::beginTransaction();
                foreach ($files as $key => $imageUpload) {
                    $uploadHelper = new FileUploadHelper($imageUpload, FileUploadTypeEnum::Image);
                    $imageUrl = $uploadHelper->upload();
                    $productImage = $product->createProductImage($imageUrl);
                    if (!isset($productImage['id'])) {
                        throw UserErrorException::uplaodError();
                    }
                }
                \DB::commit();
            } catch (\Exception $exception) {
                \DB::rollBack();
                throw UserErrorException::uplaodError();
            }

        }
    }

    function create(array $productData): Product
    {

        return Product::create([
            'name' => $productData['name'],
            'subname' => $productData['subname'],
            'price' => $productData['price'],
            'qtd' => $productData['numbers'],
            'expiracao' => $productData['expiracao'],
            'processado' => true,
            'status' => 'Ativo',
            'type_raffles' => 'automatico',
            'slug' => createSlug($productData['name']),
            'user_id' => getSiteOwnerId(),
            'visible' => 0,
            'minimo' => $productData['minimo'],
            'maximo' => $productData['maximo'],
            'modo_de_jogo' => $productData['modo_de_jogo'],
            'gateway' => $productData['gateway']
        ]);
    }

    private function saveNumbers(Product $product, $gameMode, $qtdNumbers, $zerosQtd = null)
    {
        if (str_starts_with($gameMode, 'fazendinha')) {
            if ($gameMode == 'fazendinha-completa') {
                for ($i = 1; $i <= 25; $i++) {
                    $number = 'g' . $i;
                    Raffle::simpleCreate($number, $product->id, $product->user_id);
                }
            } else if ($gameMode == 'fazendinha-meio') {
                for ($i = 1; $i <= 25; $i++) {
                    $number = 'g' . $i . '-le';
                    Raffle::simpleCreate($number, $product->id, $product->user_id);
                    $number = 'g' . $i . '-ld';
                    Raffle::simpleCreate($number, $product->id, $product->user_id);
                }
            }
        } else {
            $product->numbers = $this->genNumbers($qtdNumbers, $zerosQtd);
            $product->saveOrFail();
        }

    }

    public function genNumbers($qty, $zerosQtd = null)
    {
        $qtdNumbers = $qty;
        $arr = [];
        $qtdZeros = strlen((string)$qtdNumbers);
        if ($zerosQtd != null) {
            $qtdZeros = $zerosQtd + 1;
        }
        for ($x = 0; $x < $qtdNumbers; $x++) {
            $nbr = str_pad($x, $qtdZeros, '0', STR_PAD_LEFT);
            array_push($arr, $nbr);
        }
        return implode(",", $arr);
    }
    public function update()
    {

    }

}