const reportArticleBtn = document.querySelector(".report-btn"),
    reportCommentBtns = document.querySelectorAll(".report-comment"),
    reportContainer = document.querySelector(".report-container"),
    closeContainer = document.querySelector(".report-container-close"),
    reportForm = document.querySelector(".report-form");

reportArticleBtn.addEventListener("click", () => {
    showModal();
    addHiddenInput(
        "article_slug",
        window.location.pathname.split("articles/")[1]
    );
});

closeContainer.addEventListener("click", () => {
    document.body.classList.remove("h-screen", "overflow-hidden");
    reportContainer.classList.replace("grid", "hidden");
    let inp = document.querySelector(".identifier");

    inp.parentElement.removeChild(inp);
});

reportCommentBtns.forEach((comment) => {
    comment.addEventListener("click", () => {
        showModal();
        addHiddenInput("comment_id", comment.dataset.id);
    });
});

function showModal() {
    document.body.classList.add("h-screen", "overflow-hidden");
    reportContainer.classList.replace("hidden", "grid");
}

function addHiddenInput(name, value) {
    let hiddenInput = document.createElement("input");
    hiddenInput.classList.add("identifier");
    hiddenInput.type = "hidden";
    hiddenInput.name = name;
    hiddenInput.value = value;

    reportForm.appendChild(hiddenInput);
}
