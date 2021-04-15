const selects = document.getElementsByClassName("profile-select-items")[0]
  .children;

window.onload = () => {
  selects[0].classList.add("active-data");
  document
    .getElementsByClassName(selects[0].dataset.section)[0]
    .classList.add("active-data-list");
};

Array.from(selects).forEach((el) => {
  el.onclick = () => {
    document
      .getElementsByClassName("active-data")[0]
      .classList.remove("active-data");
    document
      .getElementsByClassName("active-data-list")[0]
      .classList.remove("active-data-list");

    el.classList.add("active-data");
    document
      .getElementsByClassName(el.dataset.section)[0]
      .classList.add("active-data-list");
  };
});
