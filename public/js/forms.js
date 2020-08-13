if (
  window.location.pathname == "/u/login" ||
  window.location.pathname == "/u/register"
) {
  let show = document.getElementsByClassName("showBtn")[0];

  show.onclick = () => showPassword();

  function showPassword() {
    let password = document.getElementsByClassName("pass")[0];

    password.getAttribute("type") == "password"
      ? password.setAttribute("type", "text")
      : password.setAttribute("type", "password");
  }

  if (window.location.pathname == "/u/login") {
    let username = document.getElementById("username");
    let password = document.getElementById("password");

    username.onkeyup = () => checkLogin(username.value, password.value);
    password.onkeyup = () => checkLogin(username.value, password.value);
  }

  if (window.location.pathname == "/u/register") {
    let username = document.getElementById("register_username");
    let password = document.getElementById("register_password");
    let mail = document.getElementById("register_email");

    username.onkeyup = () =>
      checkRegister(username.value, password.value, mail.value);
    password.onkeyup = () =>
      checkRegister(username.value, password.value, mail.value);
    mail.onkeyup = () =>
      checkRegister(username.value, password.value, mail.value);
  }

  function checkLogin(usr, pass) {
    if (usr && pass) document.getElementById("submit-login").disabled = false;
    else document.getElementById("submit-login").disabled = true;
  }

  function checkRegister(usr, pass, mail) {
    if (usr && pass && mail)
      document.getElementById("register_submit").disabled = false;
    else document.getElementById("register_submit").disabled = true;
  }
}

if (window.location.pathname.toLowerCase().indexOf("article/") !== -1) {
  let commentInp = document.getElementsByClassName("comment-input")[0];
  let commentBtn = document.getElementsByClassName("comment-submit")[0];

  commentInp.onkeyup = () => checkContent();

  function checkContent() {
    commentBtn.disabled = commentInp.value ? false : true;
  }
}

if (window.location.pathname == "/new-article") {
  let title = document.getElementById("article_title");
  let content = document.getElementById("article_content");

  title.onkeyup = () => checkForm(title.value, content.value);
  content.onkeyup = () => checkForm(title.value, content.value);

  function checkForm(title, content) {
    if (title && content)
      document.getElementById("article_submit").disabled = false;
    else document.getElementById("article_submit").disabled = true;
  }
}
