<?php

?>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active"
         id="geral{{ $product->id }}" role="tabpanel"
         aria-labelledby="geral-tab">
        @include("admin.products.parts.general-tab-form")


        <div class="row mt-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Qtd mínima de
                        compra</label>
                    <input type="number" class="form-control"
                           min="1" max="999999"
                           name="minimo"
                           value="{{ $product->minimo }}">
                </div>
            </div>
            <div class="col-md-4">
                <label for="">Qtd máxima de
                    compra</label>
                <div class="input-group">
                    <input type="number" class="form-control"
                           name="maximo"
                           value="{{ $product->maximo }}">
                </div>
            </div>
            <div class="col-md-4">
                <label for="">Tempo de expiração (min)
                </label>
                <div class="input-group">
                    <input type="number" class="form-control"
                           name="expiracao" min="0"
                           value="{{ $product->expiracao }}">
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <label for="">Mostar Ranking de compradores (Qtd)</label>
                <div class="input-group">
                    <input type="number" class="form-control"
                           name="qtd_ranking"
                           value="{{ $product->qtd_ranking }}">
                </div>
            </div>

            <div class="col-md-6">
                <label>Mostrar Parcial (%)</label>
                <select name="parcial" class="form-control">
                    <option value="1"
                            {{ $product->parcial == 1 ? 'selected' : '' }}>
                        Sim
                    </option>
                    <option value="0"
                            {{ $product->parcial == 0 ? 'selected' : '' }}>
                        Não
                    </option>
                </select>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <label>Gateway de Pagamento</label>
                <select name="gateway" class="form-control">
                    <option value="mp" {{ $product->gateway == 'mp' ? 'selected' : '' }}>Mercado Pago</option>
                    <option value="paggue" {{ $product->gateway == 'paggue' ? 'selected' : '' }}>Paggue</option>
                    <option value="asaas" {{ $product->gateway == 'asaas' ? 'selected' : '' }}>ASAAS</option>
                </select>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <label>Descrição</label>
                <textarea class="form-control summernote" name="description" id="desc-{{ $product->id }}" rows="10"
                          style="min-height: 200px;" required>{!! $product->descricao() !!}</textarea>
            </div>
        </div>

        {{-- <div class="form-group">
            <label for="exampleInputEmail1">Quantidade de números</label>
            <input type="number" class="form-control" name="numbers" min="1"
                   max="99999" value="{{$product->total_number}}" required>
        </div>
        <div class="form-group">
            <label for="exampleFormControlTextarea1">Descrição do Sorteio</label>
            <textarea class="form-control" id="summernote" name="description" rows="3" value="">{{$product->description}}</textarea>
        </div> --}}
    </div>

    <div class="tab-pane fade show"
         id="premios{{ $product->id }}" role="tabpanel"
         aria-labelledby="geral-tab">
        <div class="row">
            @foreach ($product->prizeDraws() as $premio)
                <div class="col-md-6 mt-2">
                    <label>{{ $premio->ordem }}º Prêmio</label>
                    <input type="text" class="form-control" name="descPremio[{{ $premio->ordem }}]"
                           value="{{ $premio->descricao }}">
                </div>
            @endforeach
        </div>
    </div>

    <div class="tab-pane fade"
         id="ajustes{{ $product->id }}" role="tabpanel"
         aria-labelledby="ajustes-tab">
        <div class="row mt-3">
            <div class="col-5">
                <div class="form-group">
                    <label for="status_sorteio">Status
                        Sorteio</label>
                    <select class="form-control"
                            name="status" id="status">
                        <option value="Inativo"
                                {{ $product->status == 'Inativo' ? "selected='selected'" : '' }}>
                            Inativo
                        </option>
                        <option value="Ativo"
                                {{ $product->status == 'Ativo' ? "selected='selected'" : '' }}>
                            Ativo
                        </option>
                        <option value="Finalizado"
                                {{ $product->status == 'Finalizado' ? "selected='selected'" : '' }}>
                            Finalizado
                        </option>
                    </select>
                </div>
            </div>
            <form action="{{ route('drawDate') }}"
                  method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="product_id"
                       value="{{ $product->id }}">
                <div class="col-12 col-md-7">
                    <div class="form-group">
                        <label for="data_sorteio">Data
                            Sorteio</label>
                        <input type="datetime-local"
                               class="form-control"
                               name="data_sorteio"
                               id="data_sorteio"
                               value="{{ $product->draw_date ? date('Y-m-d H:i:s', strtotime($product->draw_date)) : ''}}">
                    </div>
                </div>
            </form>
        </div>
        <div class="row mt-3">
            <div class="col-sm">
                <div class="form-group">
                    <label
                            for="cadastrar_ganhador">Ganhador</label>
                    <input type="text" class="form-control"
                           name="cadastrar_ganhador"
                           id="cadastrar_ganhador"
                           value="{{ $product->winner }}">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="visible_rifa">Mostrar na Página
                        Inicial?</label>
                    <select class="form-control"
                            name="visible" id="visible">
                        <option value="0">Não</option>
                        <option value="1"
                                {{ $product->visible == 1 ? "selected='selected'" : '' }}>
                            Sim
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>URL amigável</label>
                <input type="text" name="slug" value="{{ $product->slug }}" class="form-control">
            </div>
            {{-- <div class="col-md-4">
                <label>Qtd de zeros</label>
                <input type="number" name="qtd_zeros" value="{{ $product->qtd_zeros }}" class="form-control">
            </div> --}}
        </div>
        <div class="row mt-3">
            <div class="col">
                <div class="form-group">
                    <label for="favoritar_rifa">Favoritar
                        Rifa</label>
                    <select class="form-control"
                            name="favoritar_rifa"
                            id="favoritar_rifa">
                        <option value="0">Não</option>
                        <option value="1"
                                {{ $product->favoritar == 1 ? "selected='selected'" : '' }}>
                            Sim
                        </option>
                    </select>
                </div>
            </div>
            {{-- <div class="col-12 col-md-7">
                <div class="form-group">
                    <label for="previsao_sorteio">Previsão
                        Sorteio</label>
                    <input type="datetime-local"
                        class="form-control"
                        name="previsao_sorteio"
                        value="{{ $product->draw_prediction ?date('Y-m-d H:i:s', strtotime($product->draw_prediction)) : '' }}">
                </div>
            </div> --}}
        </div>

        <div class="row mt-3">
            <div class="col">
                <div class="form-group">
                    <label for="tipo_reserva">Tipo de
                        Reserva?</label>
                    <select class="form-control"
                            name="tipo_reserva" id="tipo_reserva">
                        <option value="manual"
                                {{ $product->type_raffles == 'manual' ? "selected='selected'" : '' }}>
                            Manual
                        </option>
                        <option value="automatico"
                                {{ $product->type_raffles == 'automatico' ? "selected='selected'" : '' }}>
                            Automático
                        </option>
                        <option value="mesclado"
                                {{ $product->type_raffles == 'mesclado' ? "selected='selected'" : '' }}>
                            Automático & Manual
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row mt-1 d-flex justify-content-center">
            <p>Tipo de Rifa</p>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="rifa_numero">Rifa de Números ou
                        Fazendinha</label>
                    <select class="form-control"
                            name="rifa_numero" id="rifa_numero" disabled>
                        <option value="numeros"
                                {{ $product->modo_de_jogo == 'numeros' ? "selected='selected'" : '' }}>
                            Números
                        </option>
                        <option value="fazendinha-completa"
                                {{ $product->modo_de_jogo == 'fazendinha-completa' ? "selected='selected'" : '' }}>
                            Fazendinha - Grupo Completo
                        </option>
                        <option value="fazendinha-meio"
                                {{ $product->modo_de_jogo == 'fazendinha-meio' ? "selected='selected'" : '' }}>
                            Fazendinha - Meio Grupo
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>


    <div class="tab-pane fade"
         id="promocao{{ $product->id }}" role="tabpanel"
         aria-labelledby="promocao-tab">

        @foreach ($product->promos()->get() as $promo)
            <div class="row text-center mt-2 promo">
                <h5>Promoção {{ $promo->ordem }}</h5>
                <div class="col-md-6">
                    <label>Qtd de números</label>
                    <input type="number" min="0"
                           name="numPromocao[{{ $promo->ordem }}]"
                           max="10000"
                           class="form-control text-center"
                           value="{{ $promo->qtdNumeros }}">
                </div>
                <div class="col-md-6">
                    <label
                            for="exampleInputEmail1">% de Desconto</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                                                                                        <span
                                                                                                class="input-group-text">%</span>
                        </div>
                        <input type="text"
                               class="form-control text-center"
                               name="valPromocao[{{ $promo->ordem }}]"
                               value="{{ $promo->desconto }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="tab-pane fade" id="fotos{{ $product->id }}"
         role="tabpanel" aria-labelledby="promocao-tab">
        <center>
            <button type="button"
                    class="btn btn-sm btn-info"
                    data-id="{{ $product->id }}"
                    onclick="addFoto(this)">+ Foto(s)
            </button>
        </center>
        <div class="row d-flex justify-content-center mt-4">
            @if ($product->fotos()->count() > 0)
                @foreach ($product->fotos() as $key => $foto)
                    <div class="col-md-4 text-center"
                         id="foto-{{ $foto->id }}">
                        <img src="{{ imageAsset($foto->name) }}"
                             width="200"
                             style="border-radius: 10px;">
                        @if($key != 0)
                            <a data-qtd="{{ $product->fotos()->count() }}" href="javascript:void(0)"
                               class="delete btn btn-danger"
                               onclick="excluirFoto(this)"
                               data-id="{{ $foto->id }}"><i
                                        class="bi bi-trash3"></i></a>
                        @endif

                    </div>
                @endforeach
            @endif
        </div>

    </div>
</div>