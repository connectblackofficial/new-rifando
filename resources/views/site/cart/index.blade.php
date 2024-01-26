<div class="row justify-content-center">
    <div class="col-md-12 col-9" style="background-color: #fff; color: #000; border-radius: 10px;">
        <div class="container">
            @if($game_mode==\App\Enums\GameModeEnum::Numbers)
                <div class="row">
                    <div class="col-12 text-center" style="width: 100%">
                        @include("site.layouts.parts.number-selected",['numbers' => $numbers,'hasClickBtn' => true])
                    </div>
                </div>
            @endif
            <div class="row"
                 style="text-align: center;background-color: #fff; margin-top: 5px; justify-content-center; margin-bottom: 10px;">
                <div class="col-12 d-flex justify-content-center">

                    <div class="col-md-8">
                        <button type="button" class="btn reservation blob"
                                style="border: none;color: #fff;font-weight: bold;width: 100%;background-color: green"
                                onclick="openModalCheckout()"><i
                                    class="far fa-check-circle"></i>&nbsp;Participar do sorteio
                            <span style="font-size: 14px; float:right">
                                    <span id="numberSelectedTotal">
<?= $formated_total ?>
                                    </span>
                                </span>
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger " onclick="return destroyCart()"
                                style="border: none;color: #fff;font-weight: bold;width: 80%; margin-top: 7px"><i
                                    class="fa fa-trash"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
