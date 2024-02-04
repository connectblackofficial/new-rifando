@extends('layouts.app')

@section('content')
    <!-- <img src="<?= cdnImageAsset('bg-2.jpg') ?>" class="rounded-md"> -->
    <img src="<?=cdnImageAsset($config->banner)?>"
         class="rounded-md" style="filter: hue-rotate(15deg);">
    <div class="row" style="margin-top: 25px; margin-bottom: 25px;">
        <div class="col-md-3">
            <div class="d-flex" style="column-gap: 15px; background: #ffffff9c; border-radius: 10px; padding: 10px;">
                <div class="step"
                     style="width: 70px;height: 70px;background: #0000000d;border-radius: 50px;font-size: 25px;text-align: center;padding: 10px;">
                    <img src="<?=cdnImageAsset('Ribbon_Purple_icon-icons.com_75198.png')?>">
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
                    <img src="<?=cdnImageAsset('Ribbon_Purple_icon-icons.com_75198.png')?>">
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
                    <img src="<?=cdnImageAsset('ftcdn_icon1.png')?>">
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
                    <img src="<?=cdnImageAsset('Certificate-NOV-830x1024.webp')?>"
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

            <div class="col-md-6 col-12">
                <a href="{{ route('product', ['slug' => $product->slug]) }}">
                    <div class="card-rifa {{ $config->tema }}">
                        <div class="img-rifa">
                            <img src="{{$product->getDefaultImageUrl()}}" alt="" srcset="">
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

        </style>

        <div class="ganhadores">

            {{-- Ganhador manual (editar rifa) --}}
            @foreach ($winners as $winner)
                <div class="ganhador {{ $config->tema }}"
                     onclick="verRifa('{{ route('product', ['slug' => $winner->slug]) }}')">
                    <div class="ganhador-foto">
                        <img src="<?=cdnImageAsset('sem-foto.jpg')?>" class="" alt="{{ $winner->name }}"
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
                        <img src="{{ $winner->getDefaultImageUrl() }}">
                    </div>
                </div>
            @endforeach

            @foreach ($ganhadores as $ganhador)
                    <?php
                    /** @var \App\Models\Product $rifaGanhador */
                    $rifaGanhador = $ganhador->rifa();
                    ?>
                <div class="ganhador {{ $config->tema }}"
                     onclick="verRifa('{{ route('product', ['slug' => $rifaGanhador->slug]) }}')">
                    <div class="ganhador-foto">
                        @if ($ganhador->foto)
                            <img src="{{ imageAsset($ganhador->foto) }}" class="" alt=""
                                 style="min-height: 50px;max-height: 20px;border-radius:10px;">
                        @else
                            <img src="<?=cdnImageAsset('sem-foto.jpg')?>" class="" alt=""
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
                            {{ date('d/m/Y', strtotime($rifaGanhador->draw_date)) }}
                        </p>
                    </div>
                    <div class="ganhador-rifa">
                        <img src="{{ $rifaGanhador->getDefaultImageUrl()}}">
                    </div>
                </div>
            @endforeach

        </div>
    @endif
    @if($config->show_faqs==1)
            <?php
            $siteFaqs = \App\Models\Faq::getAllActive($config->user_id);
            ?>
        @if(!is_null($siteFaqs) && count($siteFaqs)>0)
            <div class="perguntas-frequentes pb-2">
                    <?= faqColapse(\App\Models\Faq::getAllActive($config->user_id), $config) ?>
            </div>
        @endif
    @endif

@endsection
