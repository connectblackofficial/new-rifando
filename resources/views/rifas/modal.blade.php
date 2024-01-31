<script>
    $('#staticBackdrop').on('hide.bs.modal', function () {
        clearModal()
    })


</script>


<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: none;">
            <div class="modal-header secondary-bg-color">
                <h5 class="modal-title" id="exampleModalLabel" style="color: #fff;">DÚVIDAS FREQUENTES</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: #fff;background-color: red!important;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body secondary-bg-color">
                <b style="text-transform: uppercase;">- É confiável?</b><br>
                <span style="color: #999999;">R: Sim, sorteio pela milhar da loteria federal.</span><br>
                <b style="text-transform: uppercase;">- Que dia é o sorteio?</b><br>
                <span style="color: #999999;">R: Após a venda de todas as cotas, no site você pode acompanhar as
                    vendas!</span><br>
                <b style="text-transform: uppercase;">- Como participar da nossa rifa?</b><br>
                <span style="color: #999999;">R: Existe duas formas compra automática e compra manual.</span><br>
                <b style="text-transform: uppercase;">- Forma de pagamento</b><br>
                <span style="color: #999999;">R: Somente PIX Copia e Cola ou CNPJ</span><br>
                <b style="text-transform: uppercase;">- Se eu escolher o veículo</b><br>
                <span style="color: #999999;">R: Vamos entregar na sua garagem o prêmio.</span>
            </div>
            <div class="modal-footer secondary-bg-color">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Premios --}}
@if(isset($productResume['prizeDraws']) && count($productResume['prizeDraws'])>0)
    <div class="modal fade" id="modal-premios" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
         style="z-index: 9999999;">
        <div class="modal-dialog">
            <div class="modal-content" style="border: none;">
                <div class="modal-header secondary-bg-color">
                    <h5 class="modal-title" id="exampleModalLabel" style="color: #fff;">PRÊMIOS</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                            style="color: #fff;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="">
                    <div class="col-md-12 text-center">
                        Estes são os prêmios disponíveis no sorteio <strong>{{ $productModel->name }}</strong>
                    </div>
                    <hr>
                    @foreach ($productResume['prizeDraws'] as $premio)
                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <label><strong>Prêmio {{ $premio->ordem }}: </strong>{{ $premio->descricao }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
{{-- Modal Ranking Compradores --}}
<div class="modal fade" id="modal-ranking" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
     style="z-index: 9999999;">
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
                <div class="col-md-12 text-center" style="font-weight: 400">
                    Esses são os maiores compradores no sorteio <strong
                            style="font-weight: 600">{{ $productModel->name }}</strong>
                </div>
                @foreach ($ranking as $key => $rk)
                    <div class="row mt-3" style="font-weight: 400">
                        <div class="col-1 text-center">
                            @if ($key == 0)
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#000"
                                     viewBox="0 0 16 16" style="width: 30px; height: auto; fill: rgb(255, 193, 7);">
                                    <path
                                            d="M2.5.5A.5.5 0 0 1 3 0h10a.5.5 0 0 1 .5.5c0 .538-.012 1.05-.034 1.536a3 3 0 1 1-1.133 5.89c-.79 1.865-1.878 2.777-2.833 3.011v2.173l1.425.356c.194.048.377.135.537.255L13.3 15.1a.5.5 0 0 1-.3.9H3a.5.5 0 0 1-.3-.9l1.838-1.379c.16-.12.343-.207.537-.255L6.5 13.11v-2.173c-.955-.234-2.043-1.146-2.833-3.012a3 3 0 1 1-1.132-5.89A33.076 33.076 0 0 1 2.5.5zm.099 2.54a2 2 0 0 0 .72 3.935c-.333-1.05-.588-2.346-.72-3.935zm10.083 3.935a2 2 0 0 0 .72-3.935c-.133 1.59-.388 2.885-.72 3.935zM3.504 1c.007.517.026 1.006.056 1.469.13 2.028.457 3.546.87 4.667C5.294 9.48 6.484 10 7 10a.5.5 0 0 1 .5.5v2.61a1 1 0 0 1-.757.97l-1.426.356a.5.5 0 0 0-.179.085L4.5 15h7l-.638-.479a.501.501 0 0 0-.18-.085l-1.425-.356a1 1 0 0 1-.757-.97V10.5A.5.5 0 0 1 9 10c.516 0 1.706-.52 2.57-2.864.413-1.12.74-2.64.87-4.667.03-.463.049-.952.056-1.469H3.504z">
                                    </path>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#000"
                                     viewBox="0 0 16 16" style="width: 22px; height: auto; margin-left: 5px;">
                                    <path
                                            d="M2.5.5A.5.5 0 0 1 3 0h10a.5.5 0 0 1 .5.5c0 .538-.012 1.05-.034 1.536a3 3 0 1 1-1.133 5.89c-.79 1.865-1.878 2.777-2.833 3.011v2.173l1.425.356c.194.048.377.135.537.255L13.3 15.1a.5.5 0 0 1-.3.9H3a.5.5 0 0 1-.3-.9l1.838-1.379c.16-.12.343-.207.537-.255L6.5 13.11v-2.173c-.955-.234-2.043-1.146-2.833-3.012a3 3 0 1 1-1.132-5.89A33.076 33.076 0 0 1 2.5.5zm.099 2.54a2 2 0 0 0 .72 3.935c-.333-1.05-.588-2.346-.72-3.935zm10.083 3.935a2 2 0 0 0 .72-3.935c-.133 1.59-.388 2.885-.72 3.935zM3.504 1c.007.517.026 1.006.056 1.469.13 2.028.457 3.546.87 4.667C5.294 9.48 6.484 10 7 10a.5.5 0 0 1 .5.5v2.61a1 1 0 0 1-.757.97l-1.426.356a.5.5 0 0 0-.179.085L4.5 15h7l-.638-.479a.501.501 0 0 0-.18-.085l-1.425-.356a1 1 0 0 1-.757-.97V10.5A.5.5 0 0 1 9 10c.516 0 1.706-.52 2.57-2.864.413-1.12.74-2.64.87-4.667.03-.463.049-.952.056-1.469H3.504z">
                                    </path>
                                </svg>
                            @endif

                        </div>
                        <div class="col-6">
                            {{ $rk->name }}
                        </div>
                        <div class="col-5 text-end">
                            {{ $rk->totalReservas }} Números
                        </div>
                    </div>

                    {{-- <div class="btn-auto item-ranking">
                        {{ $key + 1 }}º {{ $productModel->medalhaRanking($key) }}<br>
                        <span style="font-size: 20px;font-weight: bold;">{{ $rk->name }}</span>
                        <br>
                        <span style="font-size: 12px;">Qtd. de Bilhetes
                            {{ $rk->totalReservas }}</span>
                    </div> --}}
                @endforeach
            </div>
        </div>
    </div>
</div>


<div class="blob green" id="messageIn"
     style="position: fixed;
bottom: 15px;
z-index: 99999;
color: #fff;
padding: 3px;
font-weight: bold;
font-size: 12px;
width: 180px;
text-align: center;
z-index: 99999;border-radius: 20px;left: 10px;">
</div>
