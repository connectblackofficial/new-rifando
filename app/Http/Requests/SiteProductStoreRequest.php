<?php

namespace App\Http\Requests;

use App\Enums\PaymentGatewayEnum;
use App\Enums\ProductStatusEnum;
use App\Enums\RaffleTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class SiteProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tipo_reserva' => 'required|' . RaffleTypeEnum::getRule(),
            'name' => 'required|max:255',
            'subname' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'images' => 'required|max:3',
            'numbers' => 'required|min:1|max:7',
            'description' => env('REQUIRED_DESCRIPTION') ? 'required|max:50000' : 'nullable|max:50000',
            'minimo' => 'required|integer|min:0',
            'maximo' => 'required|integer|min:1|max:9999999999',
            'expiracao' => 'required|integer|min:0|max:9999999999',
            'gateway' => 'required|' . PaymentGatewayEnum::getRule(),
            'data_sorteio' => 'required|date',
            'visible' => 'required|in:0,1',
            'favoritar_rifa' => 'required|in:0,1',
            'numPromocao.*' => 'required|numeric|min:0',
            'valPromocao.*' => 'required|numeric|min:0|max:99',
            'descPremio.*' => 'nullable|max:255',
            'status' => 'required|' . ProductStatusEnum::getRule(),
            'qtd_ranking' => 'required|integer|max:100|min:0',
            'parcial' => 'required|in:0,1',
            'cadastrar_ganhador' => 'nullable|max:255',
            'ganho_afiliado' => 'required|numeric|min:0|max:99',

        ];
    }
}
