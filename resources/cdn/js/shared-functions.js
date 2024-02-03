function deleteConfirm(callback) {
    Swal.fire({
        title: "Você tem certeza?",
        text: "Esta ação não poderá ser revertida.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
    return false;
}


function rand(min, max) {
    // Se apenas um argumento for fornecido, gera um número entre 0 e esse valor.
    if (arguments.length === 1) {
        max = min;
        min = 0;
    }

    // Garante que os argumentos são números inteiros.
    min = Math.floor(min);
    max = Math.floor(max);

    // Gera um número aleatório entre min (inclusive) e max (exclusive).
    return Math.floor(Math.random() * (max - min) + min);
}

function getValueById(id) {
    // Verifica se o elemento com o ID fornecido existe
    const elemento = document.getElementById(id);

    // Se o elemento existir, retorna o valor, caso contrário, retorna null
    return elemento ? elemento.value : null;
}


function errorMsg(msg) {
    alrtError("Ops!", msg);
}

function successMsg(msg) {
    alrtSucess("Sucesso!", msg);
}

function alertUnkonwnError() {
    errorMsg("Ocorreu um erro desconhecido.")
}

function processAjaxError(response) {
    if (response.error) {
        if (typeof response.error_message != "undefined") {
            for (var i in response.error_message) {
                focusRed("#" + i);
                if (typeof response.confirm_redirect != 'undefined') {
                    alrtConfirm("Ops!", response.error_message[i], response.confirm_redirect);
                } else {
                    if (Array.isArray(response.error_message[i])) {
                        errorMsg(response.error_message[i][0]);
                    } else {
                        errorMsg(response.error_message[i]);
                    }
                }
                break;
            }

        }
        return true;
    }
    return false;
}

function processAjaxRedirect(response) {
    if (response.redirect) {
        if (typeof response.redirect_url != "undefined") {
            setTimeout(function () {
                location.href = response.redirect_url;
            }, 1000);
        }
    }
}

function processAjaxSuccess(response) {
    console.log(response);
    if (response.callback) {
        if (typeof response.callback != "undefined") {
            console.log(response.callback);
            eval(response.callback_function);
        }

    }
    processAjaxError(response);
    if (response.success) {
        if (typeof response.success_message != "undefined") {
            successMsg(response.success_message);
        }
    }
    processAjaxRedirect(response);
    endLoading();
}

function isStringNotEmpty(str) {
    return typeof str === 'string' && str.trim() !== '';
}

function checkIfFunctionExists(functionName) {
    return typeof window[functionName] === 'function';
}

function snakeToCamel(str) {
    return str.replace(/_([a-z])/g, function (match, letter) {
        return letter.toUpperCase();
    });
}

function startBtnLoad(formJq) {
    if (formJq) {
        var submitBtns = formJq.find('button[type="submit"]');
        submitBtns.each(function () {
            $(this).prop('disabled', true);
            $(".btn-block-loading").show();
            $(".btn-block-text").hide();
        })
    }
}

function endBtnLoad(formJq) {
    console.log(formJq)
    if (formJq) {
        var submitBtns = formJq.find('button[type="submit"]');
        console.log(submitBtns)

        submitBtns.each(function () {
            $(this).removeAttr('disabled');
            $(".btn-block-loading").hide();
            $(".btn-block-text").show();
            console.log("hihih")

        })
    }
}

function sendAjaxRequest(url, method, data, formJq = false) {
    startBtnLoad(formJq);
    var formData = data instanceof FormData ? data : new FormData();
    var hasFiles = false;
    // Se data não for FormData, adiciona seus campos ao formData
    if (!(data instanceof FormData)) {
        for (var key in data) {
            let obName = data[key].name;
            let obValue = data[key].value;
            formData.append(obName, obValue);
        }
    }
    if (formJq) {
        var allFileInputs = formJq.find("input[type='file']");
    } else {
        var allFileInputs = $("input[type='file']");

    }
    // Verifica se há inputs de arquivo com arquivos
    allFileInputs.each(function () {
        if (this.files.length > 0) {
            hasFiles = true;
            var inputName = $(this).attr('name'); // Obtém o nome do input
            $.each(this.files, function (i, file) {
                // Utiliza o nome do input, preservando a estrutura para arrays (ex: 'images[]')
                formData.append(inputName, file);
            });
        }
    });
    let ajaxData = {
        url: url,
        method: method,
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            processAjaxSuccess(response);
            endBtnLoad(formJq);
        },
        error: function () {
            alertUnkonwnError();
            endLoading();
            endBtnLoad(formJq);
        }
    };
    console.log(ajaxData)
    $.ajax(ajaxData);

    return false;
}

function sendAjaxData(url, method, formData, callback, beforeCallback, alwaysCallback) {
    if (beforeCallback && typeof beforeCallback === "function") {
        beforeCallback();
    } else {
        startLoading();
    }
    let ajaxData = {
        url: url,
        method: method,
        data: formData,
        dataType: "json",
        cache: false,
        success: function (response) {

            callback(response)

            processAjaxError(response);

        },
        error: function () {
            alertUnkonwnError();

        }
    };
    $.ajax(ajaxData).always(function () {
        if (alwaysCallback && typeof alwaysCallback === "function") {
            alwaysCallback();
        } else {
            startLoading();
        }
    })
    return false;
}

function sendAjaxPostData(url, formData, callback, beforeCallback, alwaysCallback) {
    return sendAjaxData(url, 'POST', formData, callback, beforeCallback, alwaysCallback)
}

function sendAjaxGetData(url, formData, callback, beforeCallback, alwaysCallback) {
    return sendAjaxData(url, 'GET', formData, callback, beforeCallback, alwaysCallback)
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

function replaceId(txt, id) {
    return txt.replace("replace_id_here", id);
}

function alrtSucess(title, content) {
    Swal.fire({
        title: title,
        text: content,
        icon: "success" // type can be error/warning/success
    });
}

function initAjaxSetup() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
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

function ltrimAsset(asset) {
    return asset.replace(/^\//, '');
}

function focusRed(input) {
    $("input").focusout();
    $("select").focusout();
    $(input).focus();
    $(input).addClass("is-invalid");
}

function sendForm(form) {
    $('.is-invalid').removeClass('is-invalid');
    return sendAjaxRequest(form.attr('action'), form.attr('method'), form.serializeArray(), form);
}

function maskMoney(element) {
    $(element).maskMoney({thousands: '', decimal: '.', allowZero: true, affixesStay: false, prefix: "R$"});
}

function cdnAsset(asset) {

    return CDN_URL + "/" + asset.replace(/^\//, '');
}

function forceAsString(val) {
    if (typeof val !== 'string') {
        val = String(val);
    }
    return val;

}

function idExists(id) {
    var elemento = document.getElementById(id);
    return elemento !== null;
}

function clearMoneyVal(val) {
    return parseFloat(forceAsString(val).replace(",", "."));
}


function toLocaleMoneyString(val) {

    return forceAsString(val).toLocaleString('pt-br', {
        style: 'currency',
        currency: 'BRL',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

function isset(...args) {
    for (let i = 0; i < args.length; i++) {
        if (args[i] === undefined || args[i] === null) {
            return false;
        }
    }
    return true;
}

function isValidDate(dateString) {
    if (!dateString) return false;

    const timestamp = Date.parse(dateString);

    // Verifica se o timestamp é um número e se a data é futura
    return !isNaN(timestamp) && new Date(timestamp) <= new Date();
}

function completeCheckout() {

}

function copyPix() {
    var copyText = document.getElementById("brcodepix");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
    document.getElementById("clip_btn").innerHTML = 'COPIADO';
    successMsg("Chave PIX COPIA E COLA copiado com sucesso.");
}