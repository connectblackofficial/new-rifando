<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\PhoneRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function getCustomer(Request $request)
    {

        $rules = (new PhoneRequest())->rules();
        $action = function () use ($request) {
            $customer = Customer::siteOwner()->phoneFromRequest($request)->first();
            $response['customer'] = $customer;
            return $response;
        };

        return $this->processAjaxResponse(['phone' => $request->phone,'ddi'=>$request->ddi], $rules, $action, true);
    }
}