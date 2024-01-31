<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner" style="margin-top: -20px;">
        @foreach ($imagens as $key => $foto)
            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}" style="margin-top: 30px;"
                 id="slide-foto-{{ $foto->id }}">
                <img src="{{imageAsset($foto->name)}}"
                     style="border-top-right-radius: 20px;border-top-left-radius: 20px; height: 290px;"
                     class="d-block w-100" alt="...">
            </div>
        @endforeach
    </div>

    <div class="title-rifa-destaque {{ $config->tema }}">
        <h1>{{ $productModel->name }}</h1>
        <p>{{ $productModel->subname }}</p>
        <div style="width: 100%;">
            {!! $productModel->status() !!}
            @if ($productModel->draw_date)
                <br>
                <span class="data-sorteio {{ $config->tema }} ml-1" style="font-size: 12px;">
                    Data do sorteio {{ date('d/m/Y', strtotime($productModel->draw_date)) }}
                    {{-- {!! $product->dataSorteio() !!} --}}
                </span>
            @endif
        </div>
    </div>
</div>


<div class="container mt-2">
    <div class="text-center">
        <span class="valor {{ $config->tema }}">POR APENAS</span>
        <span class="badge p-2"
              style="font-size: 14px; background: #000; color: #d1d1d1">{{ formatMoney($productModel->price)}}</span>
    </div>
</div>
@if(isset($productDescription) && !empty($productDescription))
    <div class="" style="">
        <h5 class="mt-1 title-promo {{ $config->tema }}">
            📋 Descrição
        </h5>
    </div>
    <div class="card mt-3 desc {{ $config->tema }}">
        <p>
            {!! $productDescription !!}
        </p>
    </div>
@endif


<div class="mt-2 d-flex text-center justify-content-center">
    <div class="text-center">
        <center>
            <!-- Facebook -->
            <a class="btn btn-primary" style="background-color: #2760AE;border: none;font-size: 20px;"
               href="https://www.facebook.com/sharer/sharer.php?u={{ Request::url() }}" target="_blank"
               rel="noreferrer noopener" role="button"><i class="bi bi-facebook"></i></a>
            <!-- Telegram -->
            <a class="btn btn-primary" style="background-color: #2F9DDF;border: none;"
               href="https://telegram.me/share/url?url={{ Request::url() }}" target="_blank" rel="noreferrer noopener"
               role="button"><i class="bi bi-telegram" style="font-size: 20px;"></i></a>

            @if($config->whatsapp)
                <a class="btn btn-primary" style="background-color: #25d366;border: none;"
                   href="https://api.whatsapp.com/send?text={{ Request::url() }}" target="_blank"
                   rel="noreferrer noopener" role="button"><i class="bi bi-whatsapp" style="font-size: 20px;"></i></a>
            @endif
            <!-- Whatsapp -->

            <!-- Twitter -->
            <a class="btn btn-primary" style="background-color: #34B3F7;border: none;"
               href="https://twitter.com/intent/tweet?text=Vc%20pode%20ser%20o%20Próximo%20Ganhador%20{{ Request::url() }}"
               target="_blank" rel="noreferrer noopener" role="button"><i class="bi bi-twitter"
                                                                          style="font-size: 20px;"></i></a>
        </center>

    </div>
</div>
