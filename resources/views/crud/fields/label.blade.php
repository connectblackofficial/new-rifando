@if(isset($attrs['custom-label']))
    <label for="{{$name}}">
            <?php echo $attrs['custom-label']; ?>
        @include("crud.fields.tooltip")
    </label>
@else
    <label for="{{$name}}">
            <?php echo getInputLabelLang($name, $attrs) ?>
        @include("crud.fields.tooltip")

    </label>
@endif