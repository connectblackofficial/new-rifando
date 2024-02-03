@if(isset($attrs['custom-label']))
    <label class="label d-block"
           for="<?=$name?>"><?php echo $attrs['custom-label']; ?></label>
@else
    <label class="label d-block"
           for="<?=$name?>"><?php echo getInputLabelLang($name, $attrs) ?>:</label>
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