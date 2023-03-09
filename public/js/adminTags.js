const createTagBtn = document.querySelector(".create-tag"),
    editTagBtn = document.querySelectorAll(".edit-btn"),
    bgContainer = document.querySelector(".bg-container"),
    closeCreateForm = document.querySelector(".create-close"),
    closeEditForm = document.querySelector(".edit-close"),
    createForm = document.querySelector(".create-form"),
    editForm = document.querySelector(".edit-form"),
    editTagName = document.querySelector("#tagName");

let tagUuid;

createTagBtn.addEventListener("click", () => {
    showModal(createForm);
});

closeCreateForm.addEventListener("click", () => hideModal(createForm));

editTagBtn.forEach((btn) => {
    btn.addEventListener("click", () => {
        showModal(editForm);
        let row = btn.parentNode.parentNode;
        document
            .querySelector("#tagName")
            .setAttribute("value", row.children[1].innerHTML);
        tagUuid = btn.dataset.id;

        editForm.action = editForm.action.replace("name", tagUuid);
    });
});

closeEditForm.addEventListener("click", () => {
    hideModal(editForm);

    editForm.action = editForm.action.replace(tagUuid, "name");

    editTagName.setAttribute("value", "");
    editTagName.classList.remove("form-input-error");
    editTagName.parentNode.removeChild(editTagName.nextSibling.nextSibling);
});

function showModal(form) {
    document.body.classList.add("h-screen", "overflow-hidden");
    bgContainer.classList.replace("hidden", "grid");
    form.classList.replace("hidden", "flex");
}

function hideModal(form) {
    document.body.classList.remove("h-screen", "overflow-hidden");
    bgContainer.classList.replace("grid", "hidden");
    form.classList.replace("flex", "hidden");
}
