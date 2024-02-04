<?php

namespace App\Http\Controllers\Site;

use App\Exceptions\UserErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PhoneRequest;
use App\Models\Customer;
use App\Services\ParticipantService;
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

        return $this->processAjaxResponse(['phone' => $request->phone, 'ddi' => $request->ddi], $rules, $action, false);
    }

    public function getOrders($uuid)
    {

        $siteConfig = getSiteConfig();
        $action = function () use ($uuid, $siteConfig) {
            $customer = Customer::siteOwner()->whereUuid($uuid)->first();
            if (!isset($customer['id'])) {
                throw UserErrorException::customerNotFound();
            }
            $participantService = new ParticipantService($siteConfig);
            return $participantService->getParticipantsByCustomer($customer);
        };

        return $this->catchAndRedirect($action);
    }
}