<?php

namespace App\Rules;

use App\Models\Country;
use Illuminate\Contracts\Validation\Rule;
use libphonenumber\PhoneNumberUtil;

class ValidPhone implements Rule
{
    private $ddi;
    private $message;

    public function __construct($ddi)
    {
        $this->ddi = $ddi;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (Country::whereDialCode($this->ddi)->count() == 0) {
            $this->message = "DDI inválido.";
            return false;
        }
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        $phoneNumberObject = $phoneNumberUtil->parse($this->ddi . getOnlyNumbers($value), null);
        $this->message = "Número de celular inválido.";
        return $phoneNumberUtil->isValidNumber($phoneNumberObject);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
