<div class="modal fade" id="modal_url" data-backdrop="MODA" data-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="background-color: #939393;color: #fff;">
                <h5 class="modal-title" id="modalMsgTitle"></h5>
                <button type="button" class="btn btn-link text-white menu-mobile--button pe-0 font-lgg"
                        data-bs-dismiss="modal" aria-label="Fechar"><i class="bi bi-x-circle"></i></button>
            </div>
            <div class="modal-body" style="background: #efefef;color: #939393;" id="modalMsgBody">
                <div class="onboarding-text" id="modalLoadingMsg" >
                    <img src="<?=cdnImageAsset('loading.gif')?>" style="height: 200px" />
                    <p>Processando...</p>
                </div>
            </div>
        </div>
    </div>
</div>
