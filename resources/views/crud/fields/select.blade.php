@if(isset($attrs['form-container']))
    <div class="{{$attrs['form-container']}}">
        @endif

        @if(isset($attrs['custom-label']))
            <label class="label d-block"
                   for="<?=$name?>"><?php echo $attrs['custom-label']; ?>
                @include("crud.fields.tooltip")
            </label>
        @else
            <label class="label d-block"
                   for="<?=$name?>"><?php echo getInputLabelLang($name, $attrs) ?>
                @include("crud.fields.tooltip")

            </label>
        @endif

        <select name="<?=$name?>" <?= parseInputsAttr($name, $attrs) ?>>
            @foreach($options as $index=>$option)
                    <?php
                    $selected = '';
                    if (isset($currentData[$name]) && $index == $currentData[$name]) {
                        $selected = ' selected';
                    }
                    ?>
                <option value="<?=$index?>" <?= $selected ?> ><?= htmlLabel($option) ?></option>
            @endforeach
        </select>

        @if(isset($attrs['form-container']))
    </div>
@endif