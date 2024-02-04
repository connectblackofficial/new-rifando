@if(isset($attrs['form-container']))
    <div class="{{$attrs['form-container']}}">
        @endif
        <div class="form-group {{ $errors->has($name) ? 'has-error' : ''}}">
            @include("crud.fields.label")
            @if($type=='textarea')
                <textarea <?= parseInputsAttr($name, $attrs) ?> rows="10"
                          style="min-height: 200px;">{!!$fieldValue!!}</textarea>
            @elseif($type=='image')
                <input type="file" <?= parseInputsAttr($name, $attrs) ?> />
                @if(!empty($fieldValue))
                    <p><a href="#" onclick="return loadAjaxImage('<?=$fieldValue?>')">Ver imagem</a></p>
                @endif
            @else
                @if(empty($fieldValue))
                    <input type="{{$type}}" <?= parseInputsAttr($name, $attrs) ?>/>
                @else
                    <input type="{{$type}}" value="{{$fieldValue}}" <?= parseInputsAttr($name, $attrs) ?>/>
                @endif
            @endif

            @if($errors->has($name))
                <div class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first($name) }}</strong>
                </div>
            @endif
        </div>
        @if(isset($attrs['form-container']))
    </div>
@endif