<div class="form-group">
    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle"></i>&nbsp;
        <span>Você está adquirindo {{ $cart->getNumbersQty() }} cota(s) da ação entre amigos
                <strong>{{ $product['name'] }}</strong>.</span>
    </div>
</div>
<div class="form-group">
    <label ><strong>Nome Completo</strong></label>
    <input type="text" class="form-control" 
           name="name"
           id="name">
</div>
@if(isset($config['email_required']) && $config['email_required'] == 1)
    <div class="form-group">
        <label ><strong>E-mail</strong></label>
        <input type="email" class="form-control" 
               name="email-cliente" id="email-cliente">
    </div>
@endif

@if(isset($config['cpf_required']) && $config['cpf_required'] == 1)
    <div class="form-group">
        <label ><strong>CPF</strong></label>
        <input type="text" class="form-control" 
               name="cpf-cliente" id="cpf-cliente">
    </div>
@endif
<small class="form-text d-block" style="color: green; margin-top: 10px;">
    <b>Valor a pagar: <small style="font-size: 15px;">{{ formatMoney($cart->total) }}</small></b>
</small>
<?= submitBtn('Concluir cadastro e pagar <i class="bi bi-arrow-right-circle"></i>', 'btn-checkout-complete') ?>
