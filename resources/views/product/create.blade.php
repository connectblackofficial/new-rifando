<form onsubmit="return sendForm($(this));"  action="{{ route('product.create') }}" method="POST"
      enctype="multipart/form-data">
    <?php
    $baseLang = 'product';
    $selectData=[];
    ?>
    {{ csrf_field() }}
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
                <?= selectField('gateway', \App\Enums\PaymentGatewayEnum::getValuesAsSelect(), $product) ?>
            </div>
        </div>

        <div class="col-md-4">
            <?= inputField('price', 'numeric', $product) ?>
        </div>

        <div class="col-md-6">
            <?= inputField('name', 'text', $product) ?>
        </div>

        <div class="col-md-6">
            <?= inputField('subname', 'text', $product) ?>
        </div>

        <div class="row d-flex">
            <div class="col-md-6">
                <?= inputField('qtd_zeros', 'numeric', $product) ?>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlFile1">Até 3 Imagens</label>
                    <input type="file" class="form-control-file"
                           name="images[]" accept="image/*" multiple required>
                </div>
            </div>
        </div>


    </div>

    <div class="row mt-2 mb-2">
        <div class="col-md-6">
            <?= inputField('minimo', 'number', $product, ['min' => 1, 'max' => "999999", 'base-lang' => $baseLang]) ?>
        </div>
        <div class="col-md-6">
            <?= inputField('maximo', 'number', $product, ['base-lang' => $baseLang]) ?>
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
        <?= inputField('description', 'textarea', ['description' => $val], ['class' => 'summernote', 'id' => $textAreaDesc, 'required' => true, 'base-lang' => $baseLang]) ?>

    </div>

    <hr>
    <center>
        <h4>Prêmios</h4>
    </center>

    <div class="row mb-4">
        @for($i=1; $i<=10; $i++)
            <div class="col-md-6 mt-2">
                <label><?= $i ?>º Prêmio</label>
                <input type="text" class="form-control" name="premio<?=$i?>">
            </div>
        @endfor


    </div>

    <button type="submit" class="criar btn btn-success">Criar</button>

</form>