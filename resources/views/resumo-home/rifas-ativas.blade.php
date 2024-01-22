@extends('layouts.admin')
@section("scripts-top")
    <style>
        .hidden {
            display: none;
        }

        .promo {
            border: solid;
            border-width: thin;
            border-radius: 10px;
            padding: 20px;
        }
    </style>
@endsection
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
                                <h2>Rifas Ativas</h2>

                                {{-- form auxiliar para adicionar imagens na rifa --}}
                                <form class="d-none" action="{{ route('addFoto') }}" id="form-foto" method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <input type="text" id="rifa-id" name="idRifa">
                                    <input type="file" id="input-add-foto" accept="image/png,image/jpeg,image/jpg"
                                           multiple name="fotos[]">
                                </form>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-responsive-md table-hover align=center"
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
                                                                                      width="50"
                                                                                      alt=""></td>
                                    <td>{{ $product->status }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        @if ($product->draw_date != null)
                                            {{ \Carbon\Carbon::parse($product->draw_date)->format('d/m/Y H:i') }}
                                        @endif
                                    </td>
                                    <td>{{ $product->price }}</td>
                                    {{-- <td>
                                        <a href="#exampleModal{{ $product->id }}" class="btn btn-primary"
                                            data-toggle="modal" data-bs-target="#exampleModal{{ $product->id }}"
                                            data-id="{{ $product->id }}"><i class="bi bi-card-text"></i></a>
                                    </td> --}}
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
                                                    @include("product.drop-down-action")

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
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header d-flex align-items-center">
                                        <h4 class="modal-title">Add Rifa</h4>
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">&times;
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <form action="{{ route('addProduct') }}" method="POST"
                                              enctype="multipart/form-data">

                                            {{ csrf_field() }}
                                            <div class="row">
                                                <div class="col-md-12">

                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Modo de Jogo</label>
                                                        <select name="modo_de_jogo" class="form-control">
                                                            <option value="numeros">Números</option>
                                                            <option value="fazendinha-completa">Fazendinha - Grupo
                                                                Completo
                                                            </option>
                                                            <option value="fazendinha-meio">Fazendinha - Meio Grupo
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">

                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Nome</label>
                                                        <input type="text" class="form-control" id="name"
                                                               name="name" placeholder="Exemplo: Fusca 88" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">

                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Sub Titulo</label>
                                                        <input type="text" class="form-control" id="subname"
                                                               name="subname" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">

                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Gateway de Pagamento</label>
                                                        <select name="gateway" class="form-control" required>
                                                            <option></option>
                                                            <option value="mp">Mercado Pago</option>
                                                            <option value="paggue">Paggue</option>
                                                            <option value="asaas">ASAAS</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row d-flex">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleFormControlFile1">Até 3 Imagens</label>
                                                            <input type="file" class="form-control-file"
                                                                   name="images[]" accept="image/*" multiple required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
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

                                            <div class="row mt-2 mb-2">
                                                <div class="col-md-6">
                                                    <label>Qtd mínima de compra</label>
                                                    <input type="number" class="form-control" name="minimo"
                                                           min="1" max="99999" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Qtd máxima de compra</label>
                                                    <input type="number" class="form-control" name="maximo"
                                                           min="1" max="99999" required>
                                                </div>
                                            </div>

                                            <div class="row mt-2 mb-2">
                                                <div class="col-md-6">
                                                    <label for="exampleInputEmail1">Quantidade de números</label>
                                                    <input type="number" class="form-control" name="numbers"
                                                           min="1" max="99999" placeholder="Exemplo: 10" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Tempo de expiração (min)</label>
                                                    <input type="number" class="form-control" name="expiracao"
                                                           min="0" placeholder="Minutos" required>
                                                </div>

                                            </div>

                                            <div class="form-group">
                                                <label for="exampleFormControlTextarea1">Descrição do Sorteio</label>
                                                <textarea class="form-control" id="summernote" name="description"
                                                          rows="10" style="min-height: 200px;"
                                                          required></textarea>
                                            </div>

                                            <button type="submit"
                                                    onClick="this.form.submit(); this.disabled=true; this.innerHTML='Cadastrando…';"
                                                    class="criar btn btn-success">Criar
                                            </button>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>

                        </script>



                    </div>

                </div>

                <!-- Modal -->
                <div class="modal fade" id="modal-ranking" tabindex="-1" aria-labelledby="exampleModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <span id="content-modal-ranking"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="modal-definir-ganhador" tabindex="-1" aria-labelledby="exampleModalLabel"
                     aria-hidden="true">
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
                        success: function (response) {
                            console.log(response);
                            if (response.html) {
                                $('#content-modal-ranking').html(response.html)
                                $('#modal-ranking').modal('show')
                            }
                        },
                        error: function (error) {

                        }
                    })
                }

                document.getElementById("input-add-foto").addEventListener("change", function (el) {
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
                        success: function (response) {
                            if (response.html) {
                                $('#content-modal-definir-ganhador').html(response.html)
                                $('#modal-definir-ganhador').modal('show');
                            }
                        },
                        error: function (error) {

                        }
                    })
                }

                function verGanhadores(id) {
                    $('#content-modal-ver-ganhadores').html('')
                    $.ajax({
                        url: "{{ route('verGanhadores') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            "id": id
                        },
                        success: function (response) {
                            if (response.html) {
                                $('#content-modal-ver-ganhadores').html(response.html)
                                $('#modal-ver-ganhadores').modal('show');
                            }
                        },
                        error: function (error) {

                        }
                    })
                }


            </script>

@endsection
