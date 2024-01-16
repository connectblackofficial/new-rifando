<?php
if (isset($item)) {
    $pk = $item->{$pkModelCol};
} else {
    $pk = $row->{$pkModelCol};
}
?>
@if(isset($permissions['destroy']) && $permissions['destroy']===true)

    <form method="POST" action="{{ route($routeDelete,['pk'=>$pk]) }}"
          accept-charset="UTF-8" style="display:inline">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
        <button type="submit" class="btn btn-danger btn-sm" title="<?=htmlLabel('delete')?>"
                onclick="return confirm('<?=htmlLabel('are_you_sure')?>')">
            <i class="fa fa-trash" aria-hidden="true"></i>
                <?= htmlLabel('delete') ?>
        </button>
    </form>
@endif