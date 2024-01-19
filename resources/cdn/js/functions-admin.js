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

function toggleTelefone(id){
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
