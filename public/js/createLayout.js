const boldBtn = document.querySelector(".bold-text"),
    italicBtn = document.querySelector(".italic-text"),
    linkBtn = document.querySelector(".link-text"),
    imgBtn = document.querySelector(".place-img"),
    textarea = document.querySelector("textarea"),
    tagInput = document.querySelector("#tags"),
    tagList = document.querySelector(".list"),
    tagResults = document.querySelector(".results"),
    articleForm = document.querySelector("form#article-create");

let typingTimer,
    usedTags = [],
    allTags = [],
    removeBtns,
    parts,
    start,
    end;

function splitWithSelection() {
    start = textarea.selectionStart;
    end = textarea.selectionEnd;

    return {
        before: textarea.value.slice(0, start),
        selectedText: textarea.value.slice(start, end),
        after: textarea.value.slice(end),
    };
}

boldBtn.addEventListener("click", () => {
    parts = splitWithSelection();

    if (parts.selectedText !== "") {
        textarea.value =
            parts.before + `**${parts.selectedText}**` + parts.after;
    }
});

italicBtn.addEventListener("click", () => {
    parts = splitWithSelection();

    if (parts.selectedText !== "") {
        textarea.value = parts.before + `*${parts.selectedText}*` + parts.after;
    }
});

linkBtn.addEventListener("click", () => {
    parts = splitWithSelection();

    if (parts.selectedText !== "") {
        textarea.value =
            parts.before +
            `[<span class="text-blue-900 font-bold">${parts.selectedText}</span>](Link here)` +
            parts.after;
    }
});

imgBtn.addEventListener("click", () => {
    parts = splitWithSelection();

    if (parts.selectedText === "") {
        textarea.value =
            parts.before +
            `\n![Alt text](File name "Image title")\n` +
            parts.after;
    }
});

document
    .querySelectorAll(".tag-suggestion")
    .forEach((i) => allTags.push(i.innerHTML));

tagInput.addEventListener("focus", (e) => {
    tagResults.classList.remove("h-0");
    document.addEventListener("click", (e) => {
        let isDescendant = (parent, child) => {
            let node = child.parentNode;
            while (node) {
                if (node === parent) {
                    return true;
                }
                node = node.parentNode;
            }
            return false;
        };

        if (
            !tagResults.classList.contains("h-0") &&
            !isDescendant(tagResults.parentElement, e.target)
        ) {
            tagResults.classList.add("h-0");
        }
    });
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
    addTag(tag.innerHTML);
}

function addTag(name) {
    let hiddenInp = document.createElement("input");
    hiddenInp.type = "hidden";
    hiddenInp.name = "tags[]";
    hiddenInp.value = name;

    articleForm.appendChild(hiddenInp);

    tagList.innerHTML += `<p data-name="${name}">
    <svg class="remove-tag h-4 cursor-pointer" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm101.8-262.2L295.6 256l62.2 62.2c4.7 4.7 4.7 12.3 0 17l-22.6 22.6c-4.7 4.7-12.3 4.7-17 0L256 295.6l-62.2 62.2c-4.7 4.7-12.3 4.7-17 0l-22.6-22.6c-4.7-4.7-4.7-12.3 0-17l62.2-62.2-62.2-62.2c-4.7-4.7-4.7-12.3 0-17l22.6-22.6c4.7-4.7 12.3-4.7 17 0l62.2 62.2 62.2-62.2c4.7-4.7 12.3-4.7 17 0l22.6 22.6c4.7 4.7 4.7 12.3 0 17z"></path></svg>
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

if (
    ["article", "edit", "layout"].every((el) =>
        window.location.pathname.includes(el)
    )
) {
    window.addEventListener("load", () => {
        removeBtns = document.querySelectorAll(".remove-tag");

        removeBtns.forEach((btn) => {
            btn.addEventListener("click", () => {
                allTags.push(btn.parentElement.dataset.name);
                allTags.sort();
                removeTag(btn.parentElement.dataset.name, btn.parentElement);
            });
        });
    });
}
