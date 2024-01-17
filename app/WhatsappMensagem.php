<?php

namespace App;

use App\Models\Participant;
use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class WhatsappMensagem extends Model
{
    use ModelSiteOwnerTrait;
    protected $fillable = [
        'titulo',
        'msg',
        'user_id'
    ];

    public function clearBreak()
    {
        return str_replace("<br />", "", $this->msg);
    }

    public function getMessage(Participant $participante)
    {
        $variaveis = [
            'id',
            'nome',
            'valor',
            'total',
            'cotas',
            'sorteio',
            'link'
        ];

        $message = $this->msg;

        $message = str_replace("<br />", "", $message);

        foreach ($variaveis as $variavel) {
            $replace = $this->replaceKey($variavel, $participante);

            $var = "{" . $variavel . "}";

            $message = str_replace($var, $replace, $message);
        }

        return $message;
    }

    public function generateLink(Participant $participante)
    {
        $variaveis = [
            'id',
            'nome',
            'valor',
            'total',
            'cotas',
            'sorteio',
            'link'
        ];

        $link = $participante->linkWpp();

        $link .= '&text=' . $this->msg;

        $link = str_replace("<br />", "%0A", $link);

        foreach ($variaveis as $variavel) {
            $replace = $this->replaceKey($variavel, $participante);

            $var = "{" . $variavel . "}";

            $link = str_replace($var, $replace, $link);
        }

        return $link;
    }

    public function replaceKey($key, Participant $participante)
    {
        switch ($key) {
            case 'id':
                return $participante->id;
                break;
            case 'nome':
                return $participante->name;
                break;
            case 'valor':
                return $participante->rifa()->price;
                break;
            case 'total':
                return number_format($participante->valor, 2, ",", ".");
                break;
            case 'cotas': // TODO
                $cotas = '';
                foreach ($participante->numbers() as $key => $value) {
                    if($key != 0){
                        $cotas .= ',';
                    }
                    $cotas .= $value;
                }
                return $cotas;
                break;
            case 'sorteio':
                return $participante->rifa()->name;
                break;
            case 'link': // TODO
                return route('pagarReserva', $participante->id);
                break;
            default:
                return $key;
                break;
        }
    }
}
