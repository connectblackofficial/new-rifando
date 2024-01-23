function deleteConfirm(callback) {
    Swal.fire({
        title: "Você tem certeza?",
        text: "Esta ação não poderá ser revertida.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirmar"
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
    return false;
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
                        alrtError("Ops!", response.error_message[i][0]);
                    } else {
                        alrtError("Ops!", response.error_message[i]);
                    }
                }
                break;
            }

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
}

function sendAjaxRequest(url, method, data) {
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

    // Verifica se há inputs de arquivo com arquivos
    $("input[type='file']").each(function () {
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
        },
        error: function () {
            alrtError("Ops!", "Ocorreu um erro desconhecido.");
            endLoading();
        }
    };
    console.log(ajaxData)
    $.ajax(ajaxData);

    return false;
}

function sendAjaxData(url, method, formData, callback,beforeCallback,alwaysCallback) {
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
            alrtError("Ops!", "Ocorreu um erro desconhecido.");

        }, always: function () {
            alert("Aooba")
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

function sendAjaxPostData(url, formData, callback,beforeCallback,alwaysCallback) {
    return sendAjaxData(url, 'POST', formData, callback,beforeCallback,alwaysCallback)
}

function sendAjaxGetData(url, formData, callback,beforeCallback,alwaysCallback) {
    return sendAjaxData(url, 'GET', formData, callback,beforeCallback,alwaysCallback)
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
    $("input").attr("style", "border-color:none;");
    $("select").attr("style", "border-color:none;");
    $(input).focus();
    element = $(input).attr("style", "border-color:red;");
}

function sendForm(form) {
    return sendAjaxRequest(form.attr('action'), form.attr('method'), form.serializeArray());
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
