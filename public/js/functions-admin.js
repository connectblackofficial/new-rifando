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
}