<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartService extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
