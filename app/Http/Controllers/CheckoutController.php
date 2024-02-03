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
            'rifaDestaque' => $rifaDestaque,
        ];

        return view('site.checkout.payment', $userData);
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


    public function minhasReservas(Request $request)
    {
        $participante = Participant::siteOwner()->where('telephone', '=', $request->telephone)->orderBy('created_at', 'desc')->get();
        $rifas = [];

        foreach ($participante as $reserva) {
            $rifa = $reserva->product()->select("id", "name")->first();
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
}
