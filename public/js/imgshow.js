const linkDiv = document.getElementsByClassName("image-link")[0];

if (linkDiv) {
  const linkImg = document.getElementsByClassName("link-img")[0];
  const imgName = linkImg.getAttribute("data-id");

  const imgDisp = document.createElement("div");
  imgDisp.classList.add("show-img");

  var closeDisp = document.createElement("p");
  closeDisp.classList.add("show-close");
  closeDisp.innerText = "X";

  imgDisp.appendChild(closeDisp);

  var dispCont = document.createElement("img");
  dispCont.classList.add("show-cont");
  dispCont.src = "../images/postsImages/" + imgName;

  imgDisp.appendChild(dispCont);

  linkImg.addEventListener("click", function() {
    document.body.style.height = "100vh";
    document.body.appendChild(imgDisp);
  });

  closeDisp.addEventListener("click", function() {
    document.body.style.height = null;
    document.body.removeChild(imgDisp);
  });
}
