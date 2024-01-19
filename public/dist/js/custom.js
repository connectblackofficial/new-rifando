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

