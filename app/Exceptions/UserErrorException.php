<?php

namespace App\Exceptions;

use Exception;

class UserErrorException extends Exception
{
    public static function unauthorizedAccess()
    {
        return new self(htmlLabel("unauthorized access."));
    }

    public static function emptyImage()
    {
        return new self(htmlLabel("please, insert a image."));
    }

    public static function paymentNotFound()
    {
        return new self("Pagamento não encontrado;");

    }

    public static function pixNotFound()
    {
        return new self("PIX não encontrado;");

    }

    public static function uplaodError()
    {
        return new self("Ocorreu um erro durante o upload.");

    }

    public static function productNotFound()
    {
        return new self("Produto não encontrado;");

    }

    public static function deleteFailed()
    {
        return new self("Ocorreu um erro ao deletar o registro.");

    }

    public static function pageNotFound()
    {
        return new self("Página não encontrada.");

    }
}
