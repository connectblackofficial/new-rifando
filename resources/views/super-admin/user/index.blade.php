@extends('layouts.admin')
@section('content')
<?php
$crudData = getCrudData('User', 'user', 'admin/');
extract($crudData);
$rows = $user;
?>
<div class="row">
    <div class="col-md-12">
        @include("crud.layout.alerts")


        <div class="container mt-3" style="max-width:100%;min-height:100%;">
            @include("crud.layout.table-header")
            <div class="table-wrapper ">

                <div class="table-responsive">

                    <table class="table table-striped table-bordered table-responsive-sm table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ trans('user.name') }}</th><th>{{ trans('user.telephone') }}</th><th>{{ trans('user.status') }}</th>
                            <th>@lang("actions")</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td><td>{{ $item->telephone }}</td><td>{{ $item->status }}</td>
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
    <!-- ./row -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
            integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script>

        @endsection
