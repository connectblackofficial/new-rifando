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
                            <th><?=htmlLabel("nome")?></th>
<th><?=htmlLabel("telephone")?></th>
<th><?=htmlLabel("ddi")?></th>

                            <th>@lang("actions")</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($customers as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><?=itemRowView($formatFieldsFn, $item, 'nome')?></td>
<td><?=itemRowView($formatFieldsFn, $item, 'telephone')?></td>
<td><?=itemRowView($formatFieldsFn, $item, 'ddi')?></td>

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
