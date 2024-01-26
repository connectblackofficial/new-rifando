<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Traits\CrudTrait;
use App\Http\Requests\SuperAdmin\UserStoreRequest;
use App\Http\Requests\SuperAdmin\UserUpdateRequest;
use Illuminate\Http\Request;

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


    public function update(Request $request, $id)
    {
        $rule = UserUpdateRequest::class;
        return $this->processUpdate($rule, $request->all(), $id);
    }

    public function beforeStore($requestData)
    {
        $requestData['password'] = \Hash::make($requestData['password']);
        return $requestData;
    }

}
