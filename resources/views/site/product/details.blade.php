@extends('layouts.app')
<?php
$imageUrl = null;
if (isset($imagens[0])) {
    $imageUrl = imageAsset($imagens[0]);
}
?>
@if (isset($product))
    @section('title', $product->name)
    @section('description', '')
    @section('ogTitle', $product->name)
    @section('ogUrl', url(''))
    @if(!is_null($imageUrl))
        @section('ogImage', $imageUrl)
    @endif
    @section('sidebar')
        @section('ogContent')
            <meta property="og:title" content="{{ $product->name }}">
            <meta property="og:description" content="{{ $product->subname }}">
            @if(!is_null($imageUrl))
                <meta property="og:image" itemprop="image" content="{{$imageUrl}}">
            @endif
            <meta property="og:type" content="website">
        @endsection
    @stop
    @section('content')
        @include("site.product.product-header")

        @include("site.product.product-alerts")

        <div class="container detail">
            <input type="hidden" id="product-name" value="{{ $productModel->name }}">
            <div class="row justify-content-center">
                <div class="col-md-8 rifa-content {{ $config->tema }}">
                    <input type="hidden" id="raffleType" value="{{ $productModel->type_raffles }}">
                    <input type="hidden" id="modoDeJogo" value="{{ $productModel->modo_de_jogo }}">
                    @include('site.product.common')
                    @if ($product->status == 'Finalizado')
                        @include('site.product.finished')
                    @else
                        @include('site.product.actives')
                        @if ($productModel->modo_de_jogo == 'fazendinha-completa' || $productModel->modo_de_jogo == 'fazendinha-meio')
                            @if ($productModel->modo_de_jogo == 'fazendinha-completa')
                                @include('rifas.fazendinha')
                            @else
                                @include('rifas.fazendinha-meia')
                            @endif
                        @else
                            @if ($type_raffles == 'automatico' || $type_raffles == 'mesclado')
                                @include('rifas.automatico')
                            @endif
                            @if ($type_raffles == 'manual' || $type_raffles == 'mesclado')
                                @include('rifas.manual')
                            @endif
                        @endif
                    @endif


                </div>
            </div>

            <br>
            @include('layouts.footer')
        </div>
        <div class="d-flex justify-content-center">
            <div class="payment" id="payment" style="display: none; width: 500px !important;margin-bottom: 10px;">

            </div>
        </div>

        @include("site.checkout.modal")
        @include('site.product.modals')

        @section("scripts-footer")
            <script>
                const numbersManual = [];
                const valuePrices = "{{ $product->price }}";
                const descontos = '{!!$activePromos!!}'
                const totalOnCart = '{!!$cart->total!!}'

                let total;
                var avaliableNums = parseInt('{{$totalDispo}}');
                $(document).ready(function () {

                    productDetailPage();
                    if (totalOnCart > 0) {
                        showCart();
                    }

                });

            </script>
        @endsection

        @endif
    @stop
