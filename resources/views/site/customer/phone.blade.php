<?php
if (!isset($currentData)) {
    $currentData = [];
}
?>
<div class="col-md-3 phone-container ">
    <div class="form-group">
        <?= selectField("DDI", getCountries(), $currentData) ?>
    </div>

</div>
<div class="col-md-9 phone-container">
        <?= inputText('phone', $currentData, ['id' => 'telephone1', 'placeholder' => '(00) 90000-0000', 'style' => 'background-color: #fff;border: none;color: #333;']) ?>
</div>
