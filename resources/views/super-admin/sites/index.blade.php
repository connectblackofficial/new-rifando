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
                                <th><?= htmlLabel("name") ?></th>
                                <th><?= htmlLabel("subdomain") ?></th>
                                <th><?= htmlLabel("user_name") ?></th>
                                <th><?= htmlLabel("user_email") ?></th>
                                <th><?= htmlLabel("user_cpf") ?></th>
                                <th><?= htmlLabel("active") ?></th>
                                <th>@lang("actions")</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rows as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td><?= itemRowView($formatFieldsFn, $item, 'name') ?></td>
                                    <td><?= itemRowView($formatFieldsFn, $item, 'subdomain') ?></td>
                                    <td><?= itemRowView($formatFieldsFn, $item, 'user_name') ?></td>
                                    <td><?= itemRowView($formatFieldsFn, $item, 'user_email') ?></td>
                                    <td><?= itemRowView($formatFieldsFn, $item, 'user_cpf') ?></td>
                                    <td><?= itemRowView($formatFieldsFn, $item, 'active') ?></td>
                                    <td>
                                        @include("crud.layout.actions")
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
