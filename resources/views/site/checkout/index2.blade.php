
    <div class="modal-content" style="border: none;">
        <div class="modal-header" style="background-color: #939393;color: #fff;">
            <h5 class="modal-title" id="staticBackdropLabel">FINALIZAR RESERVA</h5>
            <button type="button" class="btn btn-link text-white menu-mobile--button pe-0 font-lgg"
                    data-bs-dismiss="modal" aria-label="Fechar"><i class="bi bi-x-circle"></i></button>
        </div>
        <div class="modal-body" style="background: #efefef;color: #939393;">
            @if($cart->random_numbers>0)
                <div class="form-group"
                     style="background-color: #cff4fc;padding: 10px;border-radius: 10px;color: #055160;">
                        <span>Você está adquirindo <?= $cart->getNumbersQty() ?> cota(s) da ação entre amigos
                            <strong><?= $product['name'] ?></strong>.</span>
                </div>
            @endif

            <small class="form-text d-none" style="color: green;">
                <b>
                    Valor a pagar: <small style="font-size: 15px;"> <?= formatMoney($cart->total) ?></small>
                </b>
            </small>

            <div class="form-group">
                <input type="hidden" name="tokenAfiliado" value="{{ $tokenAfiliado }}">
                <div class="row">
                    <div class="col-md-12 d-none">
                        <div class="numberSelected" id="numberSelectedModal"
                             style="overflow-y: auto;width: 190px;">
                            @include("site.layouts.parts.number-selected",['numbers' => $numbers,'hasClickBtn' => false])

                        </div>
                    </div>
                </div>
            </div>

            @if($cart->random_numbers>0)
                <div class="form-group"
                     style="background-color: #cff4fc;padding: 10px;border-radius: 10px;color: #055160;">
                        <span>Você está adquirindo <?= $cart->getNumbersQty() ?> cota(s) da ação entre amigos
                            <strong><?= $product['name'] ?></strong> , seu(s) número(s)
                            sera(ão) gerado(s) assim que concluir a compra.</span>
                </div>
            @endif

            <div class="form-group d-flex d-none" id="div-customer">
                <div>
                    <img src="{{ cdnImageAsset('default-user.jpg') }}"
                         style="width: 70px; height: 70px;border-radius: 10px;">
                </div>

                <div class="ml-2" style="color: #000">
                    <h4 id="customer-name">Mario Souza</h4>
                    <h5 id="customer-phone">(15) 99770-6933</h5>
                </div>
            </div>

            <div class="form-group" id="div-telefone">
                <label style="color: #000"><strong>Informe seu telefone</strong></label>
                <input type="text" class="form-control numbermask keydown"
                       style="background-color: #fff;border: none;color: #333;" name="telephone" id="telephone1"
                       placeholder="(00) 90000-0000" maxlength="15" required>
                <input type="hidden" name="telephone" id="phone-cliente">
                <input type="hidden" id="customer" name="customer">
            </div>

            <div class="form-group d-none" id="div-nome">
                <label style="color: #000">
                    <strong>Nome Completo</strong>
                </label>
                <input type="text" class="form-control"
                       style="background-color: #fff;border: none;color: #333;" name="name" id="name"
                       required>
            </div>

            <div class="form-group" id="div-info"
                 style="background-color: #fff3cd;padding: 10px;border-radius: 10px;color: #664d03;">
                        <span><i class="fas fa-info-circle"></i>&nbsp;<span id="info-footer">Informe seu telefone para
                                continuar.</span></span>
            </div>

            <button class="btn btn-block btn-primary" id="btn-checkout-action" onclick="checkCustomer()"
                    type="button"><strong id="btn-checkout">Continuar</strong></button>

            <center>
                <button class="btn btn-sm btn-outline-secondary mt-2 d-none" id="btn-alterar"
                        onclick="clearModal()">Alterar Telefone
                </button>
            </center>
            <input type="hidden" id="promo" name="promo">
        </div>
    </div>
