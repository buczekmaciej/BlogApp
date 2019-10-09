const searchContainer = document.getElementById("searchContainer");

if (searchContainer) {
  searchContainer.addEventListener("click", e => {
    if (e.target.className === "fas fa-search") {
      searchContainer.innerHTML =
        '<div class="searchGroup"><button type="button" class="search" id="searchButton"><i class="fas fa-search"></i></button><input type="text" class="searchInput" id="searchContent"/></div>';
      searchContainer.classList.add("active");
      document.getElementById("searchContainer").style.flex = "7";
      document.getElementById("nav").style.flex = "2";

      const searchButt = document.getElementById("searchButton");
      if (searchButt) {
        searchButt.addEventListener("click", function() {
          data();
        });
      }
      const searGrp = document.getElementsByClassName("searchGroup")[0];
      if (searGrp) {
        const searInp = document.getElementsByClassName("searchInput")[0];
        searInp.addEventListener("keydown", e => {
          if (e.keyCode == 13) {
            data();
            return false;
          }
        });
      }
    }
  });
}

function data() {
  var queryContent = document.getElementById("searchContent").value;

  if (!queryContent) {
    alert("Fill data you want to query for");
  } else {
    fetch("/search/" + queryContent, {
      method: "POST"
    }).then(res => (window.location.href = "/search/" + queryContent));
  }
}
