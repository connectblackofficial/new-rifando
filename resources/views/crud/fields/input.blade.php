<div class="form-group">
    @if(isset($attrs['custom-label']))
        <label for="{{$name}}"><?php echo $attrs['custom-label']; ?></label>
    @else
        <label for="{{$name}}"><?php echo getInputLabelLang($name, $attrs) ?></label>
    @endif
    @if($type=='textarea')
        <textarea <?= parseInputsAttr($name, $attrs) ?> rows="10"
                  style="min-height: 200px;">{!!$fieldValue!!}</textarea>
    @else
       @if(empty($fieldValue))
         <input type="{{$type}}"  <?= parseInputsAttr($name, $attrs) ?>/>
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