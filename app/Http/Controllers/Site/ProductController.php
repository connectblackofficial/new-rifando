<?php

namespace App\Http\Controllers\Site;

use App\Enums\CacheKeysEnum;
use App\Exceptions\UserErrorException;
use App\Helpers\CartManagerHelper;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

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

        $imagens = $productData->getAllImagesFromCache();


        $productDetail = $productData;


        $productDescription = $productData->descriptions()->select('description', 'video')->first();

        $productModel = $productData;
        $cart = CartManagerHelper::currentCart($productData['id']);
        $config = getSiteConfig();
        $activePromo = $productModel->promosAtivasFromCache();
        $arrayProducts = [
            'tokenAfiliado' => $tokenAfiliado,
            'imagens' => $imagens,
            'product' => $productDetail,
            'productDescription' => $productDescription ? $productDescription->description : '',
            'productDescriptionVideo' => $productDescription ? $productDescription->video : '',
            'totalNumbers' => $productModel->qtd,
            'totalDispo' => $productModel->qtdNumerosDisponiveisFromCache(),
            'totalReser' => $productModel->qtdNumerosReservadosFromCache(),
            'totalPago' => $productModel->qtdNumerosPagosFromCache(),
            'telephone' => $user->telephone,
            'type_raffles' => $productDetail->type_raffles,
            'productModel' => $productModel,
            'ranking' => $productModel->rankingFromCache(),
            'config' => $config,
            'activePromos' => $activePromo,
            'cart' => $cart
        ];
        return view('site.product.details', $arrayProducts);
    }


    public function numbers(Request $request)
    {
        $rules = [
            'product_id' => 'required|integer',
            'page' => 'required|integer|min:1|max:10000'
        ];
        $action = function () use ($request) {
            $productId = $request->product_id;
            $page = $request->page;

            Product::getByIdWithSiteCheckOrFail($productId);
            $qtyPagesKey = CacheKeysEnum::getQtyPaginationPageKey($productId);
            if (!\Cache::has($qtyPagesKey)) {
                throw  UserErrorException::pageNotFound();
            }
            $qtyPages = \Cache::get($qtyPagesKey);

            if ($page > $qtyPages) {
                throw  UserErrorException::pageNotFound();
            }
            $pageKey = CacheKeysEnum::getPaginationPageKey($productId, $page);
            if (!\Cache::store('file')->has($pageKey)) {
                throw  UserErrorException::pageNotFound();
            }

            $productService = new ProductService();
            $rows = [\Cache::store('file')->get($pageKey)];
            $links = $productService->getPagination($rows, $qtyPages, 1, $page);
            return ['html_page' =>$links. $rows[0] . $links,'current_page'=>$page,'total_pages'=>$qtyPages];
        };

        return $this->processAjaxResponse($request->all(), $rules, $action);
    }

}