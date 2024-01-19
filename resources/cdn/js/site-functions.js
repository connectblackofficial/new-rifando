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
    window.open('https://api.whatsapp.com/send?phone='+user_phone, '_blank');
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