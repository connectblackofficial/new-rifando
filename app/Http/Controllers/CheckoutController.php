<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Participant;
use App\Models\PaymentPix;
use App\Models\PrizeDraw;
use App\Models\Product;
use App\Models\Raffle;
use App\Services\PaymentService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use MercadoPago\Payment;
use MercadoPago\SDK;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $userOwner = getSiteOwnerUser();
        return view('checkout', [
            'participant' => $request->participant,
            'cpf' => $request->cpf,
            'telephone' => $request->telephone,
            'numbers' => $request->numbers,
            'price' => $request->price,
            'product' => $request->product,
            'productID' => $request->productID,
            'drawdate' => $request->drawdate,
            'image' => $request->image,
            'PIX' => $request->PIX,
            'codePIX' => $request->codePIX,
            'imagePIX' => $request->imagePIX,
            'telephoneConsulting' => $userOwner,
            'countRaffles' => $request->countRaffles,
            'priceUnic' => $request->priceUnic,
        ]);
    }

    public function checkoutManualy(Request $request)
    {

        $userOwner = getSiteOwnerUser();
        $numbers = Raffle::siteOwner()->whereParticipantId($request->participant_id)->get();
        //Validando se existe essa reserva
        $participante = Participant::getByIdWithSiteCheck($request->participant_id);
        if (!isset($participante['id'])) {
            return redirect()->route('inicio')->withErrors('Reserva inválida');
        }

        $rifa = Product::getByIdWithSiteCheck($request->productID);
        if (!isset($rifa['id'])) {
            return redirect()->route('inicio')->withErrors('Reserva inválida');
        }

        $criacao = date('Y-m-d H:i:s', strtotime($participante->created_at));
        $minutosExpiracao = $rifa->expiracao;
        $dataDeExpiracao = date('Y-m-d H:i:s', strtotime("+" . $minutosExpiracao . " minutes", strtotime($criacao)));

        $entrada = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $saida = DateTime::createFromFormat('Y-m-d H:i:s', $dataDeExpiracao);
        $diff = $entrada->diff($saida);

        //echo "Tempo entre horas: " . $diff->format("%y anos, %m meses, %d dias, %h horas, %i minutos, %s segundos");


        $minutosRestantes = ceil(($saida->getTimestamp() - $entrada->getTimestamp()) / 60);

        $config = getSiteConfig();

        $products = Product::siteOwner()->isVisible()->orderBy('id', 'desc')->get();
        $ganhadores = PrizeDraw::siteOwner()->winners()->get();

        // $rifaDestaque = Product::where('status', '=', 'Ativo')->where('visible', '=', 1)->where('favoritar', '=', 1)->orderBy('id', 'desc')->first();

        $rifaDestaque = Product::getByIdWithSiteCheck($participante->product_id);

        $userData = [
            'rifa' => $rifa,
            'participante' => $participante,
            'participant' => $request->participant,
            'cpf' => $request->cpf,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'numbers' => $numbers,
            'price' => $request->price,
            'product' => $request->product,
            'productID' => $request->productID,
            'drawdate' => $request->drawdate,
            'image' => $request->image,
            'PIX' => $request->PIX,
            'codePIX' => $request->codePIX,
            'imagePIX' => $request->imagePIX,
            'telephoneConsulting' => $userOwner,
            'countRaffles' => $request->countRaffles,
            'priceUnic' => $request->priceUnic,
            'qrCode' => $request->qrCode,
            'codePIXID' => $request->codePIXID,
            'minutosRestantes' => $minutosRestantes,
            'config' => $config,
            'user' => getSiteOwnerUser(),
            'products' => $products,
            'ganhadores' => $ganhadores,
            'rifaDestaque' => $rifaDestaque
        ];

        return view('new-checkout', $userData);
    }

    public function checkPixPaymment()
    {
        $codeKeyPIX = getSiteConfig()->key_pix;

        if (env('APP_ENV') == 'local') {
            $secretKey = 'TEST-330207199077363-081623-283cea3525fa71a8e4d1afa279bf8e8c-197295574';
        } else {
            $secretKey = $codeKeyPIX->key_pix;
        }

        SDK::setAccessToken($secretKey);

        $payment = new Payment();

        //$payment = new MercadoPago\Payment();
        $payment->transaction_amount = 0.11;
        $payment->description = "Título do produto";
        $payment->payment_method_id = "pix";
        $payment->notification_url = "https://google.com.br/notiification.php";
        $payment->external_reference = 1520;
        $payment->payer = array(
            "email" => "tester@email.com",
            "first_name" => "Test",
            "last_name" => "User",
            "identification" => array(
                "type" => "CPF",
                "number" => "62103474368"
            ),
            "address" => array(
                "zip_code" => "06233200",
                "street_name" => "Av. das Nações Unidas",
                "street_number" => "3003",
                "neighborhood" => "Bonfim",
                "city" => "Osasco",
                "federal_unit" => "SP"
            )
        );

        $payment->save();
        //
        echo "<pre>";
        var_dump($payment->id);
        echo "</pre>";
    }

    public function findPixStatus($id)
    {
        $cleanID = explode("-", $id);
        $realPixID = (isset($cleanID[0])) ? $cleanID[0] : NULL;
        $realProductID = (isset($cleanID[1])) ? (int)$cleanID[1] : NULL;

        $codeKeyPIX = getSiteConfig();

        $PayRaffleNumber = PaymentPix::select(['participant_id', 'full_pix', 'status'])
            ->siteOwner()
            ->where('key_pix', $realPixID)->first();

        if ($PayRaffleNumber) {
            if ($PayRaffleNumber->full_pix == 'gratis') {
                $participante = Participant::getByIdWithSiteCheck($PayRaffleNumber->participant_id);
                if (!isset($participante['id'])) {
                    abort(404);
                }
                $participante->confirmPayment();

                $cotasHTML = view('layouts.cotas-checkout', ['participante' => $participante])->render();

                $response = [
                    'status' => TRUE,
                    'cotas' => $cotasHTML
                ];

                return json_encode($response);
            }
        }


        // ASSAS
        if (str_starts_with($realPixID, "pay")) {
            $client = new \GuzzleHttp\Client([
                'headers' => [
                    'Content-Type' => 'application/json',
                    'access_token' => $codeKeyPIX->token_asaas
                ]
            ]);

            $URL = 'https://www.asaas.com/api/v3/payments/' . $realPixID;
            $req = $client->get($URL);
            $resp = json_decode($req->getBody()->getContents());

            if ($resp->status == 'RECEIVED' || $resp->status == 'CONFIRMED') {
                PaymentPix::where('key_pix', $realPixID)->siteOwner()
                    ->update(['status' => 'Aprovado']);

                $PayRaffleNumber = PaymentPix::select('participant_id')
                    ->siteOwner()
                    ->where('key_pix', $realPixID)->get();

                $participante = Participant::getByIdWithSiteCheck($PayRaffleNumber[0]->participant_id);
                $rifa = $participante->firstProduct();

                $rifa->confirmPayment($participante->id);

                $cotasHTML = view('layouts.cotas-checkout', ['participante' => $participante])->render();

                foreach ($PayRaffleNumber as $keyNumbers => $valNumbers) {
                    CheckoutController::savePayedRaffles($valNumbers->participant_id, $realProductID);
                }


                $response = [
                    'status' => TRUE,
                    'cotas' => $cotasHTML
                ];

                return json_encode($response);
            }

            return $resp->status;
        } // Paggue
        else if (strlen($id) >= 25) {

            array_pop($cleanID);
            $hash = implode('-', $cleanID);
            $paymentPix = PaymentPix::siteOwner()->where('key_pix', '=', $hash)->first();
            $participante = Participant::getByIdWithSiteCheck($paymentPix->participant_id);
            $rifa = $participante->firstProduct();


            if ($paymentPix->status == 'Aprovado') {
                $rifa->confirmPayment($participante->id);
                $response = [
                    'status' => TRUE,
                    'cotas' => view('layouts.cotas-checkout', ['participante' => $participante])->render()
                ];

                return json_encode($response);
            }
        } //MP
        else {
            $realPixID = (int)$realPixID;

            if (env('APP_ENV') == 'local') {
                $secretKey = $codeKeyPIX->key_pix;
            } else {
                $secretKey = $codeKeyPIX->key_pix;
            }

            SDK::setAccessToken($secretKey);

            $payment = new Payment();

            $payment = Payment::find_by_id($realPixID);
            $payment->capture = true;
            $payment->update();


            if ($payment->status == "approved" && $payment->status_detail == "accredited") {
                $pixPayment = PaymentPix::siteOwner()->where('key_pix', $realPixID)->firstOrFail();
                $paymentService = new PaymentService();
                $paymentService->confirmPixPayment($pixPayment);
                PaymentPix::siteOwner()->where('key_pix', $realPixID)
                    ->update(['status' => 'Aprovado']);

                $PayRaffleNumber = PaymentPix::siteOwner()->select('participant_id')
                    ->where('key_pix', $realPixID)->get();

                $participante = Participant::getByIdWithSiteCheck($PayRaffleNumber[0]->participant_id);
                $rifa = $participante->firstProduct();
                $rifa->confirmPayment($participante->id);

                $response = [
                    'status' => TRUE,
                    'cotas' => view('layouts.cotas-checkout', ['participante' => $participante])->render()
                ];

                return json_encode($response);
            }
        }
    }

    public function savePayedRaffles($participantID, $productID)
    {
        $getRaffles = Participant::select(['product_id', 'raffles_id'])
            ->siteOwner()
            ->where('id', $participantID)
            ->where('product_id', $productID)
            ->get();
        foreach ($getRaffles as $keyRaffle => $valRaffle) {
            $this->updatePayedRifflesByID($valRaffle->raffles_id, $valRaffle->product_id);
        }
    }

    public function updatePayedRifflesByID($id, $productID)
    {
        Raffle::siteOwner()->where('id', $id)
            ->where('product_id', $productID)
            ->update([
                'status' => 'Pago'
            ]);
    }

    public function findPedidoStatus($id = NULL)
    {
        $resultRaffles = [];
        if ($id !== NULL) :
            $cleandID = explode("=", $id);
            if (isset($cleandID[0]) && isset($cleandID[1])) :
                $telInfo = $cleandID[0];
                $productID = $cleandID[1];

                $product = Product::getByIdWithSiteCheck($productID);
                if (!isset($product['id'])) {
                    abort(404);
                }
                $participantDetail = Participant::siteOwner()
                    ->where('participant.product_id', '=', $productID)
                    ->where('participant.telephone', '=', $telInfo)
                    ->first();

                $participant = DB::table('participant')
                    ->select('*')
                    ->join('raffles', 'raffles.id', 'participant.raffles_id')
                    ->where('participant.product_id', '=', $productID)
                    ->where('participant.telephone', '=', $telInfo)
                    ->where('raffles.status', '=', 'Pago')
                    ->get();

                $orderParticipants = DB::table('order')
                    ->select('order.id', 'order.key_pix', 'payment_pix.full_pix', 'payment_pix.status')
                    ->join('payment_pix', 'payment_pix.key_pix', 'order.key_pix')
                    ->join('participant', 'participant.id', 'payment_pix.participant_id')
                    ->join('raffles', 'raffles.id', 'participant.raffles_id')
                    ->where('participant.product_id', '=', $productID)
                    ->where('participant.telephone', '=', $telInfo)
                    ->where('raffles.status', '=', 'Reservado')
                    ->groupBy('order.id')
                    ->get();

                $orderPayedParticipants = DB::table('order')
                    ->select('order.id', 'order.key_pix', 'payment_pix.full_pix', 'payment_pix.status')
                    ->join('payment_pix', 'payment_pix.key_pix', 'order.key_pix')
                    ->join('participant', 'participant.id', 'payment_pix.participant_id')
                    ->join('raffles', 'raffles.id', 'participant.raffles_id')
                    ->where('participant.product_id', '=', $productID)
                    ->where('participant.telephone', '=', $telInfo)
                    ->where('raffles.status', '=', 'Pago')
                    ->groupBy('order.id')
                    ->get();

                $participantReserveds = DB::table('participant')
                    ->select('participant.id as user', 'raffles.*', 'payment_pix.*')
                    ->join('raffles', 'raffles.id', 'participant.raffles_id')
                    ->join('payment_pix', 'payment_pix.participant_id', 'participant.id')
                    ->where('participant.product_id', '=', $productID)
                    ->where('participant.telephone', '=', $telInfo)
                    ->where('raffles.status', '=', 'Reservado')
                    ->get();

                $participantPayed = DB::table('participant')
                    ->select('participant.id as user', 'raffles.*', 'payment_pix.*')
                    ->join('raffles', 'raffles.id', 'participant.raffles_id')
                    ->join('payment_pix', 'payment_pix.participant_id', 'participant.id')
                    ->where('participant.product_id', '=', $productID)
                    ->where('participant.telephone', '=', $telInfo)
                    ->where('raffles.status', '=', 'Pago')
                    ->get();

                if (!empty($participantReserveds)) {
                    foreach ($participantReserveds as $rafflesNumber) :
                        $resultParticipantID[] = $rafflesNumber->user;
                        $resultRaffles[] = $rafflesNumber->number;
                    endforeach;

                    $numbers = implode(",", $resultRaffles);
                    $price = count($resultRaffles) * floatval($product->price);
                    $resultPricePIX = number_format($price, 2, ".", ",");
                    $convertPriceBR = number_format($price, 2, ",", ".");
                }

                $telephoneConsulting = DB::table('products')
                    ->select('users.telephone')
                    ->join('users', 'users.id', '=', 'products.user_id')
                    ->where('products.id', '=', $productID)
                    ->first();

                $userData = [
                    'product' => $product,
                    'participantDetail' => $participantDetail,
                    'resultRafflesALLs' => $participant,
                    'orderParticipants' => $orderParticipants,
                    'participantReserveds' => $participantReserveds,
                    'orderPayedParticipants' => $orderPayedParticipants,
                    'participantPayed' => $participantPayed,
                    'priceReserveds' => @$resultPricePIX,
                    'numberReserveds' => @$numbers,
                    'telephone' => @$telephoneConsulting,
                    'priceBR' => @$convertPriceBR
                ];
                return view('consulting-reservation', $userData);
            endif;
        endif;
    }

    public function minhasReservas(Request $request)
    {
        $participante = Participant::siteOwner()->where('telephone', '=', $request->telephone)->orderBy('created_at', 'desc')->get();
        $rifas = [];

        foreach ($participante as $reserva) {
            $rifa = $reserva->firstProduct();
            $rifas[$rifa->id] = $rifa->name;
        }


        $config = getSiteConfig();

        $data = [
            'reservas' => $participante,
            'rifas' => $rifas,
            'config' => $config
        ];

        return view('minhas-reservas', $data);
    }

    public function consultingReservation(Request $request)
    {
        $resultRaffles = [];
        $product = Product::getByIdWithSiteCheck($request->productID);
        if (!isset($product['id'])) {
            abort(404);
        }
        $participantReserveds = DB::table('participant')
            ->select('participant.id as user', 'raffles.*', 'payment_pix.*')
            ->join('raffles', 'raffles.id', 'participant.raffles_id')
            ->join('payment_pix', 'payment_pix.participant_id', 'participant.id')
            ->where('participant.product_id', '=', $request->productID)
            ->where('participant.telephone', '=', $request->telephone)
            ->where('raffles.status', '=', 'Reservado')
            ->get();

        if (!empty($participantReserveds)) {
            foreach ($participantReserveds as $rafflesNumber) :
                $resultParticipantID[] = $rafflesNumber->user;
                $resultRaffles[] = $rafflesNumber->number;
            endforeach;

            $numbers = implode(",", $resultRaffles);
            $price = count($resultRaffles) * floatval($product->price);
            $resultPricePIX = number_format($price, 2, ".", ",");
            $convertPriceBR = number_format($price, 2, ",", ".");
        }

        $telephoneConsulting = DB::table('products')
            ->select('users.telephone')
            ->join('users', 'users.id', '=', 'products.user_id')
            ->where('products.id', '=', $request->productID)
            ->first();

        $participante = Participant::where('telephone', '=', $request->telephone)->where('product_id', '=', $request->productID)->get();
        $rifas = [];

        foreach ($participante as $reserva) {
            $rifa = $reserva->firstProduct();
            $rifas[$rifa->id] = $rifa->name;
        }

        $config = getSiteConfig();

        $data = [
            'reservas' => $participante,
            'rifas' => $rifas,
            'config' => $config
        ];

        return view('minhas-reservas', $data);

    }

    public function consultingReservationTelephone(Request $request, $productID, $telephone)
    {

        $substr = substr($telephone, 0, 2);
        $ddd = '(' . $substr . ')';
        $substr1 = ' ' . substr($telephone, 2, 5) . '-';
        $substr2 = substr($telephone, 7);
        $resultTelephone = $ddd . $substr1 . $substr2;

        $product = Product::getByIdWithSiteCheck($productID);
        if (!isset($product['id'])) {
            abort(404);
        }

        $participantDetail = Participant::siteOwner()
            ->where('participant.product_id', '=', $productID)
            ->where('participant.telephone', '=', $resultTelephone)->first();

        $participant = DB::table('participant')
            ->select('*')
            ->join('raffles', 'raffles.id', 'participant.raffles_id')
            ->where('participant.product_id', '=', $productID)
            ->where('participant.telephone', '=', $resultTelephone)
            ->where('raffles.status', '=', 'Pago')
            ->get();

        $orderParticipants = DB::table('order')
            ->select('order.id', 'order.key_pix', 'payment_pix.full_pix')
            ->join('payment_pix', 'payment_pix.key_pix', 'order.key_pix')
            ->join('participant', 'participant.id', 'payment_pix.participant_id')
            ->join('raffles', 'raffles.id', 'participant.raffles_id')
            ->where('participant.product_id', '=', $productID)
            ->where('participant.telephone', '=', $resultTelephone)
            ->where('raffles.status', '=', 'Reservado')
            ->groupBy('order.id')
            ->get();

        //dd($orderParticipants);

        $participantReserveds = DB::table('participant')
            ->select('participant.id as user', 'raffles.*', 'payment_pix.*')
            ->join('raffles', 'raffles.id', 'participant.raffles_id')
            ->join('payment_pix', 'payment_pix.participant_id', 'participant.id')
            ->where('participant.product_id', '=', $productID)
            ->where('participant.telephone', '=', $resultTelephone)
            ->where('raffles.status', '=', 'Reservado')
            ->get();

        /*$participantReserveds = DB::table('participant')
            ->select('participant.id as user', 'raffles.*', 'payment_pix.full_pix', 'order.id as order')
            ->join('raffles', 'raffles.id', 'participant.raffles_id')
            ->join('payment_pix', 'participant.id', 'payment_pix.participant_id')
            ->join('order', 'payment_pix.key_pix', 'order.key_pix')
            ->where('participant.product_id', '=', $productID)
            ->where('participant.telephone', '=', $resultTelephone)
            ->where('raffles.status', '=', 'Reservado')
            ->get();*/

        $telephoneConsulting = DB::table('products')
            ->select('users.telephone')
            ->join('users', 'users.id', '=', 'products.user_id')
            ->where('products.id', '=', $request->productID)
            ->first();

        if ($participantReserveds == '[]') {
        } else {
            foreach ($participantReserveds as $rafflesNumber) {
                $resultParticipantID[] = $rafflesNumber->user;
                $resultRaffles[] = $rafflesNumber->number;
            }

            $numbers = implode(",", $resultRaffles);

            $price = count($resultRaffles) * floatval($product->price);
            $resultPricePIX = number_format($price, 2, ".", ",");
            $convertPriceBR = number_format($price, 2, ",", ".");
        }

        return view('consulting-reservation', [
            'product' => $product,
            'participantDetail' => $participantDetail,
            'resultRafflesALLs' => $participant,
            'orderParticipants' => $orderParticipants,
            'participantReserveds' => $participantReserveds,
            'priceReserveds' => @$resultPricePIX,
            'numberReserveds' => @$numbers,
            'telephone' => $telephoneConsulting,
            'priceBR' => @$convertPriceBR
        ]);
    }

    public function consultingReservationManualy(Request $request)
    {
        Product::getByIdWithSiteCheck($request->productID);
        if (!isset($product['id'])) {
            abort(404);
        }
        //METODO consultingReservationTelephone E consultingReservation SÃO IGUAIS SEMPRE DEIXAR OS DOIS PARECIDOS

        $product = DB::table('products')
            ->select('products.id', 'products.name', 'sites.key_pix', 'products.price', 'products.draw_date')
            ->join('sites', 'sites.user_id', '=', 'products.user_id')
            ->where('products.id', '=', $request->productID)
            ->first();

        $participantDetail = DB::table('participant')
            ->select('*')
            ->where('participant.product_id', '=', $request->productID)
            ->where('participant.telephone', '=', $request->telephone)
            ->first();

        $participant = DB::table('participant')
            ->select('*')
            ->join('raffles', 'raffles.id', 'participant.raffles_id')
            ->where('participant.product_id', '=', $request->productID)
            ->where('participant.telephone', '=', $request->telephone)
            ->where('raffles.status', '=', 'Pago')
            ->get();

        //dd($participant);

        $orderParticipants = DB::table('order')
            ->select('order.id', 'order.key_pix', 'payment_pix.full_pix')
            ->join('payment_pix', 'payment_pix.key_pix', 'order.key_pix')
            ->join('participant', 'participant.id', 'payment_pix.participant_id')
            ->join('raffles', 'raffles.id', 'participant.raffles_id')
            ->where('participant.product_id', '=', $request->productID)
            ->where('participant.telephone', '=', $request->telephone)
            ->where('raffles.status', '=', 'Reservado')
            ->groupBy('order.id')
            ->get();

        //dd($orderParticipants);

        $participantReserveds = DB::table('participant')
            ->select('participant.id as user', 'raffles.*')
            ->join('raffles', 'raffles.id', 'participant.raffles_id')
            ->where('participant.product_id', '=', $request->productID)
            ->where('participant.telephone', '=', $request->telephone)
            ->where('raffles.status', '=', 'Reservado')
            ->get();

        if ($participantReserveds == '[]') {
        } else {
            foreach ($participantReserveds as $rafflesNumber) {

                $resultParticipantID[] = $rafflesNumber->user;
                $resultRaffles[] = $rafflesNumber->number;
            }

            $numbers = implode(",", $resultRaffles);

            $price = count($resultRaffles) * floatval($product->price);
            $resultPricePIX = number_format($price, 2, ".", ",");
            $convertPriceBR = number_format($price, 2, ",", ".");
        }

        $telephoneConsulting = DB::table('products')
            ->select('users.telephone')
            ->join('users', 'users.id', '=', 'products.user_id')
            ->where('products.id', '=', $request->productID)
            ->first();


        return view('consulting-reservation-manualy', [
            'product' => $product,
            'participantDetail' => $participantDetail,
            'resultRafflesALLs' => $participant,
            'orderParticipants' => $orderParticipants,
            'participantReserveds' => $participantReserveds,
            'priceReserveds' => @$resultPricePIX,
            'numberReserveds' => @$numbers,
            'telephone' => @$telephoneConsulting,
            'priceBR' => @$convertPriceBR
        ]);
    }

    public function consultingReservationTelephoneManualy(Request $request, $productID, $telephone)
    {
        Product::getByIdWithSiteCheck($productID);
        if (!isset($product['id'])) {
            abort(404);
        }
        $substr = substr($telephone, 0, 2);
        $ddd = '(' . $substr . ')';
        $substr1 = ' ' . substr($telephone, 2, 5) . '-';
        $substr2 = substr($telephone, 7);
        $resultTelephone = $ddd . $substr1 . $substr2;

        $product = DB::table('products')
            ->select('products.id', 'products.name', 'sites.key_pix', 'products.price', 'products.draw_date')
            ->join('sites', 'sites.user_id', '=', 'products.user_id')
            ->where('products.id', '=', $productID)
            ->first();


        $participantDetail = DB::table('participant')
            ->select('*')
            ->where('participant.product_id', '=', $productID)
            ->where('participant.telephone', '=', $resultTelephone)
            ->first();

        $participant = DB::table('participant')
            ->select('*')
            ->join('raffles', 'raffles.id', 'participant.raffles_id')
            ->where('participant.product_id', '=', $productID)
            ->where('participant.telephone', '=', $resultTelephone)
            ->where('raffles.status', '=', 'Pago')
            ->get();

        $orderParticipants = DB::table('order')
            ->select('order.id', 'order.key_pix', 'payment_pix.full_pix')
            ->join('payment_pix', 'payment_pix.key_pix', 'order.key_pix')
            ->join('participant', 'participant.id', 'payment_pix.participant_id')
            ->join('raffles', 'raffles.id', 'participant.raffles_id')
            ->where('participant.product_id', '=', $productID)
            ->where('participant.telephone', '=', $resultTelephone)
            ->where('raffles.status', '=', 'Reservado')
            ->groupBy('order.id')
            ->get();

        //dd($orderParticipants);

        $participantReserveds = DB::table('participant')
            ->select('participant.id as user', 'raffles.*')
            ->join('raffles', 'raffles.id', 'participant.raffles_id')
            ->where('participant.product_id', '=', $productID)
            ->where('participant.telephone', '=', $resultTelephone)
            ->where('raffles.status', '=', 'Reservado')
            ->get();

        /*$participantReserveds = DB::table('participant')
            ->select('participant.id as user', 'raffles.*', 'payment_pix.full_pix', 'order.id as order')
            ->join('raffles', 'raffles.id', 'participant.raffles_id')
            ->join('payment_pix', 'participant.id', 'payment_pix.participant_id')
            ->join('order', 'payment_pix.key_pix', 'order.key_pix')
            ->where('participant.product_id', '=', $productID)
            ->where('participant.telephone', '=', $resultTelephone)
            ->where('raffles.status', '=', 'Reservado')
            ->get();*/

        $telephoneConsulting = DB::table('products')
            ->select('users.telephone')
            ->join('users', 'users.id', '=', 'products.user_id')
            ->where('products.id', '=', $request->productID)
            ->first();

        if ($participantReserveds == '[]') {
        } else {
            foreach ($participantReserveds as $rafflesNumber) {
                $resultParticipantID[] = $rafflesNumber->user;
                $resultRaffles[] = $rafflesNumber->number;
            }

            $numbers = implode(",", $resultRaffles);

            $price = count($resultRaffles) * floatval($product->price);
            $resultPricePIX = number_format($price, 2, ".", ",");
            $convertPriceBR = number_format($price, 2, ",", ".");
        }

        return view('consulting-reservation-manualy', [
            'product' => $product,
            'participantDetail' => $participantDetail,
            'resultRafflesALLs' => $participant,
            'orderParticipants' => $orderParticipants,
            'participantReserveds' => $participantReserveds,
            'priceReserveds' => @$resultPricePIX,
            'numberReserveds' => @$numbers,
            'telephone' => $telephoneConsulting,
            'priceBR' => @$convertPriceBR
        ]);
    }

    public function paymentPix(Request $request)
    {

        $participantsID = explode("|", $request->participant_id);
        //dd($raffles);

        //VER UMA FORMA PARA MELHORAR ISSO HOJE SE ALGUEM ALTERAR O PARTICIPANTID NA URL VAI LATERAR A LINHA DO BANCO COM OS DADOS ERRADOS
        foreach ($participantsID as $participantID) {
            PaymentPix::updateOrCreate([
                'user_id' => getSiteOwnerId(),
                'participant_id' => $participantID,
            ], [
                'key_pix' => $request->key_pix,
                'participant_id' => $participantID,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }

    public function paymentCredit(Request $request)
    {
        $codeKeyPIX = getSiteConfig();

        if (env('APP_ENV') == 'local') {
            $secretKey = 'TEST-330207199077363-081623-283cea3525fa71a8e4d1afa279bf8e8c-197295574';
        } else {
            $secretKey = $codeKeyPIX->key_pix;
        }

        SDK::setAccessToken($secretKey);

        $payment = new Payment();
        $payment->transaction_amount = (float)$request[0]['transaction_amount'];
        $payment->token = $request[0]['token'];
        //$payment->description = $request[0]['description'];
        $payment->installments = (int)$request[0]['installments'];
        $payment->payment_method_id = $request[0]['payment_method_id'];
        $payment->issuer_id = (int)$request[0]['issuer_id'];

        $payer = new \MercadoPago\Payer();
        $payer->email = $request[0]['payer']['email'];
        $payer->identification = array(
            "type" => $request[0]['payer']['identification']['type'],
            "number" => $request[0]['payer']['identification']['number']
        );
        $payment->payer = $payer;

        $payment->save();

        $response_fields = array(
            'status' => $payment->status,
            'status_detail' => $payment->status_detail,
            'id' => $payment->id
        );

        if ($response_fields['status'] == 'approved') {

            //FAZ A ALTERACAO NA RESERVA PARA PAGO
            DB::table('payment_pix')
                ->where("user_id", getSiteOwnerId())
                ->where('key_pix', $request[1])
                ->update(['status' => 'Concluída']);


        }

        return redirect(url('checkout-manualy?status=') . $response_fields['status']);
    }

    public function pagarReserva($id)
    {
        $participante = Participant::getByIdWithSiteCheck($id);

        $dados = json_decode($participante->order()->dados, true);

        return redirect()->route('checkoutManualy', $dados)->withInput();
    }

    public function getCustomer(Request $request)
    {
        $customer = Customer::siteOwner()->where('telephone', '=', $request->phone)->first();

        $response['customer'] = $customer;

        return $response;
    }
}
