<?php
if (isset($product['id'])) {
    $promos = $product->promocoes();
}
if (!isset($product['id']) || !isset($promos) || count($promos) == 0) {
    for ($i = 1; $i <= 4; $i++) {
        $promos[] = [
            'ordem' => $i,
            'qtdNumeros' => 0,
            'desconto' => 0
        ];
    }
}
?>
@foreach($promos as $promo)
    <div class="row text-center mt-2 promo">
        <h5>Promoção {{ $promo['ordem'] }}</h5>
        <div class="col-md-6">
            <label>Qtd de números</label>
            <input type="number" min="0"
                   name="numPromocao[{{ $promo['ordem'] }}]"
                   max="10000"
                   class="form-control text-center"
                   value="{{$promo['qtdNumeros'] }}">
        </div>
        <div class="col-md-6">
            <label
                    for="exampleInputEmail1">% de
                Desconto</label>
            <div class="input-group">
                <div class="input-group-prepend">
                                                                                        <span
                                                                                                class="input-group-text">%</span>
                </div>
                <input type="text"
                       class="form-control text-center"
                       name="valPromocao[{{ $promo['ordem'] }}]"
                       value="{{ $promo['desconto']}}">
            </div>
        </div>
    </div>
@endforeach

