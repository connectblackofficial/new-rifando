function link(url) {
    window.location.href = url;
}

function loading() {
    var el = document.getElementById('loadingSystem');
    el.classList.toggle("d-none");
}

function initAjaxSetup() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
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

function alrt(title, content) {

    swal(title, content);
}

function alrtError(title, content) {
    Swal.fire({
        title: title,
        text: content,
        icon: "error" // type can be error/warning/success
    });

}

function alrtSucess(title, content) {
    Swal.fire({
        title: title,
        text: content,
        icon: "success" // type can be error/warning/success
    });
}

function alrtConfirm(title, content, confirmRedirect) {
    swal({
        title: title,
        text: content,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: share_trans.YesIamsure,
        cancelButtonText: share_trans.Nocancelit,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
    }, function (isConfirm) {
        if (isConfirm) {
            location.href = confirmRedirect;
        }
    })
}

function focusRed(input) {
    $("input").focusout();
    $("select").focusout();
    $("input").attr("style", "border-color:none;");
    $("select").attr("style", "border-color:none;");
    $(input).focus();
    element = $(input).attr("style", "border-color:red;");
}

function removeFocusRed(input) {
    element = $(input).removeAttr("style")
}

function modal(title, content) {
    $("#modal_url").attr("data-title", title);
    $("#modalMsgTitle").html(title);
    $('#modal_url').removeData('.bs.modal');
    $("#modalMsgBody").html(content);
    $('#modal_url').modal('show');
    $('[data-toggle="tooltip"]').tooltip();
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

function fBlockUi() {
    $.blockUI({
        baseZ: 200000,
        // message: "<h4><img width='50' src='" + URL + '/assets/images/core/icons/loading.gif' + "'></h4>",
        message: "",
        css: {
            border: 'none',
            padding: '5px',
            backgroundColor: 'transparent',
            '-webkit-border-radius': '5px',
            '-moz-border-radius': '5px',
            opacity: 0,
            color: '#fff',
        }
    });
}

function ltrimAsset(asset) {
    return asset.replace(/^\//, '');
}

function cdnAsset(asset) {

    return CDN_URL + "/" + asset.replace(/^\//, '');
}

function fBlockUiLoading() {
    let loadingImg = cdnAsset("images/loading.gif");
    $.blockUI({
        baseZ: 200000,
        message: '<div class="psoload">' +
            '  <div class="straight"></div>' +
            '  <div class="curve"></div>' +
            '  <div class="center"><img style="width: 70px;" src="' + loadingImg + '"></div>' +
            '  <div class="inner"></div>' +
            '</div>',
        css: {
            border: 'none',
            padding: '5px',
            backgroundColor: 'transparent',
            '-webkit-border-radius': '5px',
            '-moz-border-radius': '5px',
            opacity: 1,
            color: '#fff',
        },
        overlayCSS: {
            backgroundColor: '#323c58',
            opacity: 1,
            cursor: 'wait'
        },
    });
}

function startLoading() {
    $("#modalLoadingMsg").show();
    $("#modalMsgBody").hide();
}

function endLoading() {
    $("#modalLoadingMsg").hide();
    $("#modalMsgBody").show();
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
        if (status == "error") {
            var errorMsg = "Erro: " + xhr.status + " " + xhr.statusText;
            alrtError("Ops", "Houve um erro ao carregar o conteúdo: " + errorMsg);
            $('#modal_url').modal('hide');
        }
        endLoading();

    });
    $('#modal_url').modal('show');
}

function sendForm(form) {
    startLoading();
    $.ajax({
        url: form.attr('action'),
        method: form.attr('method'),
        data: form.serialize(),
        dataType: "json",
        cache: false,
        success: function (response) {
            console.log(response);
            if (response.callback) {
                if (typeof response.callback != "undefined") {
                    console.log(response.callback);
                    eval(response.callback_function);
                }

            }
            if (response.error) {
                if (typeof response.error_message != "undefined") {
                    for (var i in response.error_message) {
                        focusRed("#" + i);
                        if (typeof response.confirm_redirect != 'undefined') {
                            alrtConfirm("Ops!", response.error_message[i], response.confirm_redirect);
                        } else {
                            if (Array.isArray(response.error_message[i])) {
                                alrtError("Ops!", response.error_message[i][0]);
                            } else {
                                alrtError("Ops!", response.error_message[i]);
                            }
                        }
                        break;
                    }

                }
            }
            if (response.success) {
                if (typeof response.sucess_message != "undefined") {
                    alrtSucess("Sucesso!", response.sucess_message);
                }
            }
            if (response.redirect) {
                if (typeof response.redirect_url != "undefined") {
                    setTimeout(function () {
                        location.href = response.redirect_url;
                    }, 1000);
                }

            }
            endLoading();
        },
        error: function (response) {
            alrtError("Ops!", "Ocorreu um erro desconhecido;.");
            endLoading();
        }
    });
    return false;
}

function changePopular(el) {
    var rifaID = el.dataset.rifa;
    document.getElementById(`popularCheck-${rifaID}`).value = el.dataset.id;
}

function productEdit(id) {
    let url = PRODUCT_EDIT_ROUTE.replace("replace_here", id)
    loadUrlModal("Editar rifa", url);
    return false;
}

function deleteProduct() {

}