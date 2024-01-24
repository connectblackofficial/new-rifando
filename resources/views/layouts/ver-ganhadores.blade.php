<div class="row">
    <div class="col-3">
        <?php
        /** @var \App\Models\Product $rifa */
        ?>
        <img src="{{ $rifa->getDefaultImageUrl()}}" width="100%" style="border-radius: 10px;">
    </div>
    <div class="col-9">
        <h3>{{ $rifa->name }}</h3>
        <h6><strong>Data do sorteio: </strong>{{ date('d/m/Y', strtotime($rifa->draw_date)) }}</h6>
    </div>
</div>

@foreach ($rifa->prizeDraws()->where('descricao', '!=', '')->whereNotNull('participant_id')->get() as $premio)
        <?php
        /** @var \App\Models\PrizeDraw $premio */
        $participante = $premio->participant();
        ?>
    <hr>
    <div class="row mt-2">
        <div class="col-1">
            <h1 style="font-size: 50px;">
                {{ $premio->ordem }}
            </h1>
        </div>
        <div class="col-9" style="font-size: 14px; line-height: 12px;">
            <label>Nome: {{ $premio->ganhador }}</label> <br>
            <label>
                Telefone:
                <span id="tel-hide-{{ $premio->id }}">{{ substr($premio->telefone, 0, 4) }} *****-****</span>
                <span class="d-none" id="tel-show-{{ $premio->id }}">{{ $premio->telefone }}</span>
                <i class="far fa-eye" id="eye-show" onclick="toggleTelefone('{{ $premio->id }}')"
                   style="cursor: pointer"></i>
                <i class="far fa-eye-slash d-none" id="eye-hide" onclick="toggleTelefone('{{ $premio->id }}')"
                   style="cursor: pointer"></i>
            </label>
            <br>
            <label>Status: {!! $participante->statusBadge() !!}</label> <br>
            <label>Rifa: {{ $rifa->name }}</label> <br>
            <label>Data da Compra: {{ date('d/m/Y H:i', strtotime($participante->created_at)) }}</label> <br>
            <label> Número Sorteado:&nbsp;</label><span class="badge bg-success"> {{ $premio->cota }}</span> <br>
            <label>Valor Pago: </label> <span
                    class="badge bg-primary">{{ formatMoney($participante->valor)}}</span>
            <br>
            <label>{{ $participante->pagos + $participante->reservados }} Bilhete(s)
                comprados</label> <br>

            <label>Prêmio: {{ $premio->descricao }}</label> <br>
            <a href="{{ $premio->linkWpp() }}" target="_blank" class="btn btn-sm btn-success"
               style="font-size: 12px;"><i class="fab fa-whatsapp"></i>&nbsp; ENTRAR EM CONTATO</a>
        </div>
    </div>
@endforeach

