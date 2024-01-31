<div class="modal fade" id="<?=$modalId?>" data-backdrop="MODA" data-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index: 99999;">
    <div class="modal-dialog">
        <div class="modal-content" style="border: none;">
            <div class="modal-header" style="background-color: #939393;color: #fff;">
                <h5 class="modal-title"> <?= $title ?> </h5>
                <button type="button" class="btn btn-link text-white menu-mobile--button pe-0 font-lgg"
                        data-bs-dismiss="modal" aria-label="Fechar"><i class="bi bi-x-circle"></i></button>
            </div>
            <div class="modal-body" style="background: #efefef;color: #939393;" >
                <?= $content ?>
            </div>
        </div>
    </div>
</div>
