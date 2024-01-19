@extends('layouts.admin')
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
                <button class="mr-2 mb-2 btn btn-success" type="button" style="margin-top: 30px;" onclick="return loadUrlModal('Editar Rifa', 'https://new-rifando.10mb.com.br/product/79', true)">
                    Salvar <i class="os-icon os-icon-grid-18"></i></button>
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
                                                    <?php

                                                        ?>
                                                    <a class="dropdown-item" style="cursor: pointer" onclick="return productEdit(<?=$product->id ?>) "><i
                                                                class="bi bi-pencil-square"></i>&nbsp;Editar</a>
                                                    <a class="dropdown-item"
                                                       href="#deleteEmployeeModal{{ $product->id }}"
                                                       style="cursor: pointer" data-toggle="modal"
                                                       data-bs-target="#deleteEmployeeModal{{ $product->id }}"
                                                       data-id="{{ $product->id }}"><i class="bi bi-trash3"></i>&nbsp;Excluir</a>
                                                    <a class="dropdown-item"
                                                       href="{{ route('rifa.compras', $product->id) }}"><i
                                                                class="fas fa-shopping-bag"></i></i>&nbsp;Compras</a>
                                                    {{-- <a class="dropdown-item" href="{{ route('resumoRifaPDF', $product->id) }}" target="_blank"><i class="far fa-file-pdf"></i>&nbsp;PDF</a> --}}
                                                    <a class="dropdown-item"
                                                       href="{{ route('resumoRifa', $product->id) }}" target="_blank"><i
                                                                class="fas fa-list-ol"></i>&nbsp;Ver Resumo</a>
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
                        <!-- Modal Editar Rifa -->
                        @foreach ($rifas as $key => $product)
                            <div id="modal_editar_rifa{{ $product->id }}" class="modal fade">
                                <div class="modal-dialog modal-lg">

                                </div>
                            </div>
                        @endforeach


                    </div>
                    {{-- @foreach ($rifas as $key => $product)
                        <div class="modal fade" id="exampleModal{{ $product->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Números do Sorteio
                                            {{ $product->id }} - {{ $product->name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Total de Números Disponíveis</h5>
                                                        <p class="card-text">{{ $product->qtdNumerosDisponiveis() }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Total de Números Reservados</h5>
                                                        <p class="card-text">{{ $product->qtdNumerosReservados() }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Total de Números Pagos</h5>
                                                        <p class="card-text">{{ $product->qtdNumerosPagos() }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th class="text-center" scope="col">#Pedido</th>
                                                        <th class="text-left" scope="col">Participant</th>
                                                        <th class="text-center" scope="col">Total Reservas</th>
                                                        <th class="text-center" scope="col">Total Pagas</th>
                                                        <th class="text-center" scope="col">CPF</th>
                                                        <th class="text-center" scope="col">Celular</th>
                                                        <th class="text-center" scope="col">Números</th>
                                                        <th class="text-center" scope="col">Status Num</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($product->participantes() as $participante)
                                                        <tr>
                                                            <th class="text-center" scope="row">#</th>
                                                            <td class="text-left">{{ $participante->name }}</td>
                                                            <td class="text-center">
                                                                {{ $participante->reservados()->count() }}</td>
                                                            <td class="text-center">{{ $participante->pagos()->count() }}
                                                            </td>
                                                            <td class="text-center">{{ $participante->cpf }}</td>
                                                            <td class="text-center">{{ $participante->telephone }}</td>
                                                            <td class="text-center"><button
                                                                    id="{{ $product->id }}-{{ $participante->id }}"
                                                                    type="button"
                                                                    class="btn btn-primary btn-sm see-numbers">VISUALIZAR</button>
                                                            </td>
                                                            <td class="text-center">
                                                                <form action="{{ route('pagarReservas') }}"
                                                                    method="POST">
                                                                    {{ csrf_field() }}
                                                                    <input type="hidden" name="participante"
                                                                        value="{{ $participante->id }}">
                                                                    <button
                                                                        id="{{ $product->id }}-{{ $participante->id }}"
                                                                        onclick="return confirm('Confirmar pagamento ?')"
                                                                        type="submit"
                                                                        class="btn btn-primary btn-sm see-numbers">Pagar</button>
                                                                </form>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                        <tr id="tr-reserved-{{ $product->id }}-{{ $participante->id }}"
                                                            class="hidden">
                                                            <td></td>
                                                            <td colspan="11">
                                                                @if ($participante->reservados()->count() === 0)
                                                                    <b>Números Reservados:</b>
                                                                    <div class="alert" style="padding-left: 0px">
                                                                        <i class="fa fa-exclamation-circle"></i> Cliente
                                                                        sem reservas para esse sorteio
                                                                    </div>
                                                                @else
                                                                    <span class="text-secondary"><b>Números Reservados:</b>
                                                                        @foreach ($participante->reservados() as $reserva)
                                                                            <span class="badge bg-secondary"> <i
                                                                                    class="fa fa-clock"></i>
                                                                                {{ $reserva->number }}</span>
                                                                        @endforeach
                                                                        <form form
                                                                            action="{{ route('releaseReservedRafflesNumbers') }}"
                                                                            method="POST" enctype="multipart/form-data">
                                                                            <!--@method('PUT')-->
                                                                            {{ csrf_field() }}
                                                                            <input type="hidden"
                                                                                name="release_reservervations"
                                                                                id="release_reservervations"
                                                                                value="{{ $participante->id }}">
                                                                            <button onclick="confirm('Você tem certeza?')"
                                                                                type="submit"
                                                                                style="margin-top:1rem;margin-bottom:1rem;text-transform:uppercase"
                                                                                class="btn btn-primary btn-sm col-lg-12">
                                                                                LIBERAR TODAS RESERVAS
                                                                                ({{ $participante->name }})
                                                                            </button>
                                                                        </form>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr id="tr-payed-{{ $product->id }}-{{ $participante->id }}"
                                                            class="hidden">
                                                            <td></td>
                                                            <td colspan="11">
                                                                @if ($participante->pagos()->count() === 0)
                                                                    <b>Números Pagos:</b>
                                                                    <div class="alert" style="padding-left: 0px">
                                                                        <i class="fa fa-exclamation-circle"></i> Nenhum
                                                                        pagamento realizado
                                                                    </div>
                                                                @else
                                                                    <span class="text-success">
                                                                        <b>Números Pagos:</b>
                                                                    </span>
                                                                    @foreach ($participante->pagos() as $reserva)
                                                                        <span class="badge bg-success"> <i
                                                                                class="fa fa-check"></i>
                                                                            {{ $reserva->number }}</span>
                                                                    @endforeach
                                                                @endif

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Fechar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @push('scripts')
                            <script>
                                const numberButtons = document.getElementsByClassName("see-numbers");

                                const getNumbers = e => {
                                    let trNumbersReserverd = "#tr-reserved-" + e.target.id;
                                    let trNumbersPayed = "#tr-payed-" + e.target.id;
                                    toggleClass(trNumbersReserverd, 'hidden');
                                    toggleClass(trNumbersPayed, 'hidden');
                                    console.log(trNumbersReserverd, trNumbersPayed);
                                }

                                for (let button of numberButtons) {
                                    button.addEventListener("click", getNumbers);
                                }

                                function toggleClass(el, className) {
                                    var el = document.querySelectorAll(el);
                                    console.log(el);
                                    for (i = 0; i < el.length; i++) {

                                        if (el[i].classList) {
                                            el[i].classList.toggle(className);
                                        } else {
                                            var classes = el[i].className.split(' ');
                                            var existingIndex = -1;
                                            for (var j = classes.length; j--;) {
                                                if (classes[j] === className)
                                                    existingIndex = j;
                                            }

                                            if (existingIndex >= 0)
                                                classes.splice(existingIndex, 1);
                                            else
                                                classes.push(className);

                                            el[i].className = classes.join(' ');
                                        }
                                    }
                                }

                                $(document).ready(function() {



                                    var columnDefs = [{
                                            data: "number",
                                            title: "Número",
                                        },
                                        {
                                            data: "status",
                                            title: "Status",
                                        },
                                        {
                                            data: "name",
                                            title: "Participant",
                                        },
                                        {
                                            data: "telephone",
                                            title: "Celular",
                                        },
                                        {
                                            data: "created_at",
                                            title: "Reserva Em",
                                        },
                                        {
                                            data: "updated_at",
                                            title: "Pago Em",
                                        },
                                        {
                                            title: "Status de pagamento",
                                            render: function(data, type, row) {
                                                return '<button class="btn btn-primary">Alterar</button>';
                                            }
                                        },
                                    ];

                                    var myTable;
                                    $('#example{{ $product->id }}').on('click', 'tbody tr td:nth-child(7)', function() {
                                        var rowData = myTable.row(this).data();
                                        var id = myTable.row(this).data('id');

                                        console.log('{{ $product->id }}', id[0][0]);

                                        $.ajax({
                                                // a tipycal url would be /{id} with type='POST'
                                                headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                },
                                                url: '{{ route('editRaffles') }}',
                                                type: 'POST',
                                                data: {
                                                    product_id: '{{ $product->id }}',
                                                    rowData,
                                                },
                                            }).then(function(response) {
                                                console.log(response);

                                                return myTable.row(id[0][0]).data({
                                                    name: response[0].name,
                                                    number: response[0].number,
                                                    status: response[0].status,
                                                    telephone: response[0].telephone,
                                                    updated_at: response[0].updated_at,
                                                    created_at: response[0].created_at
                                                })
                                            })
                                            .catch(function(error) {
                                                console.log(error);
                                                alert("Tente Novamente!")
                                            });
                                    });

                                    $.fn.dataTable.ext.errMode = 'none';


                                    myTable = $('#example{{ $product->id }}').DataTable({
                                        "processing": true,
                                        "serverSide": true,
                                        ajax: {
                                            url: '{{ route('getRaffles') }}',
                                            data: {
                                                'product_id': '{{ $product->id }}'
                                            },
                                        },
                                        columns: columnDefs,
                                        lengthMenu: [
                                            [10, 25, 50, 99999],
                                            ['10 linhas', '25 linhas', '50 linhas', 'Todos']
                                        ],
                                        dom: 'Blfrtip',
                                        buttons: [
                                            'excel', 'pdf', 'csv', 'print'
                                        ],
                                        select: 'multiple',
                                        pageLength: 10,
                                        deferRender: false,
                                        altEditor: true, // Enable altEditor

                                        initComplete: function() {
                                            // Setup - add a text input to each footer cell
                                            $('#example{{ $product->id }} tfoot th').each(function() {
                                                var title = $(this).text();
                                                console.log(title);
                                                $(this).html(
                                                    '<input type="text" style="width:100%;" placeholder="" />');
                                            });

                                            // Apply the search
                                            this.api().columns().every(function() {
                                                var that = this;

                                                $('input', this.footer()).on('keyup change clear', function() {
                                                    if (that.search() !== this.value) {
                                                        that
                                                            .search(this.value)
                                                            .draw();
                                                    }
                                                });
                                            });
                                        },

                                        language: {
                                            "emptyTable": "Nenhum registro encontrado",
                                            "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                                            "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                                            "infoFiltered": "(Filtrados de _MAX_ registros)",
                                            "infoThousands": ".",
                                            "loadingRecords": "Carregando...",
                                            "processing": "Processando...",
                                            "zeroRecords": "Nenhum registro encontrado",
                                            "search": "Pesquisar",
                                            "paginate": {
                                                "next": "Próximo",
                                                "previous": "Anterior",
                                                "first": "Primeiro",
                                                "last": "Último"
                                            },
                                            "aria": {
                                                "sortAscending": ": Ordenar colunas de forma ascendente",
                                                "sortDescending": ": Ordenar colunas de forma descendente"
                                            },
                                            "select": {
                                                "rows": {
                                                    "_": "Selecionado %d linhas",
                                                    "1": "Selecionado 1 linha"
                                                },
                                                "cells": {
                                                    "1": "1 célula selecionada",
                                                    "_": "%d células selecionadas"
                                                },
                                                "columns": {
                                                    "1": "1 coluna selecionada",
                                                    "_": "%d colunas selecionadas"
                                                }
                                            },
                                            "buttons": {
                                                "copySuccess": {
                                                    "1": "Uma linha copiada com sucesso",
                                                    "_": "%d linhas copiadas com sucesso"
                                                },
                                                "collection": "Coleção  <span class=\"ui-button-icon-primary ui-icon ui-icon-triangle-1-s\"><\/span>",
                                                "colvis": "Visibilidade da Coluna",
                                                "colvisRestore": "Restaurar Visibilidade",
                                                "copy": "Copiar",
                                                "copyKeys": "Pressione ctrl ou u2318 + C para copiar os dados da tabela para a área de transferência do sistema. Para cancelar, clique nesta mensagem ou pressione Esc..",
                                                "copyTitle": "Copiar para a Área de Transferência",
                                                "csv": "CSV",
                                                "excel": "Excel",
                                                "pageLength": {
                                                    "-1": "Mostrar todos os registros",
                                                    "_": "Mostrar %d registros"
                                                },
                                                "pdf": "PDF",
                                                "print": "Imprimir",
                                                "createState": "Criar estado",
                                                "removeAllStates": "Remover todos os estados",
                                                "removeState": "Remover",
                                                "renameState": "Renomear",
                                                "savedStates": "Estados salvos",
                                                "stateRestore": "Estado %d",
                                                "updateState": "Atualizar"
                                            },
                                            "autoFill": {
                                                "cancel": "Cancelar",
                                                "fill": "Preencher todas as células com",
                                                "fillHorizontal": "Preencher células horizontalmente",
                                                "fillVertical": "Preencher células verticalmente"
                                            },
                                            "lengthMenu": "Exibir _MENU_ resultados por página",
                                            "searchBuilder": {
                                                "add": "Adicionar Condição",
                                                "button": {
                                                    "0": "Construtor de Pesquisa",
                                                    "_": "Construtor de Pesquisa (%d)"
                                                },
                                                "clearAll": "Limpar Tudo",
                                                "condition": "Condição",
                                                "conditions": {
                                                    "date": {
                                                        "after": "Depois",
                                                        "before": "Antes",
                                                        "between": "Entre",
                                                        "empty": "Vazio",
                                                        "equals": "Igual",
                                                        "not": "Não",
                                                        "notBetween": "Não Entre",
                                                        "notEmpty": "Não Vazio"
                                                    },
                                                    "number": {
                                                        "between": "Entre",
                                                        "empty": "Vazio",
                                                        "equals": "Igual",
                                                        "gt": "Maior Que",
                                                        "gte": "Maior ou Igual a",
                                                        "lt": "Menor Que",
                                                        "lte": "Menor ou Igual a",
                                                        "not": "Não",
                                                        "notBetween": "Não Entre",
                                                        "notEmpty": "Não Vazio"
                                                    },
                                                    "string": {
                                                        "contains": "Contém",
                                                        "empty": "Vazio",
                                                        "endsWith": "Termina Com",
                                                        "equals": "Igual",
                                                        "not": "Não",
                                                        "notEmpty": "Não Vazio",
                                                        "startsWith": "Começa Com",
                                                        "notContains": "Não contém",
                                                        "notStarts": "Não começa com",
                                                        "notEnds": "Não termina com"
                                                    },
                                                    "array": {
                                                        "contains": "Contém",
                                                        "empty": "Vazio",
                                                        "equals": "Igual à",
                                                        "not": "Não",
                                                        "notEmpty": "Não vazio",
                                                        "without": "Não possui"
                                                    }
                                                },
                                                "data": "Data",
                                                "deleteTitle": "Excluir regra de filtragem",
                                                "logicAnd": "E",
                                                "logicOr": "Ou",
                                                "title": {
                                                    "0": "Construtor de Pesquisa",
                                                    "_": "Construtor de Pesquisa (%d)"
                                                },
                                                "value": "Valor",
                                                "leftTitle": "Critérios Externos",
                                                "rightTitle": "Critérios Internos"
                                            },
                                            "searchPanes": {
                                                "clearMessage": "Limpar Tudo",
                                                "collapse": {
                                                    "0": "Painéis de Pesquisa",
                                                    "_": "Painéis de Pesquisa (%d)"
                                                },
                                                "count": "{total}",
                                                "countFiltered": "{shown} ({total})",
                                                "emptyPanes": "Nenhum Painel de Pesquisa",
                                                "loadMessage": "Carregando Painéis de Pesquisa...",
                                                "title": "Filtros Ativos",
                                                "showMessage": "Mostrar todos",
                                                "collapseMessage": "Fechar todos"
                                            },
                                            "thousands": ".",
                                            "datetime": {
                                                "previous": "Anterior",
                                                "next": "Próximo",
                                                "hours": "Hora",
                                                "minutes": "Minuto",
                                                "seconds": "Segundo",
                                                "amPm": [
                                                    "am",
                                                    "pm"
                                                ],
                                                "unknown": "-",
                                                "months": {
                                                    "0": "Janeiro",
                                                    "1": "Fevereiro",
                                                    "10": "Novembro",
                                                    "11": "Dezembro",
                                                    "2": "Março",
                                                    "3": "Abril",
                                                    "4": "Maio",
                                                    "5": "Junho",
                                                    "6": "Julho",
                                                    "7": "Agosto",
                                                    "8": "Setembro",
                                                    "9": "Outubro"
                                                },
                                                "weekdays": [
                                                    "Domingo",
                                                    "Segunda-feira",
                                                    "Terça-feira",
                                                    "Quarta-feira",
                                                    "Quinte-feira",
                                                    "Sexta-feira",
                                                    "Sábado"
                                                ]
                                            },
                                            "editor": {
                                                "close": "Fechar",
                                                "create": {
                                                    "button": "Novo",
                                                    "submit": "Criar",
                                                    "title": "Criar novo registro"
                                                },
                                                "edit": {
                                                    "button": "Editar",
                                                    "submit": "Atualizar",
                                                    "title": "Editar registro"
                                                },
                                                "error": {
                                                    "system": "Ocorreu um erro no sistema (<a target=\"\\\" rel=\"nofollow\" href=\"\\\">Mais informações<\/a>)."
                                                },
                                                "multi": {
                                                    "noMulti": "Essa entrada pode ser editada individualmente, mas não como parte do grupo",
                                                    "restore": "Desfazer alterações",
                                                    "title": "Multiplos valores",
                                                    "info": "Os itens selecionados contêm valores diferentes para esta entrada. Para editar e definir todos os itens para esta entrada com o mesmo valor, clique ou toque aqui, caso contrário, eles manterão seus valores individuais."
                                                },
                                                "remove": {
                                                    "button": "Remover",
                                                    "confirm": {
                                                        "_": "Tem certeza que quer deletar %d linhas?",
                                                        "1": "Tem certeza que quer deletar 1 linha?"
                                                    },
                                                    "submit": "Remover",
                                                    "title": "Remover registro"
                                                }
                                            },
                                            "decimal": ",",
                                            "stateRestore": {
                                                "creationModal": {
                                                    "button": "Criar",
                                                    "columns": {
                                                        "search": "Busca de colunas",
                                                        "visible": "Visibilidade da coluna"
                                                    },
                                                    "name": "Nome:",
                                                    "order": "Ordernar",
                                                    "paging": "Paginação",
                                                    "scroller": "Posição da barra de rolagem",
                                                    "search": "Busca",
                                                    "searchBuilder": "Mecanismo de busca",
                                                    "select": "Selecionar",
                                                    "title": "Criar novo estado",
                                                    "toggleLabel": "Inclui:"
                                                },
                                                "duplicateError": "Já existe um estado com esse nome",
                                                "emptyError": "Não pode ser vazio",
                                                "emptyStates": "Nenhum estado salvo",
                                                "removeConfirm": "Confirma remover %s?",
                                                "removeError": "Falha ao remover estado",
                                                "removeJoiner": "e",
                                                "removeSubmit": "Remover",
                                                "removeTitle": "Remover estado",
                                                "renameButton": "Renomear",
                                                "renameLabel": "Novo nome para %s:",
                                                "renameTitle": "Renomear estado"
                                            }
                                        }
                                    });
                                });
                            </script>
                        @endpush
                    @endforeach --}}
                    <!-- Delete Modal HTML -->
                    @foreach ($rifas as $key => $product)
                        <div id="deleteEmployeeModal{{ $product->id }}" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('destroy') }}" method="POST" enctype="multipart/form-data">
                                        @method('DELETE')
                                        {{ csrf_field() }}
                                        <div class="modal-header">
                                            <h4 class="modal-title">Deletar Rifa</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">&times;
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Tem certeza de que deseja excluir esse registros?</p>
                                            <p class="text-warning"><small>Essa ação não pode ser desfeita..</small></p>
                                            <input name="deleteId" type="hidden" id="deleteId"
                                                   value="{{ $product->id }}">
                                        </div>
                                        <div class="modal-footer">
                                            <input type="button" class="btn btn-default" data-dismiss="modal"
                                                   value="Cancelar">
                                            <input type="submit" class="btn btn-danger" value="Deletar">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
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

                function formatarMoeda() {
                    var elemento = document.getElementById('price');
                    var valor = elemento.value;


                    valor = valor + '';
                    valor = parseInt(valor.replace(/[\D]+/g, ''));
                    valor = valor + '';
                    valor = valor.replace(/([0-9]{2})$/g, ",$1");

                    if (valor.length > 6) {
                        valor = valor.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
                    }

                    elemento.value = valor;
                    if (valor == 'NaN') elemento.value = '';

                }

                function copyResumoLink(link) {
                    const element = document.querySelector('#copy-link');
                    const storage = document.createElement('textarea');
                    storage.value = link;
                    element.appendChild(storage);

                    // Copy the text in the fake `textarea` and remove the `textarea`
                    storage.select();
                    storage.setSelectionRange(0, 99999);
                    document.execCommand('copy');
                    element.removeChild(storage);

                    alert("LINK para resumo copiado com sucesso.");
                }
            </script>

@endsection
