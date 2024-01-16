<?php

namespace App\Traits;

trait TranslateAttrTrait
{

    public function attributes()
    {
        $translations = [];
        $rules = $this->rules();
        foreach ($rules as $k => $rule) {
            $translations[$k] = htmlLabel($k);
        }
        return $translations;
    }
}