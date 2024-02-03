<?php

namespace App\Http\Controllers;

use App\Exceptions\UserErrorException;
use App\Libs\AsaasLib;
use App\Libs\MpLib;
use App\Libs\PaggueLib;
use App\Models\AffiliateEarning;
use App\Models\AffiliateRaffle;
use App\Models\AutoMessage;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Participant;
use App\Models\PaymentPix;
use App\Models\PrizeDraw;
use App\Models\Product;
use App\Models\Raffle;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use MercadoPago\Payment;
use MercadoPago\SDK;
use Ramsey\Uuid\Uuid;

class ProductController extends Controller
{

    public function index()
    {
        $ganhadores = PrizeDraw::siteOwner()->where('descricao', '!=', null)->where('ganhador', '!=', '')->get();

        $products = Product::siteOwner()->isVisible()->orderBy('id', 'desc')->get();

        $winners = Product::select('winner')->siteOwner()->winners()->get();

        $config = getSiteConfig();

        return view('site.index', [
            'products' => $products,
            'winners' => $winners,
            'ganhadores' => $ganhadores,
            'user' => getSiteOwnerUser(),
            'productModel' => Product::getByIdWithSiteCheck(4),
            'config' => $config
        ]);
    }

    public function sorteios()
    {
        $ganhadores = PrizeDraw::where('descricao', '!=', null)->where('ganhador', '!=', '')->get();

        $products = Product::where('visible', '=', 1)->orderBy('id', 'desc')->get();

        $winners = Product::winners()->get();

        $config = getSiteConfig();

        return view('sorteios', [
            'products' => $products,
            'winners' => $winners,
            'ganhadores' => $ganhadores,
            'user' => getSiteOwnerUser(),
            'productModel' => Product::getByIdWithSiteCheck(4),
            'config' => $config
        ]);
    }

    public function randomParticipant()
    {
        $nomesSobrenomes = [
            "João Silva",
            "Ana Santos",
            "Pedro Oliveira",
            "Maria Pereira",
            "Luiz Almeida",
            "Fernanda Lima",
            "Carlos Rodrigues",
            "Mariana Fernandes",
            "Rafael Sousa",
            "Isabela Costa",
            "Gustavo Gomes",
            "Larissa Santos",
            "Lucas Ribeiro",
            "Camila Nunes",
            "André Barbosa",
            "Carolina Castro",
            "Eduardo Ferreira",
            "Beatriz Cunha",
            "Antônio Santos",
            "Juliana Lima",
            "Felipe Pereira",
            "Amanda Martins",
            "Daniel Oliveira",
            "Laura Fernandes",
            "Bruno Alves",
            "Natália Barbosa",
            "Vinícius Castro",
            "Letícia Silva",
            "Gabriel Cunha",
            "Lívia Ribeiro",
            "Leonardo Sousa",
            "Ana Clara Costa",
            "Mateus Gomes",
            "Isadora Santos",
            "Thiago Pereira",
            "Raquel Lima",
            "José Silva",
            "Patrícia Fernandes",
            "Diego Almeida",
            "Júlia Castro",
            "Fábio Oliveira",
            "Mariana Pereira",
            "Roberto Santos",
            "Carla Lima",
            "Marcelo Fernandes",
            "Rita Barbosa",
            "Ricardo Gomes",
            "Renata Castro",
            "Paulo Silva",
            "Bianca Oliveira"
        ];
        $indiceSorteado = rand(0, count($nomesSobrenomes) - 1);
        $nomeSorteado = $nomesSobrenomes[$indiceSorteado];
        $resultUserRandom = explode(' ', $nomeSorteado);
        return json_encode($resultUserRandom);
    }


    public function getRaffles3(Request $request)
    {
        $productData = Product::siteOwner()->whereId($request->idProductURL)->first();
        if (!isset($productData['id'])) {
            abort(404);
        }
        $rifa = $productData;
        $numbers = $rifa->numbers();
        foreach ($rifa->participants() as $participante) {
            $statusParticipante = $participante->pagos > 0 ? 'pago' : 'reservado';
            foreach ($participante->numbers() as $value) {
                $numbers[] = $value . '-' . $statusParticipante . '-' . $participante->name;
            }
        }
        $pages = [];
        foreach (array_chunk($numbers, 100) as $number) {

        }


        return json_encode($resultRaffles);
    }

    public function formatMoney($value)
    {
        $value = str_replace('.', "", $value);
        $value = str_replace(',', ".", $value);

        return $value;
    }

    //REVERSA OS NÚMEROS DO SORTEIO X SEM INTEGRAÇÃO COM O PIX
    public function bookProductManualy(Request $request)
    {

        DB::beginTransaction();
        try {
            //Cadastrando customer
            if ($request->customer == 0) {
                $customer = Customer::create([
                    'nome' => $request->name,
                    'telephone' => $request->telephone,
                    'user_id' => getSiteOwnerId()
                ]);
            } else {
                $customer = Customer::getByIdWithSiteCheck($request->customer);
            }


            $prod = Product::siteOwner()->whereId($request->productID)->first();

            // Validando link de afiliado,
            $afiliado = AffiliateRaffle::siteOwner()->where('token', '=', $request->tokenAfiliado)->first();

            $path = 'numbers/' . $prod->id . '.json';
            //$jsonString = file_get_contents($path);

            if ($request->qtdNumbers > 10000) {
                return Redirect::back()->withErrors('Você só pode comprar no máximo 10.000 números por vez');
            }

            if (!$request->name) {
                return Redirect::back()->withErrors('Campo nome é obrigatório!');
            }
            if (!$request->telephone) {
                return Redirect::back()->withErrors('Campo telefone é obrigatório!');
            }

            // Para o gateway ASAAS é obrigatorio o CPF
            if ($prod->gateway == 'asaas') {
                $request->validate([
                    'cpf' => 'required',
                ]);
            }

            $codeKeyPIX = getSiteConfig();

            $integracaoGateway = true;
            if ($prod->gateway == 'mp' && $codeKeyPIX->key_pix == null) {
                $integracaoGateway = false;
            }
            if ($prod->gateway == 'asaas' && $codeKeyPIX->token_asaas == null) {
                $integracaoGateway = false;
            }

            if (!$integracaoGateway) {
                return Redirect::back()->withErrors('Administrador precisa adicionar a integração com o banco!');
            } else {

                $statusProduct = DB::table('products')
                    ->select('status')
                    ->where('products.id', '=', $request->productID)
                    ->first();

                $statusProduct = Product::select('status')
                    ->siteOwner()
                    ->where('products.id', '=', $request->productID)
                    ->first();

                if ($statusProduct->status == "Ativo") {

                    $user = DB::table('users')
                        ->select('users.name', 'users.telephone', 'products.type_raffles')
                        ->leftJoin('products', 'products.user_id', 'users.id')
                        ->leftJoin('sites', 'sites.user_id', 'users.id')
                        ->where('products.id', '=', $request->productID)
                        ->where('products.user_id', getSiteOwnerId())
                        ->first();

                    if ($user->type_raffles == 'manual') {
                        $validatedData = $request->validate([
                            'name' => 'required|max:255',
                            'telephone' => 'required|max:15',
                        ]);
                    } else if ($user->type_raffles == 'mesclado') {
                        if ($request->qtdNumbers == null) {
                            $validatedData = $request->validate([
                                'name' => 'required|max:255',
                                'telephone' => 'required|max:15',
                            ]);
                        } else {
                            $validatedData = $request->validate([
                                'name' => 'required|max:255',
                                'telephone' => 'required|max:15',
                                'qtdNumbers' => 'numeric|min:1|max:500'
                            ]);
                        }
                    } else if ($user->type_raffles == 'mesclado2') {
                        if ($request->qtdNumbers == null) {
                            $validatedData = $request->validate([
                                'name' => 'required|max:255',
                                'telephone' => 'required|max:15',
                            ]);
                        } else {
                            $validatedData = $request->validate([
                                'name' => 'required|max:255',
                                'telephone' => 'required|max:15',
                                'qtdNumbers' => 'numeric|min:1|max:500'
                            ]);
                        }
                    } else {
                        // $validatedData = $request->validate([
                        //     'name' => 'required|max:255',
                        //     'telephone' => 'required|max:15',
                        //     'qtdNumbers' => 'numeric|min:1|max:5000'
                        // ]);
                    }


                    if (str_starts_with($prod->modo_de_jogo, 'fazendinha')) {
                        $numbers = $request->numberSelected;
                        $resutlNumbers = explode(",", $numbers);
                    } else {

                        if ($prod->type_raffles == 'manual' || $prod->type_raffles == 'mesclado') {
                            $numbers = $request->numberSelected;
                            $resutlNumbers = explode(",", $numbers);


                            // Validando numeros escolhidos rifa manual
                            // ========================================================================================== //

                            $numerosValidos = true;

                            foreach ($resutlNumbers as $key => $value) {
                                $expl = explode("-", $value);
                                $number = end($expl);

                                $participantesPorNumero = Participant::siteOwner()->where('product_id', '=', $request->productID)->where('numbers', 'like', '%' . $number . '%')->get();

                                foreach ($participantesPorNumero as $key => $part) {
                                    if (array_search($number, $part->numbers()) || array_search($number, $part->numbers()) == 0) {
                                        $numerosValidos = false;
                                        break;
                                    }

                                }
                            }

                            if (!$numerosValidos) {
                                return Redirect::back()->withErrors('Um ou mais números escolhidos já foram comprados por outra pessoa! Tente novamente');
                            }

                            // ========================================================================================== //

                            $numbersRifa = $prod->numbers();

                            $selecionados = [];
                            foreach ($resutlNumbers as $key => $value) {
                                $expl = explode("-", $value);
                                $keyNumber = end($expl);

                                $keyRifa = array_search($keyNumber, $numbersRifa);
                                array_push($selecionados, $keyNumber);
                                unset($numbersRifa[$keyRifa]);
                            }

                            $prod->saveNumbers($numbersRifa);

                        } else {

                            $disponiveis = $prod->numbers();
                            // shuffle($numbersRifa);
                            // dd($numbersRifa);

                            // $disponiveis = array_filter($numbersRifa, function ($number) {
                            //     return $number['status'] == 'Disponivel';
                            // });

                            shuffle($disponiveis);

                            $selecionados = array_slice($disponiveis, 0, $request->qtdNumbers);

                            if (count($disponiveis) < $request->qtdNumbers) {
                                return Redirect::back()->withErrors('Quantidade indisponível para a rifa selecionada. A quantidade disponível é: ' . count($disponiveis));
                            }

                            foreach ($selecionados as $key => $resultNumber) {
                                $resutlNumbers[] = $resultNumber;
                                unset($disponiveis[$key]);
                            }

                            sort($disponiveis);

                            $prod->saveNumbers($disponiveis);

                            $numbers = implode(",", $resutlNumbers);
                        }
                    }

                    $product = DB::table('products')
                        ->select('products.*', 'products_images.name as image')
                        ->join('products_images', 'products.id', 'products_images.product_id')
                        ->where('products.id', '=', $request->productID)
                        ->where('products.user_id', getSiteOwnerId())
                        ->first();

                    // Validando minimo e maximo de compra da rifa
                    if (isset($randomNumbers)) {
                        if ($randomNumbers->count() < $product->minimo) {
                            return Redirect::back()->withErrors('Você precisa comprar no mínimo ' . $product->minimo . ' números');
                        }
                        if ($randomNumbers->count() > $product->maximo) {
                            return Redirect::back()->withErrors('Você só pode comprar no máximo ' . $product->maximo . ' números');
                        }
                    } else {
                        if (count($resutlNumbers) < $product->minimo) {
                            return Redirect::back()->withErrors('Você precisa comprar no mínimo ' . $product->minimo . ' números');
                        }
                        if (count($resutlNumbers) > $product->maximo) {
                            return Redirect::back()->withErrors('Você só pode comprar no máximo ' . $product->maximo . ' números');
                        }
                    }

                    $new = str_replace(",", ".", $product->price);

                    $price = count($resutlNumbers) * $new;
                    $resultPrice = number_format($price, 2, ",", ".");

                    $resultPricePIX = number_format($price, 2, ".", ",");


                    if ($request->promo != null && $request->promo > 0) {
                        $resultPrice = $request->promo;
                        $resultPricePIX = $this->formatMoney($request->promo);
                    }

                    // Validando valor abaixo de 5.00 para gateway ASAAS
                    if ($prod->gateway == 'asaas' && $price < 5) {
                        return Redirect::back()->withErrors('Sua aposta deve ser de no mínimo R$ 5,00');
                    }

                    // Verifica se algum numero escolhido ja possui reserva (WDM New)

                    $verifyReserved = Raffle::siteOwner()->isReserved($request->productID, $resutlNumbers)->count();


                    $reservedQty = Raffle::siteOwner()
                        ->where('raffles.product_id', '=', $request->productID)
                        ->whereIn('raffles.number', $resutlNumbers)
                        ->isReserved()
                        ->count();


                    if ($reservedQty > 0) {
                        return Redirect::back()->withErrors('Acabaram de reservar um ou mais numeros escolhidos, por favor escolha outros números :)');
                    } else {

                        $numbers = isset($selecionados) ? json_encode($selecionados) : json_encode($resutlNumbers);
                        $participante = Participant::create([
                            'user_id' => getSiteOwnerId(),
                            'customer_id' => $customer->id,
                            'name' => $request->name,
                            'telephone' => $request->telephone,
                            'email' => '',
                            'cpf' => '',
                            'valor' => $resultPricePIX,
                            'reservados' => count($resutlNumbers),
                            'product_id' => $request->productID,
                            'numbers' => $numbers

                        ]);

                        $gateway = $this->gerarPIX($prod, $resultPricePIX, $request->email, $request->name, $participante, $request->cpf, $request->telephone);

                        if (isset($gateway['error'])) {
                            return back()->withErrors($gateway['error']);
                        }

                        $codePIXID = $gateway['codePIXID'];
                        $codePIX = $gateway['codePIX'];
                        $qrCode = $gateway['qrCode'];

                        // $codePIXID = $object->id;
                        // $codePIX = $object->point_of_interaction->transaction_data->qr_code;
                        // $qrCode = $object->point_of_interaction->transaction_data->qr_code_base64;

                        $paymentPIX = DB::table('payment_pix')->insert([
                            'user_id' => getSiteOwnerId(),
                            'key_pix' => $codePIXID,
                            'full_pix' => $codePIX,
                            'status' => 'Pendente',
                            'participant_id' => $participante,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);


                        // Atualiza os numeros escolhidos para reservados
                        DB::table('raffles')
                            ->where('product_id', '=', $request->productID)
                            ->whereIn('raffles.number', $resutlNumbers)
                            ->update(
                                [
                                    'status' => 'Reservado',
                                    'participant_id' => $participante,
                                    'updated_at' => Carbon::now()
                                ]
                            );
                    }

                    $order = Order::create([
                        'uuid' => Uuid::uuid4(),
                        'key_pix' => $codePIXID,
                        'participant_id' => $participante,
                        'valor' => $price,
                        'user_id' => getSiteOwnerId()
                    ]);


                    $countRaffles = count($resutlNumbers);
                    $priceUnicFormat = str_replace(',', '.', $product->price);

                    $percentage = 5;

                    $percentagePriceUnic = ($percentage / 100) * $priceUnicFormat;
                    $resultPriceUnic = $priceUnicFormat + $percentagePriceUnic + 0.50;

                    if ($afiliado != null) {
                        $part = Participant::getByIdWithSiteCheck($participante);
                        AffiliateEarning::create([
                            'product_id' => $prod->id,
                            'participante_id' => $participante,
                            'afiliado_id' => $afiliado->afiliado_id,
                            'valor' => $part->valor * $prod->ganho_afiliado / 100,
                            'pago' => false,
                            'user_id' => getSiteOwnerId()

                        ]);
                    }


                    //dd(number_format($resultPriceUnic, 2, ".", ","));

                    $dadosSave = [
                        'participant_id' => $participante,
                        'participant' => $request->name,
                        'cpf' => $request->cpf,
                        'email' => $request->email,
                        'telephone' => $request->telephone,
                        'price' => $resultPrice,
                        'product' => $product->name,
                        'productID' => $product->id,
                        'drawdate' => $product->draw_date,
                        'image' => $product->image,
                        'PIX' => $resultPricePIX,
                        'countRaffles' => $countRaffles,
                        'priceUnic' => number_format($resultPriceUnic, 2, ".", ","),
                        'codePIX' => $codePIX,
                        'qrCode' => $qrCode,
                        'codePIXID' => $codePIXID
                    ];

                    $order->dados = json_encode($dadosSave);
                    $order->update();

                    $this->mensagemWPPCompra($participante);

                    DB::commit();
                    return redirect()->route('checkoutManualy', $dadosSave)->withInput();
                } elseif ($statusProduct->status == "Agendado") {
                    return Redirect::back()->withErrors('Sorteio agendado não é mais possível reservar!');
                } else {
                    return Redirect::back()->withErrors('Sorteio finalizado não é mais possível reservar!');
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
        }
    }

    public function validarNumerosEscolhidos($numbers)
    {
        return false;
    }

    public function mensagemWPPCompra($participanteID)
    {
        $admin = getSiteOwnerUser();
        $config = getSiteConfig();
        $participante = Participant::getByIdWithSiteCheck($participanteID);
        $msgAdmin = AutoMessage::where('identificador', '=', 'compra-admin')->first();
        $msgCliente = AutoMessage::where('identificador', '=', 'compra-cliente')->first();
        $apiURL = env('URL_API_CRIAR_WHATS');

        if ($config->token_api_wpp != null) {

            if ($msgCliente->msg != null && $msgCliente->msg != '') {
                $mensagem = $msgCliente->getMessage($participante);
                $customerPhone = '55' . str_replace(["(", ")", "-", " "], "", $participante->telephone);

                try {
                    $url = "https://api.whatapi.dev";
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


                } catch (\Throwable $th) {

                }
            }
        }
    }

    public function gerarPIX(Product $product, $resultPricePIX, $email, $name, $participante, $cpf, $telefone)
    {

        if ($resultPricePIX == 0) {
            $response['codePIXID'] = uniqid();
            $response['codePIX'] = 'gratis';
            $response['qrCode'] = '';

            return $response;
        }

        $codeKeyPIX = getSiteConfig();
        $productDesc = "Participação na ação " . $participante->id;
        $externalReferencee = $participante;
        if ($product->gateway == 'mp') {

            $mpLib = new MpLib($codeKeyPIX->key_pix);


            return $mpLib->getPix($resultPricePIX, $name, $email, $productDesc, $externalReferencee);

        } else if ($product->gateway == 'asaas') {
            $assasHelper = new AsaasLib($codeKeyPIX->token_asaas);
            $idCliente = $assasHelper->getOrCreateClienteAsaas($name, $email, $cpf, $telefone);
            return $assasHelper->getPix($product, $idCliente, $resultPricePIX, $productDesc, $externalReferencee);
        } else if ($product->gateway == 'paggue') {
            $pagLib = new PaggueLib($codeKeyPIX->paggue_client_key, $codeKeyPIX->paggue_client_secret);
            return $pagLib->getPix($name, $resultPricePIX, $productDesc, $externalReferencee);
        } else {
            $response['codePIXID'] = '';
            $response['codePIX'] = '';
            $response['qrCode'] = '';

            return $response;
        }
    }


    public function participants(Request $request)
    {
        //dd($request->product);

        $participants = Participant::select('name')
            ->join('raffles', 'participant.raffles_id', 'raffles.id')
            ->where('raffles.status', '=', 'Pago')
            ->where('raffles.product_id', '=', $request->product)
            ->where('raffles.user_id', getSiteOwnerId())
            ->inRandomOrder()
            ->count();

        //dd($teste->name);

        return $participants;
    }

    public function searchNumbers(Request $request)
    {
        $substr = substr($request->telephone, 0, 2);
        $ddd = '(' . $substr . ')';
        $substr1 = ' ' . substr($request->telephone, 2, 5) . '-';
        $substr2 = substr($request->telephone, 7);
        $resultTelephone = $ddd . $substr1 . $substr2;

        $numbersPaid = DB::table('participant')
            ->select('raffles.number', 'raffles.status', 'products.name')
            ->join('raffles', 'participant.raffles_id', 'raffles.id')
            ->join('products', 'products.id', 'raffles.product_id')
            ->where('participant.telephone', '=', $resultTelephone)
            ->where('products.id', '=', $request->productID)
            ->where('raffles.status', '=', 'Pago')
            ->where('products.user_id', getSiteOwnerId())
            ->get();

        return $numbersPaid;
    }

    public function searchPIX(Request $request)
    {
        $substr = substr($request->telephone, 0, 2);
        $ddd = '(' . $substr . ')';
        $substr1 = ' ' . substr($request->telephone, 2, 5) . '-';
        $substr2 = substr($request->telephone, 7);
        $resultTelephone = $ddd . $substr1 . $substr2;

        $pix = DB::table('participant')
            ->select('raffles.number', 'key_pix')
            ->leftJoin('payment_pix', 'participant.id', 'payment_pix.participant_id')
            ->join('raffles', 'participant.raffles_id', 'raffles.id')
            ->where('participant.telephone', '=', $resultTelephone)
            ->where('participant.product_id', '=', $request->product)
            ->where('participant.user_id', getSiteOwnerId())
            ->get();

        return $pix;
    }

    public function callbackPaymentMercadoPago(Request $request)
    {
        $mpCallback = function () use ($request) {
            if ($request['action'] == 'payment.updated') {
                $service = new PaymentService();
                $service->confirmPixPaymentById($request->id);
                return response()->json(['success' => 'success'], 200);
            } else {
                throw new UserErrorException("Ação inválida.");
            }
        };
        return $this->catchJsonResponse($mpCallback);
    }

    public function ganhadores()
    {

        // $winners = DB::table('products')
        //     ->select('*')
        //     ->where('products.status', '=', 'Finalizado')
        //     ->where('products.visible', '=', 1)
        //     ->orderBy('products.id', 'desc')
        //     ->get();

        $winners = Product::siteOwner()->get();

        return view('ganhadores', [
            'winners' => $winners
        ]);
    }


    public function notificacoesMP(Request $request)
    {
        try {
            $paymentPix = PaymentPix::siteOwner()->whereId($request->id)->firstOrFail();
            $codeKeyPIX = getSiteConfig();

            $accessToken = $codeKeyPIX->key_pix;

            SDK::setAccessToken($accessToken);

            $payment = Payment::find_by_id($paymentPix->id);

            if ($payment) {
                if ($payment->status == 'cancelled') {
                    $paymentPix->delete();
                } else if ($payment->status == 'approved') {
                    $participante = Participant::getByIdWithSiteCheck($payment->external_reference);
                    if (isset($participante['id'])) {
                        $paymentService = new PaymentService();
                        $paymentService->confirmPayment($participante);
                    } else {
                        $paymentPix->delete();
                    }
                }
            }

            return response('OK', 200)->header('Content-Type', 'text/plain');
        } catch (\Throwable $th) {
            //throw $th;
            return response('Erro', 404)->header('Content-Type', 'text/plain');
        }
    }

    public function rankingAdmin(Request $request)
    {
        $rifa = Product::getByIdWithSiteCheck($request->id);

        $data = [
            'rifa' => $rifa,
            'ranking' => $rifa->rankingAdmin()
        ];

        $response['html'] = view('ranking-admin', $data)->render();

        return $response;
    }

    public function definirGanhador(Request $request)
    {
        $rifa = Product::getByIdWithSiteCheck($request->id);

        $data = [
            'rifa' => $rifa,
        ];

        $response['html'] = view('layouts.definir-ganhador', $data)->render();

        return $response;
    }

    public function verGanhadores(Request $request)
    {
        $rifa = Product::getByIdWithSiteCheck($request->id);

        $data = [
            'rifa' => $rifa,
        ];

        $response['html'] = view('layouts.ver-ganhadores', $data)->render();

        return $response;
    }

    public function informarGanhadores(Request $request)
    {
        try {
            $rifa = Product::getByIdWithSiteCheck($request->idRifa);
            $premios = $rifa->prizeDraws();
            $ganhadores = [];


            if ($rifa->modo_de_jogo == 'numeros') {
                foreach ($request->cotas as $key => $cota) {
                    foreach ($rifa->participants() as $participante) {
                        $numbersParticipante = $participante->numbers();
                        $find = array_search($cota, $numbersParticipante);
                        if (is_int($find)) {
                            array_push($ganhadores, $participante->name);
                            $premios->where('ordem', '=', $key)->first()->update([
                                'ganhador' => $participante->name,
                                'telefone' => $participante->telephone,
                                'cota' => $cota,
                                'participant_id' => $participante->id
                            ]);
                            break;
                        }
                    }
                }
            } else {
                foreach ($request->cotas as $key => $cota) {
                    $numero = $rifa->numbers()->where('number', '=', $cota)->first();
                    $participante = $numero->participant();
                    $premios->where('ordem', '=', $key)->first()->update([
                        'ganhador' => $participante->name,
                        'telefone' => $participante->telephone,
                        'cota' => $cota
                    ]);
                }
            }


            return redirect()->back()->with(['success' => 'Ganhadores (' . implode(',', $ganhadores) . ') informados com sucesso!', 'sorteio' => $request->idRifa]);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors('Erro ao informar ganhadores');
        }
    }

    public function notificacoesPaggue(Request $request)
    {
        $req = fopen('webhook_paggue.json', 'w') or die('Cant open the file');
        fwrite($req, $request);
        fclose($req);

        $participante = Participant::getByIdWithSiteCheck($request->external_id);
        if ($participante && $request->status == '1') {

            $paymentService = new PaymentService();
            $paymentService->confirmPayment($participante);

            return response('OK', 200)->header('Content-Type', 'text/plain');
        }
    }
}
