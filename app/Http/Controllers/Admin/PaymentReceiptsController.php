<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\PaymentReceiptRequest;
use App\Models\PaymentReceipt;
use App\Models\PaymentReceiptView;
use App\Services\PaymentReceiptService;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;

class PaymentReceiptsController extends Controller
{
    use CrudTrait {
        index as pgIndex;
        edit as pgEdit;
    }


    private $crudName = "payment-receipts";
    private $routeGroup = "admin/";
    private $crudNameSingular = "payment-receipt";

    public function __construct()
    {
        $this->modelClass = PaymentReceipt::class;
        $this->denyCreate();
        $this->denyShow();
    }

    public function index(Request $request)
    {

        $this->modelClass = PaymentReceiptView::class;
        return $this->pgIndex($request);
    }

    public function store(PaymentReceiptRequest $request)
    {

        return $this->processStore($request->all());
    }


    public function update(Request $request, $id)
    {
        $siteConfig = getSiteConfig();
        $rules = (new PaymentReceiptRequest())->rules();
        $action = function () use ($request, $id, $siteConfig) {
            $service = new PaymentReceiptService($siteConfig);
            $paymentReceipt = PaymentReceipt::where("user_id", $siteConfig->user_id)->where("id", $id)->firstOrFail();
            $service->update($paymentReceipt, $request->all());
            return true;
        };
        return $this->processAjaxResponse($request->all(), $rules, $action, false, true);
    }

    public function edit($id)
    {
        $this->modelClass = PaymentReceiptView::class;
        return $this->pgEdit($id);
    }

}
