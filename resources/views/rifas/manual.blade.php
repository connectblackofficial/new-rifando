<div class="container" id="rafflesSection" style="margin-top: 10px;text-align: center">
    <h6 style=" color: #000;font-weight: bold; font-size: 12px;"><i class="bi bi-award"></i> Escolha você mesmo
        clicando no(s)
        número(s) desejado(s)!!!</h6>
</div>

<input type="number" style="text-align: center;background-color: #E5E5E5;color: #000000;font-weight: bold; display:none"
       id="numbersA" value="{{ $productModel->minimo }}" min="{{ $productModel->minimo }}"
       max="{{ $productModel->maximo }}"
       onblur="numerosAleatorio();" onkeyup="numerosAleatorio()" class="form-control" placeholder="Quantidade de cotas">

<div class="d-flex justify-content-between font-weight-600 mb-2">
    <div class="seletor-item rounded d-flex justify-content-between box-shadow-08 font-xs" style="cursor: pointer;"
         onclick="showNumbers('disponivel')">
        <div class="nome bg-white rounded-start text-dark p-2">
            Livres
        </div>
        <div class="num text-white p-2 rounded-end product-number-free">
            {{ $productModel->qtdNumerosDisponiveis() }}
        </div>
    </div>

    <div class="seletor-item rounded d-flex justify-content-between box-shadow-08 font-xs product-number-reserved"
         style="cursor: pointer;"
         onclick="showNumbers('reservado')">
        <div class="nome bg-white rounded-start text-dark p-2">
            Reserv
        </div>
        <div class="num text-white p-2 rounded-end product-number-reserved">
            {{ $productModel->qtdNumerosReservados() }}
        </div>
    </div>

    <div class="seletor-item rounded d-flex justify-content-between box-shadow-08 font-xs product-number-paid"
         style="cursor: pointer;"
         onclick="showNumbers('pago')">
        <div class="nome bg-white rounded-start text-dark p-2">
            Pagos
        </div>
        <div class="num text-white p-2 rounded-end product-paid-reserved">
            {{ $productModel->qtdNumerosPagos() }}
        </div>
    </div>
</div>


<div class="container text-center">
    <div class="raffles {{ $product->status == 'Finalizado' ? 'finished' : '' }} qty-numbers-zeros-{{$product->qtd_zeros}}"
         id="raffles"
         style="margin-bottom: 150px !important;">
        <div id="message-raffles" class="blob"
             style="background-color: transparent;color: #000;font-weight: bold;text-align: center; display: none">
            CARREGANDO AS COTAS...
        </div>
        <div id="ajax-raffles-content">
        </div>

    </div>
</div>



