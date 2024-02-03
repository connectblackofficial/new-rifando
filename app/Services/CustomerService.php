<?php

namespace App\Services;

use App\Exceptions\UserErrorException;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Participant;
use App\Models\Product;
use App\Models\Site;
use App\Rules\ValidPhone;
use Ramsey\Uuid\Uuid;

class CustomerService
{
    private $siteConfig;

    public function __construct(Site $siteConfig)
    {
        $this->siteConfig = $siteConfig;
    }

    public function createOrGet(array $requestData): Customer
    {
        $config = $this->siteConfig;
        $customer = Customer::phoneFromRequest($requestData)->where("user_id", $config->user_id)->first();
        if (empty($requestData['phone']) || empty($requestData['ddi'])) {
            throw  UserErrorException::invalidPhone();
        }

        if (!isset($customer['id'])) {
            $customerRules = Customer::getRules($config);
            $customerRules['phone'] = ['required', new ValidPhone($requestData['ddi'])];
            $validatedData = validateOrFails($customerRules, $requestData);
            $fields = ['nome', 'email', 'cpf'];
            $customer = new Customer();
            foreach ($fields as $field) {
                if (isset($validatedData[$field])) {
                    $customer->{$field} = $validatedData[$field];
                }
            }
            $customer->uuid = Uuid::uuid4();
            $customer->ddi = $requestData['ddi'];
            $customer->telephone = removePhoneMask($requestData['phone']);
            $customer->user_id = $config->user_id;
            $customer->saveOrFail();
            return $customer;
        }
        return $customer;

    }

    public function getOrders($customerUuid)
    {
        $customer = Customer::whereUserId($this->siteConfig->user_id)->whereUuid($customerUuid)->first();
        if (!isset($customer['id'])) {
            throw UserErrorException::customerNotFound();
        }
        $rifas = [];
        $participants = $customer->participants()->get();
        foreach ($participants as $reserva) {
            $rifa = $reserva->product()->select("id", "name")->first();
            $rifas[$rifa->id] = $rifa->name;
        }
        $data = [
            'reservas' => $participants,
            'rifas' => $rifas,
            'config' => $this->siteConfig
        ];

        return view('site.orders.customer-orders', $data);

    }
}