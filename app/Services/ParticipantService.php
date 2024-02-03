<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Site;
use Illuminate\Database\Eloquent\Collection;

class ParticipantService
{
    private $siteConfig;

    public function __construct(Site $siteConfig)
    {
        $this->siteConfig = $siteConfig;
    }

    public function getParticipantsByCustomer(Customer $customer)
    {

        $participants = $customer->participants()->get();


        return $this->processParticipantsPage($participants);

    }

    private function processParticipantsPage(Collection $participants)
    {
        $products = [];
        foreach ($participants as $reserva) {
            $product = $reserva->product()->first();;
            $products[$reserva->id] = $product;
            $rifas[$product->id] = $product->name;
        }
        $data = [
            'reservas' => $participants,
            'rifas' => $rifas,
            'config' => $this->siteConfig,
            'products' => $products
        ];

        return view('site.orders.customer-orders', $data);
    }

}