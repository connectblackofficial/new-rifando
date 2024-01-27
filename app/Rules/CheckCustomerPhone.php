<?php

namespace App\Rules;

use App\Models\Customer;
use App\Models\Site;
use Illuminate\Contracts\Validation\Rule;

class CheckCustomerPhone implements Rule
{
    private $site;
    private $ddi;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Site $site, $ddi)
    {
        $this->site = $site;
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

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
