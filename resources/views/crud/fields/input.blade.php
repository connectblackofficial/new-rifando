<div class="form-group">
    <label for="{{$name}}"><?php echo getInputLabelLang($name, $attrs) ?></label>
    @if($type=='textarea')
        <textarea <?= parseInputsAttr($name, $attrs) ?> rows="10"
                  style="min-height: 200px;" >{!!$fieldValue!!}</textarea>
    @else
        <input type="{{$type}}" value="{{$fieldValue}}" <?= parseInputsAttr($name, $attrs) ?>/>

    @endif
    @if($errors->has($name))
        <div class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </div>
    @endif
</div>