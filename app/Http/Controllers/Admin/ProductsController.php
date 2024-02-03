<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\UserErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\SiteProductFastStoreRequest;
use App\Http\Requests\SiteProductUpdateRequest;
use App\Models\Faq;
use App\Models\Product;
use App\Models\ProductFaq;
use App\Models\ProductImage;
use App\Services\ProductService;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;

class ProductsController extends Controller
{

    use CrudTrait {
        index as pgIndex;
    }


    private $crudName = "products";
    private $routeGroup = "admin/";
    private $crudNameSingular = "product";

    public function __construct()
    {
        $this->modelClass = Product::class;
    }

    public function edit($id)
    {
        $product = Product::getByIdWithSiteCheckOrFail($id);
        Faq::createProductFaqRelationsByProduct($product);
        $faqs = ProductFaq::getProductFaqRelations($id);
        return view('admin.products.edit', ['product' => $product, 'faqs' => $faqs]);
    }

    public function index(Request $request)
    {
        $this->modelFields = ['id', 'name', 'subname', 'price', 'status'];
        return $this->pgIndex($request, "my_products");
    }

    public function activeProducts(Request $request)
    {
        $this->indexExtraWhere = ['status' => 'Ativo'];
        return $this->pgIndex($request, "products_actives");
    }


    public function store(Request $request)
    {
        $rules = (new SiteProductFastStoreRequest())->rules();
        $update = function () use ($request) {
            $productService = new ProductService(getSiteConfig());
            $productService->processAddProduct($request->all(), $request->file('images'));
            return true;
        };
        return $this->processAjaxResponseWithTrans($request->all(), $rules, $update);
    }

    public function update(Request $request, $id)
    {
        $rules = (new SiteProductUpdateRequest())->rules();
        $product = Product::getByIdWithSiteCheckOrFail($id);
        $postData = $request->all();
        $postData['slug'] = createSlug($postData['slug']);
        if (isset($product['slug']) && $product['slug'] == $postData['slug']) {
            unset($rules['slug']);
            unset($postData['slug']);
        }
        $update = function () use ($id, $request, $postData, $product) {
            $productService = new ProductService(getSiteConfig());
            $productService->update($product, $postData);
            return true;
        };


        return $this->processAjaxResponseWithTrans($postData, $rules, $update);
    }

    public function create()
    {

        return view('product.create', ['product' => []]);
    }


    public function destroyPhoto($id)
    {
        $destroyPhoto = function () use ($id) {
            $productService = new ProductService(getSiteConfig());
            $productService->destroyPhoto($id);
            return true;
        };
        return $this->processAjaxResponse(['id' => $id], [], $destroyPhoto, true);
    }


    public function showIndex($query, $title, Request $request)
    {

        $search = $request->get('search');
        if (!empty($search)) {
            $rifas = $query->search($search)->orderBy('id', 'desc');
        } else {
            $rifas = $query->orderBy('id', 'desc');

        }
        $rifas = $rifas->paginate(10);
        return view('admin.products.index', [
            'rifas' => $rifas,
            'pgTitle' => $title
        ]);
    }

    public function beforeUpdate()
    {

    }

}