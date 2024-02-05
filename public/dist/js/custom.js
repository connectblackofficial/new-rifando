// DARK MODE

const body = document.querySelector('body');
const button = document.querySelector('#darkbutton')


function toggleDark() {
  if (body.classList.contains('dark-mode')) {
    body.classList.remove('dark-mode');
    localStorage.setItem("theme", "light");
    button.classList.remove('fa-sun')
    button.classList.add('fa-moon')
  } else {
    body.classList.add('dark-mode');
    localStorage.setItem("theme", "dark");
    button.classList.remove('fa-moon')
    button.classList.add('fa-sun')
  }
}

if (localStorage.getItem("theme") === "dark") {
  body.classList.add('dark-mode');
}

document.querySelector('#darkbutton').addEventListener('click', toggleDark);

// SIDEBAR ICON CHANGE

const sidebtn = document.querySelector('#sideBtnIcon')

function changeIcon() {
    sidebtn.classList.toggle('fa-bars')
    sidebtn.classList.toggle('fa-xmark')
}

document.querySelector('#sideBtn').addEventListener('click', changeIcon);

// SIDEBAR

$(function(e) {
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
})

var url_atual = window.location.pathname;

if (url_atual == '/dashboard') {
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

