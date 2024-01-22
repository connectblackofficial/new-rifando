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
                                <h2>Meus <b>Sorteios</b></h2>

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
                                        type="submit">Buscar</button>
                                </form>


                                <a href="#addEmployeeModal" class="btn btn-success d-flex align-items-center"
                                    data-toggle="modal"
                                    style="font-size:30px;width: 100px;justify-content: center;height: 50px;margin-left: 5px;"><i
                                        class="bi bi-plus-square "></i></a>
                                <!--<a href="#deleteEmployeeModal" class="btn btn-danger" data-toggle="modal"><i class="material-icons">&#xE15C;</i> <span>Deletar</span></a>-->


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
                                    {{-- <th>Lista</th> --}}
                                    <th>Acões</th>
                                    <div id="copy-link"></div>
                                </tr>
                            </thead>
                            @foreach ($rifas as $key => $product)
                                <tbody>
                                    <tr>
                                        <td style="width: 50px;" class="text-center"><img style="border-radius: 5px;"
                                                src="{{$product->getDefaultImageUrl()}}"
                                                width="50" alt=""></td>
                                        <td>{{ $product->status }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            @if ($product->draw_date != null)
                                                {{ \Carbon\Carbon::parse($product->draw_date)->format('d/m/Y H:i') }}
                                            @endif
                                        </td>
                                        <td>{{ $product->price }}</td>
                                        <td style="width: 20%">
                                            @if (!$product->processado)
                                                <span class="badge bg-warning">Processando...</span>
                                            @else
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        Ações
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" style="cursor: pointer"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modal_editar_rifa{{ $product->id }}"><i
                                                                class="bi bi-pencil-square"></i>&nbsp;Editar</a>
                                                        <a class="dropdown-item"
                                                            href="#deleteEmployeeModal{{ $product->id }}"
                                                            style="cursor: pointer" data-toggle="modal"
                                                            data-bs-target="#deleteEmployeeModal{{ $product->id }}"
                                                            data-id="{{ $product->id }}"><i
                                                                class="bi bi-trash3"></i>&nbsp;Excluir</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('rifa.compras', $product->id) }}"><i
                                                                class="fas fa-shopping-bag"></i></i>&nbsp;Compras</a>
                                                        {{-- <a class="dropdown-item" href="{{ route('resumoRifaPDF', $product->id) }}" target="_blank"><i class="far fa-file-pdf"></i>&nbsp;PDF</a> --}}
                                                        <a class="dropdown-item"
                                                            href="{{ route('resumoRifa', $product->id) }}"
                                                            target="_blank"><i class="fas fa-list-ol"></i>&nbsp;Ver
                                                            Resumo</a>
                                                        <a class="dropdown-item" href="#"
                                                            onclick="copyResumoLink('{{ route('resumoRifa', $product->id) }}')"><i
                                                                class="fas fa-link"></i>&nbsp;Copiar Link Resumo</a>
                                                        <a class="dropdown-item" href="javascript:void(0)" title="Ranking"
                                                            onclick="openRanking('{{ $product->id }}')"><i
                                                                class="fas fa-award"></i>&nbsp;Ranking</a>
                                                        <a class="dropdown-item" style="color: green"
                                                            href="javascript:void(0)" title="Ranking"
                                                            onclick="definirGanhador('{{ $product->id }}')"><i
                                                                class="fas fa-check"></i>&nbsp;Definir Ganhador</a>
                                                        <a class="dropdown-item" href="javascript:void(0)" title="Ranking"
                                                            onclick="verGanhadores('{{ $product->id }}')"><i
                                                                class="fas fa-users"></i>&nbsp;Visualizar Ganhadores</a>
                                                        
                                                    </div>

                                                </div>
                                            @endif
                                        </td>

                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                        {{-- START TABELA MEUS SORTEIOS --}}
                        <!-- Add Modal HTML -->
                        <div id="addEmployeeModal" class="modal fade">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header d-flex align-items-center">
                                        <h4 class="modal-title">Nova Rifa</h4>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">&times;</button>
                                    </div>
                                    <div class="modal-body">


                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Duplicar Rifa --}}
                        <div id="duplicar-modal" class="modal fade">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header d-flex align-items-center">
                                        <h4 class="modal-title">Duplicar Rifa</h4>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">&times;</button>
                                    </div>
                                    <div class="modal-body">

                                        <form action="{{ route('duplicarProduct') }}" method="POST"
                                            enctype="multipart/form-data">

                                            <input type="hidden" name="product" id="id-duplicar">
                                            <center>
                                                <h3 id="titulo-duplicar"></h3>
                                            </center>
                                            {{ csrf_field() }}

                                            <div class="row mt-4">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Nome</label>
                                                        <input type="text" class="form-control" id="name"
                                                            name="name" placeholder="Exemplo: Fusca ano 88" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="exampleInputEmail1">Valor</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">R$:</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="price"
                                                            placeholder="Exemplo: 10,00" maxlength="6" id="price"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="criar btn btn-success">Criar</button>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>




                    </div>

                </div>

                <!-- Modal Ranking Admin -->
                {{-- <div class="modal fade" id="modal-ranking" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <span id="content-modal-ranking"></span>
                            </div>
                        </div>
                    </div>
                </div> --}}

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
                <!-- ./row -->
                <script src="https://code.jquery.com/jquery-3.7.0.min.js"
                    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
                <script>
                    function openRanking(id) {
                        //$('#content-modal-ranking').html('')
                        $.ajax({
                            url: "{{ route('ranking.admin') }}",
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                "id": id
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.html) {
                                    $('#content-modal-ranking').html(response.html)
                                    $('#modal-ranking').modal('show')
                                }
                            },
                            error: function(error) {

                            }
                        })
                    }

                    document.getElementById("input-add-foto").addEventListener("change", function(el) {
                        $('#form-foto').submit();
                    });

                    function addFoto(el) {
                        $('#rifa-id').val(el.dataset.id)
                        $('#input-add-foto').click()
                    }

                    function excluirFoto(el) {
                        if (el.dataset.qtd <= 1) {
                            alert('A rifa precisa de pelo menos 1 foto, adicione outra antes de exlcuir!')
                            return;
                        }

                        const data = {
                            id: el.dataset.id
                        }

                        var id = el.dataset.id;
                        var url = '{{ route('excluirFoto') }}'

                        Swal.fire({
                            title: 'Tem certeza que deseja excluir a foto ?',
                            html: `<input type="hidden" id="id" class="swal2-input" value="` + id + `">`,
                            inputAttributes: {
                                autocapitalize: 'off'
                            },
                            backdrop: true,
                            showCancelButton: true,
                            confirmButtonText: 'Excluir',
                            cancelButtonText: 'Cancelar',
                            showLoaderOnConfirm: true,
                            preConfirm: (id) => {
                                return fetch(url, {
                                        headers: {
                                            "Content-Type": "application/json",
                                            "Accept": "application/json",
                                            "X-Requested-With": "XMLHttpRequest",
                                            "X-CSRF-Token": $('meta[name="csrf-token"]').attr('content')
                                        },
                                        method: 'POST',
                                        dataType: 'json',
                                        body: JSON.stringify(data)
                                    })
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error(response.statusText)
                                        }
                                        return response.json()
                                    })
                                    .catch(error => {
                                        Swal.showValidationMessage(
                                            `Request failed: ${error}`
                                        )
                                    })
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                            if (result.value.success) {
                                Swal.fire({
                                    title: `Foto excluida com sucesso`,
                                    icon: 'success',
                                }).then(() => {
                                    $(`#foto-${id}`).remove();
                                })
                            } else {
                                Swal.fire({
                                    title: `Erro ao excluir tente novamente`,
                                    text: 'Erro: ' + result.value.error,
                                    icon: 'error',
                                })
                            }
                        })
                    }

                    function definirGanhador(id) {
                        $('#content-modal-definir-ganhador').html('')
                        $.ajax({
                            url: "{{ route('definirGanhador') }}",
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                "id": id
                            },
                            success: function(response) {
                                if (response.html) {
                                    $('#content-modal-definir-ganhador').html(response.html)
                                    $('#modal-definir-ganhador').modal('show');
                                }
                            },
                            error: function(error) {

                            }
                        })
                    }




                </script>

                @if (session()->has('sorteio'))
                    <script>
                        $(function(e) {
                            verGanhadores('{{ session('sorteio') }}')
                        })
                    </script>
                @endif



            @endsection
