<form onsubmit="return sendForm($(this));" action="{{ route('product.update', ['id' => $product->id]) }}" method="POST"
      enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="container mt-3">
        <div class="row">
            <div class="col-12">
                <nav>
                    <ul class="nav nav-tabs" id="myTab" role="tablist"
                        style="font-size: 12px;">
                        <li class="nav-item">
                            <a class="nav-link active" id="geral-tab"
                               data-toggle="tab"
                               href="#geral{{ $product->id }}"
                               role="tab" aria-controls="geral"
                               aria-selected="true">Geral</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="premios-tab"
                               data-toggle="tab"
                               href="#premios{{ $product->id }}"
                               role="tab" aria-controls="premios"
                               aria-selected="true">Prêmios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="ajustes-tab"
                               data-toggle="tab"
                               href="#ajustes{{ $product->id }}"
                               role="tab" aria-controls="ajustes"
                               aria-selected="false">Ajustes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="promocao-tab"
                               data-toggle="tab"
                               href="#promocao{{ $product->id }}"
                               role="tab" aria-controls="promocao"
                               aria-selected="false">Promoção</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="compraAuto-tab"
                               data-toggle="tab"
                               href="#compraAuto{{ $product->id }}"
                               role="tab" aria-controls="compraAuto"
                               aria-selected="false">Compra Automática</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="faq-tab"
                               data-toggle="tab"
                               href="#faq{{ $product->id }}"
                               role="tab" aria-controls="faq"
                               aria-selected="false">FAQs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="fotos-tab"
                               data-toggle="tab"
                               href="#fotos{{ $product->id }}"
                               role="tab" aria-controls="fotos"
                               aria-selected="false">Fotos</a>
                        </li>

                    </ul>
                </nav>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active"
                         id="geral{{ $product->id }}" role="tabpanel"
                         aria-labelledby="geral-tab">
                        @include("admin.products.parts.general-tab-form")
                    </div>

                    <div class="tab-pane fade show"
                         id="premios{{ $product->id }}" role="tabpanel"
                         aria-labelledby="geral-tab">
                        <div class="row">
                            @include("admin.products.parts.premium-tab-form")
                        </div>
                    </div>

                    <div class="tab-pane fade"
                         id="ajustes{{ $product->id }}" role="tabpanel"
                         aria-labelledby="ajustes-tab">
                        @include("admin.products.parts.ajustes-tab-form")
                    </div>


                    <div class="tab-pane fade"
                         id="promocao{{ $product->id }}" role="tabpanel"
                         aria-labelledby="promocao-tab">
                        @include("admin.products.parts.promo-tab-form")

                    </div>

                    <div class="tab-pane fade"
                         id="compraAuto{{ $product->id }}" role="tabpanel"
                         aria-labelledby="promocao-tab">
                        @include("admin.products.parts.auto-buy-form")
                    </div>
                    <div class="tab-pane fade"
                         id="faq{{ $product->id }}" role="tabpanel"
                         aria-labelledby="faq-tab">

                        @include("admin.products.parts.faq-tab-form")
                    </div>
                    <div class="tab-pane fade" id="fotos{{ $product->id }}"
                         role="tabpanel" aria-labelledby="promocao-tab">
                        @include("admin.products.parts.images-tab")
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="separator-20" style="height: 20px"></div>
    <div class="row mb-4">
        <button type="submit" class="criar btn btn-lg btn-success"><i class="fa fa-save"></i> Salvar</button>
    </div>
</form>