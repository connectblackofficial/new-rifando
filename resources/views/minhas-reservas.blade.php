@extends('layouts.app')

@section('content')

    <div class="container app-main" id="app-main">

        <div class="row justify-content-center">
            <div class="col-md-6 col-12 rifas {{ $config->tema }}">
                <div class="app-title {{ $config->tema }}">
                    <h1>🛒 Compras</h1>
                    <div class="app-title-desc {{ $config->tema }}">recentes</div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <label style="color: #fff">Selecione a Rifa</label>
                        <select name="" id="" class="form-control" onchange="showHideReservas(this)">
                            <option value="0">Mostrar Todas</option>
                            @foreach ($rifas as $key => $rifa)
                                <option value="{{ $key }}">{{ $rifa }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                @foreach ($reservas as $reserva)
                        <?php
                        /** @var \App\Models\Product $rifa */
                        $rifa = $reserva->rifa();
                        ?>
                    <div class="card app-card mb-2 pointer border-bottom-warning row-rifa rifa-{{ $rifa->id }}">
                        <div class="card-body">
                            <div class="row align-items-center row-gutter-sm">
                                <div class="col-auto">
                                    <div class="position-relative rounded-pill overflow-hidden box-shadow-08"
                                         style="width: 56px; height: 56px;">
                                        <div
                                                style="display: block; overflow: hidden; position: absolute; inset: 0px; box-sizing: border-box; margin: 0px;">
                                            <img alt="" src="{{$rifa->getDefaultImageUrl()}}"
                                                 decoding="async" data-nimg="fill"
                                                 style="position: absolute; inset: 0px; box-sizing: border-box; padding: 0px; border: none; margin: auto; display: block; width: 0px; height: 0px; min-width: 100%; max-width: 100%; min-height: 100%; max-height: 100%;">
                                            <noscript></noscript>
                                        </div>
                                    </div>
                                </div>
                                <div class="col ps-2"><small
                                            class="compra-data font-xss opacity-50">{{ date('d/m/Y H:i', strtotime($reserva->created_at)) }}</small>
                                    <div class="compra-title font-weight-500">{{ $rifa->name }}</div>
                                    <small
                                            class="font-xss opacity-75 text-uppercase">{{ $reserva->status() }}
                                        ({{ $reserva->pagos + $reserva->reservados }} COTAS)</small>
                                    @if ($reserva->pagos > 0)
                                        <div class="compra-cotas font-xs" style="max-height: 200px;overflow: auto;">
                                            @if ($rifa->modo_de_jogo == 'numeros')
                                                @foreach ($reserva->pagos() as $numPago)
                                                    <span class="badge bg-success me-1">{{ $numPago }}</span>
                                                @endforeach
                                            @else
                                                @foreach ($reserva->pagos() as $numPago)
                                                    <span
                                                            class="badge bg-success me-1">{{ $numPago->grupoFazendinha() }}</span>
                                                @endforeach
                                            @endif

                                        </div>
                                    @else
                                        <div class="compra-cotas font-xs" style="max-height: 200px;overflow: auto;">
                                                @foreach ($reserva->reservados() as $numRes)
                                                    <span class="badge bg-success me-1">{{ $numRes }}</span>
                                                @endforeach


                                        </div>
                                        @if ($reserva->qtdReservados() > 0)
                                            <br>
                                            <a href="{{ route('pagarReserva', $reserva->id) }}">
                                                <div class="col-12 pt-2">
                                                    <span class="btn btn-warning btn-sm p-1 px-2 w-100 font-xss">Efetuar
                                                        pagamento <i class="bi bi-chevron-right"></i></span>
                                                </div>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
        @include('layouts.footer')
    </div>

    <br><br>

@endsection
