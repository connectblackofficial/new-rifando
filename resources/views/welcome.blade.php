@extends('layouts.app')
@section("scripts-top")
    <script type="text/javascript" src="sw.js"></script>
    <style>
        body {
            background: #000 !important;
        }
        @media only screen and (-webkit-min-device-pixel-ratio: 1) {

            ::i-block-chrome,
            .app-main {
                margin-top: 100px !important;
            }
        }

        .app-main {
            border-top-right-radius: 20px;
            border-top-left-radius: 20px;
            max-width: 600px;
            margin-top: 40px;
            margin-bottom: 50px;
            border-bottom-right-radius: 20px;
            border-bottom-left-radius: 20px;
        }

        .app-main a {
            text-decoration: none;
        }

        .app-main a:hover {
            text-decoration: none;
        }

        .app-title {
            display: flex;
            align-items: self-end;
            padding-bottom: 10px;
        }

        .app-title h1 {
            color: rgba(0, 0, 0, .9);
            padding-right: 5px;
            font-weight: 600;
            font-size: 1.3em;
            margin: 0;
            padding-top: 10px;
        }

        .app-title .app-title-desc {
            color: rgba(0, 0, 0, .5);
            padding-top: 6px;
            font-size: .9em;
        }


        /* *************************************************************** */
        /* Card Rifa em Destaque */
        /* *************************************************************** */
        .rifas {
            background: #e4e4e4;
            border-top-right-radius: 20px;
            border-top-left-radius: 20px;
            position: absolute;
            border-bottom-right-radius: 20px;
            border-bottom-left-radius: 20px;
            min-height: 100vh;
        }

        .rifa-dark {
            background-color: #383838;
        }

        .card-rifa-destaque .img-rifa-destaque img {
            width: 100%;
            height: 290px;
            border-top-right-radius: 20px;
            border-top-left-radius: 20px;
        }

        .card-rifa-destaque {
            border-top-right-radius: 20px;
            border-top-left-radius: 20px;
            padding-bottom: 10px;
            background: #fff;
            margin-bottom: 10px;
            border-bottom-right-radius: 20px;
            border-bottom-left-radius: 20px;
        }

        .title-rifa-destaque {
            padding-top: 5px;
            padding-left: 10px;
        }

        .title-rifa-destaque h1 {
            color: #202020;
            -webkit-line-clamp: 2 !important;
            margin-bottom: 1px;
            font-weight: 700;
            font-size: 19px;
            letter-spacing: -.2px;
        }

        .title-rifa-destaque p {
            color: rgba(0, 0, 0, .7);
            font-size: .75em;
            max-width: 96%;
            margin: 0;
        }

        /* *************************************************************** */


        /* *************************************************************** */
        /* Card Rifa Normal */
        /* *************************************************************** */
        .card-rifa img {
            width: 300px;
            border-radius: 10px;
        }

        .card-rifa {
            background: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            display: flex
        }

        .title-rifa {
            margin-left: 15px;
            width: 100%;
        }

        .blink {
            margin-top: 5px;
            animation: animate 1.5s linear infinite;
        }


        @keyframes animate {
            0% {
                opacity: 0;
            }

            50% {
                opacity: 0.7;
            }

            100% {
                opacity: 0;
            }
        }
    </style>

@endsection
@section('content')
    <style>
        .duvida {
            background-color: #ffffff5e;
            border-radius: 10px;
            height: 60px;
            align-items: center;
            justify-content: center;
            margin-top: 7px;
            cursor: pointer;
        }

        .icone-duvidas {
            width: 50px;
            justify-content: center;
            align-items: center;
            background-color: #b9b9b9;
            height: 35px;
            border-radius: 10px;
            text-align: center;
            font-size: 20px;
        }

        .text-duvidas {
            display: flex !important;
            flex-direction: column;
            justify-content: center
        }

        .f-15 {
            font-size: 15px;
        }

        .f-12 {
            font-size: 12px;
        }

        .data-sorteio {
            /* float: right; */
            padding-right: 10px;
            font-weight: thin;
            text-align: center;
            /* margin-top: 10px; */
            color: #000;
        }

        .rifas.dark {
            background: #383838;
        }

        .app-title.dark h1 {
            color: #fff;
        }

        .app-title-desc.dark {
            color: #fff;
        }

        .card-rifa-destaque.dark {
            background: #222222;
        }

        .title-rifa-destaque.dark h1 {
            color: #fff;
        }

        .title-rifa-destaque.dark p {
            color: #fff;
        }

        .card-rifa.dark {
            background: #222222;
        }

        .text-duvidas.dark h6 {
            color: #fff;
        }

        .text-duvidas.dark p {
            color: #fff !important;
        }

        .data-sorteio.dark {
            color: #fff !important;
        }

        .app-title.dark {
            color: #fff;
        }
    </style>

    <!-- <img src="https://storage.googleapis.com/portal-da-promo/L01_banner_promocaokibon-tudoviraverao-20211638483979877.jpg" class="rounded-md"> -->
    <img src="https://eemkt.svicente.com.br/wp-content/themes/svicente/img/renovasuacasa/banner2-desktop.webp"
         class="rounded-md" style="filter: hue-rotate(15deg);">


    <div class="row" style="margin-top: 25px; margin-bottom: 25px;">
        <div class="col-md-3">
            <div class="d-flex" style="column-gap: 15px; background: #ffffff9c; border-radius: 10px; padding: 10px;">
                <div class="step"
                     style="width: 70px;height: 70px;background: #0000000d;border-radius: 50px;font-size: 25px;text-align: center;padding: 10px;">
                    <img src="https://cdn.icon-icons.com/icons2/989/PNG/512/Ribbon_Purple_icon-icons.com_75198.png">
                </div>
                <div style="display: grid; align-content: center;">
                    <p style="margin-bottom: 0; font-weight: 700;">Escolha o sorteio</p>
                    <p style="margin-bottom: 0; font-size: 12px;">Escolha sua sorte</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="d-flex" style="column-gap: 15px; background: #ffffff9c; border-radius: 10px; padding: 10px;">
                <div class="step"
                     style="width: 70px;height: 70px;background: #0000000d;border-radius: 50px;font-size: 25px;text-align: center;padding: 10px;">
                    <img src="https://cdn.icon-icons.com/icons2/989/PNG/512/Ribbon_Purple_icon-icons.com_75198.png">
                </div>
                <div style="display: grid; align-content: center;">
                    <p style="margin-bottom: 0; font-weight: 700;">Compre seus números</p>
                    <p style="margin-bottom: 0; font-size: 12px;">Escolha sua sorte</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="d-flex" style="column-gap: 15px; background: #ffffff9c; border-radius: 10px; padding: 10px;">
                <div class="step"
                     style="width: 70px;height: 70px;background: #0000000d;border-radius: 50px;font-size: 25px;text-align: center;padding: 10px;">
                    <img src="https://t4.ftcdn.net/jpg/05/42/44/99/360_F_542449981_MUnHerAwhqwrIsi1TTkTjSJgt1M8ZpxX.png">
                </div>
                <div style="display: grid; align-content: center;">
                    <p style="margin-bottom: 0; font-weight: 700;">Pague o QR Code</p>
                    <p style="margin-bottom: 0; font-size: 12px;">Escolha sua sorte</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="d-flex" style="column-gap: 15px; background: #ffffff9c; border-radius: 10px; padding: 10px;">
                <div class="step"
                     style="width: 70px;height: 70px;background: #0000000d;border-radius: 50px;font-size: 25px;text-align: center;padding: 10px;">
                    <img src="https://coisasderh.com.br/wp-content/uploads/2023/02/Certificate-NOV-830x1024.webp"
                         style="position: relative; top: -8px;"></div>
                <div style="display: grid; align-content: center;">
                    <p style="margin-bottom: 0; font-weight: 700;">100% de confiança!</p>
                    <p style="margin-bottom: 0; font-size: 12px;">Escolha sua sorte</p>
                </div>
            </div>
        </div>
    </div>
    <div class="app-title {{ $config->tema }}">
        <h1>⚡ Prêmios</h1>
        <div class="app-title-desc {{ $config->tema }}">Escolha sua sorte</div>
    </div>

    <div class="row">
        @foreach ($products as $product)
                <?php
                $imagen = $product->imagem();
                if (!isset($imagen['name'])) {
                    continue;
                }
                ?>
            <div class="col-md-6 col-12">
                <a href="{{ route('product', ['slug' => $product->slug]) }}">
                    <div class="card-rifa {{ $config->tema }}">
                        <div class="img-rifa">
                            <img src="/products/{{ $product->imagem()->name }}" alt="" srcset="">
                        </div>
                        <div class="title-rifa title-rifa-destaque {{ $config->tema }}">


                            <h1>{{ $product->name }}</h1>
                            <p>{{ $product->subname }}</p>

                            <div>
                                Top Compradores
                            </div>
                            <div>
                                Bilhetes Premiados
                            </div>
                            <div style="width: 100%;">
                                {!! $product->status() !!}
                                @if ($product->draw_date)
                                    <br>
                                    <span class="data-sorteio {{ $config->tema }}" style="font-size: 12px;">
                                                Data do sorteio {{ date('d/m/Y', strtotime($product->draw_date)) }}
                                            </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    {{-- Fale Conosco --}}
    <div onclick="duvidas()" class="container d-flex duvida" style="">
        <div class="row">
            <div class="d-flex icone-duvidas">🤷</div>
            <div class="col text-duvidas {{ $config->tema }}">
                <h6 class="mb-0 font-md f-15">Dúvidas?</h6>
                <p class="mb-0  font-sm f-12 text-muted">Fale conosco</p>
            </div>
        </div>
    </div>

    {{-- Ganhadores --}}
    @if ($ganhadores->count() > 0)
        <div class="app-title {{ $config->tema }}">
            <h1>🎉 Ganhadores</h1>
            <div class="app-title-desc {{ $config->tema }}">sortudos</div>
        </div>

        <style>
            .ganhador {
                display: flex !important;
                margin-bottom: 10px;
                background: #fff;
                padding: 5px;
                border-radius: 10px;
                cursor: pointer;
            }

            .ganhador-foto img {
                width: 52px;
                height: 52px;
                border: 2px solid #63ac49;
                margin-top: 10px;
            }

            .ganhador-desc {
                margin-left: 5px;
                width: 80%;
            }

            .ganhador-desc h3 {
                font-size: 1.1em;
            }

            .ganhador p {
                font-size: .85em;
                margin-bottom: 0;
                opacity: .85;
            }

            .ganhador-rifa {
                float: right;
            }

            .ganhador-rifa img {
                height: 40px;
                width: 40px;
                border-radius: 50rem;
                margin-top: 5px;
            }

            .ganhadores a {
                text-decoration: none !important;
            }

            .ganhador.dark {
                background: #222222;
            }

            .ganhador-desc.dark {
                color: #fff !important;
            }
        </style>

        <div class="ganhadores">

            {{-- Ganhador manual (editar rifa) --}}
            @foreach ($winners as $winner)
                <div class="ganhador {{ $config->tema }}"
                     onclick="verRifa('{{ route('product', ['slug' => $winner->slug]) }}')">
                    <div class="ganhador-foto">
                        <img src="images/sem-foto.jpg" class="" alt="{{ $winner->name }}"
                             style="min-height: 50px;max-height: 20px;border-radius:10px;">
                    </div>
                    <div class="ganhador-desc {{ $config->tema }}">
                        <h3>{{ $winner->winner }}</h3>
                        <p>
                            <strong>Sorteio: </strong>
                            {{ date('d/m/Y', strtotime($winner->draw_date)) }}
                        </p>
                    </div>
                    <div class="ganhador-rifa">
                        <img src="/products/{{ $winner->imagem()->name }}">
                    </div>
                </div>
            @endforeach

            @foreach ($ganhadores as $ganhador)
                <div class="ganhador {{ $config->tema }}"
                     onclick="verRifa('{{ route('product', ['slug' => $ganhador->rifa()->slug]) }}')">
                    <div class="ganhador-foto">
                        @if ($ganhador->foto)
                            <img src="{{ asset($ganhador->foto) }}" class="" alt=""
                                 style="min-height: 50px;max-height: 20px;border-radius:10px;">
                        @else
                            <img src="images/sem-foto.jpg" class="" alt=""
                                 style="min-height: 50px;max-height: 20px;border-radius:10px;">
                        @endif

                    </div>
                    <div class="ganhador-desc {{ $config->tema }}">
                        <h3>{{ $ganhador->ganhador }}</h3>
                        <p>
                            Ganhou <strong>{{ $ganhador->descricao }}</strong> cota <span
                                    class="badge bg-success p-1"
                                    style="border-radius: 5px;">{{ $ganhador->cota }}</span> <br>
                            <strong>Sorteio: </strong>
                            {{ date('d/m/Y', strtotime($ganhador->rifa()->draw_date)) }}
                        </p>
                    </div>
                    <div class="ganhador-rifa">
                        <img src="/products/{{ $ganhador->rifa()->imagem()->name }}">
                    </div>
                </div>
            @endforeach

        </div>
    @endif

    {{-- Perguntas ferquentes --}}
    @if (!env('HIDE_FAQ'))
        <div class="perguntas-frequentes pb-2">
            <div class="app-title {{ $config->tema }}">
                <h1>🤷 Perguntas frequentes</h1>
            </div>
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                            <button class="btn btn-sm btn-block text-left collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseOne" aria-expanded="false"
                                    aria-controls="collapseOne">
                                Acessando suas compras
                            </button>
                        </h2>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                         data-parent="#accordionExample">
                        <div class="card-body">
                            Existem <strong>duas</strong> formas de você conseguir acessar suas compras, a
                            primeira é logando no site, clicando no carrinho de compras no menu superior e a
                            segunda é visitando o sorteio e clicando em "Ver meus números".
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion mt-2" id="accordionExample">
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn btn-sm btn-block text-left collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                                    aria-controls="collapseTwo">
                                Como envio o comprovante ?
                            </button>
                        </h2>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                         data-parent="#accordionExample">
                        <div class="card-body">
                            Caso você tenha feito o pagamento via PIX QR Code ou copiando o código, não é
                            necessário enviar o comprovante, aguardando até 5 minutos após o pagamento, o
                            sistema irá dar baixa automaticamente, para mais dúvidas entre em contato conosco
                            pelo whatsapp.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @include('layouts.footer')

@endsection
