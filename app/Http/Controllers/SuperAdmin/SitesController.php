<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Requests\Admin\PixAccountRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\PaymentReceiptView;
use App\Models\Site;
use App\Models\SiteWithUsers;
use Illuminate\Http\Request;
use App\Traits\CrudTrait;
use App\Http\Requests\SuperAdmin\SiteRequest;

class SitesController extends Controller
{
    use CrudTrait {
        index as pgIndex;
        edit as pgEdit;
    }

    private $crudName = "sites";
    private $routeGroup = "super-admin/";
    private $crudNameSingular = "site";

    public function __construct()
    {
        $this->modelClass = Site::class;
        $this->denyCreate();
        $this->denyShow();
    }

    public function index(Request $request)
    {
        $this->modelClass = SiteWithUsers::class;
        return $this->pgIndex($request);
    }

    public function store(SiteRequest $request)
    {
        return $this->processStore($request->all());
    }

    public function update(Request $request, $id)
    {
        $rule = SiteRequest::class;
        return $this->processUpdate($rule, $request->all(), $id);
    }


}
