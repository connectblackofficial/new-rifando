@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">@lang("paymentreceipt") #{{ $row->id}}</div>
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
    <th class="text-uppercase"><?= htmlLabel("status") ?></th>
    <td><?=itemRowView($formatFieldsFn, $row, 'status')?> </td>

</tr>
<tr>
    <th class="text-uppercase"><?= htmlLabel("comments") ?></th>
    <td><?=itemRowView($formatFieldsFn, $row, 'comments')?> </td>

</tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
