const articleImages = document.getElementsByClassName("article-image");

if (articleImages.length > 0) {
  Array.from(articleImages).forEach((img) => {
    img.onclick = () => {
      let container = document.createElement("div");
      container.classList.add("image-show-container");

      container.innerHTML = `<svg class="close-icon" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 16 16" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 010 .708l-7 7a.5.5 0 01-.708-.708l7-7a.5.5 0 01.708 0z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 000 .708l7 7a.5.5 0 00.708-.708l-7-7a.5.5 0 00-.708 0z" clip-rule="evenodd"></path></svg><img src="/uploads/${img.dataset.file}">`;
      document.body.insertBefore(container, document.body.children[0]);
      document.body.setAttribute(
        "style",
        "height:100vh;width:100%;overflow:hidden;"
      );

      document.getElementsByClassName("close-icon")[0].onclick = () => {
        document.body.removeChild(container);
        document.body.removeAttribute("style");
      };
    };
  });
}
