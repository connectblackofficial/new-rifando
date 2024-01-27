<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PixAccountRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Traits\CrudTrait;
use App\Http\Requests\Admin\CustomerRequest;

class CustomersController extends Controller
{
    use CrudTrait;

    private $crudName = "customers";
    private $routeGroup = "admin/";
    private $crudNameSingular = "customer";

    public function __construct()
    {
        $this->modelClass = Customer::class;
    }


    public function store(CustomerRequest $request)
    {

        return $this->processStore($request->all());
    }


    public function update(Request $request, $id)
    {
        $rule = CustomerRequest::class;
        return $this->processUpdate($rule, $request->all(), $id);
    }


}
