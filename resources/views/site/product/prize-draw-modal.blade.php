<div class="col-md-12 text-center">
    Estes são os prêmios disponíveis no sorteio <strong>{{ $productName}}</strong>
</div>
<hr>
@foreach ($prizeDraws as $premio)
    <div class="row mt-4">
        <div class="col-md-12 text-center">
            <label><strong>Prêmio {{ $premio['ordem'] }}: </strong>{{ $premio['descricao'] }}</label>
        </div>
    </div>
@endforeach