<?php

namespace App\Services;

use App\Enums\CacheExpiresInEnum;
use App\Enums\CacheKeysEnum;
use App\Enums\FileUploadTypeEnum;
use App\Enums\PaymentGatewayEnum;
use App\Enums\ReservationTypeEnum;
use App\Events\ProductCreated;
use App\Events\ProductUpdated;
use App\Exceptions\UserErrorException;
use App\Helpers\FileUploadHelper;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Promo;
use App\Models\Raffle;
use App\Models\ShoppingSuggestion;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;

class ProductService
{
    /** @var Site $siteConfig */
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
        }
    }

    public function processAddProduct(array $productData, array $images)
    {

        $gatewayName = $productData['gateway'];
        $gameMode = $productData['modo_de_jogo'];
        $qtdNumbers = $productData['numbers'];
        $zerosQtd = strlen((string)$productData['numbers']);;
        $this->validateGateways($gatewayName);/** @noinspection PhpUnreachableStatementInspection */;
        $product = $this->create($productData);
        $product->createPromos();
        $product->createDefaultPremiums($productData);
        $this->processImages($product, $images);
        $this->saveNumbers($product, $gameMode, $qtdNumbers, $zerosQtd);
        $product->createOrUpdateDescription($productData['description']);
        event(new ProductCreated($product));
        return $product;
    }

    function processImages(Product $product, array $images)
    {
        try {
            if (count($images) == 0) {
                throw UserErrorException::emptyImage();
            }
            foreach ($images as $image) {
                if (!($image instanceof UploadedFile)) {
                    throw new \Exception("Imagem inválida.");
                }
            }

            foreach ($images as $key => $imageUpload) {
                $uploadHelper = new FileUploadHelper($imageUpload, FileUploadTypeEnum::Image);
                $imageUrl = $uploadHelper->upload();
                $productImage = $product->createProductImage($imageUrl);
                if (!isset($productImage['id'])) {
                    throw UserErrorException::uplaodError();
                }
            }

        } catch (UserErrorException $exception) {
            throw new UserErrorException($exception->getMessage());
        } catch (\Exception $exception) {
            throw UserErrorException::uplaodError();
        }

    }

    function create(array $productData): Product
    {
        return Product::create([
            'uuid' => Uuid::uuid4(),
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
            'gateway' => $productData['gateway'],
            'qtd_zeros' => strlen((string)$productData['numbers'])
        ]);
    }

    private
    function saveNumbers(Product $product, $gameMode, $qtdNumbers, $zerosQtd = null)
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

    public
    function genNumbers($qty, $zerosQtd = null)
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
        if ($request['favoritar_rifa'] && $product->favoritar == 1) {
            $product->favoritar = 0;
            $product->saveOrFail();
        }
        $limitRaffle = 10000;
        if ($product->qtd >= $limitRaffle && $request['tipo_reserva'] != ReservationTypeEnum::Automatic) {
            throw new UserErrorException("Rifas com mais de $limitRaffle números não podem ser manuais ou mescladas.");
        }
        $updated = $product->update(
            [
                'name' => $request['name'],
                'subname' => $request['subname'],
                'price' => $request['price'],
                'status' => $request['status'],
                'expiracao' => $request['expiracao'],
                'parcial' => $request['parcial'],
                'slug' => $request['slug'],
                'user_id' => getSiteOwnerId(),
                'visible' => $request['visible'],
                'favoritar' => $request['favoritar_rifa'],
                'winner' => $request['cadastrar_ganhador'],
                'draw_date' => date("Y-m-d H:i:s", strtotime($request['data_sorteio'])),
                'maximo' => $request['maximo'],
                'minimo' => $request['minimo'],
                'qtd_ranking' => $request['qtd_ranking'],
                'ganho_afiliado' => $request['ganho_afiliado'],
                'gateway' => $request['gateway'],
                'type_raffles' => $request['tipo_reserva'],
            ]
        );
        if (!$updated) {
            throw new UserErrorException("Falha ao atualizar o produto");
        }

        $productDesc = $product->descriptions()->first();
        if (isset($productDesc['id'])) {
            $productDesc->description = $request['description'];
            $productDesc->saveOrFail();
        }
        $this->updateOrCreatePromos($product, $request['numPromocao'], $request['valPromocao']);
        $this->updateAutoBuy($product, $request);
        $this->updatePremium($product, $request);

        event(new ProductUpdated($product));
        return $product;
    }


    public
    function updateOrCreatePromos(Product $product, array $numPromocao, array $valPromocao)
    {
        if ($product->promos()->count() === 0) {
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
                $promo = Promo::where('product_id', '=', $product['id'])->where('ordem', '=', $i)->first();
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


    function updateAutoBuy(Product $product, $productData)
    {
        foreach ($productData['compra'] as $key => $qtd) {
            $autoBuy = ShoppingSuggestion::whereProductId($product->id)->whereId($key)->first();
            if (isset($autoBuy['id'])) {
                $autoBuy->qtd = $qtd;
                $autoBuy->popular = false;
                $autoBuy->saveOrFail();
            }
        }
        if (!empty($productData['popularCheck']) && $productData['popularCheck'] > 0) {
            $autoBuy = ShoppingSuggestion::whereProductId($product->id)->whereId($productData['popularCheck'])->first();
            if (isset($autoBuy['id'])) {
                $autoBuy->popular = true;
                $autoBuy->saveOrFail();
            }
        }

    }


    function updatePremium(Product $product, Request $request)
    {
        foreach ($product->prizeDraws() as $premio) {
            $descPremio = $request->descPremio;
            if (isset($descPremio[$premio->ordem])) {
                $premio->descricao = $descPremio[$premio->ordem];
                $premio->saveOrFail();
            }
        }

    }

    public
    function destroyProduct($productId)
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

    public
    function destroyPhoto($photoId)
    {
        $photo = ProductImage::getByIdWithSiteCheckOrFail($photoId);
        $product = Product::getByIdWithSiteCheckOrFail($photo->product_id);
        if ($product->images()->count() >= 0) {
            throw new UserErrorException("A rifa precisa de pelo menos 1 foto, adicione outra antes de exlcuir esta.");
        }
        if (!$photo->delete()) {
            throw  UserErrorException::deleteFailed();
        }

    }


    public
    function getRandomFreeNumbers(Product $product, $qty)
    {
        $numbers = explode(",", $product['numbers']);
        $chavesAleatorias = array_rand($numbers, $qty);
        $valoresSorteados = [];
        foreach ($chavesAleatorias as $chave) {
            $valoresSorteados[] = $numbers[$chave];
        }
        return $valoresSorteados;

    }


    public
    static function processRafflePages(Product $productData)
    {
        $rifa = $productData;
        $freeNumbers = $rifa->numbers();
        $pageRows = 100;
        if ($productData->qtd <= 10000) {
            $pageRows = 100;
        } elseif ($productData->qtd <= 100000) {
            $pageRows = 1000;
        } elseif ($productData->qtd <= 1000000) {
            $pageRows = 10000;
        }
        foreach ($rifa->participants() as $participante) {
            $statusParticipante = $participante->pagos > 0 ? 'pago' : 'reservado';
            foreach ($participante->numbers() as $value) {
                $freeNumbers[] = $value . '-' . $statusParticipante . '-' . $participante->name;
            }
        }
        $expiresIn = now()->addMinutes(CacheExpiresInEnum::OneMonth);
        $pages = [];
        $pgIndex = 1;
        foreach (array_chunk($freeNumbers, $pageRows) as $numbers) {
            $pages[$pgIndex] = view("site.product.number-filter", ['numbers' => $numbers])->render();
            $cacheKey = CacheKeysEnum::getPaginationPageKey($productData->id, $pgIndex);
            Cache::store('file')->put($cacheKey, $pages[$pgIndex], $expiresIn);
            $pgIndex++;
        }
        $cacheKey = CacheKeysEnum::getQtyPaginationPageKey($productData->id);
        Cache::put($cacheKey, $pgIndex, $expiresIn);
        $cacheKey = CacheKeysEnum::getQtyQtyRowsPerPageKey($productData->id);
        Cache::put($cacheKey, $pageRows, $expiresIn);
    }


    function getPagination($rows, $qtyRows, $perPage, $page)
    {
        $paginator = new LengthAwarePaginator(
            $rows,
            $qtyRows,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        return $paginator->links('vendor.pagination.ajax_bootstrap_4')->toHtml();
    }

}