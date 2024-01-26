<?php

namespace App\Rules;

use App\Enums\PixKeyTypeEnum;
use Illuminate\Contracts\Validation\Rule;

class ValidatePixKey implements Rule
{
    private $keyType;
    private $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($keyType)
    {
        $this->keyType = $keyType;
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
        if ($this->keyType == PixKeyTypeEnum::CPF) {
            $this->message = "CPF/CNPJ inválido.";
            return validarCPF($value);
        }
        if ($this->keyType == PixKeyTypeEnum::CNPJ) {
            $this->message = "CPF/CNPJ inválido.";
            return validarCNPJ($value);
        } elseif ($this->keyType == PixKeyTypeEnum::Email) {
            $this->message = "E-mail inválido.";
            return filter_var($value, FILTER_VALIDATE_EMAIL);
        } elseif ($this->keyType == PixKeyTypeEnum::Random) {
            $this->message = "Chave inválida.";
            return validarChaveAleatoriaPix($value);
        } else {
            $this->message = "Telefone inválido.";

            return validarTelefone($value);
        }
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
