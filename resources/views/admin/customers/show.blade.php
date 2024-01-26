@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">@lang("customer") #{{ $row->id}}</div>
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
    <th class="text-uppercase"><?= htmlLabel("nome") ?></th>
    <td><?=itemRowView($formatFieldsFn, $row, 'nome')?> </td>

</tr>
<tr>
    <th class="text-uppercase"><?= htmlLabel("telephone") ?></th>
    <td><?=itemRowView($formatFieldsFn, $row, 'telephone')?> </td>

</tr>
<tr>
    <th class="text-uppercase"><?= htmlLabel("ddi") ?></th>
    <td><?=itemRowView($formatFieldsFn, $row, 'ddi')?> </td>

</tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
