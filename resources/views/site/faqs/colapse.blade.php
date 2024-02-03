<div class="app-title {{ $config->tema }}">
    <h1>🤷 Perguntas frequentes</h1>
</div>
<div class="accordion" id="accordionExample">
    <div class="card">
        @foreach($faqs as $faq)
            <div class="card-header" id="faq-{{$faq->id}}">
                <h2 class="mb-0">
                    <button class="btn btn-sm btn-block text-left" type="button"
                            data-toggle="collapse" data-target="#faq-colapse-{{$faq->id}}" aria-expanded="false"
                            aria-controls="collapseOne">
                        {{$faq->title}}
                    </button>
                </h2>
            </div>
            <div id="faq-colapse-{{$faq->id}}" class="collapse" aria-labelledby="faq-{{$faq->id}}"
                 data-parent="#accordionExample">
                <div class="card-body">
                        <?= $faq->description ?>
                </div>
            </div>
        @endforeach

    </div>
</div>
