<?php

namespace Tests\Traits;

use App\Enums\PaymentGatewayEnum;
use App\Environment;
use App\Models\User;
use App\Services\ProductService;
use Faker\Provider\ar_JO\Internet;
use Illuminate\Http\UploadedFile;

trait TestTrait
{
    public function getUser()
    {
        return User::first();
    }

    public function getSiteConfig()
    {
        return Environment::where('user_id', $this->getUser()->id)->first();
    }

    public function setSiteConfig()
    {
        setSiteEnv($this->getSiteConfig());
    }

    public function getRandomProductData()
    {
        $faker = \Faker\Factory::create();
        $name = "Rifa do site " . $faker->domainName;
        return [
            'name' => $name,
            'subname' => $name,
            'price' => rand(1, 999),
            'gateway' => PaymentGatewayEnum::MP,
            'modo_de_jogo' => 'numeros',
            'numbers' => 9999,
            'description' => 'Descrição do produto teste',
            'minimo' => 1,
            'maximo' => 100,
            'expiracao' => 3600,
            'images' => $this->getRandomImages()
        ];


    }

    public function getRandomImages()
    {
        $list = [1, 2, 3];
        shuffle($list);
        $images = [];
        for ($i = 1; $i <= $list[0]; $i++) {
            $images[] = UploadedFile::fake()->image('imagem' . $i . '.jpg');
        }
        return $images;

    }

    public function getRandomProduct()
    {
        $service = new ProductService();
        $productData = $this->getRandomProductData();
        return $service->processAddProduct($productData, $productData['images']);

    }
}