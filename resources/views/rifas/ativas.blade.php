@if (count($ranking) > 0)
    <div class="card" style="border: none;border-radius: 10px;background-color: transparent;">
        <div class="card-body body-ranking {{ $config->tema }}">
            <div class="title-ranking {{ $config->tema }}" style="margin-bottom: 10px;">
                <h5 style="font-weight: bold;">RANKING DE COMPRADORES</h5>
            </div>


            <div class="row" style="display: flex;justify-content:center;position:relative">
                @foreach ($ranking as $key => $rk)
                    <div class="btn-auto item-ranking">
                        {{ $key + 1 }}º {{ $productModel->medalhaRanking($key) }}<br>
                        <span style="font-size: 20px;font-weight: bold;">{{ $rk->name }}</span>
                        <br>
                        <span style="font-size: 12px;">Qtd. de Bilhetes
                            {{ $rk->totalReservas }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

{{-- Promoções --}}
@if ($productModel->promos()->where('qtdNumeros', '>', 0)->count() > 0)
    <div class="" style="">
        <h5 class="mt-1 title-promo {{ $config->tema }}">
            📣 Promoção
            <small class="text-muted title-promo {{ $config->tema }}" style="font-size: 15px;">Compre mais
                barato!</small>
        </h5>
    </div>
    <div class="card" style="border: none;border-radius: 10px;background-color: transparent; margin-top: -15px;">
        <div class="card-body body-promo {{ $config->tema }}">
            <div class="row">
                @foreach ($productModel->promos()->where('qtdNumeros', '>', 0) as $promo)
                    @if ($productModel->type_raffles == 'manual')
                        <div class="col-6" style="margin-bottom: 8px;" onclick="infoPromo()">
                            <div class="bg-success" style="color: #fff;text-align: center;border-radius:6px;"><strong>
                                    {{ $promo->qtdNumeros }} POR - R$:
                                    {{ $promo->valorFormatted() }}</strong>
                            </div>
                        </div>
                    @else
                        <div class="col-6" style="margin-bottom: 8px;"
                             onclick="addQtd('{{ $promo->qtdNumeros }}', '{{ $promo->valorFormatted() }}')">
                            <div class="bg-success" style="color: #fff;text-align: center;border-radius:6px;"><strong>
                                    {{ $promo->qtdNumeros }} POR - R$:
                                    {{ $promo->valorFormatted() }}</strong>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endif

{{-- Ver meus premios e Parcial --}}
<div class="mb-3">

    <div class="row mt-2">
        <div class="" style="">
            <h5 class="mt-1 title-promo {{ $config->tema }}">
                ⚡ Cotas
                <small class="text-muted title-promo {{ $config->tema }}" style="font-size: 15px;">Escolha a quantidade
                    da sua sorte</small>
            </h5>
        </div>

        <div class="col-md-12 mb-1">
            <button type="button" onclick="return participantCheck()"
                    class="btn btn-secondary btn-sm bg-success btn-block"
                    style="font-size: 12px; width: 100%; ">
                <i class="fas fa-shopping-cart"></i>&nbsp;Ver meus números
            </button>
        </div>

        @if (count($ranking) > 0)
            <div class="col-md-12 mb-1">
                <button type="button" data-bs-toggle="modal" data-bs-target="#modal-ranking"
                        class="btn btn-secondary btn-sm bg-secondary btn-block" style="width: 100%; font-size: 12px; ">
                    <i class="fas fa-trophy"></i>&nbsp;Top Compradores
                </button>
            </div>
        @endif
        <div class="col-md-12 mb-1">
            <button type="button" data-bs-toggle="modal" data-bs-target="#modal-premios"
                    class="btn btn-secondary btn-sm bg-warning" style="width: 100%; font-size: 12px; ">
                <i class="fas fa-trophy"></i>&nbsp;Prêmios
            </button>
        </div>

    </div>

    @if ($productModel->parcial)
            <?php
            $porcentagem = $productModel->porcentagem();
            ?>
        @if($porcentagem>0)

            <div class="card"
                 style="border: none;border-radius: 10px;background-color: transparent; margin-top: -10px !important; margin-bottom: -20px;">
                <div class="card-body body-compra-auto {{ $config->tema }}" style="">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="progress-sell">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                         role="progressbar" style="width: {{ $porcentagem }}%"
                                         aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                                        {{ $porcentagem }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
