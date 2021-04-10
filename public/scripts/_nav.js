if (!localStorage.getItem("isAdmin")) {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "/isAdmin");
  xhr.onreadystatechange = () => {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        if (JSON.parse(xhr.response) !== "Not logged") {
          localStorage.setItem("isAdmin", xhr.response);
        }
      }
    }
  };
  xhr.send();
}

if (document.getElementsByClassName("logout-link")[0])
  document.getElementsByClassName("logout-link")[0].onclick = () => {
    localStorage.clear();
  };
