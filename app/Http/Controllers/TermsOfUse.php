<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class TermsOfUse extends Controller
{
    public function index()
    {
        return view('terms-of-use');
    }

    public function politica()
    {

        $data = [
            'user' => getSiteOwnerUser(),
            'config' => getSiteConfig()
        ];

        return view('politica', $data);
    }
}
