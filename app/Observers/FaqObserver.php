<?php

namespace App\Observers;

use App\Models\Faq;
use App\Models\Product;
use App\Models\ProductFaq;
use Illuminate\Support\Facades\Cache;

class FaqObserver
{
    public function deleted(Faq $faq)
    {
        ProductFaq::whereFaqId($faq->id)->delete();
        Faq::getAllActive($faq->user_id, true);
    }

    public function created(Faq $faq)
    {
        Faq::createProductFaqRelations($faq);
        Faq::getAllActive($faq->user_id, true);
    }

    public function updated(Faq $faq)
    {

        Faq::getAllActive($faq->user_id, true);
    }

}
