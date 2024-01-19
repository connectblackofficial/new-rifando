<?php

namespace App\Http\Requests;

use App\Enums\PaymentGatewayEnum;
use App\Enums\ReservationTypeEnum;
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
            'tipo_reserva' => 'required|' . ReservationTypeEnum::getRule(),
            'name' => 'required|max:255',
            'price' => 'required|numeric|min:0|max:6',
            'images' => 'required|max:3',
            'numbers' => 'required|min:1|max:7',
            'description' => env('REQUIRED_DESCRIPTION') ? 'required|max:50000' : '',
            'minimo' => 'required|integer:0',
            'maximo' => 'required|integer|min:1|max:9999999999',
            'expiracao' => 'required|min:0|max:9999999999',
            'gateway' => 'required|' . PaymentGatewayEnum::getRule(),
            'data_sorteio' => 'required|date',
            'previsao_sorteio' => 'nullable|date',
            'visible' => 'required|in:0,1',
            'qtd_ranking' => 'required|integer|max:100|min:0',
            'parcial' => 'required|in:' . implode(getYesNoArr())
        ];
    }
}
