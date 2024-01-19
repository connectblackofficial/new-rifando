<?php
if (isset($product['id'])) {
    $premios = $product->premios();
}
if (!isset($product['id']) || !isset($premios) || count($premios) == 0) {
    for ($i = 1; $i <= 10; $i++){
        $premios[] = [
            'ordem' => $i,
            'descricao' => ''
        ];
    }
}
?>
@foreach ($premios as $premio)
    <div class="col-md-6 mt-2">
        <label>{{ $premio['ordem']}}º Prêmio</label>
        <input type="text" class="form-control" name="descPremio[{{ $premio['ordem']}}]"
               value="{{ $premio['descricao'] }}">
    </div>
@endforeach