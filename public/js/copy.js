let avalaibleComments = document.querySelectorAll(".avalaible");

avalaibleComments.forEach((comment) => {
    comment.addEventListener("click", () => copy(comment.dataset.uuid));
});

let copy = (uuid) => {
    navigator.clipboard.writeText(uuid).then(() => {
        alert("Copied uuid for comment");
    });
};
