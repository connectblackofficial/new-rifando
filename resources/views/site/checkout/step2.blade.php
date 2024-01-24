<?php
$baseLang = "checkout_customer"

  $fieldData = ['base-lang' => $baseLang];
?>

<form action="{{ route('bookProductManualy') }}" id="form-checkout" method="POST">
    {{ csrf_field() }}
    <?= inputText("name",$customerData, ['base-lang' => $baseLang]) ?>
    <?= inputField("telephone","text", $customerData, ['base-lang' => $baseLang]) ?>

    <?= inputField("telephone", "text", $customerData, ['base-lang' => $baseLang]) ?>
    <?= inputField("email", "text", $customerData, ['base-lang' => $baseLang]) ?>

</form>
