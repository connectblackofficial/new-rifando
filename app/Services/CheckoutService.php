<?php

namespace App\Services;

use App\Enums\PaymentPixStatusEnum;
use App\Enums\ProductStatusEnum;
use App\Events\CheckoutCompletedEvent;
use App\Exceptions\UserErrorException;
use App\Helpers\ManualPixGenerator;
use App\Libs\AsaasLib;
use App\Libs\MpLib;
use App\Libs\PaggueLib;
use App\Models\AffiliateEarning;
use App\Models\AffiliateRaffle;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Participant;
use App\Models\PaymentPix;
use App\Models\PixAccount;
use App\Models\PrizeDraw;
use App\Models\Product;
use App\Models\Raffle;
use App\Models\Site;
use App\Rules\CpfValidation;
use Carbon\Carbon;
use Dompdf\Exception;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class CheckoutService
{

    private $cartModel;
    private $productResume;
    private $siteConfig;

    public function __construct(Site $siteConfig, Cart $cartModel)
    {
        $this->siteConfig = $siteConfig;
        $this->cartModel = $cartModel;
        $this->productResume = Product::getResumeCache($cartModel->product_id);
    }

    public function completeCheckout(array $requestData): Order
    {
        try {
            DB::beginTransaction();

            $siteOwner = $this->siteConfig['user_id'];
            $siteConfig = $this->siteConfig;
            $customerService = new CustomerService($siteConfig);
            $customer = $customerService->createOrGet($requestData);
            if (!isset($customer['id'])) {
                throw UserErrorException::customerNotFound();
            }
            $cart = Cart::whereUuid($requestData['cart_uuid'])->first();
            if (!isset($cart['id'])) {
                throw new UserErrorException("Checkout inválido.");
            }
            $product = $cart->product()->first();
            if (!isset($product['id']) || $product['user_id'] != $siteOwner) {
                throw UserErrorException::productNotFound();
            }
            if ($product['status'] != ProductStatusEnum::Active) {
                throw new UserErrorException("Sorteio indisponível para novas reservas.");
            }

            if (isset($requestData['tokenAfiliado'])) {
                $afiliado = AffiliateRaffle::whereUserId($siteOwner)->where('token', $requestData['tokenAfiliado'])->first();
            }
            $cart = CartService::refresh($siteConfig, $cart);
            $freeNumbers = $product->numbers();
            $numbers = $cart->getAllCartNumbers();
            $ownerUserId = $product['user_id'];
            $total = $cart->total;
            $participant = Participant::create([
                'uuid' => Uuid::uuid4(),
                'user_id' => $ownerUserId,
                'customer_id' => $customer->id,
                'name' => $customer->nome,
                'telephone' => $customer->telephone,
                'email' => $customer->email,
                'ddi' => $customer->ddi,
                'cpf' => $customer->cpf,
                'valor' => $total,
                'reservados' => $cart->getNumbersQty(),
                'product_id' => $product['id'],
                'numbers' => json_encode($numbers)
            ]);
            $cart->participant_id = $participant->id;
            $cart->saveOrFail();
            foreach ($numbers as $key => $v) {
                unset($freeNumbers[$v]);
            }
            $product->saveNumbers($freeNumbers);

            $gateway = $this->gerarPIX($product, $total, $customer->email, $customer->name, $participant, $customer->cpf, $customer->telephone);

            if (isset($gateway['error'])) {
                throw new \Exception($gateway['error']);
            }
            if (!isset($gateway['qrCode'])) {
                throw new UserErrorException("Ocorreu um erro ao gerar o qrcode para pagamento.");
            }

            $codePIXID = $gateway['codePIXID'];
            $codePIX = $gateway['codePIX'];
            $qrCode = $gateway['qrCode'];

            $paymentData = [
                'user_id' => $ownerUserId,
                'key_pix' => $codePIXID,
                'full_pix' => $codePIX,
                'status' => PaymentPixStatusEnum::Pending,
                'participant_id' => $participant->id
            ];
            $paymentPix = PaymentPix::create($paymentData);
            if (!isset($paymentPix['id'])) {
                throw  UserErrorException::createFailed();
            }
            Raffle::whereProductId($product['id'])->whereIn('number', $numbers)->update([
                'status' => 'Reservado',
                'participant_id' => $participant->id,
            ]);

            $order = Order::create([
                'uuid' => Uuid::uuid4(),
                'key_pix' => $codePIXID,
                'participant_id' => $participant->id,
                'valor' => $total,
                'user_id' => $ownerUserId
            ]);
            if (!isset($order['id'])) {
                throw  UserErrorException::createFailed();
            }
            $countRaffles = count($numbers);
            $priceUnicFormat = str_replace(',', '.', $product->price);
            $percentage = 5;
            $percentagePriceUnic = ($percentage / 100) * $priceUnicFormat;
            $resultPriceUnic = $priceUnicFormat + $percentagePriceUnic + 0.50;


            if (isset($afiliado['id'])) {
                $affiliateVal = $participant->affiliateVal($product->ganho_afiliado);
                if ($affiliateVal > 0) {
                    $affData = AffiliateEarning::create([
                        'product_id' => $product->id,
                        'participante_id' => $participant->id,
                        'afiliado_id' => $afiliado->afiliado_id,
                        'valor' => $affiliateVal,
                        'pago' => false,
                        'user_id' => $product->user_id

                    ]);
                    if (!isset($affData['id'])) {
                        throw  UserErrorException::createFailed();
                    }

                }

            }

            $dadosSave = [
                'participant_id' => $participant->id,
                'participant' => $participant->name,
                'cpf' => $participant->cpf,
                'email' => $participant->email,
                'telephone' => $participant->telephone,
                'price' => $total,
                'product' => $product->name,
                'productID' => $product->id,
                'drawdate' => $product->draw_date,
                'image' => $product->image,
                'PIX' => $total,
                'countRaffles' => $countRaffles,
                'priceUnic' => number_format($resultPriceUnic, 2, ".", ","),
                'codePIX' => $codePIX,
                'qrCode' => $qrCode,
                'codePIXID' => $codePIXID
            ];

            $order->dados = json_encode($dadosSave);
            $order->saveOrFail();
            DB::commit();
            event(new CheckoutCompletedEvent($order));
            return $order;
        } catch (UserErrorException $e) {
            DB::rollBack();
            throw new UserErrorException($e->getMessage());

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


    }


    public function gerarPIX(Product $product, $resultPricePIX, $email, $name, Participant $participante, $cpf, $telefone)
    {

        if ($resultPricePIX == 0) {
            $response['codePIXID'] = uniqid();
            $response['codePIX'] = 'gratis';
            $response['qrCode'] = '';

            return $response;
        }

        $codeKeyPIX = getSiteConfig();
        $productDesc = $participante->getDescription();
        $externalReferencee = $participante->id;
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
            /** @var PixAccount $pixAccount */
            $pixAccount = $product->pixAccount()->firstOrFail();
            $manualPix = new ManualPixGenerator($pixAccount, $participante);
            $response['codePIXID'] = $manualPix['pixId'];
            $response['codePIX'] = $manualPix['mounted_pix'];
            $response['pix_url'] = $manualPix['mounted_pix'];
            return $response;
        }
    }

    public static function paymentPage(Site $site, Order $order)
    {
        if ($site['user_id'] <> $order['user_id']) {
            throw new UserErrorException("Pedido inválido.");
        }
        $orderData = $order->getData();
        //Validando se existe essa reserva
        $participante = $order->participant()->firstOrFail();
        $rifa = $participante->product()->firstOrFail();
        $minutosRestantes = $participante->getMinutesLeft($rifa->expiracao);
        $rifaDestaque = Product::getByIdWithSiteCheck($participante->product_id);
        $userData = [
            'rifa' => $rifa,
            'participante' => $participante,
            'price' => $participante->valor,
            'productID' => $participante->product_id,
            'codePIX' => $orderData->codePIX,
            'qrCode' => $orderData->qrCode,
            'codePIXID' => $orderData->codePIXID,
            'minutosRestantes' => $minutosRestantes,
            'config' => $site,
            'rifaDestaque' => $rifaDestaque,
        ];

        return view('site.checkout.payment', $userData);

    }
}