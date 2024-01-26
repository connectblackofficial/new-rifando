<?php

namespace App\Services;

use App\Exceptions\UserErrorException;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Site;
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
            throw new UserErrorException("Telefone inválido.");
        }
        if (!isset($customer['id'])) {
            $customerRules = Customer::getRules($config);

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
}