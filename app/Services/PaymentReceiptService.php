<?php

namespace App\Services;

use App\Enums\FileUploadTypeEnum;
use App\Enums\PaymentReceiptStatusEnum;
use App\Exceptions\UserErrorException;
use App\Helpers\FileUploadHelper;
use App\Models\Order;
use App\Models\PaymentReceipt;
use App\Models\Site;
use Illuminate\Http\Request;

class PaymentReceiptService
{
    private $siteConfig;

    public function __construct(Site $siteConfig)
    {
        $this->siteConfig = $siteConfig;
    }

    public function update(PaymentReceipt $paymentReceipt, array $requestData)
    {
        if ($paymentReceipt->user_id != $this->siteConfig->user_id) {
            throw  UserErrorException::unauthorizedAccess();
        }

        if (empty($paymentReceipt['status']) || empty($requestData['comments'])) {
            throw new UserErrorException("Preencha os campos.");
        }
        if ($paymentReceipt['status'] == PaymentReceiptStatusEnum::Approved) {
            throw new UserErrorException("Não é possível editar um comprovante que já foi aprovado.");
        }
        if ($paymentReceipt['status'] == PaymentReceiptStatusEnum::Declined) {
            throw new UserErrorException("Não é possível editar um comprovante que já foi recusado.");
        }
        $paymentReceipt->status = $requestData['status'];
        $paymentReceipt->comments = $requestData['comments'];
        $paymentReceipt->saveOrFail();
        if ($requestData['status'] == PaymentReceiptStatusEnum::Approved) {
            $participant = $paymentReceipt->participant()->firstOrFail();
            $participant->confirmPayment();
        }
        return true;

    }

    public function uploadProof($orderUuid, Request $request)
    {
        if (!$request->hasFile('document')) {
            throw UserErrorException::emptyImage();
        }
        $order = Order::whereUuid($orderUuid)->where("user_id", $this->siteConfig->user_id)->first();
        if (!isset($order['id'])) {
            throw UserErrorException::cartNotFound();
        }
        $participant = $order->participant()->firstOrFail();

        $pendingReceipts = PaymentReceipt::whereParticipantId($participant['id'])->whereStatus(PaymentReceiptStatusEnum::Pending)->count();
        if ($pendingReceipts > 0) {
            throw new UserErrorException("Você já possui um comprovante em análise. Aguarde a verificação e tente novamente.");
        }
        if ($participant->pagos > 0) {
            throw new UserErrorException("Este pedido já foi pago.");
        }
        $imageUpload = new FileUploadHelper($request->file('document'), FileUploadTypeEnum::Image);
        $imageUrl = $imageUpload->upload();
        $paymentReceipts = new PaymentReceipt();
        $paymentReceipts->user_id = $this->siteConfig->user_id;
        $paymentReceipts->participant_id = $participant['id'];
        $paymentReceipts->document = $imageUrl;
        $paymentReceipts->saveOrFail();
        return true;


    }
}