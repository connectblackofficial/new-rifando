@if(isset($productResume['faqs']))
    <div class="pb-2">
            <?= faqColapse($productResume['faqs'], $config) ?>
    </div>
@endif