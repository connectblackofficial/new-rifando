
@foreach ($product->getFormatedPrizeDraws() as $premio)
    <div class="col-md-6 mt-2">
        <label>{{ $premio['ordem']}}º Prêmio</label>
        <input type="text" class="form-control" name="descPremio[{{ $premio['ordem']}}]"
               value="{{ $premio['descricao'] }}">
    </div>
@endforeach