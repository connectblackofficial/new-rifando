@if(isset($attrs['form-container']))

    <div class="{{$attrs['form-container']}}">
        @endif
        <?php
        $btnGroupId = "btnGroupAddon" . rand(1111, 9999);
        ?>
        @include("crud.fields.label")
        <div class="input-group">
            <input type="text" <?= parseInputsAttr($name, $attrs) ?>  class="form-control"
                   aria-describedby="<?=$btnGroupId?>">
            <div class="input-group-prepend">
                <div class="input-group-text" id="<?=$btnGroupId?>"><?= $groupText?></div>
            </div>
        </div>
        @if(isset($attrs['form-container']))
    </div>
@endif