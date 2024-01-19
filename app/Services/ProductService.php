<?php

namespace App\Services;

use App\CompraAutomatica;
use App\Enums\FileUploadTypeEnum;
use App\Enums\PaymentGatewayEnum;
use App\Environment;
use App\Exceptions\UserErrorException;
use App\Helpers\FileUploadHelper;
use App\Models\Product;
use App\Models\ProductDescription;
use App\Models\ProductImage;
use App\Models\Promocao;
use App\Models\Raffle;
use Carbon\Carbon;
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

    public function update(Product $product, $request)
    {
        if ($request->favoritar_rifa && $product->favoritar == 1) {
            $product->favoritar = 0;
            $product->saveOrFail();
        }
        $updated = $product->update(
            [
                'name' => $request->name,
                'subname' => $request->subname,
                'price' => $request->price,
                'status' => $request->status,
                'expiracao' => $request->expiracao,
                'parcial' => $request->parcial,
                'slug' => $request->slug,
                'user_id' => getSiteOwnerId(),
                'visible' => $request->visible,
                'favoritar' => $request->favoritar_rifa,
                'winner' => $request->cadastrar_ganhador,
                'draw_date' => date("Y-m-d H:i:s", strtotime($request->data_sorteio)),
                'maximo' => $request->maximo,
                'minimo' => $request->minimo,
                'qtd_ranking' => $request->qtd_ranking,
                'ganho_afiliado' => $request->ganho_afiliado,
                'gateway' => $request->gateway,
                'tipo_reserva' => $request->tipo_reserva
            ]
        );
        if (!$updated) {
            throw new UserErrorException("Falha ao atualizar o produto");
        }

        $productDesc = $product->descriptions()->first();
        if (isset($productDesc['id'])) {
            $productDesc->description = $request->description;
            $productDesc->saveOrFail();
        }
        $this->updateOrCreatePromos($product, $request->numPromocao, $request->valPromocao);
        $this->updateAutoBuy($product, $request);
        $this->updatePremium($product, $request);
    }

    public function updateOrCreatePromos(Product $product, array $numPromocao, array $valPromocao)
    {
        if ($product->promocoes()->count() === 0) {
            $product->createPromos();
        } else {
            // atualizando promocao

            for ($i = 1; $i <= 4; $i++) {
                $qtdNumeros = $numPromocao[$i];
                if ($qtdNumeros <= 0) {
                    continue;
                }
                $desconto = floatval($valPromocao[$i]);
                if ($desconto <= 0) {
                    continue;
                }
                $total = $qtdNumeros * $product->price;
                if (($total * $desconto) > 0) {
                    $valorComDesconto = $total - ($total * $desconto / 100);
                } else {
                    $valorComDesconto = $total;
                }
                $promo = Promocao::where('product_id', '=', $product['id'])->where('ordem', '=', $i)->first();
                if (isset($promo['id'])) {
                    $promo->qtdNumeros = $numPromocao[$i];
                    $promo->desconto = $desconto;
                    $promo->valor = $valorComDesconto;
                    $promo->user_id = $product['user_id'];
                    $promo->saveOrFail();
                }

            }
        }
    }

    public function updateAutoBuy(Product $product, Request $request)
    {
        foreach ($request->compra as $key => $qtd) {
            $autoBuy = CompraAutomatica::siteOwner()->whereProductId($product->id)->whereId($key)->first();
            if (isset($autoBuy['id'])) {
                $autoBuy->qtd = $qtd;
                $autoBuy->popular = false;
                $autoBuy->saveOrFail();
            }
        }
        if (!empty($request->popularCheck) && $request->popularCheck > 0) {
            $autoBuy = CompraAutomatica::siteOwner()->whereProductId($product->id)->whereId($request->popularCheck)->first();
            if (isset($autoBuy['id'])) {
                $autoBuy->popular = true;
                $autoBuy->saveOrFail();
            }
        }
        // Atualizando mais popular


    }

    public function updatePremium(Product $product, Request $request)
    {
        foreach ($product->premios() as $premio) {
            $descPremio = $request->descPremio;
            if (isset($descPremio[$premio->ordem])) {
                $premio->descricao = $descPremio[$premio->ordem];
                $premio->saveOrFail();
            }
        }

    }

    public function destroyProduct($productId)
    {

        $product_delete = Product::getByIdWithSiteCheck($productId);
        if (!isset($product_delete['id'])) {
            throw  UserErrorException::productNotFound();
        }

        $path = 'numbers/' . $product_delete->id . '.json';
        if (file_exists($path)) {
            unlink($path);
        }
        if (!$product_delete->delete()) {
            throw  UserErrorException::deleteFailed();
        }

    }
}