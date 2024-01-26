<?php

namespace App\Http\Requests;

use App\Enums\GameModeEnum;
use App\Enums\PaymentGatewayEnum;
use App\Enums\ProductStatusEnum;
use App\Enums\RaffleTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class SiteProductFastStoreRequest extends FormRequest
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

        $rules = (new SiteProductStoreRequest())->rules();
        $rules['modo_de_jogo'] = 'required|' . GameModeEnum::getRule();
        $unsets = ['data_sorteio', 'qtd_ranking', 'images', 'tipo_reserva', 'visible', 'favoritar_rifa', 'numPromocao.*', 'valPromocao.*', 'status', 'parcial', 'cadastrar_ganhador', 'ganho_afiliado'];
        foreach ($unsets as $k) {
            unset($rules[$k]);
        }
        return $rules;
    }
}
