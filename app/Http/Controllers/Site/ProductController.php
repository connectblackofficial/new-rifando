<?php

namespace App\Http\Controllers\Site;

use App\Enums\CacheKeysEnum;
use App\Exceptions\UserErrorException;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\PrizeDraw;
use App\Models\Product;
use App\Services\CartService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function getFreeNumbers(Request $request, $id)
    {

        $qty = 1;
        if (is_integer($request->qty) && $request->qty > 0 && $request->qty <= 10000) {
            $qty = $request->qty;
        }

        $action = function () use ($id, $qty) {
            $product = Product::getByIdWithSiteCheck($id);
            $service = new ProductService();
            return $service->getRandomFreeNumbers($product, $qty);
        };
        return $this->processAjaxResponse(['id' => $id], [], $action, true);
    }

    public function details($slug, $tokenAfiliado = null)
    {
        $productData = Product::siteOwner()->whereSlug($slug)->first();
        if (!isset($productData['id'])) {
            abort(404);
        }
        $user = getSiteOwnerUser();

        $productResume = Product::getResumeCache($productData['id']);
        $productDetail = $productData;
        $imagens = $productResume['images'];

        $productDescription = $productResume['description'];

        $productModel = $productData;
        $cart = CartService::currentCart($productData['id']);
        $config = getSiteConfig();
        $activePromo = $productModel->promosAtivasFromCache();
        $arrayProducts = [
            'tokenAfiliado' => $tokenAfiliado,
            'imagens' => $imagens,
            'product' => $productDetail,
            'productDescription' => $productDescription ? $productDescription->description : '',
            'productDescriptionVideo' => $productDescription ? $productDescription->video : '',
            'totalNumbers' => $productModel->qtd,
            'totalDispo' => $productResume['free'],
            'totalReser' => $productResume['reserved'],
            'totalPago' => $productResume['paid'],
            'telephone' => $user->telephone,
            'type_raffles' => $productDetail->type_raffles,
            'productModel' => $productModel,
            'ranking' => $productModel->ranking(),
            'config' => $config,
            'activePromos' => $activePromo,
            'cart' => $cart,
            'productResume' => $productResume,
            'productFromCache' => $productResume['product']
        ];

        return view('site.product.details', $arrayProducts);
    }


    public function numbers(Request $request)
    {
        $rules = [
            'product_uuid' => config("constants.product_uuid_rule"),
            'page' => 'required|integer|min:1|max:10000'
        ];

        $action = function () use ($request) {
            $prouctUuid = $request->product_uuid;
            $page = $request->page;
            $postData = $request->all();
            $productData = Product::getByUuidWithSiteCheckOrFail($prouctUuid);
            if (isset($postData['cart_uuid'])) {
                $cart = Cart::whereProductId($productData->id)->whereUuid($postData['cart_uuid'])->first();
            }
            $currentCart = CartService::currentCart($productData->id);
            $productId = $productData->id;

            $qtyPagesKey = CacheKeysEnum::getQtyPaginationPageKey($productId);

            if (!\Cache::has($qtyPagesKey)) {
                throw  UserErrorException::pageNotFound(__LINE__);
            }
            $qtyPages = \Cache::get($qtyPagesKey);

            if ($page > $qtyPages) {
                throw  UserErrorException::pageNotFound(__LINE__);
            }
            $pageKey = CacheKeysEnum::getPaginationPageKey($productId, $page);
            if (!\Cache::store('file')->has($pageKey)) {
                throw  UserErrorException::pageNotFound(__LINE__);
            }

            $productService = new ProductService(getSiteConfig(), $currentCart);
            $rows = [\Cache::store('file')->get($pageKey)];
            $links = $productService->getPagination($rows, $qtyPages, 1, $page);
            $response = ['html_page' => $links . $rows[0] . $links, 'current_page' => $page, 'total_pages' => $qtyPages];
            if (isset($cart['id'])) {
                $response['numbers_on_cart'] = $cart->getNumbersAsArray();
            } else {
                $response['numbers_on_cart'] = [];
            }
            return $response;
        };

        return $this->processAjaxResponse($request->all(), $rules, $action);
    }

    public function index()
    {
        $ganhadores = PrizeDraw::where('descricao', '!=', null)->where('ganhador', '!=', '')->get();

        $products = Product::where('visible', '=', 1)->orderBy('id', 'desc')->get();

        $winners = Product::winners()->get();

        $config = getSiteConfig();

        return view('sorteios', [
            'products' => $products,
            'winners' => $winners,
            'ganhadores' => $ganhadores,
            'user' => getSiteOwnerUser(),
            'productModel' => Product::getByIdWithSiteCheck(4),
            'config' => $config
        ]);
    }
}