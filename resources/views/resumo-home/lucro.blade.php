@extends('layouts.admin')
@section("scripts-top")
    <style>
        .item-compra {
            border: 1px solid;
            color: white;
            background-color: grey;
            border-radius: 5px;
            /* border-radius: 10px; */
        }

        .reservado {
            /* background-color: rgb(68, 124, 170); */
        }

        .pago {
            background-color: rgb(17, 109, 17);
        }

        .qtd-livres {
            padding-left: 10px !important;
            padding-right: 10px !important;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }

        .qtd-pagos {
            padding-left: 10px !important;
            padding-right: 10px !important;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        .qtd-reservas {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        .info-qtd {
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
    <div class="container" style="max-width:100%;min-height:100%;">
        <div class="col-md-12 text-center">
            <h4>Resumo Lucro</h4>
            <h6>Participantes: {{ $participantes->count() }}</h6>
            <h6>Total de Cotas: {{ $participantes->sum('pagos') }}</h6>
            <h6>Total: {{formatMoney($participantes->sum('valor'))}}</h6>
        </div>


        @foreach ($participantes as $participante)
                <?php
                /** @var \App\Models\Product $rifa */
                $rifa = $participante->rifa();
                $image = $rifa->getDefaultImageUrl();
                ?>
            <div class="row p-1 item-compra reservado">
                <div class="col-md-1">
                    <img class="rounded" src="<?=$image?>" width="80">
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <label>
                        <span class="bg-success">Rifa:</span> {{ $rifa->name }} <br>
                        <span class="bg-success">Participante:</span> {{ $participante->name }}

                    </label>
                </div>
                <div class="col-md-4 d-flex align-items-center">
                    <span>
                        {{ count($participante->numbers()) }} Cotas <br>
                        {{ formatMoney($participante->valor) }}
                    </span>
                </div>
            </div>
        @endforeach

        {{ $participantes->links() }}
    </div>
@endsection
