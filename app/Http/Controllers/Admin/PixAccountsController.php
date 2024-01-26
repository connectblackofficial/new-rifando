<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\SuperAdmin\UserUpdateRequest;
use App\Models\PixAccount;
use App\Rules\ValidatePixKey;
use Illuminate\Http\Request;
use App\Traits\CrudTrait;
use App\Http\Requests\Admin\PixAccountRequest;

class PixAccountsController extends Controller
{
    use CrudTrait;

    private $crudName = "pixaccounts";
    private $routeGroup = "admin/";
    private $crudNameSingular = "pixaccount";

    public function __construct()
    {
        $this->modelClass = PixAccount::class;
        $this->denyDestroy();
        $this->denyShow();

    }


    public function store(PixAccountRequest $request)
    {

        return $this->processStore($request->all());
    }

    public function update(Request $request, $id)
    {
        $rule = PixAccountRequest::class;
        return $this->processUpdate($rule, $request->all(), $id);
    }

    public function beforeUpdate($requestData, $id)
    {
        validateOrFails(['key_value' => ['required', new ValidatePixKey($requestData['key_type'])]], ['key_value' => $requestData['key_value']]);
        return $requestData;

    }

    public function beforeStore($requestData)
    {
        validateOrFails(['key_value' => ['required', new ValidatePixKey($requestData['key_type'])]], ['key_value' => $requestData['key_value']]);
        return $requestData;

    }

}
