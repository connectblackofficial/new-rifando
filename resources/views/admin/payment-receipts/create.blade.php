@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><?=htmlTitle("create new $crudNameSingular")?></div>
            <div class="card-body">
                @include("crud.layout.back-btn")
                @include("crud.layout.form-alerts")
                <form method="POST" action="{{route($routeStore)}}" accept-charset="UTF-8" class="form-horizontal"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}
                    @include ('admin.payment-receipts.form', ['formMode' => 'create'])
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
