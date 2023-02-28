const tagInput = document.querySelector("#tags"),
    tagList = document.querySelector(".list"),
    tagResults = document.querySelector(".results"),
    articleForm = document.querySelector("form#article-create");

let typingTimer,
    usedTags = [],
    allTags = [],
    removeBtns;

document
    .querySelectorAll(".tag-suggestion")
    .forEach((i) => allTags.push(i.innerHTML));

tagInput.addEventListener("focus", (e) => {
    tagResults.classList.remove("h-0");
    let q = e.target.value;
    showResults(
        allTags.filter((tag) => tag.includes(q) && !usedTags.includes(tag))
    );
});

tagInput.addEventListener("keyup", (e) => {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => {
        let q = e.target.value;

        showResults(
            allTags.filter((tag) => tag.includes(q) && !usedTags.includes(tag))
        );
    }, 150);
});

tagInput.parentElement.addEventListener("blur", () => {
    tagResults.classList.add("h-0");
});

function showResults(data) {
    let content = "";

    data.forEach((tag) => {
        content += `<p class="tag-suggestion">${tag}</p>`;
    });

    tagResults.innerHTML = content;

    let tagOptions = document.querySelectorAll(".tag-suggestion");

    tagOptions.forEach((tag) => {
        tag.addEventListener("click", () => {
            let q = tagInput.value || "";
            optionPicked(tag);
            usedTags.push(tag.innerHTML);
            showResults(
                allTags.filter((t) => t.includes(q) && !usedTags.includes(t))
            );
        });
    });
}

function optionPicked(tag) {
    tagInput.value = "";
    addTag(tag.innerHTML);
}

function addTag(name) {
    let hiddenInp = document.createElement("input");
    hiddenInp.type = "hidden";
    hiddenInp.name = "tags[]";
    hiddenInp.value = name;

    articleForm.appendChild(hiddenInp);

    tagList.innerHTML += `<p class="listed-tag" data-name="${name}">
    <svg class="remove-tag h-4 fill-blue-900 cursor-pointer" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm101.8-262.2L295.6 256l62.2 62.2c4.7 4.7 4.7 12.3 0 17l-22.6 22.6c-4.7 4.7-12.3 4.7-17 0L256 295.6l-62.2 62.2c-4.7 4.7-12.3 4.7-17 0l-22.6-22.6c-4.7-4.7-4.7-12.3 0-17l62.2-62.2-62.2-62.2c-4.7-4.7-4.7-12.3 0-17l22.6-22.6c4.7-4.7 12.3-4.7 17 0l62.2 62.2 62.2-62.2c4.7-4.7 12.3-4.7 17 0l22.6 22.6c4.7 4.7 4.7 12.3 0 17z"></path></svg>
    <span>${name}</span>
    </p>`;

    removeBtns = document.querySelectorAll(".remove-tag");

    removeBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            removeTag(btn.parentElement.dataset.name, btn.parentElement);
        });
    });
}

function removeTag(name, tag) {
    tagList.removeChild(tag);
    articleForm.removeChild(
        document.querySelector(`input[type="hidden"][value="${name}"]`)
    );
    usedTags.splice(usedTags.indexOf(name), 1);

    let q = tagInput.value || "";
    showResults(allTags.filter((t) => t.includes(q) && !usedTags.includes(t)));
}
