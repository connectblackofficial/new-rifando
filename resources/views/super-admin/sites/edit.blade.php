@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header text-capitalize">@lang("edit site") #{{ $row->id }}</div>
            <div class="card-body">
                @include("crud.layout.back-btn")
                @include("crud.layout.form-alerts")


                <form method="POST" action="{{ route($routeUpdate,['pk'=>$row->id]) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    @include ('super-admin.sites.form', ['formMode' => 'edit'])
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
