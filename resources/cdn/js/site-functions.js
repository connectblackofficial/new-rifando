function isIOS() {
    var ua = navigator.userAgent.toLowerCase();

    //Lista de dispositivos que acessar
    var iosArray = ['iphone', 'ipod'];

    var isApple = false;

    if (ua.includes('iphone') || ua.includes('ipod')) {
        isApple = true
    }

    return isApple;
}

function verRifa(route) {
    window.location.href = route
}

function showConcluidos(el) {
    document.getElementById('btn-ativos').classList.remove('bg-info');
    document.getElementById('btn-ativos').classList.add('bg-secondary');
    el.classList.add('bg-info');

    document.querySelectorAll('.sorteio').forEach((el) => {
        el.classList.add('d-none')
    });

    document.querySelectorAll('.sorteio-finalizado').forEach((el) => {
        el.classList.remove('d-none')
    });
}

function showAtivos(el) {
    document.getElementById('btn-concluidos').classList.remove('bg-info');
    document.getElementById('btn-concluidos').classList.add('bg-secondary');
    el.classList.add('bg-info');

    document.querySelectorAll('.sorteio').forEach((el) => {
        el.classList.add('d-none')
    });

    document.querySelectorAll('.sorteio-ativo').forEach((el) => {
        el.classList.remove('d-none')
    });
}

function duvidas() {
    window.open('https://api.whatsapp.com/send?phone=' + user_phone, '_blank');
}

function infoParticipante(msg) {
    Swal.fire(msg)
}

function showHideReservas(element) {
    var selected = element.value;
    document.querySelectorAll('.row-rifa').forEach((el) => {
        el.classList.add('d-none')
    });

    if (selected == 0) {
        document.querySelectorAll('.row-rifa').forEach((el) => {
            el.classList.remove('d-none')
        });
    } else {
        document.querySelectorAll(`.rifa-${selected}`).forEach((el) => {
            el.classList.remove('d-none')
        });
    }
}

function infoPromo() {
    Swal.fire('Escolha os números abaixo, o desconto será aplicado automaticamente!');
}

function openModal() {
    $('#exampleModal').modal('show');
}

function openModal1() {
    $('#exampleModal1').modal('show');
}

function endLoading() {
    $("#loadingSystem").hide();
}

function loading() {
    var el = document.getElementById('loadingSystem');
    el.classList.toggle("d-none");
}

function prepareSitePhones() {
    for (var i = 1; i < 5; i++) {
        let dvId = "telephone" + i;
        if (idExists(dvId)) {
            document.getElementById(dvId).addEventListener('input', function (e) {
                var aux = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
                e.target.value = !aux[2] ? aux[1] : '(' + aux[1] + ') ' + aux[2] + (aux[3] ? '-' + aux[3] : '');
            });
        }

    }


}

function initIntlInput() {
    const inputs = document.querySelectorAll(".intl-input-phone");
    inputs.forEach(function (input) {
        window.intlTelInput(input, {
            initIntlInput: "br",
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
        });
    });
}

function loadUrlModal(title, url, size) {
    loading();
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
            errorMsg("Houve um erro ao carregar o conteúdo: " + errorMsg);
            $('#modal_url').modal('hide');
        }
        loading();
    });
    $('#modal_url').modal('show');
}

function openModalCheckout() {
    loadUrlModal("Finalizar Reserva", replaceId(ROUTES.site_checkout, cartUuid));
    return false;
}

function initSitePg() {
    $('[data-toggle="tooltip"]').tooltip();
    initAjaxSetup();
    prepareSitePhones();
    initIntlInput();
    //loadUrlModal('Finalizr', 'https://new-rifando.10mb.com.br/site/checkout/cc7d3512-6b90-4edc-99b0-1a27cf846059')
}

function openProductBuy() {
    $("#mobileMenu").modal("hide");
    $("#consult-order-modal").show();
}

function selectRaffles(id, key) {
    addRm([id]);
    return false;
}

function wdm() {
    var teste = JSON.parse(descontos)
    console.log(teste)
}

function changeSlide(el) {
    var id = el.dataset.id;
    document.querySelectorAll('.carousel-item').forEach((el) => el.classList.remove('active'));
    document.getElementById('slide-foto-' + id).classList.add('active');
}

function showNumbers(status) {
    document.querySelectorAll('.disponivel').forEach((el) => {
        el.style.display = 'none';
    });

    document.querySelectorAll('.reservado').forEach((el) => {
        el.style.display = 'none';
    });

    document.querySelectorAll('.pago').forEach((el) => {
        el.style.display = 'none';
    });

    document.querySelectorAll(`.${status}`).forEach((el) => {
        el.style.display = '';
    });
}

function showNumbersFazendinha(status) {
    document.querySelectorAll('.fazenda-disponivel').forEach((el) => {
        el.style.display = 'none';
    });

    document.querySelectorAll('.fazenda-reservado').forEach((el) => {
        el.style.display = 'none';
    });

    document.querySelectorAll('.fazenda-pago').forEach((el) => {
        el.style.display = 'none';
    });

    document.querySelectorAll(`.fazenda-${status}`).forEach((el) => {
        el.style.display = '';
    });
}

function selectFazendinha(id) {
    if (idExists(id)) {
        const x = document.getElementById(id);

        addRm([id]);
    }
    return false;

}

function validaMaxMin(operacao) {
    const input = document.getElementById('numbersA');
    var oldValue = input.value
    var max = parseInt(input.max)
    var min = parseInt(input.min)
    if (operacao === '+') {
        var newValue = parseInt(oldValue) + 1;
        if (newValue > max) return false;
    } else if (operacao === '-') {
        var newValue = parseInt(oldValue) - 1;
        if (newValue < min) return false;
    }

    return true;
}


function addQtd(e) {
    const input = document.getElementById('numbersA');
    var qty;
    if (e === '+') {
        qty = 1;
    } else if (e === '-') {
        qty = -1;
    } else {
        qty = parseInt(e);

    }
    input.value = parseInt(input.value) + qty
    addRm(qty);
    return false;
}

function calcDiscount(qtd) {
    const productPrice = productData.price;

    var lDescontos = JSON.parse(descontos)
    var percentDesconto = 0;

    lDescontos.forEach(function (i) {
        if (qtd >= parseInt(i.numeros)) {
            percentDesconto = i.desconto
        }
    })
    if (percentDesconto > 0) {
        total = clearMoneyVal(productPrice) * qtd - (clearMoneyVal(productPrice) * qtd * percentDesconto / 100);
        totalFomat = toLocaleMoneyString(total);
        totalPromo = toLocaleMoneyString(total);
        $('#promo').val(totalPromo)
    } else {
        $('#promo').val(0)
        total = clearMoneyVal(productPrice) * qtd;
        totalFomat = total.toLocaleString('pt-br', {
            style: 'currency',
            currency: 'BRL'
        });
    }
    return total;

}


function productSetNumbersModal(totalFomat) {
    if (idExists('numberSelectedTotalModal')) {
        document.getElementById('numberSelectedTotalModal').innerHTML = totalFomat;
    }
    if (idExists('numberSelectedTotalHome')) {
        if (qtd <= 1) {
            document.getElementById('numberSelectedTotalHome').innerHTML = totalFomat;
        } else {
            document.getElementById('numberSelectedTotalHome').innerHTML = totalFomat;
        }
    }

}

function hasProductOnPage() {
    if (typeof productData !== 'undefined' && productData !== null) {
        if (typeof productData.id !== 'undefined' && Number.isInteger(productData.id)) {
            return true;
        }
    }
    return false;
}


function drawePredictionCountDown() {
    if (!isValidDate(productData.draw_prediction)) {
        return false;
    }    //aqui vai sempre ser a hora atual
    var startDate = new Date();
    console.log("HORASSSSS", startDate);
    //como exemplo vou definir a data de fim com base na data atual
    var endDate = new Date(productData.draw_prediction);
    //endDate.setDate(endDate.getDate() + 60);

    //aqui é a diferenca entre as datas, basicamente é com isso que voce calcula o tempo restante
    var dateDiff;
    var days, hours, minutes, seconds;
    var $day = $('#dias');
    var $hour = $('#horas');
    var $minute = $('#minutos');
    var $second = $('#segundos');
    var $debug = $('#debug');
    var timer;

    function updateCountDown() {
        var diffMilissegundos = endDate - startDate;
        var diffSegundos = diffMilissegundos / 1000;
        var diffMinutos = diffSegundos / 60;
        var diffHoras = diffMinutos / 60;
        var diffDias = diffHoras / 24;
        var diffMeses = diffDias / 30;

        seconds = Math.floor((diffSegundos % 60));
        minutes = Math.floor((diffMinutos % 60));
        hours = Math.floor((diffHoras % 60));
        days = Math.floor(diffDias % 60);

        $day.text(days);
        $hour.text(hours);
        $minute.text(minutes);
        $second.text(seconds);

        if (days == 0 && hours == 0 && minutes == 0 && seconds == 0) {
            window.location.reload();
        }

        startDate.setSeconds(startDate.getSeconds() + 1);
    }

    updateCountDown();
    timer = setInterval(updateCountDown, 1000);
}

function startCountdown2() {

    var tempo = new Number();

    tempo = 900;

    // Se o tempo não for zerado
    if ((tempo - 1) >= 0) {

        // Pega a parte inteira dos minutos
        var min = parseInt(tempo / 60);
        // Calcula os segundos restantes
        var seg = tempo % 60;

        // Formata o número menor que dez, ex: 08, 07, ...
        if (min < 10) {
            min = "0" + min;
            min = min.substr(0, 2);
        }
        if (seg <= 9) {
            seg = "0" + seg;
        }

        // Cria a variável para formatar no estilo hora/cronômetro
        horaImprimivel = min + 'm' + ' ' + seg + 's';
        //JQuery pra setar o valor
        $("#promoMinutes").html(horaImprimivel);

        // Define que a função será executada novamente em 1000ms = 1 segundo
        setTimeout('startCountdown2()', 1000);

        // diminui o tempo
        tempo--;

        // Quando o contador chegar a zero faz esta ação
    }

}

function countDown2() {

    startCountdown2();
}

function productPageTouchStart() {
    $('.carousel').on('touchstart', function (event) {
        const xClick = event.originalEvent.touches[0].pageX;
        $(this).one('touchmove', function (event) {
            const xMove = event.originalEvent.touches[0].pageX;
            const sensitivityInPx = 5;

            if (Math.floor(xClick - xMove) > sensitivityInPx) {
                $(this).carousel('next');
            } else if (Math.floor(xClick - xMove) < -sensitivityInPx) {
                $(this).carousel('prev');
            }
        });
        $(this).on('touchend', function () {
            $(this).off('touchmove');
        });
    });
}

function lastParticipantsNotifications() {

    var updateRandomParticipan = function () {
        $('#messageIn').fadeIn('fast');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            dataType: 'json',
            url: ROUTES.randomParticipant,
            success: function (data) {
                document.getElementById('messageIn').innerHTML = data[0] + ' acabou de comprar';
            },
        });


        setTimeout(function () {
            $('#messageIn').fadeOut('fast');
        }, 2000); // <-- time in milliseconds
    }
    updateRandomParticipan();
    var refInterval = window.setInterval('updateRandomParticipan()', 1000 * 60 * 1); // 30 seconds


}


function getNumbers() {
    changeAjaxPage(1);


}

function validarQtd() {
    openModalCheckout();
}

function initProductFilters() {
    $(".filter-button").click(function () {

        $(".filter-button").removeClass('active');
        $(this).addClass('active');

        var value = $(this).attr('data-filter');

        //console.log(value);

        /*if (value == "all") {
            $(".filter").not('.filter[filter-item="' + value + '"]').css("display", "flex");
        } else {*/
        $(".filter").not('.filter[filter-item="' + value + '"]').css("display", "none");

        $(".filter").filter('.' + value).css("display", "inline-flex");
        //}
    });
}

function productDetailPageConfig() {
    var qtd = 1;
    if (idExists('qtdNumbers')) {
        document.getElementById('qtdNumbers').value = qtd;
    }

    const value = productData.price;

    total = value.toString().replace(",", ".") * qtd;
    totalFomat = total.toLocaleString('pt-br', {
        style: 'currency',
        currency: 'BRL'
    });


    fromatPrice = value.toLocaleString('pt-br', {
        style: 'currency',
        currency: 'BRL'
    });

    productSetNumbersModal(totalFomat);


}

function productDetailPage() {


    productDetailPageConfig();
    $(window).on('scroll', function () {
        if (idExists("paymentAutomatic")) {
            if ($(this).scrollTop() > 400) {
                if (avaliableNums > 0) {
                    $("#paymentAutomatic").fadeIn();
                }
            } else {
                $("#paymentAutomatic").fadeOut();
            }
        }

    });

    $("body").tooltip({
        selector: '[data-toggle=tooltip]'
    });
    setTimeout(getNumbers(), 2000);
    if (idExists('message-raffles')) {
        document.getElementById("message-raffles").innerHTML = "CARREGANDO AS COTAS...";
    }
    drawePredictionCountDown();
    countDown2();
    productPageTouchStart();
    lastParticipantsNotifications();
}

function startLoading() {
    return loading();
}

function checkAttribute(jsonObject, attribute) {
    // Function to recursively check if the attribute exists in any level of the object
    function checkInnerObject(obj) {
        if (typeof obj !== 'object' || obj === null) {
            return false;
        }

        if (obj.hasOwnProperty(attribute)) {
            return true;
        }

        for (let prop in obj) {
            if (obj.hasOwnProperty(prop) && checkInnerObject(obj[prop])) {
                return true;
            }
        }

        return false;
    }

    return checkInnerObject(jsonObject);
}


function hasCartId(obj) {
    if (obj && obj.data && typeof obj.data.id === 'number') {
        return true;
    }
    return false;
}


function hasTotalPages(obj) {
    if (obj && obj.data && typeof obj.data.total_pages === 'number') {
        return true;
    }
    return false;
}

function processCartResponse(data) {
    if (hasCartId(data)) {
        var cartResponseData = data.data;
        $("#payment").html(cartResponseData.view)
        $("#payment").show();
        $("#numbersA").val(cartResponseData.random_numbers);
        markNumbersOnCart(cartResponseData.numbers)
    }

}

function addRm(qtyOrList) {
    let url = ROUTES.cart_add_rm;
    let data = {
        'product_uuid': productData.uuid,
        'cart_uuid': cartUuid,
        'qty_or_list': qtyOrList
    }
    var callback = function (data) {
        processCartResponse(data);
        updateNumbersFazendinha(data)
    };
    return sendAjaxPostData(url, data, callback);
}

function updateNumbersFazendinha(data) {

    if (checkAttribute(data, 'numbers')) {
        let numbers = data.data.numbers;
        if (productData.game_mode == "fazendinha-meio" || productData.game_mode == "fazendinha-completa") {
            var elements = document.querySelectorAll('.selected-group');
            elements.forEach(function (element) {
                element.classList.remove('selected-group');
            });

            $.each(numbers, function (index) {
                $("#" + index).addClass("selected-group");
            })
        }
    }

}

function showCart() {
    let url = ROUTES.cart_resume;
    let data = {
        'product_uuid': productData.uuid,
        'cart_uuid': cartUuid,
    }
    var callback = function (data) {
        processCartResponse(data);
        updateNumbersFazendinha(data);
    };
    return sendAjaxPostData(url, data, callback);
}

function destroyCart() {
    deleteConfirm(function () {
        processDestroyCart();
    });
    return false;
}

function processDestroyCart() {

    let url = ROUTES.cart_destroy;
    let data = {
        'product_uuid': productData.uuid,
        'cart_uuid': cartUuid,
    }
    var callback = function (data) {
        location.reload();
    };
    return sendAjaxData(url, 'DELETE', data, callback);
}


function markNumbersOnCart(numbersOnCart) {

    const elementosOnCart = document.querySelectorAll('.on-cart');
    elementosOnCart.forEach(function (elemento) {
        elemento.classList.remove('on-cart');
    });

    for (const k in numbersOnCart) {
        if (numbersOnCart.hasOwnProperty(k)) {
            $(`#${k}`).addClass('on-cart')
        }
    }

}

function processNumberAjaxPageResponse(response) {
    if (hasTotalPages(response)) {
        var responseData = response.data;
        $("#ajax-raffles-content").html(responseData.html_page);
        markNumbersOnCart(responseData.numbers_on_cart);
    }
}

function changeAjaxPage(page) {
    if (productData.game_mode != "numeros") {
        return false;
    }

    let url = ROUTES.product_site_numbers;
    if (page == 1) {
        beforeCallback = function () {
            $("#message-raffles").show();
        }
        alwaysCallback = function () {
            $("#message-raffles").hide();
        }
    } else {
        beforeCallback = false;
        alwaysCallback = false;
    }
    let data = {
        'product_uuid': productData.uuid,
        'page': page,
        'cart_uuid': cartUuid
    }
    var callback = function (data) {
        processNumberAjaxPageResponse(data);
    };
    return sendAjaxPostData(url, data, callback, beforeCallback, alwaysCallback);
}

$('input.keydown').on('keydown', function (e) {
    var code = e.which || e.keyCode;

    if (code == 13) {
        event.preventDefault();
        checkCustomer()
    }
});


function checkCustomer() {

    var phone = $('#telephone1').val();

    var ddi = $('#DDI').val();

    var callback = function (response) {
        console.log(checkAttribute(response, 'data'));
        console.log(checkAttribute(response, 'customer'));

        if (checkAttribute(response, 'data') && checkAttribute(response.data, 'customer')) {
            if (response.data.customer == null) {
                novoCliente(phone);
            } else {
                findCustomer(response.data.customer)
            }
        }

    }
    return sendAjaxPostData(ROUTES.getCustomer, {'phone': phone, 'ddi': ddi}, callback)
}

function finalizarCompra() {
    $('#form-checkout').submit();
}

function findCustomer(customer) {
    document.getElementById('customer-name').innerHTML = customer.nome;
    document.getElementById('customer-phone').innerHTML = customer.telephone;
    document.getElementById('name').value = customer.nome;
    document.getElementById('phone-cliente').value = customer.telephone;
    if (idExists('cpf-cliente')) {
        document.getElementById('cpf-cliente').value = customer.cpf;
    }
    if (idExists('email-cliente')) {
        document.getElementById('email-cliente').value = customer.email;
    }

    document.getElementById('customer').value = customer.id;
    document.getElementById('div-customer').classList.toggle('d-none');
    document.getElementById('btn-checkout').innerHTML = 'Concluir reserva';
    document.getElementById('btn-checkout-action').type = 'submit'
    document.getElementById('btn-alterar').innerHTML = 'Alterar Conta';
    document.getElementById('btn-alterar').classList.remove('d-none');
    document.getElementById('div-info').classList.add('d-none');
    document.getElementById('div-telefone').classList.add('d-none');
}

function clearModal() {
    document.getElementById('telephone1').value = '';
    document.getElementById('telephone1').disabled = false;
    document.getElementById('DDI').disabled = false;

    document.getElementById('div-nome').classList.add('d-none');
    document.getElementById('info-footer').innerHTML = 'Informe seu telefone para continuar.';
    document.getElementById('btn-checkout').innerHTML = 'Continuar';
    document.getElementById('btn-checkout-action').setAttribute("onclick", "checkCustomer()")
    document.getElementById('btn-alterar').classList.add('d-none');
    document.getElementById('btn-checkout-action').type = 'button'
    document.getElementById('phone-cliente').value = ''
    document.getElementById('customer').value = 0;
    document.getElementById('div-customer').classList.add('d-none');
    document.getElementById('div-info').classList.remove('d-none');
    document.getElementById('div-telefone').classList.remove('d-none');
}

function novoCliente(phone) {
    document.getElementById('telephone1').disabled = true;
    document.getElementById('DDI').disabled = true;
    document.getElementById('div-nome').classList.toggle('d-none');
    document.getElementById('info-footer').innerHTML = 'Informe os dados corretos para recebimento das premiações.';
    document.getElementById('btn-checkout').innerHTML = 'Concluir cadastro e pagar';
    document.getElementById('btn-checkout-action').setAttribute("onclick", "loading()")
    document.getElementById('btn-checkout-action').type = 'submit'
    document.getElementById('btn-alterar').classList.innerHTML = 'Alterar Telefone';
    document.getElementById('btn-alterar').classList.toggle('d-none');
    document.getElementById('phone-cliente').value = phone
    document.getElementById('customer').value = 0;

}