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
    public static function pixError()
    {
        return new self("Ocorreu um erro ao gerar o código pix. Tente novamente.");

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

    public static function createFailed()
    {
        return new self("Ocorreu um erro ao adicionar  um registro.");

    }

    public static function pageNotFound()
    {
        return new self("Página não encontrada.");

    }

    public static function customerNotFound()
    {
        return new self("Cliente não encontrado.");

    }

    public static function invalidPhone()
    {
        return new self("Telefone inválido.");

    }

    public static function cartNotFound()
    {
        return new self("Checkout inválido.");

    }

}
