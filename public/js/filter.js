let sorters = document.getElementsByClassName("filter-by");

Array.from(sorters).forEach((sort) => {
  sort.onclick = () =>
    changeFilter(
      sort.getAttribute("data-by"),
      sort.children[0] ? sort.children[0].getAttribute("data-way") : null,
      window.location.pathname
    );
});

function changeFilter(by, way, path) {
  window.location = `${path}?by=${by}&way=${way == null ? "ASC" : "DESC"}`;
}
