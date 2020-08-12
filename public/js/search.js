const input = document.getElementById("search-input");
const button = document.getElementById("search-button");

input.onkeyup = () => changeButton();

button.onclick = () => (window.location = `/search/${input.value}`);

function changeButton() {
  button.disabled = input.value ? false : true;
}
