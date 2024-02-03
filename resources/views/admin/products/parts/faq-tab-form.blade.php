<?php
$baseLangFaq = ['base-lang' => 'product_faq'];
?>

@foreach($faqs as $faq)
    <div class="row text-center mt-4 ">
        <div class="row">
            <a href="{{route("admin.faqs.show",['pk'=>$faq->faq_id])}}"><h5>{{$faq->title}} - #{{$faq->faq_id}}</h5></a>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                        <?php
                        $name = "faq[" . $faq->id . "][show]";
                        ?>
                        <?= selectField($name, getYesNoArr(), [$name => $faq->show], ['base-lang' => 'product_faq', 'custom-label' => htmlLabel("product_faq_show")]) ?>
                </div>
            </div>
            <div class="col-md-6">
                    <?php
                    $name = "faq[" . $faq->id . "][order]";
                    ?>
                    <?= inputField($name, 'number', [$name => $faq->order], ['base-lang' => 'product_faq', 'custom-label' => htmlLabel("product_faq_order")]) ?>
            </div>
        </div>

    </div>
@endforeach

