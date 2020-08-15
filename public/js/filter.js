let sorters = document.getElementsByClassName("filter-by");

Array.from(sorters).forEach((sort) => {
  sort.onclick = () =>
    changeFilter(
      sort.getAttribute("data-by"),
      sort.children[0] ? sort.children[0].getAttribute("data-way") : null
    );
});

function changeFilter(by, way) {
  window.location = `/admin/articles?by=${by}&way=${
    way == null ? "ASC" : "DESC"
  }`;
}
