<div class="form-group">
    @if(isset($attrs['custom-label']))
        <label for="{{$name}}"><?php echo $attrs['custom-label']; ?></label>
    @else
        <label for="{{$name}}"><?php echo getInputLabelLang($name, $attrs) ?></label>
    @endif

    @if($errors->has($name))
        <div class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </div>
    @endif
</div>