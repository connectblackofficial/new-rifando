<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
    public function create()
    {

        return view('product.create', ['product' => []]);
    }
    public function update(Request $request, $id)
    {
        $rules = (new SiteProductUpdateRequest())->rules();
        $update = function () use ($id, $request) {
            try {
                \DB::beginTransaction();
                $product = Product::getByIdWithSiteCheckOrFail($id);
                $productService = new ProductService();
                $productService->update($product, $request);
                \DB::commit();
                return true;
            } catch (\Exception $e) {
                \DB::rollBack();
                return false;
            }
        };


        return $this->processAjaxResponse($request, $rules, $update);
    }

    public function deletePhoto(Request $request)
    {
        try {
            ProductImage::getByIdWithSiteCheck($request->id)->delete();
            $response['message'] = 'Imagem excluida com sucesso!';
            $response['success'] = true;

            return $response;
        } catch (\Throwable $th) {
            $response['error'] = $th->getMessage();

            return $response;
        }
    }
}