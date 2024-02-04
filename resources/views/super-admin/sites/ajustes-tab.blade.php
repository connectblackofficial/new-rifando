<div class="row">
    <?php
    $formClass="col-md-3";
    ?>
    <?= inputText('key_pix', $site, ['form-container' => $formClass]) ?>
    <?= inputText('token_asaas', $site, ['form-container' => $formClass]) ?>
    <?= inputText('token_api_wpp', $site, ['form-container' => $formClass]) ?>
    <?= selectField('hide_winners', \App\Enums\YesNoAsIntEnum::getSelectCrudFormat(), $site, ['form-container' => $formClass]) ?>
    <?= selectField('enable_affiliates', \App\Enums\YesNoAsIntEnum::getSelectCrudFormat(), $site, ['form-container' => $formClass]) ?>
    <?= selectField('cpf_required', \App\Enums\YesNoAsIntEnum::getSelectCrudFormat(), $site, ['form-container' => $formClass]) ?>
    <?= selectField('email_required', \App\Enums\YesNoAsIntEnum::getSelectCrudFormat(), $site, ['form-container' => $formClass]) ?>
    <?= selectField('show_faqs', \App\Enums\YesNoAsIntEnum::getSelectCrudFormat(), $site, ['form-container' => $formClass]) ?>
    <?= selectField('require_user_terms_acept', \App\Enums\YesNoAsIntEnum::getSelectCrudFormat(), $site, ['form-container' => $formClass]) ?>
    <?= selectField('show_purchase_notifications', \App\Enums\YesNoAsIntEnum::getSelectCrudFormat(), $site, ['form-container' => $formClass]) ?>
    <?php
    $cols = ['brand-color', 'btn-free-color', 'btn-reserved-color', 'btn-paid-color', 'brand-bg-color', 'secondary-bg-color', 'secondary-bg-text-color'];
    foreach ($cols as $c) {
        echo inputColor($c, $site, ['form-container' => $formClass]);
    }
    ?>
</div>