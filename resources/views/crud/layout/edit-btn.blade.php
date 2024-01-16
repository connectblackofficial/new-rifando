@if(isset($permissions['edit']) && $permissions['edit']===true)
        <?php
        if (!isset($item[$pkModelCol])) {
            $item = $row;
        }
        ?>
    <a href="{{ route($routeEdit,['pk'=>$item->{$pkModelCol}]) }}" title="<?=htmlLabel('edit')?>">
        <button class="btn btn-primary btn-sm">
            <i class="fa fa-pen-square" aria-hidden="true"></i> <?= htmlLabel('edit') ?>
        </button>
    </a>
@endif