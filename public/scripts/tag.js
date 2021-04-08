const firstLetters = document.getElementsByClassName("tag-letter");

Array.from(firstLetters).forEach((letter) => {
  letter.onclick = () => {
    let currentlyOpen = document.getElementsByClassName("expandLetter")[0];
    if (currentlyOpen && currentlyOpen != letter.parentElement)
      currentlyOpen.parentElement.classList.remove("expandLetter");

    if (letter.parentElement.classList.contains("expandLetter"))
      letter.parentElement.classList.remove("expandLetter");
    else letter.parentElement.classList.add("expandLetter");
  };
});
