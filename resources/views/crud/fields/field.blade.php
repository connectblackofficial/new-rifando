<?php
if (!isset($class)) {
    $class = '';
}
$fieldValue = "";
if (isset($value) && !empty($value)) {
    $fieldValue = $value;
}
if (!isset($colClass)) {
    $colClass = "col-md-6";
}
?>
<div class="{{$colClass}}">
    <div class="form-group">
        <label for="{{$name}}">@lang($baseLang.".".$name)</label>
        <input type="{{$type}}" name="{{$name}}" value="{{$fieldValue}}" class="form-control {{$class}}"/>
        @if($errors->has($name))
            <div class="invalid-feedback" role="alert">
                <strong>{{ $errors->first($name) }}</strong>
            </div>
        @endif
    </div>
</div>
