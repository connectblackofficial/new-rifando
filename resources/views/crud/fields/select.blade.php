<label class="label d-block"
       for="<?=$name?>"><?php echo getInputLabelLang($name, $attrs) ?>:</label>


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