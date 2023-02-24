const sortSelect = document.querySelectorAll(".sort");

sortSelect.forEach((slct) =>
    slct.addEventListener("change", (e) => {
        e.target.parentElement.submit();
    })
);
