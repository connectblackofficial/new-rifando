<?php

namespace App\Services;

use Illuminate\Http\Request;

class ImageUploadService
{
    public function imageUpload(Request $request, $name)
    {
        if ($request->hasFile($name)) {

        }
        return false;

    }
}