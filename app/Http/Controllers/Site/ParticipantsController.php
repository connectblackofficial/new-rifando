<?php

namespace App\Http\Controllers\Site;

use App\Exceptions\UserErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PhoneRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class ParticipantsController extends Controller
{

    public function check()
    {
        return view("site.orders.consult-order-modal");
    }

    public function processCheck(Request $request)
    {
        $rules = (new PhoneRequest())->rules();
        $action = function () use ($request) {
            $phone = getOnlyNumbers($request->phone);
            $customer = Customer::siteOwner()->whereTelephone($phone)->whereDdi($request->DDI)->first();
            if (!isset($customer['id'])) {
                throw UserErrorException::customerNotFound();
            }
            if ($customer->participants()->count() == 0) {
                throw new UserErrorException("Nenhuma reserva foi encontrada.");
            }
            return ['id' => $customer['id'], 'redirect_url' => route("site.customer.orders", ['uuid' => $customer->uuid])];
        };

        return $this->processAjaxResponse(['phone' => getOnlyNumbers($request->phone), 'ddi' => $request->DDI], $rules, $action, true);
    }
}