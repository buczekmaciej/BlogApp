const filesInput = document.querySelector(".files"),
    previewsBox = document.querySelector(".preview-list"),
    previewText = previewsBox.parentElement.children[0];

filesInput.addEventListener("change", () => {
    let inpFiles = [...filesInput.files];
    inpFiles.forEach((file) => {
        if (file.size > 5000000) {
            alert("Picked too big file");
            window.location.reload();
        } else {
            previewsBox.innerHTML +=
                "<img src='" + URL.createObjectURL(file) + "' />";
        }
    });
    if (previewText.classList.contains("hidden")) {
        previewText.classList.remove("hidden");
    }

    if (inpFiles.length === 0) {
        previewsBox.innerHTML = "";
        previewText.classList.add("hidden");
    }
});
