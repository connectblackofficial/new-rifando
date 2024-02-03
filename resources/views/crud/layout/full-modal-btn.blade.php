@if(!empty($containerClass))
    <div class="{{$containerClass}}">
        @endif
        <button class="btn btn-block {{$color}} mt-2 full-btn-modal {{$buttonClass}} " id="{{$id}}" type="{{$type}}">
            @if($type=="submit")
                <strong class="btn-block-text"><?= $text ?></strong>
                <strong class="btn-block-loading" style="display: none">
                    @include("layouts.components.loading-svg")
                    Processando...
                </strong>
            @else
                <strong><?= $text ?></strong>

            @endif
        </button>

        @if(!empty($containerClass))
    </div>
@endif

