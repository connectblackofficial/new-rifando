<?php

namespace App\Services;

use App\Exceptions\UserErrorException;
use App\Models\Participant;
use App\Models\PaymentPix;
use App\Models\Raffle;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    function confirmPayment(Participant $participant)
    {
        $participant->confirmPayment();
    }

    public function confirmPixPaymentById($id)
    {
        $paymentPix = PaymentPix::siteOwner()->whereId($id)->first();
        if (isset($paymentPix['id'])) {
            $this->confirmPixPayment($paymentPix);
        } else {
            throw UserErrorException::pixNotFound();
        }
    }

    public function confirmPixPayment(PaymentPix $pixPayment)
    {
        $participant = $pixPayment->participante();
        $this->confirmPayment($participant);
        $pixPayment->status = "Aprovado";
        $pixPayment->saveOrFail();

    }
}