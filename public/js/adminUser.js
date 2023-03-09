const editRolesBtn = document.querySelectorAll(".edit-btn"),
    bgContainer = document.querySelector(".bg-container"),
    closeEditForm = document.querySelector(".edit-close"),
    rolesForm = document.querySelector(".roles-form"),
    writerCheckbox = document.querySelector("#writer"),
    adminCheckbox = document.querySelector("#admin");

let role, owner, username;

editRolesBtn.forEach((btn) => {
    btn.addEventListener("click", () => {
        let row = btn.parentNode.parentNode;

        username = btn.dataset.username;
        role = row.children[3].innerHTML;
        owner = row.children[1].innerHTML;

        showModal();
    });
});

closeEditForm.addEventListener("click", () => hideModal());

function showModal() {
    rolesForm.action = rolesForm.action.replace("username", username);
    document.querySelector(".role-owner").textContent = owner;
    if (role === "Writer") writerCheckbox.setAttribute("checked", true);
    if (role === "Admin") {
        writerCheckbox.setAttribute("checked", true);
        adminCheckbox.setAttribute("checked", true);
    }
    document.body.classList.add("h-screen", "overflow-hidden");
    bgContainer.classList.replace("hidden", "grid");
    rolesForm.classList.replace("hidden", "flex");
}

function hideModal() {
    document.body.classList.remove("h-screen", "overflow-hidden");
    bgContainer.classList.replace("grid", "hidden");
    rolesForm.classList.replace("flex", "hidden");
    writerCheckbox.setAttribute("checked", false);
    adminCheckbox.setAttribute("checked", false);
    writerCheckbox.removeAttribute("checked");
    adminCheckbox.removeAttribute("checked");
    rolesForm.action = rolesForm.action.replace(username, "username");
}
