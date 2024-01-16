<?php

namespace App\Exceptions;

use Exception;

class UserErrorException extends Exception
{
    public static function unauthorizedAccess()
    {
        return new self(htmlLabel("unauthorized access."));
    }
}
