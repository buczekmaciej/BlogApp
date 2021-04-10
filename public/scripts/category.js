const icon = `<svg class='loading-icon' stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 16 16" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.5 3a2.5 2.5 0 015 0v9a1.5 1.5 0 01-3 0V5a.5.5 0 011 0v7a.5.5 0 001 0V3a1.5 1.5 0 10-3 0v9a2.5 2.5 0 005 0V5a.5.5 0 011 0v7a3.5 3.5 0 11-7 0V3z" clip-rule="evenodd"></path></svg>`,
  categoriesList = document.getElementsByClassName("categories-list")[0];

let currentLetter, data;

window.onload = () => {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", `/categories/get`);
  xhr.onreadystatechange = () => {
    if (xhr.readyState === 4) {
      if (xhr.status === 200 || xhr.status === 404) {
        if (xhr.status === 200) {
          data = JSON.parse(xhr.response);
          currentLetter = Object.keys(data)[0];
          createCategories();
          initLettersClick();
        } else {
          categoriesList.innerHTML = JSON.parse(xhr.response);
        }
      } else {
        alert("Something went wrong. Try again.");
      }
    }
  };
  xhr.send();
};

function createCategories() {
  let inner = "";

  inner += `<div class="starts-with-box">`;
  for (let [letter, row] of Object.entries(data)) {
    if (letter == currentLetter) {
      inner += `<span class="letter active-letter">${letter.toUpperCase()}</span>`;
    } else {
      inner += `<span class="letter">${letter.toUpperCase()}</span>`;
    }
  }
  inner += `</div>`;

  inner += `<div class="categories-container">`;
  for (let [letter, row] of Object.entries(data[currentLetter])) {
    inner += `<div class="category-box">
    <a href="" class="category-name">${
      row.name[0].toUpperCase() + row.name.slice(1)
    }</a>`;

    if (localStorage.getItem("isAdmin") === "true") {
      inner += `<a href="${
        window.location.href + row.id + "/edit"
      }" class="category-edit-link">
      <svg class="edit-icon" stroke="currentColor" fill="currentColor" stroke-width="0" viewbox="0 0 16 16" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M11.293 1.293a1 1 0 011.414 0l2 2a1 1 0 010 1.414l-9 9a1 1 0 01-.39.242l-3 1a1 1 0 01-1.266-1.265l1-3a1 1 0 01.242-.391l9-9zM12 2l2 2-9 9-3 1 1-3 9-9z" clip-rule="evenodd"></path>
        <path fill-rule="evenodd" d="M12.146 6.354l-2.5-2.5.708-.708 2.5 2.5-.707.708zM3 10v.5a.5.5 0 00.5.5H4v.5a.5.5 0 00.5.5H5v.5a.5.5 0 00.5.5H6v-1.5a.5.5 0 00-.5-.5H5v-.5a.5.5 0 00-.5-.5H3z" clip-rule="evenodd"></path>
      </svg>
    </a>
  </div>`;
    }
  }
  inner += `</div>`;

  categoriesList.innerHTML = inner;
  initLettersClick();
}

function initLettersClick() {
  let letters = document.getElementsByClassName("letter");

  Array.from(letters).forEach((letter) => {
    letter.onclick = () => {
      currentLetter = letter.innerHTML.toLowerCase();
      createCategories();
    };
  });
}
