<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PixAccountRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use App\Traits\CrudTrait;
use App\Http\Requests\Admin\FaqRequest;

class FaqsController extends Controller
{
    use CrudTrait;

    private $crudName = "faqs";
    private $routeGroup = "admin/";
    private $crudNameSingular = "faq";

    public function __construct()
    {
        $this->modelClass = Faq::class;

    }


    public function store(FaqRequest $request)
    {

        return $this->processStore($request->all());
    }


    public function update(Request $request, $id)
    {
        $rule = FaqRequest::class;
        return $this->processUpdate($rule, $request->all(), $id);
    }


}
