<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\UserErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\SiteProductFastStoreRequest;
use App\Http\Requests\SiteProductUpdateRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function edit($id)
    {
        $product = Product::getByIdWithSiteCheckOrFail($id);
        return view('product.edit', ['product' => $product]);
    }

    public function index(Request $request)
    {

        return $this->showIndex(Product::siteOwner(), 'Meus Sorteios', $request);
    }

    public function activeProducts(Request $request)
    {

        return $this->showIndex(Product::siteOwner()->where('status', 'Ativo'), 'Rifas Ativas', $request);
    }

    public function create()
    {

        return view('product.create', ['product' => []]);
    }

    public function store(Request $request)
    {

        $rules = (new SiteProductFastStoreRequest())->rules();
        $update = function () use ($request) {
            $productService = new ProductService();
            $productService->processAddProduct($request->all(),$request->file('images'));
            return true;
        };


        return $this->processAjaxResponseWithTrans($request->all(), $rules, $update);
    }

    public function update(Request $request, $id)
    {
        $rules = (new SiteProductUpdateRequest())->rules();
        $update = function () use ($id, $request) {
            $product = Product::getByIdWithSiteCheckOrFail($id);
            $productService = new ProductService();
            $productService->update($product, $request);
            return true;

        };


        return $this->processAjaxResponseWithTrans($request->all(), $rules, $update);
    }

    public function destroyPhoto($id)
    {
        $destroyPhoto = function () use ($id) {
            $productService = new ProductService();
            $productService->destroyPhoto($id);
            return true;
        };
        return $this->processAjaxResponse(['id' => $id], [], $destroyPhoto, true);
    }

    public function destroy($id)
    {
        $destroyProduct = function () use ($id) {
            $productService = new ProductService();
            $productService->destroyProduct($id);
            return true;
        };
        return $this->processAjaxResponse(['id' => $id], [], $destroyProduct, true);


    }

    public function showIndex($query, $title, Request $request)
    {

        $search = $request->get('search');
        $rifas = $query->search($search)->orderBy('id', 'desc')->get();
        return view('product.index', [
            'rifas' => $rifas,
            'pageTitle' => $title
        ]);
    }

}