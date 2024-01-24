<form action="{{ route('bookProductManualy') }}" id="form-checkout" method="POST">
    {{ csrf_field() }}


    <div class="form-group">
        <input type="hidden" name="tokenAfiliado" value="{{ $tokenAfiliado }}">

        @if(isset($cart['uuid']))
            <input type="hidden" id="cart_uuid" value="{{ $cart['uuid'] }}">
        @endif
    </div>

    <div class="form-group d-flex d-none" id="div-customer">
        <div>
            <img src="{{ cdnImageAsset('default-user.jpg') }}" style="width: 70px; height: 70px; border-radius: 10px;">
        </div>
        <div class="ml-2" style="color: #000">
            <h4 id="customer-name">Jhon doe</h4>
            <h5 id="customer-phone">(11) 99999-6933</h5>
        </div>
    </div>

    <div class="form-group" id="div-telefone">
        <label style="color: #000"><strong>Informe seu telefone</strong></label>
        <input type="text" class="form-control numbermask keydown"
               style="background-color: #fff; border: none; color: #333;" name="telephone" id="telephone1"
               placeholder="(00) 90000-0000" maxlength="15" required>
        <input type="hidden" name="telephone" id="phone-cliente">
        <input type="hidden" id="customer" name="customer">
    </div>

    <div class="form-group d-none" id="div-nome">

        @if($cart->random_numbers > 0)
            <div class="form-group"
                 style="background-color: #cff4fc; padding: 10px; border-radius: 10px; color: #055160;">
            <span>Você está adquirindo {{ $cart->getNumbersQty() }} cota(s) da ação entre amigos
                <strong>{{ $product['name'] }}</strong>.</span>
            </div>
        @endif
        <div class="form-group">
            <label style="color: #000"><strong>Nome Completo</strong></label>
            <input type="text" class="form-control" style="background-color: #fff; border: none; color: #333;"
                   name="name"
                   id="name">

        </div>

        @if(isset($config['email_required']) && $config['email_required'] == 1)
            <div class="form-group">
                <label style="color: #000"><strong>E-mail</strong></label>
                <input type="email" class="form-control" style="background-color: #fff; border: none; color: #333;"
                       name="email-cliente" id="email-cliente">
            </div>
        @endif

        @if(isset($config['cpf_required']) && $config['cpf_required'] == 1)
            <div class="form-group">
                <label style="color: #000"><strong>CPF</strong></label>
                <input type="text" class="form-control" style="background-color: #fff; border: none; color: #333;"
                       name="cpf-cliente" id="cpf-cliente">
            </div>
        @endif

        <small class="form-text d-block" style="color: green; margin-top: 10px;">
            <b>Valor a pagar: <small style="font-size: 15px;">{{ formatMoney($cart->total) }}</small></b>
        </small>
        <div class="separator-20"></div>
    </div>

    <div class="form-group" id="div-info">
        <span><i class="fas fa-info-circle"></i>&nbsp;<span id="info-footer">Informe seu telefone para continuar.</span></span>
    </div>


    <button class="btn btn-block btn-primary" id="btn-checkout-action" onclick="checkCustomer()" type="button"><strong
                id="btn-checkout">Continuar</strong></button>
    <center>
        <button class="btn btn-sm btn-outline-secondary mt-2 d-none" id="btn-alterar" onclick="clearModal()">Alterar
            Telefone
        </button>
    </center>
</form>
