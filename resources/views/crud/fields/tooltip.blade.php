<?php
    $tooltipKey=$name.'_tooltip';
    $lang=getIfExistsLang($tooltipKey);
    ?>
@if($lang)
    <a href="#" >
        <i data-toggle="tooltip" data-toggle="tooltip" data-placement="top" title="<?=$lang?>" data-html="true" class="fa fa-info-circle"></i>
    </a>
@endif