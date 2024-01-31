<?php

namespace App\Http\Middleware;

use App\Models\Participant;
use App\Models\Raffle;
use Closure;
use Illuminate\Support\Facades\DB;

class ExpiradasMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {



        // $codeKeyPIX = DB::table('sites')
        //     ->select('key_pix')
        //     ->where('user_id', '=', getSiteOwner())
        //     ->first();

        // $secretKey = $codeKeyPIX->key_pix;

        // if($secretKey != null){
        //     \MercadoPago\SDK::setAccessToken($secretKey);
        // }
        

        // $pendentes = DB::table('payment_pix')->where('status', '=', 'Pendente')->where('key_pix', '!=', '')->get();

        // foreach ($pendentes as $value) {
        //     try {
        //         // Verificando se existe participante (se nao exister ja exclui o pedido)
        //         $checkReserva = Participant::find($value->participant_id);
        //         if ($checkReserva) {
        //             $realPixID = $value->key_pix;

        //             $payment = \MercadoPago\Payment::find_by_id($realPixID);

        //             if ($payment) {
        //                 if ($payment->status == 'cancelled') {
        //                     DB::table('payment_pix')->where('id', '=', $value->id)->delete();
        //                 } else if ($payment->status == 'approved') {

        //                     $participante = Participant::find($payment->external_reference);
        //                     if ($participante) {
        //                         $rifa = $participante->rifa();
        //                         $rifa->confirmPayment($participante->id);

        //                         DB::table('payment_pix')->where('id', '=', $value->id)->update([
        //                             'status' => 'Aprovado'
        //                         ]);
        //                     } else {
        //                         DB::table('payment_pix')->where('id', '=', $value->id)->delete();
        //                     }
        //                 }
        //             }
        //         } else {
        //             DB::table('payment_pix')->where('id', '=', $value->id)->delete();
        //         }
        //     } catch (\Throwable $th) {
        //         //dd($value);
        //     }
        // }

        return $next($request);
    }
}
