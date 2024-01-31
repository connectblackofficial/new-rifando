<form onsubmit="return sendForm($(this));" action="{{ route('product.store') }}" method="POST"
      enctype="multipart/form-data">
    <?php
    $baseLang = 'product';
    $selectData = [];
    ?>
    {{ csrf_field() }}
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <?php
                    echo selectField('modo_de_jogo', \App\Enums\GameModeEnum::getValuesAsSelect(), $product, $selectData);
                    ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <?= selectField('gateway', \App\Enums\PaymentGatewayEnum::getValuesAsSelect(), $product,['onchange'=>'updateGatewayPix()']) ?>
                </div>
            </div>
            <div class="col-md-4 pix-account-container" style="display: none">
                <?= selectField('pix_account_id', \App\Models\PixAccount::getAllAsSelect(), $product) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= inputField('name', 'text', $product) ?>
            </div>

            <div class="col-md-6">
                <?= inputField('subname', 'text', $product) ?>
            </div>


            <div class="row d-flex">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="exampleFormControlFile1">Até 3 Imagens</label>
                        <input type="file" class="form-control-file"
                               name="images[]" accept="image/*" multiple>
                    </div>
                </div>

                <div class="col-md-4">
                    <?= inputField('minimo', 'number', $product, ['min' => 1, 'max' => "999999", 'base-lang' => $baseLang]) ?>
                </div>
                <div class="col-md-4">
                    <?= inputField('maximo', 'number', $product, ['base-lang' => $baseLang]) ?>
                </div>
            </div>


        </div>


        <div class="row mt-2 mb-2">
            <div class="col-md-6">
                <?= inputField('numbers', 'number', $product, ['min' => 1, 'max' => "1000000", 'base-lang' => $baseLang]) ?>
            </div>
            <div class="col-md-6">
                <?= inputField('expiracao', 'number', $product, ['min' => 0, 'base-lang' => $baseLang]) ?>
            </div>

        </div>

        <div class="form-group">
            <?php
            $textAreaDesc = "description";
            $val = "";
            ?>
            <?= inputField('description', 'textarea', ['description' => $val], ['class' => 'summernote', 'id' => $textAreaDesc, 'base-lang' => $baseLang]) ?>

        </div>
        <div class="separator-20" style="height: 20px"></div>
        <div class="row mb-4">
            <button type="submit" class="criar btn btn-lg btn-success"><i class="fa fa-save"></i> Cadastrar</button>
        </div>

    </div>
</form>