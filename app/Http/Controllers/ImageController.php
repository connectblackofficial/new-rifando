<?php

namespace App\Http\Controllers;

use App\Exceptions\UserErrorException;
use App\Models\Customer;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $rules = ['image' => 'required'];
        $action = function () use ($request) {

            return ['html' => view("crud.fields.image-layout", ['image' => $request->image])->render()];
        };
        return $this->processAjaxResponse(['image' => $request->image], $rules, $action, false);

    }
}