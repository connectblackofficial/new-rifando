<?php
if (!isset($currentData)) {
    $currentData = [];
}
?>
<div class="col-md-3 phone-container ">
    <div class="form-group">
        <?= selectField("DDI", getCountries(), $currentData, ['id' => 'custom-phone-ddi']) ?>
    </div>

</div>

<div class="col-md-9 phone-container">
    <?= inputField('phone', "text", $currentData, [ 'class' => 'custom-phone','placeholder'=>'Ex:(16) 96000-0000']) ?>
</div>
