let imgDisp = document.getElementsByClassName("img-disp")[0];
let close = document.getElementsByClassName("close")[0];
let imgCont = document.getElementsByClassName("img-prev")[0];
let avalaibleImgs = document.getElementsByClassName("post-img");

Array.from(avalaibleImgs).forEach((img) => {
  img.onclick = () => {
    imgCont.setAttribute(
      "src",
      `/images/postsImages/${img.getAttribute("data-img")}`
    );
    imgDisp.style.display = "flex";
    close.onclick = () => {
      imgCont.removeAttribute("src");
      imgDisp.style.display = "none";
    };
  };
});
