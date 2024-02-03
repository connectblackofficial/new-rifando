@extends('layouts.admin')
@section('content')
    <div class="row">

        <div class="col-md-12">
            @include("crud.layout.alerts")
            <div class="container mt-3" style="max-width:100%;min-height:100%;">
                @include("crud.layout.table-header")
                <div class="table-wrapper ">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>

                            <tr>
                                <th>#</th>
                                <th><?= htmlLabel("product_name") ?></th>
                                <th><?= htmlLabel("amount") ?></th>
                                <th><?= htmlLabel("customer_name") ?></th>
                                <th><?= htmlLabel("status") ?></th>
                                <th><?= htmlLabel("customer_phone") ?></th>
                                <th><?= htmlLabel("created_at") ?></th>
                                <th><?= htmlLabel("updated_at") ?></th>
                                <th>@lang("actions")</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rows as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td><?= itemRowView($formatFieldsFn, $item, 'product_name') ?></td>
                                    <th><?= itemRowView($formatFieldsFn, $item, 'valor') ?></th>
                                    <td><?= itemRowView($formatFieldsFn, $item, 'customer_name') ?></td>
                                    <td><?= itemRowView($formatFieldsFn, $item, 'status') ?></td>
                                    <td><?= itemRowView($formatFieldsFn, $item, 'customer_phone') ?></td>

                                    <td><?= itemRowView($formatFieldsFn, $item, 'created_at') ?></td>
                                    <td><?= itemRowView($formatFieldsFn, $item, 'updated_at') ?></td>

                                    <td>
                                        @if(isset($permissions['edit']) && $permissions['edit']===true)
                                                <?php
                                                $urlEdit = route($routeEdit, ['pk' => $item->{$pkModelCol}]);
                                            $cId = $item->id;
                                                ?>
                                            <a href="#"
                                               onclick="return loadUrlModal('#{{$cId}} - Comprovante de pagameto ','{{$urlEdit}}')"
                                               title="<?= htmlLabel('edit') ?>">
                                                <button class="btn btn-primary btn-sm">
                                                    <i class="fa fa-pen-square"
                                                       aria-hidden="true"></i> <?= htmlLabel('edit') ?>
                                                </button>
                                            </a>
                                        @endif
                                        @include("crud.layout.destroy-btn")
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>


                        <div class="pagination-wrapper"> {!! $rows->appends(['search' => Request::get('search')])->render()
                        !!}
                    </div>
                </div>
            </div>

        </div>

    </div>


@endsection
