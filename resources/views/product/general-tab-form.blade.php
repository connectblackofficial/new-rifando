<?php
$baseLang = 'product';
?>

<div class="row mt-3">
    <div class="col-md-6">
        @if(isset($product['id']))
            <input type="hidden" name="product_id"
                   value="{{ $product->id }}">
        @endif
        <?= inputField('name', 'text', $product) ?>
    </div>

    <div class="col-md-6">
        <?= inputField('price', 'numeric', $product,['onclick'=>'return maskMoney(this)']) ?>
    </div>
    <div class="col-md-12">
        <?= inputField('subname', 'text', $product) ?>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-4">
        <?= inputField('minimo', 'number', $product, ['min' => 1, 'max' => "999999", 'base-lang' => $baseLang]) ?>
    </div>
    <div class="col-md-4">
        <?= inputField('maximo', 'number', $product, ['base-lang' => $baseLang]) ?>
    </div>
    <div class="col-md-4">
        <?= inputField('expiracao', 'number', $product, ['min' => 0, 'base-lang' => $baseLang]) ?>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <?= inputField('qtd_ranking', 'number', $product, ['base-lang' => $baseLang]) ?>
    </div>

    <div class="col-md-6">
        <?= selectField('parcial', getYesNoArr(), $product, ['base-lang' => $baseLang]) ?>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <?= selectField('gateway', \App\Enums\PaymentGatewayEnum::getValuesAsSelect(), $product) ?>
    </div>
    <div class="col-6">
        <?= inputField('ganho_afiliado', 'number', $product) ?>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <?php
        if (isset($product['id'])) {
            $textAreaDesc = "desc-" . $product['id'];
            $val = $product->descricao();
        } else {
            $textAreaDesc = "description";
            $val="";
        }
        ?>
        <?= inputField('description', 'textarea', ['description' => $val], ['class' => 'summernote', 'id' => $textAreaDesc, 'required' => true, 'base-lang' => $baseLang]) ?>

    </div>
</div>
