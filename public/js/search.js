const searchContainer = document.getElementById("searchContainer");

if (searchContainer) {
  searchContainer.addEventListener("click", e => {
    if (e.target.className === "glyphicon glyphicon-search") {
      searchContainer.innerHTML =
        '<div class="searchGroup"><button type="button" class="search" id="searchButton" onclick="data()"><i class="glyphicon glyphicon-search"></i></button><input type="text" class="searchInput" id="searchContent"/></div>';
      searchContainer.classList.add("active");
      document.getElementById("searchContainer").style.flex = "7";
      document.getElementById("nav").style.flex = "2";
    }
  });
}

function data() {
  var queryContent = document.getElementById("searchContent").value;

  fetch("/search/" + queryContent, {
    method: "POST"
  }).then(res => (window.location.href = "/search/" + queryContent));
}
