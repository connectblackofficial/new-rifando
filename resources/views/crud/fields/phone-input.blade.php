<?php
if (!isset($id)) {
    $id = $name;
}
?>
<div class="form-group">
    <label style="color: #000; width: 100%"><strong>{{$label}}</strong></label>
    <input type="text" class="form-control intl-input-phone"
           style="background-color: #fff;border: none;color: #333;" value="<?= $fieldValue?>"
           name="<?= $name?>" id="<?=$id?>">
</div>