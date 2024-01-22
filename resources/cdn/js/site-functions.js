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

function openModalCheckout() {
    var raffleType = $('#raffleType').val();
    var modoJogo = $('#modoDeJogo').val();
    if (raffleType == 'manual' || modoJogo == 'fazendinha-completa' || modoJogo == 'fazendinha-meio') {
        var qtdCompra = $('#qtdManual').val()
    } else {
        var qtdCompra = $('#qtdNumbers').val()
    }
    var nomeRifa = $('#product-name').val()
    $('#qtd-checkout').text(qtdCompra)
    $('#rifa-checkout').text(nomeRifa)
    $('#staticBackdrop').modal('show')
}

function initSitePg() {
    $('[data-toggle="tooltip"]').tooltip();
    initAjaxSetup();
    prepareSitePhones();
}

function calPrices() {

}

function selectRaffles(id, key) {
    const x = document.getElementById(id);

    if (x.classList[3] == "selected") {

        x.classList.remove("selected");

        numbersManual.splice(numbersManual.indexOf(x.id + '-' + key), 1);

        // document.getElementById('numberSelected').innerHTML = numbersManual;
        $('#selected-' + x.id).remove()
        document.getElementById('numberSelectedModal').innerHTML = numbersManual;
        document.getElementById('numberSelectedInput').value = numbersManual;
        document.getElementById('qtdManual').value = numbersManual.length;
        $('#promo').val(0)
        var lDescontos = JSON.parse(descontos)
        var percentDesconto = 0;

        lDescontos.forEach(function (i) {
            if (numbersManual.length >= parseInt(i.numeros)) {
                percentDesconto = i.desconto
            }
        })
        if (percentDesconto > 0) {
            total = valuePrices.toString().replace(",", ".") * numbersManual.length - (valuePrices.toString()
                .replace(",", ".") * numbersManual.length * percentDesconto / 100);
            totalFomat = total.toLocaleString('pt-br', {
                style: 'currency',
                currency: 'BRL'
            });

            totalPromo = total.toLocaleString('pt-br', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });

            // alert(totalPromo);
            $('#promo').val(totalPromo)
        } else {
            $('#promo').val(0)
            total = valuePrices.toString().replace(",", ".") * numbersManual.length;
            totalFomat = total.toLocaleString('pt-br', {
                style: 'currency',
                currency: 'BRL'
            });
        }

        // total = valuePrices.toString().replace(",", ".") * numbersManual.length;
        // totalFomat = total.toLocaleString('pt-br', {
        //     style: 'currency',
        //     currency: 'BRL'
        // });

        productSetNumbersModal(totalFomat);

        if (numbersManual.length == 0) {
            if (productData.type_raffles == 'manual') {
                document.getElementById("payment").style.display = "none";
                document.getElementById("paymentAutomatic").style.display = "block";

            } else if (productData.type_raffles == 'mesclado') {
                document.getElementById("payment").style.display = "none";
                document.getElementById("paymentAutomatic").style.display = "block";

            }

            const value = productData.price;


            total = value.toString().replace(",", ".") * 1;
            totalFomat = total.toLocaleString('pt-br', {
                style: 'currency',
                currency: 'BRL'
            });

            document.getElementById('qtdNumbers').value = 1;
            document.getElementById('numbersA').value = 1;


            productSetNumbersModal(totalFomat);

        }
    } else {
        x.classList.add("selected");

        numbersManual.push(x.id + '-' + key);

        var teste = document.createElement('div');
        var texto = document.createTextNode(x.id);
        teste.classList = 'number-selected';
        teste.id = 'selected-' + x.id
        teste.appendChild(texto)
        document.getElementById('numberSelected').appendChild(teste)

        document.getElementById('numberSelectedModal').innerHTML = numbersManual;
        document.getElementById('numberSelectedInput').value = numbersManual;
        document.getElementById('qtdManual').value = numbersManual.length;

        const productID = productData.id;

        $('#promo').val(0)
        var lDescontos = JSON.parse(descontos)
        var percentDesconto = 0;

        lDescontos.forEach(function (i) {
            if (numbersManual.length >= parseInt(i.numeros)) {
                percentDesconto = i.desconto
            }
        })

        if (percentDesconto > 0) {
            total = valuePrices.toString().replace(",", ".") * numbersManual.length - (valuePrices.toString()
                .replace(",", ".") * numbersManual.length * percentDesconto / 100);
            totalFomat = total.toLocaleString('pt-br', {
                style: 'currency',
                currency: 'BRL'
            });

            totalPromo = total.toLocaleString('pt-br', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });

            // alert(totalPromo);
            $('#promo').val(totalPromo)
        } else {
            $('#promo').val(0)
            total = valuePrices.toString().replace(",", ".") * numbersManual.length;
            totalFomat = total.toLocaleString('pt-br', {
                style: 'currency',
                currency: 'BRL'
            });
        }

        //*********************************************//
        // total = valuePrices.toString().replace(",", ".") * numbersManual.length;
        // totalFomat = total.toLocaleString('pt-br', {
        //     style: 'currency',
        //     currency: 'BRL'
        // });

        productSetNumbersModal(totalFomat);

        if (idExists('payment')) {
            document.getElementById("payment").style.display = "";

        }

    }
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
    const x = document.getElementById(id);

    console.log(x);

    if (x.classList.contains('selected-group')) {

        x.classList.remove("selected-group");

        numbersManual.splice(numbersManual.indexOf(x.id), 1);

        // document.getElementById('numberSelected').innerHTML = numbersManual;
        $('#selected-' + x.id).remove()
        // document.getElementById('numberSelectedModal').innerHTML = numbersManual;
        document.getElementById('numberSelectedInput').value = numbersManual;
        document.getElementById('qtdManual').value = numbersManual.length;

        total = valuePrices.toString().replace(",", ".") * numbersManual.length;
        totalFomat = total.toLocaleString('pt-br', {
            style: 'currency',
            currency: 'BRL'
        });

        productSetNumbersModal(totalFomat)


        if (numbersManual.length == 0) {
            if (productData.type_raffles == 'manual') {
                document.getElementById("payment").style.display = "none";
                document.getElementById("paymentAutomatic").style.display = "block";

            } else if (productData.type_raffles == 'mesclado') {
                document.getElementById("payment").style.display = "none";
                document.getElementById("paymentAutomatic").style.display = "block";
            }

            const value = productData.price;

            total = value.toString().replace(",", ".") * 1;
            totalFomat = total.toLocaleString('pt-br', {
                style: 'currency',
                currency: 'BRL'
            });

            document.getElementById('qtdNumbers').value = 1;
            document.getElementById('numbersA').value = 1;


            productSetNumbersModal(totalFomat);

        }
    } else {
        if (productData.type_raffles == 'mesclado') {
            document.getElementById('qtdNumbers').value = null;
            document.getElementById("paymentAutomatic").style.display = "none";

        }

        x.classList.add("selected-group");

        numbersManual.push(x.id);

        // document.getElementById('numberSelected').innerHTML = numbersManual;

        var teste = document.createElement('div');
        var texto = document.createTextNode(x.dataset.grupo);
        teste.classList = 'number-selected fazendinha';
        teste.id = 'selected-' + x.id
        teste.appendChild(texto)
        document.getElementById('numberSelected').appendChild(teste)

        //document.getElementById('numberSelectedModal').innerHTML = numbersManual;
        document.getElementById('numberSelectedInput').value = numbersManual;

        const productID = productData.id;

        if (numbersManual.length == 10 && productID == 12) {
            total = 120;
            totalFomat = total.toLocaleString('pt-br', {
                style: 'currency',
                currency: 'BRL'
            });
        } else {
            total = valuePrices.toString().replace(",", ".") * numbersManual.length;
            totalFomat = total.toLocaleString('pt-br', {
                style: 'currency',
                currency: 'BRL'
            });
        }
        productSetNumbersModal(totalFomat);
        document.getElementById('qtdManual').value = numbersManual.length;
        document.getElementById("payment").style.display = "";

    }
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
        numerosAleatorio();
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

function numerosAleatorio() {

    qtd = parseInt(document.getElementById('numbersA').value);
    document.getElementById('qtdNumbers').value = qtd;
    const value = productData.price;
    total = clearMoneyVal(value) * qtd;
    totalFomat = toLocaleMoneyString(total);

    const productID = productData.id;

    $('#promo').val(0)
    var lDescontos = JSON.parse(descontos)
    var percentDesconto = 0;

    lDescontos.forEach(function (i) {
        if (qtd >= parseInt(i.numeros)) {
            percentDesconto = i.desconto
        }
    })


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
    var refInterval = window.setInterval('updateRandomParticipan()', 1000 * 60 * 1); // 30 seconds

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

}

function getNumbers() {

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        dataType: 'json',
        url: ROUTES.getRafflesAjax,
        data: {
            idProductURL: productData.id
        },
        success: function (data) {
            document.getElementById("raffles").innerHTML = data.join('');
            $("#message-raffles").hide();
        },
    });


}

function validarQtd() {
    var qtd = parseInt(document.getElementById('numbersA').value);
    var disponivel = avaliableNums;
    if (qtd > disponivel) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Quantidade indisponível!',
            footer: 'Disponível: ' + disponivel
        });
    } else {
        openModalCheckout();
    }
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


    numerosAleatorio();
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

function hasCartId(obj) {
    if (obj && obj.data && typeof obj.data.id === 'number') {
        return true;
    }
    return false;
}

function addRm(qtyOrList) {
    let url = ROUTES.cart_add_rm;
    let data = {
        'product_id': productData.id,
        'uuid': cartUuid,
        'qty_or_list': qtyOrList
    }
    var callback = function (data) {
        if (hasCartId(data)) {
            var cartResponseData=data.data;
            $("#payment").html(cartResponseData.view)
            $("#payment").show();

            $("#numbersA").val(cartResponseData.random_numbers);
        }
    };
    return sendAjaxPostData(url, data, callback);
}