let password = document.getElementsByClassName("pass")[0];
let show = document.getElementsByClassName("showBtn")[0];

show.onclick = () => showPassword();

function showPassword() {
  password.getAttribute("type") == "password"
    ? password.setAttribute("type", "text")
    : password.setAttribute("type", "password");
}
