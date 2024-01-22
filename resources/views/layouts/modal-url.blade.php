<div aria-hidden="true" id="modal_url" class="onboarding-modal modal fade animated" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-centered modal-lg" role="document">
        <div class="modal-content cc-branding text-center">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMsgTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="return closeUrlModal() ">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="onboarding-content with-gradient">
                    <div class="onboarding-text" id="modalLoadingMsg" >
                        <img src="<?=cdnImageAsset('loading.gif')?>" style="height: 200px" />
                        <p>Processando...</p>
                    </div>
                    <div class="onboarding-text" id="modalMsgBody">
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>