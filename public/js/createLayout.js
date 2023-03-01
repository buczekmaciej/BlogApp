const boldBtn = document.querySelector(".bold-text"),
    italicBtn = document.querySelector(".italic-text"),
    linkBtn = document.querySelector(".link-text"),
    imgBtn = document.querySelector(".place-img"),
    textarea = document.querySelector("textarea");

let parts, start, end;

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
