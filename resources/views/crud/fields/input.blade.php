
<div class="form-group">
    <label for="{{$name}}"><?= htmlLabel($name) ?></label>
    <input type="{{$type}}" name="{{$name}}" value="{{$fieldValue}}" class="form-control"/>
    @if($errors->has($name))
        <div class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </div>
    @endif
</div>