<?php
$baseLang = 'product';
?>

<div class="row mt-3">
    <div class="col-5">
        <div class="form-group">
            <?= selectField('status', \App\Enums\ProductStatusEnum::getValuesAsSelect(), $product, ['base-lang' => $baseLang]) ?>
        </div>
    </div>
    <div class="col-12 col-md-7">
        <div class="form-group">
            <label for="data_sorteio">Data
                Sorteio</label>
            <input type="datetime-local"
                   class="form-control"
                   name="data_sorteio"
                   id="data_sorteio"
                   value="{{ $product->draw_date ? date('Y-m-d H:i:s', strtotime($product->draw_date)) :date('Y-m-d H:i:s')}}">
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-sm">
        <?php
        $winner = "";
        if (isset($product['winner'])) {
            $winner = $product['winner'];
        }
        ?>
        <?= inputField('cadastrar_ganhador', 'text', ['winner' => $winner], ['base-lang' => $baseLang]) ?>
    </div>
    <div class="col">
        <?= selectField('visible', getYesNoArr(), $product, ['base-lang' => $baseLang]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?= inputField('slug', 'text', $product) ?>
    </div>
</div>
<div class="row mt-3">
    <div class="col">
        <div class="form-group">
            <?php
            $favoritar =[];
            if (isset($product['favoritar'])) {
                $favoritar = ['favoritar_rifa'=>$product['favoritar']];
            }
            ?>
            <?= selectField('favoritar_rifa', getYesNoArr(), $favoritar) ?>

        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col">
        <div class="form-group">

            <?= selectField('tipo_reserva', \App\Enums\RaffleTypeEnum::getValuesAsSelect(), $product) ?>
        </div>
    </div>
</div>
<div class="row mt-1 d-flex justify-content-center">
    <p> Tipo de Rifa </p>
</div>
<div class="row">
    <div class="col">
        <div class="form-group">
            <?php
            $selectData = ['base-lang' => $baseLang];

            if (isset($product['id'])) {
                $selectData['disabled'] = true;
            }
            echo selectField('rifa_numero', \App\Enums\GameModeEnum::getValuesAsSelect(), $product, $selectData);
            ?>

        </div>
    </div>
</div>