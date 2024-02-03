@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">@lang("faq") #{{ $row->id}}</div>
            <div class="card-body">
                @include("crud.layout.form-alerts")
                @include("crud.layout.back-btn")

                <a href="{{ route($routeEdit,['pk'=>$row->id])}}"
                   title="<?= htmlLabel('edit') ?>">
                    <button class="btn btn-primary btn-sm text-capitalize">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        <?= htmlLabel('edit') ?>
                    </button>
                </a>

                @include("crud.layout.destroy-btn")
                <br/>
                <br/>

                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th><?= strtoupper(htmlLabel('id')) ?></th>
                            <td><?=itemRowView($formatFieldsFn, $row, 'id')?></td>
                        </tr>
                        <tr>
    <th class="text-uppercase"><?= htmlLabel("title") ?></th>
    <td><?=itemRowView($formatFieldsFn, $row, 'title')?> </td>

</tr>
<tr>
    <th class="text-uppercase"><?= htmlLabel("description") ?></th>
    <td><?=itemRowView($formatFieldsFn, $row, 'description')?> </td>

</tr>
<tr>
    <th class="text-uppercase"><?= htmlLabel("order") ?></th>
    <td><?=itemRowView($formatFieldsFn, $row, 'order')?> </td>

</tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
