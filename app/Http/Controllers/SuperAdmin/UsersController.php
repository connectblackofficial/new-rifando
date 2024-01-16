<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Traits\CrudTrait;
use App\Http\Requests\SuperAdmin\UserStoreRequest;
use App\Http\Requests\SuperAdmin\UserUpdateRequest;

class UsersController extends Controller
{
    use CrudTrait;

    private $crudName = "users";
    private $routeGroup = "super-admin/";
    private $crudNameSingular = "user";

    public function __construct()
    {
        $this->modelClass = User::class;
        $this->removedFromAdvancedSearch = ['password', 'pix', 'afiliado'];
    }

    public function store(UserStoreRequest $request)
    {

        return $this->processStore($request->validated());
    }


    public function update(UserUpdateRequest $request, $id)
    {

        return $this->processUpdate($request->validated(), $id);
    }

    public function beforeStore($requestData)
    {
        $requestData['password'] = \Hash::make($requestData['password']);
        return $requestData;
    }

}
