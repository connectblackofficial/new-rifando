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
<?php if (isset($productResume['prizeDraws']) && count($productResume['prizeDraws']) > 0) {
    echo prizeDrawModal($productFromCache['name'], $productResume['prizeDraws']);
}
if (isset($productFromCache['name']) && is_array($ranking) && count($ranking) > 0) {
    echo productRankingModal($productFromCache['name'], $ranking);
}
?>


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
