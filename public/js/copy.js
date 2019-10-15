const linkRef = document.getElementsByClassName("fa-link");
if (linkRef) {
  for (var i = 0; i < linkRef.length; i++) {
    linkRef[i].addEventListener("click", function(i) {
      let copiedPath = i.path[0].title;
      navigator.clipboard.writeText(copiedPath);
    });
  }
}
