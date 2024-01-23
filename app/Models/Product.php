<?php

namespace App\Models;

use App\AutoMessage;
use App\CompraAutomatica;
use App\Enums\CacheExpiresInEnum;
use App\Enums\CacheKeysEnum;
use App\Environment;
use App\RifaAfiliado;
use App\Traits\HasEloquentCacheTrait;
use App\Traits\ModelSiteOwnerTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use ModelSiteOwnerTrait;
    use HasEloquentCacheTrait;

    protected $fillable = ['name', 'subname', 'parcial', 'expiracao', 'qtd_ranking', 'qtd_zeros', 'product', 'slug', 'price', 'ganho_afiliado', 'status', 'qtd', 'numbers', 'processado', 'type_raffles', 'favoritar', 'modo_de_jogo', 'minimo', 'maximo', 'user_id', 'draw_prediction', 'draw_date', 'winner', 'visible', 'gateway'];

    public function saveNumbers($numbersArray)
    {
        $stringNumbers = implode(",", $numbersArray);

        $this->update([
            'numbers' => $stringNumbers
        ]);

    }

    public function getCompraMaisPopular()
    {
        $compras = $this->comprasAuto();
        if ($compras->where('popular', '=', true)->count() > 0) {
            return $compras->where('popular', '=', true)->first()->id;
        } else {
            return 0;
        }
    }

    public function getFreeNumbers()
    {
        if ($this->modo_de_jogo == 'numeros') {
            $numbersRifa = explode(",", $this->numbers);
            return $numbersRifa;
        } else {
            return Raffle::select("number")->whereProductId($this->id)->where("status", "Disponível")->get()->pluck("number");
        }
    }

    public function numbers()
    {
        if ($this->modo_de_jogo == 'numeros') {
            $numbersRifa = explode(",", $this->numbers);
            return $numbersRifa;
        } else {
            return $this->hasMany(Raffle::class, 'product_id', 'id')->where('user_id', getSiteOwnerId())->get();
        }
    }

    public function confirmPayment($participanteId)
    {
        $participante = Participant::siteOwner()->whereId($participanteId)->firstOrFail();
        $participante->confirmPayment();
    }

    public function hasImages()
    {
        $image = $this->imagem();
        if (isset($image['id'])) {
            return true;
        }
        return false;
    }

    public function mensagemWPPRecebido($participanteID)
    {
        $admin = getSiteOwnerUser();
        $config = getSiteConfig();
        $participante = Participant::getByIdWithSiteCheck($participanteID);
        $msgAdmin = AutoMessage::siteOwner()->where('identificador', '=', 'recebido-admin')->first();
        $msgCliente = AutoMessage::siteOwner()->where('identificador', '=', 'recebido-cliente')->first();
        $apiURL = env('URL_API_CRIAR_WHATS');

        if ($config->token_api_wpp != null) {

            // ============== Mensagem para o admin
            // ============================================== //
            // if($msgAdmin->msg != null && $msgAdmin->msg != ''){
            //     $mensagem = $msgAdmin->getMessage($participante);
            //     $owerPhone = '55' . str_replace(["(", ")", "-", " "], "", $admin->telephone);

            //     try {
            //         $data = [
            //             'receiver'  => $owerPhone,
            //             'msgtext'   => $mensagem,
            //             'token'     => $config->token_api_wpp,
            //         ];

            //         $ch = curl_init();
            //         curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
            //         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //         curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //         curl_setopt($ch, CURLOPT_URL, $apiURL);
            //         curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            //         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            //         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            //         $response = curl_exec($ch);
            //         curl_close($ch);
            //     } catch (\Throwable $th) {

            //     }
            // }

            // ============== Mensagem para o cliente
            // ============================================== //
            if ($msgCliente->msg != null && $msgCliente->msg != '') {
                if (!$participante->msg_pago_enviada) {
                    $mensagem = $msgCliente->getMessage($participante);
                    $customerPhone = '55' . str_replace(["(", ")", "-", " "], "", $participante->telephone);

                    try {
                        $url = "https://api.whatapi.com.br";
                        $token = base64_decode($config->token_api_wpp);
                        $numero = $customerPhone;

// testar o envio com essa formatacao abaixo. se nao for comente a linha 13 e descomente a 14 para testar novamente.
                        $mensagem = str_replace("\r\n", "\\n", $mensagem);
//$mensagem = preg_replace('/\\\n|\n|#&@/i', '\n', $mensagem);

                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $url . '/message/text?key=' . $token . '',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => '{
        "id": "' . $numero . '",
        "message": "' . $mensagem . '",
        "msdelay": "3000"
    }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                                'Authorization: Bearer @@N855cd65@@'
                            ),
                        ));

                        $response = curl_exec($curl);
                        curl_close($curl);

                        $participante->update([
                            'msg_pago_enviada' => true
                        ]);

                    } catch (\Throwable $th) {
                    }
                }
            }
        }
    }

    public function afiliados()
    {
        return $this->hasMany(RifaAfiliado::class, 'product_id', 'id')->get();
    }


    public function qtdNumerosDisponiveis()
    {
        if ($this->modo_de_jogo == 'numeros') {
            return $this->qtd - $this->qtdNumerosReservados() - $this->qtdNumerosPagos();
        } else {
            return $this->hasMany(Raffle::class, 'product_id', 'id')->where('status', '=', 'Disponível')->count();
        }
    }

    public function randomNumbers($qtd)
    {
        $randomNumbers = DB::table('raffles')
            ->select('number')
            ->where('raffles.product_id', '=', $this->id)
            ->where('raffles.status', '=', 'Disponível')
            ->inRandomOrder()
            ->limit($qtd)
            ->get();

        return $randomNumbers;
    }

    public function qtdNumerosReservados()
    {
        if ($this->modo_de_jogo == 'numeros') {
            return $this->participantes()->sum('reservados');
        } else {
            return $this->hasMany(Raffle::class, 'product_id', 'id')->where('status', '=', 'Reservado')->count();
        }
    }


    public function qtdNumerosPagos()
    {
        if ($this->modo_de_jogo == 'numeros') {
            return $this->participantes()->sum('pagos');
        } else {
            return $this->hasMany(Raffle::class, 'product_id', 'id')->where('status', '=', 'Pago')->count();
        }
    }

    public function porcentagem()
    {
        $numerosUtilizados = $this->qtdNumerosReservados() + $this->qtdNumerosPagos();
        $totalDaRifa = $this->qtd;

        $percentual = ($numerosUtilizados * 100) / $totalDaRifa;

        return round($percentual, 2);
    }

    public function participantes()
    {
        return $this->hasMany(Participant::class, 'product_id', 'id')->orderBy('id', 'desc')->get();
    }

    public function participantesReservados()
    {
        $numeros = Raffle::select('participant_id')
            ->where('product_id', '=', $this->id)
            ->where('status', '=', 'Reservado')
            ->groupBy('participant_id')
            ->get();

        return $numeros;
    }

    public function promos()
    {
        return $this->hasMany(Promocao::class, 'product_id', 'id');
    }

    public function promocoes()
    {
        return $this->hasMany(Promocao::class, 'product_id', 'id')->orderBy('ordem', 'asc')->get();
    }


    public function imagem()
    {
        return $this->hasOne(ProductImage::class, 'product_id', 'id')->first();
    }


    public function fotos()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id')->limit(3)->get();
    }


    public function ranking()
    {

        $ranking = DB::table('participant')
            ->select(DB::raw('SUM(participant.pagos) as totalReservas'), 'participant.telephone', 'participant.name')
            ->where('participant.product_id', '=', $this->id)
            ->where('participant.pagos', '>', 0)
            ->groupBy('participant.telephone')
            ->orderBy('totalReservas', 'desc')
            ->limit($this->qtd_ranking)
            ->get();

        return $ranking->toArray();
    }

    public function rankingAdmin()
    {

        $ranking = DB::table('participant')
            ->select(DB::raw('SUM(participant.pagos) as totalReservas'), DB::raw('SUM(participant.valor) as totalGasto'), 'participant.telephone', 'participant.name')
            ->where('participant.product_id', '=', $this->id)
            ->where('participant.pagos', '>', 0)
            ->groupBy('participant.telephone')
            ->orderBy('totalReservas', 'desc')
            ->limit(8)
            ->get();

        return $ranking->toArray();
    }

    public function descricao()
    {
        $desc = $this->hasOne(ProductDescription::class, 'product_id', 'id')->first();
        if ($desc) {
            return $desc->description;
        } else {
            return '';
        }
    }

    public function promosAtivas()
    {
        $promocoes = $this->promos()->where('qtdNumeros', '>', 0)->get();
        $result = [];
        foreach ($promocoes as $promocao) {
            array_push($result, [
                'numeros' => $promocao->qtdNumeros,
                'desconto' => $promocao->desconto
            ]);
        }

        return json_encode($result);
    }

    public function getWinnersQty()
    {
        return Premio::whereProductId($this->id)->winners()->count();
    }

    public function createProductImage($imageName)
    {
        return ProductImage::create([
            'name' => $imageName,
            'product_id' => $this->id,
            'user_id' => $this->user_id
        ]);
    }

    public function createPromos()
    {
        for ($i = 1; $i <= 4; $i++) {
            Promocao::create([
                'product_id' => $this->id,
                'ordem' => $i,
                'user_id' => $this->user_id,
            ]);
        }
    }

    public function createDefaultPremiums($dados = [])
    {
        for ($i = 1; $i <= 10; $i++) {
            $auxPremio = 'premio' . $i;
            $desc = "";
            if (isset($dados[$auxPremio])) {
                $desc = $dados[$auxPremio];
            }
            Premio::create([
                'product_id' => $this->id,
                'ordem' => $i,
                'descricao' => $desc,
                'ganhador' => '',
                'cota' => '',
                'user_id' => $this->user_id
            ]);
        }
    }

    public function premios()
    {
        $premios = $this->hasMany(Premio::class, 'product_id', 'id')->orderBy('ordem', 'asc')->get();

        if ($premios->count() === 0) {
            $this->createDefaultPremiums();
            return $this->hasMany(Premio::class, 'product_id', 'id')->orderBy('ordem', 'asc')->get();
        } else {
            return $premios;
        }
    }

    public function status()
    {
        switch ($this->status) {
            case 'Ativo':
                if ($this->porcentagem() >= 80) {
                    $status = '<span class="badge mt-2 blink" style="color: #fff; background-color: rgba(0,0,0,.7)">Corre que está acabando!</span>';
                } else {
                    $status = '<span class="badge mt-2 bg-success blink">Adquira já!</span>';
                }
                break;
            case 'Finalizado':
                if ($this->premios()->where('descricao', '!=', '')->where('ganhador', '!=', '')->count() == 0) {
                    $status = '<span class="badge mt-2 blink" style="color: #fff; background-color: rgba(0,0,0,.7);">Aguarde sorteio!</span>';
                } else {
                    $status = '<span class="badge mt-2 bg-danger">Finalizado</span>';
                }

                break;
            default:
                $status = '';
                break;
        }

        return $status;
    }


    public function comprasAuto()
    {
        $compras = $this->hasMany(CompraAutomatica::class, 'product_id', 'id')->get();
        if ($compras->count() == 0) {
            $this->defaultComprasAuto();
            $compras = $this->hasMany(CompraAutomatica::class, 'product_id', 'id')->get();
        }

        return $compras;
    }

    public function defaultComprasAuto()
    {
        $comprasAuto = [];
        $comprasAuto[] = [
            'qtd' => 0,
            'popular' => false
        ];
        $comprasAuto[] = [
            'qtd' => 0,
            'popular' => false
        ];
        $values = [5, 10, 30, 50];
        foreach ($values as $value) {
            $popular = false;
            if ($value == 50) {
                $popular = true;
            }
            $comprasAuto[] = [
                'qtd' => $value,
                'popular' => $popular
            ];
        }
        foreach ($comprasAuto as $compra) {
            $compra['product_id'] = $this->id;
            $compra['user_id'] = $this->user_id;
            CompraAutomatica::create($compra);
        }

    }

    public static function scopeSearch($query, $q)
    {
        if (!empty($q)) {
            return $query->where(function ($query) use ($q) {
                $query->orWhere('name', 'like', '%' . $q . '%');
                $query->orWhere('subname', 'like', '%' . $q . '%');
                $query->orWhere('product', 'like', '%' . $q . '%');
                $query->orWhere('status', 'like', '%' . $q . '%');
                $query->orWhere('gateway', 'like', '%' . $q . '%');
                $query->orWhere('winner', 'like', '%' . $q . '%');
                $query->orWhere('slug', 'like', '%' . $q . '%');
            });
        } else {
            return $query;
        }

    }

    public static function scopeHasFinished($query)
    {
        return $query->where('status', 'Finalizado');
    }

    public static function scopeIsVisible($query)
    {
        return $query->where('visible', 1);
    }

    public static function scopeHasWinner($query)
    {
        return $query->whereNotNull('winner');
    }

    public static function scopeWinners($query)
    {
        return $query->hasFinished()->hasWinner()->isVisible();
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function descriptions()
    {
        return $this->hasMany(ProductDescription::class);
    }

    public function createOrUpdateDescription($description)
    {
        $attr = ['user_id' => $this->user_id, 'product_id' => $this->id];
        return ProductDescription::updateOrCreate($attr, ['description' => $description]);
    }

    public function getDefaultImageUrl()
    {
        $imagem = $this->imagem();
        if (isset($imagem['name'])) {
            return imageAsset($imagem['name']);
        } else {
            return url('images/sem-foto.jpg');
        }

    }

    public function getBasicInfo()
    {
        return [
            'name' => $this->name,
            'subname' => $this->subname,
            'id' => $this->id,
            'price' => $this->price,
            'min_buy' => $this->minimo,
            'max_buy' => $this->maximo,
            'numbers_qty' => $this->qtd,
            'type_raffles' => $this->type_raffles,
            'game_mode' => $this->modo_de_jogo,
            'draw_prediction' => $this->draw_prediction
        ];
    }

    public function getAllImages()
    {
        return $this->images()->get();
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id', 'id')->get();
    }

    public static function getResumeCache($productId, $forceUpdate = false)
    {
        $key = "product_resume_3_" . $productId;
        $callBack = function () use ($productId) {
            $product = Product::whereId($productId)->firstOrFail();
            $productAsArray = convertToArray($product);
            unset($productAsArray['numbers']);
            return [
                'reserved' => $product->qtdNumerosReservados(),
                'paid' => $product->qtdNumerosPagos(),
                'free' => $product->qtdNumerosDisponiveis(),
                'percentage' => $product->porcentagem(),
                'promos' => $product->promocoes(),
                'free_numbers' => $product->getFreeNumbers(),
                'product' => $productAsArray
            ];
        };

        return getCacheOrCreate($key, null, $callBack, CacheExpiresInEnum::OneWeek, $forceUpdate);


    }
}
