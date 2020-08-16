if (window.location.pathname == "/admin/articles") {
  let removes = document.getElementsByClassName("remove-row");
  Array.from(removes).forEach((art) => {
    art.onclick = () => {
      let xhr = new XMLHttpRequest();
      xhr.open("POST", `/admin/article/${art.getAttribute("data-id")}`);
      xhr.onreadystatechange = () => {
        if (xhr.readyState == 4) {
          if (xhr.status == 200) {
            document
              .getElementsByClassName("articles-table")[0]
              .children[0].removeChild(art.parentElement.parentElement);
          } else {
            alert("Error occurred. Check console and contact developer.");
            let parser = new DOMParser();
            console.error(
              parser
                .parseFromString(xhr.responseText, "text/html")
                .getElementsByTagName("title")[0].innerText
            );
          }
        }
      };
      xhr.send();
    };
  });
}

if (window.location.pathname == "/admin/comments") {
  let removes = document.getElementsByClassName("remove-row");
  Array.from(removes).forEach((comm) => {
    comm.onclick = () => {
      let xhr = new XMLHttpRequest();
      xhr.open("POST", `/admin/comment/${comm.getAttribute("data-id")}`);
      xhr.onreadystatechange = () => {
        if (xhr.readyState == 4) {
          if (xhr.status == 200) {
            document
              .getElementsByClassName("comments-table")[0]
              .children[0].removeChild(comm.parentElement.parentElement);
          } else {
            alert("Error occurred. Check console and contact developer.");
            let parser = new DOMParser();
            console.error(
              parser
                .parseFromString(xhr.responseText, "text/html")
                .getElementsByTagName("title")[0].innerText
            );
          }
        }
      };
      xhr.send();
    };
  });
}

if (window.location.pathname == "/admin/users") {
  let removes = document.getElementsByClassName("remove-row");
  Array.from(removes).forEach((usr) => {
    usr.onclick = () => {
      let xhr = new XMLHttpRequest();
      xhr.open("POST", `/admin/user/${usr.getAttribute("data-id")}/delete`);
      xhr.onreadystatechange = () => {
        if (xhr.readyState == 4) {
          if (xhr.status == 200) {
            document
              .getElementsByClassName("users-table")[0]
              .children[0].removeChild(usr.parentElement.parentElement);
          } else {
            alert("Error occurred. Check console and contact developer.");
            let parser = new DOMParser();
            console.error(
              parser
                .parseFromString(xhr.responseText, "text/html")
                .getElementsByTagName("title")[0].innerText
            );
          }
        }
      };
      xhr.send();
    };
  });
}
