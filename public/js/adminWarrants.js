const createWarrantBtn = document.querySelector(".create-btn"),
    editWarrantBtn = document.querySelectorAll(".edit-btn"),
    bgContainer = document.querySelector(".bg-container"),
    closeCreateForm = document.querySelector(".create-close"),
    closeEditForm = document.querySelector(".edit-close"),
    createForm = document.querySelector(".create-form"),
    editForm = document.querySelector(".edit-form"),
    editFormWarrantUuid = document.querySelector("#warrant_uuid_edit"),
    editReasons = document.querySelector("#reason");

let warrantUuid;

createWarrantBtn.addEventListener("click", () => {
    showModal(createForm);
});

closeCreateForm.addEventListener("click", () => hideModal(createForm));

editWarrantBtn.forEach((btn) => {
    btn.addEventListener("click", () => {
        let row = btn.parentNode.parentNode;
        editFormWarrantUuid.value = row.children[0].innerHTML;
        [...editReasons.children].forEach((option, ind) => {
            if (option.value === row.children[1].innerHTML) {
                editReasons.selectedIndex = ind;
                editReasons.options[ind].selected = true;
                editReasons.value = option.value;
            }
        });
        warrantUuid = btn.dataset.id;

        editForm.action = editForm.action.replace(
            "warrant/",
            warrantUuid + "/"
        );
        showModal(editForm);
    });
});

closeEditForm.addEventListener("click", () => {
    hideModal(editForm);

    editForm.action = editForm.action.replace(warrantUuid + "/", "warrant/");

    editFormWarrantUuid.value = "";
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
