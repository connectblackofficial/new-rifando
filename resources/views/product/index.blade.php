@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session()->has('success'))
                <div class="alert alert-success">
                    <ul>
                        <li>{{ session('success') }}</li>
                    </ul>
                </div>
            @endif


            {{-- START TABELA MEUS SORTEIOS --}}
            <div class="container mt-3" style="max-width:100%;min-height:100%;">
                <div class="table-wrapper ">
                    <div class="table-title">
                        <div class="row mb-3">
                            <div class="col d-flex justify-content-center">
                                <h2><?= $pageTitle ?></h2>
                                {{-- form auxiliar para adicionar imagens na rifa --}}
                                <form class="d-none" action="{{ route('addFoto') }}" id="form-foto" method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <input type="text" id="rifa-id" name="idRifa">
                                    <input type="file" id="input-add-foto" accept="image/png,image/jpeg,image/jpg"
                                           multiple name="fotos[]">
                                </form>
                            </div>
                            <div class="row-12 mb-3 d-flex" style="justify-content: space-between;">
                                <form method="GET" class="form-inline my-2 my-lg-0">
                                    <input class="form-control mr-sm-2" type="search" name="search"
                                           placeholder="Pesquisar" aria-label="Search">
                                    <button class="btn btn-outline-secondary my-2 my-sm-0 border border-secondary text-dark"
                                            type="submit">Buscar
                                    </button>
                                </form>

                                <a href="#" class="btn btn-success d-flex align-items-center"
                                   onclick="return productCreate()"
                                   style="font-size:30px;width: 100px;justify-content: center;height: 50px;margin-left: 5px;">
                                    <i class="bi bi-plus-square"></i>
                                </a>

                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-responsive-sm table-hover align=center"
                               id="table_rifas">
                            <thead>
                            <tr>
                                <th>Miniatura</th>
                                <th>Status</th>
                                <th>Sorteio</th>
                                <th>Data Sorteio</th>
                                <th>Valor</th>
                                <th>Acões</th>
                                <div id="copy-link"></div>
                            </tr>
                            </thead>
                            @foreach ($rifas as $key => $product)
                                <tbody>
                                <tr>
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
                                        @include("product.drop-down-action")
                                    </td>
                                </tr>
                                </tbody>
                            @endforeach
                        </table>

                    </div>
                    {{ $rifas->links() }}

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
