<?php

namespace App\Observers;

use App\Events\ProductCreated;
use App\Models\Promo;

class PromoObserver
{
    /**
     * Handle the promo "created" event.
     *
     * @param \App\Promo $promo
     * @return void
     */
    public function created(Promo $promo)
    {

    }

    /**
     * Handle the promo "updated" event.
     *
     * @param \App\Promo $promo
     * @return void
     */
    public function updated(Promo $promo)
    {
        //
    }

    /**
     * Handle the promo "deleted" event.
     *
     * @param \App\Promo $promo
     * @return void
     */
    public function deleted(Promo $promo)
    {
        //
    }

    /**
     * Handle the promo "restored" event.
     *
     * @param \App\Promo $promo
     * @return void
     */
    public function restored(Promo $promo)
    {
        //
    }

    /**
     * Handle the promo "force deleted" event.
     *
     * @param \App\Promo $promo
     * @return void
     */
    public function forceDeleted(Promo $promo)
    {
        //
    }
}
