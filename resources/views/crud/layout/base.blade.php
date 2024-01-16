@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">

            {{-- START TABELA MEUS SORTEIOS --}}
            <div class="container mt-3" style="max-width:100%;min-height:100%;">
                <div class="table-wrapper ">
                    <div class="table-title">
                        <div class="row mb-3">
                            <div class="col d-flex justify-content-center">
                                <h2>Usuários</h2>
                            </div>
                            <div class="row-12 mb-3 d-flex" style="justify-content: space-between;">

                                <form method="GET" action="{{ url('/admin/user') }}" class="form-inline my-2 my-lg-0">
                                    <?php

                                    if (!isset($search)) {
                                        $search = '';
                                    }
                                    ?>
                                    <input class="form-control mr-sm-2" type="search" name="search"
                                           placeholder="Pesquisar" aria-label="Search" value="{{ $search }}">
                                    <button class="btn btn-outline-secondary my-2 my-sm-0 border border-secondary text-dark"
                                            type="submit">
                                        Buscar
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">

                        </div>
                    </div>


                </div>
            </div>


@endsection
