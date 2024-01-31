@extends('layouts.app')


@section('content')
    <div class="container app-main" id="app-main">
        <br>
        <div class="row justify-content-center">
            <div class="col-md-6 col-12 rifas {{ $config->tema }}">
                <section class="title-payment-container mt-2">
                    <section class="title-payment-content">
                        <section class="title-payment-icon">
                            <i class="far fa-check-circle" id="payment-icon" style="color: #ffc107"></i>
                        </section>
                        <section class="title-payment-texts mt-3 ml-3">
                            <h2 class="title-payment-text {{ $config->tema }}" id="payment-text">Aguardando
                                Pagamento!</h2>
                            <p class="title-payment-sub {{ $config->tema }}" id="payment-sub">Finalize o pagamento.</p>
                        </section>
                    </section>
                </section>
                <div class="progress_reserva d-none">
                    <p class="desc"><b>Tempo restante para pagamento: </b></p>
                    <span id="cpclock">
                        <span id="cpminutes"></span>:<span id="cpseconds"></span>
                    </span>
                    <div class="progress" role="progressbar" aria-label="Animated striped example" aria-valuenow="100"
                         aria-valuemin="0" aria-valuemax="100">
                        <div id="cpprogress" class="progress-bar progress-bar-striped progress-bar-animated"
                             style="width: 100%"></div>
                    </div>
                </div>

                @if ($rifa->expiracao > 0)
                    <div class="progress_reserva text-center" id="progress-bar">
                        <p class="desc"><b>Tempo restante para pagamento: </b></p>
                        <span id="qrclock">
                            <span id="qrminutes"></span> : <span id="qrseconds"></span>
                        </span>
                        <div class="progress" role="progressbar" aria-label="Animated striped example"
                             aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100">
                            <div id="qrprogress" class="progress-bar progress-bar-striped progress-bar-animated"
                                 style="width: 100%"></div>
                        </div>
                    </div>
                @endif


                <div id="divCart" class="card-rifa-destaque payment-card {{ $config->tema }}">
                    <label>
                        <span class="badge bg-success">1</span>
                        Copie o código PIX abaixo.
                    </label>

                    <div class="" style="display: flex;justify-content: center;">
                        <input type="text" readonly
                               style="width: 100%; height: 40px;background-color: #fff;border: 1px solid #000;border-style: solid;border-radius: 5px;color: #000;"
                               id="brcodepix" value="{{ $codePIX }}"></input>
                        <button type="button" id="clip_btn" class="btn blob bg-success"
                                style="color: #fff;font-weight: bold;min-width: 130px;margin-left:5px; height: 40px;"
                                data-toggle="tooltip" data-placement="top" title="COPIAR" onclick="copyPix()">
                            COPIAR</i></button>
                    </div>

                    <label class="mt-2">
                        <span class="badge bg-success">2</span>
                        Abra o app do seu banco e escolha a opção PIX, como se fosse fazer uma transferência.
                    </label>

                    <label class="mt-2">
                        <span class="badge bg-success">3</span>
                        Selecione a opção PIX copia e cola, cole a chave copiada e confirme o pagamento.
                    </label>

                    <div class="" style="margin-top: 20px;text-align: center;">
                        @if(filter_var($qrCode, FILTER_VALIDATE_URL))
                            <img src="{{ $qrCode }}" style="width: 50%;">
                        @else
                            <img src="data:image/jpeg;base64,{{ $qrCode }}" style="width: 50%;">
                        @endif
                    </div>

                    <div class="text-center">
                        <h5>QR Code</h5>

                        <label>
                            Acesse o APP do seu banco e escolha a opção pagar com QR Code, escaneie o código acima e
                            confirme o pagamento.
                        </label>
                    </div>
                </div>
                @if ($rifaDestaque)
                    <a href="{{ route('product', ['slug' => $rifaDestaque->slug]) }}">
                        <div class="card-rifa {{ $config->tema }}">
                            <div class="img-rifa">
                                <img src="{{$rifaDestaque->getDefaultImageUrl()}}" alt="" srcset="">
                            </div>
                            <div class="title-rifa title-rifa-destaque {{ $config->tema }}">

                                <h1>{{ $rifaDestaque->name }}</h1>
                                <p>{{ $rifaDestaque->subname }}</p>

                                <div style="width: 100%;">
                                    {!! $rifaDestaque->status() !!}
                                    @if ($rifaDestaque->draw_date)
                                        <br>
                                        <span class="data-sorteio {{ $config->tema }}" style="font-size: 12px;">
                                            Data do sorteio {{ date('d/m/Y', strtotime($rifaDestaque->draw_date)) }}
                                            {{-- {!! $product->dataSorteio() !!} --}}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endif


                <div class="card-rifa-destaque detalhes-compra {{ $config->tema }}">
                    <label>
                        <i class="fas fa-info-circle"></i>&nbsp; Detalhes da sua compra
                    </label>
                    <br>
                    <label>
                        <strong>Ação: </strong> {{ $participante->rifa()->name }}
                    </label>
                    <br>
                    <label>
                        <strong>Comprador: </strong> {{ $participante->name }}
                    </label>
                    <br>
                    <label>
                        <strong>Telefone: </strong> {{ $participante->telephone }}
                    </label>
                    <br>
                    <label>
                        <strong>Pedido: </strong> #{{ $participante->id }}
                    </label>
                    <br>
                    <label>
                        <strong>Data/horário: </strong> {{ date('d/m/Y H:i', strtotime($participante->created_at)) }}
                    </label>
                    <br>
                    <label>
                        <strong>Expira Em: </strong> {{ date('d/m/Y H:i', strtotime($participante->expiracao())) }}
                    </label>
                    <br>
                    <label>
                        <strong>Situação: </strong> {{ $participante->status() }}
                    </label>
                    <br>
                    <label>
                        <strong>Quantidade: </strong> {{ count($participante->numbers()) }}
                    </label>
                    <br>
                    <label>
                        <strong>Total: </strong> {{formatMoney($price)}}
                    </label>
                    <br>
                    <label>
                        <strong>Cotas: </strong>
                        @if ($rifa->modo_de_jogo == 'numeros')
                            @foreach ($participante->numbers() as $key => $number)
                                @if ($key > 0)
                                    ,
                                @endif
                                {{ $number }}
                            @endforeach
                        @else
                            @foreach ($participante->reservados() as $key => $number)
                                @if ($key > 0)
                                    ,
                                @endif
                                {{ $number->grupoFazendinha() }}
                            @endforeach
                        @endif
                    </label>
                </div>


            </div>
        </div>
        <br>
        @include('layouts.footer')
    </div>

    <br><br>
    @section("scripts-footer")
        <script>


            let freezeTimmer = new Date().setMinutes(new Date().getMinutes());
            let countdownDate = new Date().setMinutes(new Date().getMinutes() + {!! $minutosRestantes !!})
            let countDifference = countdownDate - freezeTimmer;
            let timerInterval;
            const minutesElem = document.querySelector("#cpminutes"),
                secondsElem = document.querySelector("#cpseconds"),
                qRminutesElem = document.querySelector("#qrminutes"),
                qRsecondsElem = document.querySelector("#qrseconds"),
                timerRunnigContent = document.querySelector("#divCart"),
                timerEndContent = document.querySelector("#divPixTimeOut"),
                cpProgressElementBar = document.querySelector("#cpprogress"),
                qrProgressElementBar = document.querySelector("#qrprogress");

            const formatZero = (time) => {
                let dateFormated,
                    calculated = Math.floor(Math.log10(time) + 1);

                if (calculated < 1) {
                    dateFormated = `<span>0${time}</span>`;
                }
                if (calculated === 1) {
                    dateFormated = `<span>0${time}</span>`;
                }
                if (calculated > 1) {
                    dateFormated = `<span>${time}</span>`;
                }

                return dateFormated;
            }

            const progressBarPercent = (difference, timeTotal) => {
                let color;
                let percent = Math.floor((difference * 100) / timeTotal);
                switch (percent) {
                    case 100:
                        color = "bg-success";
                        break;
                    case 55:
                        color = "bg-info";
                        break;
                    case 35:
                        color = "bg-warning";
                        break;
                    case 25:
                        color = "bg-danger";
                        break;
                }

                return data = new Array(percent, color);
            }

            const startCountdown = () => {
                const now = new Date().getTime();
                const countdown = new Date(countdownDate).getTime();
                const difference = (countdown - now) / 1000;

                let countedDifference = Math.floor(difference) * 1000;
                let countedFinalTimmer = Math.floor(countdownDate - freezeTimmer);
                let progressBar = progressBarPercent(countedDifference, countedFinalTimmer);


                if (difference < 1) {
                    endCountdown();
                }

                let days = Math.floor((difference / (60 * 60 * 24)));
                let hours = Math.floor((difference % (60 * 60 * 24)) / (60 * 60));
                let minutes = Math.floor((difference % (60 * 60)) / 60);
                let seconds = Math.floor(difference % 60);

                minutesElem.innerHTML = formatZero(minutes);
                secondsElem.innerHTML = formatZero(seconds);
                qRminutesElem.innerHTML = formatZero(minutes);
                qRsecondsElem.innerHTML = formatZero(seconds);
                cpProgressElementBar.setAttribute("aria-valuenow", progressBar[0]);
                qrProgressElementBar.setAttribute("aria-valuenow", progressBar[0]);
                cpProgressElementBar.classList.add(progressBar[1]);
                qrProgressElementBar.classList.add(progressBar[1]);
                cpProgressElementBar.style.width = progressBar[0] + '%'
                qrProgressElementBar.style.width = progressBar[0] + '%'


            }

            const endCountdown = () => {
                document.getElementById("divCart").classList.add('d-none');
                document.getElementById('payment-icon').style.color = 'red';
                document.getElementById('payment-icon').classList = 'fas fa-times-circle';
                document.getElementById('payment-text').innerHTML = 'RESERVA EXPIRADA!'
                document.getElementById('payment-sub').innerHTML = 'Realize uma nova reserva!'
                document.getElementById('cotas-pending').innerHTML = "Expiradas";
                document.getElementById("progress-bar").classList.add('d-none');
                clearInterval(timerPix);
                clearInterval(timerInterval);
                document.getElementById("progress-bar").classList.add('d-none');

                clearInterval(timerInterval);
                timerRunnigContent.className = 'hidden';
                timerEndContent.style.display = 'block';
            }

            var expiracao = {{ $rifa->expiracao }};
            window.addEventListener('load', () => {
                if (expiracao > 0) {
                    startCountdown();
                    timerInterval = setInterval(startCountdown, 1000);
                }
            });


            $(document).ready(function () {
                let timerPix = setInterval(function checkPixSuccess() {
                    $.ajax({
                        url: "{{ route('findPixStatus', $codePIXID . '-' . $productID) }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Content-Type': 'application/json'
                        },
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            'id': "{{ $codePIXID }}",
                            'product_id': "{{ $productID }}"

                        },

                        success: function (data) {
                            if (data.status === true) {
                                document.getElementById("divCart").classList.add('d-none');
                                document.getElementById('payment-icon').style.color = 'green';
                                document.getElementById('payment-text').innerHTML =
                                    'PAGAMENTO CONFIRMADO!'
                                document.getElementById('payment-sub').innerHTML = 'Boa Sorte !'
                                document.getElementById('cotas-pending').innerHTML = data.cotas;
                                clearInterval(timerPix);
                                clearInterval(timerInterval);
                                document.getElementById("progress-bar").classList.add('d-none');

                            }
                        }
                    });

                }, 2000);
            });
        </script>

    @endsection
@endsection
