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
                                <th>Miniatura</th>
                                <th>Status</th>
                                <th>Sorteio</th>
                                <th>Data Sorteio</th>
                                <th>Valor</th>
                                <th>@lang("actions")</th>
                                <div id="copy-link"></div>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td style="width: 50px;" class="text-center">
                                        <img style="border-radius: 5px;" src="{{$product->getDefaultImageUrl()}}"
                                             width="50" alt="">
                                    </td>
                                    <td>{{ $product->status }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        @if ($product->draw_date != null)
                                            {{ \Carbon\Carbon::parse($product->draw_date)->format('d/m/Y H:i') }}
                                        @endif
                                    </td>
                                    <td>{{ formatMoney($product->price) }}</td>
                                    <td style="width: 20%">
                                        @include("admin.products.parts.drop-down-action")
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


        <div class="modal fade" id="modal-ranking" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true" style="z-index: 9999999;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border: none;">
                    <div class="modal-header" style="background-color: #fff;">
                        <h5 class="modal-title" id="exampleModalLabel" style="color: #000;"><img
                                    src="{{ cdnImageAsset('treofeu.png') }}" alt=""> Top Compradores</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                                style="color: #000;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="color: #000">
                        <span id="content-modal-ranking"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal-definir-ganhador" tabindex="-1"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Definir Ganhador</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span id="content-modal-definir-ganhador"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal-ver-ganhadores" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <span id="content-modal-ver-ganhadores"></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col-->
    </div>

@endsection
