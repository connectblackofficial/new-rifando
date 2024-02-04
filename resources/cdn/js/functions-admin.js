function link(url) {
    window.location.href = url;
}

function sendFormAfterConfirm(form) {
    deleteConfirm(function () {
        return sendForm(form);
    })
    return false;
}

function loading() {
    var el = document.getElementById('loadingSystem');
    el.classList.toggle("d-none");
}


function setUrlsPages() {
    var url_atual = window.location.pathname;

    if (url_atual == '/home') {
        var d = document.getElementById("home");
        d.className += " active";
    } else if (url_atual == '/adicionar-sorteio') {
        var d = document.getElementById("adicionar-sorteio");
        d.className += " active";
    } else if (url_atual == '/meus-sorteios') {
        var d = document.getElementById("meus-sorteios");
        d.className += " active";
    } else if (url_atual == '/perfil') {
        var d = document.getElementById("perfil");
        d.className += " active";
    }

}

function initSummerNotes() {
    document.querySelectorAll('.summernote').forEach((el) => {
        $('#' + el.id).summernote({
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear', 'fontname']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['misc', ['fullscreen']],
                ['link']
            ]
        })
    });


    $('#summernote').summernote({
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear', 'fontname']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['misc', ['fullscreen']],
            ['link']
        ]
    })
}

function toggleTelefone(id) {
    var telHide = document.getElementById(`tel-hide-${id}`)
    var telShow = document.getElementById(`tel-show-${id}`)
    var eyeShow = document.getElementById('eye-show')
    var eyeHide = document.getElementById('eye-hide')

    telHide.classList.toggle('d-none')
    telShow.classList.toggle('d-none')
    eyeShow.classList.toggle('d-none')
    eyeHide.classList.toggle('d-none')
}

function copiarLink() {
    var copyText = document.getElementById("link-afiliado");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");

    alert("Link copiado com sucesso.");

}

function getLinkAfiliado(el) {
    var url = el.dataset.url;
    var token = el.dataset.token;
    var link = `${url}/${token}`;

    $('#link-afiliado').val(link);
    $('#link-facebook').attr('href', `https://www.facebook.com/sharer/sharer.php?u=${link}`);
    $('#link-telegram').attr('href', `https://telegram.me/share/url?url=${link}`)
    $('#link-wpp').attr('href', `https://api.whatsapp.com/send?text=${link}`)
    $('#link-twitter').attr('href', `https://twitter.com/intent/tweet?text=Vc%20pode%20ser%20o%20Próximo%20Ganhador%20${link}`)
    $('#modal-link').modal('show');
}


function removeFocusRed(input) {
    element = $(input).removeAttr("style")
    $(input).removeClass("is-invalid");
}

function modal(title, content) {
    $("#modal_url").attr("data-title", title);
    $("#modalMsgTitle").html(title);
    $('#modal_url').removeData('.bs.modal');
    $("#modalMsgBody").html(content);
    $('#modal_url').modal('show');
    $('[data-toggle="tooltip"]').tooltip();
}


function addFoto(el) {
    $('#rifa-id').val(el.dataset.id)
    $('#input-add-foto').click()
}

function duplicar(el) {
    var id = el.dataset.id;
    var name = el.dataset.name
    $('#id-duplicar').val(id);
    $('#titulo-duplicar').text(`Copiando dados da rifa: ${name}`);

    $('#duplicar-modal').modal('show')
}

function formatarMoeda() {
    var elemento = document.getElementById('price');
    var valor = elemento.value;


    valor = valor + '';
    valor = parseInt(valor.replace(/[\D]+/g, ''));
    valor = valor + '';
    valor = valor.replace(/([0-9]{2})$/g, ",$1");

    if (valor.length > 6) {
        valor = valor.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
    }

    elemento.value = valor;
    if (valor == 'NaN') elemento.value = '';

}

function loadUrlBigModal(title, url) {
    $("#big_modal_url").attr("data-title", title);
    $("#big_modal_url").attr("data-url", url);
    $("#bigmodalMsgTitle").html(title);
    $('#big_modal_url').removeData('.bs.modal');
    $("#BigmodalMsgBody").load(url);
    $('#big_modal_url').modal('show');
}


function startLoading() {


    $("#modalLoadingMsg").show();
    $("#modalMsgBody").hide();
    $(".btn-block-text").show();
}

function endLoading() {


    $("#modalLoadingMsg").hide();
    $("#modalMsgBody").show();
}

function closeUrlModal() {
    $("#modal_url").modal('hide');
    return false;
}

function loadRouteModal(title, url, route, size) {
    var modalUrl = $("#modal_url");
    modalUrl.attr("data-url-route", route);

    return loadUrlModal(title, url, size);
}


function updateGatewayPix() {
    let currentGateway = $("#gateway").val();
    console.log(currentGateway)
    if (currentGateway == 'pix') {
        $(".pix-account-container").show();
    } else {
        $(".pix-account-container").hide();
    }

}

function productAjaxcallBack() {
    updateGatewayPix();
}

function successProductCreateCallback(data) {
    updateGatewayPix();
}

function successProductEditCallback(data) {
    productAjaxcallBack();
}

function loadUrlModal(title, url, size) {
    startLoading();
    var modalUrl = $("#modal_url");
    if (size) {
        modalUrl.find('.modal-dialog').addClass(size);
    }
    modalUrl.attr("data-title", title);
    modalUrl.attr("data-url", url);
    $("#modalMsgTitle").html(title);
    modalUrl.removeData('.bs.modal');

    $("#modalMsgBody").html('').load(url, function (response, status, xhr) {
        if (isStringNotEmpty(modalUrl.attr("data-url-route"))) {
            let routeName = modalUrl.attr("data-url-route");
            let callBackFnName = snakeToCamel("success_" + routeName + "_callback");
            console.log(callBackFnName)
            if (checkIfFunctionExists(callBackFnName)) {
                window[callBackFnName](response);  // Substitua 'window' pelo objeto que contém a função se ela não estiver no escopo global
            }

        }

        if (status == "error") {
            var errorMsg = "Erro: " + xhr.status + " " + xhr.statusText;
            alrtError("Ops", "Houve um erro ao carregar o conteúdo: " + errorMsg);
            $('#modal_url').modal('hide');
        }
        endLoading();

    });
    $('#modal_url').modal('show');
}


function changePopular(el) {
    var rifaID = el.dataset.rifa;
    document.getElementById(`popularCheck-${rifaID}`).value = el.dataset.id;
}

function productEdit(id) {
    let url = replaceId(ROUTES.product_edit, id);
    loadRouteModal("Editar rifa", url, "product_edit");
    return false;
}

function productCreate() {
    let url = ROUTES.product_create;
    loadRouteModal("Nova rifa", url, "product_create");
    return false;
}

function deleteProduct(id) {
    let url = replaceId(ROUTES.product_destroy, id);
    return deleteConfirm(function () {
        return sendAjaxRequest(url, 'delete', {'id': id});
    });
}


function openRanking(id) {
    //$('#content-modal-ranking').html('')
    $.ajax({
        url: ROUTES.ranking_admin,
        type: 'POST',
        dataType: 'json',
        data: {
            "id": id
        },
        success: function (response) {
            console.log(response);
            if (response.html) {
                $('#content-modal-ranking').html(response.html)
                $('#modal-ranking').modal('show')
            }
        },
        error: function (error) {

        }
    })
}

function definirGanhador(id) {
    $('#content-modal-definir-ganhador').html('')
    $.ajax({
        url: ROUTES.definirGanhador,
        type: 'POST',
        dataType: 'json',
        data: {
            "id": id
        },
        success: function (response) {
            if (response.html) {
                $('#content-modal-definir-ganhador').html(response.html)
                $('#modal-definir-ganhador').modal('show');
            }
        },
        error: function (error) {

        }
    })
}

function verGanhadores(id) {
    $('#content-modal-ver-ganhadores').html('')
    $.ajax({
        url: ROUTES.verGanhadores,
        type: 'POST',
        dataType: 'json',
        data: {
            "id": id
        },
        success: function (response) {
            if (response.html) {
                $('#content-modal-ver-ganhadores').html(response.html)
                $('#modal-ver-ganhadores').modal('show');
            }
        },
        error: function (error) {

        }
    })
}

function copyResumoLink(link) {
    const element = document.querySelector('#copy-link');
    const storage = document.createElement('textarea');
    storage.value = link;
    element.appendChild(storage);

    // Copy the text in the fake `textarea` and remove the `textarea`
    storage.select();
    storage.setSelectionRange(0, 99999);
    document.execCommand('copy');
    element.removeChild(storage);
    alrtSucess("Sucesso!", "LINK para resumo copiado com sucesso.");

}

function excluirFoto(el) {

    var id = el.dataset.id;
    let url = replaceId(ROUTES.product_destroy_photo, id);
    return deleteConfirm(function () {
        return sendAjaxRequest(url, 'delete', {'id': id});
    });
}

function initPage() {
    initAjaxSetup();
    setUrlsPages();

    if (idExists("input-add-foto")) {
        document.getElementById("input-add-foto").addEventListener("change", function (el) {
            $('#form-foto').submit();
        });
    }
    $('[data-toggle="tooltip"]').tooltip({
        trigger: 'click',
        container: 'body',
        html:true
    });


}

function initCreateOrUpdateProduct() {
    alert("hihi")
}