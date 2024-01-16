@if(isset($permissions['show']) && $permissions['show']===true)
        <?php
        if (!isset($item[$pkModelCol])) {
            $item = $row;
        }
        ?>
    <a href="{{route($routeView,['pk'=>$item->{$pkModelCol}]) }}" title="<?=htmlLabel('view')?>">
        <button class="btn btn-info btn-sm">
            <i class="fa fa-eye" aria-hidden="true"></i> <?= htmlLabel('view') ?>
        </button>
    </a>
@endif