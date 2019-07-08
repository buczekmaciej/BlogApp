const save = document.getElementById("saveChanges");

if (save) {
  save.addEventListener("click", e => {
    const login = document.getElementById("login").value;
    const mail = document.getElementById("newMail").value;
    const name = document.getElementById("newName").value;
    const bday = document.getElementById("newBday").value;
    const location = document.getElementById("newLocation").value;
    const bio = document.getElementById("newBio").value;

    var data = [mail, name, bday, location, bio];
    var fd = new FormData();
    for (var i in data) {
      fd.append(i, data[i]);
    }
    alert(data);
    alert(fd);

    fetch("/" + login + "/changes/save", {
      method: "POST",
      body: fd
    })
      .then(function(response) {
        return response.text();
      })
      .then(function(body) {
        console.log(body);
      });
  });
}
