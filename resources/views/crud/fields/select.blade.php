<label class="label d-block"
       for="<?=$name?>"><?= htmlLabel($name) ?>:</label>
<select class="form-control" name="<?=$name?>">
    @foreach($options as $index=>$option)
            <?php
            $selected = '';
            if (isset($currentData[$name]) && $index == $currentData[$name]) {
                $selected = ' selected';
            }
            ?>
        <option value="<?=$index?>" <?= $selected ?> ><?=htmlLabel($option)?></option>
    @endforeach
</select>